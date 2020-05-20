<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Empleado</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">  
  <!-- Custom styles for this template-->
  <link href="vendor/sb-admin.css" rel="stylesheet">
  
</head>
<body id="page-top">
  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">


   <a class="navbar-brand mr-1" href="index.html">Concentrados El gordito</a>
    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <button class="btn btn-primary" type="button">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
      <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-bell fa-fw"></i>
          <span class="badge badge-danger">9+</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-envelope fa-fw"></i>
          <span class="badge badge-danger">7</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messagesDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="#">Settings</a>
          <a class="dropdown-item" href="#">Activity Log</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
        </div>
      </li>
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
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Pages</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
          <h6 class="dropdown-header">Login Screens:</h6>
          <a class="dropdown-item" href="login.html">Login</a>
          <a class="dropdown-item" href="register.html">Register</a>
          <a class="dropdown-item" href="forgot-password.html">Forgot Password</a>
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header">Other Pages:</h6>
          <a class="dropdown-item" href="404.html">404 Page</a>
          <a class="dropdown-item" href="blank.html">Blank Page</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Charts</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="tables.html">
          <i class="fas fa-fw fa-table"></i>
          <span>Tables</span></a>
      </li>
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">
      <button class="btn btn-primary" id="agregarC" data-toggle="modal" data-target=".bd-example-modal-lg">Agregar Empleado</button>
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="breadcrumb"><h3>REGISTRO DE EMPLEADO</h3></div>
              <div class="container-fluid">
             <form method="POST" action="#" enctype="multipart/form-data">
              <div class="container">
                <div class="col-md-6">
                    <label>Id Cliente</label>
                    <input type="text" name="txtIdEmpleado" id="txtIdEmpleado" class="form-control" readonly=true>
                  </div>
                <div class="row">
                    <div class="col-md-6">
                    <label>Nombre</label>
                    <input type="text" name="txtNombreE" id="txtNombreE" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label>Apellidos</label>
                    <input type="text" name="txtApellidos" id="txtApellidos" class="form-control">
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <label>Correo</label>
                    <input type="text" name="txtCorreo" id="txtCorreo" class="form-control" placeholder=".....@gmail.com">
                  </div>
                  <div class="col-md-6">
                    <label>Genero</label>
                     <select name="txtGenero" id="txtGenero" class="form-control">
                    <option>seleccione...</option>
                    <option value="Hombre">Hombre</option>
                    <option value="Mujer">Mujer</option>
                  </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label>ID Usuario</label>
                    <select name="idRol" id="idRol" class="form-control">
                      <option>selecione....</option>
                      <?php 
                   foreach ($data as $d) {
                    echo "<option value=".$d["id_Rol"].">".$d["nombreRol"]."</option>";
                   } 
                   ?>
                    </select>


                  </div>
                  <div class="col-md-6">
                    <label>contraseña</label>
                    <input type="password" name="txtPass" class="form-control" id="txtPass">
                  </div>
                </div>            
                </div>
              </div>
              <hr>
             <center>
              <input type="submit" class="btn btn-warning" name="insertar" id="insertar" value="Agregar Cliente">
              <input type="reset" class="btn btn-primary" name="reset" id="reset" value="Reestablecer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               
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
          Datos Empleados</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                  <th>ID Empleado</th>
                  <th>Nombre</th>
                  <th>Apellidos</th>
                  <th>Correo</th>
                  <th>Genero</th>
                  <th>ID Rol</th>
                  <th>Acciones</th> 
                                   
                  </tr>

                </thead>
                <tfoot>
                 <tr>
                  <th>ID Empleado</th>
                  <th>Nombre</th>
                  <th>Apellidos</th>
                  <th>Correo</th>
                  <th>Genero</th>
                  <th>ID Rol</th>
                  <th>Accion</th>
                  
                  </tr>

                </tfoot>
                <tbody>
              <?php 
               foreach ($datos as $e) {
                  $idEmpleado=$e->getIdEmpleado();
                  $nombre=$e->getNombre();
                  $apellido=$e->getApellido();
                  $correo=$e->getCorreo();
                  $genero=$e->getGenero();
                  $idRol=$e->getIdRol();
                  


                  echo "<tr>
                  <td>$idEmpleado</td>
                  <td>$nombre</td>
                  <td>$apellido</td>
                  <td>$correo</td>
                  <td>$genero</td>
                  <td>$idRol</td>
          
                  <td><button class='btn btn-danger' onClick=$('#txtIdEmpleado').val('$idEmpleado'),$('#txtNombreE').val('$nombre');';>Ver</button></td>
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
