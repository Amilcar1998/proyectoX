<?php
  //include '../db/conexion.php';
  include '../mpdf/mpdf.php';
  
  function selecTabla(){
    try{
        $con = new mysqli("localhost","root","","concentrados");
    }catch(Exception $exc){
        echo $exc->getTraceAsString();
    }
    $sql = "SELECT * from pedidoproveedor";
    $res = $con->query($sql);
    $tabla = "";
    $tabla .= "<table>
                <tr>
                    <th style='padding-right:25px'>ID</th>
                    <th>ID PEDIDO</th>
                    <th>ID PROVEEDOR MP</th>
                    <th>ID EMPLEADO</th>
                    <th>ID MATERIA PRIMA</th>
                    <th>FECHA</th>
                    <th>CANTIDAD</th>
                    <th>MONTO</th>
                    <th>PRECIO</th>
                </tr>
                ";
    while($fila= $res->fetch_assoc()){
        $tabla .= "<tr>
                    <td style='padding-right:25px'>".$fila['idPedido']."</td>
                    <td style='padding-right:25px'>".$fila['idProveedor']."</td>
                    <td style='padding-right:25px'>".$fila['idEmpleado']."</td>
                    <td style='padding-right:25px'>".$fila['fecha']."</td>
                    <td style='padding-right:25px'>".$fila['cantidadMP']."</td>
                    <td style='padding-right:25px'>".$fila['monto']."</td>
                    <td style='padding-right:25px'>".$fila['precioMP']."</td>
                </tr>";
    }
    $tabla .="</table>";
    return $tabla;
}
$fecha=getdate();
$html = "<p align='center'><img src='../controllers/recursos/logo.jpg' width='150px';></p>Fecha del reporte: ".$fecha = $fecha['mday']."-".$fecha['mon']."-".$fecha['year']."  ".$fecha['hours'].":".$fecha['minutes'].":".$fecha['seconds']."<hr><br>
        <h3>INVENTARIO GENERAL DE MATERIA PRIMA</h3>";
$html .= selecTabla();
$pdf = new mPDF('c');
$pdf->WriteHTML($html);
$pdf->Output();
exit;
?>