<?php 
require_once __DIR__ . "/../db/conexion.php";
require_once __DIR__ . "/../models/Cliente.php";
require_once __DIR__ . '/../models/Usuario.php';

if (!class_exists('ClienteModel')) {
    class ClienteModel extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        function getAddUs($u){
            $a = "";
            $b = $u->getUsername();
            $c = $u->getPass();
            $d = $u->getIdRol();
            $debe = 1;
            $para = $this->con->prepare("INSERT INTO usuarios(idUsuario,username,pass,id_Rol,debe_cambiar_pass) VALUES(?,?,?,?,?)");
            $para->bind_param('ssssi', $a, $b, $c, $d, $debe);
            $para->execute();
            $para->close();
        }

        function getCliente(){
            $res = $this->con->query("SELECT c.idCliente, c.NombreCliente, c.apellidosCliente, c.telefono, c.edad, c.genero, c.idUsuario, u.username FROM cliente c INNER JOIN usuarios u ON c.idUsuario=u.idUsuario");
            $r = array();
            while($row = $res->fetch_assoc()) {
                $e = new Cliente($row["idCliente"],$row["NombreCliente"],$row["apellidosCliente"],$row["telefono"],$row["edad"],$row["genero"],$row["idUsuario"],$row["username"]);
                $r[] = $e;
            }
            return $r;
        }

        function getSessionEmp($correo = null){
            if ($correo === null) {
                $correo = $_SESSION["s1"] ?? '';
            }
            $correo = $this->con->real_escape_string($correo);
            $res = $this->con->query("SELECT nombreEmp,apellido FROM empleado INNER JOIN usuarios ON empleado.idUsuario=usuarios.idUsuario WHERE username='$correo'");
            $r = array();
            while($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
            return $r;
        }

        function getUser(){
            $res = $this->con->query("SELECT * FROM usuarios WHERE id_Rol ='2'");
            $r = array();
            while($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
            return $r;
        }

        function agregarCliente($e){
            $a = "";
            $b = $e->getNombreCi();
            $c = $e->getApellidos();
            $d = $e->getTelefono();
            $f = $e->getEdad();
            $g = $e->getGenero();
            $h = $e->getUsuarioC();
            $res = $this->con->prepare("INSERT INTO cliente(idCliente,NombreCliente,apellidosCliente,telefono,edad,genero,idUsuario) VALUES(?,?,?,?,?,?,?)");
            $res->bind_param("ssssssi", $a, $b, $c, $d, $f, $g, $h);
            $res->execute();
            $res->close();
        }

        function modificarCliente($e){
            $a = $e->getNombreCi();
            $b = $e->getApellidos();
            $c = $e->getTelefono();
            $d = $e->getEdad();
            $f = $e->getGenero();
            $g = $e->getUsuarioC();
            $h = $e->getIdCliente();
            $res = $this->con->prepare("UPDATE cliente SET NombreCliente=?,apellidosCliente=?,telefono=?,edad=?,genero=?,idUsuario=? WHERE idCliente = ?");
            $res->bind_param("ssssssi", $a, $b, $c, $d, $f, $g, $h);
            $res->execute();
            $res->close();
        }

        function eliminarCliente($e){
            $a = $e->getIdCliente();
            $res = $this->con->prepare("DELETE FROM cliente WHERE idCliente=?");
            $res->bind_param('i', $a);
            $res->execute();
            $res->close();
        }
    }
}
?>