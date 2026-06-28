<?php
include '../db/conexion.php';

use Mpdf\Mpdf;

class ReportePedidoProveedorModel extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function getPedidosProveedor(): array {
        $res = $this->con->query("SELECT * from pedidoproveedor");
        if (!$res) {
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getPedidosProveedorError(): string {
        return $this->con->error;
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
                        <th style='padding-right:25px'>ID</th>
                        <th>ID PEDIDO</th>
                        <th>ID PROVEEDOR MP</th>
                        <th>ID EMPLEADO</th>
                        <th>ID MATERIA PRIMA</th>
                        <th>FECHA</th>
                        <th>CANTIDAD</th>
                        <th>MONTO</th>
                        <th>PRECIO</th>
                    </tr>";
        
        foreach ($data as $fila) {
            $html .= "<tr>
                        <td style='padding-right:25px'>".$fila['idPedido']."</td>
                        <td style='padding-right:25px'>".$fila['idProveedor']."</td>
                        <td style='padding-right:25px'>".$fila['idEmpleado']."</td>
                        <td style='padding-right:25px'>".$fila['fecha']."</td>
                        <td style='padding-right:25px'>".$fila['cantidadMP']."</td>
                        <td style='padding-right:25px'>".$fila['monto']."</td>
                        <td style='padding-right:25px'>".$fila['precioMP']."</td>
                    </tr>";
        }
        $html .= "</table>";
        
        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}