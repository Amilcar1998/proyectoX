<?php
/**
 * Controlador de Administración de Empresas y Configuración de Negocio
 * Soporte Multi-Tenant: Aislamiento por Empresa y Modo Superusuario Global
 * Arquitectura 100% MVC - Métodos en Español
 * Concentrados El Gordito
 */

require_once __DIR__ . '/sesiones.php';
require_once __DIR__ . '/../models/EmpresaModel.php';
require_once __DIR__ . '/../models/ModelDashboard.php';
require_once __DIR__ . '/../models/AuditoriaHelper.php';

$empresaModel = new EmpresaModel();
$daoDash = new ModelDashboard();

$correoUsuario = $_SESSION['s1'] ?? ($_SESSION['s2'] ?? ($_SESSION['c1'] ?? ''));
$idUsuario = function_exists('obtenerIdUsuarioPorUsername') ? obtenerIdUsuarioPorUsername($correoUsuario) : 1;
$idRolSesion = (int)($_SESSION['id_Rol'] ?? 0);

// Determinar privilegios de Superusuario Global vs Gerente/Admin de Empresa Aislada
$idEmpresaSesion = (int)($_SESSION['idEmpresa'] ?? 1);
$esSuperUsuario = !empty($_SESSION['esSuperUsuario']) || ($correoUsuario === 'amilcar199819@gmail.com');

// Obtener nombre para el navbar
$nombres = $correoUsuario;
if (!empty($correoUsuario)) {
    $sessionEmp = $daoDash->getSessionEmp($correoUsuario);
    if (!empty($sessionEmp)) {
        $nombres = trim(($sessionEmp[0]['nombreEmp'] ?? '') . ' ' . ($sessionEmp[0]['apellido'] ?? ''));
    }
}

$mensajeExito = '';
$mensajeError = '';

