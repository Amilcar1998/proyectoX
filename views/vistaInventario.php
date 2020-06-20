<?php 

include 'configuracion.php';

 ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Inventario</title>
  

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">  
  
  <link href="vendor/sb-admin.css" rel="stylesheet">
  <script type="text/javascript" src="vendor/sweetalert2.all.min.js"></script>



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
      <button class="btn btn-primary Nagregar" id="agregarC" data-toggle="modal" data-target=".modal">Agregar al Inventario</button>
      &nbsp;&nbsp;<a href="repoInventario.php"><button class="btn btn-success">Imprimir Reporte</button></a>
        <div class="modal fade modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="btn-info"><hr><center><h4>Registros Inventario</h4><hr></center></div>
              <div class="container-fluid">
              <form method="POST" id="miForm" action="#" name="formulario" enctype="multipart/form-data">
              <hr>
            <div class="row">
                <div class="col-md-6">
                  <label>ID Inventario</label>
                  <input type="text" name="txtId" id="txtId" value="" size="30" placeholder="Id Inventario" class="form-control" readonly>
                </div>
                <div class="col-md-6">
                  <label>Materia Prima</label> 
                  <select name="txtIdMateriaPrima" id="txtIdMateriaPrima" class="form-control">
                    <option 1 value="1">Maiz Amarillo</option>
                    <option 2 value="2">Maiz Blanco</option>
                    <option 3 value="1">Maicillo</option>
                    <option 4 value="1">Arroz</option>
                  <!--<input type="text" name="txtIdMateriaPrima" id="txtIdMateriaPrima" value="" size="30" placeholder="ID Materia Prima" class="form-control">-->
                  </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">  
                    <label>Existencias</label>
                    <input type="text" name="txtExistencias" id="txtExistencias" value="" size="30" placeholder="Existencias" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>ID Detalle Compra</label>
                    <input type="text" name="txtDetalleCompra" id="txtDetalleCompra" value="" size="30" placeholder="Detalle Compra" class="form-control">
                </div>
            </div>
            <hr>
            <center>
            <input type="submit" value="guardar" name="btnGuardar" class="btn btn-primary">
            <input type="submit" value="modificar" name="btnModificar" class="btn btn-warning">
            <input type="submit" value="eliminar" name="btnEliminar" class="btn btn-danger">
          </center>
            
        </form>



              <hr>
           </div>
              
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
              <table class="table table-bordered  table-triped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                  <th>ID Inventario</th>
                  <th>ID Materia Prima</th>
                  <th>Existencias</th>
                  <th>ID Detalle Compra</th>
                  <th>Accion</th>               
                  </tr>

                </thead>
                <tfoot>
                 <tr>
                  <th>ID Inventario</th>
                  <th>ID Materia Prima</th>
                  <th>Existencias</th>
                  <th>ID Detalle Compra</th>
                  <th>Accion</th>  
                  </tr>

                </tfoot>
                <tbody>
              <?php 
              foreach ($datos as $fila) {
                $idInventario=$fila["idInventario"];
                $idMateriaPrima=$fila["idMateriaPrima"];
                $Existencias=$fila["Existencias"];
                $idDetalleCompra=$fila["idDetalleCompra"];

               echo "
               <tr>
                  <td>$idInventario</td>
                  <td>$idMateriaPrima</td>
                  <td>$Existencias</td>
                  <td>$idDetalleCompra</td>
                  <td>
                  <button class='btn btn-primary' data-toggle='modal' data-target='.modal' onClick=$('#txtId').val('$idInventario');$('#txtIdMateriaPrima').val('$idMateriaPrima');$('#txtExistencias').val('$Existencias');$('#txtDetalleCompra').val('$idDetalleCompra');>Cargar</button>
                  </td>
                  
                  </tr>

               ";

              }
                    
               ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
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
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>


  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.min.js"></script>
  <script type="text/javascript" src="Recursos/validaciones.js"></script>
  <script type="text/javascript" src="../controllers/Recursos/validaciones.js"></script>

  <!-- Demo scripts for this page-->
  <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
<?php 
if(isset($msj,$icon)){
  echo "<script>Swal.fire('$msj','','$icon');</script>";
}

 ?>