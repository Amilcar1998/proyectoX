$(document).ready(function() {

	
		$('.table-responsive').DataTable({
			"language": {
				"decimal": "",
				"emptyTable": "No hay datos disponibles en la tabla",
				"info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
				"infoEmpty": "Mostrando 0 a 0 de 0 registros",
				"infoFiltered": "(filtrado de _MAX_ registros totales)",
				"lengthMenu": "Mostrar _MENU_ registros",
				"loadingRecords": "Cargando...",
				"processing": "Procesando...",
				"search": "Buscar:",
				"zeroRecords": "No se encontraron resultados",
				"paginate": {
					"first": "Primero",
					"last": "Último",
					"next": "Siguiente",
					"previous": "Anterior"
				},
				"aria": {
					"sortAscending": ": activar para ordenar la columna de manera ascendente",
					"sortDescending": ": activar para ordenar la columna de manera descendente"
				}
			}
		});



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