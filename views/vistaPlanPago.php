<?php include '../views/configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>💳 Planes de Pago</title>

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
    <script src="../controllers/vendor/sweetalert2.all.min.js"></script>

    <script>
        const WOMPI_PUBLIC_KEY = '<?php echo WOMPI_PUBLIC_KEY; ?>';

        function comprarPlan(planId) {
            if (!WOMPI_PUBLIC_KEY || WOMPI_PUBLIC_KEY.includes('XXXX')) {
                Swal.fire('Configuracion pendiente', 'Las claves de Wompi no estan configuradas en db/parametros.php', 'warning');
                return;
            }
            $.ajax({
                url: '../wompi/create-checkout-session.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ planId: planId }),
                success: function(data) {
                    if (data.url) {
                        window.location.href = data.url;
                    } else {
                        Swal.fire('Error', data.error || 'No se pudo iniciar el pago', 'error');
                    }
                },
                error: function(xhr) {
                    let msg = 'Error al conectar con Wompi';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        msg = xhr.responseJSON.error;
                    }
                    Swal.fire('Error', msg, 'error');
                }
            });
        }
    </script>

</head>
<body id="page-top">
    <?php echo "$nav"; ?>
    <div id="wrapper">
        <?php echo "$menu"; ?>
        <div id="content-wrapper">
            <div class="container-fluid">
                <h2 class="text-center mb-4">Planes de Pago</h2>

                <?php if (isset($msj, $icon)): ?>
                    <script>
                        Swal.fire('<?php echo $msj; ?>', '', '<?php echo $icon; ?>');
                    </script>
                <?php endif; ?>

                <?php if ($esAdmin): ?>
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#formModal">
                    <i class="fas fa-plus"></i> Nuevo Plan
                </button>
                <?php endif; ?>

                <?php if ($esAdmin): ?>
                <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title" id="formModalLabel">Registrar Plan de Pago</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <form method="POST" action="">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nombre del Plan</label>
                                        <input type="text" name="nombrePlan" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Descripcion</label>
                                        <textarea name="descripcion" class="form-control" rows="2"></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Monto</label>
                                                <input type="number" step="0.01" name="monto" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Duracion (dias)</label>
                                                <input type="number" name="duracionDias" class="form-control" value="30" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="activo" checked> Activo
                                        </label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="insertar" class="btn btn-info">Guardar Plan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card mb-3">
                    <div class="card-header"><i class="fas fa-table"></i> Lista de Planes de Pago</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered datatable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th>Monto</th>
                                        <th>Duracion</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($planes as $p): ?>
                                        <tr>
                                            <td><?php echo $p['idPlanPago']; ?></td>
                                            <td><?php echo htmlspecialchars($p['nombrePlan']); ?></td>
                                            <td><?php echo htmlspecialchars($p['descripcion'] ?? ''); ?></td>
                                            <td>USD <?php echo number_format($p['monto'], 2); ?></td>
                                            <td><?php echo $p['duracion_dias']; ?> dias</td>
                                            <td>
                                                <span class="badge badge-<?php echo $p['activo'] ? 'success' : 'danger'; ?>">
                                                    <?php echo $p['activo'] ? 'Activo' : 'Inactivo'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($esAdmin): ?>
                                                <button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editModal<?php echo $p['idPlanPago']; ?>'>
                                                    <i class='fas fa-edit'></i> Editar
                                                </button>
                                                <?php else: ?>
                                                <button class='btn btn-success btn-sm' onclick="comprarPlan(<?php echo $p['idPlanPago']; ?>)">
                                                    <i class='fas fa-shopping-cart'></i> Comprar
                                                </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                        <?php if ($esAdmin): ?>
                                        <div class="modal fade" id="editModal<?php echo $p['idPlanPago']; ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning">
                                                        <h5 class="modal-title">Editar Plan de Pago</h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                                                    </div>
                                                    <form method="POST" action="">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="idPlanPago" value="<?php echo $p['idPlanPago']; ?>">
                                                            <div class="form-group">
                                                                <label>Nombre del Plan</label>
                                                                <input type="text" name="nombrePlan" class="form-control" value="<?php echo htmlspecialchars($p['nombrePlan']); ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Descripcion</label>
                                                                <textarea name="descripcion" class="form-control" rows="2"><?php echo htmlspecialchars($p['descripcion'] ?? ''); ?></textarea>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Monto</label>
                                                                        <input type="number" step="0.01" name="monto" class="form-control" value="<?php echo $p['monto']; ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Duracion (dias)</label>
                                                                        <input type="number" name="duracionDias" class="form-control" value="<?php echo $p['duracion_dias']; ?>" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>
                                                                    <input type="checkbox" name="activo" <?php echo $p['activo'] ? 'checked' : ''; ?>> Activo
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                            <button type="submit" name="modificar" class="btn btn-warning">Actualizar</button>
                                                            <button type="submit" name="eliminar" class="btn btn-danger" onclick="return confirm('¿Eliminar este plan?')">Eliminar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php if (empty($planes)) echo '<tr><td colspan="7" class="text-center">Sin registros</td></tr>'; ?>
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
