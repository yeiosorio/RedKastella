//variable usada para almacernar los departamentos y usarla si es necesario en otros casos
var Departments;

    
/**
* Función usada para obtener los departamentos y agregarlos a un dropdown especificado
*
*/  
function getDepartments(){

    $.ajax({
        type:'GET',
        dataType: "json",
        url: baseUrl+"Departments/getAllDepartments/",
        success: function(response) {

            //asignamos los departamentos
            Departments = response;
            // console.log(Departments);     

            var options = $("#selectDepartment");
            options.append(new Option('Seleccionar','0'));
            $.each(Departments, function(index) {

                options.append(new Option(Departments[index]['Departments'].name, Departments[index]['Departments'].id));

            });

            $("#selectDepartment").trigger('change');


        },
        error: function(response) {

        }

    });

}

//llamamos la función
getDepartments();




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

            options.append(new Option('Seleccionar','0'));
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




$('#addUserForm').submit(function(e){

    if ($('#selectMunicipality').val() == 0) {
     
        e.preventDefault();
        $('.error-select-city').removeClass('no-display');
     }

});










