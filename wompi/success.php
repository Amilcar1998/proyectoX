<?php
session_start();
require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/../controllers/sesiones.php';
require_once __DIR__ . '/../models/AuditoriaHelper.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pago Procesado | Concentrados El Gordito</title>
    <link href="../controllers/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-header bg-info text-white">
                        <h4>Pago en Proceso</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Tu pago se está procesando</h5>
                        <p class="card-text">Estamos verificando tu transacción con Wompi.</p>
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Verificando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    setTimeout(function() {
        window.location.href = '../controllerPagos.php';
    }, 4000);
    </script>
</body>
</html>
