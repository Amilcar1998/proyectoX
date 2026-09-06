<?php
require_once __DIR__ . '/../db/conexion.php';

if (!class_exists('PromocionModel')) {
    class PromocionModel extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Revisa y finaliza automáticamente las promociones cuya fecha de fin ya expiró
         * @return int Cantidad de promociones finalizadas
         */
        public function actualizarPromocionesExpiradas(): int
        {
            $consultaExpiradas = "
                SELECT idReceta, PrecioUnitario, precio_anterior, precio_base_regular 
                FROM receta 
                WHERE en_promocion = 1 
                  AND fecha_fin_promo IS NOT NULL 
                  AND NOW() > fecha_fin_promo
            ";
            $res = $this->con->query($consultaExpiradas);
            $totalExpiradas = 0;

            if ($res) {
                while ($fila = $res->fetch_assoc()) {
                    $id = (int)$fila['idReceta'];
                    $precioRestaurado = (float)($fila['precio_base_regular'] ?: ($fila['precio_anterior'] ?: $fila['PrecioUnitario']));

                    // Restaurar precio base en receta
                    $stmtRestaurar = $this->con->prepare("
                        UPDATE receta 
                        SET PrecioUnitario = ?, 
                            en_promocion = 0, 
                            porcentaje_descuento = 0 
                        WHERE idReceta = ?
                    ");
                    $stmtRestaurar->bind_param("di", $precioRestaurado, $id);
                    $stmtRestaurar->execute();
                    $stmtRestaurar->close();

                    // Marcar en histórico como finalizada
                    $stmtHist = $this->con->prepare("
                        UPDATE historico_precios_promociones 
                        SET estado = 'finalizada' 
                        WHERE idReceta = ? AND tipo_cambio = 'promocion' AND estado = 'activa'
                    ");
                    $stmtHist->bind_param("i", $id);
                    $stmtHist->execute();
                    $stmtHist->close();

                    $totalExpiradas++;
                }
            }

            return $totalExpiradas;
        }

        /**
         * Lista todas las recetas con sus ingredientes, estado de precio y promoción
         * @return array
         */
        public function listarRecetas(): array
        {
            $this->actualizarPromocionesExpiradas();

            $consulta = "
                SELECT r.idReceta, r.nombreReceta, r.PrecioUnitario, 
                       r.precio_base_regular, r.precio_anterior, r.en_promocion, 
                       r.porcentaje_descuento, r.fecha_inicio_promo, r.fecha_fin_promo,
                       CASE 
                           WHEN r.en_promocion = 1 AND (r.fecha_inicio_promo IS NULL OR NOW() >= r.fecha_inicio_promo) AND (r.fecha_fin_promo IS NULL OR NOW() <= r.fecha_fin_promo) THEN 1 
                           ELSE 0 
                       END AS promo_activa_momento,
                       GROUP_CONCAT(DISTINCT CONCAT(mp.NombreMP, ' (', dr.cantidaSa, ' lb)') ORDER BY dr.idDetalleReceta ASC SEPARATOR ', ') AS formula,
                       GROUP_CONCAT(DISTINCT mp.NombreMP ORDER BY dr.idDetalleReceta ASC SEPARATOR ', ') AS materias_primas
                FROM receta r
                LEFT JOIN detallereceta dr ON r.idReceta = dr.IdReceta
                LEFT JOIN materiaprima mp ON dr.idMateriaPrima = mp.idMateriaPrima
                GROUP BY r.idReceta, r.nombreReceta, r.PrecioUnitario, r.precio_base_regular, r.precio_anterior, r.en_promocion, r.porcentaje_descuento, r.fecha_inicio_promo, r.fecha_fin_promo
                ORDER BY r.idReceta ASC
            ";
            $resultado = $this->con->query($consulta);
            $recetas = [];

            if ($resultado) {
                while ($fila = $resultado->fetch_assoc()) {
                    $recetas[] = $fila;
                }
            }

            return $recetas;
        }

        /**
         * Obtiene una receta por su identificador
         * @param int $idReceta
         * @return array|null
         */
        public function obtenerPorId(int $idReceta): ?array
        {
            $stmt = $this->con->prepare("SELECT * FROM receta WHERE idReceta = ?");
            $stmt->bind_param("i", $idReceta);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $fila = $resultado->fetch_assoc();
            $stmt->close();
            return $fila ?: null;
        }

        /**
         * Aplica una nivelación de precio de venta directa (sin promoción)
         * @param int $idReceta
         * @param float $nuevoPrecio
         * @param string $motivo
         * @param string $usuario
         * @return bool
         */
        public function aplicarNivelacion(int $idReceta, float $nuevoPrecio, string $motivo, string $usuario): bool
        {
            $receta = $this->obtenerPorId($idReceta);
            if (!$receta || $nuevoPrecio <= 0) {
                return false;
            }

            $precioAnterior = (float)$receta['PrecioUnitario'];

            // Actualizar receta: precio base directo, sin estado de promoción
            $stmt = $this->con->prepare("
                UPDATE receta 
                SET PrecioUnitario = ?, 
                    precio_base_regular = ?, 
                    precio_anterior = NULL, 
                    en_promocion = 0, 
                    porcentaje_descuento = 0, 
                    fecha_inicio_promo = NULL, 
                    fecha_fin_promo = NULL 
                WHERE idReceta = ?
            ");
            $stmt->bind_param("ddi", $nuevoPrecio, $nuevoPrecio, $idReceta);
            $stmt->execute();
            $stmt->close();

            // Registrar en histórico de precios
            $stmtHist = $this->con->prepare("
                INSERT INTO historico_precios_promociones 
                (idReceta, tipo_cambio, precio_anterior, precio_nuevo, porcentaje_descuento, motivo, usuario, estado) 
                VALUES (?, 'nivelacion', ?, ?, 0, ?, ?, 'aplicada')
            ");
            $stmtHist->bind_param("iddss", $idReceta, $precioAnterior, $nuevoPrecio, $motivo, $usuario);
            $stmtHist->execute();
            $stmtHist->close();

            return true;
        }

        /**
         * Crea o actualiza una promoción temporal con fecha de inicio y fin
         * @param int $idReceta
         * @param array $datosPromocion
         * @param string $usuario
         * @return bool
         */
        /**
         * Obtiene la promoción activa actual en el sistema si existe
         * @param int|null $excluirIdReceta Si se especifica, excluye esta receta
         * @return array|null
         */
        public function obtenerPromocionActiva(?int $excluirIdReceta = null): ?array
        {
            $this->actualizarPromocionesExpiradas();

            $sql = "
                SELECT r.idReceta, r.nombreReceta, r.PrecioUnitario, 
                       r.precio_base_regular, r.precio_anterior, r.porcentaje_descuento, 
                       r.fecha_inicio_promo, r.fecha_fin_promo
                FROM receta r
                WHERE r.en_promocion = 1
                  AND (r.fecha_inicio_promo IS NULL OR NOW() >= r.fecha_inicio_promo)
                  AND (r.fecha_fin_promo IS NULL OR NOW() <= r.fecha_fin_promo)
            ";
            if ($excluirIdReceta !== null) {
                $sql .= " AND r.idReceta != " . (int)$excluirIdReceta;
            }
            $sql .= " ORDER BY r.idReceta ASC LIMIT 1";

            $res = $this->con->query($sql);
            if ($res && $fila = $res->fetch_assoc()) {
                return $fila;
            }
            return null;
        }

        /**
         * Crea o actualiza una promoción temporal con fecha de inicio y fin
         * Garantiza que solo exista UNA única promoción activa en todo el sistema
         * @param int $idReceta
         * @param array $datosPromocion
         * @param string $usuario
         * @return bool
         */
        public function crearPromocionTemporal(int $idReceta, array $datosPromocion, string $usuario): bool
        {
            $receta = $this->obtenerPorId($idReceta);
            if (!$receta) {
                return false;
            }

            $precioOferta = (float)($datosPromocion['precio_oferta'] ?? 0);
            $precioRegular = (float)($datosPromocion['precio_regular'] ?: ($receta['precio_base_regular'] ?: $receta['PrecioUnitario']));
            $fechaInicio = !empty($datosPromocion['fecha_inicio']) ? $datosPromocion['fecha_inicio'] : date('Y-m-d H:i:s');
            $fechaFin = !empty($datosPromocion['fecha_fin']) ? $datosPromocion['fecha_fin'] : date('Y-m-d H:i:s', strtotime('+7 days'));
            $motivo = $datosPromocion['motivo'] ?? 'Promoción por campaña comercial';

            if ($precioOferta <= 0 || $precioRegular <= $precioOferta) {
                return false;
            }

            $porcentaje = (int)round((($precioRegular - $precioOferta) / $precioRegular) * 100);

            // Regla de Negocio: Cancelar cualquier otra promoción activa en el sistema
            $resOtras = $this->con->query("
                SELECT idReceta, precio_base_regular, precio_anterior, PrecioUnitario 
                FROM receta 
                WHERE en_promocion = 1 AND idReceta != $idReceta
            ");
            if ($resOtras) {
                while ($otra = $resOtras->fetch_assoc()) {
                    $otraId = (int)$otra['idReceta'];
                    $precioRestaurado = (float)($otra['precio_base_regular'] ?: ($otra['precio_anterior'] ?: $otra['PrecioUnitario']));

                    // Restaurar precio base del producto anterior
                    $stmtRestaurar = $this->con->prepare("
                        UPDATE receta 
                        SET PrecioUnitario = ?, 
                            en_promocion = 0, 
                            porcentaje_descuento = 0, 
                            fecha_inicio_promo = NULL, 
                            fecha_fin_promo = NULL 
                        WHERE idReceta = ?
                    ");
                    $stmtRestaurar->bind_param("di", $precioRestaurado, $otraId);
                    $stmtRestaurar->execute();
                    $stmtRestaurar->close();

                    // Marcar en histórico como cancelada por reemplazo
                    $stmtHistCancel = $this->con->prepare("
                        UPDATE historico_precios_promociones 
                        SET estado = 'cancelada', 
                            motivo = CONCAT(IFNULL(motivo, 'Promoción previa'), ' (Reemplazada por nueva promoción)') 
                        WHERE idReceta = ? AND tipo_cambio = 'promocion' AND estado = 'activa'
                    ");
                    $stmtHistCancel->bind_param("i", $otraId);
                    $stmtHistCancel->execute();
                    $stmtHistCancel->close();
                }
            }

            // Actualizar la receta con la nueva promoción
            $stmt = $this->con->prepare("
                UPDATE receta 
                SET PrecioUnitario = ?, 
                    precio_base_regular = ?, 
                    precio_anterior = ?, 
                    en_promocion = 1, 
                    porcentaje_descuento = ?, 
                    fecha_inicio_promo = ?, 
                    fecha_fin_promo = ? 
                WHERE idReceta = ?
            ");
            $stmt->bind_param("dddissi", $precioOferta, $precioRegular, $precioRegular, $porcentaje, $fechaInicio, $fechaFin, $idReceta);
            $stmt->execute();
            $stmt->close();

            // Desactivar promociones previas de esta misma receta en el histórico
            $stmtCerrar = $this->con->prepare("
                UPDATE historico_precios_promociones 
                SET estado = 'finalizada' 
                WHERE idReceta = ? AND tipo_cambio = 'promocion' AND estado = 'activa'
            ");
            $stmtCerrar->bind_param("i", $idReceta);
            $stmtCerrar->execute();
            $stmtCerrar->close();

            // Registrar la nueva promoción activa en el histórico
            $stmtHist = $this->con->prepare("
                INSERT INTO historico_precios_promociones 
                (idReceta, tipo_cambio, precio_anterior, precio_nuevo, porcentaje_descuento, fecha_inicio, fecha_fin, motivo, usuario, estado) 
                VALUES (?, 'promocion', ?, ?, ?, ?, ?, ?, ?, 'activa')
            ");
            $stmtHist->bind_param("iddiisss", $idReceta, $precioRegular, $precioOferta, $porcentaje, $fechaInicio, $fechaFin, $motivo, $usuario);
            $stmtHist->execute();
            $stmtHist->close();

            return true;
        }

        /**
         * Cancela una promoción activa y restaura el precio regular
         * @param int $idReceta
         * @param string $usuario
         * @return bool
         */
        public function cancelarPromocion(int $idReceta, string $usuario): bool
        {
            $receta = $this->obtenerPorId($idReceta);
            if (!$receta) {
                return false;
            }

            $precioRestaurado = (float)($receta['precio_base_regular'] ?: ($receta['precio_anterior'] ?: $receta['PrecioUnitario']));

            $stmt = $this->con->prepare("
                UPDATE receta 
                SET PrecioUnitario = ?, 
                    en_promocion = 0, 
                    porcentaje_descuento = 0, 
                    fecha_inicio_promo = NULL, 
                    fecha_fin_promo = NULL 
                WHERE idReceta = ?
            ");
            $stmt->bind_param("di", $precioRestaurado, $idReceta);
            $stmt->execute();
            $stmt->close();

            // Marcar en histórico como cancelada
            $stmtHist = $this->con->prepare("
                UPDATE historico_precios_promociones 
                SET estado = 'cancelada' 
                WHERE idReceta = ? AND tipo_cambio = 'promocion' AND estado = 'activa'
            ");
            $stmtHist->bind_param("i", $idReceta);
            $stmtHist->execute();
            $stmtHist->close();

            return true;
        }

        /**
         * Obtiene el historial de nivelaciones y promociones
         * @param int $limite
         * @return array
         */
        public function obtenerHistorial(int $limite = 50): array
        {
            $consulta = "
                SELECT h.*, r.nombreReceta 
                FROM historico_precios_promociones h
                LEFT JOIN receta r ON h.idReceta = r.idReceta
                ORDER BY h.creado_en DESC, h.idHistorico DESC
                LIMIT ?
            ";
            $stmt = $this->con->prepare($consulta);
            $stmt->bind_param("i", $limite);
            $stmt->execute();
            $res = $stmt->get_result();
            $historial = [];

            if ($res) {
                while ($fila = $res->fetch_assoc()) {
                    $historial[] = $fila;
                }
            }
            $stmt->close();
            return $historial;
        }
    }
}
