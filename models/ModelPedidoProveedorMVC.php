<?php

include_once __DIR__ . "/../db/conexion.php";
include_once __DIR__ . '/../models/pedidoProveedor.php';

class ModelPedidoProveedor extends Conexion {

    public function __construct(){
        parent::__construct();
    }

    /**
     * Obtiene el listado completo de pedidos a proveedor con datos relacionales y filtro tenant
     * @param int $idEmpresa
     * @return array
     */
    public function obtenerTabla(int $idEmpresa = 0): array {
        $condicion = "";
        if ($idEmpresa > 0) {
            $condicion = " WHERE (pp.idEmpresa = " . (int)$idEmpresa . ") ";
        }
        $consulta = "
            SELECT pp.idPedido, pp.idEmpresa, pp.idProveedor, p.nombreProveedor, pp.idEmpleado, 
                   CONCAT(e.nombreEmp, ' ', e.apellido) AS empleado, 
                   pp.idMateriaPrima, mp.NombreMP, pp.fecha, pp.cantidadMP, pp.monto, pp.precioMP,
                   COALESCE(emp.nombreEmpresa, 'Concentrados El Gordito') AS nombreEmpresa
            FROM pedidoproveedor pp 
            INNER JOIN proveedor p ON pp.idProveedor = p.idProveedor 
            INNER JOIN empleado e ON pp.idEmpleado = e.idEmpleado 
            INNER JOIN materiaprima mp ON pp.idMateriaPrima = mp.idMateriaPrima
            LEFT JOIN empresas emp ON pp.idEmpresa = emp.idEmpresa
            $condicion
            ORDER BY pp.idPedido DESC
        ";
        $res = $this->con->query($consulta);
        if (!$res) {
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getTabla(int $idEmpresa = 0): array {
        return $this->obtenerTabla($idEmpresa);
    }

    public function listarTodos(int $idEmpresa = 0): array {
        return $this->obtenerTabla($idEmpresa);
    }

    /**
     * Inserta un nuevo pedido a proveedor asignando la empresa activa
     * @param PedidoProveedor $obj
     * @param int $idEmpresa
     * @return bool
     */
    public function insertar($obj, int $idEmpresa = 1): bool {
        $empresaId = $obj->getIdEmpresa() ? (int)$obj->getIdEmpresa() : $idEmpresa;
        if ($empresaId <= 0) $empresaId = 1;
        $stmt = $this->con->prepare("
            INSERT INTO pedidoproveedor (idProveedor, idEmpleado, idMateriaPrima, fecha, cantidadMP, monto, precioMP, idEmpresa) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) {
            return false;
        }
        $proveedor = $obj->getIdProveedor();
        $empleado = $obj->getIdEmpleado();
        $mp = $obj->getIdMateriaPrima();
        $fecha = $obj->getFecha();
        $cantidad = $obj->getCantidadMP();
        $monto = $obj->getMonto();
        $precio = $obj->getPrecioMP();
        $stmt->bind_param("iiisiddi", $proveedor, $empleado, $mp, $fecha, $cantidad, $monto, $precio, $empresaId);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    /**
     * Elimina un pedido a proveedor por ID
     * @param int $idPedido
     * @return bool
     */
    public function eliminar(int $idPedido): bool {
        $stmt = $this->con->prepare("DELETE FROM pedidoproveedor WHERE idPedido = ?");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $idPedido);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    /**
     * Actualiza un pedido a proveedor
     * @param PedidoProveedor $obj
     * @return bool
     */
    public function modificar($obj): bool {
        $stmt = $this->con->prepare("
            UPDATE pedidoproveedor 
            SET idProveedor = ?, idEmpleado = ?, idMateriaPrima = ?, fecha = ?, cantidadMP = ?, monto = ?, precioMP = ? 
            WHERE idPedido = ?
        ");
        if (!$stmt) {
            return false;
        }
        $proveedor = $obj->getIdProveedor();
        $empleado = $obj->getIdEmpleado();
        $mp = $obj->getIdMateriaPrima();
        $fecha = $obj->getFecha();
        $cantidad = $obj->getCantidadMP();
        $monto = $obj->getMonto();
        $precio = $obj->getPrecioMP();
        $id = $obj->getIdPedido();
        $stmt->bind_param("iiisiddi", $proveedor, $empleado, $mp, $fecha, $cantidad, $monto, $precio, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function actualizar($obj): bool {
        return $this->modificar($obj);
    }

    /**
     * Obtiene los datos de sesión del empleado por correo
     * @param string $correo
     * @return array
     */
    public function obtenerSesionEmpleado(string $correo): array {
        $stmt = $this->con->prepare("
            SELECT e.idEmpleado, e.nombreEmp, e.apellido 
            FROM empleado e 
            INNER JOIN usuarios u ON e.idUsuario = u.idUsuario 
            WHERE u.username = ?
        ");
        if (!$stmt) {
            return [];
        }
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $res = $stmt->get_result();
        $r = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
        }
        $stmt->close();
        return $r;
    }

    public function getSessionEmp($correo): array {
        return $this->obtenerSesionEmpleado((string)$correo);
    }

    /**
     * Obtiene los proveedores filtrados por empresa
     * @param int $idEmpresa
     * @return array
     */
    public function obtenerProveedores(int $idEmpresa = 0): array {
        $condicion = ($idEmpresa > 0) ? " WHERE (idEmpresa = " . (int)$idEmpresa . " OR idEmpresa = 1) " : "";
        $res = $this->con->query("SELECT idProveedor, nombreProveedor FROM proveedor $condicion ORDER BY nombreProveedor ASC");
        if (!$res) {
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getProveedores(int $idEmpresa = 0): array {
        return $this->obtenerProveedores($idEmpresa);
    }

    /**
     * Obtiene los empleados filtrados por empresa
     * @param int $idEmpresa
     * @return array
     */
    public function obtenerEmpleados(int $idEmpresa = 0): array {
        $condicion = ($idEmpresa > 0) ? " WHERE (idEmpresa = " . (int)$idEmpresa . ") " : "";
        $res = $this->con->query("SELECT idEmpleado, nombreEmp, apellido FROM empleado $condicion ORDER BY nombreEmp ASC");
        if (!$res) {
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getEmpleados(int $idEmpresa = 0): array {
        return $this->obtenerEmpleados($idEmpresa);
    }

    /**
     * Obtiene las materias primas filtradas por empresa
     * @param int $idEmpresa
     * @return array
     */
    public function obtenerMateriasPrimas(int $idEmpresa = 0): array {
        $condicion = ($idEmpresa > 0) ? " WHERE (idEmpresa = " . (int)$idEmpresa . " OR idEmpresa = 1) " : "";
        $res = $this->con->query("SELECT idMateriaPrima, NombreMP FROM materiaprima $condicion ORDER BY NombreMP ASC");
        if (!$res) {
            return [];
        }
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getMateriasPrimas(int $idEmpresa = 0): array {
        return $this->obtenerMateriasPrimas($idEmpresa);
    }
}
?>