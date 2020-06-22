<?php
  //include '../db/conexion.php';
  include '../mpdf/mpdf.php';
  
  function selecTabla(){
    try{
        $con = new mysqli("localhost","root","","concentrados");
    }catch(Exception $exc){
        echo $exc->getTraceAsString();
    }
    $sql = "SELECT inventario.idMateriaPrima, materiaprima.NombreMP AS NombreMAP, Existencias FROM inventario INNER JOIN materiaprima ON 
            inventario.idMateriaPrima = materiaprima.idMateriaPrima";
    $res = $con->query($sql);
    $tabla = "";
    $tabla .= "<table>
                <tr>
                    <th>ID MP</th>
                    <th>NOMBRE MP</th>
                    <th>EXISTENCIA</th>
                </tr>
                ";
    while($fila= $res->fetch_assoc()){
        $tabla .= "<tr>
                    <td style='padding-right:25px'>".$fila['idMateriaPrima']."</td>
                    <td style='padding-right:25px'>".$fila['NombreMAP']."</td>
                    <td>".$fila['Existencias']."</td>
                </tr>";
    }
    $tabla .="</table>";
    return $tabla;
}
$fecha=getdate();
$html = "<p align='center'><h3>CONCENTRADOS EL GORDITO</h3></p><br>
        <p align='center'><img src='../controllers/recursos/logo.jpg' width='150px';></p>Fecha del reporte: ".$fecha = $fecha['mday']."-".$fecha['mon']."-".$fecha['year']."  ".$fecha['hours'].":".$fecha['minutes'].":".$fecha['seconds']."<hr><br>
        <h3>INVENTARIO GENERAL DE MATERIA PRIMA</h3>";
$html .= selecTabla();
$pdf = new mPDF('c');
$pdf->WriteHTML($html);
$pdf->Output();
exit;
?>