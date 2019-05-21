
//variable usada para almacernar los departamentos y usarla si es necesario en otros casos
var Departments;

	
function getAjaxDepartments(){


	return $.ajax({
		type:'GET',
		dataType: "json",
		url: baseUrl+"Departments/getAllDepartments/",
	});

}	


/**
* Función usada para obtener los departamentos y agregarlos a un dropdown especificado
*
*/	
function getDepartments(){

	getAjaxDepartments().done(function(response) {
			
		//asignamos los departamentos
		Departments = response;

		fillSelectDepartments();

		$("#selectDepartment").trigger('change');


	})
	.fail(function(x) {
	    console.log(x);
	});
         

}


function fillSelectDepartments(){

	// console.log(Departments);     

	var options = $("#selectDepartment");

	options.append(new Option('Todos','Todos'));
			
	$.each(Departments, function(index) {

		options.append(new Option(Departments[index]['Departments'].name, Departments[index]['Departments'].id));

	});

}



//llamamos la función
getDepartments();