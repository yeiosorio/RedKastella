
/**
 * Ajax de la peticion de invitación
 */
function requestUser(data){

	return  $.ajax({
				url: baseUrl + 'users/friendRequest',
				type: 'post',
				data: data,
				dataType:'json'
			});
}


/**
 * Ajax de la peticion de cancelar una invitación
 */
function cancelFriendRequest(data){

	return  $.ajax({
				url: baseUrl + 'users/cancelFriendRequest',
				type: 'post',
				data: data,
				dataType:'json'
			});
}


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


function getNumberFriends(data){

	return  $.ajax({
				url: baseUrl + 'users/totalFriendsAsync',
				type: 'post',
				data: data,
				dataType:'json'
			});

}

var queriedUserId = $('.profile-heading').data('user-id');

/**
 * Funcionalidad de solicitud de amistad
 */
$('.friend-request').click(function(){


var userToRequestId = $(this).data('user-id');

/**
 * Invitación de usuario
 */
requestUser({userToRequestId:userToRequestId}).done(function(response) {
     	
    if (response.success == true) {

    	$('.friend-request').addClass('no-display');
    	$('.cancel-friend-request').removeClass('no-display'); 

     }

})
.fail(function(x) {
    console.log(x);
});


});


/**
 * Funcionalidad de solicitud de amistad
 */
$('.cancel-friend-request').click(function(){


var userToRequestId = $(this).data('user-id');

/**
 * Cancelar Invitación
 */
cancelFriendRequest({userToRequestId:userToRequestId}).done(function(response) {
     	

    if (response.success == true) {

    	$('.friend-request').removeClass('no-display');
    	$('.cancel-friend-request').addClass('no-display'); 

     }

})
.fail(function(x) {
    console.log(x);
});

});


$('.accept-friend-request').click(function(){

/**
 * Usuario a aceptar invitacion de amistad
 */
var userToRequestId = $(this).data('user-id');

	
  $('.accept-friend-request').addClass('no-display');
  $('.accepted-btn').removeClass('no-display');

/**
 * Aceptar Invitación
 */
acceptFriend({userToRequestId:userToRequestId}).done(function(response) {
     	

	if (response.success == true) {
	
	
	}
	

}).fail(function(x) {
    console.log(x);

});


});


/**
 * Actualización de total de amigos
 */
function updateTotalFriends(){


/**
 * Cancelar Invitación
 */
getNumberFriends({userId:queriedUserId}).done(function(response) {
     	

	$('.total-friends').html(response.total);

	

}).fail(function(x) {
    console.log(x);

});


}





