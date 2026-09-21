<?php
// Archivo de configuración centralizada para recursos estáticos
define('VENDOR_URL', '../vendor');
define('APP_CSS_URL', '../views/css');
define('APP_JS_URL', '../views/js');
define('RESOURCES_URL', '../views/Recursos');

function getVendorCss($path) {
    return VENDOR_URL . '/' . ltrim($path, '/');
}

function getVendorJs($path) {
    return VENDOR_URL . '/' . ltrim($path, '/');
}

function getAppCss($file) {
    return APP_CSS_URL . '/' . ltrim($file, '/');
}

function getAppJs($file) {
    return APP_JS_URL . '/' . ltrim($file, '/');
}
?>