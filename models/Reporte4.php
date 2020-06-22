<?php
  //include '../db/conexion.php';
  include '../mpdf/mpdf.php';
  
  function selecTabla(){
    try{
        $con = new mysqli("localhost","root","","concentrados");
    }catch(Exception $exc){
        echo $exc->getTraceAsString();
    }
    $sql = "SELECT idPedido,pedido.fechaPedido, nombreCliente FROM pedido 
            INNER JOIN cliente ON pedido.idCliente=cliente.idCliente";
    $res = $con->query($sql);
    $tabla = "";
    $tabla .= "<table>
                <tr>
                    <th style='padding-right:25px'>ID PED</th>
                    <th>FECHA</th>
                    <th>CLIENTE</th>
                </tr>
                ";
    while($fila= $res->fetch_assoc()){
        $tabla .= "<tr>
                    <td style='padding-right:25px'>".$fila['idPedido']."</td>
                    <td style='padding-right:25px'>".$fila['fechaPedido']."</td>
                    <td style='padding-right:25px'>".$fila['nombreCliente']."</td>
                </tr>";
    }
    $tabla .="</table>";
    return $tabla;
}
$fecha=getdate();
$html = "<p align='center'><img src='../controllers/recursos/logo.jpg' width='150px';></p>Fecha del reporte: ".$fecha = $fecha['mday']."-".$fecha['mon']."-".$fecha['year']."  ".$fecha['hours'].":".$fecha['minutes'].":".$fecha['seconds']."<hr><br>
        <h3>PEDIDOS POR FECHA Y CLIENTE</h3>";
$html .= selecTabla();
$pdf = new mPDF('c');
$pdf->WriteHTML($html);
$pdf->Output();
exit;
?>