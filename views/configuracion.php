<?php 
$nombres = $nombres ?? '';
$nombres = is_array($nombres) ? '' : $nombres;

$currentPage = basename($_SERVER['PHP_SELF']);

$nav="<nav class='navbar navbar-expand navbar-dark bg-dark static-top'>

   <a class='navbar-brand mr-1' href='index.html'>Concentrados El gordito</a>
    <button class='btn btn-link btn-sm text-white order-1 order-sm-0' id='sidebarToggle' href='#'>
      <i class='fas fa-bars'></i>
    </button>


    <!-- Navbar -->
    
       <form class='d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0'>
      <div class='input-group'>
        <div class='input-group-append'>
           <button class='btn btn-success'> " . $nombres . "
          </button>
        </div>
      </div>
    </form>
    <ul class='navbar-nav ml-auto ml-md-0'>
      <div>
      <form><button class='btn btn-warning' id='c' name='c' value='c'>Cerrar session</button></form>
      </div>
    </ul>

  </nav>
  <style>
    html, body { height: 100%; }
    #wrapper { min-height: 100vh; }
    #content-wrapper {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    footer.sticky-footer {
      position: relative !important;
      margin-top: auto;
      width: 100% !important;
      height: auto !important;
      padding: 1rem 0;
    }
    .table-responsive {
      max-height: calc(100vh - 280px);
      overflow-y: auto;
    }
    .sidebar {
      height: 100vh;
      overflow-y: auto;
    }
  </style>
";

$menuItems = [
  ['href' => 'controllerDashboard.php', 'icon' => 'fa-chart-line', 'label' => 'Dashboard'],
  ['href' => 'controllerEmpleado.php', 'icon' => 'fa-user-tie', 'label' => 'Empleados'],
  ['href' => 'controllerCliente.php', 'icon' => 'fa-address-book', 'label' => 'Clientes'],
  ['href' => 'controllerUsuarios.php', 'icon' => 'fa-users-cog', 'label' => 'Usuarios'],
  ['href' => 'controllerProveedor.php', 'icon' => 'fa-building', 'label' => 'Proveedores'],
  ['href' => 'controllerPedidoProveedor.php', 'icon' => 'fa-shopping-cart', 'label' => 'Pedidos a Proveedor'],
  ['href' => 'controllerPedidos.php', 'icon' => 'fa-box-open', 'label' => 'Pedidos'],
  ['href' => 'controllerProduccion.php', 'icon' => 'fa-industry', 'label' => 'Produccion'],
  ['href' => 'controllerInventario.php', 'icon' => 'fa-warehouse', 'label' => 'Inventario'],
  ['href' => 'controllerMateriaPrima.php', 'icon' => 'fa-leaf', 'label' => 'Materia Prima'],
  ['href' => 'controllerPuesto.php', 'icon' => 'fa-briefcase', 'label' => 'Puesto'],
  ['href' => 'controllerFactura.php', 'icon' => 'fa-file-invoice-dollar', 'label' => 'Factura'],
  ['href' => 'controllerDetalleCompra.php', 'icon' => 'fa-shopping-bag', 'label' => 'Detalle Compra'],
  ['href' => 'controllerPlanPago.php', 'icon' => 'fa-credit-card', 'label' => 'Planes de Pago'],
  ['href' => 'controllerPagos.php', 'icon' => 'fa-money-bill-wave', 'label' => 'Pagos'],
  ['href' => 'controllerReportes.php', 'icon' => 'fa-chart-pie', 'label' => 'Reportes'],
];

$menu = "<div style='max-height: calc(100vh - 100px); overflow-y: auto; overflow-x: hidden;'>
       <ul class='sidebar navbar-nav'>";
foreach ($menuItems as $item) {
    $activeClass = ($item['href'] === $currentPage) ? 'active' : '';
    $menu .= "<li class='nav-item $activeClass'>
           <a class='nav-link' href='{$item['href']}'>
             <i class='fas fa-fw {$item['icon']}'></i>
             <span>{$item['label']}</span>
           </a>
         </li>";
}
$menu .= "      </ul>
     </div>";


?>