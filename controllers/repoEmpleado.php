<?php 
include '../models/ClienteModel.php';

require_once __DIR__ . '/vendor/autoload.php';
// Create an instance of the class:
$datos = new ClienteModel();
$data=$datos->getCliente();
var_dump($data);
die();

$mpdf = new \Mpdf\Mpdf();


// Write some HTML code:
$mpdf->WriteHTML('Hello World');

// Output a PDF file directly to the browser
$mpdf->Output();






 ?>