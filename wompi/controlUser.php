<?php
/**
 * Redireccionador de seguridad para evitar 404 si Wompi o un navegador
 * intenta acceder a wompi/controlUser.php
 */
header("Location: ../controllers/controlUser.php");
exit();
