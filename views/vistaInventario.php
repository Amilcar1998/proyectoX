<?php 
include '../views/configuracion.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

    <title>📋 Inventario</title>
  

  <!-- Custom fonts for this template-->
  <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">  

  <link href="../controllers/vendor/sb-admin.css" rel="stylesheet">
  <script type="text/javascript" src="../controllers/vendor/sweetalert2.all.min.js"></script>



  <script type="text/javascript">


  </script>
  
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
      <button class="btn btn-primary Nagregar" id="agregarC" data-toggle="modal" data-target="#modalInventario" onclick="limpiarInventario()"><i class="fas fa-plus mr-1"></i>Agregar al Inventario</button>
      &nbsp;&nbsp;<a href="../controllers/reporteInventarioGeneral.php" target="_blank"><button class="btn btn-success"><i class="fas fa-print mr-1"></i>Imprimir Reporte</button></a>
        <!-- Modal Inventario -->
        <div class="modal fade" id="modalInventario" tabindex="-1" role="dialog" aria-labelledby="modalInventarioLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalInventarioLabel"><i class="fas fa-boxes mr-2"></i>Registro de Inventario</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="POST" id="miForm" action="#" name="formulario" enctype="multipart/form-data">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-6 form-group">
                      <label for="txtId">ID Inventario</label>
                      <input type="text" name="txtId" id="txtId" placeholder="Automático" class="form-control" readonly>
                    </div>
                    <div class="col-md-6 form-group">
                      <label for="txtIdMateriaPrima">Materia Prima</label> 
                      <select name="txtIdMateriaPrima" id="txtIdMateriaPrima" class="form-control" required>
                        <option value="">Seleccione materia prima...</option>
                        <?php foreach ($materiasPrimas as $mp) {
                          echo "<option value='".($mp["idMateriaPrima"] ?? '')."'>".($mp["NombreMP"] ?? '')."</option>";
                        } ?>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 form-group">  
                      <label for="txtExistencias">Existencias</label>
                      <input type="number" step="any" name="txtExistencias" id="txtExistencias" placeholder="Cantidad en existencias" class="form-control" required>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary reset" data-dismiss="modal"><i class="fas fa-times mr-1"></i>Cerrar</button>
                  <input type="submit" value="Guardar" name="btnGuardar" class="btn btn-primary agregar">
                  <input type="submit" value="Modificar" name="btnModificar" class="btn btn-warning modificar">
                  <input type="submit" value="Eliminar" name="btnEliminar" class="btn btn-danger eliminar">
                </div>
              </form>
            </div>
          </div>
        </div>
        
      <br><br>

        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
          Datos Inventario</div>
          <div class="card-body">
            <div class="table-responsive">
               <table class="table table-bordered  table-triped datatable" width="100%" cellspacing="0">
                 <thead>
                   <tr>
                   <th>Materia Prima</th>
                   <th>Existencias</th>
                   <th>Accion</th>               
                   </tr>

                 </thead>
                 <tfoot>
                 <tr>
                   <th>Materia Prima</th>
                   <th>Existencias</th>
                   <th>Accion</th>  
                   </tr>

                 </tfoot>
                 <tbody>
                <?php if (!empty($tabla)): ?>
                  <?php foreach ($tabla as $fila): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($fila['NombreMP'] ?? ''); ?></td>
                      <td><?php echo htmlspecialchars($fila['Existencias'] ?? ''); ?></td>
                      <td>
                        <button type="button" class="btn btn-warning btn-sm cargar" data-toggle="modal" data-target="#modalInventario" onclick='cargarInventario("<?php echo htmlspecialchars($fila['idInventario'] ?? '', ENT_QUOTES); ?>", "<?php echo htmlspecialchars($fila['idMateriaPrima'] ?? '', ENT_QUOTES); ?>", "<?php echo htmlspecialchars($fila['Existencias'] ?? '', ENT_QUOTES); ?>")'>
                          <i class="fas fa-edit mr-1"></i>Editar
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
                 </tbody>
               </table>
            </div>
          </div>
          <div class="card-footer small text-muted">Actualizado el <?php echo date('d/m/Y \a  \l\a\s H:i'); ?></div>
        </div>

        

      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      

    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-sign-out-alt mr-2"></i>¿Desea cerrar sesión?</h5>
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
   <script src="js/sb-admin.min.js"></script>
   <script type="text/javascript" src="Recursos/validaciones.js"></script>

    <!-- Translations -->
    <script src="js/translations.js"></script>

    <!-- Demo scripts for this page-->
    <script src="js/demo/datatables-demo.js"></script>

    <script>
      function cargarInventario(id, idMP, existencias) {
        $('#txtId').val(id);
        $('#txtIdMateriaPrima').val(idMP);
        $('#txtExistencias').val(existencias);
      }

      function limpiarInventario() {
        $('#txtId').val('');
        $('#txtIdMateriaPrima').val('');
        $('#txtExistencias').val('');
      }
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
<?php 
if(isset($msj,$icon)){
  echo "<script>Swal.fire('$msj','','$icon');</script>";
}

 ?>
