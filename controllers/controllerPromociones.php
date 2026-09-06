<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/PromocionModel.php';
require_once __DIR__ . '/../controllers/sesiones.php';
require_once __DIR__ . '/../models/AuditoriaHelper.php';

$modeloPromocion = new PromocionModel();
$usuarioActual = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? ($_SESSION['c1'] ?? 'admin'));
$rolActual = (int)($_SESSION['id_Rol'] ?? 0);
$esGerenteOAdmin = isset($_SESSION['s1']) || $rolActual === 1 || $rolActual === 4;

// Registrar auditoría de acceso al módulo
logModuloAcceso(0, $usuarioActual, 'promociones');

$mensaje = null;
$tipoMensaje = 'info';

// Procesamiento de peticiones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $esGerenteOAdmin) {
    $accion = $_POST['accion'] ?? '';

    // 1. NIVELACIÓN DE PRECIO BASE (Venta directa, sin mostrar como promoción)
    if ($accion === 'nivelacion') {
        $idReceta = (int)($_POST['idReceta'] ?? 0);
        $nuevoPrecio = (float)($_POST['nuevoPrecio'] ?? 0);
        $motivo = trim((string)($_POST['motivo'] ?? 'Ajuste de costo y nivelación de precio'));

        if ($idReceta > 0 && $nuevoPrecio > 0) {
            $exito = $modeloPromocion->aplicarNivelacion($idReceta, $nuevoPrecio, $motivo, $usuarioActual);
            if ($exito) {
                $mensaje = "Nivelación de precio aplicada con éxito. El nuevo precio de venta es \$$nuevoPrecio USD.";
                $tipoMensaje = "success";
                logAccionAuditoria('crud', 'promociones', "Nivelación de precio receta ID $idReceta a \$$nuevoPrecio ($motivo)");
            } else {
                $mensaje = "No se pudo aplicar la nivelación de precio.";
                $tipoMensaje = "error";
            }
        } else {
            $mensaje = "Datos inválidos para la nivelación de precio.";
            $tipoMensaje = "error";
        }
    }

    // 2. CREAR O ACTUALIZAR PROMOCIÓN TEMPORAL (Con fecha inicio, fin y descuento)
    elseif ($accion === 'promocion') {
        $idReceta = (int)($_POST['idReceta'] ?? 0);
        $precioOferta = (float)($_POST['precioOferta'] ?? 0);
        $precioRegular = (float)($_POST['precioRegular'] ?? 0);
        $fechaInicio = !empty($_POST['fechaInicio']) ? str_replace('T', ' ', $_POST['fechaInicio']) . ':00' : date('Y-m-d H:i:s');
        $fechaFin = !empty($_POST['fechaFin']) ? str_replace('T', ' ', $_POST['fechaFin']) . ':00' : date('Y-m-d H:i:s', strtotime('+7 days'));
        $motivo = trim((string)($_POST['motivo'] ?? 'Campaña promocional'));

        if ($idReceta > 0 && $precioOferta > 0 && $precioRegular > $precioOferta) {
            $datosPromo = [
                'precio_oferta' => $precioOferta,
                'precio_regular' => $precioRegular,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'motivo' => $motivo
            ];
            $exito = $modeloPromocion->crearPromocionTemporal($idReceta, $datosPromo, $usuarioActual);
            if ($exito) {
                $mensaje = "Promoción temporal programada exitosamente desde $fechaInicio hasta $fechaFin.";
                $tipoMensaje = "success";
                logAccionAuditoria('crud', 'promociones', "Promoción programada receta ID $idReceta (\$$precioRegular -> \$$precioOferta)");
            } else {
                $mensaje = "No se pudo guardar la promoción.";
                $tipoMensaje = "error";
            }
        } else {
            $mensaje = "El precio de oferta debe ser menor al precio regular actual.";
            $tipoMensaje = "error";
        }
    }

    // 3. CANCELAR / FINALIZAR PROMOCIÓN ANTICIPADA
    elseif ($accion === 'cancelar_promocion') {
        $idReceta = (int)($_POST['idReceta'] ?? 0);
        if ($idReceta > 0) {
            $modeloPromocion->cancelarPromocion($idReceta, $usuarioActual);
            $mensaje = "Promoción cancelada. El producto ha vuelto a su precio base regular.";
            $tipoMensaje = "success";
            logAccionAuditoria('crud', 'promociones', "Canceló promoción anticipada de receta ID $idReceta");
        }
    }

    // 4. AJUSTE MASIVO DE PRECIOS
    elseif ($accion === 'ajuste_masivo') {
        $porcentaje = (float)($_POST['porcentaje'] ?? 0);
        $tipoOperacion = $_POST['tipoOperacion'] ?? 'aumentar';

        if ($porcentaje > 0) {
            $modeloPromocion->ajustarPreciosMasivo($porcentaje, $tipoOperacion);
            $mensaje = "Ajuste masivo del {$porcentaje}% aplicado exitosamente a todas las fórmulas.";
            $tipoMensaje = "success";
            logAccionAuditoria('crud', 'promociones', "Ajuste masivo ($tipoOperacion {$porcentaje}%)");
        }
    }

    // Respuesta JSON si es llamada AJAX
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => $tipoMensaje === 'success', 'mensaje' => $mensaje, 'tipo' => $tipoMensaje]);
        exit();
    }
}

// Obtener recetas, la promoción activa actual y el historial completo
$listaRecetas = $modeloPromocion->listarRecetas();
$promocionActivaActual = $modeloPromocion->obtenerPromocionActiva();
$historialPrecios = $modeloPromocion->obtenerHistorial(35);

// Cargar la vista
require_once __DIR__ . '/../views/vistaPromociones.php';
