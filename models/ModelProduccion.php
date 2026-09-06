<?php 
require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/../models/produccion.php';

class ModelProduccion extends Conexion
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerTabla(int $idEmpresa = 0): array
    {
        return $this->getProduccion($idEmpresa);
    }

    public function listarTodos(int $idEmpresa = 0): array
    {
        return $this->getProduccion($idEmpresa);
    }

    public function getProduccion(int $idEmpresa = 0): array
    {
        $condicion = "";
        if ($idEmpresa > 0) {
            $condicion = " WHERE (p.idEmpresa = " . (int)$idEmpresa . " OR ped.idEmpresa = " . (int)$idEmpresa . ") ";
        }
        $sql = "SELECT p.idProduccion, p.idEmpresa, p.fechaP, p.estadoP, p.idPedido, ped.fechaPedido, c.NombreCliente, e.nombreEmp,
                       COALESCE(emp.nombreEmpresa, 'Concentrados El Gordito') AS nombreEmpresa
                FROM produccion p 
                LEFT JOIN empleado e ON p.idEmpleado = e.idEmpleado 
                LEFT JOIN pedido ped ON p.idPedido = ped.idPedido 
                LEFT JOIN cliente c ON ped.idCliente = c.idCliente 
                LEFT JOIN empresas emp ON COALESCE(p.idEmpresa, ped.idEmpresa) = emp.idEmpresa
                $condicion
                ORDER BY p.idProduccion DESC";

        $res = $this->con->query($sql);
        $r = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        return $r;
    }

    public function getPedido($id): array
    {
        $id = (int)$id;
        $res = $this->con->query("SELECT detallePedido.idReceta, nombreReceta, SUM(cantidad) AS total_Unidades 
                                  FROM detallePedido 
                                  INNER JOIN receta ON detallePedido.idReceta = receta.idReceta 
                                  WHERE idPedido = '$id' 
                                  GROUP BY detallePedido.idReceta");
        $r = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        return $r;
    }

    public function getObtenerReceta($receta): array
    {
        $receta = (int)$receta;
        $res = $this->con->query("SELECT idDetalleReceta, cantidaSa, inventario.idInventario, Existencias, (cantidaSa/100) AS quintal 
                                  FROM detalleReceta 
                                  INNER JOIN inventario ON detalleReceta.idInventario = inventario.idInventario 
                                  WHERE idReceta = '$receta' 
                                  GROUP BY inventario.idMateriaPrima");
        $r = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        return $r;
    }

    public function getSessionEmp($correo): array
    {
        $correo = $this->con->real_escape_string((string)$correo);
        $res = $this->con->query("SELECT idEmpleado, nombreEmp, apellido 
                                  FROM empleado 
                                  INNER JOIN usuarios ON empleado.idUsuario = usuarios.idUsuario 
                                  WHERE username = '$correo' 
                                  LIMIT 1");
        $r = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        return $r;
    }

    public function validarProduccion($inventario, $total): int
    {
        $a = (int)$inventario;
        $b = (float)$total;
        $para = $this->con->prepare("SELECT idInventario FROM inventario WHERE idInventario = ? AND Existencias >= ?");
        if ($para) {
            $para->bind_param("id", $a, $b);
            $para->execute();
            $para->store_result();
            if ($para->num_rows > 0) {
                $para->close();
                return 1;
            }
            $para->close();
        }
        return 0;
    }

    public function alterPedido($id): void
    {
        $id = (int)$id;
        $para = $this->con->prepare("UPDATE `pedido` SET `idEstadoPedido` = 2 WHERE `idPedido` = ?");
        if ($para) {
            $para->bind_param("i", $id);
            $para->execute();
            $para->close();
        }
    }

    public function alterProduccion($idProduccion): void
    {
        $idProduccion = (int)$idProduccion;
        $para = $this->con->prepare("UPDATE `produccion` SET `estadoP` = 'Completado' WHERE `idProduccion` = ?");
        if ($para) {
            $para->bind_param("i", $idProduccion);
            $para->execute();
            $para->close();
        }
    }

    public function TerminarPedido($id): void
    {
        $id = (int)$id;
        $para = $this->con->prepare("UPDATE `pedido` SET `idEstadoPedido` = 3 WHERE `idPedido` = ?");
        if ($para) {
            $para->bind_param("i", $id);
            $para->execute();
            $para->close();
        }
    }

    public function insertar($p, int $idEmpresa = 1): bool
    {
        $fecha = $p->getFechaProduccion();
        $estado = $p->getEstadoProduccion();
        $idPedido = (int)$p->getIdPedido();
        $idEmp = (int)$p->getIdEmpleado();
        if ($idEmpresa <= 0) $idEmpresa = 1;

        $para = $this->con->prepare("INSERT INTO produccion (fechaP, estadoP, idPedido, idEmpleado, idEmpresa) VALUES (?, ?, ?, ?, ?)");
        if ($para) {
            $para->bind_param('ssiii', $fecha, $estado, $idPedido, $idEmp, $idEmpresa);
            $ok = $para->execute();
            $para->close();
            return $ok;
        }
        return false;
    }

    public function actualizarInventario($Inv, $inventario): void
    {
        $invRestante = (float)$Inv;
        $idInv = (int)$inventario;
        $para = $this->con->prepare("UPDATE `inventario` SET `Existencias` = ? WHERE `idInventario` = ?");
        if ($para) {
            $para->bind_param("di", $invRestante, $idInv);
            $para->execute();
            $para->close();
        }
    }

    public function eliminar($p): bool
    {
        $a = (int)$p->getIdProduccion();
        $res = $this->con->prepare("DELETE FROM `produccion` WHERE `idProduccion` = ?");
        if ($res) {
            $res->bind_param('i', $a);
            $ok = $res->execute();
            $res->close();
            return $ok;
        }
        return false;
    }
}