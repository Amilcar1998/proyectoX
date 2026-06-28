<?php
// Copiar este archivo como parametros.php y completar con tus credenciales locales
// NO subir parametros.php a GitHub - esta en .gitignore

if (!defined('SERVER')) {
    define("SERVER","localhost");
    define("USER","root");
    define("PASSWORD",""); // tu password de MySQL
    define("BASE","concentrados");
    define("CHAR","utf8mb4");
}

// Wompi - obten estas credenciales en https://dashboard.wompi.co/
define("WOMPI_PUBLIC_KEY", "pub_test_TU_APP_ID_AQUI");
define("WOMPI_PRIVATE_KEY", "priv_test_TU_API_SECRET_AQUI");
define("WOMPI_API_URL", "https://sandbox.wompi.co/v1/payment_intents");
