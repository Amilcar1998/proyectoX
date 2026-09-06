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
define("WOMPI_PUBLIC_KEY", "d01ac1a6-c618-48fb-870b-8276438a79b3");
define("WOMPI_PRIVATE_KEY", "4bd17a7b-ed83-4505-b61e-7d456f3bfd5d");
define("WOMPI_API_URL", "https://sandbox.wompi.co/v1/payment_intents");
