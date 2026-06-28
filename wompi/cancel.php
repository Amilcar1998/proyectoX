<?php
session_start();
require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/../controllers/sesiones.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pago Cancelado | Concentrados El Gordito</title>
    <link href="../controllers/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-header bg-warning">
                        <h4>Pago Cancelado</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">El pago fue cancelado</h5>
                        <p class="card-text">Puedes intentarlo de nuevo cuando quieras.</p>
                        <a href="../controllerPlanPago.php" class="btn btn-primary">Volver a Planes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
