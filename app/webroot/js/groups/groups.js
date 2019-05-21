
	
/**
 * Funcion que retorna un ajax que ejecuta la acción de pertenecer a un grupo
 * @param  {Object} data datos
 * @return Ajax
 */
function belongToGroup(data){


	return $.ajax({
			url: baseUrl +'Groups/belongToGroup',
			type: 'post',
			dataType: 'json',
			data: data,
		});

}




function cancelBelongToGroup(data){


	return $.ajax({
			url: baseUrl +'Groups/cancelBelongToGroup',
			type: 'post',
			dataType: 'json',
			data: data,
		});

}


/**
 * Acción de pertenecer a un grupo
 */
$('.belong-to-group').click(function(){


var thisButton = $(this);

var isIn = $(this).data('is-in'); 

var isInId = $(this).data('is-in-id'); 

var groupId = $(this).data('group-id');

thisButton.prop( "disabled", true );


animateButtonObj.selector = thisButton.find('.btn-belongs-to-gls');


if (parseInt(isIn) == 0) {
	
animateButtonObj.iconClass = 'fa fa-user-plus';
animateButtonObj.loading(true); 

	/**
	 * Usamos la funcionalidad de pertenecer a un grupo
	 */
	belongToGroup({groupId:groupId}).done(function(response) {
	     

	     if(response.success == true){

		     thisButton.data("is-in",1);

		   	 thisButton.data("is-in-id",parseInt(response.request_id));

		     animateButtonObj.loading(false);

		     thisButton.removeClass('btn-primary');

		     thisButton.addClass('btn-warning');

		     thisButton.find('i').removeClass('fa-user-plus');

		     thisButton.find('i').addClass('fa-user-times');

		     thisButton.find('span').html('Cancelar Solicitud');

		     thisButton.prop( "disabled", false );

	     }

	})
	.fail(function(x) {
	    console.log(x);
	});

}else{


	animateButtonObj.iconClass = 'fa fa-user-times';
	animateButtonObj.loading(true); 

	
	/**
	 * Usamos la funcionalidad de pertenecer a un grupo
	 */
	cancelBelongToGroup({requestId : isInId}).done(function(response) {
	     	

	    if (response.success == true) {

		     thisButton.data("is-in",0);

		     animateButtonObj.loading(false);
		     
		     thisButton.addClass('btn-primary');
			
			 thisButton.removeClass('btn-warning');

		     thisButton.find('i').addClass('fa-user-plus');

		     thisButton.find('i').removeClass('fa-user-times');

		     thisButton.find('span').html('Pertenecer');

		    thisButton.prop( "disabled", false );

	      }

	})
	.fail(function(x) {
	    console.log(x);
	});	


}







});