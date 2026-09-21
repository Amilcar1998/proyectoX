<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/PermisoModel.php';

$permisoModel = new PermisoModel();

$currentPage = basename($_SERVER['SCRIPT_NAME'] ?? ($_SERVER['PHP_SELF'] ?? ''));

$idRol = (int)($_SESSION['id_Rol'] ?? 0);
if ($idRol === 0) {
    if (isset($_SESSION['s1'])) {
        $idRol = 1;
    } elseif (isset($_SESSION['s2'])) {
        $idRol = 2;
    } elseif (isset($_SESSION['c1'])) {
        $idRol = 3;
    }
}

$idUsuarioSesion = (int)($_SESSION['idUsuario'] ?? 0);
$usuarioSesion = (string)($_SESSION['s1'] ?? ($_SESSION['s2'] ?? ($_SESSION['c1'] ?? '')));

$nombres = $nombres ?? '';
$nombres = is_array($nombres) ? '' : $nombres;
if (empty($nombres)) {
    $nombres = $permisoModel->obtenerNombreUsuario($idUsuarioSesion, $usuarioSesion);
    if (empty($nombres)) {
        $nombres = $usuarioSesion ?: 'Usuario';
    }
}
$nombres = html_entity_decode((string)$nombres, ENT_QUOTES | ENT_HTML5, 'UTF-8');
$nombres = str_replace(["\xc2\xa0", '&nbsp;'], ' ', $nombres);
$nombres = preg_replace('/\s+/', ' ', trim($nombres));

$brandHome = $permisoModel->obtenerRutaHome($idRol);
$modulosPermitidos = $permisoModel->obtenerModulosPorRol($idRol, $idUsuarioSesion);
$rolNombre = $permisoModel->obtenerNombreRol($idRol);

// Validación de suscripción para empresas / inquilinos (Multi-Tenant)
require_once __DIR__ . '/../models/EmpresaModel.php';
$empresaModelConf = new EmpresaModel();
$idEmpresaSesion = (int)($_SESSION['idEmpresa'] ?? 1);
$esSuperUsuario = !empty($_SESSION['esSuperUsuario']) || ($usuarioSesion === 'amilcar199819@gmail.com');

$esClienteDirecto = ($idRol === 3) || isset($_SESSION['c1']);

$infoSuscripcionConf = $empresaModelConf->verificarSuscripcionEmpresa($idEmpresaSesion);
$suscripcionInactiva = (!$esSuperUsuario && !$esClienteDirecto && $idEmpresaSesion > 1 && empty($infoSuscripcionConf['activa']));

if ($suscripcionInactiva) {
    // Si la empresa no tiene suscripción activa, ocultar todos los módulos operativos a gerentes/empleados de ese tenant
    $modulosPermitidos = array_values(array_filter($modulosPermitidos, function($m) {
        $ctrl = basename(explode('?', (string)($m['controlador'] ?? ''))[0]);
        return in_array($ctrl, ['controllerDashboard.php', 'controllerPlanPago.php', 'controllerPagos.php'], true);
    }));
}

$badgeRolClass = 'badge-primary';
if ($idRol === 1) {
    $badgeRolClass = 'badge-primary';
} elseif ($idRol === 2) {
    $badgeRolClass = 'badge-info';
} elseif ($idRol === 3) {
    $badgeRolClass = 'badge-success';
} elseif ($idRol === 4) {
    $badgeRolClass = 'badge-danger';
}

require_once __DIR__ . '/../models/ServicioCorreo.php';
$servicioCorreoCheck = new ServicioCorreo();
$estadoLimiteCorreo = $servicioCorreoCheck->obtenerEstadoLimite();
$alertaCorreoHtml = '';

// Notificación visible exclusivamente para Superadministrador (Rol 4) y Gerente (Rol 1)
if (($idRol === 1 || $idRol === 4) && !empty($estadoLimiteCorreo['alerta'])) {
    $enviadosCount = number_format($estadoLimiteCorreo['enviados']);
    $limiteCount = number_format($estadoLimiteCorreo['limite']);
    $restantesCount = number_format($estadoLimiteCorreo['restantes']);

    if (!empty($estadoLimiteCorreo['finalizada'])) {
        $alertaCorreoHtml = "<div class='bg-danger text-white py-2 px-3 text-center font-weight-bold d-flex align-items-center justify-content-center' style='font-size: 0.88rem; box-shadow: 0 2px 6px rgba(0,0,0,0.15); z-index: 1050;'>
          <i class='fas fa-ban mr-2 text-warning fa-lg'></i>
          <span><strong>⚠️ Alerta Crítica (Superadministración):</strong> La cuota mensual de correos de Resend ha <u>FINALIZADO</u> ({$enviadosCount}/{$limiteCount} enviados. <strong>0 correos restantes</strong>). Los envíos de comprobantes y recuperación de contraseña estarán deshabilitados hasta la renovación de la cuota.</span>
        </div>";
    } else {
        $alertaCorreoHtml = "<div class='bg-warning text-dark py-2 px-3 text-center font-weight-bold d-flex align-items-center justify-content-center' style='font-size: 0.86rem; box-shadow: 0 2px 6px rgba(0,0,0,0.1); z-index: 1050;'>
          <i class='fas fa-exclamation-triangle mr-2 text-danger fa-lg'></i>
          <span><strong>Aviso de Cuota de Correos (Resend):</strong> El servicio de mensajería está próximo a su límite mensual ({$enviadosCount}/{$limiteCount} enviados. Restan solo <strong>{$restantesCount}</strong> correos disponibles).</span>
        </div>";
    }
}

