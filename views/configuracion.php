<?php 


$nav="<nav class='navbar navbar-expand navbar-dark bg-dark static-top'>

   <a class='navbar-brand mr-1' href='index.html'>Concentrados El gordito</a>
    <button class='btn btn-link btn-sm text-white order-1 order-sm-0' id='sidebarToggle' href='#'>
      <i class='fas fa-bars'></i>
    </button>


    <!-- Navbar -->
    
      <form class='d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0'>
      <div class='input-group'>
        <div class='input-group-append'>
          <button class='btn btn-success'> 
          $nombres
            </button>
            
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
";

$menu="<ul class='sidebar navbar-nav'>
      <li class='nav-item'>
        <a class='nav-link' href='controllerEmpleado.php'>
          <i class='fas fa-fw fa-tachometer-alt'></i>
          <span>Empleados</span>
        </a>
      </li>
     
      <li class='nav-item'>
        <a class='nav-link' href='controllerCliente.php'>
          <i class='fas fa-fw fa-chart-area'></i>
          <span>Clientes</span></a>
      </li>
      <li class='nav-item active'>
        <a class='nav-link' href='controllerUsuarios.php'>
          <i class='fas fa-fw fa-table'></i>
          <span>Usuarios</span></a>
      </li>
      <li class='nav-item active'>
        <a class='nav-link' href='controllerProveedor.php'>
          <i class='fas fa-fw fa-table'></i>
          <span>Proveedores</span></a>
      </li>
      <li class='nav-item'>
        <a class='nav-link' href='controllerPedidos.php'>
          <i class='fas fa-fw fa-tachometer-alt'></i>
          <span>Pedidos</span>
        </a>
      </li>
      <li class='nav-item'>
        <a class='nav-link' href='controllerProduccion.php'>
          <i class='fas fa-fw fa-tachometer-alt'></i>
          <span>Produccion</span>
        </a>
      </li>
    </ul>
";





 ?>