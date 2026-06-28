<?php include 'configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
  

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

    <title>👤 Clientes</title>

<!-- Custom fonts for this template-->
   <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

   <!-- Page level plugin CSS-->
   <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
   <!-- Custom styles for this template-->
   <link href="../controllers/vendor/sb-admin.css" rel="stylesheet" />
   
   <!-- Bootstrap core JavaScript-->
   <script src="../controllers/vendor/jquery/jquery.min.js"></script>
   <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
   <script type="text/javascript" src="../controllers/vendor/sweetalert2.all.min.js"></script>
   <!-- Core plugin JavaScript-->
   <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>

   <!-- Page level plugin JavaScript-->
   <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
   <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>

   <!-- Custom scripts for all pages-->
   <script src="../controllers/js/sb-admin.min.js"></script>
    <script type="text/javascript" src="../controllers/Recursos/validaciones.js"></script>

     <!-- Demo scripts for this page-->
     <script src="js/translations.js"></script>
     <script src="js/demo/datatables-demo.js"></script>


  </head>

<body id="page-top">
  <?php echo "$nav"; ?>

  <div id="wrapper">

    <!-- Sidebar -->
    <?php 
    include 'configuracion.php';
    echo "$menu";

     ?>

    <div id="content-wrapper">

      <div class="container-fluid">
            <button type="button" class="btn btn-primary Nagregar" data-toggle="modal" data-target=".bd-example-modal-lg">Agregar Cliente</button>&nbsp;&nbsp;<a href="repoClientes.php"><button class="btn btn-success">Imprimir</button></a>
            <hr>

              <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header bg-info">
                      <h2><center>Registro de Clientes</center></h2>
                    </div>
                    <hr>

                    <form method="POST" id="miForm" action="#">
                    <div class="container">

                      
                                            
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
                           <select name="usuarioC" id="usuarioC" class="form-control idU">
                             <option value="">seleccione....</option>
                             <?php 
                             $usuariosObj = $cliente->getUser();
                             foreach ($usuariosObj as $u) {
                               echo "<option value='".$u["idUsuario"]."'>".$u["username"]."</option>";
                             } 
                             ?>
                           </select>
                         </div>
                      </div>
                      <hr><center>
                      <button class="btn btn-primary agregar" id="insertar" name="insertar">Agregar</button>
                      <button class="btn btn-warning modificar" id="modificar" name="modificar">modificar</button>
                      <button class="btn btn-danger eliminar" id="eliminar" name="eliminar">eliminar</button>
                      <input type="submit"  class="btn btn-success reset" data-dismiss="modal"  value="Cerrar">
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
            Lista de Clientes</div>
          <div class="card-body">
            <div class="table-responsive">
               <table class="table table-bordered datatable" width="100%" cellspacing="0">
                 <thead>
                   <tr>
                   <th>NOMBRE</th>
                   <th>APELLIDOS</th>
                   <th>TELEFONO</th>
                   <th>EDAD</th> 
                   <th>GENERO</th>
                   <th>USUARIO</th> 
                   <th>ACCIONES</th> 
                   </tr>
                 </thead>
                 <tbody>
                   <?php 
                foreach ($Rcliente as $e) {
                 $id=$e->getIdCliente();
                 $nombre = str_replace(' ', '&nbsp;', $e->getNombreCi());
                 $apellidos=str_replace(' ', '&nbsp;', $e->getApellidos());
                 $telefono =$e->getTelefono();
                 $edad = $e->getEdad();
                 $generoC=str_replace(' ', '&nbsp;', $e->getGenero());
                 $username = $e->getUsername();

              
                   echo "<tr>
                   <td>$nombre</td>
                   <td>$apellidos</td>
                   <td>$telefono</td>
                   <td>$edad</td>
                   <td>$generoC</td>
                   <td>$username</td>
                   <td>
                   <button class='btn btn-warning cargar' data-toggle='modal' data-target='.bd-example-modal-lg' onClick=\"$('#idCliente').val('$id');$('#nombreC').val('$nombre');$('#apellidoC').val('$apellidos');$('#telefonoC').val('$telefono');$('#edadC').val('$edad');$('#generoC').val('$generoC');$('#usuarioC').val('".$e->getUsuarioC()."');\">Editar</button>
                   </td>
                   </tr>";
                }
                   
                 
      ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content-wrapper -->
  </div>
  <!-- /#wrapper -->

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
