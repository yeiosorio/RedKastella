


 

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
        url: baseUrl + "Departments/getAllDepartments/",
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
        url: baseUrl + "Municipalities/getCitiesByDepartmentId/",
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

$('.addUserForm').submit(function(e){

	console.log("asdasd");

	var form = $(this);

	if (localStorage.newPublicationTitle != undefined){
		
		if($('.newPublicationTitle').length > 0){

			$('.newPublicationTitle').val(localStorage.newPublicationTitle);
			
			$('.newPublicationContent').val(localStorage.newPublicationContent);

			$('.newPublicationPrivacies').val(localStorage.newPublicationPrivacies);



		}else{

		    form.prepend("<input type='hidden' name='newPublicationTitle' class='newPublicationTitle' value='" + localStorage.newPublicationTitle + "' />");

		    form.prepend("<input type='hidden' name='newPublicationContent' class='newPublicationContent' value='" + localStorage.newPublicationContent + "' />");

		    form.prepend("<input type='hidden' name='newPublicationPrivacies' class='newPublicationPrivacies' value='" + localStorage.newPublicationPrivacies + "' />");
		}

	}

    if ($('#selectMunicipality').val() == 0) {
     
        $('.error-select-city').removeClass('display-none');
    
    }else{

    	$('.error-select-city').addClass('display-none');



    	$.ajax({
    			url: baseUrl + 'Users/registerRest',
    			type: 'post',
	            data: new FormData(this),
	            dataType: 'json',
	            processData: false,
	            contentType: false,
    			success: function (response) {

    				if(response.success == true){

						$.ajax({
								url: baseUrl + 'Users/loginRest',
								type: 'post',
					            data: new FormData(form[0]),
					            dataType: 'json',
					            processData: false,
					            contentType: false,
								success: function (response) {

									if(response.success == true){

										if(localStorage.newPublicationTitle != undefined){

											localStorage.removeItem("newPublicationTitle");

											localStorage.removeItem("newPublicationContent");	

											localStorage.removeItem("newPublicationPrivacies");	

										}

									$('#modal-register-success').modal('show');
				     				
									$('.error-list').html("");   	

									form[0].reset();

									}

							}
	   					});

    				}else{    						

    					$('.error-list').html("");   	

    					$('.error-list').html(response.message + "<br />");   
    					
    					$.each(response.errors, function(k, v) {
					       
					     	$('.error-list').append( v + "<br />");   
					    });
    			
    				}

    			}
    		});


    }

    e.preventDefault();


});

/**
 * función para obtener las preferencias guardadas
 */
function getInitialPreferences(){

	return $.ajax({
			type:'GET',
			dataType: "json",
			url: baseUrl+"ContractPreferences/getContractPreferencesAjax/",
	});
	
}


/**
 * función para obtener las preferencias guardadas
 */
function getSavedPreferences(){

	return $.ajax({
			type:'GET',
			dataType: "json",
			url: baseUrl+"ContractPreferences/getPreferences/",
	});
	
}

/**
 * Función que obtiene las categorias del controlador xmlreader y las asigna a la variable parentPrefCategories
 */
function getContractTypes(){


	return $.ajax({
			type:'GET',
			dataType: "json",
			url: baseUrl+"ContractPreferences/getContractCategories/",
		});

}



function getContractSubCategories(data){


	return $.ajax({
			type:'POST',
			data: data,
			dataType: "json",
			url: baseUrl+"ContractPreferences/getContractSubCategories/",
		});

}



function fillDeps(){

	// console.log(Departments);     

	var options = $(".departments");

	options.append(new Option('Todos','Todos'));
			
	$.each(Departments, function(index) {

		options.append(new Option(Departments[index]['Departments'].name, Departments[index]['Departments'].id));

	});


	// getInitialPreferences().done(function(response) {

	// 	var depto = response.ContractPreference.departamento;

	// 	if(depto !== null && depto !== 'Todos'){

	// 		depto = parseInt(depto);

	// 		$(".departments").val(depto);

	// 	}

	// }).fail(function(x) {
	//     console.log(x);
	// });

}

/**
* Función usada para obtener los departamentos y agregarlos a un dropdown especificado
*
*/	
function gDeps(){

	getAjaxDepartments().done(function(response) {
			
		//asignamos los departamentos
		Departments = response;

		fillDeps();


	})
	.fail(function(x) {
	    console.log(x);
	});
	         
}

gDeps();



/**
 * Función que detecta el cambio de selección del dropdown principal
 * esta cambia los datos del seguno dropdown de selección de categorias
 */
$("#prefContractsParentCategory").on('change',function(){

	
	var value = $(this).val();	

	/**
	 * Obtencion de las categorias
	 */
	getContractSubCategories({id:value}).done(function(response) {

			var options = $("#prefContractsCategory");

			options.empty();
			
			$.each(response.contractTypes, function() {

				options.append(new Option(this['ContractSubcategory'].name, this['ContractSubcategory'].id));

			});

	});
		

});


/**
 * Obtencion de las categorias
 */
