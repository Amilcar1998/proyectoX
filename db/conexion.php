<?php
   require_once 'parametros.php';
   if (!class_exists('Conexion')) {
       class Conexion{
          protected $con;
          function __construct(){
             $this->con=new mysqli(SERVER,USER,PASSWORD,BASE);
             if ($this->con->connect_error) {
                throw new mysqli_sql_exception("Connection failed: " . $this->con->connect_error);
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
