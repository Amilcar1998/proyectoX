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

  <title>Proveedores</title>
  

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
  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

   <a class="navbar-brand mr-1" href="index.html">Concentrados El gordito</a>
    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>


    <!-- Navbar -->
    
      <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <div class="input-group">
        <div class="input-group-append">
          <button class="btn btn-success"> 
            <?php  
          echo "$nombres";
       ?></button>
            
          </button>
        </div>
      </div>
    </form>
    <ul class="navbar-nav ml-auto ml-md-0">
      <div>
      <form><button class='btn btn-warning' id='c' name='c' value='c'>Cerrar session</button></form>
      </div>
    </ul>

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->

<?php 
  echo "$menu";


 ?>




    <div id="content-wrapper">

      <div class="container-fluid">
      <button class="btn btn-primary Nagregar" id="agregarC" data-toggle="modal" data-target=".modal">Agregar Proveedor</button>
      &nbsp;&nbsp;<a href="repoProveedor.php"><button class="btn btn-success">Imprimir</button></a>
        <div class="modal fade modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="btn-info"><hr><center><h4>Registros Proveedor</h4><hr></center></div>
              <div class="container-fluid">
             <form method="POST" id="miForm" action="#" name="formulario" enctype="multipart/form-data">
              <hr>
            <div class="row">
                <div class="col-md-6">
                    <label>id Proveedor</label>
                    <input type="text" name="txtId" id="txtId" value="" size="30" placeholder="Id Proveedor" class="form-control">
                </div>
                <div class="col-md-6">
                <label>Nombre</label> 
                <input type="text" name="txtNombre" id="txtNombre" value="" size="30" placeholder="Nombre de proveedor" class="form-control">
            </div>
            </div>
            <div class="row">
               
                <div class="col-md-6">  
                    <label>Contacto</label>
                    <input type="text" name="txtContacto" id="txtContacto" value="" size="30" placeholder="contacto" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Nit</label>
                    <input type="text" name="txtNit" id="txtNit" value="" size="30" placeholder="Nit" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>Correo</label>
                    <input type="text" name="txtCorreo" id="txtCorreo" value="" size="30" placeholder="correo electronico" class="form-control"></div>
                <div class="col-md-6">
                    <label>Telefono</label>
                    <input type="text" name="txtTelefono" id="txtTelefono" value="" size="30" placeholder="Telefono" class="form-control">
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
          Datos Proveedores</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered  table-triped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                  <th>Id Proveedor</th>
                  <th>Nombre </th>
                  <th>contacto</th>
                  <th>Nit</th>
                  <th>correo</th> 
                  <th>telefono</th>
                  <th>Accion</th>               
                  </tr>

                </thead>
                <tfoot>
                 <tr>
                  <th>ID Empleado</th>
                  <th>Nombre</th>
                  <th>Apellidos</th>
                  <th>Genero</th>
                  <th>ID Cargo</th>
                  <th>Usuario</th>
                  <th>Accion</th>
                  
                  </tr>

                </tfoot>
                <tbody>
              <?php 
              foreach ($tab as $fila) {
                $idProv =$fila["idProveedor"];
                $nombres=$fila["nombreProveedor"];
                $nombre=str_replace(' ', '&nbsp;', $nombres);
                $contacto=$fila["contacto"];
                $nit=$fila["NIT"];
                $correoP=$fila["correoP"];
                $telefono=$fila["telefono"];
 
               echo "
               <tr>
                  <td>$idProv</td>
                  <td>$nombres</td>
                  <td>$contacto</td>
                  <td>$nit</td>
                  <td>$correoP</td>
                  <td>$telefono</td>
                  <td>
                  <button class='btn btn-primary' data-toggle='modal' data-target='.modal' onClick=$('#txtId').val('$idProv');$('#txtNombre').val('$nombre');$('#txtContacto').val('$contacto');$('#txtNit').val('$nit');$('#txtCorreo').val('$correoP');$('#txtTelefono').val('$telefono');>Cargar</button>
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