// Procesamiento de Acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    // 1. REGISTRAR NUEVA EMPRESA (Solo Superusuario)
    if ($accion === 'crear_empresa') {
        if (!$esSuperUsuario) {
            $mensajeError = 'Acceso denegado: Solo el Superusuario puede registrar nuevas empresas.';
        } else {
            $idDuenoForm = (int)($_POST['idUsuarioDueno'] ?? 0);
            $datos = [
                'idUsuarioDueno' => $idDuenoForm,
                'crearNuevoDueno' => ($idDuenoForm === 0 || !empty($_POST['crearNuevoDueno'])),
                'nombreDueno' => trim((string)($_POST['nombreDueno'] ?? 'Gerente')),
                'apellidoDueno' => trim((string)($_POST['apellidoDueno'] ?? '')),
                'nombreEmpresa' => trim((string)($_POST['nombreEmpresa'] ?? '')),
                'slug' => trim((string)($_POST['slug'] ?? '')),
                'direccion' => trim((string)($_POST['direccion'] ?? '')),
                'telefono' => trim((string)($_POST['telefono'] ?? '')),
                'correo' => trim((string)($_POST['correo'] ?? '')),
                'whatsapp' => trim((string)($_POST['whatsapp'] ?? '')),
                'wompiAppId' => trim((string)($_POST['wompiAppId'] ?? '')),
                'wompiApiKey' => trim((string)($_POST['wompiApiKey'] ?? '')),
                'wompiActivo' => isset($_POST['wompiActivo']) ? 1 : 0,
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];

            if (empty($datos['nombreEmpresa'])) {
                $mensajeError = 'El nombre de la empresa es obligatorio.';
            } else {
                $nuevoId = $empresaModel->guardar($datos);
                if ($nuevoId > 0) {
                    $mensajeExito = "Empresa '{$datos['nombreEmpresa']}' registrada exitosamente (ID: {$nuevoId}).";
                    logAccionAuditoria('crear', 'empresas', "Registró nueva empresa: {$datos['nombreEmpresa']} (ID: $nuevoId)");
                    $_GET['empresa'] = $nuevoId;
                } else {
                    $mensajeError = 'Ocurrió un error al registrar la empresa.';
                }
            }
        }
    }

    // 2. ACTUALIZAR EMPRESA (Superusuario actualiza cualquiera, Gerente/Admin solo la propia)
    elseif ($accion === 'actualizar_empresa' || isset($_POST['guardar_perfil'])) {
        $idEmpresaTarget = $esSuperUsuario ? (int)($_POST['idEmpresa'] ?? 1) : $idEmpresaSesion;
        
        $datos = [
            'idUsuarioDueno' => $esSuperUsuario ? (int)($_POST['idUsuarioDueno'] ?? $idUsuario) : $idUsuario,
            'nombreEmpresa' => trim((string)($_POST['nombreEmpresa'] ?? '')),
            'slug' => trim((string)($_POST['slug'] ?? '')),
            'direccion' => trim((string)($_POST['direccion'] ?? '')),
            'telefono' => trim((string)($_POST['telefono'] ?? '')),
            'correo' => trim((string)($_POST['correo'] ?? '')),
            'whatsapp' => trim((string)($_POST['whatsapp'] ?? '')),
            'wompiAppId' => trim((string)($_POST['wompiAppId'] ?? '')),
            'wompiApiKey' => trim((string)($_POST['wompiApiKey'] ?? '')),
            'wompiActivo' => isset($_POST['wompiActivo']) ? 1 : 0,
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        if ($empresaModel->actualizar($idEmpresaTarget, $datos)) {
            $mensajeExito = "Información de la empresa '{$datos['nombreEmpresa']}' actualizada correctamente.";
            logAccionAuditoria('actualizar', 'empresas', "Actualizó empresa ID {$idEmpresaTarget}: {$datos['nombreEmpresa']}");
            $_GET['empresa'] = $idEmpresaTarget;
        } else {
            $mensajeError = 'Error al actualizar los datos de la empresa.';
        }
    }

    // 3. CAMBIAR ESTADO (ACTIVAR / DESACTIVAR)
    elseif ($accion === 'cambiar_estado') {
        $idEmpresaTarget = $esSuperUsuario ? (int)($_POST['idEmpresa'] ?? 0) : $idEmpresaSesion;
        $nuevoEstado = (int)($_POST['nuevoEstado'] ?? 1);

        if ($idEmpresaTarget > 0) {
            if ($empresaModel->cambiarEstado($idEmpresaTarget, $nuevoEstado)) {
                $estadoTexto = $nuevoEstado === 1 ? 'activada' : 'desactivada';
                $mensajeExito = "Empresa ID {$idEmpresaTarget} {$estadoTexto} exitosamente.";
                logAccionAuditoria('actualizar', 'empresas', "Cambió estado de empresa ID {$idEmpresaTarget} a {$estadoTexto}");
            } else {
                $mensajeError = 'No se pudo cambiar el estado de la empresa.';
            }
        }
    }

    // 4. GUARDAR CREDENCIALES WOMPI
    elseif (isset($_POST['guardar_wompi'])) {
        $idEmpresaTarget = $esSuperUsuario ? (int)($_POST['idEmpresa'] ?? 1) : $idEmpresaSesion;
        $appId = trim((string)($_POST['wompiAppId'] ?? ''));
        $apiKey = trim((string)($_POST['wompiApiKey'] ?? ''));
        $activo = isset($_POST['wompiActivo']);

        if ($empresaModel->actualizarCredencialesWompi($idEmpresaTarget, $appId, $apiKey, $activo)) {
            $mensajeExito = 'Credenciales de Wompi SV guardadas exitosamente.';
            logAccionAuditoria('actualizar', 'empresas', "Actualizó credenciales Wompi de empresa ID $idEmpresaTarget");
            $_GET['empresa'] = $idEmpresaTarget;
        } else {
            $mensajeError = 'Error al guardar las credenciales de Wompi.';
        }
    }

    // 5. PROBAR CONEXIÓN WOMPI
    elseif (isset($_POST['probar_conexion_wompi'])) {
        $appId = trim((string)($_POST['wompiAppId'] ?? ''));
        $apiKey = trim((string)($_POST['wompiApiKey'] ?? ''));

        if (empty($appId) || empty($apiKey)) {
            $mensajeError = 'Debes ingresar el ID de Aplicación y la Clave Secreta de Wompi para probar la conexión.';
        } else {
            $ch = curl_init('https://id.wompi.sv/connect/token');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'grant_type' => 'client_credentials',
                'client_id' => $appId,
                'client_secret' => $apiKey,
                'audience' => 'wompi_api'
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $res = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $mensajeExito = '🎉 ¡Conexión exitosa con Wompi El Salvador! Tus credenciales son válidas y están listas para procesar pagos.';
            } else {
                $mensajeError = "❌ No se pudo autenticar con Wompi SV (Código HTTP $httpCode). Verifica que tu ID de Aplicación y Clave Secreta sean correctos.";
            }
        }
    }
}

// Carga de datos según modo Superusuario vs Tenant Aislado
$listaUsuarios = $esSuperUsuario ? $empresaModel->obtenerUsuariosDisponibles() : [];

if ($esSuperUsuario) {
    $listaEmpresas = $empresaModel->listarTodas();
    $idEmpresaSeleccionada = (int)($_GET['empresa'] ?? 0);
    $empresa = ($idEmpresaSeleccionada > 0) ? $empresaModel->obtenerPorId($idEmpresaSeleccionada) : null;
    if (!$empresa) {
        $empresa = !empty($listaEmpresas) ? $listaEmpresas[0] : $empresaModel->obtenerPorId(1);
    }
} else {
    // Empresa aislada para el Gerente / Administrador de este tenant
    $empresa = $empresaModel->obtenerPorId($idEmpresaSesion);
    if (!$empresa) {
        $empresa = $empresaModel->obtenerPorUsuario($idUsuario);
    }
    $listaEmpresas = $empresa ? [$empresa] : [];
}

$urlTiendaPublica = obtenerUrlBase('?tienda=' . ($empresa['slug'] ?? ''));

include __DIR__ . '/../views/vistaConfiguracionNegocio.php';
