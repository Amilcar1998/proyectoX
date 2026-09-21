<?php
require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/../models/Pedidos.php';
require_once __DIR__ . '/../models/DetallePedido.php';
require_once __DIR__ . '/../models/DetalleProducto.php';
require_once __DIR__ . '/../models/Cliente.php';

if (!class_exists('ModelClienteIn')) {
    class ModelClienteIn extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Obtiene la información del cliente a partir de su correo/usuario
         */
        public function obtenerClientePorCorreo(string $correo): ?array
        {
            if (empty($correo)) {
                return null;
            }

            $stmt = $this->con->prepare(
                "SELECT c.idPersona AS idCliente, c.idPersona, c.nombrePersona, c.apellidoPersona, c.nombrePersona AS NombreCliente, c.apellidoPersona AS apellidosCliente, c.telefono, c.edad, c.genero, c.idUsuario, u.username
                 FROM persona c
                 INNER JOIN usuarios u ON c.idUsuario = u.idUsuario
                 WHERE u.username = ?
                 LIMIT 1"
            );
            if (!$stmt) {
                return null;
            }

            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $res = $stmt->get_result();
            $cliente = $res->fetch_assoc();
            $stmt->close();

            if ($cliente) {
                return $cliente;
            }

            // Si no existe vinculación exacta, buscar el idUsuario
            $stmtU = $this->con->prepare("SELECT idUsuario, username FROM usuarios WHERE username = ? LIMIT 1");
            if ($stmtU) {
                $stmtU->bind_param("s", $correo);
                $stmtU->execute();
                $usuario = $stmtU->get_result()->fetch_assoc();
                $stmtU->close();

                if ($usuario) {
                    $idUsuario = (int)$usuario['idUsuario'];
                    $stmtC = $this->con->prepare("SELECT p.idPersona AS idCliente, p.idPersona, p.nombrePersona, p.apellidoPersona, p.nombrePersona AS NombreCliente, p.apellidoPersona AS apellidosCliente, p.telefono, p.edad, p.genero, p.idUsuario, COALESCE(p.idEmpresa, u.idEmpresa, 1) AS idEmpresa FROM persona p INNER JOIN usuarios u ON p.idUsuario = u.idUsuario WHERE u.idUsuario = ? LIMIT 1");
                    $stmtC->bind_param("i", $idUsuario);
                    $stmtC->execute();
                    $clienteExistente = $stmtC->get_result()->fetch_assoc();
                    $stmtC->close();

                    if ($clienteExistente) {
                        $clienteExistente['username'] = $correo;
                        return $clienteExistente;
                    }

                    // Crear registro inicial de persona si no existe
                    $nombreDefecto = ucfirst(explode('@', $correo)[0]);
                    $stmtIns = $this->con->prepare("INSERT INTO persona (nombrePersona, apellidoPersona, telefono, edad, genero, idUsuario) VALUES (?, 'Comercial', '0000-0000', 25, 'M', ?)");
                    if ($stmtIns) {
                        $stmtIns->bind_param("si", $nombreDefecto, $idUsuario);
                        $stmtIns->execute();
                        $nuevoId = (int)$this->con->insert_id;
                        $stmtIns->close();

                        return [
                            'idCliente' => $nuevoId,
                            'idPersona' => $nuevoId,
                            'nombrePersona' => $nombreDefecto,
                            'apellidoPersona' => 'Comercial',
                            'NombreCliente' => $nombreDefecto,
                            'apellidosCliente' => 'Comercial',
                            'telefono' => '0000-0000',
                            'edad' => 25,
                            'genero' => 'M',
                            'idUsuario' => $idUsuario,
                            'username' => $correo
                        ];
                    }
                }
            }

            return null;
        }

        /**
         * Obtiene la suscripción o plan de pago activo del usuario
         */
        public function obtenerSuscripcionUsuario(int $idUsuario): ?array
        {
            if ($idUsuario <= 0) {
                return null;
            }

            $stmt = $this->con->prepare(
                "SELECT upp.*, pp.nombrePlan, pp.descripcion AS descripcionPlan, pp.monto AS montoPlan, pp.duracion_dias
                 FROM usuario_plan_pago upp
                 INNER JOIN plan_pago pp ON upp.idPlanPago = pp.idPlanPago
                 WHERE upp.idUsuario = ?
                 ORDER BY (upp.estado = 'activo') DESC, upp.fecha_fin DESC, upp.idUsuarioPlan DESC
                 LIMIT 1"
            );
            if (!$stmt) {
                return null;
            }

            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $res = $stmt->get_result();
            $suscripcion = $res->fetch_assoc();
            $stmt->close();

            if ($suscripcion) {
                if (!empty($suscripcion['fecha_fin'])) {
                    $fin = new DateTime($suscripcion['fecha_fin']);
                    $hoy = new DateTime();
                    $suscripcion['esta_vencida'] = ($hoy > $fin);
                    $interval = $hoy->diff($fin);
                    $suscripcion['dias_restantes'] = ($hoy > $fin) ? -((int)$interval->days) : ((int)$interval->days);
                } else {
                    $suscripcion['esta_vencida'] = false;
                    $suscripcion['dias_restantes'] = 30;
                }
            }

            return $suscripcion;
        }

        /**
         * Obtiene el historial de pagos realizados por el usuario
         */
        public function obtenerPagosPorUsuario(int $idUsuario): array
        {
            if ($idUsuario <= 0) {
                return [];
            }

            $stmt = $this->con->prepare(
                "SELECT p.idPago, p.monto, p.moneda, p.metodo_pago, p.estado, p.referencia, p.descripcion, p.fecha_hora, pp.nombrePlan
                 FROM pagos p
                 LEFT JOIN plan_pago pp ON p.idPlanPago = pp.idPlanPago
                 WHERE p.idUsuario = ?
                 ORDER BY p.idPago DESC"
            );
            if (!$stmt) {
                return [];
            }

            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $res = $stmt->get_result();
            $pagos = [];
            while ($row = $res->fetch_assoc()) {
                $pagos[] = $row;
            }
            $stmt->close();
            return $pagos;
        }

        /**
         * Obtiene todos los pedidos asociados a un cliente
         */
        public function obtenerPedidosPorCliente(int $idCliente): array
        {
            if ($idCliente <= 0) {
                return [];
            }

            $stmt = $this->con->prepare(
                "SELECT p.idPedido, p.fechaPedido, ep.nombreEstado, p.idCliente
                 FROM pedido p
                 LEFT JOIN estadopedido ep ON p.idEstadoPedido = ep.idEstadoPedido
                 WHERE p.idCliente = ?
                 ORDER BY p.idPedido DESC"
            );
            if (!$stmt) {
                return [];
            }

            $stmt->bind_param("i", $idCliente);
            $stmt->execute();
            $res = $stmt->get_result();
            $pedidos = [];
            while ($row = $res->fetch_assoc()) {
                $pedidos[] = $row;
            }
            $stmt->close();
            return $pedidos;
        }

        /**
         * Obtiene el listado de recetas y productos disponibles
         */
        public function obtenerRecetas(int $idEmpresa = 0): array
        {
            $condicion = ($idEmpresa > 0) ? " WHERE r.idEmpresa = " . (int)$idEmpresa . " " : "";
            $sql = "
                SELECT r.idReceta, r.idEmpresa, r.nombreReceta, r.categoriaRubro, r.PrecioUnitario, 
                       r.precio_anterior, r.en_promocion, r.porcentaje_descuento, r.precio_base_regular,
                       rub.nombreRubro, rub.icono AS iconoRubro
                FROM receta r
                LEFT JOIN empresas e ON r.idEmpresa = e.idEmpresa
                LEFT JOIN rubros rub ON e.idRubro = rub.idRubro
                $condicion 
                ORDER BY r.nombreReceta ASC
            ";
            $res = $this->con->query($sql);
            if (!$res) {
                return [];
            }
            $r = [];
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
            return $r;
        }

        /**
         * Obtiene los productos y totales de un pedido
         */
        public function obtenerDetalleProductosPedido(int $idPedido): array
        {
            if ($idPedido <= 0) {
                return [];
            }

            $stmt = $this->con->prepare(
                "SELECT dp.idDetallePedido, dp.cantidad, r.idReceta, r.nombreReceta, 
                        (dp.cantidad * r.PrecioUnitario) AS total_producto
                 FROM detallepedido dp
                 INNER JOIN receta r ON dp.idReceta = r.idReceta
                 WHERE dp.IdPedido = ?
                 ORDER BY dp.idDetallePedido ASC"
            );
            if (!$stmt) {
                return [];
            }

            $stmt->bind_param("i", $idPedido);
            $stmt->execute();
            $res = $stmt->get_result();
            $r = [];
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
            $stmt->close();
            return $r;
        }

        /**
         * Obtiene el último pedido de un cliente
         */
        public function obtenerUltimoPedidoCliente(int $idCliente): array
        {
            if ($idCliente <= 0) {
                return [];
            }

            $stmt = $this->con->prepare("SELECT * FROM pedido WHERE idCliente = ? ORDER BY idPedido DESC LIMIT 1");
            if (!$stmt) {
                return [];
            }
            $stmt->bind_param("i", $idCliente);
            $stmt->execute();
            $res = $stmt->get_result();
            $r = [];
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
            $stmt->close();
            return $r;
        }

        /**
         * Registra un nuevo pedido para el cliente
         */
        public function crearPedido(string $fecha, int $idCliente, int $idEstado = 1, int $idEmpresa = 1): int
        {
            if ($idEmpresa <= 0) $idEmpresa = 1;
            $stmt = $this->con->prepare("INSERT INTO pedido (fechaPedido, idCliente, idEstadoPedido, idEmpresa) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                return 0;
            }
            $stmt->bind_param("siii", $fecha, $idCliente, $idEstado, $idEmpresa);
            $stmt->execute();
            $nuevoId = (int)$this->con->insert_id;
            $stmt->close();
            return $nuevoId;
        }

        /**
         * Agrega un detalle de producto a un pedido
         */
        public function agregarDetallePedido(int $cantidad, int $idReceta, int $idPedido): bool
        {
            $stmt = $this->con->prepare("INSERT INTO detallepedido (cantidad, idReceta, IdPedido) VALUES (?, ?, ?)");
            if (!$stmt) {
                return false;
            }
            $stmt->bind_param("iii", $cantidad, $idReceta, $idPedido);
            $exito = $stmt->execute();
            $stmt->close();
            return $exito;
        }

        /**
         * Elimina un ítem del detalle de un pedido
         */
        public function eliminarDetallePedido(int $idDetalle): bool
        {
            $stmt = $this->con->prepare("DELETE FROM detallepedido WHERE idDetallePedido = ?");
            if (!$stmt) {
                return false;
            }
            $stmt->bind_param("i", $idDetalle);
            $exito = $stmt->execute();
            $stmt->close();
            return $exito;
        }

        /**
         * Elimina un pedido y sus detalles
         */
        public function eliminarPedidoPorId(int $idPedido): bool
        {
            $stmtD = $this->con->prepare("DELETE FROM detallepedido WHERE IdPedido = ?");
            if ($stmtD) {
                $stmtD->bind_param("i", $idPedido);
                $stmtD->execute();
                $stmtD->close();
            }

            $stmtP = $this->con->prepare("DELETE FROM pedido WHERE idPedido = ?");
            if (!$stmtP) {
                return false;
            }
            $stmtP->bind_param("i", $idPedido);
            $exito = $stmtP->execute();
            $stmtP->close();
            return $exito;
        }

        /**
         * Obtiene la composición de materia prima de una receta
         */
        public function obtenerMateriaPrimaReceta(int $idReceta): array
        {
            $stmt = $this->con->prepare(
                "SELECT dr.idDetalleReceta, dr.cantidadSa, mp.NombreMP
                 FROM detallereceta dr
                 INNER JOIN materiaprima mp ON dr.idMateriaPrima = mp.idMateriaPrima
                 WHERE dr.idReceta = ?"
            );
            if (!$stmt) {
                return [];
            }
            $stmt->bind_param("i", $idReceta);
            $stmt->execute();
            $res = $stmt->get_result();
            $r = [];
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
            $stmt->close();
            return $r;
        }

        // ==========================================
        // Métodos de compatibilidad retrocompatible
        // ==========================================
        public function getClienteIndividual($correo)
        {
            $cli = $this->obtenerClientePorCorreo((string)$correo);
            return $cli ? [$cli] : [];
        }

        public function getAll($idCliente)
        {
            return $this->obtenerPedidosPorCliente((int)$idCliente);
        }

        public function getReceta()
        {
            return $this->obtenerRecetas();
        }

        public function getResProducto($idPedid)
        {
            return $this->obtenerDetalleProductosPedido((int)$idPedid);
        }

        public function getIdProd($idCliente)
        {
            return $this->obtenerUltimoPedidoCliente((int)$idCliente);
        }

        public function insertarPedido($p)
        {
            if (is_object($p)) {
                return $this->crearPedido($p->getFechaPedido(), (int)$p->getIdCliente(), (int)$p->getIdEstado());
            }
            return 0;
        }

        public function AddDetalle($pedid)
        {
            if (is_object($pedid)) {
                return $this->agregarDetallePedido((int)$pedid->getCantidad(), (int)$pedid->getIdReceta(), (int)$pedid->getIdPedido());
            }
            return false;
        }

        public function eliminarDetalle($id)
        {
            $idVal = is_object($id) ? (int)$id->getIdDetallePedido() : (int)$id;
            return $this->eliminarDetallePedido($idVal);
        }

        public function eliminarPedido($id)
        {
            $idVal = is_object($id) ? (int)$id->getIdPedido() : (int)$id;
            return $this->eliminarPedidoPorId($idVal);
        }

        public function getDetalle($det)
        {
            return $this->obtenerMateriaPrimaReceta((int)$det);
        }
    }
}
