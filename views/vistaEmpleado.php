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

    <title>👷 Empleado</title>
  

  <!-- Custom fonts for this template-->
  <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">  
  
  <link href="../controllers/vendor/sb-admin.css" rel="stylesheet">
  <script type="text/javascript" src="../controllers/vendor/sweetalert2.all.min.js"></script">>



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
            <?php foreach ($session as $key) {
          $nombre = $key['nombreEmp']."&nbsp;&nbsp;".$key['apellido'];
          echo "$nombre";
        } ?></button>
            
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
      <button class="btn btn-primary Nagregar" id="agregarC" data-toggle="modal" data-target=".modal">Agregar Empleado</button>
      <a href="repoEmpleado.php"><input type="submit" class="btn btn-success" name="" value="Imprimir"></a>
        <div class="modal fade modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="breadcrumb bg-info"><h3>Reguistro de Empleado</h3></div>
              <div class="container-fluid">
             <form method="POST" id="miForm" action="#" enctype="multipart/form-data">
              <div class="container">
               
                <div class="row"> 
                  <div class="col-md-6">
                    <label>Id Cliente</label>
                    <input type="text" name="txtIdEmpleado" id="txtIdEmpleado" class="form-control txtIdEmpleado" readonly=true>
                  </div>
                    <div class="col-md-6">
                    <label>Nombre</label>
                    <input type="text" name="txtNombres" id="txtNombres" class="form-control">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label>Apellidos</label>
                    <input type="text" name="txtApellidos" id="txtApellidos" class="form-control">
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
            <hr>

                 <div class="row">
                 <div class="col-md-6">
                   <label>Cargo</label>
                   
                     <select name="txtCargo" id="txtCargo" class="form-control">
                       <option value="">seleccione....</option>
                      <?php 
                    foreach ($puesto as $c) {
                     echo "<option value=".$c["idPuesto"].">".$c["nombrePuesto"]."</option>";
                    } 
                    ?>
                    </select>
                   </div>
                   <div class="col-md-6">
                   <label>Usuario</label>
                   <select name="txtUser" id="txtUser" class="form-control">
                     <option value="">seleccione....</option>
                     <?php foreach ($usuarios as $u) {
                       echo "<option value=".$u["idUsuario"].">".$u["username"]."</option>";
                     } ?>
                   </select>
                   </div>

                 </div>

               </div>



               </div>
               <hr>
              <center>
               <input type="submit" class="btn btn-warning agregar" name="insertar" id="insertar" value="insertar">
               <input type="submit" class="btn btn-warning modificar" name="modificar" id="modificar" value="Modificar">
               <input type="submit" class="btn btn-primary eliminar" id="eliminar " name="eliminar" value="Eliminar">
               <input type="submit"  class="btn btn-success reset" data-dismiss="modal"  value="Cerrar">
               
                
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
                   <th>Nombre</th>
                   <th>Apellidos</th>
                   <th>Género</th>
                   <th>Cargo</th> 
                   <th>Usuario</th>
                   <th>Acción</th>               
                   </tr>

                 </thead>
                 <tbody>
               <?php 
                foreach ($datos as $e) {
                   $idEmpleado=$e->getIdEmpleado();
                   $nombres=str_replace(' ', '&nbsp;', $e->getNombre());
                   $apellido=str_replace(' ', '&nbsp;', $e->getApellido()); 
                   $genero=$e->getGenero();
                   $cargo=$e->getCargo();
                   $user=$e->getUsername();
                 

              
                   echo "<tr>
                   <td>$nombres</td>
                   <td>$apellido</td>
                   <td>$genero</td>
                   <td>$cargo</td>
                   <td>$user</td>
                   <td>
                   <button class='btn btn-warning cargar' data-toggle='modal' data-target='.modal' onClick=$('#txtIdEmpleado').val('$idEmpleado');$('#txtNombres').val('$nombres');$('#txtApellidos').val('$apellido');$('#txtGenero').val('$genero');$('#txtCargo').val('$cargo');$('#txtUser').val('$user');>ver</button></td>
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
    <script src="../controllers/vendor/jquery/jquery.min.js"></script>
    <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
    <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../controllers/js/sb-admin.min.js"></script>
    <script type="text/javascript" src="../controllers/Recursos/validaciones.js"></script>

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
