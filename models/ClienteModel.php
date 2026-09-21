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

        function getAddUs($u, int $idEmpresa = 1){
            $a = "";
            $b = $u->getUsername();
            $c = $u->getPass();
            $d = $u->getIdRol();
            $debe = 1;
            if ($idEmpresa <= 0) $idEmpresa = 1;
            $para = $this->con->prepare("INSERT INTO usuarios(idUsuario,username,pass,id_Rol,idEmpresa,debe_cambiar_pass) VALUES(?,?,?,?,?,?)");
            $para->bind_param('sssiii', $a, $b, $c, $d, $idEmpresa, $debe);
            $para->execute();
            $para->close();
        }

        function getCliente(int $idEmpresa = 0){
            $condicion = ($idEmpresa > 0) ? " AND (p.idEmpresa = " . (int)$idEmpresa . " OR u.idEmpresa = " . (int)$idEmpresa . ") " : "";
            $sql = "SELECT p.idPersona AS idCliente, p.nombrePersona, p.apellidoPersona, p.nombrePersona AS NombreCliente, p.apellidoPersona AS apellidosCliente, p.telefono, p.edad, p.genero, p.idUsuario, u.username 
                    FROM persona p 
                    INNER JOIN usuarios u ON p.idUsuario = u.idUsuario 
                    INNER JOIN rol r ON u.id_Rol = r.id_Rol 
                    WHERE (u.id_Rol = 3 OR u.id_Rol = 2) $condicion 
                    ORDER BY p.idPersona ASC";
            $res = $this->con->query($sql);
            $r = array();
            if ($res) {
                while($row = $res->fetch_assoc()) {
                    $e = new Cliente($row["idCliente"],$row["nombrePersona"],$row["apellidoPersona"],$row["telefono"],$row["edad"],$row["genero"],$row["idUsuario"],$row["username"]);
                    $r[] = $e;
                }
            }
            return $r;
        }

        function getSessionEmp($correo = null){
            if ($correo === null) {
                $correo = $_SESSION["s1"] ?? ($_SESSION['s2'] ?? ($_SESSION['c1'] ?? ''));
            }
            $correo = $this->con->real_escape_string($correo);
            // 1. Buscar en persona
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

        function getUser(int $idEmpresa = 0){
            $condicion = ($idEmpresa > 0) ? " AND (idEmpresa = " . (int)$idEmpresa . ") " : "";
            $res = $this->con->query("SELECT * FROM usuarios WHERE (id_Rol = 3 OR id_Rol = 2) $condicion");
            $r = array();
            if ($res) {
                while($row = $res->fetch_assoc()) {
                    $r[] = $row;
                }
            }
            return $r;
        }

        function agregarCliente($e, int $idEmpresa = 1){
            $b = $e->getNombreCi();
            $c = $e->getApellidos();
            $d = $e->getTelefono();
            $f = $e->getEdad();
            $g = $e->getGenero();
            $h = (int)$e->getUsuarioC();
            if ($idEmpresa <= 0) $idEmpresa = 1;

            if ($h > 0) {
                $check = $this->con->query("SELECT idPersona FROM persona WHERE idUsuario = $h AND idEmpresa = $idEmpresa LIMIT 1");
                if ($check && $check->num_rows > 0) {
                    return false;
                }
            }

            $res = $this->con->prepare("INSERT INTO persona(nombrePersona,apellidoPersona,telefono,edad,genero,idUsuario,idEmpresa) VALUES(?,?,?,?,?,?,?)");
            $res->bind_param("sssssii", $b, $c, $d, $f, $g, $h, $idEmpresa);
            $res->execute();
            $idInsertado = (int)$this->con->insert_id;
            $res->close();
            return $idInsertado > 0;
        }

        public function registrarClienteAutogenerado(array $datos, int $idEmpresa = 1): array
        {
            require_once __DIR__ . '/UsuarioModel.php';
            $uModel = new UsuarioModel();

            $nombre = trim((string)($datos['nombrePersona'] ?? ($datos['nombreC'] ?? ($datos['nombre'] ?? ''))));
            $apellido = trim((string)($datos['apellidoPersona'] ?? ($datos['apellidoC'] ?? ($datos['apellido'] ?? ''))));
            $telefono = trim((string)($datos['telefono'] ?? ($datos['telefonoC'] ?? '')));
            $edad = trim((string)($datos['edad'] ?? ($datos['edadC'] ?? '')));
            $genero = trim((string)($datos['genero'] ?? ($datos['generoC'] ?? '')));

            if ($idEmpresa <= 0) $idEmpresa = 1;

            // 1. Validar si ya existe persona con mismo nombre y apellido en la empresa
            $stmtCheck = $this->con->prepare("SELECT idPersona FROM persona WHERE nombrePersona = ? AND apellidoPersona = ? AND idEmpresa = ? LIMIT 1");
            if ($stmtCheck) {
                $stmtCheck->bind_param("ssi", $nombre, $apellido, $idEmpresa);
                $stmtCheck->execute();
                $resCheck = $stmtCheck->get_result();
                if ($resCheck && $resCheck->num_rows > 0) {
                    $stmtCheck->close();
                    return ['exito' => false, 'mensaje' => 'Ya existe una persona con ese nombre y apellido registrada en esta empresa.'];
                }
                $stmtCheck->close();
            }

            // 2. Generar nombre de usuario único con estrategia: nombre.apellido<numero>@<dominio_empresa>
            $username = $uModel->generarUsernameUnico($nombre, $apellido, $idEmpresa);

            // 3. Crear el nuevo usuario
            $idUsuarioNuevo = $uModel->crearUsuarioAutogenerado($username, 3, $idEmpresa, '123456');
            if ($idUsuarioNuevo <= 0) {
                return ['exito' => false, 'mensaje' => 'No se pudo crear la cuenta de usuario para el cliente.'];
            }

            // 4. Crear el registro en tabla persona
            $stmtPersona = $this->con->prepare("INSERT INTO persona (nombrePersona, apellidoPersona, telefono, edad, genero, idUsuario, idEmpresa) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if (!$stmtPersona) {
                return ['exito' => false, 'mensaje' => 'Error al preparar inserción en persona.'];
            }
            $stmtPersona->bind_param("sssssii", $nombre, $apellido, $telefono, $edad, $genero, $idUsuarioNuevo, $idEmpresa);
            $stmtPersona->execute();
            $idPersona = (int)$this->con->insert_id;
            $stmtPersona->close();

            if ($idPersona <= 0) {
                return ['exito' => false, 'mensaje' => 'Error al guardar los datos de la persona.'];
            }

            return [
                'exito' => true,
                'idPersona' => $idPersona,
                'idUsuario' => $idUsuarioNuevo,
                'username' => $username,
                'mensaje' => "Cliente y cuenta creados exitosamente con usuario: $username"
            ];
        }

        function modificarCliente($e){
            $a = $e->getNombreCi();
            $b = $e->getApellidos();
            $c = $e->getTelefono();
            $d = $e->getEdad();
            $f = $e->getGenero();
            $g = $e->getUsuarioC();
            $h = $e->getIdCliente();
            $res = $this->con->prepare("UPDATE persona SET nombrePersona=?,apellidoPersona=?,telefono=?,edad=?,genero=?,idUsuario=? WHERE idPersona = ?");
            $res->bind_param("ssssssi", $a, $b, $c, $d, $f, $g, $h);
            $res->execute();
            $res->close();
        }

        function eliminarCliente($e){
            $a = $e->getIdCliente();
            $res = $this->con->prepare("DELETE FROM persona WHERE idPersona=?");
            $res->bind_param('i', $a);
            $res->execute();
            $res->close();
        }
    }
}
?>