<?php include '../views/configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Pedidos a Proveedor - Concentrados El Gordito">
    <meta name="author" content="">

    <title>📦 Pedidos a Proveedor - Concentrados El Gordito</title>

    <!-- Custom fonts for this template-->
    <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../controllers/vendor/sb-admin.css" rel="stylesheet">
</head>

<body id="page-top">
  <?php echo "$nav"; ?>

  <div id="wrapper">

    <!-- Sidebar -->
    <?php echo "$menu"; ?>

    <div id="content-wrapper">

        <div class="container-fluid">

          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="controllerDashboard.php">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Pedidos a Proveedor</li>
          </ol>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0"><i class="fas fa-truck-loading text-primary mr-2"></i>Pedidos a Proveedor</h2>
            <button type="button" class="btn btn-primary Nagregar" data-toggle="modal" data-target="#modalPedidoProveedor" onclick="limpiarFormulario()">
              <i class="fas fa-plus mr-1"></i> Agregar Pedido
            </button>
          </div>
         
          <!-- Modal Pedido Proveedor -->
          <div class="modal fade" id="modalPedidoProveedor" tabindex="-1" role="dialog" aria-labelledby="modalPedidoProveedorLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title" id="modalPedidoProveedorLabel"><i class="fas fa-dolly mr-2"></i>Registro de Pedidos a Proveedor</h5>
                  <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form method="POST" id="miForm" name="formulario">
                  <div class="modal-body">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="txtIdPe">ID Pedido</label>
                        <input type="text" name="txtIdPe" id="txtIdPe" value="" class="form-control" readonly placeholder="Generado automáticamente">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="txtIdPro">Proveedor</label>
                        <select name="txtIdPro" id="txtIdPro" class="form-control" required>
                          <option value="">Seleccione proveedor...</option>
                          <?php foreach ($proveedores as $p) {
                            echo "<option value='".$p["idProveedor"]."'>".$p["nombreProveedor"]."</option>";
                          } ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="txtIdEmp">Empleado Responsable</label>
                        <select name="txtIdEmp" id="txtIdEmp" class="form-control" required>
                          <option value="">Seleccione empleado...</option>
                          <?php foreach ($empleados as $e) {
                            echo "<option value='".$e["idEmpleado"]."'>".$e["nombreEmp"]." ".$e["apellido"]."</option>";
                          } ?>
                        </select>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="txtIdMp">Materia Prima</label>
                        <select name="txtIdMp" id="txtIdMp" class="form-control" required>
                          <option value="">Seleccione materia prima...</option>
                          <?php foreach ($materiasPrimas as $mp) {
                            echo "<option value='".$mp["idMateriaPrima"]."'>".$mp["NombreMP"]."</option>";
                          } ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="txtFec">Fecha</label>
                        <input type="date" name="txtFec" id="txtFec" value="" class="form-control" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="txtCan">Cantidad</label>
                        <input type="number" step="any" name="txtCan" id="txtCan" value="" class="form-control" placeholder="Cantidad" required>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="txtPre">Precio Unitario ($)</label>
                        <input type="number" step="0.01" name="txtPre" id="txtPre" value="" class="form-control" placeholder="0.00" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="txtMon">Monto Total ($)</label>
                        <input type="number" step="0.01" name="txtMon" id="txtMon" value="" class="form-control" placeholder="0.00" required>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" value="guardar" name="btnGuardar" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Guardar</button>
                    <button type="submit" value="modificar" name="btnModificar" class="btn btn-warning"><i class="fas fa-edit mr-1"></i>Modificar</button>
                    <button type="submit" value="eliminar" name="btnEliminar" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar este pedido?');"><i class="fas fa-trash-alt mr-1"></i>Eliminar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- DataTables Pedidos a Proveedor -->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-table mr-1"></i>
              Listado de Pedidos a Proveedor
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered datatable" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <?php if (!empty($esSuperUsuario)): ?>
                        <th>Empresa</th>
                      <?php endif; ?>
                      <th>Proveedor</th>
                      <th>Empleado</th>
                      <th>Materia Prima</th>
                      <th>Fecha</th>
                      <th>Cantidad</th>
                      <th>Monto</th>
                      <th>Precio</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php if (!empty($tabla)): ?>
                    <?php foreach ($tabla as $fila): ?>
                      <tr>
                        <?php if (!empty($esSuperUsuario)): ?>
                          <td><span class="badge badge-primary px-2 py-1"><i class="fas fa-building mr-1"></i><?php echo htmlspecialchars($fila['nombreEmpresa'] ?? 'Concentrados El Gordito'); ?></span></td>
                        <?php endif; ?>
                        <td><?php echo htmlspecialchars($fila['nombreProveedor'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($fila['empleado'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($fila['NombreMP'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($fila['fecha'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($fila['cantidadMP'] ?? ''); ?></td>
                        <td>$<?php echo number_format((float)($fila['monto'] ?? 0), 2); ?></td>
                        <td>$<?php echo number_format((float)($fila['precioMP'] ?? 0), 2); ?></td>
                        <td>
                          <button type="button" class="btn btn-info btn-sm cargar" data-toggle="modal" data-target="#modalPedidoProveedor" onclick='cargar("<?php echo htmlspecialchars($fila['idPedido'] ?? '', ENT_QUOTES); ?>", "<?php echo htmlspecialchars($fila['idProveedor'] ?? '', ENT_QUOTES); ?>", "<?php echo htmlspecialchars($fila['idEmpleado'] ?? '', ENT_QUOTES); ?>", "<?php echo htmlspecialchars($fila['idMateriaPrima'] ?? '', ENT_QUOTES); ?>", "<?php echo htmlspecialchars($fila['fecha'] ?? '', ENT_QUOTES); ?>", "<?php echo htmlspecialchars($fila['cantidadMP'] ?? '', ENT_QUOTES); ?>", "<?php echo htmlspecialchars($fila['monto'] ?? '', ENT_QUOTES); ?>", "<?php echo htmlspecialchars($fila['precioMP'] ?? '', ENT_QUOTES); ?>")'>
                            <i class="fas fa-edit mr-1"></i> Cargar
                          </button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer small text-muted">Actualizado el <?php echo date('d/m/Y \a \l\a\s H:i'); ?></div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- /#content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="logoutModalLabel"><i class="fas fa-sign-out-alt mr-2"></i>¿Desea cerrar sesión?</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Selecciona "Cerrar sesión" si estás listo para finalizar tu sesión actual.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <a class="btn btn-danger" href="sesiones.php?c=c">Cerrar sesión</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../controllers/vendor/jquery/jquery.min.js"></script>
    <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
    <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../controllers/js/sb-admin.min.js"></script>

    <!-- Demo scripts for this page-->
    <script src="../controllers/js/translations.js"></script>
    <script src="../controllers/js/demo/datatables-demo.js"></script>

    <script>
      function cargar(idPe, idPro, idEmp, idMp, fec, can, mon, pre) {
        $('#txtIdPe').val(idPe);
        $('#txtIdPro').val(idPro);
        $('#txtIdEmp').val(idEmp);
        $('#txtIdMp').val(idMp);
        $('#txtFec').val(fec);
        $('#txtCan').val(can);
        $('#txtMon').val(mon);
        $('#txtPre').val(pre);
      }

      function limpiarFormulario() {
        $('#txtIdPe').val('');
        $('#txtIdPro').val('');
        $('#txtIdEmp').val('');
        $('#txtIdMp').val('');
        $('#txtFec').val('');
        $('#txtCan').val('');
        $('#txtMon').val('');
        $('#txtPre').val('');
      }

      // Auto-calcular monto = cantidad * precio
      $(document).ready(function() {
        $('#txtCan, #txtPre').on('input', function() {
          var can = parseFloat($('#txtCan').val()) || 0;
          var pre = parseFloat($('#txtPre').val()) || 0;
          if (can > 0 && pre > 0) {
            $('#txtMon').val((can * pre).toFixed(2));
          }
        });
      });
    </script>

    <!-- Footer -->
    <footer class="sticky-footer bg-dark mt-auto">
      <div class="container my-auto py-3">
        <div class="copyright text-center my-auto">
          <span class="text-white">Copyright &copy; Concentrados El Gordito 2026</span>
        </div>
      </div>
    </footer>

</body>
</html>
