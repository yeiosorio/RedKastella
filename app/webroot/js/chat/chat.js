


/**
 * Usamos la función para obtener la información del ususario y cuando esta este lista ejecutamos los scripts con todas las funcionalidads
 */
getUserInfo().done(function(response) {
     
    if (response.success) {

        /**
         * Asignacion de la información del usuario
         * @type Object
         */
        userInfo = response.userInfo;


        console.log(userInfo);


        /**
         * llamamos los scripts
         */
        chatScripts();

     }

})
.fail(function(x) {
    console.log(x);
});




function chatScripts(){
        
    socket.on('connect', function () {
        
        socket.emit('storeClientInfo', { username: userInfo.username });
            
	});


function getChatMessages(data) {
    return $.ajax({
        type:'POST',
        dataType: "json",
        data:data,
        url: baseUrl + "Chat/getChatMessages"
    });
  }



function getChat(userId, username,userToProfilePic){


	var newContent = ''+
		'<div class="panel panel-default " data-user-id="'+userId+'"  id="chat-'+username+'" style="display: block; margin-left:1000px;">'+
		        '<div class="panel-heading" data-toggle="chat-collapse" >'+
		          '<a href="#" class="close"><i class="fa fa-times"></i></a>'+
		          '<a href="#">'+
		            '<span class="pull-left">'+
		            		'<div style="background-image:url('+userToProfilePic+')" class="squared-image"></div>'+
		            '</span>'+
		            '<span class="contact-name">'+username+'</span>'+
		          '</a>'+
		        '</div>'+
		        '<div class="panel-body" >'+
		        	
		        	'<div style="overflow:scroll;" class="current-chat">'+	
		        	'<div class="body-messages" style="height:120px; width:100%;"></div>'+
		        	'</div>'+

		        '</div>'+
		        '<input type="text" class="form-control input-chat-message" data-user-to-profile-pic="'+userToProfilePic+'" data-to-username="'+username+'" data-user-id="'+userId+'"  placeholder="Escribe tu mensaje...">'+

		  '</div>';


	return newContent;

}





$(".ks-chat-contacts li").on('click', function () {


	var userId = $(this).data('user-id');

	var username = $(this).data('user-username');

	var userToProfilePic = $(this).data('user-profile-pic');



	/**
	 * Se obtiene un número definido de mensajes de la última conversación 
	 */
	getChatMessages({userId:userId, fromUserId:userInfo.id }).done(function(pastChats) {
	     

		// agregamos el marcado del una ventana de chat
		$('#Main-Chat-Container').append(getChat(userId, username,userToProfilePic));

		// recorrido por los ultimos mensajes
		for (var i = pastChats.length - 1; i >= 0; i--) {
			
			appendMessageHere(username,pastChats[i].Chat.message);

		};


		$('.current-chat').last().animate({ scrollTop: 9999 }, 'slow');

	})
	.fail(function(x) {
	    console.log(x);
	});


});

/**
 * Funcion que detecta si se ha presionado enter
 */

function pressedEnter(event){

	var keycode = (event.keyCode ? event.keyCode : event.which);
   	
   	if(keycode == '13') {
       
   		return true;

    }

    return false;

}



/**
 * Evento que envia un mensaje
 */
$(document).on('keypress','.input-chat-message',function(event){


	var toUsername = $(this).data('to-username');
	var message = $(this).val();


	var toUserId = $(this).data('user-id');



	var thisInput = $(this);


	 // Si se ha presionado enter
	if (pressedEnter(event)) {



		$.ajax({
				url: baseUrl + 'Chat/saveMessage',
				type: 'post',
				data: {message: message, toUserId: toUserId, userId: userInfo.id },
				dataType: 'json',	
				success: function (response) {
				

					if (response.success == true) {

						sendChatMessage(toUsername, userInfo.username,message);

						appendMessageHere(toUsername,message);



						 thisInput.val('');

						 thisInput.parent().find('.current-chat').animate({ scrollTop: 9999 }, 'slow');


					}else{
						console.log("some error/s..")
					}


				}
			});


	



	}

});


function sendChatMessage(username,fromUsername, message){


	socket.emit('chat-message', {username:username, fromUsername: fromUsername, message: message});

}



	/**
     * Detectamos un mensaje de otro usuario
     */
  	socket.on('chat-message', function(msg){
		


  		appendMessage(msg);


	});


/**
 * Función que agrega un mensaje a una ventana de chat
 */
function appendMessage(msg){

	// contenedor de los mensajes
	var messages = $(document).find('#chat-'+msg.fromUsername).find('.body-messages');

	// Foto del perfil del usuario que envia el mensaje
	var userToProfilePic = $(document).find('#chat-'+msg.fromUsername).find('.input-chat-message').data('user-to-profile-pic');


	var newMessage = 
					'<div class="chat-between-users media other-user-message">'+
		            '<div class="media-left">'+
			      		  '<div style="background-image:url('+userToProfilePic+')" class="circled-image"></div>'+
			        '</div>'+
		            '<div class="media-body">'+
		              '<span class="ks-chat-message"">'+msg.message+'</span>'+
		            '</div>'+
		          '</div>';

    messages.append(newMessage);


    messages.parent().animate({ scrollTop: 9999 }, 'slow');

}

/**
 * Función que agrega un mensaje a una ventana de chat del mismo 
 */
function appendMessageHere(toUsername,message){

	var messages = $(document).find('#chat-'+toUsername).find('.body-messages');

	var newMessage = "";

	// si el ultimo mensaje no es del usuario emisor actual
	if (!messages.find('.chat-between-users').last().hasClass('own-message')) {

		newMessage = 
					'<div class="chat-between-users media own-message">'+
			            '<div class="media-left">'+
			             	'<div style="background-image:url('+userInfo.profilePic+')" class="circled-image"></div>'+
			         	'</div>'+
			            '<div class="media-body">'+
			              '<span class="ks-chat-message">'+message+'</span>'+
			            '</div>'+

		          '</div>';

	// si el ultimo mensaje es del usuario emisor actual	          
	}else{



		messages.find('.chat-between-users').last().append('<div class=""><span class="ks-chat-message">'+message+'</span></div>');

	}

		

    messages.append(newMessage);

}



}


