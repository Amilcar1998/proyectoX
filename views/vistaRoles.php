<?php 
include 'configuracion.php'; 
?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Gestión de Roles y Permisos - Concentrados El Gordito">
  <meta name="author" content="Concentrados El Gordito">

  <title>🛡️ Roles y Permisos | Concentrados El Gordito</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">  
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
          <li class="breadcrumb-item active">Roles y Permisos</li>
        </ol>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
          <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
              <i class="fas fa-user-shield text-primary mr-2"></i>Gestión de Roles y Permisos
            </h1>
            <p class="text-muted mb-0">Mantenimiento de la estructura jerárquica de roles, subroles y matriz de submódulos autorizados.</p>
          </div>
          <div>
            <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modalRol" onclick="limpiarModalRol()">
              <i class="fas fa-plus-circle mr-1"></i>Nuevo Rol / Subrol
            </button>
          </div>
        </div>

        <!-- Metric Cards -->
        <div class="row mb-4">
          <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Roles Registrados</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($listaRoles); ?></div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Subroles Jerárquicos</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                      <?php 
                        $totalSubroles = count(array_filter($listaRoles, fn($r) => !empty($r['idRolPadre'])));
                        echo $totalSubroles;
                      ?>
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-sitemap fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Roles Activos</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                      <?php 
                        $activos = count(array_filter($listaRoles, fn($r) => (int)$r['activo'] === 1));
                        echo $activos;
                      ?>
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Roles Inactivos</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                      <?php 
                        $inactivos = count(array_filter($listaRoles, fn($r) => (int)$r['activo'] === 0));
                        echo $inactivos;
                      ?>
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-ban fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Crear/Editar Rol -->
        <div class="modal fade" id="modalRol" tabindex="-1" role="dialog" aria-labelledby="modalRolLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0 shadow">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="modalRolLabel">
                  <i class="fas fa-user-shield mr-2"></i><span id="modalTitulo">Registro de Rol</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="POST" id="formRol" action="controllerRoles.php">
                <div class="modal-body p-4 bg-light">
                  <input type="hidden" name="txtIdRol" id="txtIdRol" value="">

                  <div class="row">
                    <div class="col-md-6 form-group">
                      <label for="txtNombreRol" class="font-weight-bold small">Nombre del Rol / Subrol <span class="text-danger">*</span></label>
                      <input type="text" name="txtNombreRol" id="txtNombreRol" class="form-control" required placeholder="Ej: Jefe de Envasado">
                    </div>
                    <div class="col-md-6 form-group">
                      <label for="txtIdRolPadre" class="font-weight-bold small">Rol Padre (Herencia de Accesos)</label>
                      <select name="txtIdRolPadre" id="txtIdRolPadre" class="form-control">
                        <option value="">Ninguno (Rol Base Independiente)</option>
                        <?php foreach ($rolesPadre as $rp): ?>
                          <option value="<?php echo (int)$rp['id_Rol']; ?>">
                            <?php echo htmlspecialchars($rp['nombreRol']); ?>
                            <?php echo (!empty($rp['acceso_total']) ? ' (Acceso Total)' : ''); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                      <small class="form-text text-muted">Hereda automáticamente los submódulos del rol seleccionado.</small>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-12 form-group">
                      <label for="txtDescripcion" class="font-weight-bold small">Descripción del Puesto / Rol</label>
                      <input type="text" name="txtDescripcion" id="txtDescripcion" class="form-control" placeholder="Ej: Supervisión operativa de planta y control de envasado">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                      <div class="custom-control custom-checkbox bg-white p-2 rounded border h-100">
                        <input type="checkbox" class="custom-control-input" id="chkAccesoTotal" name="chkAccesoTotal" value="1" onchange="toggleAccesoTotal()">
                        <label class="custom-control-label font-weight-bold text-dark" for="chkAccesoTotal">
                          <i class="fas fa-crown text-warning mr-1"></i>Acceso Total al Sistema
                        </label>
                        <small class="d-block text-muted">Acceso irrestricto a todos los submódulos.</small>
                      </div>
                    </div>
                    <div class="col-md-6 mb-2">
                      <div class="custom-control custom-checkbox bg-white p-2 rounded border h-100" id="grupoEstadoRol">
                        <input type="checkbox" class="custom-control-input" id="chkActivo" name="chkActivo" value="1" checked>
                        <label class="custom-control-label font-weight-bold text-dark" for="chkActivo">
                          <i class="fas fa-check-circle text-success mr-1"></i>Rol Activo en el Sistema
                        </label>
                        <small class="d-block text-muted">Desmarcar para deshabilitar este rol.</small>
                      </div>
                    </div>
                  </div>

                  <!-- Matriz de Submódulos -->
                  <div class="card border-left-primary shadow-sm mb-2" id="contenedorSubmodulos">
                    <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                      <span class="font-weight-bold text-primary small">
                        <i class="fas fa-layer-group mr-1"></i>Submódulos Permitidos para este Rol
                      </span>
                      <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" onclick="seleccionarTodosSub(true)">Todos</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" onclick="seleccionarTodosSub(false)">Ninguno</button>
                      </div>
                    </div>
                    <div class="card-body p-2" style="max-height: 250px; overflow-y: auto;">
                      <div class="row no-gutters">
                        <?php if (!empty($catalogoSubmodulos)): ?>
                          <?php foreach ($catalogoSubmodulos as $modCat): ?>
                            <div class="col-md-6 mb-2 px-1">
                              <div class="p-2 border rounded bg-white h-100">
                                <div class="font-weight-bold text-dark small mb-1 border-bottom pb-1">
                                  <i class="fas <?php echo htmlspecialchars($modCat['iconoModulo']); ?> text-primary mr-1"></i>
                                  <?php echo htmlspecialchars($modCat['nombreModulo']); ?>
                                </div>
                                <?php foreach ($modCat['submodulos'] as $subCat): ?>
                                  <div class="custom-control custom-checkbox small ml-1 my-1">
                                    <input type="checkbox" class="custom-control-input chk-sub-rol" name="submodulos[]" 
                                           value="<?php echo (int)$subCat['idSubmodulo']; ?>" 
                                           id="subrol_<?php echo (int)$subCat['idSubmodulo']; ?>">
                                    <label class="custom-control-label text-dark font-weight-normal" for="subrol_<?php echo (int)$subCat['idSubmodulo']; ?>">
                                      <i class="fas <?php echo htmlspecialchars($subCat['icono']); ?> mr-1 text-muted" style="font-size: 0.75rem;"></i>
                                      <?php echo htmlspecialchars($subCat['nombre']); ?>
                                    </label>
                                  </div>
                                <?php endforeach; ?>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="modal-footer bg-white">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i>Cerrar</button>
                  <button type="submit" class="btn btn-primary" name="insertar" id="btnGuardarRol">
                    <i class="fas fa-save mr-1"></i>Guardar Rol
                  </button>
                  <button type="submit" class="btn btn-warning" name="modificar" id="btnModificarRol" style="display: none;">
                    <i class="fas fa-edit mr-1"></i>Guardar Cambios
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Formulario oculto para cambiar estado -->
        <form method="POST" id="formCambiarEstado" action="controllerRoles.php" style="display: none;">
          <input type="hidden" name="txtIdRol" id="estadoIdRol" value="">
          <input type="hidden" name="nuevoEstado" id="estadoNuevo" value="">
          <input type="hidden" name="cambiar_estado" value="1">
        </form>

        <!-- Tabla de Roles -->
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-table mr-2"></i>Matriz Oficial de Roles y Subroles</h6>
            <span class="badge badge-primary px-3 py-2"><?php echo count($listaRoles); ?> Roles Registrados</span>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-hover datatable" width="100%" cellspacing="0">
                <thead class="thead-light">
                  <tr>
                    <th style="width: 60px;"># ID</th>
                    <th>Nombre del Rol</th>
                    <th>Jerarquía</th>
                    <th>Descripción</th>
                    <th>Permisos Asignados</th>
                    <th class="text-center" style="width: 100px;">Estado</th>
                    <th class="text-center" style="width: 190px;">Acciones</th>               
                  </tr>
                </thead>
                <tbody>
                <?php 
                foreach ($listaRoles as $r): 
                  $idR = (int)$r['id_Rol'];
                  $nombreR = $r['nombreRol'];
                  $descR = $r['descripcion'] ?? 'Sin descripción';
                  $padreR = $r['nombreRolPadre'] ?? null;
                  $accesoTot = (int)($r['acceso_total'] ?? 0);
                  $activoR = (int)($r['activo'] ?? 1);
                  $subEfectivos = $r['submodulosEfectivos'] ?? [];
                  $esProtegido = in_array($idR, [1, 4], true);
                ?>
                <tr class="<?php echo ($activoR === 0 ? 'table-secondary text-muted' : ''); ?>">
                  <td class="align-middle font-weight-bold">#<?php echo $idR; ?></td>
                  <td class="align-middle">
                    <span class="font-weight-bold text-dark"><?php echo htmlspecialchars($nombreR); ?></span>
                  </td>
                  <td class="align-middle">
                    <?php if (!empty($padreR)): ?>
                      <span class="text-muted"><i class="fas fa-level-up-alt text-primary mr-1"></i>Hereda de <strong><?php echo htmlspecialchars($padreR); ?></strong></span>
                    <?php else: ?>
                      <span class="text-muted"><i class="fas fa-layer-group mr-1 text-secondary"></i>Rol Base</span>
                    <?php endif; ?>
                  </td>
                  <td class="align-middle">
                    <span class="text-muted"><?php echo htmlspecialchars($descR); ?></span>
                  </td>
                  <td class="align-middle">
                    <?php if ($accesoTot === 1): ?>
                      <span class="badge badge-success font-weight-normal"><i class="fas fa-check-circle mr-1"></i>Acceso Total (Todos)</span>
                    <?php else: ?>
                      <span class="badge badge-light border text-dark font-weight-normal"><i class="fas fa-cubes mr-1 text-muted"></i><?php echo count($subEfectivos); ?> submódulos</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center align-middle">
                    <?php if ($activoR === 1): ?>
                      <span class="badge badge-success">Activo</span>
                    <?php else: ?>
                      <span class="badge badge-danger">Inactivo</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center align-middle">
                    <div class="btn-group btn-group-sm">
                      <button type="button" class="btn btn-outline-primary" onclick="cargarEdicionRol(<?php echo $idR; ?>)">
                        <i class="fas fa-edit mr-1"></i>Editar
                      </button>
                      <?php if (!$esProtegido): ?>
                        <?php if ($activoR === 1): ?>
                          <button type="button" class="btn btn-outline-danger" onclick="confirmarCambioEstado(<?php echo $idR; ?>, 0, '<?php echo htmlspecialchars($nombreR, ENT_QUOTES); ?>')">
                            <i class="fas fa-ban mr-1"></i>Desactivar
                          </button>
                        <?php else: ?>
                          <button type="button" class="btn btn-outline-success" onclick="confirmarCambioEstado(<?php echo $idR; ?>, 1, '<?php echo htmlspecialchars($nombreR, ENT_QUOTES); ?>')">
                            <i class="fas fa-check mr-1"></i>Activar
                          </button>
                        <?php endif; ?>
                      <?php else: ?>
                        <span class="badge badge-light text-muted border py-1 px-2"><i class="fas fa-lock mr-1"></i>Protegido</span>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">
            <i class="fas fa-clock mr-1"></i>Actualizado el <?php echo date('d/m/Y \a \l\a\s H:i'); ?>
          </div>
        </div>

      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <footer class="sticky-footer bg-white shadow-sm border-top">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Concentrados El Gordito &copy; <?php echo date('Y'); ?></span>
          </div>
        </div>
      </footer>

    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
  <script src="js/sb-admin.min.js"></script>
  <script src="js/translations.js"></script>
  <script src="js/demo/datatables-demo.js"></script>

  <script>
    function toggleAccesoTotal() {
      if ($('#chkAccesoTotal').is(':checked')) {
        $('.chk-sub-rol').prop('checked', true).prop('disabled', true);
      } else {
        $('.chk-sub-rol').prop('disabled', false);
      }
    }

    function seleccionarTodosSub(marcar) {
      if (!$('#chkAccesoTotal').is(':checked')) {
        $('.chk-sub-rol').prop('checked', marcar);
      }
    }

    function limpiarModalRol() {
      $('#modalTitulo').text('Registro de Rol / Subrol');
      $('#txtIdRol').val('');
      $('#txtNombreRol').val('');
      $('#txtDescripcion').val('');
      $('#txtIdRolPadre').val('');
      $('#chkAccesoTotal').prop('checked', false);
      $('#chkActivo').prop('checked', true).prop('disabled', false);
      $('.chk-sub-rol').prop('checked', false).prop('disabled', false);

      $('#btnGuardarRol').show();
      $('#btnModificarRol').hide();
    }

    function cargarEdicionRol(idRol) {
      limpiarModalRol();
      $('#modalTitulo').text('Modificar Rol #' + idRol);
      $('#txtIdRol').val(idRol);
      $('#btnGuardarRol').hide();
      $('#btnModificarRol').show();

      $.getJSON('controllerRoles.php?accion=obtenerRol&idRol=' + idRol, function(res) {
        if (res && res.exito && res.rol) {
          const r = res.rol;
          $('#txtNombreRol').val(r.nombreRol);
          $('#txtDescripcion').val(r.descripcion || '');
          $('#txtIdRolPadre').val(r.idRolPadre || '');

          const esProtegido = (idRol === 1 || idRol === 4);
          if (esProtegido) {
            $('#chkActivo').prop('checked', true).prop('disabled', true);
          } else {
            $('#chkActivo').prop('checked', parseInt(r.activo) === 1).prop('disabled', false);
          }

          if (parseInt(r.acceso_total) === 1) {
            $('#chkAccesoTotal').prop('checked', true);
            $('.chk-sub-rol').prop('checked', true).prop('disabled', true);
          } else {
            $('#chkAccesoTotal').prop('checked', false);
            $('.chk-sub-rol').prop('disabled', false);
            const subs = r.submodulosArr || [];
            subs.forEach(function(idSub) {
              $('#subrol_' + idSub).prop('checked', true);
            });
          }

          $('#modalRol').modal('show');
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar la información del rol.' });
        }
      });
    }

    function confirmarCambioEstado(idRol, nuevoEstado, nombreRol) {
      const accionTxt = (nuevoEstado === 1) ? 'activar' : 'desactivar';
      const colorBtn = (nuevoEstado === 1) ? '#28a745' : '#dc3545';
      const detalleTxt = (nuevoEstado === 1) 
        ? 'El rol quedará habilitado para ser asignado a empleados y operar en el sistema.' 
        : 'Los empleados con este rol asignado no podrán acceder a sus submódulos correspondientes mientras esté inactivo.';

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: '¿Desea ' + accionTxt + ' el rol?',
          text: 'Rol: "' + nombreRol + '". ' + detalleTxt,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: colorBtn,
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Sí, ' + accionTxt,
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            $('#estadoIdRol').val(idRol);
            $('#estadoNuevo').val(nuevoEstado);
            $('#formCambiarEstado').submit();
          }
        });
      } else {
        if (confirm(`¿Desea ${accionTxt} el rol "${nombreRol}"? ${detalleTxt}`)) {
          $('#estadoIdRol').val(idRol);
          $('#estadoNuevo').val(nuevoEstado);
          $('#formCambiarEstado').submit();
        }
      }
    }
  </script>

</body>
</html>
<?php 
if (isset($msj, $icon)) {
  echo "<script>Swal.fire({ title: '$msj', icon: '$icon', confirmButtonText: 'Aceptar' });</script>";
}
?>
