<?php
include 'configuracion/configuracionCliente.php';
echo $cli;
?>

<div class="container-fluid py-4">

  <!-- ENCABEZADO DEL PORTAL -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-2 border-bottom">
    <div class="mb-3 mb-md-0">
      <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
        <i class="fas fa-store text-primary mr-2"></i>Portal de Clientes
      </h1>
      <p class="text-muted mb-0">Bienvenido, <strong><?php echo htmlspecialchars($nombres); ?></strong>. Explora nuestro catálogo de productos, gestiona tus pedidos y revisa tus pagos.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
      <a href="#seccionProductos" class="btn btn-outline-primary shadow-sm font-weight-bold mr-2 mb-1">
        <i class="fas fa-box-open mr-1"></i>Ver Catálogo
      </a>
      <form method="POST" action="controllerIndividualC.php" class="d-inline">
        <button type="submit" name="crear_pedido" class="btn btn-primary shadow-sm font-weight-bold px-3 py-2 mb-1">
          <i class="fas fa-cart-plus mr-1"></i>Nuevo Pedido
        </button>
      </form>
    </div>
  </div>

  <!-- SECCIÓN DE SUSCRIPCIÓN COMERCIAL -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card shadow border-left-info">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-lg-8">
              <div class="d-flex align-items-center mb-2">
                <div class="icon-circle bg-info text-white mr-3 shadow-sm" style="width: 52px; height: 52px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                  <i class="fas fa-crown"></i>
                </div>
                <div>
                  <h5 class="mb-0 font-weight-bold text-gray-900">
                    <?php if (!empty($suscripcion)): ?>
                      <?php echo htmlspecialchars($suscripcion['nombrePlan'] ?? 'Plan Comercial'); ?>
                    <?php else: ?>
                      Sin Suscripción Comercial Activa
                    <?php endif; ?>
                  </h5>
                  <div class="small text-muted">
                    <?php if (!empty($suscripcion)): ?>
                      <?php echo htmlspecialchars($suscripcion['descripcionPlan'] ?? 'Plan activo vinculado a tu cuenta'); ?>
                    <?php else: ?>
                      Actualmente no posees un plan comercial activo para tu cuenta.
                    <?php endif; ?>
                  </div>
                </div>
              </div>

              <?php if (!empty($suscripcion)): ?>
                <div class="mt-3 d-flex flex-wrap">
                  <div class="mr-4 mb-2">
                    <span class="text-xs text-uppercase font-weight-bold text-muted d-block">Estado</span>
                    <?php if (($suscripcion['estado'] ?? '') === 'activo' && empty($suscripcion['esta_vencida'])): ?>
                      <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>Suscripción Activa</span>
                    <?php else: ?>
                      <span class="badge badge-warning px-2 py-1"><i class="fas fa-exclamation-triangle mr-1"></i>Vencida / Inactiva</span>
                    <?php endif; ?>
                  </div>

                  <div class="mr-4 mb-2">
                    <span class="text-xs text-uppercase font-weight-bold text-muted d-block">Fecha Inicio</span>
                    <span class="font-weight-bold text-gray-800">
                      <?php echo !empty($suscripcion['fecha_inicio']) ? date('d/m/Y', strtotime($suscripcion['fecha_inicio'])) : 'N/D'; ?>
                    </span>
                  </div>

                  <div class="mr-4 mb-2">
                    <span class="text-xs text-uppercase font-weight-bold text-muted d-block">Fecha Vencimiento</span>
                    <span class="font-weight-bold text-gray-800">
                      <?php echo !empty($suscripcion['fecha_fin']) ? date('d/m/Y', strtotime($suscripcion['fecha_fin'])) : 'N/D'; ?>
                    </span>
                  </div>

                  <div class="mb-2">
                    <span class="text-xs text-uppercase font-weight-bold text-muted d-block">Días Restantes</span>
                    <span class="font-weight-bold <?php echo (($suscripcion['dias_restantes'] ?? 0) <= 5) ? 'text-danger' : 'text-success'; ?>">
                      <?php echo htmlspecialchars((string)($suscripcion['dias_restantes'] ?? 0)); ?> días
                    </span>
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
              <a href="../#planes" target="_blank" class="btn btn-outline-info btn-sm shadow-sm font-weight-bold mr-2 mb-1">
                <i class="fas fa-list-ul mr-1"></i>Ver Planes
              </a>
              <a href="../#planes" target="_blank" class="btn btn-info btn-sm shadow-sm font-weight-bold mb-1">
                <i class="fas fa-sync-alt mr-1"></i>Renovar / Mejorar Plan
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- NAVEGACIÓN DE SECCIONES (TABS) -->
  <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active font-weight-bold" id="tab-productos" data-toggle="pill" href="#pills-productos" role="tab">
        <i class="fas fa-boxes mr-1"></i>Catálogo de Productos
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link font-weight-bold" id="tab-pedidos" data-toggle="pill" href="#pills-pedidos" role="tab">
        <i class="fas fa-clipboard-list mr-1"></i>Mis Pedidos (<?php echo count($dataPedido); ?>)
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link font-weight-bold" id="tab-pagos" data-toggle="pill" href="#pills-pagos" role="tab">
        <i class="fas fa-receipt mr-1"></i>Mis Pagos (<?php echo count($misPagos); ?>)
      </a>
    </li>
  </ul>

  <div class="tab-content" id="pills-tabContent">

    <!-- ==================================================== -->
    <!-- TAB 1: CATÁLOGO DE PRODUCTOS Y MEZCLAS              -->
    <!-- ==================================================== -->
    <div class="tab-pane fade show active" id="pills-productos" role="tabpanel" aria-labelledby="tab-productos">
      
      <?php if (!empty($suscripcionEmpresaCaducada)): ?>
        <!-- BLOQUEO POR SUSCRIPCIÓN CADUCADA -->
        <div class="card border-0 shadow-lg text-center p-5 mb-4" style="border-radius: 20px; background: linear-gradient(135deg, #ffffff 0%, #fff1f2 100%); border: 2px solid #fecdd3 !important;">
          <div class="card-body">
            <div class="mb-3">
              <span class="d-inline-flex align-items-center justify-content-center bg-danger text-white rounded-circle shadow" style="width: 80px; height: 80px; font-size: 36px;">
                <i class="fas fa-lock"></i>
              </span>
            </div>
            <h3 class="font-weight-bold text-danger mb-2">¡Suscripción Comercial Caducada!</h3>
            <p class="text-muted lead mx-auto mb-4" style="max-width: 650px; font-size: 1.05rem;">
              La empresa no cuenta con un paquete de suscripción activo o su plan ha vencido recientemente.
              <br>
              Por esta razón, la visualización de productos, precios y la creación de nuevos pedidos se encuentra temporalmente suspendida.
            </p>
            <div class="d-flex justify-content-center flex-wrap gap-2">
              <a href="../#planes" target="_blank" class="btn btn-danger btn-lg font-weight-bold px-4 py-3 shadow mr-3 mb-2" style="border-radius: 30px;">
                <i class="fas fa-shopping-cart mr-2"></i>Comprar / Renovar Paquete
              </a>
              <a href="controllerPagos.php" class="btn btn-outline-danger btn-lg font-weight-bold px-4 py-3 shadow-sm mb-2" style="border-radius: 30px;">
                <i class="fas fa-credit-card mr-2"></i>Consultar Pagos
              </a>
            </div>
          </div>
        </div>
      <?php else: ?>

      <!-- Banner informativo / Pedido Activo -->
      <?php if (!empty($idPedidoActual)): ?>
        <div class="alert alert-success d-flex flex-wrap align-items-center justify-content-between mb-4 shadow-sm">
          <div>
            <i class="fas fa-shopping-cart fa-lg mr-2"></i>
            <strong>Pedido #<?php echo $idPedidoActual; ?> en curso</strong> — Los productos que elijas se agregarán directamente a este pedido.
          </div>
          <div class="mt-2 mt-sm-0">
            <a href="#pills-pedidos" onclick="$('#tab-pedidos').tab('show');" class="btn btn-success btn-sm font-weight-bold shadow-sm">
              <i class="fas fa-eye mr-1"></i>Ver Detalle del Pedido #<?php echo $idPedidoActual; ?>
            </a>
          </div>
        </div>
      <?php endif; ?>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h5 class="font-weight-bold text-gray-800 mb-0">
            <i class="fas fa-cubes text-primary mr-1"></i>Mezclas y Alimentos Concentrados Disponibles
          </h5>
          <span class="text-muted small">Selecciona la cantidad y agrega directamente a tu pedido</span>
        </div>
        <div>
          <span class="badge badge-primary font-weight-bold px-3 py-2" style="font-size: 13px;">
            <?php echo count($receta); ?> Productos
          </span>
        </div>
      </div>

      <!-- GRID DE TARJETAS DE PRODUCTOS -->
      <div class="row" id="seccionProductos">
        <?php foreach ($receta as $prod): 
          $nom = strtolower($prod['nombreReceta'] ?? '');
          $iconCard = !empty($prod['iconoRubro']) ? str_replace('fas ', '', $prod['iconoRubro']) : 'fa-seedling';
          $colorTheme = 'primary';
          $catEtiqueta = !empty($prod['categoriaRubro']) && $prod['categoriaRubro'] !== 'General' ? $prod['categoriaRubro'] : ($prod['nombreRubro'] ?? 'Calidad Premium');

          if (strpos($nom, 'pollo') !== false || strpos($nom, 'ave') !== false) {
            $iconCard = 'fa-feather-alt';
            $colorTheme = 'warning';
          } elseif (strpos($nom, 'cerdo') !== false) {
            $iconCard = 'fa-piggy-bank';
            $colorTheme = 'danger';
          } elseif (strpos($nom, 'ganado') !== false) {
            $iconCard = 'fa-horse-head';
            $colorTheme = 'success';
          } elseif (strpos($nom, 'balancead') !== false) {
            $iconCard = 'fa-balance-scale';
            $colorTheme = 'info';
          }

          $esPromo = !empty($prod['en_promocion']);
          $precioActual = (float)($prod['PrecioUnitario'] ?? 0);
          $precioAnterior = (float)($prod['precio_anterior'] ?? 0);
          $descuento = (int)($prod['porcentaje_descuento'] ?? 0);
        ?>
          <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-4">
            <div class="card h-100 shadow-sm border-top-<?php echo $colorTheme; ?>" style="border-top-width: 4px !important; transition: transform 0.2s, box-shadow 0.2s;">
              <div class="card-body d-flex flex-column">
                
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div class="bg-light p-3 rounded-circle text-<?php echo $colorTheme; ?> shadow-sm" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas <?php echo $iconCard; ?>"></i>
                  </div>
                  <?php if ($esPromo && $descuento > 0): ?>
                    <span class="badge badge-danger px-2 py-1 shadow-sm font-weight-bold">
                      <i class="fas fa-tag mr-1"></i>-<?php echo $descuento; ?>% OFF
                    </span>
                  <?php else: ?>
                    <span class="badge badge-light border text-muted px-2 py-1 font-weight-bold">
                      <?php echo htmlspecialchars($catEtiqueta); ?>
                    </span>
                  <?php endif; ?>
                </div>

                <h5 class="card-title font-weight-bold text-gray-900 mb-1" style="font-size: 16px;">
                  <?php echo htmlspecialchars($prod['nombreReceta']); ?>
                </h5>
                <p class="text-muted small mb-3">Concentrado formulado con nutrientes de alta digestibilidad.</p>

                <div class="mt-auto pt-2 border-top">
                  <div class="d-flex align-items-baseline mb-3">
                    <span class="h4 font-weight-bold text-success mb-0">
                      $<?php echo number_format($precioActual, 2); ?>
                    </span>
                    <span class="text-muted small ml-1">/ saco o unidad</span>

                    <?php if ($esPromo && $precioAnterior > $precioActual): ?>
                      <span class="small text-muted ml-auto" style="text-decoration: line-through;">
                        $<?php echo number_format($precioAnterior, 2); ?>
                      </span>
                    <?php endif; ?>
                  </div>

                  <!-- FORMULARIO DIRECTO PARA AGREGAR A PEDIDO -->
                  <form method="POST" action="controllerIndividualC.php" class="form-row align-items-center">
                    <input type="hidden" name="id_receta" value="<?php echo $prod['idReceta']; ?>">
                    <input type="hidden" name="id_pedido" value="<?php echo htmlspecialchars((string)($idPedidoActual ?? 0)); ?>">
                    
                    <div class="col-4">
                      <input type="number" name="cantidad" class="form-control form-control-sm text-center font-weight-bold" min="1" value="1" title="Cantidad">
                    </div>
                    <div class="col-8">
                      <button type="submit" name="agregar_desde_catalogo" class="btn btn-primary btn-sm btn-block font-weight-bold shadow-sm">
                        <i class="fas fa-cart-plus mr-1"></i>Agregar
                      </button>
                    </div>
                  </form>

                  <div class="text-center mt-2">
                    <a href="controllerIndividualC.php?ver_receta=<?php echo $prod['idReceta']; ?>&id_pedido=<?php echo htmlspecialchars((string)($idPedidoActual ?? 0)); ?>" class="small text-muted font-weight-bold">
                      <i class="fas fa-flask mr-1"></i>Ver Composición de Receta
                    </a>
                  </div>

                </div>

              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

    </div>

    <!-- ==================================================== -->
    <!-- TAB 2: GESTIÓN DE PEDIDOS Y DETALLE                 -->
    <!-- ==================================================== -->
    <div class="tab-pane fade" id="pills-pedidos" role="tabpanel" aria-labelledby="tab-pedidos">
      
      <!-- SECCIÓN DE DETALLE DE PEDIDO (SI HAY UNO SELECCIONADO) -->
      <?php if (!empty($idPedidoActual)): ?>
        <div class="card shadow mb-4 border-left-success">
          <div class="card-header py-3 d-flex flex-wrap align-items-center justify-content-between bg-light">
            <h6 class="m-0 font-weight-bold text-success">
              <i class="fas fa-boxes mr-1"></i>Items del Pedido #<?php echo htmlspecialchars((string)$idPedidoActual); ?>
            </h6>
            <div class="mt-2 mt-sm-0">
              <button class="btn btn-success btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalAgregarProducto">
                <i class="fas fa-plus mr-1"></i>Agregar Producto
              </button>
              <a href="controllerIndividualC.php" class="btn btn-secondary btn-sm ml-1 font-weight-bold shadow-sm">
                <i class="fas fa-times mr-1"></i>Cerrar Detalle
              </a>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="<?php echo !empty($materiaPrimaRes) ? 'col-lg-8' : 'col-12'; ?>">
                <?php if (!empty($detalleRes)): ?>
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                      <thead class="thead-light">
                        <tr>
                          <th class="text-center" style="width: 50px;">#</th>
                          <th>Producto / Receta</th>
                          <th class="text-center" style="width: 120px;">Cantidad</th>
                          <th class="text-right" style="width: 140px;">Subtotal</th>
                          <th class="text-center" style="width: 160px;">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $granTotal = 0;
                        foreach ($detalleRes as $idx => $item): 
                          $granTotal += (float)($item['total_producto'] ?? 0);
                        ?>
                          <tr>
                            <td class="text-center font-weight-bold"><?php echo $idx + 1; ?></td>
                            <td class="font-weight-bold text-gray-800"><?php echo htmlspecialchars($item['nombreReceta']); ?></td>
                            <td class="text-center font-weight-bold"><?php echo htmlspecialchars((string)$item['cantidad']); ?></td>
                            <td class="text-right font-weight-bold text-success">$<?php echo number_format((float)$item['total_producto'], 2); ?></td>
                            <td class="text-center">
                              <a href="controllerIndividualC.php?ver_receta=<?php echo $item['idReceta']; ?>&id_pedido=<?php echo $idPedidoActual; ?>" class="btn btn-info btn-sm" title="Ver componentes">
                                <i class="fas fa-leaf mr-1"></i>Receta
                              </a>
                              <form method="POST" action="controllerIndividualC.php" class="d-inline" onsubmit="return confirm('¿Deseas eliminar este producto del pedido?');">
                                <input type="hidden" name="id_detalle" value="<?php echo $item['idDetallePedido']; ?>">
                                <input type="hidden" name="id_pedido" value="<?php echo $idPedidoActual; ?>">
                                <button type="submit" name="eliminar_item" class="btn btn-danger btn-sm" title="Eliminar ítem">
                                  <i class="fas fa-trash"></i>
                                </button>
                              </form>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                      <tfoot>
                        <tr class="table-success font-weight-bold">
                          <td colspan="3" class="text-right">TOTAL ESTIMADO DEL PEDIDO:</td>
                          <td class="text-right text-success" style="font-size: 17px;">$<?php echo number_format($granTotal, 2); ?></td>
                          <td></td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>

                  <!-- BOTONES DE ACCIÓN: SEGUIR AGREGANDO O PAGAR -->
                  <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 pt-3 border-top">
                    <div class="mb-2 mb-md-0">
                      <a href="#pills-productos" onclick="$('#tab-productos').tab('show');" class="btn btn-outline-primary btn-sm font-weight-bold mr-2 mb-1">
                        <i class="fas fa-cart-plus mr-1"></i>Seguir Agregando Productos
                      </a>
                      <button class="btn btn-success btn-sm font-weight-bold mb-1" data-toggle="modal" data-target="#modalAgregarProducto">
                        <i class="fas fa-plus mr-1"></i>Agregar por Modal
                      </button>
                      <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold mb-1 ml-1" onclick="imprimirPedidoCliente(<?php echo $idPedidoActual; ?>)">
                        <i class="fas fa-print mr-1"></i>Imprimir Comprobante
                      </button>
                    </div>
                    <?php if ($granTotal > 0): ?>
                      <div>
                        <button type="button" class="btn btn-primary font-weight-bold shadow px-4 py-2" onclick="pagarPedido(<?php echo $idPedidoActual; ?>, <?php echo $granTotal; ?>)">
                          <i class="fas fa-credit-card mr-2"></i>Pagar Pedido Ahora ($<?php echo number_format($granTotal, 2); ?> USD)
                        </button>
                      </div>
                    <?php endif; ?>
                  </div>

                <?php else: ?>
                  <div class="alert alert-info mb-0 text-center py-4">
                    <i class="fas fa-box-open fa-2x d-block mb-2 text-info"></i>
                    <h6 class="font-weight-bold mb-1">Este pedido aún no tiene productos agregados.</h6>
                    <p class="small text-muted mb-3">Puedes agregar productos directamente desde el <strong>Catálogo</strong> o desde el botón de abajo.</p>
                    <button class="btn btn-success font-weight-bold shadow-sm mr-2" data-toggle="modal" data-target="#modalAgregarProducto">
                      <i class="fas fa-plus mr-1"></i>Agregar Producto Ahora
                    </button>
                    <a href="#pills-productos" onclick="$('#tab-productos').tab('show');" class="btn btn-primary font-weight-bold shadow-sm">
                      <i class="fas fa-boxes mr-1"></i>Ir al Catálogo
                    </a>
                  </div>
                <?php endif; ?>
              </div>

              <!-- COMPONENTES DE MATERIA PRIMA (SI SE SOLICITA VER RECETA) -->
              <?php if (!empty($materiaPrimaRes)): ?>
                <div class="col-lg-4 mt-3 mt-lg-0">
                  <div class="card shadow-sm border-info">
                    <div class="card-header bg-info text-white font-weight-bold py-2">
                      <i class="fas fa-flask mr-1"></i>Composición de Materia Prima
                    </div>
                    <div class="card-body p-0">
                      <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                          <thead>
                            <tr>
                              <th>Materia Prima</th>
                              <th class="text-right">Kg</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($materiaPrimaRes as $mp): ?>
                              <tr>
                                <td><?php echo htmlspecialchars($mp['NombreMP']); ?></td>
                                <td class="text-right font-weight-bold"><?php echo htmlspecialchars($mp['cantidadSa']); ?> kg</td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- HISTORIAL DE PEDIDOS -->
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history mr-1"></i>Mis Pedidos Registrados</h6>
          <span class="badge badge-primary font-weight-bold px-2 py-1"><?php echo count($dataPedido); ?> Pedido(s)</span>
        </div>
        <div class="card-body">
          <?php if (!empty($dataPedido)): ?>
            <div class="table-responsive">
              <table class="table table-bordered table-hover datatable" width="100%" cellspacing="0">
                <thead class="thead-light">
                  <tr>
                    <th class="text-center" style="width: 100px;">N° Pedido</th>
                    <th>Fecha de Solicitud</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center" style="width: 220px;">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($dataPedido as $pedido): ?>
                    <tr>
                      <td class="text-center font-weight-bold">#<?php echo htmlspecialchars((string)$pedido['idPedido']); ?></td>
                      <td><i class="far fa-calendar-alt text-muted mr-1"></i><?php echo htmlspecialchars($pedido['fechaPedido']); ?></td>
                      <td class="text-center">
                        <?php 
                          $estado = strtolower($pedido['nombreEstado'] ?? '');
                          $badgeClass = 'badge-secondary';
                          if (strpos($estado, 'proceso') !== false || strpos($estado, 'pendiente') !== false) {
                            $badgeClass = 'badge-warning';
                          } elseif (strpos($estado, 'entregado') !== false || strpos($estado, 'complet') !== false || strpos($estado, 'finaliz') !== false) {
                            $badgeClass = 'badge-success';
                          } elseif (strpos($estado, 'cancel') !== false) {
                            $badgeClass = 'badge-danger';
                          } else {
                            $badgeClass = 'badge-info';
                          }
                        ?>
                        <span class="badge <?php echo $badgeClass; ?> px-2 py-1">
                          <?php echo htmlspecialchars($pedido['nombreEstado'] ?? 'Registrado'); ?>
                        </span>
                      </td>
                      <td class="text-center" style="white-space: nowrap;">
                        <a href="controllerIndividualC.php?ver_detalle=<?php echo $pedido['idPedido']; ?>" class="btn btn-primary btn-sm font-weight-bold shadow-sm">
                          <i class="fas fa-eye mr-1"></i>Ver Items
                        </a>
                        <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold shadow-sm ml-1" onclick="imprimirPedidoCliente(<?php echo $pedido['idPedido']; ?>)" title="Imprimir Comprobante Oficial">
                          <i class="fas fa-print"></i>
                        </button>
                        <form method="POST" action="controllerIndividualC.php" class="d-inline ml-1" onsubmit="return confirm('¿Seguro que deseas eliminar el pedido #<?php echo $pedido['idPedido']; ?>?');">
                          <input type="hidden" name="id_pedido" value="<?php echo $pedido['idPedido']; ?>">
                          <button type="submit" name="eliminar_pedido" class="btn btn-danger btn-sm shadow-sm" title="Eliminar pedido">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="text-center py-5">
              <div class="mb-3">
                <i class="fas fa-shopping-basket fa-3x text-muted"></i>
              </div>
              <h5 class="font-weight-bold text-gray-800 mb-2">Aún no tienes pedidos registrados</h5>
              <p class="text-muted mb-4">Comienza a realizar tus pedidos de mezclas y productos directamente desde nuestro catálogo.</p>
              <form method="POST" action="controllerIndividualC.php">
                <button type="submit" name="crear_pedido" class="btn btn-primary font-weight-bold px-4 py-2 shadow-sm">
                  <i class="fas fa-cart-plus mr-2"></i>Hacer mi Primer Pedido
                </button>
              </form>
            </div>
          <?php endif; ?>
        </div>
      </div>

    </div>

    <!-- ==================================================== -->
    <!-- TAB 3: MIS PAGOS Y FACTURACIÓN                      -->
    <!-- ==================================================== -->
    <div class="tab-pane fade" id="pills-pagos" role="tabpanel" aria-labelledby="tab-pagos">
      <div class="card shadow mb-4 border-left-primary">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-receipt mr-1"></i>Mis Pagos y Facturación
          </h6>
          <span class="badge badge-primary font-weight-bold px-2 py-1"><?php echo count($misPagos); ?> Pago(s)</span>
        </div>
        <div class="card-body">
          <?php if (!empty($misPagos)): ?>
            <div class="table-responsive">
              <table class="table table-bordered table-hover datatable" width="100%" cellspacing="0">
                <thead class="thead-light">
                  <tr>
                    <th class="text-center" style="width: 90px;">N° Pago</th>
                    <th>Concepto / Plan</th>
                    <th class="text-right" style="width: 120px;">Monto</th>
                    <th class="text-center" style="width: 140px;">Método</th>
                    <th class="text-center" style="width: 130px;">Estado</th>
                    <th class="text-center" style="width: 170px;">Fecha y Hora</th>
                    <th class="text-center" style="width: 120px;">Comprobante</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($misPagos as $pago): ?>
                    <tr>
                      <td class="text-center font-weight-bold">#<?php echo htmlspecialchars((string)$pago['idPago']); ?></td>
                      <td>
                        <div class="font-weight-bold text-gray-800">
                          <?php echo htmlspecialchars($pago['nombrePlan'] ?? ($pago['descripcion'] ?? 'Pago de Servicio')); ?>
                        </div>
                        <?php if (!empty($pago['referencia'])): ?>
                          <div class="small text-muted">Ref: <code><?php echo htmlspecialchars($pago['referencia']); ?></code></div>
                        <?php endif; ?>
                      </td>
                      <td class="text-right font-weight-bold text-success">
                        $<?php echo number_format((float)$pago['monto'], 2); ?> <span class="small text-muted"><?php echo htmlspecialchars($pago['moneda'] ?? 'USD'); ?></span>
                      </td>
                      <td class="text-center">
                        <span class="badge badge-light border px-2 py-1 text-uppercase">
                          <i class="fas fa-credit-card mr-1"></i><?php echo htmlspecialchars($pago['metodo_pago'] ?? 'Wompi'); ?>
                        </span>
                      </td>
                      <td class="text-center">
                        <?php 
                          $estPago = strtolower($pago['estado'] ?? '');
                          $badgePagoClass = 'badge-secondary';
                          if ($estPago === 'completado' || $estPago === 'pagado') {
                            $badgePagoClass = 'badge-success';
                          } elseif ($estPago === 'pendiente') {
                            $badgePagoClass = 'badge-warning';
                          } elseif ($estPago === 'fallido' || $estPago === 'cancelado') {
                            $badgePagoClass = 'badge-danger';
                          }
                        ?>
                        <span class="badge <?php echo $badgePagoClass; ?> px-2 py-1 text-capitalize">
                          <?php echo htmlspecialchars($pago['estado']); ?>
                        </span>
                      </td>
                      <td class="text-center small text-muted">
                        <i class="far fa-clock mr-1"></i><?php echo !empty($pago['fecha_hora']) ? date('d/m/Y H:i', strtotime($pago['fecha_hora'])) : 'N/D'; ?>
                      </td>
                      <td class="text-center">
                        <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold shadow-sm" onclick="imprimirPagoCliente(<?php echo $pago['idPago']; ?>)" title="Imprimir Comprobante Oficial">
                          <i class="fas fa-print mr-1"></i>Recibo
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="text-center py-4">
              <i class="fas fa-file-invoice-dollar fa-2x text-muted mb-2"></i>
              <h6 class="font-weight-bold text-gray-800 mb-1">No se encontraron pagos registrados</h6>
              <p class="small text-muted mb-0">Cuando realices compras de planes o pagos en la plataforma, aparecerán listados aquí.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>

