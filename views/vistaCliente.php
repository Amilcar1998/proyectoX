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
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Page level plugin CSS-->
  <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="../controllers/vendor/sb-admin.css" rel="stylesheet" />

  <style>
    body {
      font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      background-color: #f1f5f9;
    }

    .card-custom {
      border-radius: 16px;
      box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
      border: 1px solid #e2e8f0;
      background: #ffffff;
    }

    .table thead th {
      background: #0f172a;
      color: #f8fafc;
      font-weight: 600;
      border: none;
      font-size: 0.84rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      padding: 14px 16px;
    }

    .table tbody td {
      vertical-align: middle;
      padding: 13px 16px;
      color: #334155;
      font-size: 0.9rem;
    }
  </style>
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

      <div class="container-fluid py-4">
        <ol class="breadcrumb bg-white shadow-sm rounded-lg mb-4 py-2 px-3 border">
          <li class="breadcrumb-item">
            <a href="controllerDashboard.php" class="text-secondary"><i class="fas fa-home"></i> Inicio</a>
          </li>
          <li class="breadcrumb-item active text-dark font-weight-bold"><i class="fas fa-users mr-1"></i> Clientes</li>
        </ol>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="m-0 font-weight-bold text-dark"><i class="fas fa-users text-primary mr-2"></i>Directorio de Clientes</h4>
          <div>
            <button type="button" class="btn btn-primary font-weight-bold px-3 shadow-sm Nagregar" data-toggle="modal" data-target="#modalCliente" onclick="limpiarCliente()">
              <i class="fas fa-plus mr-1"></i>Agregar Cliente
            </button>
            <a href="repoClientes.php" class="btn btn-outline-secondary font-weight-bold ml-2">
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
        <div class="card card-custom mb-4">
          <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-address-book text-primary mr-2"></i>Listado de Clientes Registrados</h6>
            <span class="badge badge-light border text-dark px-3 py-2 font-weight-bold"><?= !empty($Rcliente) ? count($Rcliente) : 0 ?> Clientes</span>
          </div>
          <div class="card-body p-4">
            <div class="table-responsive">
              <table class="table table-custom table-hover datatable" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Nombre y Apellidos</th>
                    <th>Teléfono</th>
                    <th>Edad</th> 
                    <th>Género</th>
                    <th>Usuario Asociado</th> 
                    <th class="text-center" style="width: 110px;">Acciones</th> 
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($Rcliente)): ?>
                    <?php foreach ($Rcliente as $e): 
                      $nombreCompleto = trim($e->getNombreCi() . ' ' . $e->getApellidos());
                      $genero = $e->getGenero();
                      $user = $e->getUsername();
                    ?>
                      <tr>
                        <td class="font-weight-bold text-dark">
                          <i class="fas fa-user-circle text-secondary mr-2"></i><?php echo htmlspecialchars($nombreCompleto); ?>
                        </td>
                        <td>
                          <?php if ($e->getTelefono()): ?>
                            <a href="tel:<?= htmlspecialchars($e->getTelefono()) ?>" class="text-primary font-weight-bold"><i class="fas fa-phone-alt text-success mr-1"></i><?php echo htmlspecialchars($e->getTelefono()); ?></a>
                          <?php else: ?>
                            <span class="text-muted">-</span>
                          <?php endif; ?>
                        </td>
                        <td><span class="badge badge-light border px-2 py-1"><?php echo htmlspecialchars($e->getEdad() ?: 'N/A'); ?></span></td>
                        <td>
                          <?php if (strtolower($genero) === 'hombre' || strtolower($genero) === 'm'): ?>
                            <span class="text-muted"><i class="fas fa-mars mr-1 text-primary"></i>Hombre</span>
                          <?php else: ?>
                            <span class="text-muted"><i class="fas fa-venus mr-1 text-danger"></i>Mujer</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php if ($user): ?>
                            <code class="text-primary"><?php echo htmlspecialchars($user); ?></code>
                          <?php else: ?>
                            <span class="text-muted small">Sin cuenta</span>
                          <?php endif; ?>
                        </td>
                        <td class="text-center">
                          <button type="button" class="btn btn-outline-primary btn-sm font-weight-bold px-2 py-1 cargar" data-toggle="modal" data-target="#modalCliente" onclick='cargarCliente("<?php echo htmlspecialchars($e->getIdCliente(), ENT_QUOTES); ?>", "<?php echo htmlspecialchars($e->getNombreCi(), ENT_QUOTES); ?>", "<?php echo htmlspecialchars($e->getApellidos(), ENT_QUOTES); ?>", "<?php echo htmlspecialchars($e->getTelefono(), ENT_QUOTES); ?>", "<?php echo htmlspecialchars($e->getEdad(), ENT_QUOTES); ?>", "<?php echo htmlspecialchars($e->getGenero(), ENT_QUOTES); ?>", "<?php echo htmlspecialchars($e->getUsuarioC(), ENT_QUOTES); ?>")'>
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
          <div class="card-footer bg-white small text-muted border-top">
            <i class="fas fa-clock mr-1"></i>Actualizado el <?php echo date('d/m/Y \a \l\a\s H:i'); ?>
          </div>
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
