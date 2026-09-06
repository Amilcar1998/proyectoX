<?php
/**
 * Punto de entrada retrocompatible para el login.
 * Redirige al navegador directamente al controlador oficial en controllers/controlUser.php
 * para asegurar que la URL base del navegador siempre sea correcta.
 */
header("Location: controllers/controlUser.php");
exit();
