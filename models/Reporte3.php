<?php
  //include '../db/conexion.php';
include '../mpdf/mpdf.php';
  /////////////////////_________MEZCLAS__________/////////////////////////////////////
  function selecTabla(){
    try{
        $con = new mysqli("localhost","root","","concentrados");
    }catch(Exception $exc){
        echo $exc->getTraceAsString();
    }
    $sql = "SELECT receta.idReceta, receta.nombreReceta, cantidaSa AS cantidad, idMateriaPrima  from receta
            inner join detallereceta on receta.idReceta=detallereceta.idDetalleReceta
            order by nombreReceta";
    $res = $con->query($sql);
    $tabla = "";
    $tabla .= "<table>
                <tr>
                    <th>ID RECETA</th>
                    <th>NOMBRE RECETA</th>
                    <th>CANTIDAD</th>
                    <th>ID MATERIA PRIMA</th>
                </tr>
                ";
    $i = 1;
    while($fila= $res->fetch_assoc()){
        $tabla .= "<tr>
                    <td style='padding-right:25px'>".$fila['idReceta']."</td>
                    <td style='padding-right:25px'>".$fila['nombreReceta']."</td>
                    <td style='padding-right:25px'>".$fila['cantidad']."</td>
                    <td style='padding-right:25px'>".$fila['idMateriaPrima']."</td>
                </tr>";
    }
    $tabla .="</table>";
    return $tabla;
}
$fecha=getdate();
$html = "<p align='center'><h3>CONCENTRADOS EL GORDITO</h3></p><br>
        <p align='center'><img src='../controllers/recursos/logo.jpg' width='150px';></p>Fecha del reporte: ".$fecha = $fecha['mday']."-".$fecha['mon']."-".$fecha['year']."  ".$fecha['hours'].":".$fecha['minutes'].":".$fecha['seconds']."<hr><br>
        <h3>MEZCLAS HECHAS</h3>";
$html .= selecTabla();
$pdf = new mPDF('c');
$pdf->WriteHTML($html);
$pdf->Output();
exit;
?>