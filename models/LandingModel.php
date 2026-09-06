<?php
require_once __DIR__ . '/../db/conexion.php';

if (!class_exists('LandingModel')) {
    class LandingModel extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Obtiene todos los productos/recetas con sus precios reales y metadata enriquecida
         * @return array
         */
        public function obtenerProductosCatalogo(): array
        {
            // Auto-finalizar promociones cuya fecha de fin ya expiró
            $this->con->query("
                UPDATE receta 
                SET PrecioUnitario = IFNULL(precio_base_regular, IFNULL(precio_anterior, PrecioUnitario)),
                    en_promocion = 0,
                    porcentaje_descuento = 0
                WHERE en_promocion = 1 
                  AND fecha_fin_promo IS NOT NULL 
                  AND NOW() > fecha_fin_promo
            ");

            $consulta = "
                SELECT r.idReceta, r.nombreReceta, r.PrecioUnitario, 
                       r.precio_base_regular, r.precio_anterior, r.en_promocion, 
                       r.porcentaje_descuento, r.fecha_inicio_promo, r.fecha_fin_promo,
                       CASE 
                           WHEN r.en_promocion = 1 
                                AND (r.fecha_inicio_promo IS NULL OR NOW() >= r.fecha_inicio_promo) 
                                AND (r.fecha_fin_promo IS NULL OR NOW() <= r.fecha_fin_promo) 
                           THEN 1 
                           ELSE 0 
                       END AS promo_activa,
                       GROUP_CONCAT(DISTINCT CONCAT(mp.NombreMP, ' (', dr.cantidaSa, ' lb)') ORDER BY dr.idDetalleReceta ASC SEPARATOR ', ') AS formula_detallada,
                       GROUP_CONCAT(DISTINCT mp.NombreMP ORDER BY dr.idDetalleReceta ASC SEPARATOR ', ') AS lista_ingredientes
                FROM receta r
                LEFT JOIN detallereceta dr ON r.idReceta = dr.IdReceta
                LEFT JOIN materiaprima mp ON dr.idMateriaPrima = mp.idMateriaPrima
                GROUP BY r.idReceta, r.nombreReceta, r.PrecioUnitario, r.precio_base_regular, r.precio_anterior, r.en_promocion, r.porcentaje_descuento, r.fecha_inicio_promo, r.fecha_fin_promo
                ORDER BY r.idReceta ASC
            ";
            $resultado = $this->con->query($consulta);
            $productos = [];

            if ($resultado) {
                while ($fila = $resultado->fetch_assoc()) {
                    $nombre = (string)$fila['nombreReceta'];
                    $precio = (float)$fila['PrecioUnitario'];
                    $precioBase = !empty($fila['precio_base_regular']) ? (float)$fila['precio_base_regular'] : (!empty($fila['precio_anterior']) ? (float)$fila['precio_anterior'] : $precio);
                    $promoActiva = (int)($fila['promo_activa'] ?? 0) === 1;

                    $enPromocion = $promoActiva && $precioBase > $precio;
                    $precioAnterior = $enPromocion ? $precioBase : 0.0;
                    $porcentajeDescuento = (int)($fila['porcentaje_descuento'] ?? 0);
                    if ($enPromocion && $porcentajeDescuento <= 0 && $precioAnterior > 0) {
                        $porcentajeDescuento = (int)round((($precioAnterior - $precio) / $precioAnterior) * 100);
                    }
                    $ahorro = ($enPromocion && $precioAnterior > $precio) ? ($precioAnterior - $precio) : 0.0;

                    $formula = trim((string)($fila['formula_detallada'] ?? ''));
                    $ingredientes = trim((string)($fila['lista_ingredientes'] ?? ''));

                    $categoria = 'Balanceados';
                    $filtro = 'balanceados';
                    $imagen = 'views/Recursos/pollos.jpg';
                    $icono = 'fas fa-seedling';
                    $etapa = 'Fórmula General Balanceada';
                    $proteina = 'Nutrición Integral';

                    if (stripos($nombre, 'Pollo') !== false || stripos($nombre, 'Aves') !== false) {
                        $categoria = 'Aves de Corral';
                        $filtro = 'aves';
                        $imagen = 'views/Recursos/pollo.jpg';
                        $icono = 'fas fa-feather-alt';
                    } elseif (stripos($nombre, 'Cerdo') !== false) {
                        $categoria = 'Porcinos';
                        $filtro = 'cerdos';
                        $imagen = 'views/Recursos/cerdo.jpg';
                        $icono = 'fas fa-paw';
                    } elseif (stripos($nombre, 'Ganado') !== false) {
                        $categoria = 'Ganado Bovino';
                        $filtro = 'ganado';
                        $imagen = 'views/Recursos/vaca.jpg';
                        $icono = 'fas fa-hat-cowboy';
                    }

                    if (stripos($nombre, 'Inicio') !== false) {
                        $etapa = 'Etapa de Inicio (Crecimiento Temprano)';
                        $proteina = '21% - 23% Proteína';
                    } elseif (stripos($nombre, 'Engorde') !== false) {
                        $etapa = 'Etapa de Engorde y Desarrollo Muscular';
                        $proteina = '18% - 20% Proteína';
                    } elseif (stripos($nombre, 'Final') !== false) {
                        $etapa = 'Etapa de Finalización y Rendimiento';
                        $proteina = '15% - 17% Proteína';
                    }

                    $productos[] = [
                        'id' => (int)$fila['idReceta'],
                        'nombre' => $nombre,
                        'precio' => $precio,
                        'precio_formato' => number_format($precio, 2),
                        'en_promocion' => $enPromocion,
                        'precio_anterior' => $precioAnterior,
                        'precio_anterior_formato' => number_format($precioAnterior, 2),
                        'porcentaje_descuento' => $porcentajeDescuento,
                        'ahorro_formato' => number_format($ahorro, 2),
                        'formula' => $formula ?: 'Fórmula balanceada con materias primas seleccionadas',
                        'ingredientes' => $ingredientes ?: 'Materias primas certificadas',
                        'categoria' => $categoria,
                        'filtro' => $filtro,
                        'imagen' => $imagen,
                        'icono' => $icono,
                        'etapa' => $etapa,
                        'proteina' => $proteina,
                        'unidad' => 'lb / presentación en saco'
                    ];
                }
            }

            return $productos;
        }

        /**
         * Obtiene los planes de servicio y pago registrados en el sistema
         * @return array
         */
        public function obtenerPlanesDisponibles(): array
        {
            $consulta = "SELECT idPlanPago, nombrePlan, descripcion, monto, duracion_dias 
                         FROM plan_pago 
                         WHERE activo = 1 
                         ORDER BY idPlanPago ASC";
            $resultado = $this->con->query($consulta);
            $planes = [];

            if ($resultado) {
                while ($fila = $resultado->fetch_assoc()) {
                    $nombre = (string)$fila['nombrePlan'];
                    $descripcionBD = trim((string)$fila['descripcion']);
                    $badge = 'Plan Comercial';
                    $destacado = false;

                    if (stripos($nombre, 'Basico') !== false) {
                        $badge = 'Granjas Pequeñas';
                    } elseif (stripos($nombre, 'Premium') !== false) {
                        $badge = 'Más Popular';
                        $destacado = true;
                    } elseif (stripos($nombre, 'Enterprise') !== false) {
                        $badge = 'Agroindustrias';
                    } elseif (stripos($nombre, 'Avícola') !== false || stripos($nombre, 'Avicola') !== false) {
                        $badge = 'Ciclo 90 Días';
                    } elseif (stripos($nombre, 'Porcina') !== false || stripos($nombre, 'Porcino') !== false) {
                        $badge = 'Semestral 180 Días';
                    } elseif (stripos($nombre, 'Cooperativas') !== false || stripos($nombre, 'Anual') !== false) {
                        $badge = 'Membresía Anual';
                        $destacado = true;
                    }

                    // Extraer características estrictamente desde la descripción de la BD
                    $caracteristicas = [];
                    if (!empty($descripcionBD)) {
                        $lineas = preg_split('/[\r\n;]+/', $descripcionBD);
                        foreach ($lineas as $linea) {
                            $item = trim(ltrim($linea, '-•* '));
                            if (!empty($item)) {
                                $caracteristicas[] = $item;
                            }
                        }
                    }

                    if (empty($caracteristicas) && !empty($descripcionBD)) {
                        $caracteristicas[] = $descripcionBD;
                    }

                    $resumen = $caracteristicas[0] ?? $descripcionBD;

                    $planes[] = [
                        'id' => (int)$fila['idPlanPago'],
                        'nombre' => $nombre,
                        'descripcion' => $descripcionBD,
                        'resumen' => $resumen,
                        'monto' => (float)$fila['monto'],
                        'monto_formato' => number_format((float)$fila['monto'], 2),
                        'duracion_dias' => (int)$fila['duracion_dias'],
                        'badge' => $badge,
                        'destacado' => $destacado,
                        'caracteristicas' => array_values(array_unique($caracteristicas))
                    ];
                }
            }

            return $planes;
        }

        /**
         * Obtiene estadísticas dinámicas del sistema para la sección de métricas de confianza
         * @return array
         */
        public function obtenerEstadisticas(): array
        {
            $totalFormulas = 10;
            $totalClientes = 120;
            $totalPedidos = 450;

            $resF = $this->con->query("SELECT COUNT(*) AS total FROM receta");
            if ($resF && $filaF = $resF->fetch_assoc()) {
                $totalFormulas = max((int)$filaF['total'], 10);
            }

            $resC = $this->con->query("SELECT COUNT(*) AS total FROM cliente");
            if ($resC && $filaC = $resC->fetch_assoc()) {
                $totalClientes = max((int)$filaC['total'], 50);
            }

            $resP = $this->con->query("SELECT COUNT(*) AS total FROM pedido");
            if ($resP && $filaP = $resP->fetch_assoc()) {
                $totalPedidos = max((int)$filaP['total'], 100);
            }

            return [
                'total_formulas' => $totalFormulas,
                'total_clientes' => $totalClientes,
                'total_pedidos' => $totalPedidos,
                'departamentos' => 14,
                'anios_experiencia' => 12
            ];
        }

        /**
         * Retorna la información institucional completa de la empresa desde la base de datos
         * @param string $slug Slug opcional de la empresa
         * @return array
         */
        public function obtenerInformacionEmpresa(string $slug = ''): array
        {
            if (!empty($slug)) {
                $stmtEmp = $this->con->prepare("SELECT * FROM empresas WHERE slug = ? AND activo = 1 LIMIT 1");
                if ($stmtEmp) {
                    $stmtEmp->bind_param("s", $slug);
                    $stmtEmp->execute();
                    $resEmp = $stmtEmp->get_result();
                    if ($rowEmp = $resEmp->fetch_assoc()) {
                        $stmtEmp->close();
                        $wa = preg_replace('/[^0-9]/', '', (string)($rowEmp['whatsapp'] ?: '50370000000'));
                        return [
                            'idEmpresa' => (int)$rowEmp['idEmpresa'],
                            'nombre' => (string)$rowEmp['nombreEmpresa'],
                            'eslogan' => 'Nutrición Animal y Servicios Agropecuarios',
                            'resumen' => 'Bienvenido a la sucursal y catálogo oficial de ' . $rowEmp['nombreEmpresa'] . ' en la plataforma Concentrados El Gordito.',
                            'mision' => 'Proveer soluciones agropecuarias y alimentos concentrados de primera calidad con entrega oportuna.',
                            'vision' => 'Ser el referente agropecuario de confianza para nuestros clientes en El Salvador.',
                            'telefono' => (string)($rowEmp['telefono'] ?: '+503 2440-1234'),
                            'telefono_movil' => (string)($rowEmp['whatsapp'] ?: '+503 7000-0000'),
                            'whatsapp' => (string)($rowEmp['whatsapp'] ?: '+503 7000-0000'),
                            'whatsapp_enlace' => "https://wa.me/{$wa}?text=Hola%20" . urlencode($rowEmp['nombreEmpresa']) . "%2C%20deseo%20m%C3%A1s%20informaci%C3%B3n%20sobre%20sus%20productos%20y%20precios",
                            'correo' => (string)($rowEmp['correo'] ?: 'contacto@empresa.com'),
                            'direccion' => (string)($rowEmp['direccion'] ?: 'El Salvador'),
                            'horario' => 'Lunes a Viernes: 7:00 AM – 5:00 PM | Sábados: 7:00 AM – 12:00 MD',
                            'logo' => (string)($rowEmp['logo'] ?: 'views/Recursos/logo.png'),
                            'facebook' => 'https://facebook.com',
                            'instagram' => 'https://instagram.com',
                            'pilares' => [
                                [
                                    'titulo' => 'Materias Primas Seleccionadas',
                                    'descripcion' => 'Granos, harinas y fórmulas certificadas para garantizar alta digestibilidad.',
                                    'icono' => 'fas fa-seedling'
                                ],
                                [
                                    'titulo' => 'Nutrición de Precisión',
                                    'descripcion' => 'Formulaciones diseñadas para el máximo rendimiento de aves, cerdos y ganado.',
                                    'icono' => 'fas fa-balance-scale'
                                ],
                                [
                                    'titulo' => 'Atención Personalizada',
                                    'descripcion' => 'Asesoría técnica y soporte comercial directo para tu granja.',
                                    'icono' => 'fas fa-truck-moving'
                                ]
                            ]
                        ];
                    }
                    $stmtEmp->close();
                }
            }

            $consulta = "SELECT * FROM empresa_info ORDER BY idEmpresa ASC LIMIT 1";
            $res = $this->con->query($consulta);
            if ($res && $fila = $res->fetch_assoc()) {
                $whatsappClean = preg_replace('/[^0-9]/', '', (string)$fila['whatsapp']);
                if (empty($whatsappClean)) {
                    $whatsappClean = '50378905678';
                }

                return [
                    'idEmpresa' => (int)$fila['idEmpresa'],
                    'nombre' => (string)$fila['nombre'],
                    'eslogan' => (string)$fila['eslogan'],
                    'resumen' => (string)$fila['resumen'],
                    'mision' => (string)$fila['mision'],
                    'vision' => (string)$fila['vision'],
                    'telefono' => (string)$fila['telefono'],
                    'telefono_movil' => (string)$fila['telefono_movil'],
                    'whatsapp' => (string)$fila['whatsapp'],
                    'whatsapp_enlace' => "https://wa.me/{$whatsappClean}?text=Hola%20Concentrados%20El%20Gordito%2C%20deseo%20m%C3%A1s%20informaci%C3%B3n%20sobre%20sus%20productos%20y%20precios",
                    'correo' => (string)$fila['correo'],
                    'direccion' => (string)$fila['direccion'],
                    'horario' => (string)$fila['horario'],
                    'logo' => (string)($fila['logo'] ?? 'views/Recursos/logo.png'),
                    'facebook' => (string)($fila['facebook'] ?? 'https://facebook.com'),
                    'instagram' => (string)($fila['instagram'] ?? 'https://instagram.com'),
                    'pilares' => [
                        [
                            'titulo' => 'Materias Primas Seleccionadas',
                            'descripcion' => 'Granos, harinas, vitaminas y minerales de pureza certificada para garantizar alta digestibilidad.',
                            'icono' => 'fas fa-seedling'
                        ],
                        [
                            'titulo' => 'Nutrición de Precisión',
                            'descripcion' => 'Formulaciones exactas diseñadas para cada fase de crecimiento: inicio, engorde y finalización.',
                            'icono' => 'fas fa-balance-scale'
                        ],
                        [
                            'titulo' => 'Control de Calidad y Lotes',
                            'descripcion' => 'Monitoreo constante en cada lote de producción con trazabilidad integral desde la recepción hasta el despacho.',
                            'icono' => 'fas fa-microscope'
                        ],
                        [
                            'titulo' => 'Distribución y Cobertura',
                            'descripcion' => 'Atención ágil y cobertura de entrega en los 14 departamentos de El Salvador para granjas de todos los tamaños.',
                            'icono' => 'fas fa-truck-moving'
                        ]
                    ]
                ];
            }

            // Datos por defecto si aún no existen registros
            return [
                'nombre' => 'Concentrados El Gordito',
                'eslogan' => 'Nutrición Animal de Alto Rendimiento para el Campo Salvadoreño',
                'resumen' => 'Somos una empresa salvadoreña dedicada a la elaboración y distribución de alimentos balanceados y concentrados de primera calidad para aves, ganado bovino y porcinos.',
                'mision' => 'Proveer soluciones nutricionales balanceadas con los más altos estándares de calidad.',
                'vision' => 'Consolidarnos como la planta de concentrados líder y más confiable de El Salvador.',
                'telefono' => '+503 2440-1234',
                'telefono_movil' => '+503 7890-5678',
                'whatsapp' => '+50378905678',
                'whatsapp_enlace' => 'https://wa.me/50378905678',
                'correo' => 'contacto@concentradoselgordito.com',
                'direccion' => 'Carretera Panamericana Km 65, El Salvador',
                'horario' => 'Lunes a Viernes: 7:00 AM – 5:00 PM | Sábados: 7:00 AM – 12:00 MD'
            ];
        }

        /**
         * Actualiza la información institucional de la empresa
         * @param array $datos
         * @return bool
         */
        public function actualizarInformacionEmpresa(array $datos): bool
        {
            $stmt = $this->con->prepare("
                UPDATE empresa_info 
                SET nombre = ?, eslogan = ?, resumen = ?, mision = ?, vision = ?, 
                    telefono = ?, telefono_movil = ?, whatsapp = ?, correo = ?, 
                    direccion = ?, horario = ? 
                WHERE idEmpresa = 1
            ");
            if (!$stmt) {
                return false;
            }

            $stmt->bind_param(
                "sssssssssss",
                $datos['nombre'],
                $datos['eslogan'],
                $datos['resumen'],
                $datos['mision'],
                $datos['vision'],
                $datos['telefono'],
                $datos['telefono_movil'],
                $datos['whatsapp'],
                $datos['correo'],
                $datos['direccion'],
                $datos['horario']
            );
            $exito = $stmt->execute();
            $stmt->close();
            return $exito;
        }
    }
}
