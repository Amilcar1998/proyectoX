<?php
include '../db/conexion.php';
include '../controllers/vendor/autoload.php';

use Mpdf\Mpdf;

class ReporteInventarioMP extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function getInventario(): array {
        $sql = "SELECT mp.idMateriaPrima AS Codigo, mp.NombreMP AS Materia_Prima,
                       i.Existencias AS Existencias,
                       CASE WHEN i.Existencias <= 5 THEN 'CRITICO'
                            WHEN i.Existencias <= 15 THEN 'BAJO'
                            WHEN i.Existencias <= 30 THEN 'MEDIO'
                            ELSE 'NORMAL'
                       END AS Estado_Inventario
                FROM inventario i
                INNER JOIN materiaprima mp ON i.idMateriaPrima = mp.idMateriaPrima
                ORDER BY Estado_Inventario, Materia_Prima";
        $res = $this->con->query($sql);
        if (!$res) {
            error_log('SQL Error in getInventario: ' . $this->con->error);
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getInventarioEscaso(): array {
        $sql = "SELECT mp.idMateriaPrima AS Codigo, mp.NombreMP AS Materia_Prima,
                       i.Existencias AS Stock,
                       CASE WHEN i.Existencias <= 5 THEN 'URGENTE'
                            WHEN i.Existencias <= 10 THEN 'REABASTECER'
                            ELSE 'NORMAL'
                       END AS Estado
                FROM inventario i
                INNER JOIN materiaprima mp ON mp.idMateriaPrima = i.idMateriaPrima
                WHERE i.Existencias <= 10
                ORDER BY i.Existencias ASC, mp.NombreMP ASC";
        $res = $this->con->query($sql);
        if (!$res) {
            error_log('SQL Error in getInventarioEscaso: ' . $this->con->error);
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    private function generarEncabezado(string $titulo): string {
        $fecha = getdate();
        $fechaStr = $fecha['mday']."-".$fecha['mon']."-".$fecha['year']."  ".$fecha['hours'].":".$fecha['minutes'].":".$fecha['seconds'];
        
        return "<table style='width: 100%; border-collapse: collapse; margin-bottom: 15px;'>
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
                 <h3>$titulo</h3>";
    }

    public function generarPDF(array $data, string $titulo, string $header = ''): void {
        if ($header !== '') {
            $html = $header;
        } else {
            $esGeneral = stripos($titulo, 'INVENTARIO GENERAL') !== false;
            $html = $this->generarEncabezado($titulo);
            $html .= "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 11px;'>
                        <thead style='background-color: " . ($esGeneral ? '#4e73df' : '#f6c23e') . "; color: " . ($esGeneral ? 'white' : '#000') . ";'>
                            <tr>
                                <th style='padding: 10px; text-align: center;'>CÓDIGO</th>
                                <th style='padding: 10px; text-align: center;'>MATERIA PRIMA</th>
                                <th style='padding: 10px; text-align: center;'>" . ($esGeneral ? 'EXISTENCIAS' : 'STOCK') . "</th>
                                <th style='padding: 10px; text-align: center;'>ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>";
            
            foreach ($data as $fila) {
                $estado = $fila['Estado_Inventario'] ?? ($fila['Estado'] ?? '');
                $colorFila = '';
                if (in_array($estado, ['CRITICO', 'URGENTE'])) $colorFila = 'background-color: #f8d7da;';
                elseif (in_array($estado, ['BAJO', 'REABASTECER'])) $colorFila = 'background-color: #fff3cd;';
                elseif ($estado === 'MEDIO') $colorFila = 'background-color: #fff3cd;';
                elseif ($estado === 'NORMAL') $colorFila = 'background-color: #d4edda;';
                
                $campoStock = $esGeneral ? ($fila['Existencias'] ?? '') : ($fila['Stock'] ?? '');
                $campoEstado = $esGeneral ? ($fila['Estado_Inventario'] ?? '') : ($fila['Estado'] ?? '');
                
                $html .= "<tr style='$colorFila'>
                            <td style='padding: 8px; text-align: center; border: 1px solid #ddd;'>".($fila['Codigo'] ?? '')."</td>
                            <td style='padding: 8px; text-align: left; border: 1px solid #ddd;'>".($fila['Materia_Prima'] ?? '')."</td>
                            <td style='padding: 8px; text-align: center; border: 1px solid #ddd;'>$campoStock</td>
                            <td style='padding: 8px; text-align: center; border: 1px solid #ddd; font-weight: bold;'>$campoEstado</td>
                          </tr>";
            }
            $html .= "</tbody></table>";
        }
        
        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}
