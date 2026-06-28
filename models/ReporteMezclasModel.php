<?php
include '../db/conexion.php';

use Mpdf\Mpdf;

class ReporteMezclasModel extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function getMezclas(): array {
        $sql = "SELECT r.idReceta AS Codigo_Receta, r.nombreReceta AS Mezcla,
                       r.PrecioUnitario AS Precio_Venta, mp.NombreMP AS Materia_Prima,
                       dr.cantidaSa AS Cantidad
                FROM receta r
                INNER JOIN detallereceta dr ON r.idReceta = dr.IdReceta
                INNER JOIN materiaprima mp ON dr.idMateriaPrima = mp.idMateriaPrima
                ORDER BY r.nombreReceta, mp.NombreMP";
        $res = $this->con->query($sql);
        if (!$res) {
            error_log('SQL Error in getMezclas: ' . $this->con->error);
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
                 <table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 11px;'>
                     <thead style='background-color: #1cc88a; color: white;'>
                         <tr>
                             <th style='padding: 10px; text-align: center;'>CÓDIGO RECETA</th>
                             <th style='padding: 10px; text-align: center;'>MEZCLA</th>
                             <th style='padding: 10px; text-align: center;'>PRECIO VENTA</th>
                             <th style='padding: 10px; text-align: center;'>MATERIA PRIMA</th>
                             <th style='padding: 10px; text-align: center;'>CANTIDAD</th>
                         </tr>
                     </thead>
                     <tbody>";
        
        foreach ($data as $fila) {
            $html .= "<tr>
                        <td style='padding: 8px; text-align: center; border: 1px solid #ddd;'>".($fila['Codigo_Receta'] ?? '')."</td>
                        <td style='padding: 8px; text-align: left; border: 1px solid #ddd;'>".($fila['Mezcla'] ?? '')."</td>
                        <td style='padding: 8px; text-align: right; border: 1px solid #ddd;'>".($fila['Precio_Venta'] ?? '')."</td>
                        <td style='padding: 8px; text-align: left; border: 1px solid #ddd;'>".($fila['Materia_Prima'] ?? '')."</td>
                        <td style='padding: 8px; text-align: center; border: 1px solid #ddd;'>".($fila['Cantidad'] ?? '')."</td>
                    </tr>";
        }
        $html .= "</tbody></table>";
        
        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}