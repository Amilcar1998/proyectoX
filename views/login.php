<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Login</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="Recursos/validaciones.js"></script>
  <!-- Custom styles for this template-->
  <link href="vendor/sb-admin.css" rel="stylesheet">

</head>
<body style="background-image: url(Recursos/OIP.jpg);">

  <div class="container" >
    <div class="card card-login mx-auto mt-5">
      <div class="card-header bg-info">Login</div>
      <div class="card-body">
        <form method="POST" action="#">
              <div class="container">
                <div> 
                  Correo Electonico
              <input type="email" id="login" name="login" class="form-control" placeholder="Email address" required="required" autofocus="autofocus"></div>
              <div>
                Password
              <input type="password" id="pass" class="form-control" name="pass" placeholder="Password" required="required">
              </div>
           </div>
           <hr>
          <button class="btn btn-primary btn-block" id="validar" name="validar">Entrar</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

</body>

</html>
