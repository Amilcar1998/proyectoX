$(document).ready(function() {
	$(document).on('click', '#insertar', function(event) {
		
		var idEmp = $("#txtIdEmpleado").val();
		var nombre = $("#txtNombreE").val();
		var apellido = $("#txtApellidos").val();
		var correo = $("#txtCorreo").val();
		var genero = $("#txtGenero").val();
		var rol = $("#idRol").val();
		var pass = $("#txtPass").val();
		var destino = "../controllerEmpleado.php";
		var datos = {'idEmp':idEmp,'nombre':nombre,'apellido':apellido,'correo':correo,'genero':genero,'rol':rol,'pass':pass}
		console.log(datos);

		$.ajax({
			url:destino ,
			type: 'POST',
			data:datos ,
		})
		.done(function(Respuesta) {
			Swal.fire("buen trabajo",Respuesta,"success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	});
	
});