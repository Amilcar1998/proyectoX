<?php include '../views/configuracion.php'; ?>
<?php
// Contadores para filtros directos
$totalEmpresas = count($listaEmpresas);
$activasCount = 0;
$inactivasCount = 0;
foreach ($listaEmpresas as $e) {
    if ((int)($e['activo'] ?? 1) === 1) {
        $activasCount++;
    } else {
        $inactivasCount++;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>🏢 Administración de Empresas - Concentrados El Gordito</title>

    <!-- Custom fonts and styles -->
    <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../controllers/vendor/sb-admin.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }
        .card-header-gradient {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #ffffff;
        }
        .wompi-banner {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: #ffffff;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 18px;
        }
        .store-url-box {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 12px 16px;
        }
        .avatar-empresa {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.05rem;
        }
        .avatar-empresa-lg {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.4rem;
        }
        .table td { vertical-align: middle; }
        .filtro-btn {
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 6px 16px;
            transition: all 0.2s ease;
        }
    </style>
</head>
<body id="page-top">
    <?php echo "$nav"; ?>
    <div id="wrapper">
        <?php echo "$menu"; ?>
        <div id="content-wrapper">
            <div class="container-fluid py-4">

                <!-- Breadcrumb -->
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="controllerDashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Administración de Empresas</li>
                </ol>

                <!-- Título y Encabezado -->
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                            <i class="fas fa-building text-primary mr-2"></i>
                            <?php echo $esSuperUsuario ? 'Administración Global de Empresas' : 'Configuración de Mi Empresa'; ?>
                        </h1>
                        <p class="text-muted small mb-0">
                            <?php if ($esSuperUsuario): ?>
                                Panel Superusuario: Supervisión multi-comercio, control de tenants y pasarelas de pago.
                            <?php else: ?>
                                Panel Empresa: Gestión de perfil institucional, canales de contacto y pasarela Wompi SV de tu negocio.
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="mt-2 mt-sm-0 d-flex">
                        <?php if ($esSuperUsuario): ?>
                            <button type="button" class="btn btn-primary font-weight-bold shadow-sm mr-2" data-toggle="modal" data-target="#modalNuevaEmpresa">
                                <i class="fas fa-plus mr-1"></i>Registrar Nueva Empresa
                            </button>
                        <?php endif; ?>
                        <a href="<?php echo htmlspecialchars($urlTiendaPublica); ?>" target="_blank" class="btn btn-outline-success font-weight-bold shadow-sm">
                            <i class="fas fa-external-link-alt mr-1"></i>Ver Catálogo Online
                        </a>
                    </div>
                </div>

                <!-- Alertas del Sistema -->
                <?php if (!empty($mensajeExito)): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($mensajeExito); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($mensajeError)): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i><?php echo htmlspecialchars($mensajeError); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- SECCIÓN: LISTADO DE EMPRESAS REGISTRADAS (Superusuario ve todas, Tenant ve solo la suya) -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header card-header-gradient d-flex justify-content-between align-items-center flex-wrap py-3">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-list mr-2"></i>
                                <?php echo $esSuperUsuario ? "Empresas Registradas ({$totalEmpresas})" : "Datos de Tu Empresa Registrada"; ?>
                            </h6>
                            <?php if ($esSuperUsuario): ?>
                                <span class="badge badge-warning text-dark font-weight-bold ml-2 px-2 py-1"><i class="fas fa-crown mr-1"></i>Superusuario Global</span>
                            <?php else: ?>
                                <span class="badge badge-info font-weight-bold ml-2 px-2 py-1"><i class="fas fa-building mr-1"></i>Empresa Aislada</span>
                            <?php endif; ?>
                        </div>

                        <?php if ($esSuperUsuario): ?>
                        <!-- Filtros Directos -->
                        <div class="d-flex align-items-center flex-wrap">
                            <span class="text-light small font-weight-bold mr-2"><i class="fas fa-filter mr-1"></i>Filtrar:</span>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary filtro-btn active mr-1" data-filtro="todas">
                                    Todas <span class="badge badge-light ml-1"><?php echo $totalEmpresas; ?></span>
                                </button>
                                <button type="button" class="btn btn-outline-light filtro-btn mr-1" data-filtro="activa">
                                    Activas <span class="badge badge-success ml-1"><?php echo $activasCount; ?></span>
                                </button>
                                <button type="button" class="btn btn-outline-light filtro-btn" data-filtro="inactiva">
                                    Inactivas <span class="badge badge-danger ml-1"><?php echo $inactivasCount; ?></span>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered datatable" id="dataTable" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 50px;">ID</th>
                                        <th>Empresa</th>
                                        <th>Dueño / Responsable</th>
                                        <th>Contacto</th>
                                        <th>Dirección</th>
                                        <th style="width: 90px;" class="text-center">Estado</th>
                                        <th style="width: 140px;" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($listaEmpresas)): ?>
                                    <?php foreach ($listaEmpresas as $empItem): ?>
                                        <?php 
                                            $estaActiva = ((int)($empItem['activo'] ?? 1) === 1);
                                            $inicial = strtoupper(mb_substr($empItem['nombreEmpresa'] ?? 'E', 0, 1));
                                        ?>
                                        <tr>
                                            <td class="text-center font-weight-bold text-muted">#<?php echo htmlspecialchars($empItem['idEmpresa']); ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-empresa mr-2"><?php echo htmlspecialchars($inicial); ?></div>
                                                    <div>
                                                        <div class="font-weight-bold text-dark"><?php echo htmlspecialchars($empItem['nombreEmpresa']); ?></div>
                                                        <small class="text-muted">
                                                            <i class="fas fa-link mr-1"></i>/<?php echo htmlspecialchars($empItem['slug']); ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold text-dark"><i class="fas fa-user-circle text-secondary mr-1"></i><?php echo htmlspecialchars($empItem['usuarioDueno'] ?? 'Sin asignar'); ?></div>
                                                <?php if (!empty($empItem['nombreResponsable'])): ?>
                                                    <small class="text-muted"><?php echo htmlspecialchars(trim($empItem['nombreResponsable'])); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($empItem['telefono'])): ?>
                                                    <div><i class="fas fa-phone-alt text-primary mr-1"></i><small><?php echo htmlspecialchars($empItem['telefono']); ?></small></div>
                                                <?php endif; ?>
                                                <?php if (!empty($empItem['whatsapp'])): ?>
                                                    <div>
                                                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $empItem['whatsapp']); ?>" target="_blank" class="text-success small font-weight-bold">
                                                            <i class="fab fa-whatsapp mr-1"></i><?php echo htmlspecialchars($empItem['whatsapp']); ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($empItem['correo'])): ?>
                                                    <div><small class="text-muted"><i class="fas fa-envelope mr-1"></i><?php echo htmlspecialchars($empItem['correo']); ?></small></div>
                                                <?php endif; ?>
                                            </td>
                                            <td><small class="text-muted"><?php echo htmlspecialchars($empItem['direccion'] ?? 'No especificada'); ?></small></td>
                                            <td class="text-center">
                                                <?php if ($estaActiva): ?>
                                                    <span class="badge badge-success px-3 py-1 font-weight-bold" style="font-size: 0.8rem;">Activa</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger px-3 py-1 font-weight-bold" style="font-size: 0.8rem;">Inactiva</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-info font-weight-bold" title="Ver y Editar en Modal Largo" onclick='abrirModalEditar(<?php echo json_encode($empItem, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)'>
                                                        <i class="fas fa-edit mr-1"></i>Gestionar
                                                    </button>
                                                    <a href="<?php echo obtenerUrlBase('?tienda=' . $empItem['slug']); ?>" target="_blank" class="btn btn-success" title="Ver Catálogo Público">
                                                        <i class="fas fa-external-link-alt mr-1"></i>Catálogo
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#content-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- ======================================================== -->
    <!-- MODAL LARGO: DETALLE Y EDICIÓN COMPLETA DE LA EMPRESA   -->
    <!-- ======================================================== -->
    <div class="modal fade" id="modalEditarEmpresa" tabindex="-1" role="dialog" aria-labelledby="modalEditarEmpresaLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                
                <!-- Encabezado del Modal con Resumen de la Empresa -->
                <div class="modal-header card-header-gradient d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-empresa-lg mr-3" id="modalEditAvatar">E</div>
                        <div>
                            <h4 class="mb-0 font-weight-bold text-white" id="modalEditTitulo">Nombre de la Empresa</h4>
                            <div class="text-light opacity-90 small" id="modalEditSubtitulo">
                                ID de Registro: #0 &bull; Dueño: usuario@empresa.com
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span id="badgeEstadoEmpresa" class="badge badge-success px-3 py-1 mr-2" style="font-size: 0.85rem;">Activa</span>
                        <span id="badgeEstadoWompi" class="badge badge-primary px-3 py-1 mr-3" style="font-size: 0.85rem;">Wompi: Activo</span>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                        </button>
                    </div>
                </div>

                <div class="modal-body p-4" style="background-color: #f8fafc;">
                    <div class="row">

                        <!-- Columna 1: Información Institucional y Perfil Comercial -->
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="card border-left-primary h-100 shadow-sm border-0">
                                <div class="card-header bg-white font-weight-bold text-primary py-3">
                                    <i class="fas fa-id-card mr-2"></i>Perfil Institucional y Comercial
                                </div>
                                <div class="card-body">
                                    <form method="POST" id="formActualizarEmpresa">
                                        <input type="hidden" name="accion" value="actualizar_empresa">
                                        <input type="hidden" name="idEmpresa" id="edit_idEmpresa" value="">
                                        
                                        <div class="form-group">
                                            <label class="font-weight-bold small text-gray-700">Nombre Comercial de la Empresa</label>
                                            <input type="text" class="form-control" name="nombreEmpresa" id="edit_nombreEmpresa" required>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-bold small text-gray-700">Slug / Identificador URL</label>
                                                <input type="text" class="form-control" name="slug" id="edit_slug" required>
                                                <small class="text-muted">Ruta única para el catálogo</small>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-bold small text-gray-700">Usuario Dueño / Responsable</label>
                                                <?php if ($esSuperUsuario): ?>
                                                    <select class="form-control" name="idUsuarioDueno" id="edit_idUsuarioDueno" required>
                                                        <?php foreach ($listaUsuarios as $u): ?>
                                                            <option value="<?php echo $u['idUsuario']; ?>">
                                                                <?php echo htmlspecialchars($u['username'] . ' (' . ($u['nombreRol'] ?? 'Usuario') . ')'); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                <?php else: ?>
                                                    <input type="text" class="form-control bg-light" readonly value="<?php echo htmlspecialchars($correoUsuario); ?>">
                                                    <input type="hidden" name="idUsuarioDueno" value="<?php echo (int)$idUsuario; ?>">
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold small text-gray-700">Dirección Física</label>
                                            <input type="text" class="form-control" name="direccion" id="edit_direccion" placeholder="Dirección completa del comercio en El Salvador">
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-bold small text-gray-700">Teléfono Central</label>
                                                <input type="text" class="form-control" name="telefono" id="edit_telefono" placeholder="+503 2440-1234">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-bold small text-gray-700">WhatsApp de Ventas</label>
                                                <input type="text" class="form-control" name="whatsapp" id="edit_whatsapp" placeholder="+503 7000-0000">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold small text-gray-700">Correo Electrónico Comercial</label>
                                            <input type="email" class="form-control" name="correo" id="edit_correo" placeholder="contacto@empresa.com">
                                        </div>

                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="edit_activo" name="activo" value="1">
                                            <label class="form-check-label font-weight-bold small text-gray-700" for="edit_activo">
                                                Empresa Habilitada y Activa en el Sistema
                                            </label>
                                        </div>

                                        <button type="submit" class="btn btn-primary font-weight-bold btn-block shadow-sm">
                                            <i class="fas fa-save mr-1"></i>Guardar Cambios de la Empresa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Columna 2: Pasarela Wompi El Salvador y Catálogo Online -->
                        <div class="col-lg-6">
                            <div class="card border-left-success h-100 shadow-sm border-0">
                                <div class="card-header bg-white font-weight-bold text-success py-3">
                                    <i class="fas fa-credit-card mr-2"></i>Pasarela de Pagos Wompi SV
                                </div>
                                <div class="card-body">
                                    
                                    <!-- Banner Informativo de Wompi -->
                                    <div class="wompi-banner shadow-sm">
                                        <h6 class="font-weight-bold mb-1"><i class="fas fa-shield-alt mr-2"></i>Cobros con Wompi El Salvador</h6>
                                        <p class="small mb-0 opacity-90">Los pagos que los clientes realicen en el catálogo se depositarán directamente a la cuenta bancaria de esta empresa.</p>
                                    </div>

                                    <form method="POST" id="formWompi">
                                        <input type="hidden" name="idEmpresa" id="wompi_idEmpresa" value="">
                                        
                                        <div class="form-group">
                                            <label class="font-weight-bold small text-gray-700">ID de Aplicación (App ID)</label>
                                            <input type="text" class="form-control" name="wompiAppId" id="wompi_appId" placeholder="Ej: 5b4d7c8e-xxxx-xxxx-xxxx-xxxxxxxxxxxx">
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold small text-gray-700">Clave Secreta API (API Key)</label>
                                            <input type="password" class="form-control" name="wompiApiKey" id="wompi_apiKey" placeholder="••••••••••••••••••••••••••••••••">
                                        </div>

                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="wompi_activo" name="wompiActivo" value="1">
                                            <label class="form-check-label font-weight-bold small text-gray-700" for="wompi_activo">
                                                Habilitar cobros en línea con Wompi para esta empresa
                                            </label>
                                        </div>

                                        <div class="d-flex justify-content-between flex-wrap mt-3">
                                            <button type="submit" name="guardar_wompi" class="btn btn-success font-weight-bold mb-2 shadow-sm">
                                                <i class="fas fa-save mr-1"></i>Guardar Credenciales Wompi
                                            </button>
                                            <button type="submit" name="probar_conexion_wompi" class="btn btn-outline-info font-weight-bold mb-2">
                                                <i class="fas fa-plug mr-1"></i>Probar Conexión en Vivo
                                            </button>
                                        </div>
                                    </form>

                                    <hr>

                                    <!-- Enlace a Tienda Pública de esta empresa -->
                                    <div class="store-url-box mt-3">
                                        <label class="font-weight-bold small text-gray-700 mb-1">
                                            <i class="fas fa-store mr-1 text-primary"></i>Enlace Directo al Catálogo en Línea:
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm bg-white font-weight-bold text-primary" readonly value="" id="modal_urlTiendaInput">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary btn-sm" type="button" onclick="copiarUrlModal()">
                                                    <i class="fas fa-copy mr-1"></i>Copiar
                                                </button>
                                                <a href="#" target="_blank" id="modal_btnAbrirTienda" class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-white border-top">
                    <button type="button" class="btn btn-secondary font-weight-bold px-4" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL: REGISTRAR NUEVA EMPRESA (Solo Superusuario)        -->
    <!-- ======================================================== -->
    <?php if ($esSuperUsuario): ?>
    <div class="modal fade" id="modalNuevaEmpresa" tabindex="-1" role="dialog" aria-labelledby="modalNuevaEmpresaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header card-header-gradient">
                    <h5 class="modal-title font-weight-bold text-white" id="modalNuevaEmpresaLabel">
                        <i class="fas fa-plus-circle mr-2"></i>Registrar Nueva Empresa en el Sistema
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST">
                    <input type="hidden" name="accion" value="crear_empresa">
                    <div class="modal-body">
                        
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label class="font-weight-bold small text-gray-700">Nombre de la Empresa / Razón Social</label>
                                <input type="text" class="form-control" name="nombreEmpresa" placeholder="Ej: Avícola San José" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold small text-gray-700">Slug (URL opcional)</label>
                                <input type="text" class="form-control" name="slug" placeholder="avicola-san-jose">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold small text-gray-700">Usuario Responsable / Dueño</label>
                                <select class="form-control" name="idUsuarioDueno" id="nueva_idUsuarioDueno" onchange="toggleNuevoDuenoInputs()">
                                    <option value="0" selected>⚡ Auto-generar nuevo Usuario Gerente con el dominio de la empresa</option>
                                    <?php foreach ($listaUsuarios as $u): ?>
                                        <option value="<?php echo $u['idUsuario']; ?>">
                                            <?php echo htmlspecialchars($u['username'] . ' (' . ($u['nombreRol'] ?? 'Usuario') . ')'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold small text-gray-700">Correo Electrónico Comercial</label>
                                <input type="email" class="form-control" name="correo" id="nueva_correo" placeholder="contacto@empresa.com">
                            </div>
                        </div>

                        <div class="form-row p-2 rounded bg-light border mb-3" id="seccionNuevoDueno">
                            <div class="form-group col-md-6 mb-1">
                                <label class="font-weight-bold small text-primary">Nombre del Gerente / Dueño</label>
                                <input type="text" class="form-control form-control-sm" name="nombreDueno" placeholder="Ej: Carlos">
                            </div>
                            <div class="form-group col-md-6 mb-1">
                                <label class="font-weight-bold small text-primary">Apellido del Gerente / Dueño</label>
                                <input type="text" class="form-control form-control-sm" name="apellidoDueno" placeholder="Ej: Mendoza">
                            </div>
                            <div class="col-12 mt-1">
                                <small class="text-info font-weight-bold">
                                    <i class="fas fa-magic mr-1"></i>Se generará su usuario con el dominio propio de la empresa (ej: <code>carlos.mendoza@empresa.com</code>) y contraseña temporal (123456).
                                </small>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold small text-gray-700">Teléfono</label>
                                <input type="text" class="form-control" name="telefono" placeholder="+503 2440-1234">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold small text-gray-700">WhatsApp de Atención</label>
                                <input type="text" class="form-control" name="whatsapp" placeholder="+503 7000-0000">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold small text-gray-700">Dirección Física</label>
                            <input type="text" class="form-control" name="direccion" placeholder="Dirección completa del comercio en El Salvador">
                        </div>

                        <hr>
                        <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-credit-card mr-2"></i>Credenciales Wompi El Salvador (Opcional)</h6>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold small text-gray-700">ID de Aplicación (App ID)</label>
                                <input type="text" class="form-control" name="wompiAppId" placeholder="Ej: 5b4d7c8e-xxxx-xxxx-xxxx-xxxxxxxxxxxx">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold small text-gray-700">Clave Secreta API (API Key)</label>
                                <input type="password" class="form-control" name="wompiApiKey" placeholder="••••••••••••••••••••••••••••••••">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 form-check pl-4">
                                <input type="checkbox" class="form-check-input" id="wompiActivoModal" name="wompiActivo" checked>
                                <label class="form-check-label font-weight-bold small text-gray-700" for="wompiActivoModal">Habilitar Wompi para esta empresa</label>
                            </div>
                            <div class="form-group col-md-6 form-check pl-4">
                                <input type="checkbox" class="form-check-input" id="activoModal" name="activo" checked>
                                <label class="form-check-label font-weight-bold small text-gray-700" for="activoModal">Empresa activa en la plataforma</label>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-save mr-1"></i>Registrar Empresa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Scripts -->
    <script src="../controllers/vendor/jquery/jquery.min.js"></script>
    <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
    <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="../controllers/js/sb-admin.min.js"></script>
    <script src="../controllers/js/translations.js"></script>
    <script src="../controllers/js/demo/datatables-demo.js"></script>

    <script>
        var baseUrl = "<?php echo obtenerUrlBase(''); ?>";

        // Filtros directos de la tabla
        $(document).ready(function() {
            var tabla = $('#dataTable').DataTable();

            $('.filtro-btn').on('click', function() {
                $('.filtro-btn').removeClass('active btn-primary btn-success btn-danger').addClass('btn-outline-light');
                $(this).removeClass('btn-outline-light').addClass('active');

                var filtro = $(this).data('filtro');

                if (filtro === 'todas') {
                    $(this).addClass('btn-primary');
                    tabla.column(5).search('').draw();
                } else if (filtro === 'activa') {
                    $(this).addClass('btn-success');
                    tabla.column(5).search('Activa').draw();
                } else if (filtro === 'inactiva') {
                    $(this).addClass('btn-danger');
                    tabla.column(5).search('Inactiva').draw();
                }
            });
        });

        function abrirModalEditar(empresa) {
            if (!empresa) return;

            // Encabezado del modal
            var inicial = (empresa.nombreEmpresa || 'E').charAt(0).toUpperCase();
            $('#modalEditAvatar').text(inicial);
            $('#modalEditTitulo').text(empresa.nombreEmpresa || 'Empresa');
            $('#modalEditSubtitulo').html('ID de Registro: #' + empresa.idEmpresa + ' &bull; Dueño: <strong>' + (empresa.usuarioDueno || 'Sin asignar') + '</strong>');

            // Badges
            var estaActiva = (parseInt(empresa.activo) === 1);
            if (estaActiva) {
                $('#badgeEstadoEmpresa').removeClass('badge-danger').addClass('badge-success').text('Activa');
            } else {
                $('#badgeEstadoEmpresa').removeClass('badge-success').addClass('badge-danger').text('Inactiva');
            }

            var wompiActivo = (parseInt(empresa.wompiActivo) === 1 && empresa.wompiAppId && empresa.wompiApiKey);
            if (wompiActivo) {
                $('#badgeEstadoWompi').removeClass('badge-secondary').addClass('badge-primary').text('Wompi: Activo');
            } else {
                $('#badgeEstadoWompi').removeClass('badge-primary').addClass('badge-secondary').text('Wompi: Inactivo');
            }

            // Formulario Perfil Comercial
            $('#edit_idEmpresa').val(empresa.idEmpresa);
            $('#edit_nombreEmpresa').val(empresa.nombreEmpresa || '');
            $('#edit_slug').val(empresa.slug || '');
            $('#edit_idUsuarioDueno').val(empresa.idUsuarioDueno || '1');
            $('#edit_direccion').val(empresa.direccion || '');
            $('#edit_telefono').val(empresa.telefono || '');
            $('#edit_whatsapp').val(empresa.whatsapp || '');
            $('#edit_correo').val(empresa.correo || '');
            $('#edit_activo').prop('checked', estaActiva);

            // Formulario Wompi
            $('#wompi_idEmpresa').val(empresa.idEmpresa);
            $('#wompi_appId').val(empresa.wompiAppId || '');
            $('#wompi_apiKey').val(empresa.wompiApiKey || '');
            $('#wompi_activo').prop('checked', parseInt(empresa.wompiActivo) === 1);

            // URL pública
            var urlTienda = baseUrl + '?tienda=' + encodeURIComponent(empresa.slug || '');
            $('#modal_urlTiendaInput').val(urlTienda);
            $('#modal_btnAbrirTienda').attr('href', urlTienda);

            // Mostrar el modal
            $('#modalEditarEmpresa').modal('show');
        }

        function copiarUrlModal() {
            var copyText = document.getElementById("modal_urlTiendaInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            alert("¡Enlace copiado al portapapeles!:\n" + copyText.value);
        }

        function toggleNuevoDuenoInputs() {
            var val = $('#nueva_idUsuarioDueno').val();
            if (val === '0') {
                $('#seccionNuevoDueno').slideDown();
            } else {
                $('#seccionNuevoDueno').slideUp();
            }
        }

        // Auto-abrir modal si viene ?empresa=ID por GET o tras un POST
        <?php if (!empty($_GET['empresa']) && !empty($empresa)): ?>
            $(document).ready(function() {
                abrirModalEditar(<?php echo json_encode($empresa, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>);
            });
        <?php endif; ?>
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
