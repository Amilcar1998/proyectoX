<?php
include __DIR__ . '/../db/conexion.php';

if (!class_exists('UsuarioModel')) {
    class UsuarioModel extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        function validarUsuario($login, $pass)
        {
            $a = $login;
            $b = sha1($pass);
            $para = $this->con->prepare("SELECT * FROM usuarios WHERE username=? AND pass=? AND id_Rol='3'");
            $para->bind_param("ss", $a, $b);
            $para->execute();
            $para->store_result();
            if ($para->num_rows > 0) {
                return 1;
            }

            $a = $login;
            $b = sha1($pass);
            $para = $this->con->prepare("SELECT * FROM usuarios WHERE username=? AND pass=? AND id_Rol='1'");
            $para->bind_param("ss", $a, $b);
            $para->execute();
            $para->store_result();
            if ($para->num_rows > 0) {
                return 2;
            }

            $a = $login;
            $b = sha1($pass);
            $para = $this->con->prepare("SELECT * FROM usuarios WHERE username=? AND pass=? AND id_Rol='2'");
            $para->bind_param("ss", $a, $b);
            $para->execute();
            $para->store_result();
            if ($para->num_rows > 0) {
                return 3;
            }

            return 0;
        }
    }
}
