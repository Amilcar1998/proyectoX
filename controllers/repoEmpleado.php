<?php 
include '../models/ReportModel.php';

require_once __DIR__ . '/vendor/autoload.php';

$dataEmp= new ReportModel();
$dataEmpleado = $dataEmp->dataEmpleados();
$html='<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Reporte Empleados</title>
    <link rel="stylesheet" href="style.css" media="all" />
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="Recursos/logo.jpg" style="width: 200px;">
      </div>
      <h1>Concentrados El Gordito</h1>
      <div id="project">
        <div><span>Reporte</span>: Empleados</div>
      </div>
    </header>
    <main>';


$html.="<table class='table table-striped'>"."<thead class='table table-striped table-dark'>";
        $html.="<tr>"
                    ."<th>ID Empleado</th>"
                    ."<th>Nombre</th>"
                    ."<th>Apellidos</th>"
                    ."<th>Genero</th>"
                    ."<th>Cargo</th>"
                    ."<th>Usuario</th>"                                        
               ."</tr></thead><tbody>";
                foreach ($dataEmpleado as $fila){
                            $html.= "<tr>"
                        ."<td nowrap='nowrap'>".$fila["idEmpleado"]."</td>"
                        ."<td nowrap='nowrap'>".$fila["nombreEmp"]."</td>"
                        ."<td nowrap='nowrap'>".$fila["apellido"]."</td>"                        
                        ."<td nowrap='nowrap'>".$fila["genero"]."</td>"
                        ."<td nowrap='nowrap'>".$fila["nombrePuesto"]."</td>"
                        ."<td nowrap='nowrap'>".$fila["username"]."</td>"
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






 ?>

 </body>
</html>
