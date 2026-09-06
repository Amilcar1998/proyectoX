<?php
include 'configuracion.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Mis Pedidos - Concentrados El Gordito">
    <title>📦 Mis Pedidos - Concentrados El Gordito</title>

    <!-- Custom fonts for this template-->
    <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Page level plugin CSS-->
    <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../controllers/vendor/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f1f5f9;
        }

        .card-custom {
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            border: 1px solid #e2e8f0;
            background: #ffffff;
        }

        .table-custom thead th {
            background: #0f172a;
            color: #f8fafc;
            font-weight: 600;
            border: none;
            font-size: 0.84rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 14px 16px;
        }

        .table-custom tbody tr {
            transition: all 0.15s ease-in-out;
        }

        .table-custom tbody tr:hover {
            background-color: #f8fafc !important;
            transform: scale([1.002]);
        }

        .table-custom tbody td {
            vertical-align: middle;
            padding: 14px 16px;
            color: #334155;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
        }

        /* BADGES MODERNOS DE ESTADO (Pasteles Elegantes con Borde) */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            white-space: nowrap;
        }

        .status-pill i {
            font-size: 0.75rem;
        }

        /* Pendiente (Ámbar Cálido) */
        .status-pill-pendiente {
            background-color: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .status-pill-pendiente i {
            color: #d97706;
        }

        /* En Proceso / Producción (Azul Cielo) */
        .status-pill-proceso {
            background-color: #f0f9ff;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .status-pill-proceso i {
            color: #0284c7;
        }

        /* Completado (Esmeralda Suave) */
        .status-pill-completado {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .status-pill-completado i {
            color: #059669;
        }

        /* Cancelado (Rojo Rosado Suave) */
        .status-pill-cancelado {
            background-color: #fff1f2;
            color: #9f1239;
            border: 1px solid #fecdd3;
        }
        .status-pill-cancelado i {
            color: #e11d48;
        }

        /* BOTONES DE ACCIÓN MODERNOS */
        .btn-action-detail {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 7px 14px;
            font-weight: 600;
            font-size: 0.82rem;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
        }

        .btn-action-detail:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(37, 99, 235, 0.35);
        }

        .btn-action-detail:active {
            transform: translateY(0);
        }

        /* CONTROLES DE FILTRO RÁPIDO */
        .filter-btn-group .btn-filter {
            background: #ffffff;
            color: #64748b;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 7px 15px;
            font-size: 0.83rem;
            font-weight: 600;
            margin-right: 6px;
            margin-bottom: 6px;
            transition: all 0.2s ease;
        }

        .filter-btn-group .btn-filter:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
        }

        .filter-btn-group .btn-filter.active {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.2);
        }

        .filter-count {
            display: inline-block;
            background: rgba(0, 0, 0, 0.08);
            border-radius: 999px;
            padding: 1px 7px;
            font-size: 0.72rem;
            margin-left: 4px;
        }

        .btn-filter.active .filter-count {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }

        /* MODAL ELEGANTE */
        .modal-header-custom {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            padding: 18px 24px;
        }

        .modal-content {
            border-radius: 16px;
            overflow: hidden;
        }

        .stat-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .nav-tabs-custom {
            border-bottom: 2px solid #e2e8f0;
            gap: 8px;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            color: #64748b;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 8px 8px 0 0;
            background: transparent;
            transition: all 0.2s ease;
        }

        .nav-tabs-custom .nav-link:hover {
            color: #0f172a;
            background: #f1f5f9;
        }

        .nav-tabs-custom .nav-link.active {
            color: #2563eb;
            background: #ffffff;
            border-bottom: 3px solid #2563eb;
            font-weight: 700;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            #areaImpresionModal, #areaImpresionModal * {
                visibility: visible;
            }
            #areaImpresionModal {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background: white !important;
                color: black !important;
                padding: 20px;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body id="page-top">
    <?php echo $nav; ?>

    <div id="wrapper">
        <!-- Sidebar -->
        <?php echo $menu; ?>

        <div id="content-wrapper">
            <div class="container-fluid py-4">

                <!-- Breadcrumbs-->
                <ol class="breadcrumb bg-white shadow-sm rounded-lg mb-4 py-2 px-3 border">
                    <li class="breadcrumb-item">
                        <a href="controllerTablero.php" class="text-secondary"><i class="fas fa-home"></i> Inicio</a>
                    </li>
                    <li class="breadcrumb-item active text-dark font-weight-bold"><i class="fas fa-tasks mr-1"></i> Mis Pedidos</li>
                </ol>

                <?php
                $totalPedidos = count($datos);
                $cntPendientes = 0;
                $cntProceso = 0;
                $cntCompletados = 0;
                $cntCancelados = 0;

                foreach ($datos as $row) {
                    $idEst = (int)($row['idEstadoPedido'] ?? 1);
                    $nomEst = strtolower((string)($row['nombreEstado'] ?? ''));
                    if ($idEst === 2 || strpos($nomEst, 'proceso') !== false || strpos($nomEst, 'producc') !== false) {
                        $cntProceso++;
                    } elseif ($idEst === 3 || strpos($nomEst, 'completa') !== false || strpos($nomEst, 'entrega') !== false) {
                        $cntCompletados++;
                    } elseif ($idEst === 4 || strpos($nomEst, 'cancela') !== false) {
                        $cntCancelados++;
                    } else {
                        $cntPendientes++;
                    }
                }
                ?>

                <!-- Tarjeta Principal de Pedidos -->
                <div class="card card-custom mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap justify-content-between align-items-center">
                        <div class="mb-2 mb-md-0">
                            <h5 class="m-0 font-weight-bold text-dark d-flex align-items-center">
                                <span class="bg-primary text-white rounded p-2 mr-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="fas fa-boxes"></i>
                                </span>
                                Control y Consulta de Mis Pedidos
                            </h5>
                            <small class="text-muted">Visualización en tiempo real de órdenes de clientes y detalle técnico de formulación.</small>
                        </div>
                    </div>

                    <!-- Barra de Controles y Filtros Rápidos -->
                    <div class="px-4 pt-3 pb-2 bg-light border-bottom">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="filter-btn-group d-flex flex-wrap align-items-center mb-2">
                                <span class="small font-weight-bold text-muted text-uppercase mr-2"><i class="fas fa-filter mr-1"></i>Filtrar:</span>
                                <button type="button" class="btn btn-filter active" data-filter="">
                                    Todos <span class="filter-count"><?= $totalPedidos ?></span>
                                </button>
                                <button type="button" class="btn btn-filter" data-filter="Pendiente">
                                    <i class="fas fa-clock text-warning mr-1"></i>Pendientes <span class="filter-count"><?= $cntPendientes ?></span>
                                </button>
                                <button type="button" class="btn btn-filter" data-filter="En Proceso|En Producción">
                                    <i class="fas fa-cog text-info mr-1"></i>En Proceso <span class="filter-count"><?= $cntProceso ?></span>
                                </button>
                                <button type="button" class="btn btn-filter" data-filter="Completado">
                                    <i class="fas fa-check-circle text-success mr-1"></i>Completados <span class="filter-count"><?= $cntCompletados ?></span>
                                </button>
                                <button type="button" class="btn btn-filter" data-filter="Cancelado">
                                    <i class="fas fa-times-circle text-danger mr-1"></i>Cancelados <span class="filter-count"><?= $cntCancelados ?></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-custom table-hover" id="tablaPedidosIn" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 100px;">N° Pedido</th>
                                        <th>Fecha Solicitud</th>
                                        <th>Cliente</th>
                                        <th>Contacto</th>
                                        <th class="text-center" style="width: 160px;">Estado</th>
                                        <th class="text-center" style="width: 130px;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($datos) && is_array($datos)): ?>
                                        <?php foreach ($datos as $row): 
                                            $id = (int)($row['idPedido'] ?? 0);
                                            $fecha = htmlspecialchars((string)($row['fechaPedido'] ?? ''));
                                            $nombre = htmlspecialchars(trim(($row['NombreCliente'] ?? '') . ' ' . ($row['ApellidosCliente'] ?? '')));
                                            $telefono = htmlspecialchars((string)($row['telefono'] ?? ''));
                                            $estado = (string)($row['nombreEstado'] ?? 'Pendiente');
                                            $idEstado = (int)($row['idEstadoPedido'] ?? 1);

                                            if ($idEstado === 2 || stripos($estado, 'proceso') !== false || stripos($estado, 'producc') !== false) {
                                                $pillClass = 'status-pill-proceso';
                                                $pillIcon = 'fa-cog fa-spin';
                                                $estadoLabel = 'En Proceso';
                                            } elseif ($idEstado === 3 || stripos($estado, 'completa') !== false || stripos($estado, 'entrega') !== false) {
                                                $pillClass = 'status-pill-completado';
                                                $pillIcon = 'fa-check-circle';
                                                $estadoLabel = 'Completado';
                                            } elseif ($idEstado === 4 || stripos($estado, 'cancela') !== false) {
                                                $pillClass = 'status-pill-cancelado';
                                                $pillIcon = 'fa-times-circle';
                                                $estadoLabel = 'Cancelado';
                                            } else {
                                                $pillClass = 'status-pill-pendiente';
                                                $pillIcon = 'fa-clock';
                                                $estadoLabel = 'Pendiente';
                                            }
                                        ?>
                                            <tr>
                                                <td class="text-center align-middle font-weight-bold text-dark">
                                                    <span class="badge badge-light border px-2 py-1" style="font-size: 0.88rem;">#<?= $id ?></span>
                                                </td>
                                                <td class="align-middle">
                                                    <span class="text-muted"><i class="far fa-calendar-alt mr-1 text-secondary"></i><?= $fecha ?></span>
                                                </td>
                                                <td class="align-middle font-weight-bold text-dark">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center mr-2 flex-shrink-0" style="width: 32px; height: 32px;">
                                                            <i class="fas fa-user-tie"></i>
                                                        </div>
                                                        <span><?= $nombre ?: 'Cliente General' ?></span>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <?php if (!empty($telefono) && $telefono !== 'N/A' && $telefono !== '0000-0000'): ?>
                                                         <a href="tel:<?= $telefono ?>" class="text-primary font-weight-bold text-decoration-none">
                                                             <i class="fas fa-phone-alt mr-1 text-success"></i><?= $telefono ?>
                                                         </a>
                                                    <?php else: ?>
                                                        <span class="text-muted small">Sin teléfono</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <span class="status-pill <?= $pillClass ?>">
                                                        <i class="fas <?= $pillIcon ?>"></i> <?= htmlspecialchars($estadoLabel) ?>
                                                    </span>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <button type="button" class="btn-action-detail btn-ver-detalle" data-id="<?= $id ?>" title="Ver Ficha Completa del Pedido">
                                                        <i class="fas fa-eye"></i> Detalle
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

            <!-- Sticky Footer elegante en color negro -->
            <footer class="sticky-footer bg-dark text-white mt-auto">
                <div class="container-fluid text-center">
                    <span>Concentrados El Gordito &bull; Sistema de Gestión &copy; 2026</span>
                </div>
            </footer>
        </div>
        <!-- /.content-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- ========================================================================= -->
    <!-- MODAL MODERNO: DETALLE COMPLETO DEL PEDIDO -->
    <!-- ========================================================================= -->
    <div class="modal fade" id="modalDetallePedido" tabindex="-1" role="dialog" aria-labelledby="modalDetallePedidoLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0">
                <!-- Header del Modal -->
                <div class="modal-header modal-header-custom">
                    <div class="d-flex align-items-center">
                        <div class="bg-white text-dark rounded-circle p-2 mr-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="fas fa-file-invoice text-primary fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold mb-0 text-white" id="modalDetallePedidoLabel">
                                Ficha de Pedido #<span id="txtModalIdPedido">---</span>
                            </h5>
                            <small class="text-light opacity-75">Resumen de cliente, productos solicitados y formulación técnica</small>
                        </div>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.9;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Cuerpo del Modal -->
                <div class="modal-body p-4 bg-light" id="modalCuerpoDetalle">
                    <!-- Spinner de Carga -->
                    <div id="cargandoModal" class="text-center py-5">
                        <div class="spinner-border text-primary mb-3" style="width: 3.5rem; height: 3.5rem;" role="status"></div>
                        <h6 class="text-muted font-weight-bold">Obteniendo detalles del pedido...</h6>
                    </div>

                    <!-- Contenedor del Detalle Imprimible / Visible -->
                    <div id="areaImpresionModal" style="display: none;">
                        <!-- Bloque de Resumen / Cards Superiores -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="stat-box h-100">
                                    <div class="text-muted small font-weight-bold text-uppercase mb-1"><i class="fas fa-user-circle text-primary mr-1"></i> Datos del Cliente</div>
                                    <h6 class="font-weight-bold text-dark mb-1" id="detClienteNombre">---</h6>
                                    <div class="small text-muted">
                                        <div><i class="fas fa-phone-alt text-success mr-1"></i> <span id="detClienteTel">---</span></div>
                                        <div><i class="fas fa-envelope text-info mr-1"></i> <span id="detClienteCorreo">---</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="stat-box h-100">
                                    <div class="text-muted small font-weight-bold text-uppercase mb-1"><i class="fas fa-info-circle text-info mr-1"></i> Estado y Fecha</div>
                                    <div class="my-1" id="detEstadoBadge">
                                        <span class="status-pill status-pill-pendiente"><i class="fas fa-clock"></i> Pendiente</span>
                                    </div>
                                    <div class="small text-muted mt-2">
                                        <i class="far fa-calendar-alt text-secondary mr-1"></i> <strong>Fecha Solicitud:</strong> <span id="detFechaPedido">---</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-box h-100 border-left-success" style="border-left: 4px solid #10b981 !important;">
                                    <div class="text-muted small font-weight-bold text-uppercase mb-1"><i class="fas fa-wallet text-success mr-1"></i> Importe del Pedido</div>
                                    <h3 class="font-weight-bold text-success mb-0" id="detTotalPedido">$0.00</h3>
                                    <small class="text-muted font-weight-bold"><span id="detTotalItems">0</span> línea(s) de productos</small>
                                </div>
                            </div>
                        </div>

                        <!-- Pestañas de Detalle (Productos vs Receta Técnica) -->
                        <div class="card border-0 shadow-sm mb-2 rounded-lg overflow-hidden">
                            <div class="card-header bg-white px-3 pt-3 pb-0 border-bottom">
                                <ul class="nav nav-tabs-custom" id="tabsPedido" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tab-productos-link" data-toggle="tab" href="#tab-productos" role="tab">
                                            <i class="fas fa-boxes mr-1 text-primary"></i> 1. Productos Solicitados
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-receta-link" data-toggle="tab" href="#tab-receta" role="tab">
                                            <i class="fas fa-flask mr-1 text-info"></i> 2. Formulación & Materia Prima (Receta)
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-3">
                                <div class="tab-content" id="tabsPedidoContenido">
                                    <!-- TAB 1: PRODUCTOS -->
                                    <div class="tab-pane fade show active" id="tab-productos" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered mb-0">
                                                <thead class="bg-light text-dark">
                                                    <tr>
                                                        <th class="text-center" style="width: 50px;">#</th>
                                                        <th>Producto / Alimento Concentrado</th>
                                                        <th class="text-center" style="width: 130px;">Unidades</th>
                                                        <th class="text-right" style="width: 140px;">Precio Unitario</th>
                                                        <th class="text-right" style="width: 140px;">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaItemsPedido">
                                                    <!-- Llenado con JS -->
                                                </tbody>
                                                <tfoot class="bg-light font-weight-bold">
                                                    <tr>
                                                        <td colspan="4" class="text-right text-uppercase small text-muted">Total Liquidación:</td>
                                                        <td class="text-right text-success font-weight-bold h5 mb-0" id="pieTotalGeneral">$0.00</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- TAB 2: MATERIA PRIMA / FORMULACIÓN -->
                                    <div class="tab-pane fade" id="tab-receta" role="tabpanel">
                                        <div class="alert alert-info py-2 px-3 mb-3 small d-flex align-items-center">
                                            <i class="fas fa-info-circle mr-2 fa-lg"></i>
                                            <span>Desglose técnico de insumos y materias primas requeridas para la preparación de los concentrados solicitados.</span>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered table-sm mb-0">
                                                <thead class="bg-light text-dark">
                                                    <tr>
                                                        <th class="text-center" style="width: 50px;">#</th>
                                                        <th>Materia Prima / Insumo</th>
                                                        <th>Producto Destino</th>
                                                        <th class="text-center" style="width: 150px;">Cantidad Salida</th>
                                                        <th class="text-center" style="width: 140px;">Fecha Registro</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaRecetaPedido">
                                                    <!-- Llenado con JS -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Fin #areaImpresionModal -->

                </div>

                <!-- Footer del Modal con Acciones -->
                <div class="modal-footer bg-white py-3 px-4 d-flex justify-content-between border-top">
                    <div>
                        <button type="button" class="btn btn-outline-secondary font-weight-bold px-3" onclick="imprimirDetallePedido()">
                            <i class="fas fa-print mr-1"></i> Imprimir Comprobante
                        </button>
                    </div>
                    <div class="d-flex align-items-center">
                        <!-- Formulario para Pasar a Producción -->
                        <form action="controllerProduccionIn.php" method="POST" id="formPasarProduccion" class="mr-2">
                            <input type="hidden" name="id" id="inpProduccionIdPedido" value="">
                            <input type="hidden" name="agregar" value="1">
                            <button type="submit" class="btn btn-info font-weight-bold px-3 shadow-sm" id="btnPasarProduccion">
                                <i class="fas fa-industry mr-1"></i> Pasar a Producción
                            </button>
                        </form>
                        <button type="button" class="btn btn-secondary font-weight-bold px-4" data-dismiss="modal">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Scripts Base -->
    <script src="../controllers/vendor/jquery/jquery.min.js"></script>
    <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
    <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/sb-admin.min.js"></script>
    <script src="js/translations.js"></script>

    <!-- Script de Manejo de la Tabla y el Modal de Detalle -->
    <script>
        let dataTablePedidosIn = null;

        $(document).ready(function() {
            dataTablePedidosIn = $('#tablaPedidosIn').DataTable({
                order: [[0, 'desc']],
                pageLength: 15,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
                }
            });

            $('.btn-filter').on('click', function() {
                $('.btn-filter').removeClass('active');
                $(this).addClass('active');

                const filterVal = $(this).data('filter');
                if (filterVal) {
                    dataTablePedidosIn.column(4).search(filterVal, true, false).draw();
                } else {
                    dataTablePedidosIn.column(4).search('').draw();
                }
            });

            $(document).on('click', '.btn-ver-detalle', function(e) {
                e.preventDefault();
                const idPedido = $(this).data('id');
                if (!idPedido) return;

                abrirModalDetallePedido(idPedido);
            });
        });

        function abrirModalDetallePedido(idPedido) {
            $('#txtModalIdPedido').text(idPedido);
            $('#inpProduccionIdPedido').val(idPedido);
            $('#cargandoModal').show();
            $('#areaImpresionModal').hide();
            $('#modalDetallePedido').modal('show');

            $.ajax({
                url: 'controllerPedidosIn.php',
                type: 'GET',
                data: {
                    accion: 'obtenerDetalle',
                    idPedido: idPedido
                },
                dataType: 'json',
                success: function(resp) {
                    $('#cargandoModal').hide();
                    if (resp && resp.success && resp.data) {
                        renderizarDetalle(resp.data);
                        $('#areaImpresionModal').fadeIn(200);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: resp.mensaje || 'No fue posible cargar el detalle del pedido.'
                        });
                        $('#modalDetallePedido').modal('hide');
                    }
                },
                error: function(xhr, status, error) {
                    $('#cargandoModal').hide();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Conexión',
                        text: 'Ocurrió un error al comunicarse con el servidor: ' + error
                    });
                    $('#modalDetallePedido').modal('hide');
                }
            });
        }

        function renderizarDetalle(data) {
            const p = data.pedido || {};
            const items = data.items || [];
            const recetas = data.recetas || [];
            const total = parseFloat(data.total || 0).toFixed(2);

            const nombreCliente = ((p.NombreCliente || '') + ' ' + (p.apellidosCliente || '')).trim() || 'Cliente No Identificado';
            $('#detClienteNombre').text(nombreCliente);
            $('#detClienteTel').text(p.telefono || 'Sin teléfono registrado');
            $('#detClienteCorreo').text(p.correoCliente || 'Sin correo');
            $('#detFechaPedido').text(p.fechaPedido || '---');
            $('#detTotalPedido').text('$' + total);
            $('#detTotalItems').text(items.length);
            $('#pieTotalGeneral').text('$' + total);

            const idEstado = parseInt(p.idEstadoPedido || 1);
            const nombreEstado = p.nombreEstado || 'Pendiente';
            let badgeHtml = '<span class="status-pill status-pill-pendiente"><i class="fas fa-clock"></i> Pendiente</span>';

            if (idEstado === 2 || nombreEstado.toLowerCase().includes('proceso') || nombreEstado.toLowerCase().includes('producc')) {
                badgeHtml = '<span class="status-pill status-pill-proceso"><i class="fas fa-cog fa-spin"></i> En Proceso</span>';
                $('#btnPasarProduccion').hide();
            } else if (idEstado === 3 || nombreEstado.toLowerCase().includes('completa') || nombreEstado.toLowerCase().includes('entrega')) {
                badgeHtml = '<span class="status-pill status-pill-completado"><i class="fas fa-check-circle"></i> Completado</span>';
                $('#btnPasarProduccion').hide();
            } else if (idEstado === 4 || nombreEstado.toLowerCase().includes('cancela')) {
                badgeHtml = '<span class="status-pill status-pill-cancelado"><i class="fas fa-times-circle"></i> Cancelado</span>';
                $('#btnPasarProduccion').hide();
            } else {
                $('#btnPasarProduccion').show();
            }
            $('#detEstadoBadge').html(badgeHtml);

            let itemsHtml = '';
            if (items.length > 0) {
                items.forEach((it, idx) => {
                    const cant = parseInt(it.cantidad || 0);
                    const precio = parseFloat(it.PrecioUnitario || 0).toFixed(2);
                    const subtotal = parseFloat(it.subtotal || (cant * precio)).toFixed(2);
                    itemsHtml += `
                        <tr>
                            <td class="text-center font-weight-bold text-muted">${idx + 1}</td>
                            <td class="font-weight-bold text-dark">
                                <i class="fas fa-cube text-primary mr-2"></i>${it.nombreReceta || 'Producto General'}
                            </td>
                            <td class="text-center"><span class="badge badge-pill badge-light border px-2 py-1 font-weight-bold">${cant} unid.</span></td>
                            <td class="text-right text-muted">$${precio}</td>
                            <td class="text-right font-weight-bold text-dark">$${subtotal}</td>
                        </tr>
                    `;
                });
            } else {
                itemsHtml = '<tr><td colspan="5" class="text-center text-muted py-3">No hay productos registrados en este pedido.</td></tr>';
            }
            $('#tablaItemsPedido').html(itemsHtml);

            let recetasHtml = '';
            if (recetas.length > 0) {
                recetas.forEach((rec, idx) => {
                    recetasHtml += `
                        <tr>
                            <td class="text-center text-muted font-weight-bold">${idx + 1}</td>
                            <td class="font-weight-bold text-dark">
                                <i class="fas fa-seedling text-success mr-2"></i>${rec.NombreMP || 'Insumo'}
                            </td>
                            <td><span class="text-muted small">${rec.nombreReceta || 'General'}</span></td>
                            <td class="text-center font-weight-bold text-info">${rec.cantidaSa || 0}</td>
                            <td class="text-center text-muted small">${rec.fechaSa || '---'}</td>
                        </tr>
                    `;
                });
            } else {
                recetasHtml = '<tr><td colspan="5" class="text-center text-muted py-3">No hay desglose de materia prima para los productos de este pedido.</td></tr>';
            }
            $('#tablaRecetaPedido').html(recetasHtml);
        }

        function imprimirDetallePedido() {
            window.print();
        }
    </script>
</body>
</html>