$nav = "<nav class='navbar navbar-expand navbar-dark bg-dark static-top'>

   <a class='navbar-brand mr-1' href='{$brandHome}'><i class='fas fa-seedling text-success mr-2'></i>Concentrados El Gordito</a>
    <button class='btn btn-link btn-sm text-white order-1 order-sm-0' id='sidebarToggle' href='#'>
      <i class='fas fa-bars'></i>
    </button>

    <!-- Navbar -->
    
    <div class='d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0'>
      <div class='text-white d-flex align-items-center'>
        <span class='badge {$badgeRolClass} text-uppercase px-2 py-1 mr-2' style='font-size: 0.75rem;'><i class='fas fa-user-shield mr-1'></i>{$rolNombre}</span>
        <span class='font-weight-bold text-light mr-3'><i class='fas fa-user-circle mr-1 text-info'></i>{$nombres}</span>
      </div>
    </div>
    <ul class='navbar-nav ml-auto ml-md-0'>
      <div>
      <a href='sesiones.php?c=c' class='btn btn-outline-warning btn-sm' id='c'><i class='fas fa-sign-out-alt mr-1'></i>Cerrar sesión</a>
      </div>
    </ul>

  </nav>
  {$alertaCorreoHtml}
  <link href='../views/css/layout.css' rel='stylesheet'>
";

$menu = "<ul class='sidebar navbar-nav' style='padding-bottom: 2.5rem;'>";

if ($suscripcionInactiva) {
    $menu .= "<li class='nav-item'>
        <div class='alert alert-danger mx-2 my-2 py-2 px-2 small font-weight-bold text-center shadow-sm' style='border-radius: 8px; font-size: 0.78rem;'>
            <i class='fas fa-lock mr-1'></i>Suscripción Inactiva<br>
            <span class='font-weight-normal' style='font-size:0.72rem;'>Módulos restringidos</span>
            <a href='controllerPlanPago.php' class='btn btn-danger btn-sm btn-block mt-2 font-weight-bold text-white shadow-sm' style='font-size: 0.75rem; border-radius: 6px;'>
                <i class='fas fa-shopping-cart mr-1'></i>Renovar Plan
            </a>
        </div>
    </li>";
}

foreach ($modulosPermitidos as $item) {
    $submodulos = $item['submodulos'] ?? [];
    $tieneSubmodulos = !empty($submodulos);
    $icono = !empty($item['icono']) ? htmlspecialchars($item['icono']) : 'fa-folder';
    $nombre = htmlspecialchars($item['nombre']);
    $controlador = htmlspecialchars($item['controlador']);
    $idMod = (int)$item['idModulo'];
    
    // Comprobar si la página actual coincide con el módulo o alguno de sus submódulos
    $isParentActive = ($item['controlador'] === $currentPage);
    $isChildActive = false;
    foreach ($submodulos as $sub) {
        $subScript = basename(explode('?', $sub['controlador_accion'])[0]);
        if ($subScript === $currentPage) {
            $isChildActive = true;
            break;
        }
    }
    
    $activeClass = ($isParentActive || $isChildActive) ? 'active' : '';

    if ($tieneSubmodulos) {
        $collapseId = "collapseMod_" . $idMod;
        $showClass = ($isParentActive || $isChildActive) ? 'show' : '';
        $expanded = ($isParentActive || $isChildActive) ? 'true' : 'false';
        $collapsedClass = ($isParentActive || $isChildActive) ? '' : 'collapsed';

        $menu .= "<li class='nav-item {$activeClass}'>
           <a class='nav-link {$collapsedClass} d-flex align-items-center justify-content-between' href='#{$collapseId}' data-toggle='collapse' aria-expanded='{$expanded}' aria-controls='{$collapseId}' style='cursor: pointer;'>
             <div>
               <i class='fas fa-fw {$icono} mr-1'></i>
               <span>{$nombre}</span>
             </div>
             <i class='fas fa-chevron-down ml-auto' style='font-size: 0.75rem; transition: transform 0.2s;'></i>
           </a>
           <div id='{$collapseId}' class='collapse {$showClass}' style='background-color: #1a1e21;'>
             <div class='py-1'>";
        
        foreach ($submodulos as $sub) {
            $subUrl = htmlspecialchars($sub['controlador_accion']);
            $subNombre = htmlspecialchars($sub['nombre']);
            $subIcono = !empty($sub['icono']) ? htmlspecialchars($sub['icono']) : 'fa-circle-notch';
            $subScript = basename(explode('?', $sub['controlador_accion'])[0]);
            $isThisSubActive = ($subScript === $currentPage);
            $subClass = $isThisSubActive ? 'text-white font-weight-bold bg-dark' : 'text-light';

            $menu .= "<a class='nav-link py-1 pl-4 d-flex align-items-center {$subClass}' href='{$subUrl}' style='font-size: 0.85rem;'>
               <i class='fas fa-fw {$subIcono} mr-2' style='font-size: 0.75rem; opacity: 0.8;'></i>
               <span>{$subNombre}</span>
             </a>";
        }

        $menu .= "  </div>
           </div>
         </li>";
    } else {
        $menu .= "<li class='nav-item {$activeClass}'>
           <a class='nav-link' href='{$controlador}'>
             <i class='fas fa-fw {$icono}'></i>
             <span>{$nombre}</span>
           </a>
         </li>";
    }
}
$menu .= "</ul>";




?>