<?php
include '../db/conexion.php';

use Mpdf\Mpdf;

class ReportePedidosModel extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function getPedidos(): array {
        $res = $this->con->query("SELECT idPedido,pedido.fechaPedido, nombreCliente FROM pedido INNER JOIN cliente ON pedido.idCliente=cliente.idCliente");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function generarPDF(array $data, string $titulo): void {
        $fecha = getdate();
        $fechaStr = $fecha['mday']."-".$fecha['mon']."-".$fecha['year']."  ".$fecha['hours'].":".$fecha['minutes'].":".$fecha['seconds'];
        
        $html = "<p align='center'><h3>CONCENTRADOS EL GORDITO</h3></p><br>
                <p align='center'><img src='../controllers/recursos/logo.jpg' width='150px';></p>
                Fecha del reporte: $fechaStr<hr><br>
                <h3>$titulo</h3>
                <table border='1'>
                    <tr>
                        <th style='padding-right:25px'>ID PED</th>
                        <th>FECHA</th>
                        <th>CLIENTE</th>
                    </tr>";
        
        foreach ($data as $fila) {
            $html .= "<tr>
                        <td style='padding-right:25px'>".$fila['idPedido']."</td>
                        <td style='padding-right:25px'>".$fila['fechaPedido']."</td>
                        <td style='padding-right:25px'>".$fila['nombreCliente']."</td>
                    </tr>";
        }
        $html .= "</table>";
        
        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}