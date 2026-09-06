<?php
if (!isset($planesServicio) || !isset($catalogoProductos)) {
    require_once __DIR__ . '/../models/LandingModel.php';
    $modeloLandingAuto = new LandingModel();
    if (!isset($catalogoProductos)) {
        $catalogoProductos = $modeloLandingAuto->obtenerProductosCatalogo();
    }
    if (!isset($planesServicio)) {
        $planesServicio = $modeloLandingAuto->obtenerPlanesDisponibles();
    }
    if (!isset($estadisticas)) {
        $estadisticas = $modeloLandingAuto->obtenerEstadisticas();
    }
    if (!isset($infoEmpresa)) {
        $infoEmpresa = $modeloLandingAuto->obtenerInformacionEmpresa();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concentrados El Gordito | Nutrición Animal de Alto Rendimiento en El Salvador</title>
    <meta name="description" content="Alimentos concentrados y fórmulas balanceadas de alta calidad para aves, cerdos y ganado bovino en El Salvador. Compra directa con carrito y pasarela Wompi.">

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="views/Recursos/icon.jpg">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Custom High-End Styling -->
    <style>
        :root {
            --primary-emerald: #059669;
            --primary-dark: #064e3b;
            --primary-light: #10b981;
            --primary-glow: rgba(16, 185, 129, 0.25);
            --accent-gold: #f59e0b;
            --accent-warm: #d97706;
            --dark-surface: #0f172a;
            --dark-card: #1e293b;
            --light-bg: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --card-radius: 18px;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-main);
            background-color: #ffffff;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Top Notification Bar */
        .top-announcement {
            background: linear-gradient(90deg, #064e3b, #059669);
            color: #ffffff;
            font-size: 0.85rem;
            padding: 8px 0;
            font-weight: 500;
        }

        .top-announcement a {
            color: #fef08a;
            text-decoration: none;
            font-weight: 700;
        }

        /* Navbar */
        .navbar-brand img {
            height: 48px;
            width: 48px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid var(--primary-light);
            box-shadow: 0 4px 10px var(--primary-glow);
        }

        .navbar-brand-text {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.5px;
            color: var(--dark-surface);
            line-height: 1.2;
        }

        .navbar-brand-subtitle {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
            color: var(--primary-emerald);
        }

        .custom-navbar {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            transition: var(--transition-smooth);
        }

        .nav-link {
            font-weight: 600;
            font-size: 0.95rem;
            color: #334155 !important;
            padding: 8px 16px !important;
            border-radius: 8px;
            transition: var(--transition-smooth);
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-emerald) !important;
            background-color: #ecfdf5;
        }

        .btn-nav-cart {
            background: #ffffff;
            border: 2px solid var(--primary-light);
            color: var(--primary-dark);
            font-weight: 700;
            padding: 8px 18px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition-smooth);
            cursor: pointer;
        }

        .btn-nav-cart:hover {
            background: var(--primary-emerald);
            color: #ffffff;
            box-shadow: 0 4px 15px var(--primary-glow);
            transform: translateY(-2px);
        }

        .btn-portal {
            background: linear-gradient(135deg, var(--primary-emerald), var(--primary-dark));
            color: #ffffff !important;
            font-weight: 700;
            padding: 10px 22px;
            border-radius: 999px;
            box-shadow: 0 4px 15px var(--primary-glow);
            transition: var(--transition-smooth);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-portal:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4);
            color: #ffffff !important;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            background: radial-gradient(circle at top right, rgba(16, 185, 129, 0.15), transparent 50%),
                        radial-gradient(circle at bottom left, rgba(245, 158, 11, 0.08), transparent 50%),
                        linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%);
            padding: 90px 0 70px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #dcfce7;
            color: #166534;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            border: 1px solid #bbf7d0;
            margin-bottom: 20px;
        }

        .hero-badge .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: var(--primary-light);
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulseGreen 1.8s infinite;
        }

        @keyframes pulseGreen {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .hero-title {
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1.15;
            color: #0f172a;
            letter-spacing: -1px;
            margin-bottom: 20px;
        }

        .hero-title span {
            background: linear-gradient(135deg, var(--primary-emerald), #047857);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 1.18rem;
            color: #475569;
            max-width: 580px;
            margin-bottom: 32px;
        }

        .hero-cta-group {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 40px;
        }

        .btn-cta-primary {
            background: linear-gradient(135deg, var(--primary-emerald), var(--primary-dark));
            color: #ffffff;
            font-weight: 700;
            font-size: 1.05rem;
            padding: 14px 28px;
            border-radius: 12px;
            box-shadow: 0 10px 20px var(--primary-glow);
            transition: var(--transition-smooth);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-cta-primary:hover {
            color: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(5, 150, 105, 0.35);
        }

        .btn-cta-cart {
            background: #ffffff;
            color: var(--primary-dark);
            border: 2px solid var(--primary-light);
            font-weight: 700;
            font-size: 1.05rem;
            padding: 14px 26px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: var(--transition-smooth);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .btn-cta-cart:hover {
            background: #ecfdf5;
            color: var(--primary-emerald);
            transform: translateY(-3px);
        }

        .hero-image-card {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
            border: 4px solid #ffffff;
        }

        .hero-image-card img {
            width: 100%;
            height: 440px;
            object-fit: cover;
            display: block;
            transition: transform 0.6s ease;
        }

        .hero-image-card:hover img {
            transform: scale(1.03);
        }

        .hero-floating-badge {
            position: absolute;
            bottom: 24px;
            left: 24px;
            right: 24px;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 16px 20px;
            border-radius: 16px;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Stats Bar */
        .stats-section {
            background-color: #ffffff;
            padding: 30px 0;
            margin-top: -35px;
            position: relative;
            z-index: 20;
        }

        .stats-wrapper {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 25px 20px;
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.06);
        }

        .stat-item {
            text-align: center;
            padding: 10px 15px;
            border-right: 1px solid var(--border-color);
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary-emerald);
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-label {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Section Headings */
        .section-header {
            text-align: center;
            max-width: 680px;
            margin: 0 auto 50px auto;
        }

        .section-tag {
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--primary-emerald);
            margin-bottom: 8px;
            display: inline-block;
        }

        .section-title {
            font-size: 2.3rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            line-height: 1.25;
            margin-bottom: 14px;
        }

        .section-desc {
            font-size: 1.05rem;
            color: var(--text-muted);
        }

        /* About Us Cards */
        .about-section {
            padding: 80px 0;
            background-color: var(--light-bg);
        }

        .mv-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--card-radius);
            padding: 35px 30px;
            height: 100%;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .mv-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-emerald), var(--accent-gold));
        }

        .mv-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.08);
            border-color: #cbd5e1;
        }

        .mv-icon-box {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            background: #ecfdf5;
            color: var(--primary-emerald);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 22px;
        }

        .pillar-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px 20px;
            transition: var(--transition-smooth);
            height: 100%;
        }

        .pillar-card:hover {
            border-color: var(--primary-light);
            background: #f0fdf4;
            transform: translateY(-3px);
        }

        /* Products Catalog Section */
        .products-section {
            padding: 90px 0;
            background: #ffffff;
        }

        .category-tabs {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 45px;
        }

        .category-tab-btn {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            font-weight: 700;
            font-size: 0.92rem;
            padding: 10px 22px;
            border-radius: 999px;
            transition: var(--transition-smooth);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .category-tab-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .category-tab-btn.active {
            background: linear-gradient(135deg, var(--primary-emerald), var(--primary-dark));
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 4px 14px var(--primary-glow);
        }

        .product-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--card-radius);
            overflow: hidden;
            transition: var(--transition-smooth);
            display: flex;
            flex-direction: column;
            height: 100%;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .product-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 22px 35px -10px rgba(0, 0, 0, 0.12);
            border-color: #86efac;
        }

        .product-img-wrapper {
            position: relative;
            height: 200px;
            overflow: hidden;
            background: #e2e8f0;
        }

        .product-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-img-wrapper img {
            transform: scale(1.08);
        }

        .product-badge-cat {
            position: absolute;
            top: 14px;
            left: 14px;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(6px);
            color: #ffffff;
            font-size: 0.76rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-badge-id {
            position: absolute;
            top: 14px;
            right: 14px;
            background: rgba(255, 255, 255, 0.92);
            color: #0f172a;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .product-badge-promo {
            position: absolute;
            bottom: 12px;
            left: 14px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #ffffff;
            font-size: 0.76rem;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.35);
        }

        .price-old {
            font-size: 1.05rem;
            color: #94a3b8;
            font-weight: 700;
            text-decoration: line-through;
            margin-left: 4px;
        }

        .product-recipe-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 3px solid var(--primary-emerald);
            border-radius: 10px;
            padding: 10px 12px;
            margin: 10px 0 14px 0;
            font-size: 0.82rem;
        }

        .product-recipe-title {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 3px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
        }

        .product-recipe-desc {
            color: #475569;
            line-height: 1.35;
            font-size: 0.77rem;
        }

        .product-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .product-stage {
            font-size: 0.85rem;
            color: #059669;
            font-weight: 700;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .product-protein {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 18px;
            display: inline-block;
        }

        .product-pricing-box {
            margin-top: auto;
            padding-top: 18px;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .price-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .price-val {
            font-size: 1.9rem;
            font-weight: 800;
            color: var(--primary-dark);
            line-height: 1;
        }

        .price-val span {
            font-size: 1.05rem;
            font-weight: 700;
            color: #059669;
        }

        .price-unit {
            font-size: 0.78rem;
            color: #64748b;
            font-weight: 500;
        }

        /* Stepper & Action Controls */
        .stepper-box {
            display: flex;
            align-items: center;
            background: #f1f5f9;
            border-radius: 10px;
            padding: 2px;
            width: 120px;
        }

        .stepper-btn {
            border: none;
            background: #ffffff;
            color: #0f172a;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: var(--transition-smooth);
        }

        .stepper-btn:hover {
            background: var(--primary-emerald);
            color: #ffffff;
        }

        .stepper-input {
            width: 50px;
            border: none;
            background: transparent;
            text-align: center;
            font-weight: 700;
            color: #0f172a;
            font-size: 0.95rem;
        }

        .stepper-input:focus {
            outline: none;
        }

        .btn-add-cart {
            background: linear-gradient(135deg, var(--primary-emerald), var(--primary-dark));
            color: #ffffff;
            border: none;
            font-weight: 700;
            font-size: 0.92rem;
            padding: 10px 16px;
            border-radius: 10px;
            transition: var(--transition-smooth);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            flex-grow: 1;
            box-shadow: 0 4px 12px var(--primary-glow);
        }

        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(5, 150, 105, 0.4);
            color: #ffffff;
        }

        /* Plans Section */
        .plans-section {
            padding: 90px 0;
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        }

        .plan-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 22px;
            padding: 36px 30px;
            height: 100%;
            transition: var(--transition-smooth);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .plan-card.featured {
            border: 2px solid var(--primary-light);
            box-shadow: 0 20px 40px -10px var(--primary-glow);
            transform: scale(1.02);
            background: #ffffff;
        }

        .plan-ribbon {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-warm));
            color: #ffffff;
            font-size: 0.78rem;
            font-weight: 800;
            padding: 5px 16px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 10px rgba(245, 158, 11, 0.4);
        }

        .plan-name {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .plan-desc {
            font-size: 0.92rem;
            color: var(--text-muted);
            margin-bottom: 24px;
            min-height: 42px;
        }

        .plan-price-box {
            margin-bottom: 26px;
        }

        .plan-amount {
            font-size: 2.6rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .plan-amount span {
            font-size: 1.2rem;
            color: var(--primary-emerald);
        }

        .plan-period {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        .plan-features-list {
            list-style: none;
            padding: 0;
            margin: 0 0 32px 0;
            flex-grow: 1;
        }

        .plan-features-list li {
            font-size: 0.92rem;
            color: #334155;
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .plan-features-list li i {
            color: var(--primary-emerald);
            margin-top: 4px;
            font-size: 0.95rem;
        }

        .btn-wompi-plan {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            color: #ffffff;
            border: none;
            width: 100%;
            padding: 13px 20px;
            border-radius: 12px;
            font-weight: 700;
            transition: var(--transition-smooth);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
            margin-bottom: 10px;
        }

        .btn-wompi-plan:hover {
            background: linear-gradient(135deg, #0369a1, #075985);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(2, 132, 199, 0.4);
        }

        .btn-cart-plan {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            color: #334155;
            width: 100%;
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: var(--transition-smooth);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
        }

        .btn-cart-plan:hover {
            background: #ecfdf5;
            color: var(--primary-dark);
            border-color: var(--primary-light);
        }

        /* Quality Banner */
        .quality-banner {
            background: radial-gradient(circle at top, #064e3b, #0f172a);
            color: #ffffff;
            padding: 70px 0;
            position: relative;
            overflow: hidden;
        }

        .quality-banner::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.2), transparent 70%);
            pointer-events: none;
        }

        /* Contact Section */
        .contact-section {
            padding: 90px 0;
            background: #ffffff;
        }

        .contact-info-card {
            background: var(--light-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--card-radius);
            padding: 35px 30px;
            height: 100%;
        }

        .contact-method-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 24px;
        }

        .contact-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #ecfdf5;
            color: var(--primary-emerald);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .contact-form-box {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--card-radius);
            padding: 35px 30px;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
        }

        .form-control, .form-select {
            padding: 12px 16px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px var(--primary-glow);
        }

        /* Footer */
        .site-footer {
            background-color: #0b1320;
            color: #94a3b8;
            padding: 60px 0 25px 0;
            border-top: 1px solid #1e293b;
        }

        .footer-logo img {
            height: 46px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .footer-title {
            color: #ffffff;
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            transition: var(--transition-smooth);
            font-size: 0.92rem;
        }

        .footer-links a:hover {
            color: var(--primary-light);
            padding-left: 4px;
        }

        .social-link {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #1e293b;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            transition: var(--transition-smooth);
            text-decoration: none;
        }

        .social-link:hover {
            background: var(--primary-emerald);
            color: #ffffff;
            transform: translateY(-3px);
        }

        /* Floating Buttons */
        .floating-whatsapp {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 60px;
            height: 60px;
            background-color: #25d366;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            transition: var(--transition-smooth);
            text-decoration: none;
        }

        .floating-whatsapp:hover {
            transform: scale(1.1) rotate(6deg);
            color: #ffffff;
            box-shadow: 0 12px 30px rgba(37, 211, 102, 0.6);
        }

        .floating-cart-btn {
            position: fixed;
            bottom: 25px;
            left: 25px;
            width: 62px;
            height: 62px;
            background: linear-gradient(135deg, var(--primary-emerald), var(--primary-dark));
            color: #ffffff;
            border-radius: 50%;
            border: 3px solid #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 10px 25px rgba(5, 150, 105, 0.45);
            z-index: 1000;
            cursor: pointer;
            transition: var(--transition-smooth);
        }

        .floating-cart-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 15px 35px rgba(5, 150, 105, 0.6);
        }

        .floating-cart-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background-color: #ef4444;
            color: #ffffff;
            font-size: 0.78rem;
            font-weight: 800;
            padding: 4px 8px;
            border-radius: 999px;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        /* Offcanvas Cart Styling */
        .offcanvas-cart {
            width: 440px !important;
            max-width: 95vw;
        }

        .cart-item-row {
            padding: 16px 0;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .cart-item-img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1px solid #e2e8f0;
        }

        .cart-item-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .cart-item-price {
            font-size: 0.85rem;
            color: var(--primary-emerald);
            font-weight: 700;
        }

        .cart-item-subtotal {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            text-align: right;
        }

        .cart-summary-box {
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 18px;
            margin-top: 15px;
        }

        .btn-wompi-checkout {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            color: #ffffff;
            font-weight: 800;
            font-size: 1.05rem;
            padding: 14px 20px;
            border-radius: 12px;
            border: none;
            width: 100%;
            transition: var(--transition-smooth);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35);
        }

        .btn-wompi-checkout:hover {
            background: linear-gradient(135deg, #0369a1, #075985);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(2, 132, 199, 0.45);
        }

        .btn-whatsapp-checkout {
            background: #25d366;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 12px 18px;
            border-radius: 12px;
            border: none;
            width: 100%;
            transition: var(--transition-smooth);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
            text-decoration: none;
        }

        .btn-whatsapp-checkout:hover {
            background: #1eb956;
            color: #ffffff;
        }

        @media (max-width: 991px) {
            .hero-title {
                font-size: 2.4rem;
            }
            .hero-image-card {
                margin-top: 40px;
            }
            .stat-item {
                border-right: none;
                border-bottom: 1px solid var(--border-color);
                padding: 15px 0;
            }
            .stat-item:last-child {
                border-bottom: none;
            }
            .plan-card.featured {
                transform: none;
            }
        }
    </style>
</head>
<body>

    <!-- Top Announcement Bar -->
    <div class="top-announcement">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <i class="fas fa-truck-fast me-2 text-warning"></i>
                    <span>Despachos y pedidos en todo El Salvador | <strong>Pago seguro con Wompi o Pedido a WhatsApp</strong></span>
                </div>
                <div class="d-none d-md-flex align-items-center gap-3">
                    <span><i class="fas fa-phone-volume me-1"></i> <?= htmlspecialchars($infoEmpresa['contacto']['telefono']) ?></span>
                    <span><i class="fab fa-whatsapp me-1 text-success"></i> <?= htmlspecialchars($infoEmpresa['contacto']['telefono_movil']) ?></span>
                    <a href="#contacto"><i class="fas fa-location-dot me-1"></i> Ver Ubicación</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg sticky-top custom-navbar" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-3" href="index.php">
                <img src="views/Recursos/logo.jpg" alt="Logo Concentrados El Gordito" class="img-fluid">
                <div>
                    <div class="navbar-brand-text">Concentrados El Gordito</div>
                    <div class="navbar-brand-subtitle">Nutrición Animal de Alto Rendimiento</div>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Navegación">
                <i class="fas fa-bars fs-4 text-dark"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#empresa">Sobre Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#productos">Fórmulas & Precios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#planes">Planes de Servicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#calidad">Calidad</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <!-- Botón Carrito en Navbar -->
                    <button type="button" class="btn-nav-cart" onclick="abrirCarrito()" id="btnNavCarrito" title="Abrir Carrito de Compras">
                        <i class="fas fa-shopping-cart text-success fs-5"></i>
                        <span>Carrito</span>
                        <span class="badge bg-danger rounded-pill px-2 py-1" id="badgeContadorNavbar">0</span>
                    </button>

                    <!-- Acceso al Sistema -->
                    <a href="controllers/controlUser.php" class="btn-portal" id="btnAccesoSistema">
                        <i class="fas fa-right-to-bracket"></i>
                        <span>Acceso al Sistema</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="inicio">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="hero-badge">
                        <span class="pulse-dot"></span>
                        <span>Planta Agroindustrial • 100% Calidad Salvadoreña</span>
                    </div>
                    <h1 class="hero-title">
                        Nutrición Superior para <span>Aves, Cerdos y Ganado</span>
                    </h1>
                    <p class="hero-desc">
                        <?= htmlspecialchars($infoEmpresa['resumen']) ?>
                    </p>

                    <div class="hero-cta-group">
                        <a href="#productos" class="btn-cta-primary">
                            <i class="fas fa-bag-shopping"></i>
                            <span>Ver Catálogo & Precios</span>
                        </a>
                        <button type="button" class="btn-cta-cart" onclick="abrirCarrito()">
                            <i class="fas fa-shopping-cart text-success"></i>
                            <span>Mi Carrito (<span class="badge-hero-count">0</span>)</span>
                        </button>
                    </div>

                    <div class="d-flex align-items-center gap-4 text-muted small pt-2 flex-wrap">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-shield-halved text-success fs-5"></i>
                            <span>Fórmulas Balanceadas</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-credit-card text-primary fs-5"></i>
                            <span>Pago Wompi en Línea</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-truck-ramp-box text-success fs-5"></i>
                            <span>Despacho a Granja</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="hero-image-card">
                        <img src="views/Recursos/vaca.jpg" alt="Ganadería y Concentrados El Gordito">
                        <div class="hero-floating-badge">
                            <div>
                                <div class="fw-bold fs-6 text-white mb-0">Concentrados El Gordito</div>
                                <div class="small text-white-50">Máximo rendimiento en campo</div>
                            </div>
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i> Precios de Fábrica
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <div class="stats-section">
        <div class="container">
            <div class="stats-wrapper">
                <div class="row g-3">
                    <div class="col-6 col-lg-3 stat-item">
                        <div class="stat-number">+<?= htmlspecialchars($estadisticas['total_formulas']) ?></div>
                        <div class="stat-label">Fórmulas Especializadas</div>
                    </div>
                    <div class="col-6 col-lg-3 stat-item">
                        <div class="stat-number">+<?= htmlspecialchars($estadisticas['anios_experiencia']) ?></div>
                        <div class="stat-label">Años de Trayectoria</div>
                    </div>
                    <div class="col-6 col-lg-3 stat-item">
                        <div class="stat-number"><?= htmlspecialchars($estadisticas['departamentos']) ?></div>
                        <div class="stat-label">Departamentos Atendidos</div>
                    </div>
                    <div class="col-6 col-lg-3 stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Trazabilidad en Lotes</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section (Empresa, Misión, Visión, Pilares) -->
    <section class="about-section" id="empresa">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Nuestra Empresa</span>
                <h2 class="section-title">Comprometidos con el Crecimiento del Productor Agropecuario</h2>
                <p class="section-desc">
                    Diseñamos cada fórmula pensando en la salud animal, el índice de conversión alimenticia y el retorno económico de su inversión.
                </p>
            </div>

            <!-- Misión y Visión -->
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="mv-card">
                        <div class="mv-icon-box">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="h4 fw-bold mb-3 text-dark">Nuestra Misión</h3>
                        <p class="text-secondary mb-0 leading-relaxed">
                            <?= htmlspecialchars($infoEmpresa['mision']) ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mv-card">
                        <div class="mv-icon-box" style="background:#fef3c7; color:#d97706;">
                            <i class="fas fa-compass"></i>
                        </div>
                        <h3 class="h4 fw-bold mb-3 text-dark">Nuestra Visión</h3>
                        <p class="text-secondary mb-0 leading-relaxed">
                            <?= htmlspecialchars($infoEmpresa['vision']) ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- 4 Pilares -->
            <div class="row g-3">
                <?php foreach ($infoEmpresa['pilares'] as $pilar): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="pillar-card">
                            <div class="text-success mb-3 fs-3">
                                <i class="<?= htmlspecialchars($pilar['icono']) ?>"></i>
                            </div>
                            <h4 class="h6 fw-bold text-dark mb-2"><?= htmlspecialchars($pilar['titulo']) ?></h4>
                            <p class="text-muted small mb-0"><?= htmlspecialchars($pilar['descripcion']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Products and Prices Section -->
    <section class="products-section" id="productos">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Catálogo Oficial</span>
                <h2 class="section-title">Nuestras Fórmulas Concentradas y Precios</h2>
                <p class="section-desc">
                    Precios directos de planta. Agrega al carrito las fórmulas deseadas para pagar con Wompi o confirmar por WhatsApp.
                </p>
            </div>

            <!-- Filter Tabs -->
            <div class="category-tabs" id="filtroCategorias">
                <button type="button" class="category-tab-btn active" data-filtro="todos">
                    <i class="fas fa-border-all"></i>
                    <span>Todos los Productos (<?= count($catalogoProductos) ?>)</span>
                </button>
                <button type="button" class="category-tab-btn" data-filtro="aves">
                    <i class="fas fa-feather-alt"></i>
                    <span>Aves de Corral</span>
                </button>
                <button type="button" class="category-tab-btn" data-filtro="cerdos">
                    <i class="fas fa-paw"></i>
                    <span>Porcinos / Cerdos</span>
                </button>
                <button type="button" class="category-tab-btn" data-filtro="ganado">
                    <i class="fas fa-hat-cowboy"></i>
                    <span>Ganado Bovino</span>
                </button>
                <button type="button" class="category-tab-btn" data-filtro="balanceados">
                    <i class="fas fa-seedling"></i>
                    <span>Balanceados Especiales</span>
                </button>
            </div>

            <!-- Product Cards Grid -->
            <div class="row g-4" id="contenedorProductos">
                <?php foreach ($catalogoProductos as $prod): ?>
                    <div class="col-md-6 col-lg-4 item-producto" data-categoria="<?= htmlspecialchars($prod['filtro']) ?>">
                        <div class="product-card">
                            <div class="product-img-wrapper">
                                <img src="<?= htmlspecialchars($prod['imagen']) ?>" alt="<?= htmlspecialchars($prod['nombre']) ?>" loading="lazy">
                                <span class="product-badge-cat">
                                    <i class="<?= htmlspecialchars($prod['icono']) ?> me-1"></i>
                                    <?= htmlspecialchars($prod['categoria']) ?>
                                </span>
                                <span class="product-badge-id">Fórmula #<?= htmlspecialchars($prod['id']) ?></span>
                                <?php if (!empty($prod['en_promocion'])): ?>
                                    <span class="product-badge-promo">
                                        <i class="fas fa-fire me-1"></i> -<?= $prod['porcentaje_descuento'] ?>% OFERTA HOY
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="product-body">
                                <h3 class="product-title"><?= htmlspecialchars($prod['nombre']) ?></h3>
                                <div class="product-stage">
                                    <i class="fas fa-circle-check"></i>
                                    <span><?= htmlspecialchars($prod['etapa']) ?></span>
                                </div>
                                <div class="product-protein">
                                    <i class="fas fa-bolt me-1 text-warning"></i> <?= htmlspecialchars($prod['proteina']) ?>
                                </div>

                                <!-- Fórmula y Materias Primas de la Receta en BD -->
                                <div class="product-recipe-box">
                                    <div class="product-recipe-title">
                                        <i class="fas fa-mortar-pestle text-success"></i>
                                        <span>Fórmula y Receta en BD:</span>
                                    </div>
                                    <div class="product-recipe-desc">
                                        <?= htmlspecialchars($prod['formula']) ?>
                                    </div>
                                </div>

                                <div class="product-pricing-box">
                                    <div>
                                        <div class="price-label">
                                            Precio Unitario
                                            <?php if (!empty($prod['en_promocion'])): ?>
                                                <span class="badge bg-danger ms-1 text-uppercase" style="font-size: 0.62rem;">¡En Oferta!</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex align-items-baseline">
                                            <div class="price-val">
                                                <span>$</span><?= htmlspecialchars($prod['precio_formato']) ?>
                                            </div>
                                            <?php if (!empty($prod['en_promocion']) && $prod['precio_anterior'] > 0): ?>
                                                <span class="price-old" title="Precio Regular Anterior">
                                                    $<?= htmlspecialchars($prod['precio_anterior_formato']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($prod['en_promocion']) && $prod['precio_anterior'] > 0): ?>
                                            <div class="text-danger fw-bold mt-1" style="font-size: 0.76rem;">
                                                <i class="fas fa-tag me-1"></i>Ahorras $<?= htmlspecialchars($prod['ahorro_formato']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-end">
                                        <div class="price-unit text-muted"><?= htmlspecialchars($prod['unidad']) ?></div>
                                        <span class="badge bg-light text-success border border-success-subtle px-2 py-1 small">
                                            Stock Inmediato
                                        </span>
                                    </div>
                                </div>

                                <!-- Stepper y Botón Agregar al Carrito -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="stepper-box">
                                        <button type="button" class="stepper-btn" onclick="ajustarStepper('cant-prod-<?= $prod['id'] ?>', -1)">-</button>
                                        <input type="number" id="cant-prod-<?= $prod['id'] ?>" class="stepper-input" value="1" min="1" max="999">
                                        <button type="button" class="stepper-btn" onclick="ajustarStepper('cant-prod-<?= $prod['id'] ?>', 1)">+</button>
                                    </div>
                                    <button type="button" class="btn-add-cart" onclick="agregarProductoAlCarrito(<?= $prod['id'] ?>, '<?= addslashes($prod['nombre']) ?>', <?= $prod['precio'] ?>, '<?= $prod['imagen'] ?>', '<?= addslashes($prod['unidad']) ?>')">
                                        <i class="fas fa-cart-plus"></i>
                                        <span>Al Carrito</span>
                                    </button>
                                </div>

                                <a href="https://wa.me/50378905678?text=Hola%20Concentrados%20El%20Gordito%2C%20deseo%20cotizar%20la%20f%C3%B3rmula%20<?= urlencode($prod['nombre']) ?>%20(Precio%3A%20%24<?= $prod['precio_formato'] ?>)" target="_blank" class="text-muted small text-center text-decoration-none mt-1">
                                    <i class="fab fa-whatsapp text-success me-1"></i> Consultar dudas por WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Plans Section (from plan_pago) -->
    <section class="plans-section" id="planes">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Planes Comerciales</span>
                <h2 class="section-title">Planes de Producción, Despacho y Asesoría</h2>
                <p class="section-desc">
                    Elige el plan que mejor se adapte a tu operación. Puedes <strong>pagar directamente en línea con Wompi</strong> o agregarlo a tu carrito.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                <?php foreach ($planesServicio as $plan): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="plan-card <?= $plan['destacado'] ? 'featured' : '' ?>">
                            <?php if ($plan['destacado']): ?>
                                <div class="plan-ribbon"><?= htmlspecialchars($plan['badge']) ?></div>
                            <?php endif; ?>

                            <div class="plan-name"><?= htmlspecialchars($plan['nombre']) ?></div>
                            <div class="plan-desc"><?= htmlspecialchars($plan['resumen']) ?></div>

                            <div class="plan-price-box">
                                <div class="plan-amount">
                                    <span>$</span><?= htmlspecialchars($plan['monto_formato']) ?>
                                </div>
                                <div class="plan-period">Facturación cada <?= htmlspecialchars($plan['duracion_dias']) ?> días</div>
                            </div>

                            <ul class="plan-features-list">
                                <?php foreach ($plan['caracteristicas'] as $feat): ?>
                                    <li>
                                        <i class="fas fa-check-circle"></i>
                                        <span><?= htmlspecialchars($feat) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <!-- Botón 1: Comprar directo con Wompi -->
                            <button type="button" class="btn-wompi-plan" onclick="iniciarPagoPlanWompi(<?= $plan['id'] ?>, '<?= addslashes($plan['nombre']) ?>', <?= $plan['monto'] ?>)">
                                <i class="fas fa-credit-card"></i>
                                <span>Comprar con Wompi ($<?= $plan['monto_formato'] ?>)</span>
                            </button>

                            <!-- Botón 2: Agregar Plan al Carrito -->
                            <button type="button" class="btn-cart-plan" onclick="agregarPlanAlCarrito(<?= $plan['id'] ?>, '<?= addslashes($plan['nombre']) ?>', <?= $plan['monto'] ?>)">
                                <i class="fas fa-cart-plus text-success"></i>
                                <span>Agregar Plan al Carrito</span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Quality & Process Banner -->
    <section class="quality-banner" id="calidad">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-success px-3 py-2 mb-3 text-uppercase fw-bold">Estándar de Inocuidad</span>
                    <h2 class="display-6 fw-bold mb-3">¿Por Qué Elegir Fórmulas Concentradas El Gordito?</h2>
                    <p class="lead text-white-50 mb-4">
                        Nuestros procesos de molienda y mezclado homogéneo garantizan que cada porción entregue exactamente los niveles requeridos de aminoácidos, energía metabolizable, fósforo y calcio para un desarrollo animal vigoroso.
                    </p>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-check text-warning fs-4"></i>
                                <span>Menor costo de conversión por libra de carne</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-check text-warning fs-4"></i>
                                <span>Análisis de laboratorio en materias primas</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-check text-warning fs-4"></i>
                                <span>Disponibilidad inmediata los 365 días</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-check text-warning fs-4"></i>
                                <span>Atención técnica directa en su granja</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="p-4 rounded-4" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                        <i class="fas fa-award text-warning display-4 mb-3"></i>
                        <h3 class="h5 fw-bold text-white">Garantía Concentrados El Gordito</h3>
                        <p class="text-white-50 small mb-3">Compromiso con el productor salvadoreño desde el primer saco.</p>
                        <a href="controllers/controlUser.php" class="btn btn-warning fw-bold px-4 py-2 w-100 rounded-pill">
                            Ingresar al Sistema
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact & Location Section -->
    <section class="contact-section" id="contacto">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Canales Directos</span>
                <h2 class="section-title">Contáctanos y Haz tu Pedido Hoy</h2>
                <p class="section-desc">
                    Estamos listos para atender cotizaciones, pedidos al por mayor o coordinar visitas de asesoría técnica.
                </p>
            </div>

            <div class="row g-4">
                <!-- Info Cards -->
                <div class="col-lg-5">
                    <div class="contact-info-card">
                        <h3 class="h5 fw-bold text-dark mb-4">Información de Atención</h3>

                        <div class="contact-method-item">
                            <div class="contact-icon-box">
                                <i class="fas fa-location-dot"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">Planta Principal</div>
                                <div class="text-muted small"><?= htmlspecialchars($infoEmpresa['contacto']['direccion']) ?></div>
                            </div>
                        </div>

                        <div class="contact-method-item">
                            <div class="contact-icon-box">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">Línea Telefónica PBX</div>
                                <div class="text-muted small"><?= htmlspecialchars($infoEmpresa['contacto']['telefono']) ?></div>
                            </div>
                        </div>

                        <div class="contact-method-item">
                            <div class="contact-icon-box" style="background:#ecfdf5; color:#25d366;">
                                <i class="fab fa-whatsapp fs-4"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">WhatsApp Ventas Directas</div>
                                <div class="text-muted small"><?= htmlspecialchars($infoEmpresa['contacto']['telefono_movil']) ?> (Respuesta Rápida)</div>
                            </div>
                        </div>

                        <div class="contact-method-item">
                            <div class="contact-icon-box">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">Correo Electrónico</div>
                                <div class="text-muted small"><?= htmlspecialchars($infoEmpresa['contacto']['correo']) ?></div>
                            </div>
                        </div>

                        <div class="contact-method-item">
                            <div class="contact-icon-box">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">Horario de Despacho</div>
                                <div class="text-muted small"><?= htmlspecialchars($infoEmpresa['contacto']['horario']) ?></div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <a href="<?= htmlspecialchars($infoEmpresa['contacto']['whatsapp_enlace']) ?>" target="_blank" class="btn btn-success w-100 py-3 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2">
                                <i class="fab fa-whatsapp fs-5"></i>
                                <span>Escribir directamente al WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Interactive Inquiry Form -->
                <div class="col-lg-7">
                    <div class="contact-form-box">
                        <h3 class="h5 fw-bold text-dark mb-2">Solicitar Cotización Inmediata</h3>
                        <p class="text-muted small mb-4">Complete los datos y nuestro asesor comercial le responderá a la brevedad con los mejores precios por volumen.</p>

                        <form id="formularioCotizacion" onsubmit="enviarCotizacionWhatsApp(event)">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-secondary">Nombre o Razón Social</label>
                                    <input type="text" class="form-control" id="cotizaNombre" placeholder="Ej. Granja San Francisco / Juan Pérez" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-secondary">Teléfono o WhatsApp</label>
                                    <input type="tel" class="form-control" id="cotizaTelefono" placeholder="Ej. 7890-1234" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-secondary">Fórmula de Interés</label>
                                    <select class="form-select" id="cotizaProducto" required>
                                        <option value="">Seleccione una fórmula...</option>
                                        <?php foreach ($catalogoProductos as $prod): ?>
                                            <option value="<?= htmlspecialchars($prod['nombre']) ?> ($<?= $prod['precio_formato'] ?>)">
                                                <?= htmlspecialchars($prod['nombre']) ?> — $<?= $prod['precio_formato'] ?> / lb
                                            </option>
                                        <?php endforeach; ?>
                                        <option value="Cotización por Mayor / Varias Fórmulas">Pedido Mixto / Por Mayor</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-secondary">Departamento de Entrega</label>
                                    <select class="form-select" id="cotizaDepartamento" required>
                                        <option value="Santa Ana">Santa Ana</option>
                                        <option value="San Salvador">San Salvador</option>
                                        <option value="La Libertad">La Libertad</option>
                                        <option value="Sonsonate">Sonsonate</option>
                                        <option value="Ahuachapán">Ahuachapán</option>
                                        <option value="Chalatenango">Chalatenango</option>
                                        <option value="Cuscatlán">Cuscatlán</option>
                                        <option value="La Paz">La Paz</option>
                                        <option value="Cabañas">Cabañas</option>
                                        <option value="San Vicente">San Vicente</option>
                                        <option value="Usulután">Usulután</option>
                                        <option value="San Miguel">San Miguel</option>
                                        <option value="Morazán">Morazán</option>
                                        <option value="La Unión">La Unión</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-secondary">Detalle del Pedido o Consulta</label>
                                    <textarea class="form-control" id="cotizaMensaje" rows="3" placeholder="Indique cantidad estimada de sacos, dudas nutricionales o frecuencia requerida..."></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-cta-primary w-100 py-3 mt-2">
                                        <i class="fab fa-whatsapp fs-5"></i>
                                        <span>Enviar Consulta por WhatsApp</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <div class="footer-logo d-flex align-items-center gap-3">
                        <img src="views/Recursos/logo.jpg" alt="Logo">
                        <div>
                            <div class="text-white fw-bold fs-5">Concentrados El Gordito</div>
                            <div class="text-success small fw-semibold">Nutrición Animal Profesional</div>
                        </div>
                    </div>
                    <p class="small text-muted mb-4">
                        Fabricación y distribución de alimentos concentrados para el sector avícola, porcino y ganadero de El Salvador. Rendimiento comprobado en cada etapa de crecimiento.
                    </p>
                    <div>
                        <a href="https://www.facebook.com/Concentrados-el-Gordito-106721037742036/" target="_blank" class="social-link" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/concentradosel/" target="_blank" class="social-link" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="<?= htmlspecialchars($infoEmpresa['contacto']['whatsapp_enlace']) ?>" target="_blank" class="social-link" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <div class="col-6 col-lg-2">
                    <div class="footer-title">Navegación</div>
                    <ul class="footer-links">
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#empresa">Sobre Nosotros</a></li>
                        <li><a href="#productos">Fórmulas & Precios</a></li>
                        <li><a href="#planes">Planes</a></li>
                        <li><a href="#calidad">Estándar de Calidad</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="footer-title">Fórmulas Destacadas</div>
                    <ul class="footer-links">
                        <li><a href="#productos">Mezcla Pollo Inicio & Engorde</a></li>
                        <li><a href="#productos">Mezcla Cerdo Engorde & Final</a></li>
                        <li><a href="#productos">Mezcla Ganado Lechero & Carne</a></li>
                        <li><a href="#productos">Mezcla Balanceada Multiespecie</a></li>
                        <li><a href="#planes">Planes de Suministro por Lote</a></li>
                    </ul>
                </div>

                <div class="col-lg-3">
                    <div class="footer-title">Portal del Sistema</div>
                    <p class="small text-muted mb-3">
                        Acceso exclusivo para personal operativo, clientes registrados y administración de pedidos.
                    </p>
                    <a href="controllers/controlUser.php" class="btn btn-outline-success w-100 py-2 fw-bold text-white border-success">
                        <i class="fas fa-lock me-2"></i> Iniciar Sesión
                    </a>
                </div>
            </div>

            <div class="pt-4 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center flex-wrap gap-2 text-muted small">
                <div>
                    &copy; <?= date('Y') ?> Concentrados El Gordito. Todos los derechos reservados. El Salvador.
                </div>
                <div>
                    Pasarela Wompi integrada • Carrito interactivo
                </div>
            </div>
        </div>
    </footer>

    <!-- ============================================================
         MODAL REGISTRO / LOGIN OBLIGATORIO ANTES DE PAGAR
         ============================================================ -->
    <div id="modalRegistroLanding" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.65); backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:20px;">
        <div style="background:#fff; border-radius:24px; max-width:460px; width:100%; box-shadow:0 30px 60px rgba(0,0,0,0.3); overflow:hidden; animation:slideUp .4s ease; font-family:'Plus Jakarta Sans',sans-serif;">

            <!-- Header -->
            <div style="background:linear-gradient(135deg,#064e3b,#059669); padding:28px 32px 20px; text-align:center;">
                <div style="width:64px;height:64px;background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.3);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:28px;color:#fff;">
                    <i class="fas fa-lock-open"></i>
                </div>
                <h5 style="color:#fff;font-weight:800;font-size:1.2rem;margin:0 0 4px;">Registrate para continuar</h5>
                <p style="color:rgba(255,255,255,.8);font-size:.85rem;margin:0;">Necesitamos tu cuenta para procesar el pago y guardar tu pedido.</p>
            </div>

            <!-- Tabs -->
            <div style="display:flex; border-bottom:2px solid #e2e8f0;">
                <button id="tabRegistroBtn" onclick="cambiarTabRegistro('registro')" style="flex:1;padding:14px;font-weight:700;font-size:.9rem;border:none;background:#f0fdf4;color:#059669;cursor:pointer;transition:.2s;">
                    <i class="fas fa-user-plus me-1"></i>Crear cuenta
                </button>
                <button id="tabLoginBtn" onclick="cambiarTabRegistro('login')" style="flex:1;padding:14px;font-weight:700;font-size:.9rem;border:none;background:#fff;color:#64748b;cursor:pointer;transition:.2s;">
                    <i class="fas fa-sign-in-alt me-1"></i>Ya tengo cuenta
                </button>
            </div>

            <!-- Form Body -->
            <div style="padding:28px 32px 24px;">

                <!-- Tab Registro -->
                <div id="tabRegistroContent">
                    <div id="errorRegistro" style="display:none;background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:10px 14px;border-radius:10px;font-size:.85rem;margin-bottom:16px;"></div>
                    <div style="margin-bottom:14px;">
                        <label style="font-size:.8rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.3px;display:block;margin-bottom:6px;">Nombre completo</label>
                        <input id="regNombre" type="text" placeholder="Tu nombre o nombre de granja" style="width:100%;padding:12px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:.95rem;font-family:inherit;outline:none;box-sizing:border-box;">
                    </div>
                    <div style="margin-bottom:14px;">
                        <label style="font-size:.8rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.3px;display:block;margin-bottom:6px;">Teléfono / WhatsApp</label>
                        <input id="regTelefono" type="tel" placeholder="7890-1234" style="width:100%;padding:12px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:.95rem;font-family:inherit;outline:none;box-sizing:border-box;">
                    </div>
                    <div style="margin-bottom:14px;">
                        <label style="font-size:.8rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.3px;display:block;margin-bottom:6px;">Correo electrónico</label>
                        <input id="regCorreo" type="email" placeholder="correo@empresa.com" style="width:100%;padding:12px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:.95rem;font-family:inherit;outline:none;box-sizing:border-box;">
                    </div>
                    <div style="margin-bottom:20px;">
                        <label style="font-size:.8rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.3px;display:block;margin-bottom:6px;">Contraseña (mín. 6 caracteres)</label>
                        <input id="regClave" type="password" placeholder="••••••••" style="width:100%;padding:12px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:.95rem;font-family:inherit;outline:none;box-sizing:border-box;">
                    </div>
                    <button onclick="enviarRegistro()" id="btnRegistrarse" style="width:100%;padding:14px;background:linear-gradient(135deg,#059669,#064e3b);color:#fff;border:none;border-radius:12px;font-size:1rem;font-weight:700;cursor:pointer;font-family:inherit;transition:.2s;">
                        <i class="fas fa-user-check me-2"></i>Crear cuenta y continuar al pago
                    </button>
                </div>

                <!-- Tab Login -->
                <div id="tabLoginContent" style="display:none;">
                    <div id="errorLogin" style="display:none;background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:10px 14px;border-radius:10px;font-size:.85rem;margin-bottom:16px;"></div>
                    <div style="margin-bottom:14px;">
                        <label style="font-size:.8rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.3px;display:block;margin-bottom:6px;">Correo electrónico</label>
                        <input id="loginCorreo" type="email" placeholder="correo@empresa.com" style="width:100%;padding:12px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:.95rem;font-family:inherit;outline:none;box-sizing:border-box;">
                    </div>
                    <div style="margin-bottom:20px;">
                        <label style="font-size:.8rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.3px;display:block;margin-bottom:6px;">Contraseña</label>
                        <input id="loginClave" type="password" placeholder="••••••••" style="width:100%;padding:12px 16px;border:2px solid #e2e8f0;border-radius:10px;font-size:.95rem;font-family:inherit;outline:none;box-sizing:border-box;">
                    </div>
                    <button onclick="enviarLogin()" id="btnLogin" style="width:100%;padding:14px;background:linear-gradient(135deg,#059669,#064e3b);color:#fff;border:none;border-radius:12px;font-size:1rem;font-weight:700;cursor:pointer;font-family:inherit;transition:.2s;">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión y continuar al pago
                    </button>
                </div>
            </div>

            <!-- Footer del modal -->
            <div style="padding:0 32px 24px;text-align:center;">
                <button onclick="cerrarModalRegistro()" style="background:none;border:none;color:#94a3b8;font-size:.85rem;cursor:pointer;font-family:inherit;">
                    <i class="fas fa-times me-1"></i>Cancelar y seguir viendo
                </button>
            </div>
        </div>
    </div>

    <!-- Floating WhatsApp Widget -->
    <a href="<?= htmlspecialchars($infoEmpresa['contacto']['whatsapp_enlace']) ?>" target="_blank" class="floating-whatsapp" title="Contactar por WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Floating Shopping Cart Button -->
    <button type="button" class="floating-cart-btn" onclick="abrirCarrito()" title="Abrir Carrito de Compras">
        <i class="fas fa-shopping-cart"></i>
        <span class="floating-cart-badge" id="badgeContadorFlotante">0</span>
    </button>

    <!-- Offcanvas Shopping Cart Drawer -->
    <div class="offcanvas offcanvas-end offcanvas-cart" tabindex="-1" id="offcanvasCarrito" aria-labelledby="offcanvasCarritoLabel">
        <div class="offcanvas-header bg-light border-bottom">
            <h5 class="offcanvas-title fw-bold text-dark d-flex align-items-center gap-2" id="offcanvasCarritoLabel">
                <i class="fas fa-shopping-cart text-success"></i>
                <span>Tu Carrito</span>
                <span class="badge bg-success rounded-pill px-2 py-1 fs-6" id="badgeCartHeader">0</span>
            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>

        <div class="offcanvas-body d-flex flex-column">
            <!-- Empty State -->
            <div id="cartEmptyState" class="text-center py-5 d-none">
                <i class="fas fa-cart-arrow-down text-muted display-3 mb-3"></i>
                <h5 class="fw-bold text-dark">Tu carrito está vacío</h5>
                <p class="text-muted small mb-4">Añade concentrados o suscríbete a un plan para comenzar tu pedido.</p>
                <a href="#productos" class="btn btn-outline-success rounded-pill px-4" data-bs-dismiss="offcanvas">
                    Explorar Productos
                </a>
            </div>

            <!-- Items List -->
            <div id="cartItemsList" class="flex-grow-1 overflow-auto pe-1">
                <!-- Se inyecta dinámicamente con JS -->
            </div>

            <!-- Datos del Cliente para Checkout -->
            <div id="cartCustomerSection" class="mt-3 pt-3 border-top">
                <h6 class="fw-bold text-dark mb-2 small text-uppercase">Datos para la Entrega y Factura</h6>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <input type="text" id="cartClienteNombre" class="form-control form-control-sm" placeholder="Tu Nombre / Granja" required>
                    </div>
                    <div class="col-6">
                        <input type="tel" id="cartClienteTelefono" class="form-control form-control-sm" placeholder="Teléfono / WhatsApp" required>
                    </div>
                    <div class="col-12">
                        <input type="email" id="cartClienteCorreo" class="form-control form-control-sm" placeholder="Correo electrónico (para comprobante)">
                    </div>
                </div>

                <!-- Resumen de Costos -->
                <div class="cart-summary-box">
                    <div class="d-flex justify-content-between mb-1 small text-muted">
                        <span>Subtotal:</span>
                        <span class="fw-bold text-dark" id="cartSubtotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>Despacho:</span>
                        <span class="text-success fw-bold">Por coordinar / Granja</span>
                    </div>
                    <div class="d-flex justify-content-between pt-2 border-top fs-5 fw-bold text-dark">
                        <span>Total a Pagar:</span>
                        <span class="text-success" id="cartTotalUSD">$0.00</span>
                    </div>
                </div>

                <!-- Botones de Pago y Confirmación -->
                <div class="mt-3">
                    <!-- Pago Wompi -->
                    <button type="button" class="btn-wompi-checkout mb-2" onclick="procesarPagoCarritoWompi()" id="btnPagarWompi">
                        <i class="fas fa-credit-card"></i>
                        <span>Pagar con Wompi</span>
                    </button>

                    <!-- Confirmar por WhatsApp -->
                    <button type="button" class="btn-whatsapp-checkout" onclick="enviarPedidoCarritoWhatsApp()">
                        <i class="fab fa-whatsapp fs-5"></i>
                        <span>Confirmar Pedido por WhatsApp</span>
                    </button>

                    <div class="text-center mt-2">
                        <button type="button" class="btn btn-link text-danger text-decoration-none small p-0" onclick="vaciarCarritoConfirm()">
                            <i class="fas fa-trash-can me-1"></i> Vaciar Carrito
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ==========================================
        // GESTIÓN DEL CARRITO DE COMPRAS (LocalStorage)
        // ==========================================
        const CLAVE_STORAGE = 'concentrados_el_gordito_carrito';
        let carrito = [];
        let bsOffcanvasCarrito = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Offcanvas
            const offcanvasEl = document.getElementById('offcanvasCarrito');
            if (offcanvasEl) {
                bsOffcanvasCarrito = new bootstrap.Offcanvas(offcanvasEl);
            }

            // Cargar carrito desde LocalStorage
            cargarCarritoStorage();
            actualizarUI();

            // Cargar datos previos del cliente si existen
            const clienteGuardado = JSON.parse(localStorage.getItem('concentrados_cliente') || '{}');
            if (clienteGuardado.nombre) document.getElementById('cartClienteNombre').value = clienteGuardado.nombre;
            if (clienteGuardado.telefono) document.getElementById('cartClienteTelefono').value = clienteGuardado.telefono;
            if (clienteGuardado.correo) document.getElementById('cartClienteCorreo').value = clienteGuardado.correo;

            // Filtros de categoría de productos
            const tabs = document.querySelectorAll('.category-tab-btn');
            const items = document.querySelectorAll('.item-producto');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    const filtro = this.getAttribute('data-filtro');

                    items.forEach(item => {
                        if (filtro === 'todos' || item.getAttribute('data-categoria') === filtro) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });

        function cargarCarritoStorage() {
            try {
                const data = localStorage.getItem(CLAVE_STORAGE);
                carrito = data ? JSON.parse(data) : [];
            } catch (e) {
                carrito = [];
            }
        }

        function guardarCarritoStorage() {
            localStorage.setItem(CLAVE_STORAGE, JSON.stringify(carrito));
            actualizarUI();
        }

        function abrirCarrito() {
            actualizarUI();
            if (bsOffcanvasCarrito) {
                bsOffcanvasCarrito.show();
            }
        }

        function ajustarStepper(inputId, delta) {
            const input = document.getElementById(inputId);
            if (!input) return;
            let val = parseInt(input.value) || 1;
            val = Math.max(1, val + delta);
            input.value = val;
        }

        function agregarProductoAlCarrito(id, nombre, precio, imagen, unidad) {
            const inputId = 'cant-prod-' + id;
            const input = document.getElementById(inputId);
            const cantidad = input ? Math.max(1, parseInt(input.value) || 1) : 1;

            const indexExistente = carrito.findIndex(item => item.id === id && item.tipo === 'producto');

            if (indexExistente >= 0) {
                carrito[indexExistente].cantidad += cantidad;
            } else {
                carrito.push({
                    id: id,
                    tipo: 'producto',
                    nombre: nombre,
                    precio: parseFloat(precio),
                    cantidad: cantidad,
                    imagen: imagen,
                    unidad: unidad
                });
            }

            guardarCarritoStorage();

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `¡${cantidad}x ${nombre} agregado al carrito!`,
                showConfirmButton: false,
                timer: 2200,
                timerProgressBar: true
            });

            // Resetea stepper a 1
            if (input) input.value = 1;
        }

        function agregarPlanAlCarrito(id, nombre, monto) {
            const indexExistente = carrito.findIndex(item => item.id === id && item.tipo === 'plan');

            if (indexExistente >= 0) {
                carrito[indexExistente].cantidad += 1;
            } else {
                carrito.push({
                    id: id,
                    tipo: 'plan',
                    nombre: nombre,
                    precio: parseFloat(monto),
                    cantidad: 1,
                    imagen: 'views/Recursos/icon.jpg',
                    unidad: 'Suscripción / Mes'
                });
            }

            guardarCarritoStorage();

            Swal.fire({
                icon: 'success',
                title: 'Plan agregado al carrito',
                text: `${nombre} por $${parseFloat(monto).toFixed(2)} USD fue añadido. Puedes proceder al pago con Wompi desde tu carrito.`,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-shopping-cart"></i> Ver Carrito',
                cancelButtonText: 'Seguir Viendo',
                confirmButtonColor: '#059669'
            }).then((result) => {
                if (result.isConfirmed) {
                    abrirCarrito();
                }
            });
        }

        function cambiarCantidad(index, delta) {
            if (!carrito[index]) return;
            carrito[index].cantidad += delta;
            if (carrito[index].cantidad <= 0) {
                carrito.splice(index, 1);
            }
            guardarCarritoStorage();
        }

        function eliminarItem(index) {
            if (!carrito[index]) return;
            carrito.splice(index, 1);
            guardarCarritoStorage();
        }

        function vaciarCarritoConfirm() {
            if (carrito.length === 0) return;
            Swal.fire({
                title: '¿Vaciar carrito?',
                text: 'Se eliminarán todos los productos seleccionados.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, vaciar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    carrito = [];
                    guardarCarritoStorage();
                }
            });
        }

        function actualizarUI() {
            const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);
            const totalMonto = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);

            // Badges
            const badgeNav = document.getElementById('badgeContadorNavbar');
            if (badgeNav) badgeNav.textContent = totalItems;

            const badgeFlotante = document.getElementById('badgeContadorFlotante');
            if (badgeFlotante) badgeFlotante.textContent = totalItems;

            const badgeHeader = document.getElementById('badgeCartHeader');
            if (badgeHeader) badgeHeader.textContent = totalItems;

            const heroCount = document.querySelector('.badge-hero-count');
            if (heroCount) heroCount.textContent = totalItems;

            // Contenedores del offcanvas
            const emptyState = document.getElementById('cartEmptyState');
            const itemsList = document.getElementById('cartItemsList');
            const customerSection = document.getElementById('cartCustomerSection');
            const subtotalEl = document.getElementById('cartSubtotal');
            const totalEl = document.getElementById('cartTotalUSD');

            if (carrito.length === 0) {
                if (emptyState) emptyState.classList.remove('d-none');
                if (itemsList) itemsList.innerHTML = '';
                if (customerSection) customerSection.classList.add('d-none');
            } else {
                if (emptyState) emptyState.classList.add('d-none');
                if (customerSection) customerSection.classList.remove('d-none');

                if (itemsList) {
                    itemsList.innerHTML = carrito.map((item, idx) => `
                        <div class="cart-item-row">
                            <img src="${item.imagen || 'views/Recursos/icon.jpg'}" alt="${item.nombre}" class="cart-item-img">
                            <div class="flex-grow-1">
                                <div class="cart-item-title">${item.nombre}</div>
                                <div class="cart-item-price">$${item.precio.toFixed(2)} <span class="text-muted small">/ ${item.unidad}</span></div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <div class="stepper-box" style="width: 90px;">
                                        <button type="button" class="stepper-btn" style="width:24px;height:24px;" onclick="cambiarCantidad(${idx}, -1)">-</button>
                                        <span class="stepper-input" style="width:36px;font-size:0.85rem;">${item.cantidad}</span>
                                        <button type="button" class="stepper-btn" style="width:24px;height:24px;" onclick="cambiarCantidad(${idx}, 1)">+</button>
                                    </div>
                                    <button type="button" class="btn btn-link text-danger p-0 ms-2" onclick="eliminarItem(${idx})" title="Eliminar">
                                        <i class="fas fa-trash-can small"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="cart-item-subtotal">
                                $${(item.precio * item.cantidad).toFixed(2)}
                            </div>
                        </div>
                    `).join('');
                }

                if (subtotalEl) subtotalEl.textContent = `$${totalMonto.toFixed(2)}`;
                if (totalEl) totalEl.textContent = `$${totalMonto.toFixed(2)} USD`;
            }
        }

        function guardarDatosCliente() {
            const nombre = document.getElementById('cartClienteNombre').value.trim();
            const telefono = document.getElementById('cartClienteTelefono').value.trim();
            const correo = document.getElementById('cartClienteCorreo').value.trim();
            localStorage.setItem('concentrados_cliente', JSON.stringify({ nombre, telefono, correo }));
            return { nombre, telefono, correo };
        }

        // ==========================================
        // REGISTRO / LOGIN GUARD ANTES DE PAGAR
        // ==========================================
        function obtenerUsuarioRegistrado() {
            try {
                const data = sessionStorage.getItem('gordito_usuario');
                return data ? JSON.parse(data) : null;
            } catch(e) { return null; }
        }

        function guardarUsuarioRegistrado(datos) {
            sessionStorage.setItem('gordito_usuario', JSON.stringify(datos));
        }

        function abrirModalRegistro(callbackDespuesDeRegistro) {
            window._pendingPaymentCallback = callbackDespuesDeRegistro;
            const modal = document.getElementById('modalRegistroLanding');
            if (modal) modal.style.display = 'flex';
        }

        // ==========================================
        // PROCESAMIENTO DE PAGOS CON WOMPI
        // ==========================================
        function iniciarPagoPlanWompi(planId, nombrePlan, monto) {
            const usuario = obtenerUsuarioRegistrado();
            if (!usuario) {
                abrirModalRegistro(() => iniciarPagoPlanWompi(planId, nombrePlan, monto));
                return;
            }
            ejecutarCheckoutWompi({
                planId:    planId,
                nombre:    usuario.nombre,
                telefono:  usuario.telefono,
                correo:    usuario.correo,
                idUsuario: usuario.idUsuario
            });
        }

        function procesarPagoCarritoWompi() {
            if (carrito.length === 0) {
                Swal.fire('Carrito vacío', 'Añade productos antes de pagar.', 'info');
                return;
            }

            const usuario = obtenerUsuarioRegistrado();
            if (!usuario) {
                abrirModalRegistro(() => procesarPagoCarritoWompi());
                return;
            }

            ejecutarCheckoutWompi({
                items:     carrito,
                nombre:    usuario.nombre,
                telefono:  usuario.telefono,
                correo:    usuario.correo,
                idUsuario: usuario.idUsuario
            });
        }

        function ejecutarCheckoutWompi(payload) {
            Swal.fire({
                title: 'Conectando con Wompi...',
                text: 'Generando sesión segura de pago, por favor espera.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('wompi/create-checkout-session.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.url) {
                    window.location.href = data.url;
                } else if (data.config_pendiente) {
                    Swal.fire({
                        title: 'Pasarela Wompi en Modo Pruebas',
                        html: `
                            <p class="small text-muted mb-3">${data.error}</p>
                            <p class="fw-semibold">¿Deseas enviar tu pedido directamente por WhatsApp para ser atendido de inmediato por un asesor?</p>
                        `,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: '<i class="fab fa-whatsapp me-1"></i> Sí, Enviar Pedido por WhatsApp',
                        cancelButtonText: 'Cerrar',
                        confirmButtonColor: '#25d366'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            enviarPedidoCarritoWhatsApp();
                        }
                    });
                } else {
                    Swal.fire('Error en la pasarela', data.error || 'No se pudo iniciar la transacción.', 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error de conexión', 'No se pudo comunicar con el servidor para iniciar Wompi. Puedes enviar tu pedido vía WhatsApp.', 'error');
            });
        }

        // ==========================================
        // CONFIRMACIÓN Y ENVÍO DEL PEDIDO A WHATSAPP
        // ==========================================
        function enviarPedidoCarritoWhatsApp() {
            if (carrito.length === 0) {
                Swal.fire('Carrito vacío', 'Añade productos antes de enviar el pedido.', 'info');
                return;
            }

            const datos = guardarDatosCliente();
            const totalMonto = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);

            let texto = `*🛒 ¡Hola Concentrados El Gordito! Deseo confirmar un pedido desde la web*%0A%0A`;
            texto += `*👤 Cliente:* ${encodeURIComponent(datos.nombre || 'Cliente Web')}%0A`;
            texto += `*📞 Teléfono:* ${encodeURIComponent(datos.telefono || 'No especificado')}%0A`;
            if (datos.correo) {
                texto += `*✉️ Correo:* ${encodeURIComponent(datos.correo)}%0A`;
            }
            texto += `%0A*📋 DETALLE DE PRODUCTOS:*%0A`;

            carrito.forEach((item, idx) => {
                const sub = (item.precio * item.cantidad).toFixed(2);
                texto += `${idx + 1}. *${encodeURIComponent(item.nombre)}*%0A`;
                texto += `   Cantidad: ${item.cantidad} (${encodeURIComponent(item.unidad)})%0A`;
                texto += `   Precio Unit.: $${item.precio.toFixed(2)} | Subtotal: *$${sub}*%0A`;
            });

            texto += `%0A*💰 TOTAL A PAGAR: $${totalMonto.toFixed(2)} USD*%0A%0A`;
            texto += `_Quedo a la espera de su confirmación de despacho y forma de pago._`;

            const url = `https://wa.me/50378905678?text=${texto}`;
            window.open(url, '_blank');
        }

        // Cotización del formulario estándar
        function enviarCotizacionWhatsApp(e) {
            e.preventDefault();
            const nombre = document.getElementById('cotizaNombre').value.trim();
            const telefono = document.getElementById('cotizaTelefono').value.trim();
            const producto = document.getElementById('cotizaProducto').value;
            const departamento = document.getElementById('cotizaDepartamento').value;
            const mensaje = document.getElementById('cotizaMensaje').value.trim();

            let texto = `*¡Hola Concentrados El Gordito! Solicitud de Cotización*%0A%0A`;
            texto += `*Cliente/Granja:* ${encodeURIComponent(nombre)}%0A`;
            texto += `*Teléfono:* ${encodeURIComponent(telefono)}%0A`;
            texto += `*Fórmula de Interés:* ${encodeURIComponent(producto)}%0A`;
            texto += `*Departamento de Entrega:* ${encodeURIComponent(departamento)}%0A`;
            if (mensaje) {
                texto += `*Detalles adicionales:* ${encodeURIComponent(mensaje)}%0A`;
            }

            const url = `https://wa.me/50378905678?text=${texto}`;
            window.open(url, '_blank');
        }

        // ============================================================
        // FUNCIONES DEL MODAL DE REGISTRO / LOGIN
        // ============================================================
        function cambiarTabRegistro(tab) {
            const esRegistro = tab === 'registro';
            document.getElementById('tabRegistroContent').style.display = esRegistro ? 'block' : 'none';
            document.getElementById('tabLoginContent').style.display   = esRegistro ? 'none'  : 'block';
            document.getElementById('tabRegistroBtn').style.background = esRegistro ? '#f0fdf4' : '#fff';
            document.getElementById('tabRegistroBtn').style.color      = esRegistro ? '#059669' : '#64748b';
            document.getElementById('tabLoginBtn').style.background    = esRegistro ? '#fff'    : '#f0fdf4';
            document.getElementById('tabLoginBtn').style.color         = esRegistro ? '#64748b' : '#059669';
        }

        function cerrarModalRegistro() {
            document.getElementById('modalRegistroLanding').style.display = 'none';
            window._pendingPaymentCallback = null;
        }

        function mostrarErrorModal(idElemento, mensaje) {
            const el = document.getElementById(idElemento);
            if (el) { el.textContent = mensaje; el.style.display = 'block'; }
        }

        function ocultarErrorModal(idElemento) {
            const el = document.getElementById(idElemento);
            if (el) el.style.display = 'none';
        }

        function setBotonCargando(idBtn, cargando) {
            const btn = document.getElementById(idBtn);
            if (!btn) return;
            btn.disabled = cargando;
            btn.style.opacity = cargando ? '0.7' : '1';
            btn.innerHTML = cargando
                ? '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...'
                : btn.dataset.textoOriginal || btn.innerHTML;
        }

        function enviarRegistro() {
            ocultarErrorModal('errorRegistro');
            const nombre   = document.getElementById('regNombre').value.trim();
            const telefono = document.getElementById('regTelefono').value.trim();
            const correo   = document.getElementById('regCorreo').value.trim();
            const clave    = document.getElementById('regClave').value;

            if (!nombre || !telefono || !correo || !clave) {
                mostrarErrorModal('errorRegistro', 'Por favor completa todos los campos.');
                return;
            }

            const btn = document.getElementById('btnRegistrarse');
            btn.dataset.textoOriginal = btn.innerHTML;
            setBotonCargando('btnRegistrarse', true);

            fetch('controllers/controllerRegistroLanding.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion: 'registro', nombre, telefono, correo, clave })
            })
            .then(r => r.json())
            .then(data => {
                setBotonCargando('btnRegistrarse', false);
                if (data.exito) {
                    guardarUsuarioRegistrado(data);
                    cerrarModalRegistro();
                    if (typeof window._pendingPaymentCallback === 'function') {
                        window._pendingPaymentCallback();
                    }
                } else {
                    mostrarErrorModal('errorRegistro', data.mensaje || 'Error al registrarse.');
                }
            })
            .catch(() => {
                setBotonCargando('btnRegistrarse', false);
                mostrarErrorModal('errorRegistro', 'Error de conexión. Intenta de nuevo.');
            });
        }

        function enviarLogin() {
            ocultarErrorModal('errorLogin');
            const correo = document.getElementById('loginCorreo').value.trim();
            const clave  = document.getElementById('loginClave').value;

            if (!correo || !clave) {
                mostrarErrorModal('errorLogin', 'Por favor ingresa tu correo y contraseña.');
                return;
            }

            const btn = document.getElementById('btnLogin');
            btn.dataset.textoOriginal = btn.innerHTML;
            setBotonCargando('btnLogin', true);

            fetch('controllers/controllerRegistroLanding.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion: 'login', correo, clave })
            })
            .then(r => r.json())
            .then(data => {
                setBotonCargando('btnLogin', false);
                if (data.exito) {
                    guardarUsuarioRegistrado(data);
                    cerrarModalRegistro();
                    if (typeof window._pendingPaymentCallback === 'function') {
                        window._pendingPaymentCallback();
                    }
                } else {
                    mostrarErrorModal('errorLogin', data.mensaje || 'Credenciales incorrectas.');
                }
            })
            .catch(() => {
                setBotonCargando('btnLogin', false);
                mostrarErrorModal('errorLogin', 'Error de conexión. Intenta de nuevo.');
            });
        }

        // Cerrar modal al hacer click fuera
        document.getElementById('modalRegistroLanding').addEventListener('click', function(e) {
            if (e.target === this) cerrarModalRegistro();
        });
    </script>
</body>
</html>
