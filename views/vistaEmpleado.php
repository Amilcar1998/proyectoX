<?php 
include 'configuracion.php'; 
?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Gestión de Empleados - Concentrados El Gordito">
  <meta name="author" content="Concentrados El Gordito">

  <title>👷 Gestión de Empleados | Concentrados El Gordito</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">  
  <link href="vendor/sb-admin.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    /* BADGES MODERNOS */
    .status-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 5px 12px;
      border-radius: 9999px;
      font-size: 0.8rem;
      font-weight: 600;
      letter-spacing: 0.02em;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
      white-space: nowrap;
    }

    .status-pill-completado {
      background-color: #ecfdf5;
      color: #065f46;
      border: 1px solid #a7f3d0;
    }
    .status-pill-completado i { color: #059669; }

    .status-pill-cancelado {
      background-color: #fff1f2;
      color: #9f1239;
      border: 1px solid #fecdd3;
    }
    .status-pill-cancelado i { color: #e11d48; }

    .role-pill-admin {
      background-color: #eef2ff;
      color: #4338ca;
      border: 1px solid #c7d2fe;
      padding: 4px 10px;
      border-radius: 999px;
      font-weight: 600;
      font-size: 0.82rem;
    }

    .role-pill-user {
      background-color: #f0f9ff;
      color: #0369a1;
      border: 1px solid #bae6fd;
      padding: 4px 10px;
      border-radius: 999px;
      font-weight: 600;
      font-size: 0.82rem;
    }
  </style>

</head>
<body id="page-top">
  <?php echo "$nav"; ?>

  <div id="wrapper">

    <?php echo "$menu"; ?>

    <div id="content-wrapper">

      <div class="container-fluid py-4">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb bg-white shadow-sm rounded-lg mb-4 py-2 px-3 border">
          <li class="breadcrumb-item">
            <a href="controllerDashboard.php" class="text-secondary"><i class="fas fa-home"></i> Inicio</a>
          </li>
          <li class="breadcrumb-item active text-dark font-weight-bold"><i class="fas fa-user-tie mr-1"></i> Gestión de Empleados</li>
        </ol>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
          <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
              <i class="fas fa-users-cog text-primary mr-2"></i>Gestión de Empleados
            </h1>
            <p class="text-muted mb-0">Administración del personal operativo, administrativo y sus credenciales de acceso.</p>
          </div>
          <div>
            <button class="btn btn-primary shadow-sm" id="agregarC" data-toggle="modal" data-target="#modalEmpleado" onclick="limpiarEmpleado()">
              <i class="fas fa-user-plus mr-1"></i>Nuevo Empleado
            </button>
            <a href="repoEmpleado.php" target="_blank" class="btn btn-outline-danger shadow-sm ml-2">
              <i class="fas fa-file-pdf mr-1"></i>Exportar PDF
            </a>
          </div>
        </div>

        <!-- Modal Empleado -->
        <div class="modal fade" id="modalEmpleado" tabindex="-1" role="dialog" aria-labelledby="modalEmpleadoLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0 shadow">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="modalEmpleadoLabel">
                  <i class="fas fa-user-tie mr-2"></i><span id="modalTitulo">Registro de Empleado</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="POST" id="formEmpleado" action="controllerEmpleado.php">
                <div class="modal-body p-4 bg-light">
                  <input type="hidden" name="txtIdEmpleado" id="txtIdEmpleado" value="">
                  <input type="hidden" name="txtIdUsuario" id="txtIdUsuario" value="">

                  <div class="row">
                    <div class="col-md-6 form-group">
                      <label for="txtNombres" class="font-weight-bold small">Nombres <span class="text-danger">*</span></label>
                      <input type="text" name="txtNombres" id="txtNombres" class="form-control" required placeholder="Ej: Juan Carlos">
                    </div>
                    <div class="col-md-6 form-group">
                      <label for="txtApellidos" class="font-weight-bold small">Apellidos <span class="text-danger">*</span></label>
                      <input type="text" name="txtApellidos" id="txtApellidos" class="form-control" required placeholder="Ej: Perez Gomez">
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4 form-group">
                      <label for="txtGenero" class="font-weight-bold small">Género</label>
                      <select name="txtGenero" id="txtGenero" class="form-control">
                        <option value="Hombre">Hombre</option>
                        <option value="Mujer">Mujer</option>
                      </select>
                    </div>
                    <div class="col-md-4 form-group">
                      <label for="txtCargo" class="font-weight-bold small">Puesto Laboral <span class="text-danger">*</span></label>
                      <select name="txtCargo" id="txtCargo" class="form-control" required onchange="sugerirRolSegunPuesto()">
                        <option value="">Seleccione un cargo...</option>
                        <?php foreach ($puesto as $c): ?>
                          <option value="<?php echo (int)$c['idPuesto']; ?>">
                            <?php echo htmlspecialchars($c['nombrePuesto']); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-md-4 form-group">
                      <label for="txtRol" class="font-weight-bold small">Rol en el Sistema <span class="text-danger">*</span></label>
                      <select name="txtRol" id="txtRol" class="form-control" required>
                        <?php foreach ($rolesSistema as $r): ?>
                          <option value="<?php echo (int)$r['id_Rol']; ?>">
                            <?php echo htmlspecialchars($r['nombreRol']); ?>
                            <?php echo (!empty($r['nombreRolPadre']) ? ' (Subrol de ' . htmlspecialchars($r['nombreRolPadre']) . ')' : ''); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-md-8 form-group mb-2">
                      <label for="txtUser" class="font-weight-bold small">Usuario Institucional (Generado Automáticamente)</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text bg-white"><i class="fas fa-envelope text-muted"></i></span>
                        </div>
                        <input type="text" name="txtUser" id="txtUser" class="form-control bg-white" readonly placeholder="Se generará automáticamente (ej: juan.perez@<?php echo htmlspecialchars($dominioEmpresa ?? 'gordito.com'); ?>)">
                      </div>
                      <small class="form-text text-muted">El empleado utilizará este correo institucional para iniciar sesión.</small>
                    </div>
                    <div class="col-md-4 form-group mb-2" id="grupoEstadoEmpleado">
                      <label class="font-weight-bold small">Estado del Empleado</label>
                      <div class="custom-control custom-checkbox bg-white p-2 rounded border">
                        <input type="checkbox" class="custom-control-input" id="chkActivoEmp" name="chkActivo" value="1" checked>
                        <label class="custom-control-label font-weight-bold text-dark small" for="chkActivoEmp">
                          <i class="fas fa-check-circle text-success mr-1"></i>Activo
                        </label>
                      </div>
                    </div>
                  </div>

                  <!-- Asignación de Permisos a Submódulos -->
                  <div class="card border-left-primary shadow-sm mb-2">
                    <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                      <div>
                        <span class="font-weight-bold text-primary small">
                          <i class="fas fa-shield-alt mr-1"></i>Asignación de Submódulos Permitidos
                        </span>
                        <small class="text-muted d-block" style="font-size: 0.72rem;">
                          <i class="fas fa-info-circle text-info mr-1"></i>Gerente y Admin (o al dejar sin marcar) tienen acceso total a todos los submódulos.
                        </small>
                      </div>
                      <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" onclick="marcarSubmodulos('todos')">Todos</button>
                        <button type="button" class="btn btn-outline-info btn-sm py-0 px-2" onclick="marcarSubmodulos('rol')">Por Rol</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" onclick="marcarSubmodulos('ninguno')">Ninguno</button>
                      </div>
                    </div>
                    <div class="card-body p-2" style="max-height: 230px; overflow-y: auto;">
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
                                    <input type="checkbox" class="custom-control-input chk-submodulo" name="submodulos[]" 
                                           value="<?php echo (int)$subCat['idSubmodulo']; ?>" 
                                           id="sub_<?php echo (int)$subCat['idSubmodulo']; ?>">
                                    <label class="custom-control-label text-dark font-weight-normal" for="sub_<?php echo (int)$subCat['idSubmodulo']; ?>">
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
                  <button type="submit" class="btn btn-primary" name="insertar" id="btnInsertar">
                    <i class="fas fa-save mr-1"></i>Guardar Empleado
                  </button>
                  <button type="submit" class="btn btn-warning" name="modificar" id="btnModificar" style="display: none;">
                    <i class="fas fa-edit mr-1"></i>Guardar Cambios
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
         
        <!-- Formulario oculto para alternar estado de empleado -->
        <form id="formCambiarEstadoEmpleado" method="POST" action="controllerEmpleado.php" style="display: none;">
          <input type="hidden" name="cambiar_estado" value="1">
          <input type="hidden" name="txtIdEmpleado" id="estadoIdEmpleado" value="">
          <input type="hidden" name="nuevoEstado" id="estadoNuevoEmpleado" value="">
        </form>

        <!-- Tabla de Empleados -->
        <div class="card card-custom mb-4">
          <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-id-card text-primary mr-2"></i>Nómina y Directorio de Empleados</h6>
            <span class="badge badge-light border text-dark px-3 py-2 font-weight-bold"><?php echo count($datos); ?> Empleados Registrados</span>
          </div>
          <div class="card-body p-4">
            <div class="table-responsive">
              <table class="table table-custom table-hover datatable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th class="text-center" style="width: 70px;"># ID</th>
                    <th>Nombres y Apellidos</th>
                    <th>Género</th>
                    <th>Puesto Laboral</th> 
                    <th>Rol en Sistema</th>
                    <th>Usuario Institucional</th>
                    <th class="text-center" style="width: 120px;">Estado</th>
                    <th class="text-center" style="width: 190px;">Acciones</th>               
                  </tr>
                </thead>
                <tbody>
                <?php 
                if (!empty($datos)):
                  foreach ($datos as $e):
                     $idEmpleado = (int)$e->getIdEmpleado();
                     $nombresEmp = $e->getNombre();
                     $apellidoEmp = $e->getApellido(); 
                     $generoEmp = $e->getGenero();
                     $cargoEmp = $e->getCargo();
                     $userEmp = $e->getUsername();
                     $idPuestoEmp = $e->getIdPuesto();
                     $idUsuarioEmp = $e->getIdUsuario();
                     $idRolEmp = (int)$e->getIdRol();
                     $nombreRolEmp = $e->getNombreRol();
                     $activoEmp = (int)$e->getActivo();

                     $roleClass = ($idRolEmp === 1 || $idRolEmp === 4) ? 'role-pill-admin' : 'role-pill-user';
                ?>
                <tr class="<?php echo ($activoEmp === 0 ? 'table-secondary text-muted' : ''); ?>">
                  <td class="text-center font-weight-bold">
                    <span class="badge badge-light border px-2 py-1">#<?php echo $idEmpleado; ?></span>
                  </td>
                  <td class="align-middle font-weight-bold text-dark">
                    <i class="fas fa-user-circle text-secondary mr-1"></i><?php echo htmlspecialchars($nombresEmp . ' ' . $apellidoEmp); ?>
                  </td>
                  <td class="align-middle">
                    <?php if (strtolower($generoEmp) === 'hombre' || strtolower($generoEmp) === 'm'): ?>
                      <span class="text-muted"><i class="fas fa-mars mr-1 text-primary"></i>Hombre</span>
                    <?php else: ?>
                      <span class="text-muted"><i class="fas fa-venus mr-1 text-danger"></i>Mujer</span>
                    <?php endif; ?>
                  </td>
                  <td class="align-middle">
                    <span class="badge badge-pill badge-light border px-2 py-1 font-weight-bold text-dark"><?php echo htmlspecialchars($cargoEmp); ?></span>
                  </td>
                  <td class="align-middle"><span class="badge <?php echo $roleClass; ?>"><?php echo htmlspecialchars($nombreRolEmp); ?></span></td>
                  <td class="align-middle">
                    <code class="text-primary"><?php echo htmlspecialchars($userEmp); ?></code>
                  </td>
                  <td class="text-center align-middle">
                    <?php if ($activoEmp === 1): ?>
                      <span class="status-pill status-pill-completado"><i class="fas fa-check-circle"></i> Activo</span>
                    <?php else: ?>
                      <span class="status-pill status-pill-cancelado"><i class="fas fa-times-circle"></i> Inactivo</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center align-middle">
                    <div class="btn-group btn-group-sm">
                      <button type="button" class="btn btn-outline-primary font-weight-bold px-2 py-1" data-toggle="modal" data-target="#modalEmpleado" onclick='cargarEmpleado("<?php echo htmlspecialchars((string)$idEmpleado, ENT_QUOTES); ?>", "<?php echo htmlspecialchars($nombresEmp, ENT_QUOTES); ?>", "<?php echo htmlspecialchars($apellidoEmp, ENT_QUOTES); ?>", "<?php echo htmlspecialchars($generoEmp, ENT_QUOTES); ?>", "<?php echo htmlspecialchars((string)$idPuestoEmp, ENT_QUOTES); ?>", "<?php echo htmlspecialchars((string)$idUsuarioEmp, ENT_QUOTES); ?>", "<?php echo htmlspecialchars($userEmp, ENT_QUOTES); ?>", "<?php echo htmlspecialchars((string)$idRolEmp, ENT_QUOTES); ?>", "<?php echo $activoEmp; ?>")'>
                        <i class="fas fa-edit mr-1"></i>Editar
                      </button>
                      <?php if ($activoEmp === 1): ?>
                        <button type="button" class="btn btn-outline-danger font-weight-bold px-2 py-1 ml-1" onclick="confirmarEstadoEmpleado(<?php echo $idEmpleado; ?>, 0, '<?php echo htmlspecialchars($nombresEmp.' '.$apellidoEmp, ENT_QUOTES); ?>')">
                          <i class="fas fa-ban mr-1"></i>Desactivar
                        </button>
                      <?php else: ?>
                        <button type="button" class="btn btn-outline-success font-weight-bold px-2 py-1 ml-1" onclick="confirmarEstadoEmpleado(<?php echo $idEmpleado; ?>, 1, '<?php echo htmlspecialchars($nombresEmp.' '.$apellidoEmp, ENT_QUOTES); ?>')">
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
          <div class="card-footer bg-white small text-muted border-top">
            <i class="fas fa-clock mr-1"></i>Actualizado el <?php echo date('d/m/Y \a \l\a\s H:i'); ?>
          </div>
        </div>

      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer elegante en color negro -->
      <footer class="sticky-footer bg-dark text-white mt-auto">
        <div class="container-fluid text-center">
          <span>Concentrados El Gordito &bull; Sistema de Gestión &copy; <?php echo date('Y'); ?></span>
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
    const SUBMODULOS_POR_ROL = <?php 
      $map = [];
      if (!empty($rolesJerarquia)) {
          foreach ($rolesJerarquia as $rj) {
              $map[(int)$rj['id_Rol']] = $rj['submodulosEfectivos'] ?? [];
          }
      }
      if (empty($map)) {
          $map = [
              1 => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
              2 => [1, 2, 4, 5, 6, 7, 8, 12, 13],
              4 => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
              5 => [1, 2, 4, 5, 6, 7, 8, 11, 12, 13],
              6 => [1, 2, 4, 5, 6, 7, 8, 12, 13],
              7 => [1, 2, 3, 4, 5, 6, 7, 8, 11, 12, 13]
          ];
      }
      echo json_encode($map); 
    ?>;

    function marcarSubmodulos(modo) {
      $('.chk-submodulo').prop('checked', false);
      if (modo === 'todos') {
        $('.chk-submodulo').prop('checked', true);
      } else if (modo === 'rol') {
        const rolActual = parseInt($('#txtRol').val()) || 2;
        const permitidos = SUBMODULOS_POR_ROL[rolActual] || SUBMODULOS_POR_ROL[2] || [];
        permitidos.forEach(function(id) {
          $('#sub_' + id).prop('checked', true);
        });
      }
    }

    const DOMINIO_EMPRESA_ACTUAL = '<?php echo htmlspecialchars($dominioEmpresa ?? 'gordito.com', ENT_QUOTES); ?>';

    function actualizarUsuarioPreview() {
      if ($('#txtIdEmpleado').val() === '') {
        var n = $('#txtNombres').val().trim().split(/\s+/)[0] || '';
        var a = $('#txtApellidos').val().trim().split(/\s+/)[0] || '';
        n = n.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[^a-z0-9]/g, "");
        a = a.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[^a-z0-9]/g, "");
        if (n || a) {
          $('#txtUser').val((n && a ? n + '.' + a : (n || a)) + '@' + DOMINIO_EMPRESA_ACTUAL);
        } else {
          $('#txtUser').val('');
        }
      }
    }

    $('#txtNombres, #txtApellidos').on('input', actualizarUsuarioPreview);

    function sugerirRolSegunPuesto() {
      var puesto = parseInt($('#txtCargo').val()) || 0;
      if (puesto === 1) {
        $('#txtRol').val('1'); // Gerente General -> Gerente
      } else if (puesto === 2) {
        $('#txtRol').val('5'); // Jefe de Produccion -> Subrol Jefe de Producción
      } else if (puesto === 5) {
        $('#txtRol').val('7'); // Ventas -> Subrol Supervisor de Ventas
      } else if (puesto === 6) {
        $('#txtRol').val('6'); // Almacenista -> Subrol Jefe de Almacén
      } else if (puesto === 12) {
        $('#txtRol').val('4'); // Sistemas -> Administrador
      } else {
        // Operario y otros puestos operativos
        $('#txtRol').val('2'); // Empleado Operativo Base
      }
      marcarSubmodulos('rol');
    }

    $('#txtRol').on('change', function() {
      marcarSubmodulos('rol');
    });

    function cargarEmpleado(id, nombres, apellidos, genero, cargo, idUsuario, username, idRol, activo) {
      $('#modalTitulo').text('Modificar Empleado #' + id);
      $('#txtIdEmpleado').val(id);
      $('#txtNombres').val(nombres);
      $('#txtApellidos').val(apellidos);
      $('#txtGenero').val(genero);
      $('#txtCargo').val(cargo);
      $('#txtRol').val(idRol || '2');
      $('#txtIdUsuario').val(idUsuario);
      $('#txtUser').val(username);
      $('#chkActivoEmp').prop('checked', parseInt(activo) !== 0);

      $('#btnInsertar').hide();
      $('#btnModificar').show();

      // Cargar submódulos asignados del usuario vía AJAX
      $('.chk-submodulo').prop('checked', false);
      if (idUsuario) {
        $.getJSON('controllerEmpleado.php?accion=obtenerSubmodulosUsuario&idUsuario=' + idUsuario, function(res) {
          if (res && res.exito && res.submodulos && res.submodulos.length > 0) {
            res.submodulos.forEach(function(idSub) {
              $('#sub_' + idSub).prop('checked', true);
            });
          } else {
            marcarSubmodulos('rol');
          }
        }).fail(function() {
          marcarSubmodulos('rol');
        });
      } else {
        marcarSubmodulos('rol');
      }
    }

    function limpiarEmpleado() {
      $('#modalTitulo').text('Registro de Empleado');
      $('#txtIdEmpleado').val('');
      $('#txtNombres').val('');
      $('#txtApellidos').val('');
      $('#txtGenero').val('Hombre');
      $('#txtCargo').val('');
      $('#txtRol').val('2');
      $('#txtIdUsuario').val('');
      $('#txtUser').val('');
      $('#chkActivoEmp').prop('checked', true);

      $('#btnInsertar').show();
      $('#btnModificar').hide();

      marcarSubmodulos('rol');
    }

    function confirmarEstadoEmpleado(idEmpleado, nuevoEstado, nombre) {
      const accion = nuevoEstado === 1 ? 'activar' : 'desactivar';
      const colorBoton = nuevoEstado === 1 ? '#28a745' : '#dc3545';
      const textoAccion = nuevoEstado === 1 ? 'El empleado y su usuario de acceso quedarán activos para operar.' : 'El empleado y su cuenta institucional quedarán desactivados y no podrán iniciar sesión.';

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: `¿Desea ${accion} al empleado?`,
          text: `Empleado: ${nombre}. ${textoAccion}`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: colorBoton,
          cancelButtonColor: '#6c757d',
          confirmButtonText: `Sí, ${accion}`,
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            $('#estadoIdEmpleado').val(idEmpleado);
            $('#estadoNuevoEmpleado').val(nuevoEstado);
            $('#formCambiarEstadoEmpleado').submit();
          }
        });
      } else {
        if (confirm(`¿Desea ${accion} al empleado ${nombre}? ${textoAccion}`)) {
          $('#estadoIdEmpleado').val(idEmpleado);
          $('#estadoNuevoEmpleado').val(nuevoEstado);
          $('#formCambiarEstadoEmpleado').submit();
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
