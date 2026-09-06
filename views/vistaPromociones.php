<?php include 'configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>🏷️ Gestión de Precios, Nivelaciones y Promociones</title>

    <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../controllers/vendor/sb-admin.css" rel="stylesheet">
    <script src="../controllers/vendor/sweetalert2.all.min.js"></script>

    <style>
        .card-stat {
            border-radius: 12px;
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .badge-promo-active {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #ffffff;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.78rem;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.35);
            display: inline-block;
        }
        .badge-promo-inactive {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #cbd5e1;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.76rem;
            font-weight: 600;
            display: inline-block;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            white-space: nowrap;
        }
        .status-pill-completado {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .status-pill-cancelado {
            background-color: #fff1f2;
            color: #9f1239;
            border: 1px solid #fecdd3;
        }
        .status-pill-pendiente {
            background-color: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .status-pill-proceso {
            background-color: #f0f9ff;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .price-current {
            font-size: 1.18rem;
            font-weight: 800;
            color: #059669;
        }
        .price-old {
            font-size: 0.92rem;
            color: #94a3b8;
            text-decoration: line-through;
            font-weight: 600;
        }
        .nav-tabs-custom .nav-link {
            font-weight: 700;
            color: #64748b;
            border-radius: 8px 8px 0 0;
            padding: 10px 20px;
        }
        .nav-tabs-custom .nav-link.active {
            color: #059669;
            background-color: #ffffff;
            border-color: #dee2e6 #dee2e6 #ffffff;
            border-top: 3px solid #059669;
        }
    </style>
</head>
<body id="page-top">
    <?php echo "$nav"; ?>

    <div id="wrapper">
        <!-- Sidebar -->
        <?php echo "$menu"; ?>

        <div id="content-wrapper">
            <div class="container-fluid">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="controllerDashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Precios, Nivelaciones y Promociones</li>
                </ol>

                <!-- Header y Acciones -->
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h2 class="h3 font-weight-bold text-dark mb-1">
                            <i class="fas fa-tags text-success mr-2"></i>Gestión de Precios, Nivelaciones y Promociones
                        </h2>
                        <p class="text-muted mb-0">
                            <strong>Gerencia Comercial:</strong> Aplica <em>nivelaciones de precio base</em> directas o programa <em>promociones temporales</em> con inicio, fin e histórico automático.
                        </p>
                    </div>
                    <div class="d-flex gap-2 mt-2 mt-md-0">
                        <button type="button" class="btn btn-outline-primary mr-2" data-toggle="modal" data-target="#modalAjusteMasivo">
                            <i class="fas fa-percentage mr-1"></i> Ajuste Masivo (%)
                        </button>
                        <a href="../index.php#catalogo" target="_blank" class="btn btn-success font-weight-bold">
                            <i class="fas fa-external-link-alt mr-1"></i> Ver Landing Page
                        </a>
                    </div>
                </div>

                <!-- Tarjetas Estadísticas -->
                <?php
                    $totalRecetas = count($listaRecetas);
                    $totalPromociones = 0;
                    $sumaPrecios = 0;
                    foreach ($listaRecetas as $r) {
                        if (!empty($r['promo_activa_momento'])) {
                            $totalPromociones++;
                        }
                        $sumaPrecios += (float)$r['PrecioUnitario'];
                    }
                    $promedioPrecio = $totalRecetas > 0 ? ($sumaPrecios / $totalRecetas) : 0;
                ?>
                <div class="row mb-4">
                    <div class="col-xl-4 col-md-6 mb-3">
                        <div class="card card-stat bg-primary text-white shadow-sm p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-white-50 small text-uppercase font-weight-bold">Fórmulas en Catálogo</div>
                                    <div class="h3 font-weight-bold mb-0"><?= $totalRecetas ?> Fórmulas</div>
                                    <small class="text-white-50">Precios base configurados</small>
                                </div>
                                <i class="fas fa-mortar-pestle fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 mb-3">
                        <div class="card card-stat bg-danger text-white shadow-sm p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-white-50 small text-uppercase font-weight-bold">Promociones Activas Hoy</div>
                                    <div class="h3 font-weight-bold mb-0"><?= $totalPromociones ?> con Descuento</div>
                                    <small class="text-white-50">Dentro del rango de vigencia</small>
                                </div>
                                <i class="fas fa-fire fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 mb-3">
                        <div class="card card-stat bg-success text-white shadow-sm p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-white-50 small text-uppercase font-weight-bold">Precio Promedio de Venta</div>
                                    <div class="h3 font-weight-bold mb-0">$<?= number_format($promedioPrecio, 2) ?> USD</div>
                                    <small class="text-white-50">Por libra de concentrado</small>
                                </div>
                                <i class="fas fa-balance-scale fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs de Navegación -->
                <ul class="nav nav-tabs nav-tabs-custom mb-3" id="tabPrecios" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="catalogo-tab" data-toggle="tab" href="#tab-catalogo" role="tab" aria-controls="tab-catalogo" aria-selected="true">
                            <i class="fas fa-tags mr-1"></i> Precios Vigentes y Promociones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="historial-tab" data-toggle="tab" href="#tab-historial" role="tab" aria-controls="tab-historial" aria-selected="false">
                            <i class="fas fa-history mr-1"></i> Historial de Nivelaciones y Promociones
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="tabContentPrecios">
                    <!-- TAB 1: CATÁLOGO Y PRECIOS VIGENTES -->
                    <div class="tab-pane fade show active" id="tab-catalogo" role="tabpanel" aria-labelledby="catalogo-tab">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                                <h6 class="m-0 font-weight-bold"><i class="fas fa-list mr-2"></i>Catálogo de Fórmulas y Estado Comercial</h6>
                                <span class="badge badge-light font-weight-bold">Ventas & Landing Sincronizadas</span>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width: 45px;">ID</th>
                                                <th>Fórmula / Producto</th>
                                                <th>Composición / Receta en BD</th>
                                                <th style="width: 120px;">Precio Venta</th>
                                                <th style="width: 120px;">Precio Base</th>
                                                <th style="width: 170px;">Estado Comercial</th>
                                                <th style="width: 180px;">Acciones Gerenciales</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($listaRecetas as $item): ?>
                                                <?php
                                                    $id = (int)$item['idReceta'];
                                                    $nombre = htmlspecialchars($item['nombreReceta']);
                                                    $precioVenta = (float)$item['PrecioUnitario'];
                                                    $precioBase = !empty($item['precio_base_regular']) ? (float)$item['precio_base_regular'] : $precioVenta;
                                                    $enPromoActiva = (int)($item['promo_activa_momento'] ?? 0) === 1;
                                                    $pct = (int)($item['porcentaje_descuento'] ?? 0);
                                                    $formula = htmlspecialchars($item['formula'] ?? 'Materia prima balanceada');
                                                    $fInicio = !empty($item['fecha_inicio_promo']) ? date('d/m/Y H:i', strtotime($item['fecha_inicio_promo'])) : null;
                                                    $fFin = !empty($item['fecha_fin_promo']) ? date('d/m/Y H:i', strtotime($item['fecha_fin_promo'])) : null;
                                                ?>
                                                <tr>
                                                    <td class="text-center font-weight-bold"><?= $id ?></td>
                                                    <td class="font-weight-bold text-dark">
                                                        <i class="fas fa-seedling text-success mr-1"></i><?= $nombre ?>
                                                    </td>
                                                    <td class="small text-muted">
                                                        <?= $formula ?>
                                                    </td>
                                                    <td>
                                                        <span class="price-current">$<?= number_format($precioVenta, 2) ?></span>
                                                        <?php if ($enPromoActiva && $precioBase > $precioVenta): ?>
                                                            <br><span class="price-old">$<?= number_format($precioBase, 2) ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="text-dark font-weight-bold">$<?= number_format($precioBase, 2) ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if ($enPromoActiva): ?>
                                                            <span class="badge-promo-active mb-1">
                                                                <i class="fas fa-fire mr-1"></i>-<?= $pct ?>% OFERTA
                                                            </span>
                                                            <div class="small text-muted" style="font-size: 0.73rem;">
                                                                <i class="far fa-clock mr-1 text-danger"></i>Hasta: <?= $fFin ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="badge-promo-inactive">
                                                                <i class="fas fa-check-circle text-success mr-1"></i>Precio Base Nivelado
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column gap-1">
                                                            <!-- Botón Nivelación de Precio Base -->
                                                            <button type="button" class="btn btn-sm btn-outline-dark mb-1" 
                                                                    onclick="abrirModalNivelacion(<?= $id ?>, '<?= addslashes($nombre) ?>', <?= $precioBase ?>)">
                                                                <i class="fas fa-balance-scale mr-1 text-primary"></i> Nivelar Precio
                                                            </button>

                                                            <!-- Botón Programar Promoción -->
                                                            <button type="button" class="btn btn-sm btn-danger mb-1" 
                                                                    onclick="abrirModalPromocion(<?= $id ?>, '<?= addslashes($nombre) ?>', <?= $precioBase ?>, <?= $precioVenta ?>, '<?= $item['fecha_inicio_promo'] ?? '' ?>', '<?= $item['fecha_fin_promo'] ?? '' ?>')">
                                                                <i class="fas fa-tag mr-1"></i> <?= $enPromoActiva ? 'Editar Oferta' : 'Crear Oferta' ?>
                                                            </button>

                                                            <?php if ($enPromoActiva): ?>
                                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                                        onclick="cancelarPromocion(<?= $id ?>, '<?= addslashes($nombre) ?>')">
                                                                    <i class="fas fa-times-circle mr-1 text-danger"></i> Quitar Oferta
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: HISTORIAL DE NIVELACIONES Y PROMOCIONES -->
                    <div class="tab-pane fade" id="tab-historial" role="tabpanel" aria-labelledby="historial-tab">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                                <h6 class="m-0 font-weight-bold"><i class="fas fa-history mr-2"></i>Auditoría e Historial de Cambios de Precio</h6>
                                <span class="badge badge-info font-weight-bold">Registro Inmutable</span>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="dataTableHistorial" width="100%" cellspacing="0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width: 140px;">Fecha y Hora</th>
                                                <th>Fórmula / Producto</th>
                                                <th style="width: 120px;">Tipo de Cambio</th>
                                                <th style="width: 110px;">Precio Antes</th>
                                                <th style="width: 110px;">Precio Nuevo</th>
                                                <th style="width: 170px;">Vigencia (Inicio - Fin)</th>
                                                <th>Motivo / Campaña</th>
                                                <th style="width: 90px;">Usuario</th>
                                                <th style="width: 100px;">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($historialPrecios)): ?>
                                                <?php foreach ($historialPrecios as $hist): ?>
                                                    <?php
                                                        $tipo = $hist['tipo_cambio'];
                                                        $estado = $hist['estado'];
                                                        $esNivelacion = $tipo === 'nivelacion';
                                                    ?>
                                                    <tr>
                                                        <td class="small text-muted">
                                                            <?= date('d/m/Y H:i', strtotime($hist['creado_en'])) ?>
                                                        </td>
                                                        <td class="font-weight-bold">
                                                            <?= htmlspecialchars($hist['nombreReceta'] ?? "Fórmula #{$hist['idReceta']}") ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($esNivelacion): ?>
                                                                <span class="badge badge-dark px-2 py-1">
                                                                    <i class="fas fa-balance-scale mr-1"></i>Nivelación
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge badge-danger px-2 py-1">
                                                                    <i class="fas fa-fire mr-1"></i>Promoción
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-muted font-weight-bold">
                                                            $<?= number_format((float)$hist['precio_anterior'], 2) ?>
                                                        </td>
                                                        <td class="text-success font-weight-bold">
                                                            $<?= number_format((float)$hist['precio_nuevo'], 2) ?>
                                                            <?php if (!$esNivelacion && !empty($hist['porcentaje_descuento'])): ?>
                                                                <small class="text-danger">(-<?= $hist['porcentaje_descuento'] ?>%)</small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="small">
                                                            <?php if (!empty($hist['fecha_inicio']) && !empty($hist['fecha_fin'])): ?>
                                                                <i class="far fa-calendar-alt text-primary mr-1"></i><?= date('d/m/y H:i', strtotime($hist['fecha_inicio'])) ?><br>
                                                                <i class="far fa-calendar-check text-danger mr-1"></i><?= date('d/m/y H:i', strtotime($hist['fecha_fin'])) ?>
                                                            <?php else: ?>
                                                                <span class="text-muted">Permanente (Base)</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="small text-muted">
                                                            <?= htmlspecialchars($hist['motivo'] ?? 'Ajuste general') ?>
                                                        </td>
                                                        <td class="small font-weight-bold text-dark">
                                                            <?= htmlspecialchars($hist['usuario']) ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($estado === 'activa'): ?>
                                                                <span class="status-pill status-pill-completado"><i class="fas fa-check-circle"></i> Activa</span>
                                                            <?php elseif ($estado === 'finalizada'): ?>
                                                                <span class="status-pill status-pill-cancelado"><i class="fas fa-history"></i> Finalizada</span>
                                                            <?php elseif ($estado === 'cancelada'): ?>
                                                                <span class="status-pill status-pill-cancelado"><i class="fas fa-ban"></i> Cancelada</span>
                                                            <?php else: ?>
                                                                <span class="status-pill status-pill-proceso"><i class="fas fa-check"></i> Aplicada</span>
                                                            <?php endif; ?>
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
                </div>

            </div>
            <!-- /.container-fluid -->

            <!-- Sticky Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Concentrados El Gordito &copy; <?= date('Y') ?> | Módulo de Precios y Promociones</span>
                    </div>
                </div>
            </footer>
        </div>
        <!-- /.content-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- =========================================================================
         MODAL 1: NIVELACIÓN DE PRECIO BASE (VENTA DIRECTA)
         ========================================================================= -->
    <div class="modal fade" id="modalNivelacion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <form action="controllerPromociones.php" method="POST">
                    <input type="hidden" name="accion" value="nivelacion">
                    <input type="hidden" name="idReceta" id="nivelacionIdReceta">

                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fas fa-balance-scale text-success mr-2"></i>Nivelación de Precio Base
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="alert alert-info small border-0 shadow-sm mb-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Nivelación de Precio:</strong> Modifica el precio base oficial de venta de la fórmula. <em>No se mostrará como oferta ni con precio tachado</em>; actualizará el precio de venta directo en todo el sistema.
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold text-muted small text-uppercase">Fórmula / Producto</label>
                            <input type="text" id="nivelacionNombreReceta" class="form-control font-weight-bold bg-light" readonly>
                        </div>

                        <div class="row">
                            <div class="col-6 form-group">
                                <label class="font-weight-bold small text-muted">Precio Base Actual</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="text" id="nivelacionPrecioActual" class="form-control bg-light" readonly>
                                </div>
                            </div>
                            <div class="col-6 form-group">
                                <label class="font-weight-bold text-dark">Nuevo Precio Base ($)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text font-weight-bold text-success">$</span></div>
                                    <input type="number" step="0.01" min="0.01" name="nuevoPrecio" id="nivelacionNuevoPrecio" class="form-control font-weight-bold" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Motivo de la Nivelación:</label>
                            <input type="text" name="motivo" class="form-control" placeholder="Ej. Aumento de costo en materias primas, reajuste trimestral" required>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark font-weight-bold px-4">
                            <i class="fas fa-check mr-1"></i> Aplicar Nivelación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         MODAL 2: CREAR / PROGRAMAR PROMOCIÓN TEMPORAL
         ========================================================================= -->
    <div class="modal fade" id="modalPromocion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <form id="formPromocion" action="controllerPromociones.php" method="POST">
                    <input type="hidden" name="accion" value="promocion">
                    <input type="hidden" name="idReceta" id="promoIdReceta">

                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fas fa-fire mr-2"></i>Programar Promoción Temporal
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="alert alert-warning small border-0 shadow-sm mb-3">
                            <i class="fas fa-clock mr-1"></i>
                            <strong>Vigencia Temporal:</strong> Mientras esté dentro del rango de fecha y hora, se mostrará como oferta en la Landing Page con el precio anterior tachado. <em>Al cumplirse la fecha de fin, el precio volverá automáticamente al valor regular y pasará al histórico.</em>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold text-muted small text-uppercase">Fórmula / Producto</label>
                            <input type="text" id="promoNombreReceta" class="form-control font-weight-bold bg-light" readonly>
                        </div>

                        <div class="row">
                            <div class="col-6 form-group">
                                <label class="font-weight-bold small text-muted">Precio Regular Base ($)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="number" step="0.01" name="precioRegular" id="promoPrecioRegular" class="form-control font-weight-bold" required oninput="calcularCalculoPromo()">
                                </div>
                            </div>
                            <div class="col-6 form-group">
                                <label class="font-weight-bold text-danger">Precio de Oferta ($)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text font-weight-bold text-danger">$</span></div>
                                    <input type="number" step="0.01" min="0.01" name="precioOferta" id="promoPrecioOferta" class="form-control font-weight-bold" required oninput="calcularCalculoPromo()">
                                </div>
                            </div>
                        </div>

                        <!-- Panel de Cálculo en Vivo -->
                        <div class="p-3 bg-light rounded-lg border mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="font-weight-bold text-dark">Descuento Promocional:</span>
                                <span class="badge badge-danger px-3 py-1 font-weight-bold h6 mb-0" id="badgeCalculoDescuento">0%</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted">Ahorro por cliente:</span>
                                <span class="font-weight-bold text-success" id="montoCalculoAhorro">$0.00 USD</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 form-group">
                                <label class="font-weight-bold small"><i class="far fa-calendar-alt text-primary mr-1"></i>Fecha y Hora Inicio:</label>
                                <input type="datetime-local" name="fechaInicio" id="promoFechaInicio" class="form-control small" required>
                            </div>
                            <div class="col-6 form-group">
                                <label class="font-weight-bold small"><i class="far fa-calendar-check text-danger mr-1"></i>Fecha y Hora Fin:</label>
                                <input type="datetime-local" name="fechaFin" id="promoFechaFin" class="form-control small" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Nombre de Campaña / Motivo:</label>
                            <input type="text" name="motivo" class="form-control" placeholder="Ej. Cyber Granja, Descuento Especial Avícola" required>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger font-weight-bold px-4">
                            <i class="fas fa-bolt mr-1"></i> Activar Promoción
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         MODAL 3: AJUSTE MASIVO DE PRECIOS (%)
         ========================================================================= -->
    <div class="modal fade" id="modalAjusteMasivo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <form action="controllerPromociones.php" method="POST">
                    <input type="hidden" name="accion" value="ajuste_masivo">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fas fa-percentage mr-2"></i>Ajuste Masivo de Precios
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-4">
                        <p class="text-muted small">
                            Aplica una nivelación porcentual a <strong>todas las fórmulas</strong> del catálogo en un solo paso.
                        </p>

                        <div class="form-group">
                            <label class="font-weight-bold">Tipo de Operación:</label>
                            <select name="tipoOperacion" class="form-control font-weight-bold">
                                <option value="aumentar">🔺 Aumentar Precios Base (+%)</option>
                                <option value="reducir">🔻 Reducir Precios Base (-%)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Porcentaje (%):</label>
                            <div class="input-group">
                                <input type="number" step="0.5" min="0.5" max="100" name="porcentaje" class="form-control font-weight-bold" placeholder="Ej. 5" required>
                                <div class="input-group-append"><span class="input-group-text font-weight-bold">%</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fas fa-check mr-1"></i> Aplicar a Todas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Formulario oculto para cancelar promoción -->
    <form id="formCancelarPromo" action="controllerPromociones.php" method="POST" style="display: none;">
        <input type="hidden" name="accion" value="cancelar_promocion">
        <input type="hidden" name="idReceta" id="cancelarIdReceta">
    </form>

    <!-- Scripts Base -->
    <script src="../controllers/vendor/jquery/jquery.min.js"></script>
    <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
    <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="../controllers/js/sb-admin.min.js"></script>

    <script>
        const promocionActivaActual = <?= json_encode($promocionActivaActual ?: null) ?>;

        $(document).ready(function() {
            $('#dataTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
                }
            });

            $('#dataTableHistorial').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
                },
                order: [[0, 'desc']]
            });

            <?php if (!empty($mensaje)): ?>
                Swal.fire({
                    icon: '<?= $tipoMensaje ?>',
                    title: '<?= ($tipoMensaje === "success" ? "¡Operación Exitosa!" : "Aviso") ?>',
                    text: '<?= addslashes($mensaje) ?>',
                    confirmButtonColor: '#059669'
                });
            <?php endif; ?>

            // Interceptar envío del formulario de promoción para validar promoción única
            $('#formPromocion').on('submit', function(e) {
                e.preventDefault();
                const idActual = parseInt($('#promoIdReceta').val()) || 0;
                const nombreActual = $('#promoNombreReceta').val();
                const form = this;

                if (promocionActivaActual && parseInt(promocionActivaActual.idReceta) !== idActual) {
                    Swal.fire({
                        title: '⚠️ ¿Reemplazar promoción activa?',
                        html: `Actualmente ya existe una promoción activa para <b>"${promocionActivaActual.nombreReceta}"</b> (Oferta: $${parseFloat(promocionActivaActual.PrecioUnitario).toFixed(2)} USD).<br><br><b>Regla del Sistema:</b> Solo puede haber <u>una única promoción activa</u> a la vez.<br><br>Si continúas, la promoción anterior será <b>cancelada automáticamente y pasará al histórico</b>, restaurando su precio regular, y se activará la nueva oferta para <b>"${nombreActual}"</b>.<br><br>¿Deseas confirmar el reemplazo?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Sí, cancelar anterior y activar nueva',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    form.submit();
                }
            });
        });

        // Abrir Modal de Nivelación
        function abrirModalNivelacion(id, nombre, precioActual) {
            $('#nivelacionIdReceta').val(id);
            $('#nivelacionNombreReceta').val(nombre);
            $('#nivelacionPrecioActual').val(precioActual.toFixed(2));
            $('#nivelacionNuevoPrecio').val(precioActual.toFixed(2));
            $('#modalNivelacion').modal('show');
        }

        // Abrir Modal de Promoción
        function abrirModalPromocion(id, nombre, precioBase, precioVenta, fInicio, fFin) {
            $('#promoIdReceta').val(id);
            $('#promoNombreReceta').val(nombre);
            $('#promoPrecioRegular').val(precioBase.toFixed(2));
            $('#promoPrecioOferta').val(precioVenta < precioBase ? precioVenta.toFixed(2) : (precioBase * 0.85).toFixed(2));

            // Configurar fechas por defecto si vienen vacías
            const now = new Date();
            const nowFormatted = now.toISOString().slice(0, 16);
            const in7Days = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
            const in7DaysFormatted = in7Days.toISOString().slice(0, 16);

            if (fInicio) {
                $('#promoFechaInicio').val(fInicio.replace(' ', 'T').slice(0, 16));
            } else {
                $('#promoFechaInicio').val(nowFormatted);
            }

            if (fFin) {
                $('#promoFechaFin').val(fFin.replace(' ', 'T').slice(0, 16));
            } else {
                $('#promoFechaFin').val(in7DaysFormatted);
            }

            calcularCalculoPromo();
            $('#modalPromocion').modal('show');
        }

        // Cálculo dinámico de % de descuento y ahorro
        function calcularCalculoPromo() {
            const regular = parseFloat($('#promoPrecioRegular').val()) || 0;
            const oferta = parseFloat($('#promoPrecioOferta').val()) || 0;

            if (regular > oferta && oferta > 0) {
                const ahorro = regular - oferta;
                const pct = Math.round((ahorro / regular) * 100);
                $('#badgeCalculoDescuento').text('-' + pct + '%');
                $('#montoCalculoAhorro').text('$' + ahorro.toFixed(2) + ' USD');
            } else {
                $('#badgeCalculoDescuento').text('0%');
                $('#montoCalculoAhorro').text('$0.00 USD');
            }
        }

        // Cancelar promoción anticipada
        function cancelarPromocion(id, nombre) {
            Swal.fire({
                title: '¿Finalizar promoción?',
                text: 'La oferta de "' + nombre + '" será desactivada y volverá a su precio base regular.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, finalizar oferta',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#cancelarIdReceta').val(id);
                    $('#formCancelarPromo').submit();
                }
            });
        }
    </script>
</body>
</html>
