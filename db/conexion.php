<?php
    if (file_exists(__DIR__ . '/parametros.php')) {
        require_once __DIR__ . '/parametros.php';
    } else {
        date_default_timezone_set('America/El_Salvador');
        if (!defined('SERVER')) {
            $server   = getenv('MYSQLHOST') ?: getenv('DB_HOST') ?: getenv('SERVER') ?: "localhost";
            $user     = getenv('MYSQLUSER') ?: getenv('DB_USER') ?: getenv('USER') ?: "root";
            $password = getenv('MYSQLPASSWORD') ?: getenv('DB_PASSWORD') ?: getenv('PASSWORD') ?: "";
            $database = getenv('MYSQLDATABASE') ?: getenv('DB_NAME') ?: getenv('BASE') ?: "concentrados";
            $port     = (int)(getenv('MYSQLPORT') ?: getenv('DB_PORT') ?: 3306);

            define("SERVER", $server);
            define("USER", $user);
            define("PASSWORD", $password);
            define("BASE", $database);
            define("PORT", $port);
            define("CHAR", "utf8mb4");
        }
    }
    require_once __DIR__ . '/../models/HelperUrl.php';
   if (!class_exists('Conexion')) {
       class Conexion{
          protected $con;
           function __construct(){
              $puerto = defined('PORT') ? PORT : 3306;
              mysqli_report(MYSQLI_REPORT_OFF);
              $this->con = @new mysqli(SERVER, USER, PASSWORD, BASE, $puerto);
              if ($this->con->connect_errno) {
                 throw new Exception("Error al conectar a MySQL en [" . SERVER . ":" . $puerto . "] usuario [" . USER . "] base [" . BASE . "]: " . $this->con->connect_error . ". Por favor verifica las variables de entorno de MySQL en Railway.");
              }
              $this->con->set_charset(CHAR);
              $this->con->query("SET time_zone = '-06:00'");
           }
          public function obtenerConexion(): mysqli {
             return $this->con;
          }
          public function getConnection(): mysqli {
             return $this->obtenerConexion();
          }
           public function obtenerNombreUsuario(): string {
              $nombre = '';
              if (isset($_SESSION['s1'])) {
                  $correo = $this->con->real_escape_string($_SESSION['s1']);
                  $res = $this->con->query("SELECT e.nombreEmp, e.apellido FROM empleado e INNER JOIN usuarios u ON e.idUsuario = u.idUsuario WHERE u.username = '$correo' LIMIT 1");
                  if ($res && $fila = $res->fetch_assoc()) {
                      $nombre = trim($fila['nombreEmp'] . ' ' . $fila['apellido']);
                  }
                  if (empty($nombre)) {
                      $nombre = (string)$_SESSION['s1'];
                  }
              } elseif (isset($_SESSION['s2'])) {
                  $correo = $this->con->real_escape_string($_SESSION['s2']);
                  $res = $this->con->query("SELECT e.nombreEmp, e.apellido FROM empleado e INNER JOIN usuarios u ON e.idUsuario = u.idUsuario WHERE u.username = '$correo' LIMIT 1");
                  if ($res && $fila = $res->fetch_assoc()) {
                      $nombre = trim($fila['nombreEmp'] . ' ' . $fila['apellido']);
                  }
                  if (empty($nombre)) {
                      $nombre = (string)$_SESSION['s2'];
                  }
              } elseif (isset($_SESSION['c1'])) {
                  $correo = $this->con->real_escape_string($_SESSION['c1']);
                  $res = $this->con->query("SELECT c.NombreCliente, c.apellidosCliente FROM cliente c INNER JOIN usuarios u ON c.idUsuario = u.idUsuario WHERE u.username = '$correo' LIMIT 1");
                  if ($res && $fila = $res->fetch_assoc()) {
                      $nombre = trim($fila['NombreCliente'] . ' ' . $fila['apellidosCliente']);
                  }
                  if (empty($nombre)) {
                      $nombre = (string)$_SESSION['c1'];
                  }
              }
              return $nombre;
           }
           public function getNombreUsuario(): string {
              return $this->obtenerNombreUsuario();
           }
       }
   }
?>
