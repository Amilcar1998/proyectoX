<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="vendor/sb-admin.min.css">
</head>
<body>


<?php 
include '../models/ReportModel.php';

require_once __DIR__ . '/vendor/autoload.php';

$dataEmp= new ReportModel();
$dataEmpleado = $dataEmp->dataEmpleados();


$tabla = "<table class='table table-striped'>"."<thead class='table table-striped table-dark'>";
        $tabla .="<tr>"
                    ."<th>ID Empleado</th>"
                    ."<th>Nombre</th>"
                    ."<th>Apellidos</th>"
                    ."<th>Genero</th>"
                    ."<th>Cargo</th>"
                    ."<th>Usuario</th>"                                        
               ."</tr></thead><tbody>";
foreach ($dataEmpleado as $fila){
            $tabla .= "<tr>"
                        ."<td nowrap='nowrap'>".$fila["idEmpleado"]."</td>"
                        ."<td nowrap='nowrap'>".$fila["nombreEmp"]."</td>"
                        ."<td nowrap='nowrap'>".$fila["apellido"]."</td>"                        
                        ."<td nowrap='nowrap'>".$fila["genero"]."</td>"
                        ."<td nowrap='nowrap'>".$fila["nombrePuesto"]."</td>"
                        ."<td nowrap='nowrap'>".$fila["username"]."</td>"
                    ."</tr>";
        }$tabla .="</tbody></table>";
        //echo "$tabla";
        //die();
        
$mpdf = new \Mpdf\Mpdf();

$mpdf->shrink_tables_to_fit = 1;
$mpdf->WriteHTML($tabla);

$mpdf->Output();






 ?>

 </body>
</html>
