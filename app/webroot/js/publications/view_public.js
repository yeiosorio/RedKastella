
/**
 * compartir
 */

$(document).on('click','.copyLinkShare', function(){


  var id = $(this).data('id');

  var post = $(document).find('.copyLinkShare-'+id).data('post');

  console.log(post);

  var url = 'redkastella.com/Publications/viewPublic/'+id;

  $('.share-on-fb-link').attr("href", "https://www.facebook.com/sharer/sharer.php?u=http%3A//"+url+"&title=Redkastella Novedades: "+post.title+"&description="+post.content+"&picture=http://redkastella.com/resourcesFolder/1447080033251319885253906/1459956723848613023757935.jpg");
  $('.share-on-tw-link').attr("href", "https://twitter.com/home?status=Redkastella Novedades: "+post.title+" @KastellaApp http%3A//"+url);
  $('.share-on-wp-link').attr("href", "whatsapp://send?text=http://"+url);

  $('.justCopyLink').data("id", id);

  $('.share-modal').modal('show');


});
  
/**
 * Funcionalidad copiar al portapapeles
 */

var shareLinkClipboard = undefined; 

// var linkToShare = "";


$(document).on('click','.justCopyLink', function(){

  var selectorName = '.copyLinkShare-'+$(this).data('id');

  shareLinkClipboard = new Clipboard(selectorName);

  shareLinkClipboard.on('success', function(e) {

    // linkToShare = e.text;

     // alert("link copiado "+ linkToShare);

    $('.just-link').modal('show');

  });

  var linkToShare = "";

  /**
   * trigger para copiar
   */
  $(document).find(selectorName).click();


});



$(document).on('click','.do-publication',function(){





  $('.new-publication-modal').modal('show');

});  

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
     
    // if (response.success) {

        /**
         * Asignacion de la información del usuario
         * @type Object
         */
        userInfo = response.userInfo;


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
          


     // }

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




     $('.attachedDocuments').trigger('click');

});







/**
 * Función que envia el formulario de nueva publicación con archivos via ajax
 */
