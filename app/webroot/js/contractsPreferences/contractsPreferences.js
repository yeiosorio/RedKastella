

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


	getInitialPreferences().done(function(response) {

		var depto = response.ContractPreference.departamento;

		if(depto !== null && depto !== 'Todos'){

			depto = parseInt(depto);

			$(".departments").val(depto);

		}

	}).fail(function(x) {
	    console.log(x);
	});

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
 * Obtencion de las categorias
 */
getContractTypes().done(function(response) {


		//asignamos las categorias
		  

		var options = $("#prefContractsParentCategory");
			
		$.each(response.contractTypes, function() {

			options.append(new Option(this['ContractCategory'].name, this['ContractCategory'].id));

		});


		getSavedPreferences().done(function(response) {


			console.log(response.savedPreferences);
				
			var savedIds = new Array();

			$.each(response.savedPreferences, function() {


				$('.selected-categories').append('<option data-values="['+this['ContractSubcategoryPreference'].minvalue+','+this['ContractSubcategoryPreference'].maxvalue+']" value="' + this['ContractSubcategories'].id + '">' + this['ContractSubcategories'].name + '</option>'); 


				savedIds.push(this['ContractSubcategories'].id);


			});


			/**
			 * Agregamos preferencias guardads del inicio si se han seteado
			 */
			if(localStorage.homePreferences != undefined){


				var homePreferences = JSON.parse(localStorage.homePreferences);

				for (var i = 0; i < homePreferences.length; i++) {

						
					if(!$.inArray(homePreferences[i].id, savedIds)){

						$('.selected-categories').append('<option data-values="[' + homePreferences[i].values[0] + ',' + homePreferences[i].values[1] + ']" value="' + homePreferences[i].id + '">' + homePreferences[i].text + '</option>'); 

					}
				
				}




			}




	   	
	   		$("#prefContractsParentCategory").trigger('change');


		});




	

});




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


function getSelection(){


	var selectedOptions = new Array();

	$.each($('.selected-categories option'),function(){

		selectedOptions.push({id:$(this).val(), values: $(this).data('values')});

	});	

	return selectedOptions;
}

/**
 * Función que guarda las preferencias del usuario
 */
$('#savePreferences').click(function(){


	var selection = getSelection();

	/**
	 * Si hay por lo menos una opcion 
	 */
	if (selection.length > 0) {

		//mostramos la animación de guardado
		saveAnimation(true);

		/**
		 * Ajax que guarda los datos cuando ocurre error o se completa la acción para la animación
		 */
		$.ajax({
			type:'POST',
			data: {selection: selection, departamento: $(".departments").val()},
			dataType: "json",
			url: baseUrl+"ContractPreferences/saveContractPreference/",
			success: function(response) {

				console.log(response);

				saveAnimation(false);


				localStorage.removeItem("homePreferences");
  

				$('#modal-confirm-saved-pref').modal('show');

			},
			error: function(response) {
				saveAnimation(false);
			}

		});


	}else{

		console.log("asdasd");

	}


});







//función de animación del boton de búsqueda
function saveAnimation(state){

	if (state){

		$('.btn-save-gls').removeClass("fa fa-floppy-o");
		$('.btn-save-gls').addClass("fa fa-spinner fa-pulse");

	}else{
		$('.btn-save-gls').removeClass("fa fa-spinner fa-pulse");
		$('.btn-save-gls').addClass("fa fa-floppy-o");

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




