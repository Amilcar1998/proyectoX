$(document).ready(function() {
	$('.form').hide();
			
	$(document).on('click', '.Nagregar', function() {
		$('.modificar').hide();
		$('.eliminar').hide();
		$('.reset').show();
		$('.agregar').show()

		
	});
	$(document).on('click', '.cargar', function() {
		$('.modificar').show();
		$('.eliminar').show();
		$('.reset').show();
		$('.agregar').hide();
	});
});