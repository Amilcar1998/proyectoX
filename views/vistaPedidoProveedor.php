<?php include 'configuracion.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>🛒 Pedidos a Proveedor</title>

    <!-- Custom fonts for this template-->
    <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../controllers/vendor/sb-admin.css" rel="stylesheet">

</head>

<body id="page-top">
  <?php 
       echo "$nav";
      ?>
  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <?php 
       echo "$menu";
      ?>

    <div id="content-wrapper">

        <div class="container-fluid">

          <hr>
         
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#formModal">
      <i class="fas fa-plus"></i> Agregar Pedido
    </button>
    
    <!-- Modal -->
    <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-info text-white">
            <h5 class="modal-title" id="formModalLabel">Registro de Pedidos a Proveedor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="POST" name="formulario">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>ID Pedido</label>
                    <input type="text" name="txtIdPe" value="" class="form-control" readonly>
                  </div>
                </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>ID Proveedor</label>
                          <select name="txtIdPro" class="form-control">
                            <option value="">Seleccione proveedor</option>
                            <?php foreach ($proveedores as $p) {
                              echo "<option value='".$p["idProveedor"]."'>".$p["nombreProveedor"]."</option>";
                            } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>ID Empleado</label>
                          <select name="txtIdEmp" class="form-control">
                            <option value="">Seleccione empleado</option>
                            <?php foreach ($empleados as $e) {
                              echo "<option value='".$e["idEmpleado"]."'>".$e["nombreEmp"]." ".$e["apellido"]."</option>";
                            } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Materia Prima</label>
                          <select name="txtIdMp" class="form-control">
                            <option value="">Seleccione materia prima</option>
                            <?php foreach ($materiasPrimas as $mp) {
                              echo "<option value='".$mp["idMateriaPrima"]."'>".$mp["NombreMP"]."</option>";
                            } ?>
                          </select>
                        </div>
                      </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>ID Empleado</label>
                    <input type="text" name="txtIdEmp" value="" class="form-control" placeholder="ID Empleado">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Materia Prima</label>
                    <select name="txtIdMp" class="form-control">
                      <option value="">Seleccione materia prima</option>
                      <option value="1">Maiz Amarillo</option>
                      <option value="2">Maiz Blanco</option>
                      <option value="3">Soya</option>
                      <option value="4">Harina</option>
                      <option value="5">Maicillo</option>
                      <option value="6">Trigo</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Fecha</label>
                    <input type="text" name="txtFec" value="" class="form-control" placeholder="Fecha">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Cantidad</label>
                    <input type="text" name="txtCan" value="" class="form-control" placeholder="Cantidad qq">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Monto</label>
                    <input type="text" name="txtMon" value="" class="form-control" placeholder="Monto">
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label>Precio</label>
                <input type="text" name="txtPre" value="" class="form-control" placeholder="Precio">
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" value="guardar" name="btnGuardar" class="btn btn-primary">Guardar</button>
                <button type="submit" value="modificar" name="btnModificar" class="btn btn-warning">Modificar</button>
                <button type="submit" value="eliminar" name="btnEliminar" class="btn btn-danger">Eliminar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- DataTables Example -->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fas fa-table"></i>
        Pedidos a Proveedor
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered datatable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Proveedor</th>
                <th>Empleado</th>
                <th>Materia Prima</th>
                <th>FECHA</th>
                <th>CANTIDAD</th>
                <th>MONTO</th>
                <th>PRECIO</th>
                <th>ACCIONES</th>
              </tr>
            </thead>
            <tbody>
            <?php 
            foreach ($tabla as $fila) {
                echo "<tr>
                    <td>".($fila['nombreProveedor'] ?? '')."</td>
                    <td>".($fila['empleado'] ?? '')."</td>
                    <td>".($fila['NombreMP'] ?? '')."</td>
                    <td>".($fila['fecha'] ?? '')."</td>
                    <td>".($fila['cantidadMP'] ?? '')."</td>
                    <td>".($fila['monto'] ?? '')."</td>
                    <td>".($fila['precioMP'] ?? '')."</td>
                    <td>
                    <button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#formModal' onclick='cargar({$fila['idPedido']},{$fila['idProveedor']},{$fila['idEmpleado']},\"{$fila['idMateriaPrima']}\",\"{$fila['fecha']}\",{$fila['cantidadMP']},{$fila['monto']},{$fila['precioMP']})'><i class='fas fa-edit'></i> Editar</button>
                    </td>
                </tr>";
            }
            ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer small text-muted">Actualizado el <?php echo date('d/m/Y \a  \l\a\s H:i'); ?></div>
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

    <script src="../controllers/vendor/jquery/jquery.min.js"></script>
    <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
    <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>

    <!-- Demo scripts for this page-->
    <script src="js/translations.js"></script>
    <script src="js/demo/datatables-demo.js"></script>

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