getContractTypes().done(function(response) {


		//asignamos las categorias
		  

		var options = $("#prefContractsParentCategory");
			
		$.each(response.contractTypes, function() {

			options.append(new Option(this['ContractCategory'].name, this['ContractCategory'].id));

		});


		// getSavedPreferences().done(function(response) {


		// 	console.log(response.savedPreferences);

			

		// 	$.each(response.savedPreferences, function() {


		// 		$('.selected-categories').append('<option data-values="['+this['ContractSubcategoryPreference'].minvalue+','+this['ContractSubcategoryPreference'].maxvalue+']" value="' + this['ContractSubcategories'].id + '">' + this['ContractSubcategories'].name + '</option>'); 


		// 	});


	   	
	   		$("#prefContractsParentCategory").trigger('change');


		// });




	

});






/** Confuguración del slide de valores mínimo y máximo **/


/**
 * [slideValues objeto en el cual almacenaremos los valores de máximo y mínimo]
 * @type {Array}
 */
var slideValues = new Array();

 

$(".slider-values").slider({
	min: 0,
	max: 5000000000, 
	step: 5000000, 
	orientation: "horizontal",  
	selection: "after", 
	tooltip: "show",	
	value: [0,100000000]
});



setCurrencyValues(0, 100000000);


/**
 * Inicialización del objeto slider en el tag de clase .slider
 * Detectamos ademas el evento de slide (cuando se mueve) para obtener los valores actuales siempre
 */
$('.slider-values').slider().on('slide', function(ev){
  	
	//asignamos los valores	
    setCurrencyValues(ev.value[0],ev.value[1]);


     $(".selected-categories option[value='"+$('.selected-categories').val()+"']").data('values',[ev.value[0],ev.value[1]]);
    

});



/**
 * Función que ingresa y da formato a los valores seleccionados por el usuario
 * @param {int} valor mínimo
 * @param {int} valor máximo
 */
function setCurrencyValues(min,max){
	
	//almacenamos los valores en slideValues
	slideValues[0] = min;
	slideValues[1] = max;

    /**
     * asignamos los valores a unos campos escondidos
     */
	$('.currencyField1').val(min);
    $('.currencyField2').val(max);

	/**
	 * Utilizamos un plugin para formatear los valores
	 * primero formateamos los valores en los campos escondidos usando la funcion formatCurrency
	 */
    $('.currencyField1').formatCurrency();
	$('.currencyField2').formatCurrency();

	/**
	 * Luego los mostramos en unos spans aplicando la misma función formatCurrency
	 */
	$('.currencyField1').formatCurrency('.currencyFieldLabel1');
	$('.currencyField2').formatCurrency('.currencyFieldLabel2');
}


/**
 * configuramos los valores poor defecto
 */
// setCurrencyValues(5000000,10000000);

/**
 * seleccion de categorias de contratos
 */
function getSelection(){


	var selectedOptions = new Array();

	$.each($('.selected-categories option'),function(){


		selectedOptions.push({id:$(this).val(), values: $(this).data('values'), text: $(this).text()});

	});	

	return selectedOptions;
}


/**
 * Función que guarda las preferencias del usuario
 */
$('#savePreferences').click(function(){


	$('.register-new-modal').modal('show');


	/**
	 * guardado de categorias seleccionadas desde el home
	 */
	localStorage.homePreferences = JSON.stringify(getSelection());

	// var selection = getSelection();

	/**
	 * Si hay por lo menos una opcion 
	 */
	// if (selection.length > 0) {

	// 	//mostramos la animación de guardado
	// 	searchAnimation(true);

	// 	/**
	// 	 * Ajax que guarda los datos cuando ocurre error o se completa la acción para la animación
	// 	 */
	// 	$.ajax({
	// 		type:'POST',
	// 		data: {selection: selection, departamento: $(".departments").val()},
	// 		dataType: "json",
	// 		url: baseUrl+"ContractPreferences/saveContractPreference/",
	// 		success: function(response) {

	// 			console.log(response);

	// 			searchAnimation(false);

	// 			$('#modal-confirm-saved-pref').modal('show');

	// 		},
	// 		error: function(response) {
	// 			searchAnimation(false);
	// 		}

	// 	});


	// }else{

	// 	console.log("no hay selección");

	// }


});







//función de animación del boton de búsqueda
function searchAnimation(state){

	if (state){

		$('.btn-search-gls').removeClass("fa fa-search");
		$('.btn-search-gls').addClass("fa fa-spinner fa-pulse");

	}else{
		$('.btn-search-gls').removeClass("fa fa-spinner fa-pulse");
		$('.btn-search-gls').addClass("fa fa-search");

	}
	 
}


$('#prefSelCategory').click(function(){

	var value = $('#prefContractsCategory').val();

	var text = $("#prefContractsCategory option[value='"+value+"']").text();




	if(!$(".selected-categories option[value='"+value+"']").length > 0 && value != null){

		$('.selected-categories').append('<option data-values="[0,100000000]" value="' + value + '">' + text + '</option>'); 
	       
	}

});


