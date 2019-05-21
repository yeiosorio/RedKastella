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




$('#addOrganizationForm').submit(function(e){

    if ($('#selectMunicipality').val() == 0) {
     
        e.preventDefault();
        $('.error-select-city').removeClass('no-display');
     }

});



/**
 * Autocompletado de búsqueda de usuarios por email
 */
$(function() {

    /**
     * Variable que contendra el objeto de tipo magicSuggest para la funcionalidad se selección de tags dinamico
     * @type Object
     */
    var selectedEmails = $('#emailSelection').magicSuggest({
        
        /**
         * no permitimos nuevos entradas
         * @type {Boolean}
         */
        allowFreeEntries: false,

        /**
         * seleccion máxima de tags
         * @type {Number}
         */
        maxSelection: 10,

        /**
         * clase de estilos personalizada
         * @type {String}
         */
        cls:'serach-emails-cont',
        /**
         * url de donde se obtiene los tags de emails
         * @type String
         */
        data: baseUrl+'Users/getUsersByEmail',

        /**
         * Placeholder de entrada
         * @type {String}
         */
        placeholder: 'Escribe los emails de los usuarios invitar',

        /**
         * Identificador de los items devueltos por ajax
         * @type {String}
         */
        valueField: 'id',

        /**
         * nombre a mostrar de los items devueltos por ajax
         * @type {String}
         */
        displayField: 'email'
    });



    /**
     * Detectamos el evento del boton Invitar
     */
    $('#invite').click(function(){

        /**
         * configuración del boton invitar para la animación
         */
        animateButton.selector = '.invite-gls';
        animateButton.iconClass = 'fa fa-user-plus';

        /**
         * Arreglo de indentificadores de usuarios
         * @type Array
         */
        var emailsIds = selectedEmails.getValue(); 
        
        /**
         * Si hay identificadores 
         */
        if (emailsIds.length >0 ) {

            animateButton.loading(true);

            /**
             * Invitamos a los usuarios
             */
            $.ajax({
                    url: baseUrl+'Users/inviteUsers',
                    type: 'post',
                    dataType: 'json',
                    data: {emailsIds: emailsIds},
                    success: function (response) {
                        
                        /**
                         * Si hubo éxito
                         */
                        if (response.success == true) {


                            /**
                             * animación en falso
                             */
                            animateButton.loading(false);
                            
                            /**
                             * recargamos la página
                             */
                            window.location.href = window.location.href;
                        
                        }else{
                        
                            /**
                             * animación en falso
                             */
                            animateButton.loading(false);
                        
                        }
                    }
                });

        }


    });

});







