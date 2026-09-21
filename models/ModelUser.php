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
            $res=$this->con->query("select usuarios.idUsuario,p.nombrePersona as nombrePersona,p.nombrePersona as nombreCliente,p.apellidoPersona as apellidoPersona,p.apellidoPersona as apellidosCliente,username,pass,usuarios.id_Rol,nombreRol,COALESCE(usuarios.activo, 1) as activo from persona p inner join usuarios on p.idUsuario=usuarios.idUsuario left join rol on usuarios.id_Rol=rol.id_Rol");
            $r=array();
            if ($res) {
                while($row=$res->fetch_assoc()) {
                    $r[]=$row;
                }
            }
            return $r;
        }

        public function getUsuarios(){
            $res=$this->con->query("select usuarios.idUsuario,username,pass,nombreRol,usuarios.id_Rol,COALESCE(usuarios.activo, 1) as activo, p.nombrePersona, p.apellidoPersona, p.nombrePersona as NombreCliente, p.apellidoPersona as apellidosCliente from usuarios left join rol on usuarios.id_Rol=rol.id_Rol left join persona p on usuarios.idUsuario=p.idUsuario ORDER BY usuarios.idUsuario ASC");
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
                $correo = $_SESSION["s1"] ?? ($_SESSION['s2'] ?? ($_SESSION['c1'] ?? ''));
            }
            $correo = $this->con->real_escape_string($correo);
            // 1. Buscar en tabla persona
            $res = $this->con->query("SELECT p.nombrePersona AS nombreEmp, p.apellidoPersona AS apellido FROM persona p INNER JOIN usuarios u ON p.idUsuario=u.idUsuario WHERE u.username='$correo' LIMIT 1");
            $r = array();
            if ($res && $res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $r[] = $row;
                }
                return $r;
            }
            // 2. Fallback a empleado
            $res = $this->con->query("SELECT nombreEmp,apellido FROM empleado INNER JOIN usuarios ON empleado.idUsuario=usuarios.idUsuario WHERE username='$correo'");
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

        public function modificarUsuarioSinPass(int $idUsuario, string $username, int $idRol): bool
        {
            $res=$this->con->prepare("UPDATE `usuarios` SET username=?,id_Rol=?,actualizado_en=NOW() WHERE `usuarios`.`idUsuario` = ?");
            if (!$res) return false;
            $res->bind_param("sii", $username, $idRol, $idUsuario);
            $exito = $res->execute();
            $res->close();
            return (bool)$exito;
        }
    }
}
?>