</div>

<!-- MODAL PARA AGREGAR PRODUCTO A PEDIDO (DESDE DETALLE) -->
<div class="modal fade" id="modalAgregarProducto" tabindex="-1" role="dialog" aria-labelledby="modalAgregarProductoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalAgregarProductoLabel"><i class="fas fa-cart-plus mr-2"></i>Agregar Producto al Pedido #<?php echo htmlspecialchars((string)($idPedidoActual ?? 0)); ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="controllerIndividualC.php">
        <input type="hidden" name="id_pedido" value="<?php echo htmlspecialchars((string)($idPedidoActual ?? 0)); ?>">
        <div class="modal-body">
          <div class="form-group">
            <label for="producto" class="font-weight-bold">Producto / Receta</label>
            <select class="form-control" name="producto" id="producto" required>
              <option value="">-- Selecciona un Producto --</option>
              <?php foreach ($receta as $r): ?>
                <option value="<?php echo $r['idReceta']; ?>">
                  <?php echo htmlspecialchars($r['nombreReceta']); ?> - $<?php echo number_format((float)($r['PrecioUnitario'] ?? 0), 2); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="txtcantidad" class="font-weight-bold">Cantidad de Unidades</label>
            <input type="number" min="1" class="form-control" name="txtcantidad" id="txtcantidad" value="1" required>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" name="agregar_producto" class="btn btn-primary font-weight-bold">
            <i class="fas fa-plus mr-1"></i>Agregar al Pedido
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

