





    /**
     * Variable que contendra el objeto de tipo magicSuggest para la funcionalidad se selección de tags dinamico
     * @type Object
     */
    var selectedEmails = $('#emailSelection').magicSuggest({
        
        /**
         * no permitimos nuevos entradas
         * @type {Boolean}
         */
        allowFreeEntries: false,

        /**
         * seleccion máxima de tags
         * @type {Number}
         */
        maxSelection: 30,

        /**
         * clase de estilos personalizada
         * @type {String}
         */
        cls:'serach-emails-cont',
        /**
         * url de donde se obtiene los tags de emails
         * @type String
         */
        // data: baseUrl+'Messages/getUsersToSendEmail',

        /**
         * Placeholder de entrada
         * @type {String}
         */
        placeholder: 'Personas...',

        /**
         * Identificador de los items devueltos por ajax
         * @type {String}
         */
        valueField: 'id',

        /**
         * nombre a mostrar de los items devueltos por ajax
         * @type {String}
         */
        displayField: 'email'
    });




	             




var timerSpeople;

var inputSpeople = $('.jm-search-people');


var suggestContPeople = $(".jm-search-suggestions-people");


// $('.global-search-button-resp').click(function(){ 	



// 	suggestContPeople = $(".jm-search-suggestions-modal");

//    	inputSpeople.focus();

//    	$('.search-modal').modal('show');

// });


function ajaxSearchPeople(query){

	return $.ajax({
			url: baseUrl+'Messages/peopleSearch',
			type: 'post',
			dataType: 'json',
			data: {query:query},
		});
}


function getContentSpFormat(item){

	var content =  '<div class="person-gs-sp" data-username="'+item.username+'" style="clear: both; height: 30px;" data-email="'+item.email+'" data-id="'+item.id+'" >'+
					 '<div class="post-user-picture" style="background-image:url('+item.profilePic+'); float: left; "></div>'+
						 '<div style="float:left; margin-top: 7px; margin-left: 5px;">'+
						 	'<p>'+ item.name + ' ' + item.surname + '</p> ' +
						 	'<p style="margin-top: -10px; margin-bottom: 0px;">'+ item.municipality + ', ' + item.department + '</p> ' +
						 '</div>'+		
				'</div>';
	return content;
}


$(document).on('click','.person-gs-sp', function(){

	var email = $(this).data('email');
	
	var id = $(this).data('id');
	
	// $('#directions').append(email+';');


	selectedEmails.addToSelection([{id: id, email: email}]);

	checkAddClassPeople();

	// window.location.href = baseUrl + 'users/profile/' + username;

});



	


// checking if is a mobile device


var isMobileDevice = is.mobile();



if (isMobileDevice) {


	/**
	 * Detect event of keypress
	 */
	inputSpeople.keypress(function( event ) {

	  
	  var searchTerm = $.trim($(this).val());
	  
	 
	  // if ( event.which == 13 ) {
	  
	  //    event.preventDefault();
	  
	  // }else{


	  	peopleSearch(searchTerm);

	  // }


	});


	inputSpeople.keydown(function(e) {
			
		
		var searchTerm = $.trim($(this).val());
	    
	    // if( e.which == 8 || e.which == 46 ){

	 
	 		peopleSearch(searchTerm);

	    // } 

	});


}else{



	/**
	 * Detect event of keypress
	 */
	inputSpeople.keypress(function( event ) {

	  
	  var searchTerm = $.trim($(this).val());
	  
	 
	  if ( event.which == 13 ) {
	  
	     event.preventDefault();
	  
	  }else{


	  	peopleSearch(searchTerm);

	  }


	});


	inputSpeople.keydown(function(e) {
			
		
		var searchTerm = $.trim($(this).val());
	    
	    if( e.which == 8 || e.which == 46 ){

	 
	 		peopleSearch(searchTerm);

	    } 

	});

}



function peopleSearch(searchTerm){


    clearTimeout(timerSpeople);

    var ms = 500; // milliseconds
 
    timerSpeople = setTimeout(function() {

			ajaxSearchPeople(searchTerm).done(function(response) {
			

				/**
				 * Clean container 
				 */
				suggestContPeople.empty();

				checkAddClassPeople();

				
				console.log(response);		

				var foundUsers = response.users;


				/**
				 * if there are users
				 */
				if(foundUsers.length >= 1){

					checkRemoveClassPeople();


					suggestContPeople.append("<div class='heading-gs'><b>Personas</b></div>");



					for (var i = 0; i < foundUsers.length; i++) {

						var user = foundUsers[i];

						suggestContPeople.append(getContentSpFormat(user));


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

inputSpeople.focus(function() {

	var searchTerm = $.trim($(this).val());

	if(searchTerm != ''){

		peopleSearch(searchTerm);

	}



});


/**
 * when losing focus 
 */


// suggestContPeople.blur(function() {
 
// 	checkAddClassPeople();

// });



$(document).click(function(event) { 
	


	if($(event.target).is('.jm-search-people')){


		checkRemoveClassPeople();
		

	}else if(!$(event.target).is(suggestContPeople)){


		checkAddClassPeople();

	} 

});


function checkAddClassPeople(){

	if(!suggestContPeople.hasClass("no-display")){

		suggestContPeople.addClass('no-display'); 
	}
}



function checkRemoveClassPeople(){

	if(suggestContPeople.hasClass("no-display")){

		suggestContPeople.removeClass('no-display'); 
	}

}





