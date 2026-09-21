<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../models/PermisoModel.php';

$idRol = (int)($_SESSION['id_Rol'] ?? 3);
$permisoModel = new PermisoModel();
$modulosPermitidos = $permisoModel->obtenerModulosPorRol($idRol);
$brandHome = $permisoModel->obtenerRutaHome($idRol);
$currentPage = basename($_SERVER['SCRIPT_NAME'] ?? ($_SERVER['PHP_SELF'] ?? ''));

$idUsuarioSesion = (int)($_SESSION['idUsuario'] ?? 0);
$usuarioSesion = (string)($_SESSION['c1'] ?? ($_SESSION['s2'] ?? ($_SESSION['s1'] ?? '')));

$nombres = $nombres ?? '';
$nombres = is_array($nombres) ? '' : $nombres;
if (empty($nombres)) {
    $nombres = $permisoModel->obtenerNombreUsuario($idUsuarioSesion, $usuarioSesion);
    if (empty($nombres)) {
        $nombres = $usuarioSesion ?: 'Cliente';
    }
}
$nombres = html_entity_decode((string)$nombres, ENT_QUOTES | ENT_HTML5, 'UTF-8');
$nombres = str_replace(["\xc2\xa0", '&nbsp;'], ' ', $nombres);
$nombres = preg_replace('/\s+/', ' ', trim($nombres));

$sidebarHtml = "";
foreach ($modulosPermitidos as $item) {
    $activeClass = ($item['controlador'] === $currentPage) ? 'active' : '';
    $icono = !empty($item['icono']) ? htmlspecialchars($item['icono']) : 'fa-folder';
    $nombre = htmlspecialchars($item['nombre']);
    $controlador = htmlspecialchars($item['controlador']);
    $sidebarHtml .= "<li class='nav-item $activeClass'>
           <a class='nav-link' href='{$controlador}'>
             <i class='fas fa-fw {$icono}'></i>
             <span>{$nombre}</span>
           </a>
         </li>";
}

$cli="<!DOCTYPE html>
<html lang='es'>

<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
  <title>Portal Cliente - Concentrados El Gordito</title>

  <!-- Custom fonts for this template-->
  <link href='../vendor/fontawesome-free/css/all.min.css' rel='stylesheet' type='text/css'>
  <link href='../vendor/datatables/dataTables.bootstrap4.css' rel='stylesheet'>
  <link href='../vendor/sb-admin.css' rel='stylesheet'>
  <link href='../views/css/layout.css' rel='stylesheet'>
</head>

<body id='page-top'>

<nav class='navbar navbar-expand navbar-dark bg-dark static-top'>

    <a class='navbar-brand mr-1' href='{$brandHome}'><i class='fas fa-seedling text-success mr-2'></i>Concentrados El Gordito</a>

    <button class='btn btn-link btn-sm text-white order-1 order-sm-0' id='sidebarToggle' href='#'>
      <i class='fas fa-bars'></i>
    </button>

    <!-- Navbar Search -->
    <div class='d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0'></div>

    <!-- Navbar -->
    <ul class='navbar-nav ml-auto ml-md-0 align-items-center'>
      <li class='mr-3'>
        <span class='badge badge-success px-3 py-2' style='font-size: 13px;'><i class='fas fa-user-circle mr-1'></i> $nombres</span>
      </li>
      <li class='nav-item dropdown no-arrow'>
        <a href='sesiones.php?c=c' class='btn btn-warning btn-sm font-weight-bold'><i class='fas fa-sign-out-alt mr-1'></i>Cerrar sesión</a>
      </li>
    </ul>

  </nav>

  <div id='wrapper'>

    <!-- Sidebar Dinámica RBAC -->
    <ul class='sidebar navbar-nav'>
      {$sidebarHtml}
    </ul>

    <div id='content-wrapper'>
";
?>