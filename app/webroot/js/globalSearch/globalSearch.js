

var timer;

var inputElement = $('.jm-search');



var pos = inputElement.position(); 
var height1 = inputElement.outerHeight();

var suggestionsContainer = $(".jm-search-suggestions");


$('.global-search-button').click(function(){ 


	suggestionsContainer = $(".jm-search-suggestions");


	$('.main-search-cont').removeClass('no-display');



	inputElement.animate({ width: '280px', }, '2500');

    

    suggestionsContainer.css({ position: "absolute", top: (pos.top + height1 + 17) + "px", left: (pos.left) + "px" }).show();

   	inputElement.focus();

});



$('.global-search-button-resp').click(function(){ 	



	suggestionsContainer = $(".jm-search-suggestions-modal");

   	inputElement.focus();

   	$('.search-modal').modal('show');


});






function ajaxSearch(query){

	return $.ajax({
			url: baseUrl+'Users/globalSearch',
			type: 'post',
			dataType: 'json',
			data: {query:query},
		});
}







function getContentFormat(item){

	var content =  '<div class="person-gs" data-username="'+item.username+'">'+
					 '<div class="post-user-picture" style="background-image:url('+item.profilePic+'); float: left; "></div>'+
						 '<div style="float:left; margin-top: 7px; margin-left: 5px;">'+
						 	'<p>'+ item.name + ' ' + item.surname + '</p> ' +
						 	'<p style="margin-top: -10px; margin-bottom: 0px;">'+ item.municipality + ', ' + item.department + '</p> ' +
						 '</div>'+		
				'</div>';
	return content;
}


$(document).on('click','.person-gs', function(){

	var username = $(this).data('username');

	window.location.href = baseUrl + 'users/profile/' + username;

});


function getOrganizationContentFormat(item){

	var content =  '<div class="organization-gs" data-nit="'+item.nit+'" >'+
					 '<div class="post-user-picture" style="background-image:url('+item.pic+'); float: left; "></div>'+


					  '<div style="float:left; margin-top: 7px; margin-left: 5px;">'+
						 	'<p>'+ item.name + '</p> ' +
						 	'<p style="margin-top: -10px; margin-bottom: 0px;">'+ item.municipality + ', ' + item.department + '</p> ' +
						 '</div>'+		


				 '</div>';
	return content;

}

$(document).on('click','.organization-gs', function(){

	var nit = $(this).data('nit');

	window.location.href = baseUrl + 'Groups/group/' + nit;

});


	


// checking if is a mobile device


var isMobileDevice = is.mobile();



if (isMobileDevice) {


	/**
	 * Detect event of keypress
	 */
	inputElement.keypress(function( event ) {

	  
	  var searchTerm = $.trim($(this).val());
	  
	 
	  // if ( event.which == 13 ) {
	  
	  //    event.preventDefault();
	  
	  // }else{


	  	globalSearch(searchTerm);

	  // }


	});


	inputElement.keydown(function(e) {
			
		
		var searchTerm = $.trim($(this).val());
	    
	    // if( e.which == 8 || e.which == 46 ){

	 
	 		globalSearch(searchTerm);

	    // } 

	});


}else{



	/**
	 * Detect event of keypress
	 */
	inputElement.keypress(function( event ) {

	  
	  var searchTerm = $.trim($(this).val());
	  
	 
	  if ( event.which == 13 ) {
	  
	     event.preventDefault();
	  
	  }else{


	  	globalSearch(searchTerm);

	  }


	});


	inputElement.keydown(function(e) {
			
		
		var searchTerm = $.trim($(this).val());
	    
	    if( e.which == 8 || e.which == 46 ){

	 
	 		globalSearch(searchTerm);

	    } 

	});

}



function globalSearch(searchTerm){


    clearTimeout(timer);

    var ms = 500; // milliseconds
 
    timer = setTimeout(function() {

			ajaxSearch(searchTerm).done(function(response) {
			

				/**
				 * Clean container 
				 */
				suggestionsContainer.empty();

				checkAddClass();

				
				console.log(response);		

				var foundUsers = response.users;


				/**
				 * if there are users
				 */
				if(foundUsers.length >= 1){

					checkRemoveClass();


					suggestionsContainer.append("<div class='heading-gs'><b>Personas</b></div>");



					for (var i = 0; i < foundUsers.length; i++) {

						var user = foundUsers[i];

						suggestionsContainer.append(getContentFormat(user));


					};

				}


				var foundOrganizations = response.organizations;

				/**
				 * if there are users
				 */
				if(foundOrganizations.length >= 1){

					checkRemoveClass();


					suggestionsContainer.append("<div class='heading-gs'><b>Grupos</b></div>");



					for (var i = 0; i < foundOrganizations.length; i++) {

						var organization = foundOrganizations[i];

						suggestionsContainer.append(getOrganizationContentFormat(organization));


					};

				}





			})
			.fail(function(x) {

			    console.log(x);

			});

	}, ms);

}



/**
 * when has the focus
 */

inputElement.focus(function() {

	var searchTerm = $.trim($(this).val());

	if(searchTerm != ''){

		globalSearch(searchTerm);

	}



});


/**
 * when losing focus 
 */


// suggestionsContainer.blur(function() {
 
// 	checkAddClass();

// });



$(document).click(function(event) { 
	

	if($(event.target).is('.jm-search')){

		checkRemoveClass();


		

	}else if(!$(event.target).is(suggestionsContainer)){


		checkAddClass();


	} 

});


function checkAddClass(){

	if(!suggestionsContainer.hasClass("no-display")){

		suggestionsContainer.addClass('no-display'); 
	}
}



function checkRemoveClass(){

	if(suggestionsContainer.hasClass("no-display")){

		suggestionsContainer.removeClass('no-display'); 
	}

}









