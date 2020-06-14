$(document).ready(function() {
	$('.form').hide();
			
	$(document).on('click', '.Nagregar', function() {
		$('.modificar').hide();
		$('.eliminar').hide();
		$('.reset').show();
		$('.agregar').show();
    	
    	document.getElementById('miForm').reset();

		
	});
	$(document).on('click', '.cargar', function() {
		$('.modificar').show();
		$('.eliminar').show();
		$('.reset').show();
		$('.agregar').hide();
	});
	$(document).on('click', '#det', function() {
		
		document.getElementById('miForm').reset();
	});

	
});