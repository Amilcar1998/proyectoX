<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Clientes</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="vendor/sb-admin.css" rel="stylesheet">

</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="index.html">Concentrados El Gordito</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <div class="input-group">
        
        </div>
      </div>
    </form>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
      <form>
      <button id="c" name="c" class="btn btn-warning">Cerrar Session</button>
    </form>
    </ul>

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="index.html">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
     
      <li class="nav-item">
        <a class="nav-link" href="controllerEmpleado.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Empleados</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="tables.html">
          <i class="fas fa-fw fa-table"></i>
          <span>Tables</span></a>
      </li>
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Agregar Cliente</button>
            <hr>

              <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <form method="POST" action="#">
                    <div class="container">
                      <hr>
                      <h2><center>Clientes</center></h2>
                      <hr>
                      <div class="row">
                        <div class="col-md-6">
                          <label>id Cliente</label>
                          <input type="text" name="idCliente" id="idCliente" readonly="true" class="form-control">
                        </div>
                        <div class="col-md-6">
                          <label>Nombre</label>
                          <input type="text" name="nombreC" id="nombreC" class="form-control">
                        </div>
                      </div>
                       <div class="row">
                        <div class="col-md-6">
                          <label>Apellidos</label>
                          <input type="text" class="form-control" id="apellidoC" name="apellidoC">
                        </div>
                        <div class="col-md-6">
                          <label>Telefono</label>
                          <input type="text" class="form-control" id="telefonoC" name="telefonoC">
                        </div>
                      </div>
                       <div class="row">
                        <div class="col-md-4">
                          <label>Edad</label>
                          <input type="text" name="edadC" id="edadC" class="form-control">
                        </div>
                        <div class="col-md-4">
                          <label>Genero</label>
                         <select id="generoC" name="generoC" class="form-control">
                          <option>seleccione........</option>
                          <option value="Hombre">Hombre</option>
                          <option value="Mujer">Mujer</option>
                           


                         </select >
                          
                        </div>
                        
                        <div class="col-md-4">
                          <label>Usuario</label>
                          <select name="usuarioC" id="usuarioC" class="form-control">
                              <option>seleccione...</option>
                             <?php 
                             foreach ($user as $us) {
                              echo "<option value=".$us["idUsuario"].">".$us["username"]."</option>";
                             } 
                             ?>
                          </select>
                          
                        </div>
                      </div>
                      <hr><center>
                      <button class="btn btn-primary" id="insertar" name="insertar">Agregar</button>
                      <button class="btn btn-primary" id="modificar" name="modificar">modificar</button>
                      <button class="btn btn-primary" id="eliminar" name="eliminar">eliminar</button>
                    </center>
                      <hr>
                    </div> 
                  </form> 
                  </div>
                </div>
              </div>

        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Data Table Example</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                  <th>ID CLIENTE</th>
                  <th>NOMBRE</th>
                  <th>APELLIDOS</th>
                  <th>TELEFONO</th>
                  <th>EDAD</th> 
                  <th>GENERO</th>
                  <th>ID USUARIO</th> 
                  <th>ACCIONES</th> 
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                  <th>ID CLIENTE</th>
                  <th>NOMBRE</th>
                  <th>APELLIDOS</th>
                  <th>TELEFONO</th>
                  <th>EDAD</th> 
                  <th>GENERO</th>
                  <th>ID USUARIO</th> 
                  <th>ACCIONES</th> 
                  </tr>
                </tfoot>
                <tbody>
                  <?php 
               foreach ($Rcliente as $e) {
                $id=$e->getIdCliente();
                $nombre = str_replace(' ', '&nbsp;', $e->getNombreCi());
                $apellidos=str_replace(' ', '&nbsp;', $e->getApellidos());
                $telefono =$e->getTelefono();
                $edad = $e->getEdad();
                $generoC=str_replace(' ', '&nbsp;', $e->getGenero());
                $idUsuario = $e->getUsuarioC();

             
                  echo "<tr>
                  <td>$id</td>
                  <td>$nombre</td>
                  <td>$apellidos</td>
                  <td>$telefono</td>
                  <td>$edad</td>
                  <td>$generoC</td>
                  <td>$idUsuario</td>
                  <td>
                  <button class='btn btn-warning' data-toggle='modal' data-target='.bd-example-modal-lg' onClick=$('#idCliente').val('$id');$('#nombreC').val('$nombre');$('#apellidoC').val('$apellidos');$('#telefonoC').val('$telefono');$('#edadC').val('$edad');$('#generoC').val('$generoC');$('#usuarioC').val('$idUsuario'); >ver</button>
                  </td>
                  </tr>";
               }
                  
                
      ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div>

        <p class="small text-center text-muted my-5">
          <em>More table examples coming soon...</em>
        </p>

      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <footer class="sticky-footer">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright © Your Website 2019</span>
          </div>
        </div>
      </footer>

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

  <!-- Demo scripts for this page-->
  <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
