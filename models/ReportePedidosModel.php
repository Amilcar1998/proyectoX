<?php
include '../db/conexion.php';

use Mpdf\Mpdf;

class ReportePedidosModel extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function getPedidos(): array {
        $sql = "SELECT pe.idPedido AS Pedido,
                       CONCAT(cl.NombreCliente, ' ', cl.apellidosCliente) AS Cliente,
                       cl.telefonoCliente AS Telefono,
                       cl.direccionCliente AS Direccion,
                       pe.fechaPedido AS Fecha,
                       ep.estadoPedido AS Estado,
                       r.nombreReceta AS Producto,
                       dp.cantidad AS Cantidad,
                       r.PrecioUnitario AS Precio,
                       dp.cantidad * r.PrecioUnitario AS Subtotal
                FROM pedido pe
                INNER JOIN cliente cl ON pe.idCliente = cl.idCliente
                INNER JOIN estadopedido ep ON pe.idEstadoPedido = ep.idEstadoPedido
                INNER JOIN detallepedido dp ON pe.idPedido = dp.IdPedido
                INNER JOIN receta r ON dp.idReceta = r.idReceta
                ORDER BY pe.idPedido DESC";
        $res = $this->con->query($sql);
        if (!$res) {
            error_log('SQL Error in getPedidos: ' . $this->con->error . ' | Query: ' . $sql);
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function generarPDF(array $data, string $titulo): void {
        $fecha = getdate();
        $fechaStr = $fecha['mday']."-".$fecha['mon']."-".$fecha['year']."  ".$fecha['hours'].":".$fecha['minutes'].":".$fecha['seconds'];
        
        $html = "<table style='width: 100%; border-collapse: collapse; margin-bottom: 15px;'>
                    <tr>
                        <td style='width: 60%; vertical-align: middle;'>
                            <h3>CONCENTRADOS EL GORDITO</h3>
                            <p style='margin: 0; color: #666;'>Fecha del reporte: $fechaStr</p>
                        </td>
                        <td style='width: 40%; text-align: right; vertical-align: middle;'>
                            <img src='../controllers/recursos/logo.jpg' width='100px'>
                        </td>
                    </tr>
                 </table>
                 <hr>
                 <h3>$titulo</h3>
                 <table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 10px;'>
                     <thead style='background-color: #e74a3b; color: white;'>
                         <tr>
                             <th style='padding: 10px; text-align: center;'>PEDIDO</th>
                             <th style='padding: 10px; text-align: center;'>CLIENTE</th>
                             <th style='padding: 10px; text-align: center;'>TELÉFONO</th>
                             <th style='padding: 10px; text-align: center;'>DIRECCIÓN</th>
                             <th style='padding: 10px; text-align: center;'>FECHA</th>
                             <th style='padding: 10px; text-align: center;'>ESTADO</th>
                             <th style='padding: 10px; text-align: center;'>PRODUCTO</th>
                             <th style='padding: 10px; text-align: center;'>CANTIDAD</th>
                             <th style='padding: 10px; text-align: center;'>PRECIO</th>
                             <th style='padding: 10px; text-align: center;'>SUBTOTAL</th>
                         </tr>
                     </thead>
                     <tbody>";
        
        foreach ($data as $fila) {
            $html .= "<tr>
                        <td style='padding: 8px; text-align: center; border: 1px solid #ddd;'>".($fila['Pedido'] ?? '')."</td>
                        <td style='padding: 8px; text-align: left; border: 1px solid #ddd;'>".($fila['Cliente'] ?? '')."</td>
                        <td style='padding: 8px; text-align: center; border: 1px solid #ddd;'>".($fila['Telefono'] ?? '')."</td>
                        <td style='padding: 8px; text-align: left; border: 1px solid #ddd;'>".($fila['Direccion'] ?? '')."</td>
                        <td style='padding: 8px; text-align: center; border: 1px solid #ddd;'>".($fila['Fecha'] ?? '')."</td>
                        <td style='padding: 8px; text-align: center; border: 1px solid #ddd;'>".($fila['Estado'] ?? '')."</td>
                        <td style='padding: 8px; text-align: left; border: 1px solid #ddd;'>".($fila['Producto'] ?? '')."</td>
                        <td style='padding: 8px; text-align: center; border: 1px solid #ddd;'>".($fila['Cantidad'] ?? '')."</td>
                        <td style='padding: 8px; text-align: right; border: 1px solid #ddd;'>".($fila['Precio'] ?? '')."</td>
                        <td style='padding: 8px; text-align: right; border: 1px solid #ddd; font-weight: bold;'>".($fila['Subtotal'] ?? '')."</td>
                    </tr>";
        }
        $html .= "</tbody></table>";
        
        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}