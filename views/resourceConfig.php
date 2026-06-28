<?php
// Archivo de configuración centralizada para recursos estáticos
define('VENDOR_URL', '../controllers/vendor');
define('JS_URL', '../controllers/js');
define('RESOURCES_URL', '../controllers/Recursos');

function getVendorCss($path) {
    return VENDOR_URL . '/css/' . $path;
}

function getVendorJs($path) {
    return VENDOR_URL . '/js/' . $path;
}
?>