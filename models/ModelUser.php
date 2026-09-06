<?php 
require_once __DIR__ . '/../db/conexion.php';
require_once __DIR__ . '/Usuario.php';

if (!class_exists('ModelUser')) {
    class ModelUser extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function getUsuario(){
            $res=$this->con->query("select usuarios.idUsuario,nombreEmp,apellido,username,pass,usuarios.id_Rol,nombreRol,COALESCE(usuarios.activo, 1) as activo from empleado inner join usuarios on empleado.idUsuario=usuarios.idUsuario left join rol on usuarios.id_Rol=rol.id_Rol");
            $r=array();
            if ($res) {
                while($row=$res->fetch_assoc()) {
                    $r[]=$row;
                }
            }
            return $r;
        }

        public function getUsuarioCli(){
            $res=$this->con->query("select usuarios.idUsuario,nombreCliente,username,pass,usuarios.id_Rol,nombreRol,COALESCE(usuarios.activo, 1) as activo from cliente inner join usuarios on cliente.idUsuario=usuarios.idUsuario left join rol on usuarios.id_Rol=rol.id_Rol");
            $r=array();
            if ($res) {
                while($row=$res->fetch_assoc()) {
                    $r[]=$row;
                }
            }
            return $r;
        }

        public function getUsuarios(){
            $res=$this->con->query("select usuarios.idUsuario,username,pass,nombreRol,usuarios.id_Rol,COALESCE(usuarios.activo, 1) as activo from usuarios left join rol on usuarios.id_Rol=rol.id_Rol ORDER BY usuarios.idUsuario ASC");
            $r=array();
            if ($res) {
                while($row=$res->fetch_assoc()) {
                    $r[]=$row;
                }
            }
            return $r;
        }

        public function getSessionEmp($correo = null){
            if ($correo === null) {
                $correo = $_SESSION["s1"] ?? ($_SESSION['s2'] ?? '');
            }
            $correo = $this->con->real_escape_string($correo);
            $res=$this->con->query("select nombreEmp,apellido from empleado inner join usuarios on empleado.idUsuario=usuarios.idUsuario where username='$correo'");
            $r=array();
            if ($res) {
                while($row=$res->fetch_assoc()) {
                    $r[]=$row;
                }
            }
            return $r;
        }

        public function getRol(){
            $res=$this->con->query("select * from rol WHERE COALESCE(activo, 1) = 1 ORDER BY id_Rol ASC");
            $r=array();
            if ($res) {
                while($row=$res->fetch_assoc()) {
                    $r[]=$row;
                }
            }
            return $r;
        }

        public function cambiarEstadoUsuario(int $idUsuario, int $nuevoEstado): bool
        {
            $stmt = $this->con->prepare("UPDATE usuarios SET activo = ?, actualizado_en = NOW() WHERE idUsuario = ?");
            if (!$stmt) return false;
            $stmt->bind_param("ii", $nuevoEstado, $idUsuario);
            $res = $stmt->execute();
            $stmt->close();
            return (bool)$res;
        }

        public function modificarUsuario($u){
            $a=$u->getUsername();
            $b=$u->getPass();
            $c=$u->getIdRol();
            $d=$u->getIdUsuario();
            $res=$this->con->prepare("UPDATE `usuarios` SET username=?,pass=?,id_Rol=?,actualizado_en=NOW() WHERE `usuarios`.`idUsuario` = ?");
            $res->bind_param("ssii",$a,$b,$c,$d);
            $res->execute();
        }
    }
}
?>
