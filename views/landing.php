<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Concentrados El Gordito - Alimentos balanceados y nutrición animal de alto rendimiento en El Salvador">
  <meta name="author" content="Concentrados El Gordito">

  <title>Concentrados El Gordito - Nutrición Animal de Alto Rendimiento</title>

  <!-- Bootstrap 4.6 & FontAwesome & Google Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary: #1e3a8a;
      --primary-hover: #1e40af;
      --success: #16a34a;
      --dark: #0f172a;
      --light-bg: #f8fafc;
      --border-color: #e2e8f0;
    }

    * {
      font-family: 'Inter', sans-serif;
    }

    body {
      background-color: var(--light-bg);
      color: #334155;
      overflow-x: hidden;
    }

    /* Navbar */
    .navbar-main {
      background-color: #0f172a !important;
      border-bottom: 2px solid #1e293b;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .navbar-brand {
      font-weight: 800;
      letter-spacing: -0.02em;
      font-size: 1.25rem;
    }
    .nav-link {
      font-weight: 600;
      color: #cbd5e1 !important;
      transition: color 0.2s ease;
      font-size: 0.95rem;
    }
    .nav-link:hover, .nav-link.active {
      color: #38bdf8 !important;
    }

    /* Hero Section */
    .hero-section {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      color: #ffffff;
      padding: 70px 0 60px 0;
      border-bottom: 1px solid #334155;
    }
    .hero-badge {
      display: inline-block;
      background: rgba(34, 197, 94, 0.15);
      border: 1px solid rgba(34, 197, 94, 0.4);
      color: #4ade80;
      font-weight: 700;
      font-size: 0.85rem;
      padding: 6px 14px;
      border-radius: 9999px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 16px;
    }

    /* Stat Cards - SB Admin Style */
    .stat-card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      background: #ffffff;
    }
    .stat-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .border-left-primary { border-left: 4px solid #2563eb !important; }
    .border-left-success { border-left: 4px solid #16a34a !important; }
    .border-left-info    { border-left: 4px solid #0284c7 !important; }
    .border-left-warning { border-left: 4px solid #d97706 !important; }

    /* Product Cards */
    .product-card {
      border: 1px solid var(--border-color);
      border-radius: 12px;
      background: #ffffff;
      transition: all 0.25s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    .product-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 30px rgba(0,0,0,0.08);
      border-color: #cbd5e1;
    }
    .product-icon {
      width: 56px;
      height: 56px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
    }

    /* Pricing Cards */
    .pricing-card {
      border: 1px solid var(--border-color);
      border-radius: 14px;
      background: #ffffff;
      transition: all 0.3s ease;
      position: relative;
    }
    .pricing-card.featured {
      border: 2px solid #2563eb;
      transform: scale(1.02);
      box-shadow: 0 15px 35px rgba(37,99,235,0.12);
    }
    .pricing-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    }

    /* Category Filter Buttons */
    .filter-btn {
      font-weight: 600;
      border-radius: 9999px;
      padding: 6px 18px;
      font-size: 0.9rem;
      border: 1px solid #cbd5e1;
      background: #ffffff;
      color: #475569;
      transition: all 0.2s;
    }
    .filter-btn.active, .filter-btn:hover {
      background: #0f172a;
      color: #ffffff;
      border-color: #0f172a;
    }

    /* Floating Cart Button */
    .floating-cart-btn {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: 1040;
      background: linear-gradient(135deg, #16a34a, #15803d);
      color: #ffffff;
      border-radius: 9999px;
      padding: 14px 22px;
      font-weight: 700;
      box-shadow: 0 8px 25px rgba(22,163,74,0.4);
      display: flex;
      align-items: center;
      gap: 10px;
      border: none;
      transition: all 0.2s ease;
      cursor: pointer;
    }
    .floating-cart-btn:hover {
      transform: scale(1.05);
      color: #ffffff;
      text-decoration: none;
      box-shadow: 0 10px 30px rgba(22,163,74,0.5);
    }

    /* Section Styling */
    section {
      padding: 65px 0;
    }
    .section-title {
      font-weight: 800;
      color: #0f172a;
      letter-spacing: -0.02em;
    }
    .section-subtitle {
      color: #64748b;
      font-size: 1.05rem;
      max-width: 650px;
      margin: 0 auto;
    }
  </style>
</head>

<body data-spy="scroll" data-target="#mainNav" data-offset="80">

  <!-- NAVBAR PRINCIPAL -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-main sticky-top" id="mainNav">
    <div class="container">
      <a class="navbar-brand text-white d-flex align-items-center" href="#inicio">
        <i class="fas fa-seedling text-success mr-2 fa-lg"></i>
        <span>Concentrados El Gordito</span>
      </a>

      <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Navegación">
        <i class="fas fa-bars text-white"></i>
      </button>

      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item">
            <a class="nav-link" href="#inicio"><i class="fas fa-home mr-1"></i>Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#productos"><i class="fas fa-boxes mr-1"></i>Productos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#planes"><i class="fas fa-crown mr-1"></i>Planes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#nosotros"><i class="fas fa-shield-alt mr-1"></i>Calidad</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#contacto"><i class="fas fa-envelope mr-1"></i>Contacto</a>
          </li>
        </ul>

        <div class="d-flex align-items-center">
          <button class="btn btn-outline-light btn-sm font-weight-bold mr-2 d-none d-sm-inline-flex align-items-center" onclick="abrirModalCarrito()">
            <i class="fas fa-shopping-cart mr-1"></i>
            <span class="badge badge-success ml-1" id="navCartCount">0</span>
          </button>
          <a href="controllers/controlUser.php" class="btn btn-primary btn-sm font-weight-bold shadow-sm">
            <i class="fas fa-user-circle mr-1"></i>Iniciar Sesión
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- HERO SECTION -->
  <section class="hero-section text-center text-lg-left" id="inicio">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-7 mb-5 mb-lg-0">
          <span class="hero-badge">
            <i class="fas fa-check-circle mr-1"></i>Fórmulas 100% Nutricionales y Balanceadas
          </span>
          <h1 class="display-4 font-weight-bold mb-3" style="letter-spacing: -0.03em; line-height: 1.15;">
            Nutrición Animal de <span class="text-warning">Alto Rendimiento</span> para tu Granja
          </h1>
          <p class="lead text-light mb-4" style="color: #cbd5e1 !important; font-size: 1.15rem;">
            Concentrados de alta digestibilidad formulados para maximizar la conversión alimenticia y el desarrollo de porcinos, aves de corral y ganado bovino en El Salvador.
          </p>
          <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3">
            <a href="#productos" class="btn btn-warning btn-lg font-weight-bold text-dark px-4 py-3 shadow mr-3 mb-2">
              <i class="fas fa-box-open mr-2"></i>Ver Catálogo de Mezclas
            </a>
            <a href="#planes" class="btn btn-outline-light btn-lg font-weight-bold px-4 py-3 mb-2">
              <i class="fas fa-crown mr-2"></i>Planes Comerciales
            </a>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="card shadow-lg bg-dark text-white border-0" style="border-radius: 16px; background: rgba(15, 23, 42, 0.75) !important; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15) !important;">
            <div class="card-body p-4 text-center">
              <div class="bg-success text-white p-3 rounded-circle d-inline-flex mb-3 shadow" style="width: 64px; height: 64px; align-items: center; justify-content: center; font-size: 28px;">
                <i class="fas fa-store-alt"></i>
              </div>
              <h4 class="font-weight-bold text-white mb-2">Portal de Clientes</h4>
              <p class="small text-muted mb-4" style="color: #94a3b8 !important;">
                Accede a tu cuenta para crear pedidos en línea, dar seguimiento al despacho y gestionar tus pagos en tiempo real.
              </p>
              <a href="controllers/controlUser.php" class="btn btn-success btn-block font-weight-bold py-2 shadow-sm mb-2">
                <i class="fas fa-sign-in-alt mr-2"></i>Acceder al Sistema
              </a>
              <span class="small text-muted" style="color: #64748b !important;">
                ¿Eres cliente nuevo? <a href="#planes" class="text-warning font-weight-bold">Suscríbete a un plan</a>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- TARJETAS DE ESTADÍSTICAS / MÉTRICAS (SB ADMIN STATS) -->
  <section class="py-4" style="margin-top: -30px; position: relative; z-index: 10;">
    <div class="container">
      <div class="row">
        
        <div class="col-xl-3 col-md-6 mb-3">
          <div class="card stat-card border-left-primary h-100 py-2">
            <div class="card-body py-2 px-3">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Clientes Satisfechos</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">+500 Granjas</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-users fa-2x text-gray-300" style="color: #cbd5e1;"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
          <div class="card stat-card border-left-success h-100 py-2">
            <div class="card-body py-2 px-3">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Calidad Garantizada</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">100% Digestibilidad</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-award fa-2x text-gray-300" style="color: #cbd5e1;"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
          <div class="card stat-card border-left-info h-100 py-2">
            <div class="card-body py-2 px-3">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Cobertura Nacional</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">Todo El Salvador</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-truck-moving fa-2x text-gray-300" style="color: #cbd5e1;"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
          <div class="card stat-card border-left-warning h-100 py-2">
            <div class="card-body py-2 px-3">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pasarela Segura</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">Wompi SV & Tarjetas</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-lock fa-2x text-gray-300" style="color: #cbd5e1;"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- CATÁLOGO DE PRODUCTOS -->
  <section id="productos" class="bg-white">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title h2 font-weight-bold">Nuestros Concentrados y Mezclas</h2>
        <p class="section-subtitle">
          Fórmulas diseñadas por especialistas en nutrición animal para cada etapa productiva de tus animales.
        </p>

        <!-- Filtros de Categoría -->
        <div class="d-flex flex-wrap justify-content-center gap-2 mt-4" id="filtrosCategorias">
          <button class="filter-btn active mr-2 mb-2" data-filter="todos">Todos los Productos</button>
          <button class="filter-btn mr-2 mb-2" data-filter="aves">Aves / Pollos</button>
          <button class="filter-btn mr-2 mb-2" data-filter="cerdos">Porcinos</button>
          <button class="filter-btn mr-2 mb-2" data-filter="ganado">Ganado Bovino</button>
          <button class="filter-btn mb-2" data-filter="balanceados">Balanceados</button>
        </div>
      </div>

      <!-- Cuadrícula de Productos -->
      <div class="row" id="catalogoGrid">
        <?php foreach ($catalogoProductos as $p): 
          $filtro = $p['filtro'] ?? 'balanceados';
          $colorBg = '#f1f5f9';
          $colorText = '#0f172a';
          if ($filtro === 'aves') { $colorBg = '#fef3c7'; $colorText = '#d97706'; }
          elseif ($filtro === 'cerdos') { $colorBg = '#fee2e2'; $colorText = '#dc2626'; }
          elseif ($filtro === 'ganado') { $colorBg = '#dcfce7'; $colorText = '#16a34a'; }
          elseif ($filtro === 'balanceados') { $colorBg = '#e0f2fe'; $colorText = '#0284c7'; }
        ?>
          <div class="col-xl-3 col-lg-4 col-md-6 mb-4 item-producto" data-categoria="<?php echo $filtro; ?>">
            <div class="product-card p-3">
              
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="product-icon" style="background-color: <?php echo $colorBg; ?>; color: <?php echo $colorText; ?>;">
                  <i class="<?php echo $p['icono']; ?>"></i>
                </div>
                <?php if (!empty($p['en_promocion'])): ?>
                  <span class="badge badge-danger px-2 py-1 font-weight-bold shadow-sm">
                    -<?php echo $p['porcentaje_descuento']; ?>% OFF
                  </span>
                <?php else: ?>
                  <span class="badge badge-light border text-muted px-2 py-1">
                    <?php echo htmlspecialchars($p['categoria']); ?>
                  </span>
                <?php endif; ?>
              </div>

              <h5 class="font-weight-bold text-gray-900 mb-1" style="font-size: 1.05rem;">
                <?php echo htmlspecialchars($p['nombre']); ?>
              </h5>
              <p class="text-muted small mb-2" style="min-height: 38px;">
                <?php echo htmlspecialchars($p['etapa']); ?>
              </p>

              <div class="mb-3">
                <span class="badge badge-light border text-dark font-weight-bold px-2 py-1">
                  <i class="fas fa-dna text-primary mr-1"></i><?php echo htmlspecialchars($p['proteina']); ?>
                </span>
              </div>

              <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                <div>
                  <span class="h4 font-weight-bold text-success mb-0">$<?php echo number_format($p['precio'], 2); ?></span>
                  <span class="small text-muted">/ saco</span>
                  <?php if (!empty($p['en_promocion']) && $p['precio_anterior'] > $p['precio']): ?>
                    <div class="small text-muted" style="text-decoration: line-through;">
                      $<?php echo number_format($p['precio_anterior'], 2); ?>
                    </div>
                  <?php endif; ?>
                </div>

                <button class="btn btn-primary btn-sm font-weight-bold px-3 py-2 shadow-sm" onclick="agregarAlCarrito(<?php echo $p['id']; ?>, '<?php echo addslashes($p['nombre']); ?>', <?php echo $p['precio']; ?>, '<?php echo $p['icono']; ?>')">
                  <i class="fas fa-cart-plus mr-1"></i>Agregar
                </button>
              </div>

            </div>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </section>

  <!-- PLANES Y SUSCRIPCIONES COMERCIALES -->
  <section id="planes" class="bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <span class="text-primary font-weight-bold text-uppercase small" style="letter-spacing: 0.05em;">Suscripciones para Productores</span>
        <h2 class="section-title h2 font-weight-bold mt-1">Planes Comerciales</h2>
        <p class="section-subtitle">
          Disfruta de tarifas preferenciales, despachos programados y asesoría nutricional continua para tu granja.
        </p>
      </div>

      <div class="row justify-content-center">
        <?php foreach ($planesServicio as $idx => $plan): 
          $esDestacado = !empty($plan['destacado']) || ($idx === 1);
          $montoPlan = (float)($plan['monto'] ?? ($plan['precio'] ?? 0));
          $caracteristicasPlan = $plan['caracteristicas'] ?? ($plan['beneficios'] ?? []);
          if (!is_array($caracteristicasPlan)) {
            $caracteristicasPlan = [$caracteristicasPlan];
          }
        ?>
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="pricing-card <?php echo $esDestacado ? 'featured' : ''; ?> p-4 h-100 d-flex flex-column text-center">
              
              <?php if ($esDestacado): ?>
                <div class="position-absolute" style="top: -12px; left: 50%; transform: translateX(-50%);">
                  <span class="badge badge-primary px-3 py-1 font-weight-bold text-uppercase shadow-sm">
                    <?php echo htmlspecialchars($plan['badge'] ?? 'Más Recomendado'); ?>
                  </span>
                </div>
              <?php endif; ?>

              <div class="my-3">
                <h4 class="font-weight-bold text-gray-900 mb-1"><?php echo htmlspecialchars($plan['nombre']); ?></h4>
                <p class="text-muted small"><?php echo htmlspecialchars($plan['descripcion']); ?></p>
              </div>

              <div class="py-3 my-2 border-top border-bottom bg-light rounded">
                <div class="display-4 font-weight-bold text-primary mb-0" style="font-size: 2.75rem;">
                  $<?php echo number_format($montoPlan, 2); ?>
                </div>
                <span class="small text-muted font-weight-bold">USD por <?php echo $plan['duracion_dias']; ?> días</span>
              </div>

              <ul class="list-unstyled text-left my-4 px-2 small">
                <?php foreach ($caracteristicasPlan as $ben): ?>
                  <li class="mb-2 d-flex align-items-center">
                    <i class="fas fa-check-circle text-success mr-2"></i>
                    <span><?php echo htmlspecialchars($ben); ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>

              <div class="mt-auto pt-2">
                <button class="btn <?php echo $esDestacado ? 'btn-primary shadow' : 'btn-outline-primary'; ?> btn-block font-weight-bold py-2" onclick="iniciarCheckoutPlan(<?php echo $plan['id']; ?>, '<?php echo addslashes($plan['nombre']); ?>', <?php echo $montoPlan; ?>)">
                  <i class="fas fa-credit-card mr-1"></i>Contratar Plan
                </button>
              </div>

            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- SECCIÓN DE CALIDAD / POR QUÉ ELEGIRNOS -->
  <section id="nosotros" class="bg-white">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <span class="text-success font-weight-bold text-uppercase small">Compromiso Nutricional</span>
          <h2 class="section-title h2 font-weight-bold mt-1 mb-3">Formulaciones Científicas de Máxima Calidad</h2>
          <p class="text-muted mb-4">
            En <strong>Concentrados El Gordito</strong> seleccionamos materias primas de primer nivel y controlamos estrictamente cada mezcla para asegurar el aporte exacto de proteínas, aminoácidos, vitaminas y minerales.
          </p>

          <div class="row">
            <div class="col-sm-6 mb-3">
              <div class="d-flex align-items-start">
                <div class="text-success mr-3 mt-1"><i class="fas fa-check-square fa-lg"></i></div>
                <div>
                  <h6 class="font-weight-bold text-gray-900 mb-1">Materias Primas Certificadas</h6>
                  <p class="small text-muted mb-0">Maíz, soya, premezclas y minerales rigurosamente inspeccionados.</p>
                </div>
              </div>
            </div>

            <div class="col-sm-6 mb-3">
              <div class="d-flex align-items-start">
                <div class="text-success mr-3 mt-1"><i class="fas fa-check-square fa-lg"></i></div>
                <div>
                  <h6 class="font-weight-bold text-gray-900 mb-1">Entregas Puntuales</h6>
                  <p class="small text-muted mb-0">Despacho eficiente directo a tu granja o punto de distribución.</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card border-0 shadow-sm bg-light p-4 rounded-lg">
            <h5 class="font-weight-bold text-gray-900 mb-3"><i class="fas fa-info-circle text-primary mr-2"></i>Información de la Empresa</h5>
            <ul class="list-unstyled mb-0">
              <li class="mb-3 d-flex align-items-center">
                <i class="fas fa-map-marker-alt text-danger mr-3 fa-lg" style="width: 20px;"></i>
                <span><?php echo htmlspecialchars($infoEmpresa['direccion'] ?? 'El Salvador, Centroamérica'); ?></span>
              </li>
              <li class="mb-3 d-flex align-items-center">
                <i class="fas fa-phone-alt text-primary mr-3 fa-lg" style="width: 20px;"></i>
                <span><?php echo htmlspecialchars($infoEmpresa['telefono'] ?? '+503 2222-0000'); ?></span>
              </li>
              <li class="mb-3 d-flex align-items-center">
                <i class="fas fa-envelope text-info mr-3 fa-lg" style="width: 20px;"></i>
                <span><?php echo htmlspecialchars($infoEmpresa['email'] ?? 'contacto@concentradoselgordito.com'); ?></span>
              </li>
              <li class="d-flex align-items-center">
                <i class="fas fa-clock text-warning mr-3 fa-lg" style="width: 20px;"></i>
                <span><?php echo htmlspecialchars($infoEmpresa['horario'] ?? 'Lunes a Sábado: 7:00 AM - 5:00 PM'); ?></span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SECCIÓN DE CONTACTO -->
  <section id="contacto" class="bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title h2 font-weight-bold">¿Tienes Dudas o Pedidos Especiales?</h2>
        <p class="section-subtitle">
          Nuestro equipo de técnicos y asesores comerciales está listo para orientarte con la mejor fórmula para tus animales.
        </p>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card shadow-sm border-0 p-4">
            <form onsubmit="enviarMensajeContacto(event)">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="cNombre" class="font-weight-bold small text-gray-700">Nombre Completo</label>
                  <input type="text" class="form-control" id="cNombre" placeholder="Tu nombre" required>
                </div>
                <div class="form-group col-md-6">
                  <label for="cTelefono" class="font-weight-bold small text-gray-700">Teléfono / WhatsApp</label>
                  <input type="tel" class="form-control" id="cTelefono" placeholder="Ej: 7777-8888" required>
                </div>
              </div>
              <div class="form-group">
                <label for="cEmail" class="font-weight-bold small text-gray-700">Correo Electrónico</label>
                <input type="email" class="form-control" id="cEmail" placeholder="correo@ejemplo.com" required>
              </div>
              <div class="form-group">
                <label for="cMensaje" class="font-weight-bold small text-gray-700">Mensaje o Consulta Nutricional</label>
                <textarea class="form-control" id="cMensaje" rows="3" placeholder="Describe los productos o cantidades que necesitas..." required></textarea>
              </div>
              <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2 shadow-sm">
                <i class="fas fa-paper-plane mr-2"></i>Enviar Consulta
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER LIMPIO (SB ADMIN STYLE) -->
  <footer class="bg-dark text-white py-4 border-top" style="border-color: #334155 !important;">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-left mb-3 mb-md-0">
          <div class="font-weight-bold text-white mb-1">
            <i class="fas fa-seedling text-success mr-1"></i> Concentrados El Gordito &copy; 2026
          </div>
          <span class="small text-muted" style="color: #94a3b8 !important;">
            Nutrición y alimentos balanceados para granjas en El Salvador. Todos los derechos reservados.
          </span>
        </div>
        <div class="col-md-6 text-center text-md-right">
          <a href="controllers/controlUser.php" class="btn btn-outline-light btn-sm font-weight-bold mr-2">
            <i class="fas fa-lock mr-1"></i>Acceso Empleados y Admin
          </a>
          <a href="#inicio" class="btn btn-secondary btn-sm font-weight-bold" title="Ir arriba">
            <i class="fas fa-chevron-up"></i>
          </a>
        </div>
      </div>
    </div>
  </footer>

  <!-- BOTÓN FLOTANTE DE CARRITO -->
  <button class="floating-cart-btn" onclick="abrirModalCarrito()" id="btnCarritoFlotante" style="display: none;">
    <i class="fas fa-shopping-cart fa-lg"></i>
    <span>Ver Pedido (<strong id="cartFloatingCount">0</strong>)</span>
  </button>

  <!-- MODAL DE CHECKOUT / CARRITO DE COMPRAS -->
  <div class="modal fade" id="modalCarrito" tabindex="-1" role="dialog" aria-labelledby="modalCarritoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title font-weight-bold" id="modalCarritoLabel">
            <i class="fas fa-shopping-cart text-success mr-2"></i>Tu Carrito de Compra
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div id="cartEmptyState" class="text-center py-4" style="display: none;">
            <i class="fas fa-shopping-basket fa-3x text-muted mb-2"></i>
            <h6 class="font-weight-bold text-gray-800">Tu carrito está vacío</h6>
            <p class="small text-muted mb-3">Agrega productos del catálogo para procesar tu orden.</p>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-dismiss="modal">
              Explorar Catálogo
            </button>
          </div>

          <div id="cartContentState">
            <div class="table-responsive mb-3">
              <table class="table table-bordered table-sm mb-0">
                <thead class="thead-light">
                  <tr>
                    <th>Producto</th>
                    <th class="text-center" style="width: 100px;">Precio</th>
                    <th class="text-center" style="width: 130px;">Cantidad</th>
                    <th class="text-right" style="width: 110px;">Subtotal</th>
                    <th class="text-center" style="width: 60px;"></th>
                  </tr>
                </thead>
                <tbody id="cartTableBody"></tbody>
                <tfoot>
                  <tr class="table-success font-weight-bold">
                    <td colspan="3" class="text-right">TOTAL A PAGAR:</td>
                    <td class="text-right text-success" id="cartTotalSum" style="font-size: 16px;">$0.00</td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>

            <!-- Datos del Comprador -->
            <div class="bg-light p-3 rounded border mb-3">
              <h6 class="font-weight-bold text-gray-800 mb-2"><i class="fas fa-user mr-1 text-primary"></i>Datos para el Despacho y Facturación</h6>
              <div class="form-row">
                <div class="form-group col-md-6 mb-2">
                  <label class="small font-weight-bold text-muted mb-1">Nombre o Empresa</label>
                  <input type="text" class="form-control form-control-sm" id="buyerName" placeholder="Tu nombre o granja" required>
                </div>
                <div class="form-group col-md-6 mb-2">
                  <label class="small font-weight-bold text-muted mb-1">Teléfono / WhatsApp</label>
                  <input type="tel" class="form-control form-control-sm" id="buyerPhone" placeholder="Ej: 7777-8888" required>
                </div>
              </div>
              <div class="form-group mb-0">
                <label class="small font-weight-bold text-muted mb-1">Correo Electrónico (para comprobante)</label>
                <input type="email" class="form-control form-control-sm" id="buyerEmail" placeholder="correo@ejemplo.com" required>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light" id="cartModalFooter">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Seguir Comprando</button>
          <button type="button" class="btn btn-success font-weight-bold shadow-sm" onclick="procesarPagoCarrito()" id="btnPagarCarrito">
            <i class="fas fa-lock mr-1"></i>Pagar con Wompi SV
          </button>
        </div>

      </div>
    </div>
  </div>

  <!-- SCRIPTS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Carrito de compras local
    let carrito = JSON.parse(localStorage.getItem('gordito_cart') || '[]');

    function guardarCarrito() {
      localStorage.setItem('gordito_cart', JSON.stringify(carrito));
      actualizarContadoresCarrito();
    }

    function actualizarContadoresCarrito() {
      const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);
      $('#navCartCount').text(totalItems);
      $('#cartFloatingCount').text(totalItems);
      if (totalItems > 0) {
        $('#btnCarritoFlotante').fadeIn(200);
      } else {
        $('#btnCarritoFlotante').fadeOut(200);
      }
    }

    function agregarAlCarrito(id, nombre, precio, icono) {
      const existente = carrito.find(i => i.id === id);
      if (existente) {
        existente.cantidad += 1;
      } else {
        carrito.push({ id, nombre, precio: parseFloat(precio), icono, cantidad: 1 });
      }
      guardarCarrito();

      Swal.fire({
        title: 'Producto añadido',
        text: `${nombre} agregado al carrito`,
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
      });
    }

    function cambiarCantidadItem(id, delta) {
      const item = carrito.find(i => i.id === id);
      if (item) {
        item.cantidad += delta;
        if (item.cantidad <= 0) {
          carrito = carrito.filter(i => i.id !== id);
        }
        guardarCarrito();
        renderizarTablaCarrito();
      }
    }

    function eliminarItemCarrito(id) {
      carrito = carrito.filter(i => i.id !== id);
      guardarCarrito();
      renderizarTablaCarrito();
    }

    function renderizarTablaCarrito() {
      const tbody = $('#cartTableBody');
      tbody.empty();

      if (carrito.length === 0) {
        $('#cartEmptyState').show();
        $('#cartContentState').hide();
        $('#cartModalFooter').hide();
        return;
      }

      $('#cartEmptyState').hide();
      $('#cartContentState').show();
      $('#cartModalFooter').show();

      let total = 0;
      carrito.forEach(item => {
        const subtotal = item.precio * item.cantidad;
        total += subtotal;
        tbody.append(`
          <tr>
            <td class="align-middle font-weight-bold text-gray-800">${item.nombre}</td>
            <td class="align-middle text-center">$${item.precio.toFixed(2)}</td>
            <td class="align-middle text-center">
              <div class="input-group input-group-sm justify-content-center" style="width: 110px; margin: 0 auto;">
                <div class="input-group-prepend">
                  <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidadItem(${item.id}, -1)">-</button>
                </div>
                <input type="text" class="form-control text-center font-weight-bold" value="${item.cantidad}" readonly>
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidadItem(${item.id}, 1)">+</button>
                </div>
              </div>
            </td>
            <td class="align-middle text-right font-weight-bold text-success">$${subtotal.toFixed(2)}</td>
            <td class="align-middle text-center">
              <button class="btn btn-outline-danger btn-sm" onclick="eliminarItemCarrito(${item.id})" title="Eliminar"><i class="fas fa-trash"></i></button>
            </td>
          </tr>
        `);
      });

      $('#cartTotalSum').text('$' + total.toFixed(2) + ' USD');
    }

    function abrirModalCarrito() {
      renderizarTablaCarrito();
      $('#modalCarrito').modal('show');
    }

    function procesarPagoCarrito() {
      if (carrito.length === 0) {
        Swal.fire('Atención', 'El carrito está vacío.', 'warning');
        return;
      }

      const nombre = $('#buyerName').val().trim();
      const telefono = $('#buyerPhone').val().trim();
      const correo = $('#buyerEmail').val().trim();

      if (!nombre || !telefono || !correo) {
        Swal.fire('Campos requeridos', 'Por favor ingresa tu nombre, teléfono y correo electrónico para la factura.', 'warning');
        return;
      }

      const btn = $('#btnPagarCarrito');
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Conectando con Wompi...');

      const payload = {
        nombre: nombre,
        telefono: telefono,
        correo: correo,
        items: carrito.map(i => ({
          id: i.id,
          nombre: i.nombre,
          precio: i.precio,
          cantidad: i.cantidad
        }))
      };

      fetch('wompi/create-checkout-session.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        if (data.url) {
          window.location.href = data.url;
        } else {
          throw new Error(data.error || 'Error al generar la sesión de pago.');
        }
      })
      .catch(err => {
        btn.prop('disabled', false).html('<i class="fas fa-lock mr-1"></i> Pagar con Wompi SV');
        Swal.fire('Error', err.message, 'error');
      });
    }

    function iniciarCheckoutPlan(planId, nombrePlan, monto) {
      Swal.fire({
        title: 'Suscripción a ' + nombrePlan,
        html: `
          <p class="text-muted small">Ingresa tus datos para vincular tu suscripción y continuar a la pasarela segura de Wompi SV:</p>
          <div class="text-left">
            <div class="form-group mb-2">
              <label class="small font-weight-bold mb-1">Nombre Completo o Granja</label>
              <input type="text" id="swalName" class="form-control form-control-sm" placeholder="Tu nombre" required>
            </div>
            <div class="form-group mb-2">
              <label class="small font-weight-bold mb-1">Teléfono / WhatsApp</label>
              <input type="tel" id="swalPhone" class="form-control form-control-sm" placeholder="7777-8888" required>
            </div>
            <div class="form-group mb-0">
              <label class="small font-weight-bold mb-1">Correo Electrónico</label>
              <input type="email" id="swalEmail" class="form-control form-control-sm" placeholder="correo@ejemplo.com" required>
            </div>
          </div>
          <div class="mt-3 p-2 bg-light rounded text-center">
            <span class="small font-weight-bold text-muted">Monto a pagar:</span>
            <span class="h5 font-weight-bold text-success mb-0 d-block">$${parseFloat(monto).toFixed(2)} USD</span>
          </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-lock mr-1"></i> Proceder al Pago',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#2563eb',
        preConfirm: () => {
          const nombre = document.getElementById('swalName').value.trim();
          const telefono = document.getElementById('swalPhone').value.trim();
          const correo = document.getElementById('swalEmail').value.trim();

          if (!nombre || !telefono || !correo) {
            Swal.showValidationMessage('Por favor completa todos los campos.');
            return false;
          }

          return { nombre, telefono, correo };
        }
      }).then(result => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'Iniciando Wompi...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
          });

          fetch('wompi/create-checkout-session.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              planId: planId,
              nombre: result.value.nombre,
              telefono: result.value.telefono,
              correo: result.value.correo
            })
          })
          .then(res => res.json())
          .then(data => {
            if (data.url) {
              window.location.href = data.url;
            } else {
              throw new Error(data.error || 'Error al conectar con la pasarela.');
            }
          })
          .catch(err => {
            Swal.fire('Error', err.message, 'error');
          });
        }
      });
    }

    function enviarMensajeContacto(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Mensaje Enviado',
        text: '¡Gracias por contactarnos! Un asesor técnico se comunicará contigo a la brevedad.',
        icon: 'success',
        confirmButtonColor: '#2563eb'
      });
      e.target.reset();
    }

    // Filtros de productos
    $(document).ready(function() {
      actualizarContadoresCarrito();

      $('#filtrosCategorias .filter-btn').on('click', function() {
        $('#filtrosCategorias .filter-btn').removeClass('active');
        $(this).addClass('active');

        const cat = $(this).data('filter');
        if (cat === 'todos') {
          $('.item-producto').fadeIn(200);
        } else {
          $('.item-producto').each(function() {
            if ($(this).data('categoria') === cat) {
              $(this).fadeIn(200);
            } else {
              $(this).fadeOut(200);
            }
          });
        }
      });
    });
  </script>

</body>
</html>
