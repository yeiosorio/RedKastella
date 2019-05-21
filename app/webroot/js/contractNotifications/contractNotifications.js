


/**
 * variable que almacenara los niveles de visibilidad
 */
var privacies;    
  /**
   * Función que obtiene las opciones de visiibilidad
   */

function getPrivacies(){


    return $.ajax({
        type:'GET',
        dataType: "json",
        url: baseUrl+"Privacies/getPrivacies",
    });

}


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
 * Opciones de privacidad
 */
getPrivacies().done(function(response) {



            privacies = response;            

            var options = $('.privacies');
 
            $.each(privacies, function() {

                options.append(new Option(this['Privacy'].title, this['Privacy'].id));

            });

       /**
         * llamamos los scripts
         */
        publicationScripts();


})
.fail(function(x) {
    console.log(x);
});
          


     }

})
.fail(function(x) {
    console.log(x);
});



/**
 * Todos las funciones encargadas de las funcionalidades de las publicaciones
 * @return {[type]} [description]
 */
function publicationScripts(){



      //   socket.on('connect', function () {
        
      //       socket.emit('storeClientInfo', { username: userInfo.username });
            
      //   });

      // /**
      //  * Enviar un like
      //  */
      // function doLike(likeData){

      //   socket.emit('like', likeData);
        
      // }
        

      // var ficticiousNumbNoti = 0;


      // /**
      //  * Detectamos un like
      //  */
      // socket.on('like', function(msg){


      //       ficticiousNumbNoti = ficticiousNumbNoti + 1;


      //       $('#g-notifications').removeClass('no-display');


      //       $('#number-noti').html(ficticiousNumbNoti);


                            
      //       var newContent = '<li class="media" >'+
      //             '<div class="media-left"> '+
      //               '<a href="#">'+
      //                 '<img class="media-object thumb" src="http://localhost/kastella/resourcesFolder/1447080033251319885253906/1449673923497756958007812.jpg" alt="people">'+
      //               '</a>'+
      //             '</div>'+
      //             '<div class="media-body">'+
      //               '<div class="pull-right">'+
      //                 '<span class="label label-default">5 min</span>'+
      //               '</div>';
                      
      //               // <!-- Nombre de Usuario -->
      //           newContent +=' <h5 class="media-heading">'+
      //                 'a '+ msg.username + ' le gusta:'+
      //               '</h5>';

      //               // <!-- Contenido -->
      //           newContent += '<p class="margin-none">'+msg.object.title+'</p>'+
      //             '</div>'+
      //           '</li>';

      //            $('#content-notifications').append(newContent); 
            

      // });


           

/**
 * Guardado de nueva publicación
 */

/**
 * Función que abre el dialogo de selección de archivos multiple
 */
$('.attachDocuments').click(function(){


     $('.attachedDocuments').click();

});







/**
 * Función que envia el formulario de nueva publicación con archivos via ajax
 */
$('#publicationsForm').submit( function(e) {
    

    /**
     * configuración del botón publicar para la animación
     */
    animateButton.selector = '.btn-publish-gls';
    animateButton.iconClass = 'fa fa-cloud-upload';


    animateButton.loading(true);
    
    /**
     * Ajax que envia los datos del formulario y sus archivos
     * se usa un nuevo objeto de tipo FormatData y se le pasa el formulario por parametro
     * processData y contentType en falso para que pueda enviar los archivos adjuntos correctamente
     */
    $.ajax({
          url: baseUrl+ 'Notifications/addPublication/',
          type: 'POST',
          dataType: 'json',
          data: new FormData(this),
          processData: false,
          contentType: false,
          success: function(response){

            /**
             * Reseteamos el formulario
             */
             $("#publicationsForm")[0].reset();

            /**
             * Agregamos el nuevo post al inicio de los posts
             */
            putPost(response,'start');
                
            
            animateButton.loading(false);

            $('.title-publication').focus();
    
          },
          error: function(response){

            
            animateButton.loading(false);
    
          }
    });
    
    e.preventDefault();
});

/**
 * Fin Guardado de nueva publicación
 */





/**
 * Variable que almacena el numero actual del cual obtener los resultados
 * @type {Number}
 */
var currentFromPosts; 

/**
 * variable que almacenara el número de veces que se ha hecho scroll
 */
var timesScrolled;

/**
 * Función que obtiene las publicaciones
 */
function getContractNotifyUser(){

    /**
     * si se han pedido datos, sumamos 1 a la variable, de lo contrario le asignamos valor de 1 
     */
    if (timesScrolled != undefined) {

        timesScrolled = timesScrolled + 1;
    
    }else{

        timesScrolled = 1;
    }

  
    /**
     * si se han pedido datos, sumamos a la variable un valor definido, de lo contrario le asignamos valor de 0 
     */
    if (currentFromPosts != undefined) {

        currentFromPosts = currentFromPosts + 20;

    }else{
        currentFromPosts = 0;
    }

    console.log(timesScrolled + ' - ' + currentFromPosts);

    $.ajax({
        type:'POST',
        data:{from: currentFromPosts}, 
        dataType: "json",
        url: baseUrl+"Notifications/getContractNotifyUser",
        success: function(response) {

          console.log(response);

            /**
             * Recorrido de los posts obtenidos
             */
            $.each(response,function(){

                /**
                 * Llamamos a la función que pone los datos en la presentación
                 */
                putPost(this,'end');

            });

                
            /**
             * Se activa el scroll nuevamente si las veces que se han cargado posts es menor a x
             */
            if (timesScrolled < 2) {


                //scrollableContent();


                
            }else{

                /**
                 * ponemos visible el botón de cargar mas posts quitando y agregando un par de clases
                 */
                $('#morePosts').removeClass('no-display');

                $('#morePosts').removeClass('display');

            }

            animateButton.loading(false); 

        },
        error: function(response) {
        }
            
                   
    });

}


  

/**
 * Función que obtiene un post por su identificador
 */
function getPostById(data){

    return $.ajax({
            type:'POST',
            data: data, 
            dataType: "json",
            url: baseUrl+"Notifications/getPostById"
        });

}


/**
 * Botón de mostrar mas posts
 */
$('#morePosts').click(function(){

    /**
     * Configuración de la animación de cargar mas posts
     */
    animateButton.selector = '.gsl-load-more-posts';
    animateButton.iconClass = 'fa fa-cloud-download';
    
    animateButton.loading(true);

    /**
     * Obtenemos las publicaciones
     */
    getContractNotifyUser();

});


/**
 * Variable que define el contenedor de los posts
 */
var postsContainer = $('#publications');




/**
 * Función que obtiene un post
 * @param  Object   post  pos con toda su información
 * @param  Boolean  withContGrid Variable que determina si se devolvera el post con o sin contenedor
 * @return String   post
 */
function getPostCard(post,withContGrid){    

	console.log(post);

    // console.log(post.User.profilePic);

    var newContent =    "<div class='col-xs-12 col-md-12 col-lg-12 item' >"+
                            "<div class='timeline-block'>"+
                              "<div class='panel panel-default'>"+

                                    // <!-- Contenedor de Encabezado -->
                                    "<div class='panel-heading'>"+
                                        "<div class='media'>"+
                                            "<div class='media-left'>"+
                                                // "<a href='" + baseUrl + "users/profile/"+post.User.username+"'>"+
                                                    "<div class='post-user-picture' style='background-image:url("+userInfo.profilePic+")'></div>"+    
                                                    //"<img src='"+post.User.profilePic+"' style='width:50px; height:50px;' class='media-object'>"+
                                                 "</a>"+
                                            "</div>"+

                                            // <!-- Cabezera -->
                                            "<div class='media-body'>"+

                                                // <!-- Edición - eliminación -->
                                                "<a href='#' class='pull-right text-muted'>"; 

                                                    // si el usuario actual es el dueño del post
                                                   // if (post.User.id == userInfo.id) {

                                                      // Configuración del elemento de editar post
                                                      // newContent += "<i class='fa fa-fw fa-edit edit-post' data-post-id='"+post.Post.id+"'></i>"+
                                                 
                                                  
                                                       //Configuración del elemento de eliminar Post 
                                                    // "<i class='fa fa-fw fa-remove drop-post' data-post-id='"+post.Post.id+"'></i>";

                                                    // }
                                                   

                                                    // "<i class='icon-reply-all-fill fa fa-2x '></i>"+
                                                newContent += "</a>"+
                                                // <!-- Fin Edición - eliminación -->

                                                "<h5>"+post.InterestContract.title+"</h5>"+

                                                // "<a href='"+baseUrl+"users/profile/" + post.User.username + "'   >"+post.User.username+"</a>"+

                                                //fecha amigable formateada de la publicación
                                                // "<span>"+moment(post.Post.modified).fromNow()+"</span>"+
                                            "</div>"+
                                            // <!-- Fin Cabecera -->

                                        "</div>"+
                                    "</div>";
                                    // <!-- FIn contenedor de encabezado -->


                                    /**
                                     * variable que contiene la imagen destacada
                                     * @type String
                                     */
                                    // var postThumbnail = getThumbnail(post);
                                    
                                    /**
                                     * Si hay una ruta definida para la imágen
                                     */
                                    // if (postThumbnail != undefined ) {

                                        // newContent += "<div class='post_thumbnail' style='background-image:url("+postThumbnail+");'></div>";
                                    // }

                                    // <!-- Contenido -->
                                    newContent += "<div class='panel-body' ><p>";

                                    // <b class='h4 margin-none'>"+post.InterestContract.title+"</b><br/>";


                                    newContent +="<div class='post-content' style='text-align: justify;'>";


                                    	newContent += "<h5>" + post.InterestContract.nombre + "</h5>";

                                    	newContent +="<p>"+ post.InterestContract.contenido +"</p>";


                                    	newContent += "<b>Valor:</b> $"+post.InterestContract.valor + "<br /><br />"+

                  										"<b>Link: </b><a href='"+post.InterestContract.link +"'>aqu&iacute;</a><br /><br />"+ 

                  										"<b>Ubicación: </b>"+post.InterestContract.ciudad + " - " + post.InterestContract.category + "<br /><br />"+

                  										"<b>Estado del Proceso: </b>"+post.ContractHistorials.estado_del_proceso + "<br /><br />"+

                  										"<b>Fecha de Apertura: </b>"+post.ContractHistorials.fecha_apertura_proceso + "<br /><br />"+

                  										"<b>Fecha de Cierre: </b>"+post.ContractHistorials.fecha_cierre_proceso + "<br /><br />"+

                  										"<b>Documentos: </b>"+ post.ContractHistorials.number_of_docs;

                                          // postContent(post.Post);

                                    newContent += "</div>";                

                                    /**
                                     * Archivos Adjuntos
                                     * Link, nombre y peso del archivo
                                     */
                                    // $.each(post.Resource,function(){

                                    //     newContent += "<a href='"+this.filePath+"' target='_blank'>"+ this.fileName + "</a> <small>("+this.size_format+")</small> <br />";

                                    // });

                                    /**
                                     * Fin archivos adjuntos
                                     */
                                    
                                       newContent +="</p>"+
                                    "</div>";
                                    // <!-- Fin Contenido -->


                                         //Sección número de comentarios y Me gusta
                                        // newContent += "<div class='view-all-comments'>"+
                                          // "<a href='#' class='see-all-comments' >"+
                                            // "<i class='fa fa-comments-o'></i> Ver Todos "+
                                          // "</a>";
                                          // "<span><span class='number-of-comments'></span> comentarios</span>"+
                                          // "<div class='float-right likes-container'>";

                                            //i-like clase que identificara la funcionalidad de Me guta
                                            
                                            //data-id identificador del post
                                            
                                            //data-i-like define si al usuario actualmente le gusta o no el post 
                                            
                                            // "<a href='#' class='i-like' data-id='"+post.Post.id+"' data-i-like='"+post.Post.ilike+"' >"+

                                              //number-likes clase que define el contenedor del número de likes con un mensaje
                                              // "<i class='fa fa-thumbs-up'></i> <span class='number-likes'>"+post.Post.likes;

                                              /**
                                               * si al usuario le gusta el post, escribimos:
                                               */
                                              // if (post.Post.ilike != 0) {

                                              //   newContent +=" Ya no me gusta";  

                                              // }else{
                                                  
                                              //   newContent +=" Me gusta";
                                              // }


                                            // newContent +="</span></a></div>"+

                                        // newContent += "</div>";
                                        // Fin sección número de comentarios y Me gusta


                                            // Inicio de comentarios       
                                            // newContent += "<ul class='comments comments-container'>";

                                            // if (post.PostComment.length) {

                                            //   var moreThan;
                                            //     //si hay mas de cinco comentarios
                                            //    if (post.PostComment.length > 4 ) {

                                            //       moreThan = 5;
                                                  
                                            //     }


                                            //     var currentCommentCount = 0;  
                                                
                                            //     /**
                                            //      * Recorrido por los comentarios del post
                                            //      */
                                            //     $.each(post.PostComment,function(){
                                                      
                                            //         /**
                                            //          * Si no se ha superado el limite definido 
                                            //          */
                                            //         if (currentCommentCount != moreThan) {

                                            //             /**
                                            //              * Obtenemos el comentario
                                            //              */
                                            //             newContent += getFormattedComment(this.Coment);

                                            //         }else{
                                            //           return false;
                                            //         }
                                                
                                            //         currentCommentCount++;

                                            //     });
                                                 
                                            // }
                                            // //Fin Comentarios
                                            


                                            // Inicio Formulario de comentarios
                                            // newContent += "<li class='comment-form'>"+
                                                
                                            //     "<form class='commet-post-form' method='post'>"+
                                            //     "<div class='input-group'>"+
                                            //     "<span class='input-group-btn'>"+
                                            //       "<a href='"+baseUrl + "users/profile/" + userInfo.username + "'> <div class='post-user-picture' style='float:left;background-image:url("+userInfo.profilePic+")'></div></a>"+    
                                            //        "</span>"+
                                            //         "<textarea name='comment' placeholder='Ingrese su comentario...' rows='2'  class='form-control comment' style='width:100%' cols='30' required></textarea>"+
                                            //         "<input type='number' style='display:none;' value='"+post.Post.id+"' name='postId' /> "+
                                            //     "</div>"+
                                            //     "<div class='input-group' style='width:100%'>"+
                                            //     "<button type='submit' class='btn btn-primary comment-post' style='float: right;' title='Comentar'><i class='fa fa-comments'></i> </button>"+   
                                             
    
                                            //     "</div>"+        
                                            //    "</form>"+ 
                                            //   "</li>"+
                                          //Fin Formulario de comentarios

                                          // "</ul>";
                              
                             newContent +="</div>"+
                            "</div>"+
                        "</div>";


    if (withContGrid) {

        return "<div class='grid-item'>"+newContent+"</div>";

    }else{
        return newContent;        
    }


}



/**
 * función que formatea el contenido del post
 * @param  Object post información del post
 * @return String Contenido
 */
function postContent(post){



  if (getNumberWords(post.content) > 35) {

      // <i class='fa fa-plus-circle'></i>
      
      return trimWords(post.content,35)+ "...<a href='#' data-post-id='"+post.id+"' class='show-all-post-content'> Ver mas</a>";

  }else{

    return post.content;

  }

}


/**
 * Variable que contendra el objeto que tiene el conetenido del post
 */
var postContentContainer;


$(document).on('click','.show-all-post-content',function(){

   postContentContainer =  $(this).parents('.post-content');

        /**
           * Obtenemos el post
           */
          getPostById({postId: $(this).data('post-id')}).done(function(response) {
              
              postContentContainer.html(response.Post.content + " <a href='#' data-post-id='"+response.Post.id+"' class='show-less-post-content'> Ver Menos</a>");

              postsContainer.masonry();

          }).fail(function(x) {
              console.log(x);
          });

}); 



$(document).on('click','.show-less-post-content',function(){


   postContentContainer =  $(this).parents('.post-content');


        /**
           * Obtenemos el post
           */
          getPostById({postId: $(this).data('post-id')}).done(function(response) {
              
              postContentContainer.html(postContent(response.Post));

              postsContainer.masonry();

          }).fail(function(x) {
              console.log(x);
          });

}); 






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



/**
 * Función que obtiene el número de palabras de un string
 * @return Int número de palabras
 */
function getNumberWords(theString){


  return theString.split(/\s+/).length;

}









/**
 * Funcion que toma datos de un post y los inserta en el DOM
 * @param  Object post Datos del post
 * @param  Boolean endStart 'start' si es al principio, 'end' si va al final
 */
function putPost(post,endStart){

    /**
     * Contenido del Post en formato html
     * @type {String}
     */
    var newContent = getPostCard(post,true);
        
        /**
         * Variable que contiene el contenido nuevo como entidad html
         */
        var $newContentObject = $(newContent);

        /**
         * se agrega el objeto de contenido al contenedor principal previamente configurado
         */
        switch(endStart) {
        
          case 'end':
              postsContainer.append( $newContentObject ).masonry( 'appended', $newContentObject );
              break;
        
          case 'start':
              postsContainer.prepend($newContentObject).masonry( 'prepended', $newContentObject );       
              break;
        }

}


/**
 * Función que obtiene la imagen destaca de un post
 * @param  Object post post
 * @return String url de la imagen
 */
function getThumbnail(post){

/**
 * extensiones de imagenes
 * @type {Array}
 */
var extensions = ['png','jpg'];

/**
 * Variable que contendra la url de la imagen
 */
var filePath;

    
    /**
     * recorrido para buscar en los recursos
     */
    $.each(post.Resource,function(){ 

        /**
         * si hay una imagen
         */
        if ($.inArray(this.ResourceExtension.extension, extensions) != -1){

            /**
             * Asignacion de la ruta 
             */
            filePath = this.filePath;
            
            /**
             * cerrado de la búsqueda
             */
            return false;
        }

    });

    /**
     * Retorno del resultado
     */
    return filePath;

}




/**
 * Función que inicializa el mansonry effect
 */
function masonryEffect(){
    
// función que acciona acopla los elementos 
$('#publications').masonry({

  /**
   * clase que tiene cada item del grid 
   * @type {String}   
   */
  itemSelector: '.grid-item ',

  /**
   * Ancho de las columnas
   * @type {Number}
   */
  columnWidth: 400,

  /**
   * Es animado
   * @type {Boolean}
   */
  isAnimated: true,
    
  /**
   * Otras opciones
   */
  // animationOptions: {
  //   duration: 750,
  //   easing: 'linear',
  //   queue: false
  // }

});

}



/**
 * llamado de la función que usa el plugin mansonry
 */
masonryEffect();



/**
 * Función que detecta si un elemento esta en el foco del footer cuando se hace scroll
 * @param  elem elemento sobre el cual se hace scroll
 */
function element_in_scroll(elem)
{
    var docViewTop = $(document).scrollTop();
    var docViewBottom = docViewTop + $(document).height();
 
    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();
 
    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}





/**
 * Función que ejecuta la funcion para detectar scroll en el contenedor de los posts
 */
function scrollableContent(){

     // if (element_in_scroll("#publications .grid-item:last")) {

     //            //se quita el scroll del elemento
     //            unBindScrollableContent();

     //            /**
     //             * llamamos a la función que obtiene las publicaciones
     //             */
     //            getContractNotifyUser();

     // }


    if ($("#publications .grid-item:last") != undefined) {

       /**
        * llamamos a la función que obtiene las publicaciones
        */
        getContractNotifyUser();

   }




    $('.scrollable_content').scroll(function(e){ 

          /**
           * Detección del elemento final cuando se haga scroll
           */
         if (element_in_scroll("#publications .grid-item:last")) {


                  //Here you must do what you need to achieve the infinite scroll effect...

                  //se quita el scroll del elemento
                  unBindScrollableContent();

                  /**
                   * llamamos a la función que obtiene las publicaciones
                   */
                  getContractNotifyUser();

          };








    });      

}

/**
 * Función que quita el scrol del contenedor de los posts
 * @return {[type]} [description]
 */
function unBindScrollableContent(){

    $('.scrollable_content').unbind('scroll');
            
}


/**
 * Inicialización del scroll sobre el contenedor de las publicaciones
 */
scrollableContent();


//Función que obtiene las prublicaciones
// getContractNotifyUser();



$('.btn-add-publication').click(function(){


   $('.new-publication-modal').modal('show');


});


function getUserLikesPost(data){


  return $.ajax({
      url: baseUrl + 'Likes/getUserLikesPost',
      type: 'post',
      dataType: 'json',
      data: data
    });


}




}




function showHidePubblicationButton(){

        if ($(window).width() <= 768 ){

 
              $('.btn-add-publication-resp').removeClass('no-display');

        }else{


              $('.btn-add-publication-resp').addClass('no-display');

      }
}


$(window).resize(function(){     


  showHidePubblicationButton();

});


  showHidePubblicationButton();

$('.add-publication-global').removeClass('no-display');