$('#prefContractsCategory').change(function(){


	var text = $("#prefContractsCategory option[value='"+$(this).val()+"']").text();

	setCurrentCategoryMessage(text);

});




$('.selected-categories').change(function(){

	var text = $(".selected-categories option[value='"+$(this).val()+"']").text();
	setCurrentCategoryMessage(text);


	$('.category-to-set').html(text);

	var values = $(".selected-categories option[value='"+$(this).val()+"']").data('values');

	$(".slider-values").slider( "setValue", values);


	// configuramos los valores del usuario
	setCurrencyValues(values[0],values[1]); 

});


function setCurrentCategoryMessage(category){

	$('.current-selected-category').html(category);
}



$('#remove-item').click(function(){

	$(".selected-categories option[value='"+$('.selected-categories').val()+"']").remove();


});






/**
 * Función que inicializa el mansonry effect
 */
function masonryEffect(){
    
// función que acciona acopla los elementos 
$('#publications').masonry({

  /**
   * clase que tiene cada item del grid 
   * @type {String}   
   */
  itemSelector: '.grid-item',

  /**
   * Ancho de las columnas
   * @type {Number}
   */
  columnWidth: 400,

  /**
   * Es animado
   * @type {Boolean}
   */
  isAnimated: true,
    
  /**
   * Otras opciones
   */
  // animationOptions: {
  //   duration: 750,
  //   easing: 'linear',
  //   queue: false
  // }

});

}



/**
 * llamado de la función que usa el plugin mansonry
 */
masonryEffect();


$(document).on('click','.interested-contract-add',function(){

	$('.register-new-modal').modal('show');

});




$(document).on('click','.goto-to-kast-btn', function(){

	window.location.href = baseUrl + 'Publications/allPublications';

});










/**
 * Customs Scripts for kastella landing page
 */

$(".login-form").submit(function(e){

    var form = $(this);

	if (localStorage.newPublicationTitle != undefined){
		
		if($('.newPublicationTitle').length > 0){

			$('.newPublicationTitle').val(localStorage.newPublicationTitle);
			
			$('.newPublicationContent').val(localStorage.newPublicationContent);

			$('.newPublicationPrivacies').val(localStorage.newPublicationPrivacies);



		}else{

		    form.prepend("<input type='hidden' name='newPublicationTitle' class='newPublicationTitle' value='" + localStorage.newPublicationTitle + "' />");

		    form.prepend("<input type='hidden' name='newPublicationContent' class='newPublicationContent' value='" + localStorage.newPublicationContent + "' />");

		    form.prepend("<input type='hidden' name='newPublicationPrivacies' class='newPublicationPrivacies' value='" + localStorage.newPublicationPrivacies + "' />");
		}

	}





     $.ajax({
     		url: baseUrl + 'Users/loginRest',
     		type: 'post',
     		dataType: 'json',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (data) {

            	console.log(data);

    			if(data.success == false){

    				window.location.href = baseUrl+ 'users/login/';



     				$('.error-l').html(data.message);

     				$('.error-l').removeClass('display-none');

     			}else if(data.success == true){

     				if(localStorage.newPublicationTitle != undefined){

     					localStorage.removeItem("newPublicationTitle");

     					localStorage.removeItem("newPublicationContent");	

     					localStorage.removeItem("newPublicationPrivacies");	

     					

     				}

					$('.error-l').html("");

     				$('.error-l').addClass('display-none');


     				/**
     				 * llevamos a preferencias si hay algo definido de preferencias desde home
     				 * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
     				 * @date     2016-09-27
     				 * @datetime 2016-09-27T16:02:17-0500
     				 */
     				if(localStorage.homePreferences != undefined){

	     				window.location.href = baseUrl + "InterestContracts/contractPreferences";

     				}else{

	     				window.location.href = baseUrl + "Publications/allPublications";
     				}


     			}
     		}
     	});

    return false;
   	e.preventDefault();


});
	




/**
 * Para enfocar en el primer formulario de registro
 * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
 * @date     2016-09-27
 */
$('.btn-focus-register-home').click(function(){

	$('.register-new-modal').modal('hide');

	setTimeout(function(){ 

		$('#UserName').focus();

	}, 1000);
	
});

/**
 * Para enfocar en el formulario de login
 * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
 * @date     2016-09-27
 */
$('.btn-focus-login-home').click(function(){


	$('.register-new-modal').modal('hide');

	setTimeout(function(){ 

		$('.username-input').focus();

	}, 1000);


});




/**
 * Agregamos preferencias guardads del inicio si se han seteado
 */
if(localStorage.homePreferences != undefined){

	var homePreferences = JSON.parse(localStorage.homePreferences);

	for (var i = 0; i < homePreferences.length; i++) {
		
		$('.selected-categories').append('<option data-values="[' + homePreferences[i].values[0] + ',' + homePreferences[i].values[1] + ']" value="' + homePreferences[i].id + '">' + homePreferences[i].text + '</option>'); 
	
	}
}


// });
