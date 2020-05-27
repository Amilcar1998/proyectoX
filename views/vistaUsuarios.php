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

  <title>Usuarios</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="vendor/sb-admin.css" rel="stylesheet">

</head>

<body id="page-top">

  <?php echo "$nav"; ?>

  <div id="wrapper">

    <?php  echo "$menu"; ?>

    <div id="content-wrapper">
      <div class="container-fluid">
        <form action="#" method="POST" id="miForm">
          <a href="controllerUsuarios.php"><button class="btn btn-warning">Usuarios</button></a>
          <button class="btn btn-success" id="empleado" name="empleado">Empleados</button>
          <button class="btn btn-success" id="cliente" name="cliente">Clientes</button></form>
          <hr>

<!-- Modal -->
              <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header btn-info">
                      <h5 class="modal-title" id="exampleModalLabel">Panel Usuarios</h5>
                       
                    </div>
                    <div class="modal-body">
                    <form method="POST" action="#">
                      <div class="container">
                        <div class="row">
                          <div class="col-md-6">
                            <label>Id Usuario</label>
                            <input type="text" class="form-control" name="txtUsuario" id="txtUsuario">
                          </div>
                          <div class="col-md-6">
                            <label>Nombre Usuario</label>
                            <input type="text" class="form-control" name="txtUser" id="txtUser">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <label>Contraseña</label>
                            <input type="text" class="form-control" name="txtPass" id="txtPass">
                          </div>
                          <div class="col-md-6">
                            <label>Rol</label>
                            <select class="form-control" name="txtRol" id="txtRol" >
                              <?php 
                                 foreach ($rol as $c) {
                                  echo "<option value=".$c["id_Rol"].">".$c["nombreRol"]."</option>";
                                 } 
                                 ?>
                              


                            </select>
                            
                          </div>
                        </div>
                      </div>
                      <hr>
                      <center>
                      <button class="btn btn-success" id="modificar" name="modificar">Modificar</button>
                      <button type="button" class="btn btn-info reset" data-dismiss="modal">Close</button>
                    </center>
                    </form>
                    </div>
                    
                  </div>
                </div>
              </div>
          <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
          <?php if (isset($nombre)) {
            echo "$nombre";
          } ?>
        </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                  <?php echo "$thead"; ?>               
                  </tr>
                </thead>
                <tfoot>
                 <tr>
                  <?php echo "$thead"; ?>
                  </tr>
                </tfoot>
                <tbody>
                  <?php
                  if(isset($userEmp)) {
                   foreach ($userEmp as $u) {
                      $usuario = $u["idUsuario"];
                      $nombreE = $u['nombreEmp'];
                      $apellido = $u["apellido"];
                      $usern=$u['username'];
                      $pass=$u['pass'];
                      $rol=$u['id_Rol'];
                      echo "
                            <tr>
                            <td>$usuario</td>
                            <td>$nombreE</td>
                            <td>$usern</td>
                            <td>$pass</td>
                            <td>
                            <button class='btn btn-success' data-toggle='modal' data-target='#exampleModal' onClick=$('#txtUsuario').val('$usuario');$('#txtUser').val('$usern');$('#txtPass').val('$pass');$('#txtRol').val('$rol');>Cargar</button>
                            </td>
                            </tr>
                      " ;
                        
                      }
                  }
              if (isset($userCli)) {
                    foreach ($userCli as $u) {
                      $idC=$u["idUsuario"];
                      $nombre=$u["nombreCliente"];
                      $user=$u["username"];
                      $pass=$u["pass"];
                      $rol = $u['id_Rol'];
                      echo "
                            <tr>
                            <td>$idC</td>
                            <td>$nombre</td>
                            <td>$user</td>
                            <td>$pass</td>
                            <td>
                            <button class='btn btn-success' data-toggle='modal' data-target='#exampleModal' onClick=$('#txtUsuario').val('$idC');$('#txtUser').val('$user');$('#txtPass').val('$pass');$('#txtRol').val('$rol'); >
                            Cargar
                            </button></td>
                            </tr>" ;
                    }
                      
                  }

                  if(isset($m)){
                    foreach ($m as $us) {
                      $user = $us["idUsuario"];
                      $usern= $us["username"];
                      $contra=$us["pass"];
                      $nRol=$us["nombreRol"];
                      $rol = $us["id_Rol"];
                      echo "<tr>
                            <td>$user</td>                     
                            <td>$usern</td>
                            <td>$contra</td>
                            <td>$nRol</td>
                            <td><button class='btn btn-success' data-toggle='modal' data-target='#exampleModal' onClick=$('#txtUsuario').val('$user');$('#txtUser').val('$usern');$('#txtPass').val('$contra');$('#txtRol').val('$rol');>
                            Cargar
                            </button></td>
                            </tr>

                      ";




                    }







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
