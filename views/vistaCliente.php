<?php include 'configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Gestión de Clientes - Concentrados El Gordito">
  <meta name="author" content="">

  <title>👤 Clientes - Concentrados El Gordito</title>

  <!-- Custom fonts for this template-->
  <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="../controllers/vendor/sb-admin.css" rel="stylesheet" />
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
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="controllerDashboard.php">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">Clientes</li>
        </ol>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <h2 class="mb-0"><i class="fas fa-users text-primary mr-2"></i>Gestión de Clientes</h2>
          <div>
            <button type="button" class="btn btn-primary Nagregar" data-toggle="modal" data-target="#modalCliente" onclick="limpiarCliente()">
              <i class="fas fa-plus mr-1"></i>Agregar Cliente
            </button>
            <a href="repoClientes.php" class="btn btn-success ml-2">
              <i class="fas fa-print mr-1"></i>Imprimir
            </a>
          </div>
        </div>

        <!-- Modal Cliente -->
        <div class="modal fade" id="modalCliente" tabindex="-1" role="dialog" aria-labelledby="modalClienteLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalClienteLabel"><i class="fas fa-user-plus mr-2"></i>Registro de Clientes</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <form method="POST" id="miForm" action="#">
                <div class="modal-body">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="idCliente">ID Cliente</label>
                      <input type="text" name="idCliente" id="idCliente" readonly class="form-control" placeholder="Automático">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="nombreC">Nombre</label>
                      <input type="text" name="nombreC" id="nombreC" class="form-control" required placeholder="Nombre del cliente">
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="apellidoC">Apellidos</label>
                      <input type="text" class="form-control" id="apellidoC" name="apellidoC" required placeholder="Apellidos del cliente">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="telefonoC">Teléfono</label>
                      <input type="text" class="form-control" id="telefonoC" name="telefonoC" placeholder="Teléfono">
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label for="edadC">Edad</label>
                      <input type="number" name="edadC" id="edadC" class="form-control" placeholder="Edad">
                    </div>
                    <div class="form-group col-md-4">
                      <label for="generoC">Género</label>
                      <select id="generoC" name="generoC" class="form-control">
                        <option value="">Seleccione género...</option>
                        <option value="Hombre">Hombre</option>
                        <option value="Mujer">Mujer</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4">
                      <label for="usuarioC">Usuario</label>
                      <select name="usuarioC" id="usuarioC" class="form-control">
                        <option value="">Seleccione usuario...</option>
                        <?php foreach ($user as $u): ?>
                          <option value="<?php echo htmlspecialchars($u['idUsuario']); ?>">
                            <?php echo htmlspecialchars($u['username']); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary reset" data-dismiss="modal">Cancelar</button>
                  <input type="submit" class="btn btn-primary agregar" name="insertar" id="insertar" value="Guardar">
                  <input type="submit" class="btn btn-warning modificar" name="modificar" id="modificar" value="Modificar">
                  <input type="submit" class="btn btn-danger eliminar" id="eliminar" name="eliminar" value="Eliminar" onclick="return confirm('¿Está seguro de eliminar este cliente?');">
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Tabla Clientes -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table mr-1"></i> Listado de Clientes Registrados
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered datatable" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Teléfono</th>
                    <th>Edad</th> 
                    <th>Género</th>
                    <th>Usuario</th> 
                    <th>Acciones</th> 
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($Rcliente)): ?>
                    <?php foreach ($Rcliente as $e): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($e->getNombreCi()); ?></td>
                        <td><?php echo htmlspecialchars($e->getApellidos()); ?></td>
                        <td><?php echo htmlspecialchars($e->getTelefono()); ?></td>
                        <td><?php echo htmlspecialchars($e->getEdad()); ?></td>
                        <td><?php echo htmlspecialchars($e->getGenero()); ?></td>
                        <td><?php echo htmlspecialchars($e->getUsername()); ?></td>
                        <td>
                          <button type="button" class="btn btn-warning btn-sm cargar" data-toggle="modal" data-target="#modalCliente" onclick='cargarCliente("<?php echo htmlspecialchars($e->getIdCliente(), ENT_QUOTES); ?>", "<?php echo htmlspecialchars($e->getNombreCi(), ENT_QUOTES); ?>", "<?php echo htmlspecialchars($e->getApellidos(), ENT_QUOTES); ?>", "<?php echo htmlspecialchars($e->getTelefono(), ENT_QUOTES); ?>", "<?php echo htmlspecialchars($e->getEdad(), ENT_QUOTES); ?>", "<?php echo htmlspecialchars($e->getGenero(), ENT_QUOTES); ?>", "<?php echo htmlspecialchars($e->getUsuarioC(), ENT_QUOTES); ?>")'>
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
          <div class="card-footer small text-muted">Actualizado el <?php echo date('d/m/Y \a \l\a\s H:i'); ?></div>
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
  <script type="text/javascript" src="../controllers/vendor/sweetalert2.all.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
  <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../controllers/js/sb-admin.min.js"></script>
  <script type="text/javascript" src="../controllers/Recursos/validaciones.js"></script>

  <!-- Translations & Demo scripts-->
  <script src="js/translations.js"></script>
  <script src="js/demo/datatables-demo.js"></script>

  <script>
    function cargarCliente(id, nombre, apellidos, telefono, edad, genero, usuario) {
      $('#idCliente').val(id);
      $('#nombreC').val(nombre);
      $('#apellidoC').val(apellidos);
      $('#telefonoC').val(telefono);
      $('#edadC').val(edad);
      $('#generoC').val(genero);
      $('#usuarioC').val(usuario);
    }

    function limpiarCliente() {
      $('#idCliente').val('');
      $('#nombreC').val('');
      $('#apellidoC').val('');
      $('#telefonoC').val('');
      $('#edadC').val('');
      $('#generoC').val('');
      $('#usuarioC').val('');
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