</div>
<!-- /.content-wrapper -->
</div>
<!-- /#wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Page level plugin JavaScript-->
<script src="../vendor/datatables/jquery.dataTables.js"></script>
<script src="../vendor/datatables/dataTables.bootstrap4.js"></script>
<script src="../controllers/js/sb-admin.min.js"></script>
<script src="../vendor/sweetalert2.all.min.js"></script>
<script src="../controllers/js/translations.js"></script>
<script src="../controllers/js/demo/datatables-demo.js"></script>

<!-- Footer -->
<footer class="sticky-footer bg-dark mt-auto">
  <div class="container my-auto py-3">
    <div class="copyright text-center my-auto">
      <span class="text-white">Copyright &copy; Concentrados El Gordito 2026</span>
    </div>
  </div>
</footer>

<script>
  $(document).ready(function() {
    <?php if (!empty($mostrarModalAgregar)): ?>
      $('#modalAgregarProducto').modal('show');
    <?php endif; ?>

    <?php if (!empty($idPedidoActual)): ?>
      // Si se está editando o viendo un pedido, activar la pestaña de pedidos
      $('#tab-pedidos').tab('show');
    <?php endif; ?>
  });

  function pagarPedido(idPedido, montoTotal) {
    if (!idPedido || montoTotal <= 0) {
      Swal.fire('Atención', 'El pedido no cuenta con un monto válido para pagar.', 'warning');
      return;
    }

    Swal.fire({
      title: '💳 Pagar Pedido #' + idPedido,
      html: '<p>Total a pagar: <strong class="text-success" style="font-size: 18px;">$' + parseFloat(montoTotal).toFixed(2) + ' USD</strong></p><p class="small text-muted">Serás redirigido a la pasarela segura de pago de Wompi El Salvador.</p>',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: '<i class="fas fa-lock mr-1"></i> Ir a Pagar con Wompi',
      cancelButtonText: 'Seguir Comprando',
      confirmButtonColor: '#2563eb',
      showLoaderOnConfirm: true,
      preConfirm: () => {
        const payload = {
          idPedido: idPedido,
          nombre: <?php echo json_encode($nombres); ?>,
          correo: <?php echo json_encode($correo); ?>,
          items: <?php echo json_encode(array_map(function($i) {
            $cant = max(1, (int)($i['cantidad'] ?? 1));
            $tot = (float)($i['total_producto'] ?? 0);
            return [
              'id' => (int)($i['idReceta'] ?? 1),
              'nombre' => (string)($i['nombreReceta'] ?? 'Producto'),
              'precio' => $tot / $cant,
              'cantidad' => $cant
            ];
          }, $detalleRes)); ?>
        };

        return fetch('../wompi/create-checkout-session.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })
        .then(response => {
          if (!response.ok) {
            return response.json().then(json => { throw new Error(json.error || 'Error al crear la sesión de pago'); });
          }
          return response.json();
        })
        .then(data => {
          if (data.url) {
            window.location.href = data.url;
          } else {
            throw new Error(data.error || 'No se recibió la URL de pago.');
          }
        })
        .catch(error => {
          Swal.showValidationMessage('Error: ' + error.message);
        });
      },
      allowOutsideClick: () => !Swal.isLoading()
    });
  }

  // Funciones de Impresión de Comprobantes para el Cliente
  function imprimirPedidoCliente(idPedido) {
    if (!idPedido) return;
    Swal.fire({
      title: 'Preparando Comprobante...',
      text: 'Cargando datos del pedido...',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); }
    });

    $.ajax({
      url: 'controllerIndividualC.php',
      type: 'GET',
      dataType: 'json',
      data: {
        accion: 'obtenerDetallePedido',
        idPedido: idPedido
      },
      success: function(resp) {
        Swal.close();
        if (resp && resp.success && resp.data) {
          abrirVentanaImpresionPedido(resp.data);
        } else {
          Swal.fire('Error', resp.mensaje || 'No se pudo cargar el detalle del pedido.', 'error');
        }
      },
      error: function() {
        Swal.close();
        Swal.fire('Error', 'No se pudo comunicar con el servidor para obtener el comprobante.', 'error');
      }
    });
  }

  function imprimirPagoCliente(idPago) {
    if (!idPago) return;
    Swal.fire({
      title: 'Preparando Recibo...',
      text: 'Cargando datos de la transacción...',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); }
    });

    $.ajax({
      url: 'controllerIndividualC.php',
      type: 'GET',
      dataType: 'json',
      data: {
        accion: 'obtenerDetallePago',
        idPago: idPago
      },
      success: function(resp) {
        Swal.close();
        if (resp && resp.success && resp.data) {
          abrirVentanaImpresionPago(resp.data);
        } else {
          Swal.fire('Error', resp.mensaje || 'No se pudo cargar la información del pago.', 'error');
        }
      },
      error: function() {
        Swal.close();
        Swal.fire('Error', 'No se pudo comunicar con el servidor para obtener el comprobante.', 'error');
      }
    });
  }

  function abrirVentanaImpresionPedido(data) {
    const p = data.pedido || {};
    const items = data.items || [];
    const total = parseFloat(data.total || 0).toFixed(2);
    const fechaEmision = new Date().toLocaleDateString('es-SV', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
    const idPedido = p.idPedido || '---';
    const nombreCliente = ((p.nombrePersona || p.NombreCliente || '') + ' ' + (p.apellidoPersona || p.apellidosCliente || '')).trim() || <?php echo json_encode($nombres); ?>;
    const telCliente = p.telefono || 'Sin teléfono';
    const correoCliente = p.correoCliente || <?php echo json_encode($correo); ?>;
    const fechaPedido = p.fechaPedido || '---';
    const nombreEstado = (p.nombreEstado || 'Pendiente').toUpperCase();

    let itemsRows = '';
    if (items.length > 0) {
      items.forEach((it, idx) => {
        const cant = parseInt(it.cantidad || 0);
        const precio = parseFloat(it.PrecioUnitario || 0).toFixed(2);
        const sub = parseFloat(it.subtotal || (cant * precio)).toFixed(2);
        itemsRows += `
          <tr>
            <td style="text-align: center; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">${idx + 1}</td>
            <td style="padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">
              <strong style="color: #1e293b; font-size: 13.5px;">${it.nombreReceta || 'Producto General'}</strong>
              <div style="font-size: 11px; color: #64748b;">Alimento Concentrado Formulado</div>
            </td>
            <td style="text-align: center; font-weight: bold; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">${cant} unid.</td>
            <td style="text-align: right; color: #475569; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">$${precio}</td>
            <td style="text-align: right; font-weight: bold; color: #0f172a; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">$${sub}</td>
          </tr>
        `;
      });
    } else {
      itemsRows = `<tr><td colspan="5" style="text-align: center; padding: 15px; color: #94a3b8;">No se registraron productos en este pedido.</td></tr>`;
    }

    const html = `
      <!DOCTYPE html>
      <html lang="es">
      <head>
        <meta charset="UTF-8">
        <title>Comprobante de Pedido #${idPedido} - Concentrados El Gordito</title>
        <style>
          @page { size: letter portrait; margin: 12mm 15mm 12mm 15mm; }
          body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; color: #1e293b; margin: 0; padding: 20px; font-size: 12.5px; line-height: 1.4; background: #ffffff; }
          .header-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
          .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; }
          .badge-status { display: inline-block; padding: 3px 8px; font-size: 10px; font-weight: bold; border-radius: 12px; text-transform: uppercase; background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
          .total-card { background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 8px; padding: 12px 16px; margin-top: 12px; margin-left: auto; width: 300px; }
          .signatures { width: 100%; margin-top: 35px; border-collapse: collapse; page-break-inside: avoid; }
          .signature-line { border-top: 1.5px solid #94a3b8; width: 85%; margin: 0 auto 5px auto; }
        </style>
      </head>
      <body>
        <table class="header-table">
          <tr>
            <td style="width: 58%; vertical-align: top;">
              <div style="font-size: 20px; font-weight: 800; color: #0f766e; text-transform: uppercase; letter-spacing: -0.5px;">
                Concentrados El Gordito
              </div>
              <div style="font-size: 11px; font-weight: 600; color: #475569; margin-top: 1px;">
                Nutrición Animal de Alta Calidad • Planta Central de Producción
              </div>
              <div style="font-size: 10.5px; color: #64748b; margin-top: 4px; line-height: 1.3;">
                📍 Km 45 Carretera Panamericana, San Salvador, El Salvador<br>
                📞 PBX: (503) 2234-5678 • WhatsApp: (503) 7890-5678<br>
                ✉️ ventas@concentradoselgordito.com • Registro MAG N° 894-SV
              </div>
            </td>
            <td style="width: 42%; vertical-align: top; text-align: right;">
              <div style="border: 1.5px solid #0f766e; border-radius: 8px; padding: 10px 12px; background: #f0fdfa; display: inline-block; text-align: right; min-width: 220px;">
                <div style="font-size: 10px; font-weight: bold; color: #0f766e; text-transform: uppercase;">Comprobante de Pedido</div>
                <div style="font-size: 18px; font-weight: 800; color: #134e4a; margin: 2px 0;">#PED-${String(idPedido).padStart(6, '0')}</div>
                <div style="font-size: 10.5px; color: #475569;"><strong>Fecha Pedido:</strong> ${fechaPedido}</div>
                <div style="font-size: 10px; color: #64748b;"><strong>Emisión:</strong> ${fechaEmision}</div>
                <div style="margin-top: 4px;"><span class="badge-status">${nombreEstado}</span></div>
              </div>
            </td>
          </tr>
        </table>

        <div class="info-box">
          <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <tr>
              <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                <div style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: #64748b; margin-bottom: 2px;">Datos del Cliente / Granja:</div>
                <div style="font-size: 14px; font-weight: bold; color: #0f172a;">${nombreCliente}</div>
                <div style="color: #475569; margin-top: 2px;">📞 Tel: ${telCliente}</div>
                <div style="color: #475569;">✉️ Correo: ${correoCliente}</div>
              </td>
              <td style="width: 50%; vertical-align: top; border-left: 1px solid #cbd5e1; padding-left: 10px;">
                <div style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: #64748b; margin-bottom: 2px;">Detalles de Despacho:</div>
                <div style="color: #334155;"><strong>Origen:</strong> Planta Central El Gordito</div>
                <div style="color: #334155;"><strong>Condición:</strong> Entrega en Planta / Ruta Programada</div>
                <div style="color: #334155;"><strong>Moneda:</strong> Dólares de los Estados Unidos (USD)</div>
              </td>
            </tr>
          </table>
        </div>

        <div style="font-size: 12px; font-weight: bold; text-transform: uppercase; color: #0f766e; border-bottom: 2px solid #99f6e4; padding-bottom: 3px; margin-bottom: 8px;">
          Detalle de Productos y Alimentos Concentrados
        </div>
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
          <thead>
            <tr style="background: #f1f5f9; color: #334155;">
              <th style="padding: 7px; text-align: center; width: 35px; border-bottom: 2px solid #cbd5e1;">#</th>
              <th style="padding: 7px; text-align: left; border-bottom: 2px solid #cbd5e1;">Descripción del Concentrado</th>
              <th style="padding: 7px; text-align: center; width: 100px; border-bottom: 2px solid #cbd5e1;">Cantidad</th>
              <th style="padding: 7px; text-align: right; width: 110px; border-bottom: 2px solid #cbd5e1;">Precio Unitario</th>
              <th style="padding: 7px; text-align: right; width: 110px; border-bottom: 2px solid #cbd5e1;">Subtotal</th>
            </tr>
          </thead>
          <tbody>${itemsRows}</tbody>
        </table>

        <div class="total-card">
          <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <tr>
              <td style="color: #475569; padding: 2px 0;">Subtotal Productos:</td>
              <td style="text-align: right; font-weight: 600; color: #1e293b;">$${total}</td>
            </tr>
            <tr>
              <td style="color: #475569; padding: 2px 0;">IVA (Exento Ley Agropecuaria):</td>
              <td style="text-align: right; font-weight: 600; color: #1e293b;">$0.00</td>
            </tr>
            <tr style="border-top: 1.5px solid #86efac;">
              <td style="padding-top: 5px; font-weight: 800; font-size: 14px; color: #14532d;">TOTAL GENERAL:</td>
              <td style="padding-top: 5px; text-align: right; font-weight: 800; font-size: 16px; color: #15803d;">$${total} USD</td>
            </tr>
          </table>
        </div>

        <div style="margin-top: 20px; padding: 8px 12px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; font-size: 10.5px; color: #64748b; line-height: 1.35;">
          <strong>Términos de Garantía y Despacho:</strong> El presente comprobante certifica la formulación y preparación de los concentrados solicitados. Al recibir el producto, el cliente valida el buen estado del empaque y peso correspondiente.
        </div>

        <table class="signatures">
          <tr>
            <td style="width: 48%; text-align: center; vertical-align: top;">
              <div class="signature-line"></div>
              <div style="font-weight: bold; font-size: 11.5px; color: #1e293b;">Entregado por / Planta y Despacho</div>
              <div style="font-size: 10px; color: #64748b;">Concentrados El Gordito S.A. de C.V.</div>
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 48%; text-align: center; vertical-align: top;">
              <div class="signature-line"></div>
              <div style="font-weight: bold; font-size: 11.5px; color: #1e293b;">Recibido Conforme (Cliente)</div>
              <div style="font-size: 10px; color: #64748b;">Firma, Nombre y N° Documento</div>
            </td>
          </tr>
        </table>

        <div style="text-align: center; font-size: 9.5px; color: #94a3b8; margin-top: 20px; border-top: 1px solid #f1f5f9; padding-top: 6px;">
          Impreso desde Concentrados El Gordito • Folio de Seguridad: CG-PED-${idPedido}-${Date.now().toString().slice(-6)}
        </div>
      </body>
      </html>
    `;

    const ventana = window.open('', '_blank', 'height=800,width=900');
    ventana.document.open();
    ventana.document.write(html);
    ventana.document.close();
    ventana.focus();
    setTimeout(() => { ventana.print(); }, 350);
  }

  function abrirVentanaImpresionPago(data) {
    const p = (data && data.pago) ? data.pago : (data || {});
    const meta = p.metadatos_array || {};
    const items = data.items || (meta.items || (p.items_comprados || []));
    let idTxn = p.idTransaccionWompi || (data.idTransaccion || '');
    if (!idTxn && meta.wompi_retorno && meta.wompi_retorno.id) idTxn = meta.wompi_retorno.id;
    if (!idTxn) idTxn = p.referencia || 'N/D';
    const formaPago = p.metodo_pago || (data.formaPago || 'Wompi SV (Tarjeta)');
    const idPago = p.idPago || '---';
    const monto = parseFloat(p.monto || 0).toFixed(2);
    const fechaEmision = new Date().toLocaleDateString('es-SV', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
    const fechaTxn = p.fecha_hora || '---';
    const nombreCliente = p.nombrePersona ? (p.nombrePersona + ' ' + (p.apellidoPersona || '')).trim() : (p.nombreCliente || (p.NombreCliente || <?php echo json_encode($nombres); ?>));
    const telCliente = p.telefonoCliente || (p.telefono || 'Sin teléfono');
    const correoCliente = p.correoCliente || (p.correo || <?php echo json_encode($correo); ?>);
    const estado = (p.estado || 'completado').toUpperCase();
    const esAprobado = estado === 'COMPLETADO' || estado === 'PAGADO' || estado === 'APROBADO';

    let itemsRows = '';
    if (items.length > 0) {
      items.forEach((it, idx) => {
        const cant = parseInt(it.cantidad || 1);
        const prec = parseFloat(it.precio || 0).toFixed(2);
        const sub = parseFloat(it.subtotal || (cant * prec)).toFixed(2);
        itemsRows += `
          <tr>
            <td style="text-align: center; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">${idx + 1}</td>
            <td style="padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">
              <strong style="color: #1e293b; font-size: 13.5px;">${it.nombre || 'Producto / Concentrado'}</strong>
              <div style="font-size: 11px; color: #64748b;">Línea de Nutrición Animal</div>
            </td>
            <td style="text-align: center; font-weight: bold; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">${cant} unid.</td>
            <td style="text-align: right; color: #475569; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">$${prec}</td>
            <td style="text-align: right; font-weight: bold; color: #0f172a; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">$${sub}</td>
          </tr>
        `;
      });
    } else {
      const concepto = p.nombrePlan || (p.descripcion || 'Pago de Concentrados y Servicios');
      itemsRows = `
        <tr>
          <td style="text-align: center; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">1</td>
          <td style="padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">
            <strong style="color: #1e293b; font-size: 13.5px;">${concepto}</strong>
            <div style="font-size: 11px; color: #64748b;">Transacción Comercial Confirmada</div>
          </td>
          <td style="text-align: center; font-weight: bold; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">1 servicio</td>
          <td style="text-align: right; color: #475569; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">$${monto}</td>
          <td style="text-align: right; font-weight: bold; color: #0f172a; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">$${monto}</td>
        </tr>
      `;
    }

    const html = `
      <!DOCTYPE html>
      <html lang="es">
      <head>
        <meta charset="UTF-8">
        <title>Comprobante de Pago #${idPago} - Concentrados El Gordito</title>
        <style>
          @page { size: letter portrait; margin: 12mm 15mm 12mm 15mm; }
          body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; color: #1e293b; margin: 0; padding: 20px; font-size: 12.5px; line-height: 1.4; background: #ffffff; }
          .header-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
          .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; }
          .badge-status { display: inline-block; padding: 3px 8px; font-size: 10px; font-weight: bold; border-radius: 12px; text-transform: uppercase; background: ${esAprobado ? '#dcfce7' : '#fee2e2'}; color: ${esAprobado ? '#166534' : '#991b1b'}; border: 1px solid ${esAprobado ? '#bbf7d0' : '#fecaca'}; }
          .total-card { background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 8px; padding: 12px 16px; margin-top: 12px; margin-left: auto; width: 300px; }
          .signatures { width: 100%; margin-top: 35px; border-collapse: collapse; page-break-inside: avoid; }
          .signature-line { border-top: 1.5px solid #94a3b8; width: 85%; margin: 0 auto 5px auto; }
        </style>
      </head>
      <body>
        <table class="header-table">
          <tr>
            <td style="width: 58%; vertical-align: top;">
              <div style="font-size: 20px; font-weight: 800; color: #0f766e; text-transform: uppercase; letter-spacing: -0.5px;">
                Concentrados El Gordito
              </div>
              <div style="font-size: 11px; font-weight: 600; color: #475569; margin-top: 1px;">
                Nutrición Animal de Alta Calidad • Pasarela Oficial Wompi El Salvador
              </div>
              <div style="font-size: 10.5px; color: #64748b; margin-top: 4px; line-height: 1.3;">
                📍 Km 45 Carretera Panamericana, San Salvador, El Salvador<br>
                📞 PBX: (503) 2234-5678 • WhatsApp: (503) 7890-5678<br>
                ✉️ pagos@concentradoselgordito.com • NRC: 245981-4 • NIT: 0614-220921-102-1
              </div>
            </td>
            <td style="width: 42%; vertical-align: top; text-align: right;">
              <div style="border: 1.5px solid #0f766e; border-radius: 8px; padding: 10px 12px; background: #f0fdfa; display: inline-block; text-align: right; min-width: 220px;">
                <div style="font-size: 10px; font-weight: bold; color: #0f766e; text-transform: uppercase;">Comprobante Oficial de Pago</div>
                <div style="font-size: 18px; font-weight: 800; color: #134e4a; margin: 2px 0;">#PAG-${String(idPago).padStart(6, '0')}</div>
                <div style="font-size: 10.5px; color: #475569;"><strong>Fecha Pago:</strong> ${fechaTxn}</div>
                <div style="font-size: 10px; color: #64748b;"><strong>Emisión:</strong> ${fechaEmision}</div>
                <div style="margin-top: 4px;"><span class="badge-status">${esAprobado ? 'PAGO APROBADO' : estado}</span></div>
              </div>
            </td>
          </tr>
        </table>

        <div class="info-box">
          <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <tr>
              <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                <div style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: #64748b; margin-bottom: 2px;">Datos del Pagador:</div>
                <div style="font-size: 14px; font-weight: bold; color: #0f172a;">${nombreCliente}</div>
                <div style="color: #475569; margin-top: 2px;">📞 Tel: ${telCliente}</div>
                <div style="color: #475569;">✉️ Correo: ${correoCliente}</div>
              </td>
              <td style="width: 50%; vertical-align: top; border-left: 1px solid #cbd5e1; padding-left: 10px;">
                <div style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: #64748b; margin-bottom: 2px;">Trazabilidad de la Transacción:</div>
                <div style="color: #334155;"><strong>Pasarela:</strong> Wompi El Salvador (Banco Agrícola)</div>
                <div style="color: #334155;"><strong>Método:</strong> ${formaPago}</div>
                <div style="color: #334155; word-break: break-all;"><strong>ID Transacción:</strong> <span style="font-family: monospace;">${idTxn}</span></div>
                <div style="color: #334155;"><strong>Referencia:</strong> <span style="font-family: monospace;">${p.referencia || 'N/D'}</span></div>
              </td>
            </tr>
          </table>
        </div>

        <div style="font-size: 12px; font-weight: bold; text-transform: uppercase; color: #0f766e; border-bottom: 2px solid #99f6e4; padding-bottom: 3px; margin-bottom: 8px;">
          Concepto y Desglose Liquidado
        </div>
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
          <thead>
            <tr style="background: #f1f5f9; color: #334155;">
              <th style="padding: 7px; text-align: center; width: 35px; border-bottom: 2px solid #cbd5e1;">#</th>
              <th style="padding: 7px; text-align: left; border-bottom: 2px solid #cbd5e1;">Descripción / Concepto</th>
              <th style="padding: 7px; text-align: center; width: 100px; border-bottom: 2px solid #cbd5e1;">Cantidad</th>
              <th style="padding: 7px; text-align: right; width: 110px; border-bottom: 2px solid #cbd5e1;">Precio</th>
              <th style="padding: 7px; text-align: right; width: 110px; border-bottom: 2px solid #cbd5e1;">Total</th>
            </tr>
          </thead>
          <tbody>${itemsRows}</tbody>
        </table>

        <div class="total-card">
          <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <tr>
              <td style="color: #475569; padding: 2px 0;">Monto Liquidado:</td>
              <td style="text-align: right; font-weight: 600; color: #1e293b;">$${monto}</td>
            </tr>
            <tr>
              <td style="color: #475569; padding: 2px 0;">Comisión / Recargos:</td>
              <td style="text-align: right; font-weight: 600; color: #1e293b;">$0.00</td>
            </tr>
            <tr style="border-top: 1.5px solid #86efac;">
              <td style="padding-top: 5px; font-weight: 800; font-size: 14px; color: #14532d;">TOTAL PAGADO:</td>
              <td style="padding-top: 5px; text-align: right; font-weight: 800; font-size: 16px; color: #15803d;">$${monto} USD</td>
            </tr>
          </table>
        </div>

        <div style="margin-top: 20px; padding: 8px 12px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; font-size: 10.5px; color: #64748b; line-height: 1.35;">
          <strong>Certificación Electrónica:</strong> Este documento ampara la transacción procesada exitosamente en la plataforma de pagos electrónicos Wompi El Salvador. Conserve este comprobante para cualquier gestión o retiro de producto en planta.
        </div>

        <table class="signatures">
          <tr>
            <td style="width: 48%; text-align: center; vertical-align: top;">
              <div class="signature-line"></div>
              <div style="font-weight: bold; font-size: 11.5px; color: #1e293b;">Autorizado / Tesorería y Caja</div>
              <div style="font-size: 10px; color: #64748b;">Concentrados El Gordito S.A. de C.V.</div>
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 48%; text-align: center; vertical-align: top;">
              <div class="signature-line"></div>
              <div style="font-weight: bold; font-size: 11.5px; color: #1e293b;">Sello Digital de Transacción</div>
              <div style="font-size: 10px; color: #64748b;">Validación en Línea Wompi SV</div>
            </td>
          </tr>
        </table>

        <div style="text-align: center; font-size: 9.5px; color: #94a3b8; margin-top: 20px; border-top: 1px solid #f1f5f9; padding-top: 6px;">
          Folio Electrónico Oficial: WMP-PAG-${idPago}-${Date.now().toString().slice(-6)} • Registro Seguro SSL 256-bit
        </div>
      </body>
      </html>
    `;

    const ventana = window.open('', '_blank', 'height=800,width=900');
    ventana.document.open();
    ventana.document.write(html);
    ventana.document.close();
    ventana.focus();
    setTimeout(() => { ventana.print(); }, 350);
  }
</script>

<?php if (!empty($msj) && !empty($icon)): ?>
  <script>
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: '<?php echo htmlspecialchars($msj, ENT_QUOTES); ?>',
        icon: '<?php echo htmlspecialchars($icon, ENT_QUOTES); ?>',
        timer: 3000,
        showConfirmButton: false
      });
    }
  </script>
<?php endif; ?>

</body>
</html>