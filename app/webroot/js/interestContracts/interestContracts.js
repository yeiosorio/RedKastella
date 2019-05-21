

$("#acc").accordion({
    collapsible: true,
    heightStyle: "content"
});


/**
 * variable usada para almacenar las categorias
 */
var parentCategories; 


/**
 * Función que obtiene las categorias del controlador xmlreader y las asigna a la variable parentPrefCategories
 */
function getParentCategories(){

	$.ajax({
		type:'GET',
		dataType: "json",
		url: baseUrl+"/xmlreader/getFeedCategoriesArray/",
		success: function(response) {

			//asignamos las categorias
			parentCategories = response;
	
			var options = $("#contractsParentCategory");
			
			$.each(parentCategories, function() {

				options.append(new Option(this.nombre, this.nombre));

			});

			$("#contractsParentCategory").trigger('change');


		},
		error: function(response) {

		}

	});

}


/**
 * Función que detecta el cambio de selección del dropdown principal
 * esta cambia los datos del seguno dropdown de selección de categorias
 */
$("#contractsParentCategory").on('change',function(){

	var index = this.selectedIndex;

	var categories = parentCategories[index]['categories'];
	$("#contractsCategory").empty();

	var options = $("#contractsCategory");
	$.each(categories, function() {

		options.append(new Option(this.nombre, this.url));

	});

});


/**
 * Función que trae los resultados se la selección de categorias
 */
$("#seeResults").on('click',function(){

	var url = $("#contractsCategory").val();
	searchAnimation(true);

	$.ajax({
		type:'POST',
		dataType: "json",
		data: {url:url},
		url: baseUrl+"/xmlreader/getUrlJsonContent/",
		success: function(response) {

			var category = response.category;

			fillCategoryContracts(category);
			searchAnimation(false);

		},
		error: function(response) {
			searchAnimation(false);
		}

	});

});


/**
 * Función que muestra los datos de las categorias en el contenedor contentResults
 * @param  {Array} category categoria a mostrar
 */
function fillCategoryContracts(category){


	//Contenedor de resultados
	var contentResults = $('#contentResults');
	
	//vaciamos los resultados
	contentResults.empty();

	//scribimos el encabezado de la categoria
	contentResults.append($('<h3>', {text:category.title}));
	contentResults.append($('<p>', {text:category.description}));

	// copyright: "Copyright hold by CCE"
	// description: "Agencia Nacional de Contratación Colombia Compra Eficiente"
	// language: "en"
	// link: "http://www.colombiacompra.gov.co"
	// pubdate: "Thu, 29 Oct 2015 11:50:05 -0500"
	// title: "Colombia Compra Eficiente - Ropa, Maletas y Productos de Aseo Personal"			
	
	/**
	 * variable que representara el item actual del recorrido que se ejecuta a continiación
	 */
	var item;

	/**
	 * [selectDepartment nombre del departamento seleccionado]
	 * @type {String}
	 */
	var selectDepartment = $.trim($('#selectDepartment option:selected').text());

	/**
	 * [selectMunicipality nombre de la ciudad seleccionada]
	 * @type {String}
	 */
	var selectMunicipality = $.trim($('#selectMunicipality option:selected').text());
	

		//si la selección de departamento es todos
		if (selectDepartment == "Todos") {

			//recorrido de los items que contiene la categoria
			$.each(category.item, function(index) {

				item = category.item[index];
				//llamamos la función que muestra los resultados
				fillCategoryContract(item);

			});


		}else{

			//si la selección de ciudad es todas
			if(selectMunicipality == "Todas"){

				//recorrido de los items que contiene la categoria
				$.each(category.item, function(index) {

					item = category.item[index];

					//condicion por departamento
					if ($.trim(item.departamento).toLowerCase() == selectDepartment.toLowerCase()) {
						//llamamos la función que muestra los resultados
						fillCategoryContract(item);
					}

				});	

			}else{

				//recorrido de los items que contiene la categoria
				$.each(category.item, function(index) {

						item = category.item[index];

						// Condición por departamento y ciudad
						if ($.trim(item.departamento).toLowerCase() == selectDepartment.toLowerCase() && $.trim(item.ciudad).toLowerCase() == selectMunicipality.toLowerCase()) {

							//llamamos la función que muestra los resultados
							fillCategoryContract(item);
						}

				});		
			}
		}
	
}


/**
 * Función que muestra los datos de un resultado 
 * @param  {object} item objeto que contiene un resultado
 */
function fillCategoryContract(item){

	var contentResults = $('#contentResults');
	
	var contentResult = "<div class='panel panel-info contentResult'>";

	contentResult += "<div class='panel-heading'><h5>"+item.title+"</h5></div>";
	contentResult += "<div class='panel-body'>";
	contentResult += "<h4>"+item.nombre+"</h4>";
	contentResult += "<p>"+item.contenido+"</p>";
	contentResult += "<p><strong>Valor Estimado:</strong> "+accounting.formatMoney(item.valor)+"</p>";
	contentResult += "<p><strong>Ciudad:</strong> "+item.ciudad+"</p>";
	contentResult += "<p><strong>Departamento:</strong> "+item.departamento+"</p>";
	contentResult += "<p><strong>Email:</strong> "+item.author+"</p>";
	contentResult += "<p><strong>Link:</strong> <a href='"+item.link+"' target='_blank'>"+item.link+"</a></p>";
	contentResult += "</div>";				
	contentResult += "</div>";

	contentResults.append(contentResult); 
}

//selectDepartment
getParentCategories();


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







