/**
 * Accordeon de la vista de editar perfil
 * @type accordion
 */

// $("#acc").accordion({
//     collapsible: true,
//     heightStyle: "content"
// });
// 

/**
 * Función que cambia los estilos globales de usuario 
 * @param  String color color seleccionado
 */
function cambiar_color(color) {
    $.post(baseUrl + 'users/change_color/' + color, function(data) {
        switch (color) {
            case "purple":
                appendHeadStyles('skin-purple.css');
                break;
            case "blue":
                appendHeadStyles('skin-blue.css');
                break;
            case "orange":
                appendHeadStyles('skin-orange.css');
                break;
            case "brown":
                appendHeadStyles('skin-brown.css');
                break;
            case "default":
                appendHeadStyles('skin-default-nav-inverse.css');
                break;
            default:
                appendHeadStyles('skin-orange.css');
        }
    });
}
/**
 * Función que agrega un estilo al encabezado del documento html
 * @param String styleName nombre del estilo
 */
function appendHeadStyles(styleName) {
    $('head').append(getCustomStyles(styleName));
}
/**
 * Función que retorna el html de un recurso de estilos css
 * @param String styleName nombre del estilo
 */
function getCustomStyles(styleName) {
    return "<link rel='stylesheet' href='"+ baseUrl + "css/vendor/" + styleName + "' type='text/css' />";
}


$("#UserProfilePicture").change(function() {

    var inputFileImage = document.getElementById("UserProfilePicture");
    var file = inputFileImage.files[0];
    var data = new FormData();
    data.append('archivo', file);
   
    $.ajax({
        url: baseUrl + "users/change_picture",
        type: 'POST',
        contentType: false,
        data: data,
        processData: false,
        cache: false,
        success: function(response) {
            //$('#nueva_publicacion').html(response);
            if (response != '') {
                $("#img_avatar").attr("src", "/img/" + response);
            }
            console.log("Llego el contenido y no hubo error", response);
        },
        error: function(response) {
            console.error("Este callback maneja los errores", response);
        }
    });

});

$('.my_toggle').hide();

$("#togglear").click(function() {
    $('.my_toggle').toggle();
});




$("#form_pwd").submit(function(event) {

    
    $.ajax({
            url: baseUrl+'users/change_pwd',
            type: 'post',
            dataType: 'json',
            data: { newPasswordData:{
                    actualPassword: $('#UserPassword-old').val(),
                    newPassword: $('#UserPassword').val(),
                    newPasswordConf: $('#UserPwdConfirmation').val()
                }
            },
            success: function (response) {
                
                if (response.success) {

                    $('#message_doc').html(response.message);
                    $('#message_doc').css("color", "green");
                    
                    $('#UserPassword-old').val('');
                    $('#UserPassword').val('');
                    $('#UserPwdConfirmation').val('');

                }else{

                    $('#message_doc').html(response.message);
                    $('#message_doc').css("color", "red");
                    

                }
            }
        });


    event.preventDefault();
    return false;
    
});



$('#UserPassword-old').val('');
$('#UserPassword').val('');
$('#UserPwdConfirmation').val('');




function getDepartmentIdFromMunicipalityId(municipalityId){

    return $.ajax({
            url: baseUrl+'Municipalities/getByDepartmentIdByMunicipalityId',
            type: 'post',
            data: {municipalityId: municipalityId},
            dataType:'json',
            cache: false       
        });

}






/**
* Función usada para obtener los departamentos y agregarlos a un dropdown especificado
*
*/  
function getDepartmentsProfile(){

    $.ajax({
        type:'GET',
        dataType: "json",
        url: baseUrl+"Departments/getAllDepartments/",
        success: function(response) {

            //asignamos los departamentos
            Departments = response;
            // console.log(Departments);     

            var options = $("#selectDepartmentEditProf");
            options.append(new Option('Seleccionar','0'));
            $.each(Departments, function(index) {

                options.append(new Option(Departments[index]['Departments'].name, Departments[index]['Departments'].id));

            });

            
            getDepartmentIdFromMunicipalityId($('#user-municipality').val()).done(function(response) {
       
                if (response.success) {

                    var userDepartment = response.id;

                        $('#selectDepartmentEditProf').val(userDepartment);

                            getMunicipalitesEditProf(parseInt($('#user-municipality').val()));
                        
                 }

            })
            .fail(function(x) {
                console.log(x);
            });

        },
        error: function(response) {

        }

    });

}

//llamamos la función
getDepartmentsProfile();




/**
* Función usada para obtener las ciudades y agregarlas a un dropdown especificado
*
*/  
function getMunicipalitesEditProf(selectedMunicipality){


    $.ajax({
        type:'POST',
        data:{departmentId: $("#selectDepartmentEditProf").val()},
        dataType: "json",
        url: baseUrl+"Municipalities/getCitiesByDepartmentId/",
        success: function(response) {

            //asignamos los departamentos
            municipalities = response;
            // console.log(municipalities);     

            var options = $("#selectMunicipalityEditProf");
            options.empty();

            options.append(new Option('Seleccionar','0'));
            $.each(municipalities, function(index) {

                options.append(new Option(municipalities[index]['Municipality'].municipality, municipalities[index]['Municipality'].id));

            });

            if (selectedMunicipality != undefined) {

                options.val(selectedMunicipality);
           
            }
 
        },
        error: function(response) {

        }

    });

}

//detectamos el cambio en la selección de departamentos
$("#selectDepartmentEditProf").on('change',function(){

    //llamamos la función
    getMunicipalitesEditProf(undefined);


});




$('#user-form-edit').submit(function(e){



    if ($('#selectMunicipalityEditProf').val() == 0) {
     
        e.preventDefault();
        $('.error-select-city').removeClass('no-display');
     }

});


$('#profilePicForm').submit( function(e){

      $.ajax({
          url: baseUrl+ 'Users/changeProfilePic/',
          type: 'POST',
          dataType: 'json',
          data: new FormData(this),
          processData: false,
          contentType: false,
          success: function(response){

            /**
             * Url de la imagen
             * @type String
             */
            var imageUrl = response[0]['file'];
                
            /**
             * Asignacion de la imagen al las clases
             */
            $('.edit-pic-prof').css('backgroundImage','url('+imageUrl+')');

          },
          error: function(response){
    
          }
    });
    
    e.preventDefault();

});



$("#profilePicFile").change(function() {

    $('#profilePicForm').submit();
    
});








