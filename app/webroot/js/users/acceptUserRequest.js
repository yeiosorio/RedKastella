

/**
 * Ajax de la peticion de aceptar una invitación
 */
function acceptFriend(data){

	return  $.ajax({
				url: baseUrl + 'users/acceptFriend',
				type: 'post',
				data: data,
				dataType:'json'
			});
}




$('.accept-friend-request').click(function(){

	/**
	 * Usuario a aceptar invitacion de amistad
	 */
	var userToRequestId = $(this).data('user-id');
		

	$('.accept-friend-request').prop('disabled',true);


	/**
	 * Aceptar Invitación
	 */
	acceptFriend({userToRequestId:userToRequestId}).done(function(response) {
	     	

		if (response.success == true) {
	

				$('.accept-friend-request').addClass('no-display');
				$('.accepted-btn').removeClass('no-display');

		
		}
		

	}).fail(function(x) {
	    console.log(x);

	});


});






