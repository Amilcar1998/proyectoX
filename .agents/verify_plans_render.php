<?php
$html1 = file_get_contents('http://localhost/ProyectoX/');
$html2 = file_get_contents('http://localhost/ProyectoX/views/landing.php');

preg_match_all('/class="plan-name">([^<]+)<\/div>/', $html1, $m1);
preg_match_all('/class="plan-name">([^<]+)<\/div>/', $html2, $m2);

echo "Ruta / (index.php) planes encontrados (" . count($m1[1]) . "):\n";
foreach ($m1[1] as $p) {
    echo " - " . trim($p) . "\n";
}

echo "\nRuta /views/landing.php planes encontrados (" . count($m2[1]) . "):\n";
foreach ($m2[1] as $p) {
    echo " - " . trim($p) . "\n";
}
