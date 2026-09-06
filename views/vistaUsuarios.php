<?php 
include 'configuracion.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Gestión de Cuentas de Usuarios - Concentrados El Gordito">
  <meta name="author" content="Concentrados El Gordito">

  <title>⚙️ Gestión de Usuarios - Concentrados El Gordito</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="vendor/sb-admin.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body id="page-top">

  <?php echo "$nav"; ?>

  <div id="wrapper">

    <?php echo "$menu"; ?>

    <div id="content-wrapper">
      <div class="container-fluid">
        
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="controllerDashboard.php">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">Gestión de Usuarios</li>
        </ol>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h4 class="m-0 font-weight-bold text-dark"><i class="fas fa-users-cog text-primary mr-2"></i>Gestión de Cuentas de Acceso</h4>
            <p class="text-muted small mb-0">Administra las credenciales, roles y estado de activación de todas las cuentas del sistema.</p>
          </div>
          <div class="btn-group shadow-sm">
            <a href="controllerUsuarios.php" class="btn btn-outline-primary <?php echo (!isset($_REQUEST['empleado']) && !isset($_REQUEST['cliente'])) ? 'active' : ''; ?>">
              <i class="fas fa-users mr-1"></i>Todos
            </a>
            <a href="controllerUsuarios.php?empleado=1" class="btn btn-outline-success <?php echo isset($_REQUEST['empleado']) ? 'active' : ''; ?>">
              <i class="fas fa-user-tie mr-1"></i>Empleados
            </a>
            <a href="controllerUsuarios.php?cliente=1" class="btn btn-outline-info <?php echo isset($_REQUEST['cliente']) ? 'active' : ''; ?>">
              <i class="fas fa-user-tag mr-1"></i>Clientes
            </a>
          </div>
        </div>

        <!-- Modal Editar Usuario -->
        <div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content border-0 shadow">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalUsuarioLabel"><i class="fas fa-user-edit mr-2"></i>Editar Cuenta de Usuario</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="POST" action="controllerUsuarios.php">
                <div class="modal-body bg-light">
                  <div class="row">
                    <div class="col-md-6 form-group">
                      <label for="txtUsuario" class="font-weight-bold small">ID Usuario</label>
                      <input type="text" class="form-control bg-white font-weight-bold" name="txtUsuario" id="txtUsuario" readonly>
                    </div>
                    <div class="col-md-6 form-group">
                      <label for="txtUser" class="font-weight-bold small">Nombre de Usuario / Correo</label>
                      <input type="text" class="form-control" name="txtUser" id="txtUser" required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 form-group">
                      <label for="txtPass" class="font-weight-bold small">Nueva Contraseña</label>
                      <input type="password" class="form-control" name="txtPass" id="txtPass" placeholder="Dejar en blanco si no cambia">
                      <small class="form-text text-muted">Escriba una nueva clave si desea restablecerla.</small>
                    </div>
                    <div class="col-md-6 form-group">
                      <label for="txtRol" class="font-weight-bold small">Rol en Sistema</label>
                      <select class="form-control" name="txtRol" id="txtRol" required>
                        <?php 
                          foreach ($rol as $c) {
                            echo "<option value='".$c["id_Rol"]."'>".$c["nombreRol"]."</option>";
                          } 
                        ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="modal-footer bg-white">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i>Cerrar</button>
                  <button type="submit" class="btn btn-primary" id="modificar" name="modificar"><i class="fas fa-save mr-1"></i>Guardar Cambios</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Formulario oculto para alternar estado -->
        <form id="formCambiarEstado" method="POST" action="controllerUsuarios.php" style="display: none;">
          <input type="hidden" name="cambiar_estado" value="1">
          <input type="hidden" name="txtIdUsuario" id="estadoIdUsuario" value="">
          <input type="hidden" name="nuevoEstado" id="estadoNuevo" value="">
        </form>

        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-table mr-2"></i><?php echo htmlspecialchars($nombre ?? 'Listado de Usuarios'); ?></h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-hover datatable" width="100%" cellspacing="0">
                <thead class="thead-light">
                  <tr>
                    <?php echo "$thead"; ?>               
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (isset($userEmp)):
                    foreach ($userEmp as $u):
                      $idU = (int)$u["idUsuario"];
                      $nombreE = $u['nombreEmp'];
                      $apellido = $u["apellido"];
                      $usern = $u['username'];
                      $rolId = (int)$u['id_Rol'];
                      $nombreRol = $u['nombreRol'] ?? 'Empleado';
                      $activo = (int)($u['activo'] ?? 1);
                      $badgeRol = ($rolId === 1 || $rolId === 4) ? 'badge-primary' : 'badge-secondary';
                  ?>
                    <tr class="<?php echo ($activo === 0 ? 'table-secondary text-muted' : ''); ?>">
                      <td class="align-middle font-weight-bold">#<?php echo $idU; ?></td>
                      <td class="align-middle"><?php echo htmlspecialchars($nombreE . ' ' . $apellido); ?></td>
                      <td class="align-middle"><code><?php echo htmlspecialchars($usern); ?></code></td>
                      <td class="align-middle"><span class="badge <?php echo $badgeRol; ?> font-weight-normal"><?php echo htmlspecialchars($nombreRol); ?></span></td>
                      <td class="text-center align-middle">
                        <?php if ($activo === 1): ?>
                          <span class="badge badge-success">Activo</span>
                        <?php else: ?>
                          <span class="badge badge-danger">Inactivo</span>
                        <?php endif; ?>
                      </td>
                      <td class="text-center align-middle">
                        <div class="btn-group btn-group-sm">
                          <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modalUsuario" onclick='cargarUsuario("<?php echo $idU; ?>", "<?php echo htmlspecialchars($usern, ENT_QUOTES); ?>", "", "<?php echo $rolId; ?>")'>
                            <i class="fas fa-edit mr-1"></i>Editar
                          </button>
                          <?php if ($activo === 1): ?>
                            <button type="button" class="btn btn-outline-danger" onclick="confirmarEstadoUsuario(<?php echo $idU; ?>, 0, '<?php echo htmlspecialchars($usern, ENT_QUOTES); ?>')">
                              <i class="fas fa-ban mr-1"></i>Desactivar
                            </button>
                          <?php else: ?>
                            <button type="button" class="btn btn-outline-success" onclick="confirmarEstadoUsuario(<?php echo $idU; ?>, 1, '<?php echo htmlspecialchars($usern, ENT_QUOTES); ?>')">
                              <i class="fas fa-check mr-1"></i>Activar
                            </button>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
                  <?php 
                    endforeach;
                  elseif (isset($userCli)):
                    foreach ($userCli as $u):
                      $idU = (int)$u["idUsuario"];
                      $nombreC = $u["nombreCliente"];
                      $usern = $u["username"];
                      $rolId = (int)$u['id_Rol'];
                      $nombreRol = $u['nombreRol'] ?? 'Cliente';
                      $activo = (int)($u['activo'] ?? 1);
                      $badgeRol = 'badge-secondary';
                  ?>
                    <tr class="<?php echo ($activo === 0 ? 'table-secondary text-muted' : ''); ?>">
                      <td class="align-middle font-weight-bold">#<?php echo $idU; ?></td>
                      <td class="align-middle"><?php echo htmlspecialchars($nombreC); ?></td>
                      <td class="align-middle"><code><?php echo htmlspecialchars($usern); ?></code></td>
                      <td class="align-middle"><span class="badge <?php echo $badgeRol; ?> font-weight-normal"><?php echo htmlspecialchars($nombreRol); ?></span></td>
                      <td class="text-center align-middle">
                        <?php if ($activo === 1): ?>
                          <span class="badge badge-success">Activo</span>
                        <?php else: ?>
                          <span class="badge badge-danger">Inactivo</span>
                        <?php endif; ?>
                      </td>
                      <td class="text-center align-middle">
                        <div class="btn-group btn-group-sm">
                          <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modalUsuario" onclick='cargarUsuario("<?php echo $idU; ?>", "<?php echo htmlspecialchars($usern, ENT_QUOTES); ?>", "", "<?php echo $rolId; ?>")'>
                            <i class="fas fa-edit mr-1"></i>Editar
                          </button>
                          <?php if ($activo === 1): ?>
                            <button type="button" class="btn btn-outline-danger" onclick="confirmarEstadoUsuario(<?php echo $idU; ?>, 0, '<?php echo htmlspecialchars($usern, ENT_QUOTES); ?>')">
                              <i class="fas fa-ban mr-1"></i>Desactivar
                            </button>
                          <?php else: ?>
                            <button type="button" class="btn btn-outline-success" onclick="confirmarEstadoUsuario(<?php echo $idU; ?>, 1, '<?php echo htmlspecialchars($usern, ENT_QUOTES); ?>')">
                              <i class="fas fa-check mr-1"></i>Activar
                            </button>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
                  <?php 
                    endforeach;
                  elseif (isset($m)):
                    foreach ($m as $us):
                      $idU = (int)$us["idUsuario"];
                      $usern = $us["username"];
                      $nombreRol = $us["nombreRol"] ?? 'Sin Rol';
                      $rolId = (int)$us["id_Rol"];
                      $activo = (int)($us['activo'] ?? 1);
                      $badgeRol = ($rolId === 1 || $rolId === 4) ? 'badge-primary' : 'badge-secondary';
                  ?>
                    <tr class="<?php echo ($activo === 0 ? 'table-secondary text-muted' : ''); ?>">
                      <td class="align-middle font-weight-bold">#<?php echo $idU; ?></td>
                      <td class="align-middle"><code><?php echo htmlspecialchars($usern); ?></code></td>
                      <td class="align-middle"><span class="badge <?php echo $badgeRol; ?> font-weight-normal"><?php echo htmlspecialchars($nombreRol); ?></span></td>
                      <td class="text-center align-middle">
                        <?php if ($activo === 1): ?>
                          <span class="badge badge-success">Activo</span>
                        <?php else: ?>
                          <span class="badge badge-danger">Inactivo</span>
                        <?php endif; ?>
                      </td>
                      <td class="text-center align-middle">
                        <div class="btn-group btn-group-sm">
                          <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modalUsuario" onclick='cargarUsuario("<?php echo $idU; ?>", "<?php echo htmlspecialchars($usern, ENT_QUOTES); ?>", "", "<?php echo $rolId; ?>")'>
                            <i class="fas fa-edit mr-1"></i>Editar
                          </button>
                          <?php if ($activo === 1): ?>
                            <button type="button" class="btn btn-outline-danger" onclick="confirmarEstadoUsuario(<?php echo $idU; ?>, 0, '<?php echo htmlspecialchars($usern, ENT_QUOTES); ?>')">
                              <i class="fas fa-ban mr-1"></i>Desactivar
                            </button>
                          <?php else: ?>
                            <button type="button" class="btn btn-outline-success" onclick="confirmarEstadoUsuario(<?php echo $idU; ?>, 1, '<?php echo htmlspecialchars($usern, ENT_QUOTES); ?>')">
                              <i class="fas fa-check mr-1"></i>Activar
                            </button>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
                  <?php 
                    endforeach;
                  endif;
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">
            <i class="fas fa-clock mr-1"></i>Actualizado el <?php echo date('d/m/Y \a  \l\a\s H:i'); ?>
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
  <script src="js/translations.js"></script>
  <script src="js/demo/datatables-demo.js"></script>

  <script>
    function cargarUsuario(id, user, pass, rol) {
      $('#txtUsuario').val(id);
      $('#txtUser').val(user);
      $('#txtPass').val('');
      $('#txtRol').val(rol);
    }

    function confirmarEstadoUsuario(idUsuario, nuevoEstado, username) {
      const accion = nuevoEstado === 1 ? 'activar' : 'desactivar';
      const colorBoton = nuevoEstado === 1 ? '#28a745' : '#dc3545';
      const textoAccion = nuevoEstado === 1 ? 'La cuenta podrá iniciar sesión y operar en el sistema.' : 'El usuario no podrá iniciar sesión en el sistema mientras esté inactivo.';

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: `¿Desea ${accion} al usuario?`,
          text: `Usuario: ${username}. ${textoAccion}`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: colorBoton,
          cancelButtonColor: '#6c757d',
          confirmButtonText: `Sí, ${accion}`,
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            $('#estadoIdUsuario').val(idUsuario);
            $('#estadoNuevo').val(nuevoEstado);
            $('#formCambiarEstado').submit();
          }
        });
      } else {
        if (confirm(`¿Desea ${accion} al usuario ${username}? ${textoAccion}`)) {
          $('#estadoIdUsuario').val(idUsuario);
          $('#estadoNuevo').val(nuevoEstado);
          $('#formCambiarEstado').submit();
        }
      }
    }
  </script>

  <!-- Footer -->
  <footer class="sticky-footer bg-white shadow-sm border-top">
    <div class="container my-auto">
      <div class="copyright text-center my-auto">
        <span>Concentrados El Gordito &copy; <?php echo date('Y'); ?></span>
      </div>
    </div>
  </footer>

</body>

</html>
<?php 
if (isset($msj, $icon)) {
  echo "<script>Swal.fire({ title: '$msj', icon: '$icon', confirmButtonText: 'Aceptar' });</script>";
}
?>