$('#publicationsForm').submit( function(e) {

    localStorage.newPublicationTitle = $('.title-publication').val();

    localStorage.newPublicationContent = $('.content-publication').val();

    localStorage.newPublicationPrivacies = $('.privacies').val();

    $('.register-new-modal').modal('show');

    /**
     * configuración del botón publicar para la animación
     */
    // animateButton.selector = '.btn-publish-gls';
    // animateButton.iconClass = 'fa fa-cloud-upload';


    // animateButton.loading(true);
    
    // /**
    //  * Ajax que envia los datos del formulario y sus archivos
    //  * se usa un nuevo objeto de tipo FormatData y se le pasa el formulario por parametro
    //  * processData y contentType en falso para que pueda enviar los archivos adjuntos correctamente
    //  */
    // $.ajax({
    //       url: baseUrl+ 'Publications/addPublication/',
    //       type: 'POST',
    //       dataType: 'json',
    //       data: new FormData(this),
    //       processData: false,
    //       contentType: false,
    //       success: function(response){

    //         /**
    //          * Reseteamos el formulario
    //          */
    //          $("#publicationsForm")[0].reset();

    //         /**
    //          * Agregamos el nuevo post al inicio de los posts
    //          */
    //         putPost(response,'start');
            
    //         animateButton.loading(false);

    //         $('.new-publication-modal').modal('hide');

    //         // $('.title-publication').focus();
    
    //       },
    //       error: function(response){

            
    //         animateButton.loading(false);
    
    //       }
    // });
    
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
function getPublications(){

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
        url: baseUrl+"Publications/getPublicationsPublic",
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
            url: baseUrl+"Publications/getPostById"
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
    getPublications();

});


/**
 * Variable que define el contenedor de los posts
 */
var postsContainer = $('#publications');




/**
 * Objeto en el que se define el post a eliminar
 * @type {Object}
 */
var dataDrop ={
    postId: undefined,
    gridItem: undefined
};

/**
 * Función que prepara un post para ser eliminado
 */

$(document).on('click','.drop-post',function(){
    
    /**
     * Identificador del post
     * @type Int
     */
    dataDrop.postId = $(this).data('post-id');


    /**
     * Obtenemos el item del grid, del cual se genero el evento
     * @type Object
     */
    dataDrop.gridItem = $(this).parents('.grid-item');

        //mostramos la modal de confirmación
    $('#confrimDropPostModal').modal('show');



});




/**
 * Variable que contendra el item seleccionado para editar
 */
var currentEditPost;

/**
 * Función que prepara un post para su edición
 */

$(document).on('click','.edit-post',function(){



    /**
     * Identificador del post
     * @type Int
     */
    var postId = $(this).data('post-id');

    /**
     * Obtenemos el item del grid, del cual se genero el evento
     */
     currentEditPost = $(this).parents('.grid-item');

    /**
     * 
     */
    // currentEditPost.find('.post_thumbnail').addClass('no-display');
    
    // currentEditPost.find('.panel-body').addClass('no-display');


    /**
     * eliminación del botón de edición
     */
    // $(this).remove();
    
         /**
           * Obtenemos el post
           */
           getPostById({postId: postId}).done(function(response) {

            var post = response;              


              $('.edit-form-container').html(getEditForm(post));
              
              $('.edit-publication-modal').modal('show');


                /**
                 * Reorganizamos el grid 
                 */
                // postsContainer.masonry();

          }).fail(function(x) {
            console.log(x);
          });



});


//Objeto que retorna un formulario formateado 
var formatForm = {
    formSelector: undefined,
    
    /**
     * Función de animación de cargar
     */
    format: function () {
    

        var dataArray = this.formSelector.serializeArray(),
            dataObj = {};

        $(dataArray).each(function(i, field){
          dataObj[field.name] = field.value;
        });
            

        return dataObj;

    }
}



/**
 * Función que envia el formulario de nueva publicación con archivos via ajax
 */
$(document).on('submit','.publicationEditForm', function(e) {
        

    /**
     * configuración del botón editar publicación para la animación
     */

    animateButton.selector = '.btn-edit-publish-gls';
    animateButton.iconClass = 'fa fa-cloud-upload';

    /**
     * Inicio de la animación
     */
    animateButton.loading(true);


    /**
     * Inicializamos el objeto formatForm
     */
    formatForm.formSelector = $(this);
    
    /**
     * Formateamos el formulario
     * @type Array
     */    
    var formData = formatForm.format(); 


    /**
     * Editamos el post
     */
    $.ajax({
          url: baseUrl+ 'Publications/editPost/',
          type: 'POST',
          dataType: 'json',
          data: formData,
          success: function(response){
             
            /**
             * Si se guardo con exito establecemos los nuevos datos
             */
            if (response.success == true) {  

                currentEditPost.html(getPostCard(response.post,false));

                /**
                 * Reorganizamos el grid
                 */
                postsContainer.masonry();

            }

            /**
             * reestablecemos la animación del boton 
             */
            animateButton.loading(false);


            /**
             * esconder la ventana modal de editar publicación
             */
            $('.edit-publication-modal').modal('hide');
     
          },
          error: function(response){
              
           /**
            * reestablecemos la animación del boton 
            */
            animateButton.loading(false);
    
          }
    });
    
    /**
     * quitamos el envio del formualario por defecto
     */
    e.preventDefault();
});
  

/**
 * Función que obtiene el formulario de edición del post con sus datos
 * @param  Object post post
 * @return String html 
 */
function getEditForm(post){

  /**
   * cargamos las opciones de visibilidad
   * @type {String}
   */
    

  var privaciesOptions ="";

            $.each(privacies, function() {

                /**
                 * configuración de la opcion del post
                 */
                if (this['Privacy'].id == post.Privacy.id) {

                    privaciesOptions +="<option value='"+this['Privacy'].id+"' selected>"+this['Privacy'].title+"</option>"; 
                
                }else{
                
                    privaciesOptions +="<option value='"+this['Privacy'].id+"'>"+this['Privacy'].title+"</option>"; 
                }

              
            });
  
  /**
   * Variable que contiene el formulario
   * @type {String}
   */
  var form = "<form method='post' class='publicationEditForm'>"+

                            //  Titulo 
                            "<input name='title' value='"+post.Post.title+"' type='text' placeholder='Ingrese un título para su publicación' class='form-control' style='background-color:white;' maxlength='100' required/>"+

                            //  Contenido 
                            "<textarea name='content' placeholder='Ingrese su texto...' rows='5' class='form-control' style='background-color:white;' maxlength='1000' required>"+post.Post.content+"</textarea>"+

                            //  Sección de botones 
                            "<div class='panel-body buttons-spacing-vertical'>"+
                                "<p>"+

                                   
                                    //  Botón de seleccione de visibilidad 
                                    "<select name='privacies_id' class='btn btn-primary dropdown-toggle privacies custom-dropdown-height' data-toggle='dropdown'>"+privaciesOptions+

                                   "</select>"+

                                  /**
                                    * Agregamos la identificación del post en este campo
                                    * @type Int
                                    */
                                    "<input type='numer' value='"+post.Post.id+"' name='id' style='display:none;' /> "+
                                    //  Enviar 
                                    "<button type='submit' class='btn btn-primary float-right' ><i class='fa fa-cloud-upload btn-edit-publish-gls'></i> Publicar</button>"+
                                "</p>"+
                            "</div>"+
                            //  Fin sección de botones 
                        "</form>";

    /**
     * Retornamos el formulario
     */
    return form;

}


/**
 * Función de confirmar eliminación
 */
$('#confirmDelete').click(function(){

    $('#confrimDropPostModal').modal('hide');
    
    /**
     * eliminamos el post
     */
    deletePost();

});


/**
 * Función que elimina un post
 */
function deletePost(){

        //usamos el objeto dataDrop para obtenemos los datos del post que se eliminara

      $.ajax({
        type:'POST',
        data:{postId: dataDrop.postId}, 
        dataType: "json",
        url: baseUrl+"Publications/deletePost",
        success: function(response) {
            /**
             * Si se borro correctamente
             */
            if (response.success == true) {

                /**
                 * Removemos el post y Reorganizamos todos los posts
                 */
                postsContainer.masonry( 'remove', dataDrop.gridItem).masonry('layout');

            }else{
              console.log(response);
            }
        },
        
        error: function(response) {
            console.log(response);
        }
                   
        });
}


function extractLinks(content) {

  
  var rx =   /(((https?:\/\/)|(www\.))[^\s]+)/g;

  var arr = []; 

  var matchArray;

  while( (matchArray = rx.exec( content )) !== null )
  {
        var token = matchArray[0];
        arr.push( token );
  }


  if(arr.length > 0){

      return arr;
  }else{

    return false;
  }



}



function matchYoutubeUrl(url) {
    var p = /^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
    if(url.match(p)){
        return url.match(p)[1];
    }
    return false;
}



function getFirstYoutubeLinkInText(text){

  var extracted = extractLinks(text);

  if(extracted !== false){

    for (var i = 0; i < extracted.length; i++) {
    
      console.log(extracted[i]);

      if(matchYoutubeUrl(extracted[i]) !== false ){

        return matchYoutubeUrl(extracted[i]);

        break;
      }  
    
    }


  }
  
    return false;
  

}





function urlify(text) {

    var urlRegex = /(((https?:\/\/)|(www\.))[^\s]+)/g;

    /**
     * if has www in url 
     * @type {RegExp}
     */
    var urlReg = /www/ig;

    var matchArray;
  
    return text.replace(urlRegex, function(url) {

        if((matchArray = urlReg.exec( url )) !== null){

          return '<a href="http://' + url + '" target="_blank" style="text-decoration: underline;">' + url + '</a>';

        }else{

          return '<a href="' + url + '" target="_blank" style="text-decoration: underline;">' + url + '</a>';

        }
  
    })
  

}





/**
 * Función que obtiene un post
 * @param  Object   post  pos con toda su información
 * @param  Boolean  withContGrid Variable que determina si se devolvera el post con o sin contenedor
 * @return String   post
 */
function getPostCard(post,withContGrid){    

    // console.log(post.User.profilePic);

    var newContent =    "<div class='col-xs-12 col-md-12 col-lg-12 item' >"+
                            "<div class='timeline-block'>"+
                              "<div class='panel panel-default'>"+

                                    // <!-- Contenedor de Encabezado -->
                                    "<div class='panel-heading'>"+
                                        "<div class='media'>"+
                                            "<div class='media-left'>"+
                                                "<a href='' class='show-register-new-modal'>"+
                                                    "<div class='post-user-picture' style='background-image:url("+post.User.profilePic+")'></div>"+    
                                                    //"<img src='"+post.User.profilePic+"' style='width:50px; height:50px;' class='media-object'>"+
                                                 "</a>"+
                                            "</div>"+

                                            // <!-- Cabezera -->
                                            "<div class='media-body'>"+

                                                // <!-- Edición - eliminación -->
                                                "<a href='#' class=' pull-right text-muted'>"; 

                                                    // si el usuario actual es el dueño del post
                                                   if (post.User.id == userInfo.id) {

                                                      // Configuración del elemento de editar post
                                                      newContent += "<i class='fa fa-fw fa-edit edit-post' data-post-id='"+post.PostPublic.id+"'></i>"+
                                                 
                                                  
                                                       //Configuración del elemento de eliminar Post 
                                                    "<i class='fa fa-fw fa-remove drop-post' data-post-id='"+post.PostPublic.id+"'></i>";

                                                    }
                                                   

                                                    // "<i class='icon-reply-all-fill fa fa-2x '></i>"+
                                                newContent += "</a>"+
                                                // <!-- Fin Edición - eliminación -->

                                                "<a href='' class='show-register-new-modal'>"+post.User.username+"</a>"+

                                                //fecha amigable formateada de la publicación
                                                "<span>"+moment(post.PostPublic.modified).fromNow()+"</span>"+
                                            "</div>"+
                                            // <!-- Fin Cabecera -->

                                        "</div>"+
                                    "</div>";
                                    // <!-- FIn contenedor de encabezado -->


                                    /**
                                     * variable que contiene la imagen destacada
                                     * @type String
                                     */
                                    var postThumbnail = getThumbnail(post);
                                    
                                    /**
                                     * Si hay una ruta definida para la imágen
                                     */
                                    if (postThumbnail != undefined ) {

                                        newContent += "<div class='post_thumbnail' style='background-image:url("+postThumbnail+");'></div>";
                                    }


                                    // <!-- Contenido -->
                                    newContent += "<div class='panel-body' >"+
                                      "<p><a href='"+baseUrl+"publications/viewPublic/"+post.PostPublic.id+"' style='text-decoration: underline;'> <b class='h4 margin-none'>"+post.PostPublic.title+"</b></a><br/>"; 

                                      var hasYoutubeLink = getFirstYoutubeLinkInText(post.PostPublic.content);

                                      if(hasYoutubeLink != false){

                                        newContent += '<p><iframe width="100%" height="250" src="https://www.youtube.com/embed/'+hasYoutubeLink+'" frameborder="0" allowfullscreen></iframe></p>';

                                      }                                      

                                      newContent += "<div class='post-content' style='text-align: justify;'>";
                                         
                                      newContent += postContent(post.PostPublic);

                                      newContent += "</div><br /> <br />";                

                                    /**
                                     * Archivos Adjuntos
                                     * Link, nombre y peso del archivo
                                     */
                                    $.each(post.Resource,function(){

                                        newContent += "<a href='"+this.filePath+"' target='_blank'>"+ this.fileName + "</a> <small>("+this.size_format+")</small> <br />";

                                    });

                                    /**
                                     * Fin archivos adjuntos
                                     */
                                    
                                       newContent +="</p>"+
                                    "</div>";
                                    // <!-- Fin Contenido -->


                                         //Sección número de comentarios y Me gusta
                                        newContent += "<div class='view-all-comments'>"+
                                          "<a href='#' class='see-all-comments' data-post-id='"+post.PostPublic.id+"'>"+
                                            "<i class='fa fa-comments-o'></i> Ver Todos "+
                                          "</a>"+
                                          "<span><span class='number-of-comments'>"+post.PostComment.length+"</span> comentarios</span>"+
                                          "<div class='float-right likes-container'>"+

                                            //i-like clase que identificara la funcionalidad de Me guta
                                            
                                            //data-id identificador del post
                                            
                                            //data-i-like define si al usuario actualmente le gusta o no el post 
                                            
                                            "<a href='#' class='i-like' data-id='"+post.PostPublic.id+"' data-i-like='"+post.PostPublic.ilike+"' >"+

                                              //number-likes clase que define el contenedor del número de likes con un mensaje
                                              "<i class='fa fa-thumbs-up'></i> <span class='number-likes'>"+post.PostPublic.likes;

                                              /**
                                               * si al usuario le gusta el post, escribimos:
                                               */
                                              if (post.PostPublic.ilike != 0) {

                                                newContent +=" Ya no me gusta";  

                                              }else{
                                                  
                                                newContent +=" Me gusta";
                                              }


                                            newContent +="</span></a></div>"+

                                        "</div>";
                                        // Fin sección número de comentarios y Me gusta


                                            // Inicio de comentarios       
                                            newContent += "<ul class='comments comments-container'>";

                                            if (post.PostComment.length) {

                                              var moreThan;
                                                //si hay mas de cinco comentarios
                                               if (post.PostComment.length > 4 ) {

                                                  moreThan = 5;
                                                  
                                                }


                                                var currentCommentCount = 0;  
                                                
                                                /**
                                                 * Recorrido por los comentarios del post
                                                 */
                                                $.each(post.PostComment,function(){
                                                      
                                                    /**
                                                     * Si no se ha superado el limite definido 
                                                     */
                                                    if (currentCommentCount != moreThan) {

                                                        /**
                                                         * Obtenemos el comentario
                                                         */
                                                        newContent += getFormattedComment(this.Coment);

                                                    }else{
                                                      return false;
                                                    }
                                                
                                                    currentCommentCount++;

                                                });
                                                 
                                            }
                                            //Fin Comentarios
                                            


                                            // Inicio Formulario de comentarios
                                            newContent += "<li class='comment-form'>"+
                                                
                                                "<form class='commet-post-form' method='post'>"+
                                                "<div class='input-group'>"+
                                                "<span class='input-group-btn'>"+
                                                  "<a href='' class='show-register-new-modal'> <div class='post-user-picture' style='float:left;background-image:url("+userInfo.profilePic+")'></div></a>"+    
                                                   "</span>"+
                                                    "<textarea name='comment' placeholder='Ingrese su comentario...' rows='2'  class='form-control comment' style='width:100%' cols='30' ></textarea>"+
                                                    "<input type='number' style='display:none;' value='"+post.PostPublic.id+"' name='postId' /> "+
                                                "</div>"+
                                                "<div class='input-group' style='width:100%'>"+
                                                
                                                // "<button type='submit' class='btn btn-primary comment-post' style='float: right;' title='Comentar'><i class='fa fa-comments'></i> </button>"+   
                                              
                                              "<button type='button' style='float: right; margin-top: 5px; display:none;' "+
                                                  
                                                  "class='btn btn-primary copyLinkShare-"+post.PostPublic.id+"' "+
                                                  
                                                  "data-post='"+JSON.stringify(post.PostPublic)+"'"+

                                                  "data-toggle='tooltip' "+
                                                  "title='Haciendo Click aqui podras compartir esta novedad en cualquiera de tus red sociales' "+
                                                  "data-clipboard-text='http://redkastella.com/publications/viewPublic/"+post.PostPublic.id+"' "+
                                                  "data-placement='right'"+
                                                ">"+

                                                  "  Compartir"+
                                                " </button>"+
      
                                                

                                              "<button type='button' style='float: right; margin-top: 5px;' data-id='"+post.PostPublic.id+"' "+
                                                  
                                                  "class='btn btn-primary copyLinkShare' >"+
                                                    
                                                  "<i class='fa fa-globe' aria-hidden='true'></i> "+

                                                  "<i class='fa fa-facebook' aria-hidden='true'></i> "+

                                                  "<i class='fa fa-twitter' aria-hidden='true'></i> ";


                                                 if(is.mobile()){

                                                     newContent += "<i class='fa fa-whatsapp' aria-hidden='true'></i> ";
                                                    

                                                   }
                                                 


                                                newContent += "  Compartir"+
                                                " </button>"+


    
                                                "</div>"+        
                                               "</form>"+ 
                                              "</li>"+
                                          //Fin Formulario de comentarios

                                          "</ul>";
                              
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
 * Función que obtiene un post
 * @param  Object   post  pos con toda su información
 * @param  Boolean  withContGrid Variable que determina si se devolvera el post con o sin contenedor
 * @return String   post
 */
function getSinglePostCard(post,withContGrid){    

    // console.log(post.User.profilePic);

    var newContent =    "<div class='col-xs-12 col-md-12 col-lg-12 item' >"+
                            "<div class='timeline-block'>"+
                              "<div class='panel panel-default'>"+

                                    // <!-- Contenedor de Encabezado -->
                                    "<div class='panel-heading'>"+
                                        "<div class='media'>"+
                                            "<div class='media-left'>"+
                                                "<a href='' class='show-register-new-modal'>"+
                                                    "<div class='post-user-picture' style='background-image:url("+post.User.profilePic+")'></div>"+    
                                                    //"<img src='"+post.User.profilePic+"' style='width:50px; height:50px;' class='media-object'>"+
                                                 "</a>"+
                                            "</div>"+

                                            // <!-- Cabezera -->
                                            "<div class='media-body'>"+

                                                // <!-- Edición - eliminación -->
                                                "<a href='#' class='pull-right text-muted'>"; 

                                                    // si el usuario actual es el dueño del post
                                                   if (post.User.id == userInfo.id) {

                                                      // Configuración del elemento de editar post
                                                      newContent += "<i class='fa fa-fw fa-edit edit-post' data-post-id='"+post.Post.id+"'></i>"+
                                                 
                                                  
                                                       //Configuración del elemento de eliminar Post 
                                                    "<i class='fa fa-fw fa-remove drop-post' data-post-id='"+post.Post.id+"'></i>";

                                                    }
                                                   

                                                    // "<i class='icon-reply-all-fill fa fa-2x '></i>"+
                                                newContent += "</a>"+
                                                // <!-- Fin Edición - eliminación -->

                                                "<a href='' class='show-register-new-modal'>"+post.User.username+"</a>"+

                                                //fecha amigable formateada de la publicación
                                                "<span>"+moment(post.PostPublic.modified).fromNow()+"</span>"+
                                            "</div>"+
                                            // <!-- Fin Cabecera -->

                                        "</div>"+
                                    "</div>";
                                    // <!-- FIn contenedor de encabezado -->


                                    /**
                                     * variable que contiene la imagen destacada
                                     * @type String
                                     */
                                    var postThumbnail = getThumbnail(post);
                                    
                                    /**
                                     * Si hay una ruta definida para la imágen
                                     */
                                    if (postThumbnail != undefined ) {

                                        newContent += "<div class='post_thumbnail' style='background-image:url("+postThumbnail+");'></div>";
                                    }


                                    // <!-- Contenido -->
                                    newContent += "<div class='panel-body' >"+
                                      "<p><a href='"+baseUrl+"publications/viewPublic/"+post.PostPublic.id+"' style='text-decoration: underline;'><b class='h4 margin-none'>"+post.PostPublic.title+"</b></a><br/>"; 

                                      var hasYoutubeLink = getFirstYoutubeLinkInText(post.PostPublic.content);

                                      if(hasYoutubeLink != false){

                                        newContent += '<p><iframe width="100%" height="350" src="https://www.youtube.com/embed/'+hasYoutubeLink+'" frameborder="0" allowfullscreen></iframe></p>';

                                      }                                      

                                      newContent += "<div class='post-content' style='text-align: justify;'>";
                                         
                                      newContent += urlify(post.PostPublic.content); 

                                      newContent += "</div><br /> <br />";                

                                    /**
                                     * Archivos Adjuntos
                                     * Link, nombre y peso del archivo
                                     */
                                    $.each(post.Resource,function(){

                                        newContent += "<a href='"+this.filePath+"' target='_blank'>"+ this.fileName + "</a> <small>("+this.size_format+")</small> <br />";

                                    });

                                    /**
                                     * Fin archivos adjuntos
                                     */
                                    
                                       newContent +="</p>"+
                                    "</div>";
                                    // <!-- Fin Contenido -->


                                         //Sección número de comentarios y Me gusta
                                        newContent += "<div class='view-all-comments'>"+
                                          "<a href='#' class='see-all-comments' data-post-id='"+post.PostPublic.id+"'>"+
                                            "<i class='fa fa-comments-o'></i> Ver Todos "+
                                          "</a>"+
                                          "<span><span class='number-of-comments'>"+post.PostComment.length+"</span> comentarios</span>"+
                                          "<div class='float-right likes-container'>"+

                                            //i-like clase que identificara la funcionalidad de Me guta
                                            
                                            //data-id identificador del post
                                            
                                            //data-i-like define si al usuario actualmente le gusta o no el post 
                                            
                                            "<a href='#' class='i-like' data-id='"+post.PostPublic.id+"' data-i-like='"+post.PostPublic.ilike+"' >"+

                                              //number-likes clase que define el contenedor del número de likes con un mensaje
                                              "<i class='fa fa-thumbs-up'></i> <span class='number-likes'>"+post.PostPublic.likes;

                                              /**
                                               * si al usuario le gusta el post, escribimos:
                                               */
                                              if (post.PostPublic.ilike != 0) {

                                                newContent +=" Ya no me gusta";  

                                              }else{
                                                  
                                                newContent +=" Me gusta";
                                              }


                                            newContent +="</span></a></div>"+

                                        "</div>";
                                        // Fin sección número de comentarios y Me gusta


                                            // Inicio de comentarios       
                                            newContent += "<ul class='comments comments-container'>";

                                            if (post.PostComment.length) {

                                              var moreThan;
                                                //si hay mas de cinco comentarios
                                               if (post.PostComment.length > 4 ) {

                                                  moreThan = 5;
                                                  
                                                }


                                                var currentCommentCount = 0;  
                                                
                                                /**
                                                 * Recorrido por los comentarios del post
                                                 */
                                                $.each(post.PostComment,function(){
                                                      
                                                    /**
                                                     * Si no se ha superado el limite definido 
                                                     */
                                                    if (currentCommentCount != moreThan) {

                                                        /**
                                                         * Obtenemos el comentario
                                                         */
                                                        newContent += getFormattedComment(this.Coment);

                                                    }else{
                                                      return false;
                                                    }
                                                
                                                    currentCommentCount++;

                                                });
                                                 
                                            }
                                            //Fin Comentarios
                                            


                                            // Inicio Formulario de comentarios
                                            newContent += "<li class='comment-form'>"+
                                                
                                                "<form class='commet-post-form' method='post'>"+
                                                "<div class='input-group'>"+
                                                "<span class='input-group-btn'>"+
                                                  "<a href='' class='show-register-new-modal'><div class='post-user-picture' style='float:left;background-image:url("+userInfo.profilePic+")'></div></a>"+    
                                                   "</span>"+
                                                    "<textarea name='comment' placeholder='Ingrese su comentario...' rows='2'  class='form-control comment' style='width:100%' cols='30' ></textarea>"+
                                                    "<input type='number' style='display:none;' value='"+post.PostPublic.id+"' name='postId' /> "+
                                                "</div>"+
                                                "<div class='input-group' style='width:100%'>"+
                                                
                                                // "<button type='submit' class='btn btn-primary comment-post' style='float: right;' title='Comentar'><i class='fa fa-comments'></i> </button>"+   
                                              
                                              // "<button type='button' style='float: right; margin-top: 5px; display:none;' "+
                                                  
                                              //     "class='btn btn-primary copyLinkShare-"+post.Post.id+"'"+ 
                                              //     "data-toggle='tooltip' "+
                                              //     "title='Haciendo Click aqui podras compartir esta novedad en cualquiera de tus red sociales' "+
                                              //     "data-clipboard-text='"+post.Post.id+"' "+
                                              //     "data-placement='right'"+
                                              //   ">"+

                                              //     "  Compartir"+
                                              //   " </button>"+
                                            

                                              "<button type='button' style='float: right; margin-top: 5px; display:none;' "+
                                                  
                                                  "class='btn btn-primary copyLinkShare-"+post.PostPublic.id+"' "+
                                                   "data-post='"+JSON.stringify(post.PostPublic)+"'"+
                                                  "data-toggle='tooltip' "+
                                                  "title='Haciendo Click aqui podras compartir esta novedad en cualquiera de tus red sociales' "+
                                                  "data-clipboard-text='http://redkastella.com/publications/viewPublic/"+post.PostPublic.id+"' "+
                                                  "data-placement='right'"+
                                                ">"+

                                                  "  Compartir"+
                                                " </button>"+
      
                                                

                                              "<button type='button' style='float: right; margin-top: 5px;' data-id='"+post.PostPublic.id+"' "+
                                                  
                                                  "class='btn btn-primary copyLinkShare' >"+
                                                    
                                                  "<i class='fa fa-globe' aria-hidden='true'></i> "+

                                                  "<i class='fa fa-facebook' aria-hidden='true'></i> "+

                                                  "<i class='fa fa-twitter' aria-hidden='true'></i> ";


                                                   if(is.mobile()){

                                                     newContent += "<i class='fa fa-whatsapp' aria-hidden='true'></i> ";
                                                    

                                                   }


                                                 newContent += "  Compartir"+
                                                " </button>"+


    
                                                "</div>"+        
                                               "</form>"+ 
                                              "</li>"+
                                          //Fin Formulario de comentarios

                                          "</ul>";
                              
                             newContent +="</div>"+
                            "</div>"+
                        "</div>";


    if (withContGrid) {

        return "<div class='grid-item'>"+newContent+"</div>";

    }else{
        return newContent;        
    }


}



var sharedPostId = $('#sharedPostId').val();


console.log(sharedPostId);

if(sharedPostId != undefined){



  $.ajax({
      url: baseUrl+'Publications/getPostPublicById',
      type: 'post',
      dataType:'json',
      data: {postId:sharedPostId},
      success: function (data) {
        
        console.log("post data by id....");

        var post = getSinglePostCard(data, false);

        $('#mainPublication').html(post);

        $('.heading-main-pub').removeClass('no-display');

      }

    });
}








/**
 * función que formatea el contenido del post
 * @param  Object post información del post
 * @return String Contenido
 */
function postContent(post){


  var postContent =  urlify(post.content); 


  if (getNumberWords(post.content) > 35) {

      // <i class='fa fa-plus-circle'></i>
      
      return trimWords(postContent,35)+ "...<a href='#' data-post-id='"+post.id+"' style='text-decoration: underline;' class='show-all-post-content'> Ver mas</a>";

  }else{

    return postContent;

  }

}


/**
 * Variable que contendra el objeto que tiene el conetenido del post
 */
var postContentContainer;

$(document).on('click','.show-register-new-modal', function(e){

    e.preventDefault();

    $('.register-new-modal').modal('show');

});

$(document).on('click','.show-all-post-content',function(){


  $('.register-new-modal').modal('show');

   // postContentContainer =  $(this).parents('.post-content');


   //      /**
   //         * Obtenemos el post
   //         */
   //        getPostById({postId: $(this).data('post-id')}).done(function(response) {

   //            postContentContainer.html(urlify(response.Post.content) + " <a href='#' data-post-id='"+response.Post.id+"' style='text-decoration: underline;' class='show-less-post-content'> Ver Menos</a>");

   //            postsContainer.masonry();

   //        }).fail(function(x) {
   //            console.log(x);
   //        });

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
 * Funcionalidad de Me gusta
 */
$(document).on('click','.i-like',function(){


  $('.register-new-modal').modal('show');



});


/**
 * Función que obtiene todos los comentarios de un post
 */
$(document).on('click','.see-all-comments',function(){


  var commentForm = $(this).parents('.panel').find('.comment-form');   
  
  var currentSeeAllComments = $(this);

  var postId = $(this).data('post-id');  

  $.ajax({
          url: baseUrl+ 'Coments/getCommentsFromPostId/',
          type: 'POST',
          dataType: 'json',
          data: {postId: postId},
          success: function(response){

            /**
             * Removemos todos los comentarios
             */
            removeCurrentComments(currentSeeAllComments);

            /**
             * Obtenemos los comentarios
             * @type {String}
             */
            var newContent ="";
            $.each(response,function(){

               newContent += getFormattedComment(this.Coment);

            });

            /**
             * Insertamos todos los comentarios
             */
            $(newContent).insertBefore(commentForm);
    

            /**
             * Reorganizamos los posts
             */
            postsContainer.masonry();            

          },
          error: function(response){

            
          }
    });  
});

/**
 * Función que elimina todos los comentarios de un post, solo visualamente
 * @param  Object currentSeeAllComments selectot del cual se realiza la acción 
 */
function removeCurrentComments(currentSeeAllComments){

  currentSeeAllComments.parents('.panel').find('.user-comment').remove();

}

/**
 * Función que obtiene el html con la información de un comentario
 * @param  {Object} comment comentario
 * @return {String} comentario
 */
function getFormattedComment(comment){


    
    if (comment.Coment != undefined ) {

        var User = comment.User;
      
        comment = comment.Coment;
    }

    var newContent = "<li class='media user-comment'>"+
                        "<div class='media-left'>"+
                          "<a href=''>";

                          if (User != undefined) {

                                newContent += "<a href='' class='show-register-new-modal' ><div class='post-user-picture' style='float:left;background-image:url("+User.profilePic+")'></div></a>";
                          }else{


                                newContent += "<a href='' class='show-register-new-modal' ><div class='post-user-picture' style='float:left;background-image:url("+comment.profilePic+")'></div></a>";
                            
                          }

                                
                                                
                            //"<img src='"+comment.User.profilePic+"' class='media-object' style='width:50px; height:50px;'>"+
                          newContent += "</a>"+
                           "</div>"+
                            "<div class='media-body'>";
                              
                            // si el usuario actual es el dueño del post
                               
                            if (comment.users_id== userInfo.id) {

                            newContent += "<div class='pull-right dropdown' data-show-hover='li' >"+
                                "<a href='#' data-toggle='dropdown' class='toggle-button'>"+
                              "<i class='fa fa-pencil'></i>"+
                            "</a>";

                            
                             newContent += "<ul class='dropdown-menu' role='menu'>"+
                              
                              //funcion de editar
                              
                              "<li><a href='#' data-id='"+comment.id+"' class='edit-comment'>Editar</a></li>"+
                              
                                //Función de eliminar
                                "<li><a href='#' data-id='"+comment.id+"' class='delete-comment' >Eliminar</a></li>"+
                                
                                 "</ul>";
                             
                              newContent += "</div>";

                            }
                          //nombre de usuario
                          newContent += "<a href='"+baseUrl+ "users/profile/" +comment.username + "' class='comment-author pull-left'>"+comment.username+"</a>"+
                          
                          //comentario
                           "<span>"+comment.comment+"</span>"+
                          
                           //fecha del comentario
                          "<div class='comment-date'>"+moment(comment.modified).fromNow()+"</div>"+
                           "</div>"+
                      "</li>";

    return newContent;
}




/**
 * Formulario dinamico con el cual se editara un comentario
 */
var formEdit;

/**
 * item de comentario 
 */
var userComment;

/**
 * Función de editar un comentario
 */

$(document).on('click','.edit-comment',function(){


   var commentId = $(this).data('id');

   /**
    * Asignamos el item de comentario
    * @type Object
    */
  userComment = $(this).parents('.user-comment');


  $.ajax({
      url: baseUrl+'Coments/ajaxGetCommentById',
      type: 'post',
      dataType: 'json',
      data: {commentId:commentId},
      success: function (data) {
          
        /**
         * ocultamos el comentario
         */
        userComment.addClass('no-display');
        
        /**
         * Objeto que conetiene el formulario
         * @type Object
         */
        formEdit = $(getEditCommentForm(data.Coment));

        /**
         * insertamos el formulario encima del comentario, previamente escondido
         */
        formEdit.insertBefore(userComment);


      }
    });


   // $.ajax({
   //        url: baseUrl+ 'Coments/editComment/',
   //        type: 'POST',
   //        dataType: 'json',
   //        data: formData,
   //        success: function(response){

   //          console.log(response);
   //        },  
   //        error: function(response){

   //          console.log(response);
    
   //        }
   //  });


});


// save-edit-comment

$(document).on('submit','.edit-commet-form',function(e){


    /**
     * obtenemos el formulario actual
     * @type Object
     */
    var form = $(this);
    /**
     * Inicializamos el formateador de formularios
     */
    formatForm.formSelector = $(this);

    /**
     * Datos formateados del formulario
     */
    var formData = formatForm.format(); 
         
                
  $.ajax({
      url: baseUrl+'Coments/editComment',
      type: 'post',
      dataType: 'json',
      data: formData,
      success: function (response) {
  
      formEdit.remove();
      
      var editedComment = $(getFormattedComment(response.Comment));


      editedComment.insertBefore(userComment);

      userComment.remove();


      



      }
    });

  e.preventDefault();
});


function getEditCommentForm(comment){

  var newContent = 
  "<li class='comment-form'>"+
    "<form class='edit-commet-form' method='post'>"+
      "<div class='input-group'>"+
        "<span class='input-group-btn'>"+

        "<img src='"+comment.profilePic+"' class='circle' style='width:50px; height:50px;'></span>"+
        "<label for='Comment231ContentComment'></label>"+

        "<textarea name='comment' placeholder='Ingrese su comentario...' rows='2'  class='form-control' style='width:100%' cols='30' required>"+
        comment.comment
        +"</textarea>"+
        "<input type='number' style='display:none;' value='"+comment.id+"' name='commentId' /> "+
        "</div>"+
        "<div class='input-group' style='width:100%'>"+
        "<button type='submit' class='btn btn-primary' style='float: right;' title='Comentar'><i class='fa fa-comments'></i> </button>"+   

      "</div>"+        
    "</form>"+ 
  "</li>";

  return newContent;

}



/**
 * Objeto que contendra la información del comentario a eliminar
 * @type {Object}
 */
var deleteCommentInfo = {
  /**
   * Identificador del comentario
   * @type Int
   */
  commentId: undefined,
  /**
   * contenedor del comentario
   * @type Object
   */
  commentsContainer: undefined,
  /**
   * Contenedor del numero de comentarios
   * @type Object
   */
  numberOfComments: undefined  
};
    
$(document).on('click','.delete-comment',function(){

    /**
     * Asignacion del identificador del comentario
     * @type Int
     */
    deleteCommentInfo.commentId = $(this).data('id');
    
    /**
     * contenedor del comentario
     * @type Object
     */
    deleteCommentInfo.commentsContainer = $(this).parents('.user-comment');
    
    /**
     * Asignación del contenedor de numero de comentarios
     * @type Object
     */
    deleteCommentInfo.numberOfComments = $(this).parents('.panel').find('.number-of-comments');

    /**
     * Mostramos la modal de confirmación de eliminar comentario
     */
    $('#confirmDropCommentModal').modal('show');

});



/**
 * Acción de confirmación de eliminar comentario de la ventana modal
 */
$('#confirmDeleteComment').click(function(){

  /**
   * Llamamos la funcion que elimina un comentario
   */
  deleteComment(deleteCommentInfo);

});

/**
 * Función de eliminar comentario
 * @param  Object deleteCommentInfo Información del comentario a eliminar
 */
function deleteComment(deleteCommentInfo){

  $.ajax({
          url: baseUrl+ 'Coments/deleteComment/',
          type: 'POST',
          dataType: 'json',
          data: {commentId: deleteCommentInfo.commentId},
          success: function(response){
            
            /**
             * si fue exitoso
             */
            if (response.success) {

              /**
               * Borramos el comentario de la publicación
               */
              deleteCommentInfo.commentsContainer.remove();

              /**
               * escondemos la ventana modal de confirmación
               */
              $('#confirmDropCommentModal').modal('hide');

              /**
               * Actualizamos el número de comentarios
               */
              deleteCommentInfo.numberOfComments.html(response.numberComments);
              
              /**
               * Reorganizamos los items del grid de posts
               */
              postsContainer.masonry();
            }

          },
          error: function(response){

              /**
               * escondemos la ventana modal de confirmación
               */
               $('#confirmDropCommentModal').modal('hide');  

          }
    });

}

/**
 * Función que trabaja sobre el envio de comentarios
 */
$(document).on('submit','.commet-post-form',function(e) {

    /**
     * Detenemos el evío por defecto
     */
    e.preventDefault();

    $('.register-new-modal').modal('show');

});





/**
 * Función que trabaja sobre el envio de comentarios
 */
$(document).on('click','.menu-link',function(e) {

    /**
     * Detenemos el evío por defecto
     */
    e.preventDefault();

    $('.register-new-modal').modal('show');

});

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
     //            getPublications();

     // }


    if ($("#publications .grid-item:last") != undefined) {

       /**
        * llamamos a la función que obtiene las publicaciones
        */
        getPublications();

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
                  getPublications();

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
// getPublications();



$('.btn-add-publication').click(function(){


  $('.new-publication-modal').modal('show');


  $('.title-publication').focus();

});


function getUserLikesPost(data){


  return $.ajax({
      url: baseUrl + 'Likes/getUserLikesPost',
      type: 'post',
      dataType: 'json',
      data: data
    });


}


$(document).on('mouseover','.i-like',function(){

  var postId = $(this).data('id');

  var thisIlike = $(this);


  getUserLikesPost({ postId : postId }).done(function(response) {

    console.log(response);

    var currentUser = undefined;

    var usersHtml = "";

    for (var i = 0; i < response.length; i++) {
      
      currentUser = response[i]['User'];

      usersHtml += '<span class="link-user-likes" data-username ="'+currentUser.username+'" >' + currentUser.username + '</span><br/>';
      
    };


    if(response.length > 0){

        usersHtml += '<span class="post-link-user-likes" data-postid ="'+postId+'" >Ver todos</span><br/>';
     
    }
 

    thisIlike.tooltipster({
        
         content: $(usersHtml),
         interactive: true
    
    });

    thisIlike.tooltipster('show');


  })
  .fail(function(x) {
      console.log(x);
  });
      



});


  $(document).on('click', '.link-user-likes',function(){

      var username = $(this).data('username');

      window.location.href = baseUrl + '/users/profile/' + username;

  });    



  $(document).on('click', '.post-link-user-likes',function(){

      var postid = $(this).data('postid');

      window.location.href = baseUrl + 'Likes/seePeopleToPostLikes/' + postid;

  });    


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








