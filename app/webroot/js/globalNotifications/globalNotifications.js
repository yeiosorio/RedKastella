
/**
 * Ajax to get global notifications
 */
function getGlobalNotifications(){


	return $.ajax({
			url: baseUrl +'Notifications/getNotifications',
			dataType: 'json',
			type: 'post',
			async:true
		});

}

/**
 * Ajax To Update the global notifications
 */
function updateContractNotifications(){

	return $.ajax({
			url: baseUrl +'Notifications/updateContractNotifications',
			dataType: 'json',
			type: 'post',
			async:true
		});
}



/**
 * Ajax To Update the global notifications
 */
function getFriendNotifications(){

	return $.ajax({
			url: baseUrl +'Notifications/getFriendNotifications',
			dataType: 'json',
			type: 'post',
			async:true
		});
}


/**
 * Ajax To Update the global notifications
 */
function updateFriendNotification(){

	return $.ajax({
			url: baseUrl +'Notifications/updateFriendNotification',
			dataType: 'json',
			type: 'post',
			async:true
		});
}




function doGetFriendNotifications(){


	getFriendNotifications().done(function(response){



		if(response.numFriendRequests != 0){

			$('#g-notifications-friends').removeClass('no-display');

            $('#number-noti-friends').html(response.numFriendRequests);

		}


		$.each(response.FriendRequest, function() {

			console.log(this);
            
            var newContent = '<li class="media friend-request-item" data-username="'+this.User.username+'">'+
                  '<div class="media-left"> '+
                    '<a href="#">'+
                      '<div class="circled-image" style="background-image:url('+this.User.profilePic+')"  alt="people">'+
                    '</a>'+
                  '</div>'+
                  '<div class="media-body">'+
                    '<div class="pull-right">'+
                      //'<span class="label label-default">5 min</span>'+
                    '</div>';
					                      
                    // <!-- Nombre completo del Usuario -->
                	newContent +=' <p style="margin-top: 8px;"><b>'+
                       this.User.name.toLowerCase().capitalizeFirstLetter() + ' '+ this.User.surname.toLowerCase().capitalizeFirstLetter() +
                    '</b></p>';


                    // <!-- Localizacion del usuario -->
                	newContent +=' <p style="margin-top: -13px;">'+
                        this.User.location +
                    '</p>' +

                  '</div>' +

                '</li>';

            $('#content-notifications-friends').append(newContent); 

		});


	}).fail(function(x) {

	    console.log(x);

	});

}


/**
 * Función que devuelte un string con un determinado número de palabras 
 * @param  String theString cadena de entrada
 * @param  Int numWords  Número de palabras
 * @return String Palabras
 */
function trimWords(theString, numWords) {

    expString = theString.split(/\s+/,numWords);
    theNewString=expString.join(" ");
    return theNewString;
}


String.prototype.capitalizeFirstLetter = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}	


function doGetGlobalNotifications(){

	getGlobalNotifications().done(function(response){

			if(response.numberNewNotifications != 0){

				$('.g-notifications').removeClass('no-display');

			    $('.number-noti').html(response.numberNewNotifications);
			}


			$.each(response.contractNotifications, function() {

				var newContent = '<li class="media contract-notification-item" data-link="'+this.link+'">'+
	                  '<div class="media-left"> '+
	                    '<a href="#">'+
	                     //'<img class="media-object thumb" src="http://localhost/kastella/resourcesFolder/1447080033251319885253906/1449673923497756958007812.jpg" alt="people">'+
	                    '</a>'+
	                  '</div>'+
	                  '<div class="media-body">'+
	                    '<div class="pull-right">'+
	                      //'<span class="label label-default">5 min</span>'+
	                    '</div>';
	                      
	                    // <!-- Nombre de Usuario -->
	                	newContent +=' <h5 class="media-heading">'+
	                     	this.title +
	                    '</h5>';

	                    // <!-- Contenido -->
	                	newContent += '<p class="margin-none">'+ trimWords(this.contenido, 7).toLowerCase().capitalizeFirstLetter() +'... </p>'+
	                	'<p class="margin-none"><b>'+ this.ciudad +', '+ this.category.toLowerCase().capitalizeFirstLetter() +'</b> </p>'+
	                  '</div>'+
	                '</li>';

	                 $('.content-notifications').append(newContent);


	        });	

			$.each(response.contractNotifications, function() {

				var newContent = '<li class="media contract-notification-item" data-link="'+this.link+'">'+
	                  '<div class="media-left"> '+
	                    '<a href="#">'+
	                     //'<img class="media-object thumb" src="http://localhost/kastella/resourcesFolder/1447080033251319885253906/1449673923497756958007812.jpg" alt="people">'+
	                    '</a>'+
	                  '</div>'+
	                  '<div class="media-body">'+
	                    '<div class="pull-right">'+
	                      //'<span class="label label-default">5 min</span>'+
	                    '</div>';
	                      
	                    // <!-- Nombre de Usuario -->
	                	newContent +=' <h5 class="media-heading">'+
	                     	this.title +
	                    '</h5>';

	                    // <!-- Contenido -->
	                	newContent += '<p class="margin-none">'+ trimWords(this.contenido, 7).toLowerCase().capitalizeFirstLetter() +'... </p>'+
	                	'<p class="margin-none"><b>'+ this.ciudad +', '+ this.category.toLowerCase().capitalizeFirstLetter() +'</b> </p>'+
	                  '</div>'+
	                '</li>';

	                 $('.content-notifications').append(newContent);


	        });	

				var seeAll = '<li class="media see-all" >'+
	                  '<div class="media-left"> '+
	                    '<a href="#">'+
	                    '</a>'+
	                  '</div>'+
	                  '<div class="media-body">';
	                    // <!-- Nombre de Usuario -->
	                	seeAll +=' <h5 class="media-heading">Ver Todas</h5>' +

	                   
	                  '</div>'+
	                '</li>';
	
		    $('.content-notifications').append(seeAll);
	


	}).fail(function(x) {

	    console.log(x);

	});


}




$(document).on('click','.see-all',function(){


	window.location.href = baseUrl + 'Notifications/notifications'; 

});

$(document).on('click','.friend-request-item',function(){

	var username = $(this).data('username');

	window.location.href = baseUrl + 'users/profile/' + username; 

});



$(document).on('click','.contract-notification-item',function(){


	var link = $(this).data('link');

	window.open(link);

});


$(document).on('click','.notification-list-items', function(){


	// updateContractNotifications().done(function(response){

	// 	$('#g-notifications').addClass('no-display');

	//     $('#number-noti').html('');


	// 	}).fail(function(x) {

	//     console.log(x);

	// });


});


$(document).on('click','.notification-users-list', function(){



	updateFriendNotification().done(function(response){

		$('#g-notifications-friends').addClass('no-display');

        $('#number-noti-friends').html('');


		}).fail(function(x) {

	    console.log(x);

	});




});






/**
 * Getting the notifications of contracts
 */
setTimeout(function(){ 

		doGetGlobalNotifications();

}, 1500);



/**
 * Getting the notifications
 */
setTimeout(function(){ 

		doGetFriendNotifications();

}, 2500);




















