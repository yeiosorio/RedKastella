

//variable usada para almacernar las ciudades y usarlas si es necesario en otros casos
var municipalities;

/**
* Función usada para obtener las ciudades y agregarlas a un dropdown especificado
*
*/	
function getMunicipalites(){

	$.ajax({
		type:'POST',
		data:{departmentId: $("#selectDepartment").val()},
		dataType: "json",
		url: baseUrl+"Municipalities/getCitiesByDepartmentId/",
		success: function(response) {

			//asignamos los departamentos
		    municipalities = response;
			// console.log(municipalities);     

			var options = $("#selectMunicipality");
			options.empty();

			options.append(new Option('Todas','Todas'));
			$.each(municipalities, function(index) {

				options.append(new Option(municipalities[index]['Municipality'].municipality, municipalities[index]['Municipality'].id));

			});

		},
		error: function(response) {

		}

	});

}

//detectamos el cambio en la selección de departamentos
$("#selectDepartment").on('change',function(){

	//llamamos la función
	getMunicipalites();


});
