<?php
include '../db/conexion.php';

use Mpdf\Mpdf;

class ReportePedidoProveedorModel extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function getPedidosProveedor(): array {
        $sql = "SELECT mp.NombreMP, i.Existencias,
                       SUM(dp.cantidad) AS Necesario,
                       i.Existencias - SUM(dp.cantidad) AS Disponible,
                       CASE WHEN i.Existencias < SUM(dp.cantidad)
                            THEN SUM(dp.cantidad) - i.Existencias
                            ELSE 0
                       END AS Comprar
                FROM materiaprima mp
                INNER JOIN inventario i ON mp.idMateriaPrima = i.idMateriaPrima
                INNER JOIN detallereceta dr ON mp.idMateriaPrima = dr.idMateriaPrima
                INNER JOIN detallepedido dp ON dr.IdReceta = dp.idReceta
                GROUP BY mp.idMateriaPrima, mp.NombreMP, i.Existencias
                ORDER BY Comprar DESC";
        $res = $this->con->query($sql);
        if (!$res) {
            error_log('SQL Error in getPedidosProveedor: ' . $this->con->error);
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
                 <table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 11px;'>
                     <thead style='background-color: #36b9cc; color: white;'>
                         <tr>
                             <th style='padding: 10px; text-align: center;'>MATERIA PRIMA</th>
                             <th style='padding: 10px; text-align: center;'>EXISTENCIAS</th>
                             <th style='padding: 10px; text-align: center;'>NECESARIO</th>
                             <th style='padding: 10px; text-align: center;'>DISPONIBLE</th>
                             <th style='padding: 10px; text-align: center;'>COMPRAR</th>
                         </tr>
                     </thead>
                     <tbody>";
        
        foreach ($data as $fila) {
            $comprar = $fila['Comprar'] ?? 0;
            $colorFila = $comprar > 0 ? 'background-color: #f8d7da;' : '';
            
            $html .= "<tr style='$colorFila'>
                        <td style='padding: 8px; text-align: left; border: 1px solid #ddd;'>".($fila['NombreMP'] ?? '')."</td>
                        <td style='padding: 8px; text-align: center; border: 1px solid #ddd;'>".($fila['Existencias'] ?? '')."</td>
                        <td style='padding: 8px; text-align: center; border: 1px solid #ddd;'>".($fila['Necesario'] ?? '')."</td>
                        <td style='padding: 8px; text-align: center; border: 1px solid #ddd;'>".($fila['Disponible'] ?? '')."</td>
                        <td style='padding: 8px; text-align: center; border: 1px solid #ddd; font-weight: bold; color: " . ($comprar > 0 ? '#e74a3b' : '#1cc88a') . "'>".($fila['Comprar'] ?? '')."</td>
                    </tr>";
        }
        $html .= "</tbody></table>";
        
        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}