<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/PermisoModel.php';

$permisoModel = new PermisoModel();

$nombres = $nombres ?? '';
$nombres = is_array($nombres) ? '' : $nombres;
if (empty($nombres)) {
    $nombres = $permisoModel->obtenerNombreUsuario();
    if (empty($nombres)) {
        $nombres = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? ($_SESSION['c1'] ?? 'Usuario'));
    }
}
$nombres = html_entity_decode((string)$nombres, ENT_QUOTES | ENT_HTML5, 'UTF-8');
$nombres = str_replace(["\xc2\xa0", '&nbsp;'], ' ', $nombres);
$nombres = preg_replace('/\s+/', ' ', trim($nombres));

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
$brandHome = $permisoModel->obtenerRutaHome($idRol);
$modulosPermitidos = $permisoModel->obtenerModulosPorRol($idRol, $idUsuarioSesion);
$rolNombre = $permisoModel->obtenerNombreRol($idRol);

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

$nav = "<nav class='navbar navbar-expand navbar-dark bg-dark static-top'>

   <a class='navbar-brand mr-1' href='{$brandHome}'><i class='fas fa-seedling text-success mr-2'></i>Concentrados El Gordito</a>
    <button class='btn btn-link btn-sm text-white order-1 order-sm-0' id='sidebarToggle' href='#'>
      <i class='fas fa-bars'></i>
    </button>

    <!-- Navbar -->
    
    <div class='d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0'>
      <div class='text-white d-flex align-items-center'>
        <span class='badge {$badgeRolClass} text-uppercase px-2 py-1 mr-2' style='font-size: 0.75rem;'><i class='fas fa-user-shield mr-1'></i>" . htmlspecialchars($rolNombre) . "</span>
        <span class='font-weight-bold text-light mr-3'><i class='fas fa-user-circle mr-1 text-info'></i>" . htmlspecialchars((string)$nombres) . "</span>
      </div>
    </div>
    <ul class='navbar-nav ml-auto ml-md-0'>
      <div>
      <a href='sesiones.php?c=c' class='btn btn-outline-warning btn-sm' id='c'><i class='fas fa-sign-out-alt mr-1'></i>Cerrar sesión</a>
      </div>
    </ul>

  </nav>
  <style>
    html {
      min-height: 100%;
    }
    body {
      min-height: 100vh;
      margin: 0;
      padding: 0;
      background-color: #f1f5f9;
      display: flex;
      flex-direction: column;
    }
    #wrapper {
      display: flex;
      flex: 1 0 auto;
      width: 100%;
      align-items: stretch;
    }
    .sidebar {
      width: 235px !important;
      min-width: 235px !important;
      background-color: #111827 !important;
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      position: sticky !important;
      top: 0;
      height: 100vh !important;
      max-height: 100vh !important;
      overflow-y: auto !important;
      overflow-x: hidden !important;
      scrollbar-width: thin;
      scrollbar-color: #64748b #1e293b;
    }
    .sidebar::-webkit-scrollbar {
      width: 6px;
    }
    .sidebar::-webkit-scrollbar-track {
      background: #1e293b;
      border-radius: 4px;
    }
    .sidebar::-webkit-scrollbar-thumb {
      background: #64748b;
      border-radius: 4px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
    #content-wrapper {
      flex: 1 1 auto;
      display: flex;
      flex-direction: column;
      width: calc(100% - 235px);
      min-width: 0;
      background-color: #f1f5f9;
    }
    .container-fluid {
      flex: 1 0 auto;
      width: 100%;
    }
    .table-responsive {
      width: 100% !important;
      overflow-x: auto;
      overflow-y: visible;
      -webkit-overflow-scrolling: touch;
    }
    footer.sticky-footer {
      position: static !important;
      width: 100% !important;
      background-color: #111827 !important;
      color: #94a3b8 !important;
      border-top: 1px solid #1f2937 !important;
      margin-top: auto;
      padding: 1.1rem 0 !important;
      flex-shrink: 0;
    }
    footer.sticky-footer .copyright, footer.sticky-footer span {
      color: #cbd5e1 !important;
      font-size: 0.86rem;
      font-weight: 500;
    }
  </style>
";

$menu = "<ul class='sidebar navbar-nav' style='padding-bottom: 2.5rem;'>";

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