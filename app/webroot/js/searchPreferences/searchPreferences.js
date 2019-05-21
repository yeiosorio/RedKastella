


function getParentCategories(){

//variable usada para almacenar las categorias 
var parentCategories; 

	$.ajax({
		type:'GET',
		dataType: "json",
		url: baseUrl+"/xmlreader/getFeedCategoriesArray/",
		success: function(response) {

			//asignamos las categorias
			parentCategories = response;
			// console.log(parentCategories);     

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





$("#contractsParentCategory").on('change',function(){

	//var categoryName = $(this).val();

	var index = this.selectedIndex;


	// console.log(parentCategories[index]['categories']);

	var categories = parentCategories[index]['categories'];
	$("#contractsCategory").empty();

	var options = $("#contractsCategory");
	$.each(categories, function() {

		options.append(new Option(this.nombre, this.url));

	});



});






