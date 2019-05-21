
/**
 * Funcion que retorna un ajax que ejecuta la acción de pertenecer a un grupo
 * @param  {Object} data datos
 * @return Ajax
 */
function acceptUserRequest(data){


	return $.ajax({
			url: baseUrl +'Groups/acceptUserRequest',
			type: 'post',
			dataType: 'json',
			data: data,
		});

}


/**
 * eliminar peticion de grupo
 */
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
$('.accept-user').click(function(){


	var idAcceptedUser = $(this).data('user-id');
	var requestId = $(this).data('request-id');

	/**
	 * Usamos la funcionalidad de pertenecer a un grupo
	 */
	acceptUserRequest({idAcceptedUser:idAcceptedUser, requestId: requestId}).done(function(response) {


	})
	.fail(function(x) {
	    console.log(x);
	});

});



            


/**
 * Acción de pertenecer a un grupo
 */
$('.cancel-user-request').click(function(){


	var requestId = $(this).data('request-id');
	
	/**
	 * Usamos la funcionalidad de pertenecer a un grupo
	 */
	cancelBelongToGroup({requestId:requestId}).done(function(response) {


	})
	.fail(function(x) {
	    console.log(x);
	});

});


