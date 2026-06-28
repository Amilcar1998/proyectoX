<?php include '../views/configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>💵 Pagos | Concentrados El Gordito</title>

    <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../controllers/vendor/sb-admin.css" rel="stylesheet" />
    <script src="../controllers/vendor/jquery/jquery.min.js"></script>
    <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
    <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="../controllers/js/sb-admin.min.js"></script>
    <script src="js/translations.js"></script>
    <script src="js/demo/datatables-demo.js"></script>

</head>
<body id="page-top">
    <?php echo "$nav"; ?>
    <div id="wrapper">
        <?php echo "$menu"; ?>
        <div id="content-wrapper">
            <div class="container-fluid">
                <h2 class="text-center mb-4"><i class="fas fa-money-bill-wave"></i> Pagos Recibidos</h2>

                <?php if ($esAdmin): ?>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Transacciones</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($pagos); ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Monto Total Recaudado</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">USD <?php echo number_format(array_sum(array_column($pagos, 'monto')), 2); ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pagos Completados</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count(array_filter($pagos, function($p){ return $p['estado'] === 'completado'; })); ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-clipboard-check fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Mis Pagos</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['total']; ?> transacciones</div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-wallet fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pagado</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">USD <?php echo number_format($stats['totalMonto'], 2); ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card mb-3">
                    <div class="card-header"><i class="fas fa-table"></i> Lista de Pagos</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered datatable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Plan</th>
                                        <th>Monto</th>
                                        <th>Metodo</th>
                                        <th>Estado</th>
                                        <th>Referencia</th>
                                        <th>Fecha</th>
                                        <th>IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pagos as $p): ?>
                                    <tr>
                                        <td><?php echo $p['idPago']; ?></td>
                                        <td><?php echo htmlspecialchars($p['username'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($p['nombrePlan'] ?? 'Sin plan'); ?></td>
                                        <td>USD <?php echo number_format($p['monto'], 2); ?></td>
                                        <td><?php echo ucfirst($p['metodo_pago']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php
                                                echo $p['estado'] === 'completado' ? 'success' :
                                                    ($p['estado'] === 'pendiente' ? 'warning' :
                                                    ($p['estado'] === 'fallido' ? 'danger' : 'secondary'));
                                            ?>">
                                                <?php echo ucfirst($p['estado']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($p['referencia'] ?? '-'); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($p['fecha_hora'])); ?></td>
                                        <td><?php echo htmlspecialchars($p['ip_address'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($pagos)) echo '<tr><td colspan="9" class="text-center">Sin registros</td></tr>'; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <footer class="sticky-footer bg-dark mt-auto">
        <div class="container my-auto py-3">
            <div class="copyright text-center my-auto">
                <span class="text-white">Copyright &copy; Concentrados El Gordito 2026</span>
            </div>
        </div>
    </footer>
</body>
</html>
