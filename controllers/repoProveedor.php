<?php 
include '../models/ReportModel.php';
require_once __DIR__ . '/vendor/autoload.php';

$dataProv= new ReportModel();
$dataProveedor = $dataProv->dataProveedor();

$html='<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Reporte Clientes</title>
    <link rel="stylesheet" href="style.css" media="all" />
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="Recursos/logo.jpg" style="width: 200px;">
      </div>
      <h1>Concentrados El Gordito</h1>
      <div id="project">
        <div><span>Reporte</span>: Proveedores</div>
      </div>
    </header>
    <main>';


$html.="<table class='table table-striped'>"."<thead class='table table-striped table-dark'>";
        $html.="<tr>"
                    ."<th>id Proveedor</th>"
                    ."<th>Nombre</th>"
                    ."<th>Contacto</th>"
                    ."<th>Nit</th>"
                    ."<th>Correo</th>"
                    ."<th>telefono</th>"                                       
               ."</tr></thead><tbody>";
                foreach ($dataProveedor as $fila){
                            $html.= "<tr>"
                        ."<td nowrap='nowrap'>".$fila["idProveedor"]."</td>"
                        ."<td nowrap='nowrap'>".$fila["nombreProveedor"]."</td>"
                        ."<td nowrap='nowrap'>".$fila["contacto"]."</td>"                        
                        ."<td nowrap='nowrap'>".$fila["NIT"]."</td>"
                        ."<td nowrap='nowrap'>".$fila["correoP"]."</td>"
                        ."<td nowrap='nowrap'>".$fila["telefono"]."</td>"
                    ."</tr>";
        }$html.="</tbody></table>";
    

$html.='</main>
    <footer>
      Aqui pondre la fecha
    </footer>
  </body>
</html>';

        
$mpdf = new \Mpdf\Mpdf();
$css=file_get_contents('css/style.css');
$mpdf->WriteHTML($css,1);
$mpdf->WriteHTML($html);

$mpdf->Output();
