<?php require_once __DIR__ . '/configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>💵 Pagos y Transacciones Wompi | Concentrados El Gordito</title>

    <!-- Hojas de estilo oficiales -->
    <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../controllers/vendor/sb-admin.css" rel="stylesheet" />

    <style>
        .badge-completado {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .badge-pendiente {
            background-color: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .badge-fallido {
            background-color: #fff1f2;
            color: #9f1239;
            border: 1px solid #fecdd3;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .badge-wompi {
            background-color: #4f46e5;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 4px;
        }
        .kpi-card {
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
        }
        .json-pre-box {
            background-color: #1e293b;
            color: #38bdf8;
            padding: 14px;
            border-radius: 8px;
            font-size: 0.82rem;
            max-height: 250px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-all;
        }
        .detail-item-label {
            color: #64748b;
            font-size: 0.82rem;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 2px;
        }
        .detail-item-val {
            font-weight: 700;
            color: #0f172a;
            font-size: 0.96rem;
        }
    </style>
</head>
<body id="page-top">
    <?php echo "$nav"; ?>
    <div id="wrapper">
        <?php echo "$menu"; ?>
        <div id="content-wrapper">
            <div class="container-fluid py-3">
                
                <!-- Encabezado de Página -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                            <i class="fas fa-credit-card text-success mr-2"></i>Control de Pagos y Transacciones Wompi
                        </h1>
                        <p class="text-muted small mb-0">
                            Registro en tiempo real de cobros en línea, pasarela Wompi El Salvador y pedidos asociados.
                        </p>
                    </div>
                    <div class="mt-2 mt-sm-0">
                        <span class="badge badge-success px-3 py-2 text-white">
                            <i class="fas fa-shield-alt mr-1"></i> Wompi SV Enlace Activo
                        </span>
                    </div>
                </div>

                <!-- Tarjetas KPI / Métricas -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-left-success shadow h-100 py-2 kpi-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Monto Total Recaudado</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                                            USD $<?php echo number_format($estadisticas['totalRecaudado'], 2); ?>
                                        </div>
                                        <div class="text-muted small mt-1">Pagos completados con éxito</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-left-primary shadow h-100 py-2 kpi-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Transacciones Aprobadas</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $estadisticas['totalCompletados']; ?>
                                        </div>
                                        <div class="text-muted small mt-1">De <?php echo $estadisticas['totalTransacciones']; ?> intentos totales</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-left-warning shadow h-100 py-2 kpi-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pagos Pendientes</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $estadisticas['totalPendientes']; ?>
                                        </div>
                                        <div class="text-muted small mt-1">Esperando confirmación</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-left-info shadow h-100 py-2 kpi-card">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ticket Promedio</div>
                                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                                            USD $<?php echo number_format($estadisticas['ticketPromedio'], 2); ?>
                                        </div>
                                        <div class="text-muted small mt-1">Por compra exitosa</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-receipt fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Datos Principal -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list-ul mr-2"></i>Historial de Pagos y Transacciones Wompi
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover datatable" id="tablaPagos" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th># ID</th>
                                        <th>Fecha y Hora</th>
                                        <th>Cliente / Contacto</th>
                                        <th>Concepto / Tipo</th>
                                        <th>Monto (USD)</th>
                                        <th>Estado</th>
                                        <th>ID Transacción Wompi</th>
                                        <th>Pedido Creado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pagos as $p): 
                                        $meta = $p['metadatos_array'] ?? [];
                                        $items = $meta['items'] ?? [];
                                        $idTxnWompi = $p['idTransaccionWompi'];
                                        if (empty($idTxnWompi)) {
                                            // Extraer del string si existe
                                            if (preg_match('/Aprobado.*?:\\s*([a-zA-Z0-9\\-]+)/', $p['descripcion'] ?? '', $matches)) {
                                                $idTxnWompi = $matches[1];
                                            }
                                        }
                                        $nombreConcepto = !empty($p['nombrePlan']) ? 'Plan: ' . $p['nombrePlan'] : ((!empty($items)) ? count($items) . ' productos (Carrito)' : 'Compra Productos');
                                    ?>
                                    <tr>
                                        <td class="font-weight-bold">
                                            #<?php echo $p['idPago']; ?>
                                            <div class="text-muted font-monospace" style="font-size: 0.72rem;"><?php echo htmlspecialchars($p['referencia'] ?? ''); ?></div>
                                        </td>
                                        <td style="white-space: nowrap;">
                                            <div><i class="far fa-calendar-alt text-muted mr-1"></i><?php echo date('d/m/Y', strtotime($p['fecha_hora'])); ?></div>
                                            <div class="text-muted small"><i class="far fa-clock text-muted mr-1"></i><?php echo date('h:i A', strtotime($p['fecha_hora'])); ?></div>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold text-dark"><?php echo htmlspecialchars($p['nombreCliente']); ?></div>
                                            <?php if (!empty($p['telefonoCliente'])): ?>
                                                <div class="small text-muted"><i class="fas fa-phone-alt mr-1"></i><?php echo htmlspecialchars($p['telefonoCliente']); ?></div>
                                            <?php endif; ?>
                                            <?php if (!empty($p['correoCliente'])): ?>
                                                <div class="small text-muted"><i class="fas fa-envelope mr-1"></i><?php echo htmlspecialchars($p['correoCliente']); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-light border text-dark">
                                                <i class="fas fa-box-open mr-1 text-primary"></i><?php echo htmlspecialchars($nombreConcepto); ?>
                                            </span>
                                        </td>
                                        <td class="font-weight-bold text-success font-monospace" style="font-size: 1.05rem;">
                                            $<?php echo number_format((float)$p['monto'], 2); ?>
                                        </td>
                                        <td>
                                            <?php if ($p['estado'] === 'completado'): ?>
                                                <span class="badge badge-completado"><i class="fas fa-check-circle mr-1"></i>Aprobado</span>
                                            <?php elseif ($p['estado'] === 'pendiente'): ?>
                                                <span class="badge badge-pendiente"><i class="fas fa-clock mr-1"></i>Pendiente</span>
                                            <?php else: ?>
                                                <span class="badge badge-fallido"><i class="fas fa-times-circle mr-1"></i><?php echo ucfirst($p['estado']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($idTxnWompi)): ?>
                                                <span class="badge badge-light border font-monospace text-primary" title="<?php echo htmlspecialchars($idTxnWompi); ?>" style="font-size: 0.8rem;">
                                                    <i class="fas fa-receipt mr-1"></i><?php echo htmlspecialchars(substr($idTxnWompi, 0, 16)); ?><?php echo strlen($idTxnWompi) > 16 ? '...' : ''; ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted small">Wompi SV</span>
                                            <?php endif; ?>
                                            <?php if (!empty($p['idEnlaceWompi'])): ?>
                                                <div class="text-muted font-monospace" style="font-size: 0.72rem;">Enlace: <?php echo htmlspecialchars($p['idEnlaceWompi']); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($p['idPedidoCreado'])): ?>
                                                <a href="controllerPedidos.php" class="btn btn-sm btn-outline-primary py-1 px-2 font-weight-bold" title="Ver pedido en producción">
                                                    <i class="fas fa-box mr-1"></i>#<?php echo $p['idPedidoCreado']; ?>
                                                </a>
                                            <?php elseif (!empty($p['idPlanPago'])): ?>
                                                <span class="badge badge-info"><i class="fas fa-certificate mr-1"></i>Plan Activo</span>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-primary py-1 px-2 btn-ver-detalle" 
                                                    data-id="<?php echo $p['idPago']; ?>" 
                                                    title="Ver toda la información devuelta por Wompi">
                                                <i class="fas fa-eye mr-1"></i>Detalle Wompi
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($pagos)): ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-4 text-muted">
                                                <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                                Aún no se han registrado transacciones o cobros en la base de datos.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Extra Grande para Detalle Completo de Transacción Wompi -->
    <div class="modal fade" id="modalDetallePago" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document" style="max-width: 1250px; width: 95%;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header bg-dark text-white px-4 py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 d-inline-flex align-items-center" id="modalLabel">
                            <i class="fas fa-credit-card text-success mr-2"></i>Detalle de Transacción Oficial Wompi El Salvador
                        </h5>
                        <div class="text-white-50 small mt-1" id="modalHeaderSub">Comprobante y auditoría de pago en línea</div>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="font-size: 1.6rem; opacity: 0.85;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 bg-light" id="modalContenidoDetalle" style="max-height: calc(85vh - 120px); overflow-y: auto;">
                    <div class="text-center py-5">
                        <i class="fas fa-spinner fa-spin fa-3x text-success"></i>
                        <div class="mt-3 text-muted h6 font-weight-normal">Cargando toda la información devuelta por Wompi...</div>
                    </div>
                </div>
                <div class="modal-footer bg-white px-4 py-3 d-flex justify-content-between">
                    <div>
                        <button type="button" class="btn btn-outline-primary" onclick="copiarJsonWompi()">
                            <i class="fas fa-copy mr-1"></i>Copiar Payload JSON
                        </button>
                        <button type="button" class="btn btn-outline-secondary ml-2" onclick="imprimirModalDetalle()">
                            <i class="fas fa-print mr-1"></i>Imprimir Comprobante
                        </button>
                    </div>
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="sticky-footer bg-dark mt-auto">
        <div class="container my-auto py-3">
            <div class="copyright text-center my-auto">
                <span class="text-white">Copyright &copy; Concentrados El Gordito 2026 • Módulo Oficial Wompi</span>
            </div>
        </div>
    </footer>

    <!-- Scripts Oficiales -->
    <script src="../controllers/vendor/jquery/jquery.min.js"></script>
    <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
    <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="js/sb-admin.min.js"></script>
    <script src="js/translations.js"></script>
    <script src="js/demo/datatables-demo.js"></script>

    <script>
        let ultimoJsonCargado = "";

        $(document).ready(function() {
            // Manejar clic en "Detalle Wompi"
            $('.btn-ver-detalle').on('click', function() {
                const idPago = $(this).data('id');
                $('#modalDetallePago').modal('show');
                $('#modalHeaderSub').text(`Consultando registro de pago #${idPago}...`);
                $('#modalContenidoDetalle').html(`
                    <div class="text-center py-5">
                        <i class="fas fa-spinner fa-spin fa-3x text-success"></i>
                        <div class="mt-3 text-muted h6 font-weight-normal">Obteniendo datos de Wompi para el pago #${idPago}...</div>
                    </div>
                `);

                $.ajax({
                    url: 'controllerPagos.php',
                    type: 'GET',
                    data: { accion: 'obtenerDetalle', idPago: idPago },
                    dataType: 'json',
                    success: function(resp) {
                        if (resp.status === 'success' && resp.pago) {
                            renderizarDetallePago(resp.pago);
                        } else {
                            $('#modalContenidoDetalle').html(`
                                <div class="alert alert-danger p-4 text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                                    No se pudo cargar la información del pago solicitado.
                                </div>
                            `);
                        }
                    },
                    error: function() {
                        $('#modalContenidoDetalle').html(`
                            <div class="alert alert-danger p-4 text-center">
                                <i class="fas fa-exclamation-circle fa-2x mb-2 d-block"></i>
                                Error al conectar con el servidor para obtener los datos de Wompi.
                            </div>
                        `);
                    }
                });
            });
        });

        function renderizarDetallePago(p) {
            const meta = p.metadatos_array || {};
            const wompiRetorno = meta.wompi_retorno || {};
            const items = meta.items || [];
            ultimoJsonCargado = JSON.stringify(p, null, 2);

            $('#modalHeaderSub').html(`Pago <strong>#${p.idPago}</strong> • Ref: <span class="font-monospace text-warning">${p.referencia || '-'}</span> • Fecha: ${p.fecha_hora}`);

            let estadoBadge = `<span class="badge badge-completado px-3 py-2 text-uppercase font-weight-bold" style="font-size: 0.85rem;"><i class="fas fa-check-circle mr-1"></i>Aprobado por Wompi</span>`;
            if (p.estado === 'pendiente') {
                estadoBadge = `<span class="badge badge-pendiente px-3 py-2 text-uppercase font-weight-bold" style="font-size: 0.85rem;"><i class="fas fa-clock mr-1"></i>Pendiente</span>`;
            } else if (p.estado === 'fallido') {
                estadoBadge = `<span class="badge badge-fallido px-3 py-2 text-uppercase font-weight-bold" style="font-size: 0.85rem;"><i class="fas fa-times-circle mr-1"></i>Fallido / Declinado</span>`;
            }

            // Desglose de productos o plan
            let htmlProductos = '';
            if (items.length > 0) {
                htmlProductos = `
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered bg-white mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 60px;" class="text-center">Cant.</th>
                                    <th>Producto / Mezcla</th>
                                    <th class="text-right" style="width: 110px;">P. Unitario</th>
                                    <th class="text-right" style="width: 120px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                let totalSuma = 0;
                items.forEach(it => {
                    const cant = it.cantidad || 1;
                    const prec = parseFloat(it.precio || 0);
                    const sub = cant * prec;
                    totalSuma += sub;
                    htmlProductos += `
                        <tr>
                            <td class="text-center font-weight-bold bg-light">${cant}</td>
                            <td>
                                <div class="font-weight-bold text-dark">${it.nombre || 'Concentrado'}</div>
                                ${it.unidad ? `<small class="text-muted">${it.unidad}</small>` : ''}
                            </td>
                            <td class="text-right text-muted">$${prec.toFixed(2)}</td>
                            <td class="text-right font-weight-bold text-dark">$${sub.toFixed(2)}</td>
                        </tr>
                    `;
                });
                htmlProductos += `
                            </tbody>
                            <tfoot class="bg-light font-weight-bold">
                                <tr>
                                    <td colspan="3" class="text-right text-uppercase">Total Calculado:</td>
                                    <td class="text-right text-success h6 mb-0 font-monospace font-weight-bold">$${totalSuma.toFixed(2)}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                `;
            } else if (p.nombrePlan) {
                htmlProductos = `
                    <div class="p-3 bg-white rounded border border-success">
                        <div class="d-flex align-items-center mb-2">
                            <div class="mr-3 text-success"><i class="fas fa-certificate fa-2x"></i></div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-0">${p.nombrePlan}</h6>
                                <div class="small text-muted">Suscripción comercial por 30 días de cobertura nutricional y despacho prioritario.</div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                htmlProductos = `
                    <div class="p-3 bg-white rounded border text-muted small">
                        Compra directa de productos en línea procesada exitosamente en Wompi SV.
                    </div>
                `;
            }

            const idTxnMostrar = wompiRetorno.idTransaccion || p.idTransaccionWompi || 'Aprobado en Wompi SV';
            const idEnlaceMostrar = wompiRetorno.idEnlace || p.idEnlaceWompi || '-';
            const codAuthMostrar = wompiRetorno.codigoAutorizacion || 'Transacción Aprobada';
            const formaPagoMostrar = wompiRetorno.formaPago || 'Tarjeta Débito / Crédito';

            const html = `
                <!-- Fila Superior: 4 Métricas Clave del Pago -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-2">
                        <div class="bg-white p-3 rounded shadow-sm border-left-success h-100" style="border-left: 4px solid #10b981;">
                            <div class="text-xs text-muted text-uppercase font-weight-bold mb-1">Monto Cobrado</div>
                            <div class="h3 font-weight-bold text-success mb-0 font-monospace">$${parseFloat(p.monto).toFixed(2)} <span class="fs-6 text-muted" style="font-size: 0.9rem;">USD</span></div>
                            <div class="small text-muted">Sin recargos</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="bg-white p-3 rounded shadow-sm border-left-primary h-100" style="border-left: 4px solid #4f46e5;">
                            <div class="text-xs text-muted text-uppercase font-weight-bold mb-1">Estado en Pasarela</div>
                            <div class="mb-0 mt-1">${estadoBadge}</div>
                            <div class="small text-muted mt-1">Confirmado por Wompi</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="bg-white p-3 rounded shadow-sm border-left-info h-100" style="border-left: 4px solid #0ea5e9;">
                            <div class="text-xs text-muted text-uppercase font-weight-bold mb-1">Código de Autorización</div>
                            <div class="h5 font-weight-bold text-dark mb-0 font-monospace mt-1">${codAuthMostrar}</div>
                            <div class="small text-muted">Aprobación bancaria</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="bg-white p-3 rounded shadow-sm border-left-warning h-100" style="border-left: 4px solid #f59e0b;">
                            <div class="text-xs text-muted text-uppercase font-weight-bold mb-1">Forma de Pago</div>
                            <div class="h6 font-weight-bold text-dark mb-0 mt-1"><i class="fas fa-credit-card text-primary mr-1"></i>${formaPagoMostrar}</div>
                            <div class="small text-muted">Wompi El Salvador</div>
                        </div>
                    </div>
                </div>

                <!-- Cuadrícula Principal de 2 Columnas -->
                <div class="row">
                    <!-- Columna Izquierda: Cliente, Pedido y Wompi IDs -->
                    <div class="col-lg-6 mb-3">
                        <!-- Tarjeta Datos del Cliente -->
                        <div class="card shadow-sm border-0 mb-3" style="border-radius: 12px;">
                            <div class="card-header bg-white font-weight-bold py-3 text-dark d-flex align-items-center">
                                <i class="fas fa-user-circle text-primary mr-2"></i>Información del Cliente y Despacho
                            </div>
                            <div class="card-body py-3">
                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        <div class="detail-item-label">Nombre / Granja</div>
                                        <div class="detail-item-val">${p.nombreCliente || 'Cliente Web'}</div>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <div class="detail-item-label">Teléfono de Contacto</div>
                                        <div class="detail-item-val font-monospace">${p.telefonoCliente ? `<a href="tel:${p.telefonoCliente}"><i class="fas fa-phone-alt mr-1"></i>${p.telefonoCliente}</a>` : '<span class="text-muted font-italic">No especificado</span>'}</div>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <div class="detail-item-label">Correo Electrónico</div>
                                        <div class="detail-item-val">${p.correoCliente ? `<a href="mailto:${p.correoCliente}"><i class="fas fa-envelope mr-1"></i>${p.correoCliente}</a>` : '<span class="text-muted font-italic">No especificado</span>'}</div>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <div class="detail-item-label">Usuario en Sistema</div>
                                        <div class="detail-item-val">${p.username || 'Invitado (Web)'}</div>
                                    </div>
                                </div>

                                ${p.idPedidoCreado ? `
                                <div class="alert alert-success d-flex align-items-center justify-content-between mb-0 mt-2 py-2 px-3 rounded">
                                    <div>
                                        <i class="fas fa-box-open mr-2 text-success"></i>
                                        <strong>Orden de Producción Generada:</strong> Pedido #${p.idPedidoCreado}
                                    </div>
                                    <a href="controllerPedidos.php" class="btn btn-sm btn-success font-weight-bold">
                                        Ver Pedido &rarr;
                                    </a>
                                </div>` : ''}
                            </div>
                        </div>

                        <!-- Tarjeta Parámetros Oficiales Wompi -->
                        <div class="card shadow-sm border-0 mb-3" style="border-radius: 12px;">
                            <div class="card-header bg-white font-weight-bold py-3 text-dark d-flex align-items-center">
                                <i class="fas fa-fingerprint text-success mr-2"></i>Identificadores y Trazabilidad Wompi
                            </div>
                            <div class="card-body py-3">
                                <div class="mb-3">
                                    <div class="detail-item-label">ID Transacción Oficial Wompi</div>
                                    <div class="p-2 bg-light rounded font-monospace font-weight-bold text-primary d-flex align-items-center justify-content-between">
                                        <span class="text-break">${idTxnMostrar}</span>
                                        <button class="btn btn-sm btn-outline-secondary py-0 px-2 ml-2" onclick="navigator.clipboard.writeText('${idTxnMostrar}'); alert('ID Transacción copiado');" title="Copiar ID">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        <div class="detail-item-label">ID Enlace Wompi</div>
                                        <div class="detail-item-val font-monospace">${idEnlaceMostrar}</div>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <div class="detail-item-label">Referencia Comercio</div>
                                        <div class="detail-item-val font-monospace text-dark">${p.referencia || '-'}</div>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <div class="detail-item-label">Dirección IP Comprador</div>
                                        <div class="detail-item-val font-monospace text-muted small">${p.ip_address || '127.0.0.1'}</div>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <div class="detail-item-label">Fecha y Hora Registro</div>
                                        <div class="detail-item-val small">${p.fecha_hora}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Desglose de Productos y Payload JSON -->
                    <div class="col-lg-6 mb-3">
                        <!-- Tarjeta Desglose de Compra -->
                        <div class="card shadow-sm border-0 mb-3" style="border-radius: 12px;">
                            <div class="card-header bg-white font-weight-bold py-3 text-dark d-flex align-items-center justify-content-between">
                                <span><i class="fas fa-shopping-bag text-warning mr-2"></i>Desglose de la Compra</span>
                                <span class="badge badge-light border text-muted">${items.length > 0 ? items.length + ' ítems' : (p.nombrePlan || 'Servicio')}</span>
                            </div>
                            <div class="card-body py-3">
                                ${htmlProductos}
                            </div>
                        </div>

                        <!-- Tarjeta Payload Completo JSON de Wompi -->
                        <div class="card shadow-sm border-0 mb-3" style="border-radius: 12px;">
                            <div class="card-header bg-white font-weight-bold py-3 text-dark d-flex align-items-center justify-content-between">
                                <span><i class="fas fa-code text-info mr-2"></i>Datos Devueltos por Wompi (JSON Completo)</span>
                                <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" onclick="copiarJsonWompi()">
                                    <i class="fas fa-copy mr-1"></i>Copiar JSON
                                </button>
                            </div>
                            <div class="card-body p-2">
                                <div class="json-pre-box" style="max-height: 280px;">${ultimoJsonCargado}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $('#modalContenidoDetalle').html(html);
        }

        function copiarJsonWompi() {
            if (!ultimoJsonCargado) return;
            navigator.clipboard.writeText(ultimoJsonCargado).then(() => {
                alert('¡Datos completos de la transacción copiados al portapapeles!');
            }).catch(() => {
                alert('No se pudo copiar automáticamente. Puedes seleccionarlo directamente desde el recuadro.');
            });
        }

        function imprimirModalDetalle() {
            const contenido = document.getElementById('modalContenidoDetalle').innerHTML;
            const ventana = window.open('', '', 'height=750,width=900');
            ventana.document.write(`
                <html>
                <head>
                    <title>Comprobante de Pago Wompi - Concentrados El Gordito</title>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
                    <style>
                        body { padding: 30px; font-family: sans-serif; }
                        .json-pre-box, button, .btn { display: none !important; }
                    </style>
                </head>
                <body>
                    <div class="text-center mb-4">
                        <h3>Concentrados El Gordito</h3>
                        <h5>Comprobante Oficial de Transacción Wompi SV</h5>
                    </div>
                    ${contenido}
                </body>
                </html>
            `);
            ventana.document.close();
            ventana.focus();
            setTimeout(() => {
                ventana.print();
                ventana.close();
            }, 500);
        }
    </script>
</body>
</html>
