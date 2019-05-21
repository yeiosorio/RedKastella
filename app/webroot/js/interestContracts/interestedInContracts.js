
var numConstancia = $('#numConstancia').val();



/**
 * compartir
 */

$(document).on('click', '.copyLinkShare', function () {


    var id = $(this).data('id');

    var post = $(document).find('.copyLinkShare-' + id).data('post');

    console.log(post);

    var url = 'redkastella.com/InterestContracts/interestedInContracts/' + id;

    $('.share-on-fb-link').attr("href", "https://www.facebook.com/sharer/sharer.php?u=http%3A//" + url + "&title=Redkastella Novedades: " + post.title + "&description=" + post.contenido + "&picture=http://redkastella.com/resourcesFolder/1447080033251319885253906/1459956723848613023757935.jpg");
    $('.share-on-tw-link').attr("href", "https://twitter.com/home?status=Redkastella Novedades: " + post.title + " @KastellaApp http%3A//" + url);
    $('.share-on-wp-link').attr("href", "whatsapp://send?text=http://" + url);

    $('.justCopyLink').data("id", id);

    $('.share-modal').modal('show');


});

/**
 * Funcionalidad copiar al portapapeles
 */

var shareLinkClipboard = undefined;

// var linkToShare = "";


$(document).on('click', '.justCopyLink', function () {

    var selectorName = '.copyLinkShare-' + $(this).data('id');

    shareLinkClipboard = new Clipboard(selectorName);

    shareLinkClipboard.on('success', function (e) {

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









var shareLinkClipboard = new Clipboard('#copyLinkShare');

var linkToShare = "";

shareLinkClipboard.on('success', function (e) {

    linkToShare = e.text;

    $('.share-modal').modal('show');

});



/**
 * Usamos la función para obtener la información del ususario y cuando esta este lista ejecutamos los scripts con todas las funcionalidads
 */
getUserInfo().done(function (response) {

    if (response.success) {

        /**
         * Asignacion de la información del usuario
         * @type Object
         */
        userInfo = response.userInfo;

        /**
         * llamamos los scripts
         */
        publicationScripts();





    }

})
        .fail(function (x) {
            console.log(x);
        });



/**
 * Todos las funciones encargadas de las funcionalidades de las publicaciones
 * @return {[type]} [description]
 */
function publicationScripts() {





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
    $('.attachDocuments').click(function () {


        $('.attachedDocuments').click();

    });







    /**
     * Función que envia el formulario de nueva publicación con archivos via ajax
     */
    $('#publicationsForm').submit(function (e) {


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
            url: baseUrl + 'InterestContracts/addPublication/',
            type: 'POST',
            dataType: 'json',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {

                /**
                 * Reseteamos el formulario
                 */
                $("#publicationsForm")[0].reset();

                /**
                 * Agregamos el nuevo post al inicio de los posts
                 */
                putPost(response, 'start');


                animateButton.loading(false);

                $('.title-publication').focus();

            },
            error: function (response) {


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
    function getPublications() {

        /**
         * si se han pedido datos, sumamos 1 a la variable, de lo contrario le asignamos valor de 1 
         */
        if (timesScrolled != undefined) {

            timesScrolled = timesScrolled + 1;

        } else {

            timesScrolled = 1;
        }


        /**
         * si se han pedido datos, sumamos a la variable un valor definido, de lo contrario le asignamos valor de 0 
         */
        if (currentFromPosts != undefined) {

            currentFromPosts = currentFromPosts + 20;

        } else {
            currentFromPosts = 0;
        }

        console.log(timesScrolled + ' - ' + currentFromPosts);

        $.ajax({
            type: 'POST',
            data: {from: currentFromPosts},
            dataType: "json",
            url: baseUrl + "InterestContracts/getContractsInterested",
            success: function (response) {

                //Llamado a funcion para conocer cambios en contratos
                getInterestNotifications();

                /**
                 * Recorrido de los posts obtenidos
                 */
                $.each(response, function () {

                    /**
                     * Llamamos a la función que pone los datos en la presentación
                     */

                    putPost(this, 'end');

                });

                /**
                 * ponemos visible el botón de cargar mas posts quitando y agregando un par de clases
                 */
                $('#morePosts').removeClass('no-display');

                $('#morePosts').removeClass('display');

                animateButton.loading(false);

            },
            error: function (response) {
            }


        });

    }


//Funcion para buscar todos los contratos de interes
    function getInterestNotifications() {

        $.ajax({
            type: 'POST',
            dataType: "json",
            url: baseUrl + "InterestContracts/getContractsNotifications",
            success: function (response) {
                    console.log('---------------------------');
                    getStateContract(response)

            },
        });

    }

    //Funcion encargada de consumir la API de SECOP https://www.datos.gov.co/resource/2ftq-vnhb.json
    function getStateContract(dataInterest) {
        
        // jQuery.each(dataInterest, function (index, val) {

        //     $.ajax({
        //         type: "GET",
        //         url: "https://www.datos.gov.co/resource/2ftq-vnhb.json?numero_de_constancia=" + val.InterestContracts.num_constancia,
        //         data: {
        //             "$$app_token": "CdMN0BeIXp9QXG5zpGiSe4EQD",
        //         },
        //         success: function (response) {

        //             console.log('contratos -------------------------------------------');
        //             console.log(response);
        //         }});
        // });

        jQuery.each(dataInterest, function (index, val) {
                $.get(
                    "https://api.colombiacompra.gov.co/releases/?num_constancia="+ val.InterestContracts.num_constancia,
                    function( success, status, xhr ){
                       
                       if( xhr.getResponseHeader('Content-Type') == 'application/json' ){

                            $.get(
                                "https://api.colombiacompra.gov.co/packagemetadata/?ocid="+success.releases[0].ocid,
                                function( success, status, xhr ){
                                    $('#'+success.releases[0].num_constancia ).prepend( "<b>actualizado</b>" );
                                }
                            );

                       }
                        
                    }
                );
            }
        );

    }





    /**
     * Función que obtiene un post por su identificador
     */
    function getPostById(data) {

        return $.ajax({
            type: 'POST',
            data: data,
            dataType: "json",
            url: baseUrl + "InterestContracts/getPostById"
        });

    }






    /**
     * Botón de mostrar mas posts
     */
    $('#morePosts').click(function () {

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
    var dataDrop = {
        postId: undefined,
        gridItem: undefined
    };

    /**
     * Función que prepara un post para ser eliminado
     */

    $(document).on('click', '.drop-post', function () {

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

    $(document).on('click', '.edit-post', function () {

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
        currentEditPost.find('.post_thumbnail').addClass('no-display');

        currentEditPost.find('.panel-body').addClass('no-display');



        /**
         * Obtenemos el post
         */
        getPostById({postId: postId}).done(function (response) {

            var post = response;

            $(getEditForm(post)).insertAfter(currentEditPost.find('.panel-body'));

        }).fail(function (x) {
            console.log(x);
        });



    });


//Objeto que anima un contenedor con unas clases definidas
    var formatForm = {
        formSelector: undefined,
        /**
         * Función de animación de cargar
         */
        format: function () {


            var dataArray = this.formSelector.serializeArray(),
                    dataObj = {};

            $(dataArray).each(function (i, field) {
                dataObj[field.name] = field.value;
            });


            return dataObj;

        }
    }



    /**
     * Función que envia el formulario de nueva publicación con archivos via ajax
     */
    $(document).on('submit', '.publicationEditForm', function (e) {


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
            url: baseUrl + 'InterestContracts/editPost/',
            type: 'POST',
            dataType: 'json',
            data: formData,
            success: function (response) {

                /**
                 * Si se guardo con exito establecemos los nuevos datos
                 */
                if (response.success == true) {

                    currentEditPost.html(getPostCard(response.post, false));

                    /**
                     * Reorganizamos el grid
                     */
                    postsContainer.masonry();

                }

                /**
                 * reestablecemos la animación del boton 
                 */
                animateButton.loading(false);

            },
            error: function (response) {

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
    function getEditForm(post) {

        /**
         * cargamos las opciones de visibilidad
         * @type {String}
         */


        var privaciesOptions = "";

        $.each(privacies, function () {

            /**
             * configuración de la opcion del post
             */
            if (this['Privacy'].id == post.Privacy.id) {

                privaciesOptions += "<option value='" + this['Privacy'].id + "' selected>" + this['Privacy'].title + "</option>";

            } else {

                privaciesOptions += "<option value='" + this['Privacy'].id + "'>" + this['Privacy'].title + "</option>";
            }


        });

        /**
         * Variable que contiene el formulario
         * @type {String}
         */
        var form = "<form method='post' class='publicationEditForm'>" +
                //  Titulo 
                "<input name='title' value='" + post.Post.title + "' type='text' placeholder='Ingrese un título para su publicación' class='form-control' style='background-color:white;' maxlength='100' required/>" +
                //  Contenido 
                "<textarea name='content' placeholder='Ingrese su texto...' rows='5' class='form-control' style='background-color:white;' maxlength='1000' required>" + post.Post.content + "</textarea>" +
                //  Sección de botones 
                "<div class='panel-body buttons-spacing-vertical'>" +
                "<p>" +
                //  Botón de seleccione de visibilidad 
                "<select name='privacies_id' class='btn btn-primary dropdown-toggle privacies custom-dropdown-height' data-toggle='dropdown'>" + privaciesOptions +
                "</select>" +
                /**
                 * Agregamos la identificación del post en este campo
                 * @type Int
                 */
                "<input type='numer' value='" + post.Post.id + "' name='id' style='display:none;' /> " +
                //  Enviar 
                "<button type='submit' class='btn btn-primary float-right' ><i class='fa fa-cloud-upload btn-edit-publish-gls'></i> Publicar</button>" +
                "</p>" +
                "</div>" +
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
    $('#confirmDelete').click(function () {

        $('#confrimDropPostModal').modal('hide');

        /**
         * eliminamos el post
         */
        deletePost();

    });


    /**
     * Función que elimina un post
     */
    function deletePost() {

        //usamos el objeto dataDrop para obtenemos los datos del post que se eliminara

        $.ajax({
            type: 'POST',
            data: {postId: dataDrop.postId},
            dataType: "json",
            url: baseUrl + "InterestContracts/deletePost",
            success: function (response) {
                /**
                 * Si se borro correctamente
                 */
                if (response.success == true) {

                    /**
                     * Removemos el post y Reorganizamos todos los posts
                     */
                    postsContainer.masonry('remove', dataDrop.gridItem).masonry('layout');

                } else {
                    console.log(response);
                }
            },
            error: function (response) {
                console.log(response);
            }

        });
    }


    /**
     * Función que obtiene un post
     * @param  Object   post  pos con toda su información
     * @param  Boolean  withContGrid Variable que determina si se devolvera el post con o sin contenedor
     * @return String   post
     */
    function getPostCard(post, withContGrid) {

        console.log( 'POST' );
        console.log( post );


        var newContent = "<div class='col-xs-12 col-md-12 col-lg-12 item' >" +
                "<div class='timeline-block'>" +
                "<div class='panel panel-default'>" +
                // <!-- Contenedor de Encabezado -->
                "<div class='panel-heading'>" +
                "<div class='media'>" +
                "<div class='media-left'>" +
                "<a href=''>" +
                "<img src='" + baseUrl + "img/logo-kastella.jpg' style='width:50px; height:50px;' class='media-object'>" +
                "</a>" +
                "</div>" +
                // <!-- Cabezera -->
                "<div class='media-body'>" +
                // <!-- Edición - eliminación -->
                "<a href='#' class='pull-right text-muted'>";

        // si el usuario actual es el dueño del post
        //  if (post.User.id == userInfo.id) {

        // Configuración del elemento de editar post
        //   newContent += "<i class='fa fa-fw fa-edit edit-post' data-post-id='"+post.Post.id+"'></i>"+


        //Configuración del elemento de eliminar Post 
        // "<i class='fa fa-fw fa-remove drop-post' data-post-id='"+post.Post.id+"'></i>";

        // }


        // "<i class='icon-reply-all-fill fa fa-2x '></i>"+
        newContent += "</a>" +
                // <!-- Fin Edición - eliminación -->

                "<a href=''>Tal vez te pueda interesar...</a>" +
                //fecha amigable formateada de la publicación
                // "<span>"+moment(post.Post.modified).fromNow()+"</span>"+
                "</div>" +
                // <!-- Fin Cabecera -->

                "</div>" +
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

        //     newContent += "<div class='post_thumbnail' style='background-image:url("+postThumbnail+");'></div>";
        // }

        // <!-- Contenido -->
        newContent += "<div class='panel-body' id='"+post.InterestContracts.num_constancia+"' >" +
                "<p>" +
                // "<b class='h4 margin-none'>"+post.Post.title+"</b><br/><div class='post-content'>"+

                fillCategoryContract(post);

        // postContent(post.Post);

        newContent += "</div><br /> <br />";

        /**
         * Archivos Adjuntos
         * Link, nombre y peso del archivo
         */

        // $.each(post.Resource,function(){

        //     newContent += "<a href='"+this.filePath+"' target='_blank'>"+ this.fileName + "</a> <small>("+this.size_format+")</small> <br />";

        // });


        //  Fin archivos adjuntos


        //    newContent +="</p>"+
        // "</div>";
        // <!-- Fin Contenido -->


        //Sección número de comentarios y Me gusta
        // newContent += "<div class='view-all-comments'>"+
        //   "<a href='#' class='see-all-comments' data-post-id='"+post.Post.id+"'>"+
        //     "<i class='fa fa-comments-o'></i> Ver Todos "+
        //   "</a>"+
        //   "<span><span class='number-of-comments'>"+post.PostComment.length+"</span> comentarios</span>"+
        //   "<div class='float-right likes-container'>"+

        //     //i-like clase que identificara la funcionalidad de Me guta

        //     //data-id identificador del post

        //     //data-i-like define si al usuario actualmente le gusta o no el post 

        //     "<a href='#' class='i-like' data-id='"+post.Post.id+"' data-i-like='"+post.Post.ilike+"' >"+

        //       //number-likes clase que define el contenedor del número de likes con un mensaje
        //       "<i class='fa fa-thumbs-up'></i> <span class='number-likes'>"+post.Post.likes;

        //       /**
        //        * si al usuario le gusta el post, escribimos:
        //        */
        //       if (post.Post.ilike != 0) {

        //         newContent +=" Ya no me gusta";  

        //       }else{

        //         newContent +=" Me gusta";
        //       }


        //     newContent +="</span></a></div>"+

        // "</div>";
        // // Fin sección número de comentarios y Me gusta


        // Inicio de comentarios       
        //newContent += "<ul class='comments comments-container'>";

        // if (post.PostComment.length) {

        //   var moreThan;
        //     //si hay mas de cinco comentarios
        //    if (post.PostComment.length > 4 ) {

        //       moreThan = 5;

        //     }


        //     var currentCommentCount = 0;  


        //      // Recorrido por los comentarios del post

        //     $.each(post.PostComment,function(){


        //          // Si no se ha superado el limite definido 

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

        //Fin Comentarios


        // Inicio Formulario de comentarios
        //   newContent += "<li class='comment-form'>"+

        //       "<form class='commet-post-form' method='post'>"+
        //       "<div class='input-group'>"+
        //       "<span class='input-group-btn'>"+

        //           "<img src='/kastella/img/avatar/avatar.jpg' class='circle' style='width:50px; height:50px;'></span>"+
        //           "<label for='Comment231ContentComment'></label>"+

        //           "<textarea name='comment' placeholder='Ingrese su comentario...' rows='2'  class='form-control comment' style='width:100%' cols='30' required></textarea>"+
        //           "<input type='number' style='display:none;' value='"+post.Post.id+"' name='postId' /> "+
        //       "</div>"+
        //       "<div class='input-group' style='width:100%'>"+
        //       "<button type='submit' class='btn btn-primary comment-post' style='float: right;' title='Comentar'><i class='fa fa-comments'></i> </button>"+   


        //       "</div>"+        
        //      "</form>"+ 
        //     "</li>"+
        // //Fin Formulario de comentarios

        //"</ul>";

        newContent += "</div>" +
                "</div>" +
                "</div>";


        if (withContGrid) {

            return "<div class='grid-item'>" + newContent + "</div>";

        } else {
            return newContent;
        }



    }



    /**
     * función que formatea el contenido del post
     * @param  Object post información del post
     * @return String Contenido
     */
    function postContent(post) {



        if (getNumberWords(post.content) > 35) {

            // <i class='fa fa-plus-circle'></i>

            return trimWords(post.content, 35) + "...<a href='#' data-post-id='" + post.id + "' class='show-all-post-content'> Ver mas</a>";

        } else {

            return post.content;

        }

    }


    /**
     * Variable que contendra el objeto que tiene el conetenido del post
     */
    var postContentContainer;


    $(document).on('click', '.show-all-post-content', function () {

        postContentContainer = $(this).parents('.post-content');

        /**
         * Obtenemos el post
         */
        getPostById({postId: $(this).data('post-id')}).done(function (response) {

            postContentContainer.html(response.Post.content + "...<a href='#' data-post-id='" + response.Post.id + "' class='show-less-post-content'> Ver Menos</a>");

            postsContainer.masonry();

        }).fail(function (x) {
            console.log(x);
        });

    });



    $(document).on('click', '.show-less-post-content', function () {


        postContentContainer = $(this).parents('.post-content');


        /**
         * Obtenemos el post
         */
        getPostById({postId: $(this).data('post-id')}).done(function (response) {

            postContentContainer.html(postContent(response.Post));

            postsContainer.masonry();

        }).fail(function (x) {
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

        expString = theString.split(/\s+/, numWords);
        theNewString = expString.join(" ");
        return theNewString;
    }



    /**
     * Función que obtiene el número de palabras de un string
     * @return Int número de palabras
     */
    function getNumberWords(theString) {


        return theString.split(/\s+/).length;

    }

    /**
     * Funcionalidad de Me gusta
     */
    $(document).on('click', '.i-like', function () {

        /**
         * Este elementos
         * @type Object
         */
        var thisIlike = $(this);

        /**
         * Identificador del post
         * @type Int
         */
        var postId = $(this).data('id');

        /**
         * contenedor que muestra el numero de likes
         * @type {[type]}
         */
        var numberLikes = $(this).parents('.likes-container').find('.number-likes');


        /**
         * llamamos la función de likes y mandamos el identificador del post y le decimos que pertenecera a la entidad posts
         */
        like({to: 'posts', id: postId}).done(function (response) {

            /**
             * Si se ha hecho el like correctamente
             */
            if (response.success) {

                /**
                 * Obtenemos el post
                 */
                getPostById({postId: postId}).done(function (response) {

                    /**
                     * Asignamos los likes actuales y cambiamos el atributo data del estado de like del usuario que define si le gusta o ya no le gusta
                     */

                    if (thisIlike.data('i-like')) {

                        /**
                         * Cambiamos el atributo a cero
                         */
                        thisIlike.data('i-like', 0);

                        /**
                         * Escribimos el mensaje con el número de likes
                         */
                        numberLikes.html(response.Post.likes + " Me gusta");


                    } else {

                        /**
                         * Cambiamos el atributo a uno
                         */
                        thisIlike.data('i-like', 1);


                        /**
                         * Escribimos el mensaje con el número de likes
                         */
                        numberLikes.html(response.Post.likes + " Ya no me gusta");

                        var objLike = {
                            id: 1,
                            object: response.Post,
                            username: userInfo.username
                        };

                        // console.log(response);

                        //mandamos el evento de like
                        doLike(objLike);
                    }


                }).fail(function (x) {
                    console.log(x);
                });
            }

        }).fail(function (x) {
            console.log(x);
        });



    });


    /**
     * Función que obtiene todos los comentarios de un post
     */
    $(document).on('click', '.see-all-comments', function () {


        var commentForm = $(this).parents('.panel').find('.comment-form');

        var currentSeeAllComments = $(this);

        var postId = $(this).data('post-id');

        $.ajax({
            url: baseUrl + 'Coments/getCommentsFromPostId/',
            type: 'POST',
            dataType: 'json',
            data: {postId: postId},
            success: function (response) {

                /**
                 * Removemos todos los comentarios
                 */
                removeCurrentComments(currentSeeAllComments);

                /**
                 * Obtenemos los comentarios
                 * @type {String}
                 */
                var newContent = "";
                $.each(response, function () {

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
            error: function (response) {


            }
        });
    });

    /**
     * Función que elimina todos los comentarios de un post, solo visualamente
     * @param  Object currentSeeAllComments selectot del cual se realiza la acción 
     */
    function removeCurrentComments(currentSeeAllComments) {

        currentSeeAllComments.parents('.panel').find('.user-comment').remove();

    }

    /**
     * Función que obtiene el html con la información de un comentario
     * @param  {Object} comment comentario
     * @return {String} comentario
     */
    function getFormattedComment(comment) {

        console.log(comment);

        if (comment.Coment != undefined) {

            var User = comment.User;

            comment = comment.Coment;
        }

        var newContent = "<li class='media user-comment'>" +
                "<div class='media-left'>" +
                "<a href=''>";

        if (User != undefined) {

            newContent += "<div class='post-user-picture' style='float:left;background-image:url(" + User.profilePic + ")'></div>";
        } else {
            newContent += "<div class='post-user-picture' style='float:left;background-image:url(" + comment.User.profilePic + ")'></div>";

        }



        //"<img src='"+comment.User.profilePic+"' class='media-object' style='width:50px; height:50px;'>"+
        newContent += "</a>" +
                "</div>" +
                "<div class='media-body'>";

        // si el usuario actual es el dueño del post

        if (comment.users_id == userInfo.id) {

            newContent += "<div class='pull-right dropdown' data-show-hover='li' style='display: none;'>" +
                    "<a href='#' data-toggle='dropdown' class='toggle-button'>" +
                    "<i class='fa fa-pencil'></i>" +
                    "</a>";


            newContent += "<ul class='dropdown-menu' role='menu'>" +
                    //funcion de editar

                    "<li><a href='#' data-id='" + comment.id + "' class='edit-comment'>Editar</a></li>" +
                    //Función de eliminar
                    "<li><a href='#' data-id='" + comment.id + "' class='delete-comment' >Eliminar</a></li>" +
                    "</ul>";

            newContent += "</div>";

        }
        //nombre de usuario
        newContent += "<a href='' class='comment-author pull-left'>" + comment.username + "</a>" +
                //comentario
                "<span>" + comment.comment + "</span>" +
                //fecha del comentario
                "<div class='comment-date'>" + moment(comment.modified).fromNow() + "</div>" +
                "</div>" +
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

    $(document).on('click', '.edit-comment', function () {


        var commentId = $(this).data('id');

        /**
         * Asignamos el item de comentario
         * @type Object
         */
        userComment = $(this).parents('.user-comment');


        $.ajax({
            url: baseUrl + 'Coments/ajaxGetCommentById',
            type: 'post',
            dataType: 'json',
            data: {commentId: commentId},
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

    $(document).on('submit', '.edit-commet-form', function (e) {


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
            url: baseUrl + 'Coments/editComment',
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


    function getEditCommentForm(comment) {

        var newContent =
                "<li class='comment-form'>" +
                "<form class='edit-commet-form' method='post'>" +
                "<div class='input-group'>" +
                "<span class='input-group-btn'>" +
                "<img src='/kastella/img/avatar/avatar.jpg' class='circle' style='width:50px; height:50px;'></span>" +
                "<label for='Comment231ContentComment'></label>" +
                "<textarea name='comment' placeholder='Ingrese su comentario...' rows='2'  class='form-control' style='width:100%' cols='30' required>" +
                comment.comment
                + "</textarea>" +
                "<input type='number' style='display:none;' value='" + comment.id + "' name='commentId' /> " +
                "</div>" +
                "<div class='input-group' style='width:100%'>" +
                "<button type='submit' class='btn btn-primary' style='float: right;' title='Comentar'><i class='fa fa-comments'></i> </button>" +
                "</div>" +
                "</form>" +
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

    $(document).on('click', '.delete-comment', function () {

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
    $('#confirmDeleteComment').click(function () {

        /**
         * Llamamos la funcion que elimina un comentario
         */
        deleteComment(deleteCommentInfo);

    });

    /**
     * Función de eliminar comentario
     * @param  Object deleteCommentInfo Información del comentario a eliminar
     */
    function deleteComment(deleteCommentInfo) {

        $.ajax({
            url: baseUrl + 'Coments/deleteComment/',
            type: 'POST',
            dataType: 'json',
            data: {commentId: deleteCommentInfo.commentId},
            success: function (response) {

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
            error: function (response) {

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
    $(document).on('submit', '.commet-post-form', function (e) {

        /**
         * Detenemos el evío por defecto
         */
        e.preventDefault();


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

        /**
         * ultimo comentario del post
         */
        var commentsContainer = $(this).parents('.comments-container').find('.user-comment').last();

        /**
         * formulario de comentarios del post
         */
        var commentFormContainer = $(this).parents('.comments-container').find('.comment-form');

        /**
         * contenedor del numero de comentarios
         */
        var numberOfComments = $(this).parents('.panel').find('.number-of-comments');


        $.ajax({
            url: baseUrl + 'Coments/addComment/',
            type: 'POST',
            dataType: 'json',
            data: formData,
            success: function (response) {

                /**
                 * Si se agrego correctamente
                 */
                if (response.success) {

                    //reseteamos el formulario                
                    form[0].reset();

                    /**
                     * ponemos el foco en el fomulario de comentarios
                     */
                    form.find('.comment').focus();

                    /**
                     * Si no hay comentarios en el post
                     */
                    if (commentsContainer.length == 0) {

                        /**
                         * Agregamos antes del formulario de comentarios
                         */
                        $(getFormattedComment(response.Comment)).insertBefore(commentFormContainer);

                    } else {

                        /**
                         * Agregamos despues del ultimo comentario
                         */
                        $(getFormattedComment(response.Comment)).insertAfter(commentsContainer);

                    }

                    /**
                     * Actualizamos el número de comentarios
                     */
                    numberOfComments.html(response.numberComments);


                    /**
                     * Reorganizamos el grid 
                     */
                    postsContainer.masonry();





                }

            },
            error: function (response) {

                console.log(response);

            }
        });

    });


    /**
     * Funcion que toma datos de un post y los inserta en el DOM
     * @param  Object post Datos del post
     * @param  Boolean endStart 'start' si es al principio, 'end' si va al final
     */
    function putPost(post, endStart) {
        

        console.log( "putPost()" );

        /**
         * Contenido del Post en formato html
         * @type {String}
         */
        var newContent = getPostCard(post, true);

        /**
         * Variable que contiene el contenido nuevo como entidad html
         */
        var $newContentObject = $(newContent);

        /**
         * se agrega el objeto de contenido al contenedor principal previamente configurado
         */
        switch (endStart) {

            case 'end':
                postsContainer.append($newContentObject).masonry('appended', $newContentObject);
                break;

            case 'start':
                postsContainer.prepend($newContentObject).masonry('prepended', $newContentObject);
                break;
        }

    }


    /**
     * Función que obtiene la imagen destaca de un post
     * @param  Object post post
     * @return String url de la imagen
     */
    function getThumbnail(post) {

        /**
         * extensiones de imagenes
         * @type {Array}
         */
        var extensions = ['png', 'jpg'];

        /**
         * Variable que contendra la url de la imagen
         */
        var filePath;


        /**
         * recorrido para buscar en los recursos
         */
        $.each(post.Resource, function () {

            /**
             * si hay una imagen
             */
            if ($.inArray(this.ResourceExtension.extension, extensions) != -1) {

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
    function masonryEffect() {

// función que acciona acopla los elementos 
        $('#publications').masonry({
            /**
             * clase que tiene cada item del grid 
             * @type {String}   
             */
            itemSelector: '.grid-item',
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
    function scrollableContent() {

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

        $('.scrollable_content').scroll(function (e) {

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
            }
            ;
        });


    }

    /**
     * Función que quita el scrol del contenedor de los posts
     * @return {[type]} [description]
     */
    function unBindScrollableContent() {

        $('.scrollable_content').unbind('scroll');

    }




    /**
     * Función que muestra los datos de un resultado 
     * @param  {object} item objeto que contiene un resultado
     */
    function fillCategoryContract(item) {

        console.log("ver ... ");

        console.log(item);

        var post = item.InterestContract;

        if (post == undefined) {

            post = item.InterestContracts;

        }


        var contentResult = "";
        contentResult += "<h4><a href='" + post.link + "' target='_blank'>" + post.title + "</a></h4>";
        contentResult += "<h5>" + post.nombre + "</h5>";
        contentResult += "<p>" + post.contenido + "</p>";
        contentResult += "<p><strong>Valor estimado:</strong> " + accounting.formatMoney(post.valor) + "</p>";
        contentResult += "<p><strong>Ciudad:</strong> " + post.ciudad + "</p>";
        contentResult += "<p><strong>Departamento:</strong> " + post.departamento + "</p>";
        contentResult += "<p><strong>Email:</strong> " + post.author + "</p>";
        // contentResult += "<p><strong>Link:</strong> <a href='"+post.link+"' target='_blank'>aqu&iacute;</a></p>";

        /**
         * Si es el mismo numero de constancia
         */
        if (post.num_constancia == numConstancia) {


            contentResult += "<p style='float: left;'><button type='button' class='btn btn-primary interested-contract-add-public' data-contract='" + JSON.stringify(post) + "' ><i class='fa fa-thumbs-up'></i> Me Interesa</button></p>"

        } else {

            contentResult += "<p style='float: left;' class='interested-container'><button type='button' class='btn btn-primary interested-contract-del' data-contract='" + JSON.stringify(post) + "' ><i class='fa fa-thumbs-up'></i> Ya No Me Interesa</button></p>";

        }

        contentResult += "<button type='button' style='float: left; margin-top: 5px; display:none;' " +
                "class='btn btn-primary copyLinkShare-" + post.num_constancia + "' " +
                "data-post='" + JSON.stringify(post) + "'" +
                "data-toggle='tooltip' " +
                "title='Haciendo Click aqui podras compartir esta novedad en cualquiera de tus red sociales' " +
                "data-clipboard-text='http://redkastella.com/InterestContracts/interestedInContracts/" + post.num_constancia + "' " +
                "data-placement='right'" +
                ">" +
                "  Compartir" +
                " </button>" +
                "<button type='button' style='margin-left: 5px;' data-id='" + post.num_constancia + "' " +
                "class='btn btn-primary copyLinkShare' >" +
                "<i class='fa fa-globe' aria-hidden='true'></i> " +
                "<i class='fa fa-facebook' aria-hidden='true'></i> " +
                "<i class='fa fa-twitter' aria-hidden='true'></i> Compartir";


        if (is.mobile()) {

            contentResult += "<i class='fa fa-whatsapp' aria-hidden='true'></i> ";


        }

        contentResult += "</button> ";


        return contentResult;

    }



    /**
     * Busca 
     */
    var interestedContainer;

    $(document).on('click', '.interested-contract-add', function () {


        interestedContainer = $(this).parents('.interested-container');

        $(this).prop("disabled", true);

        var data = $(this).data('contract');

        data.valor = data.valor.replace(/,/g, '');

        var numConstancia = getUrlVars(data.link)["numConstancia"];

        data.num_constancia = numConstancia;

        $.ajax({
            url: baseUrl + 'InterestContracts/saveInterestContract',
            type: 'post',
            dataType: 'json',
            data: {interest: data},
            success: function (response) {

                interestedContainer.html("<button type='button' class='btn btn-primary interested-contract-del' data-contract='" + JSON.stringify(response.contract) + "' ><i class='fa fa-thumbs-up'></i> Ya No Me Interesa</button></p>");

            }
        });

    });



    function deleteInterestContract(data) {

        return  $.ajax({
            url: baseUrl + 'InterestContracts/delInterestContract',
            type: 'post',
            dataType: 'json',
            data: {interest: data}

        });

    }


    $(document).on('click', '.interested-contract-del', function () {

        var data = $(this).data('contract');

        interestedContainer = $(this).parents('.interested-container');

        $(this).prop("disabled", true);

        data.valor = data.valor.replace(/,/g, '');

        var numConstancia = getUrlVars(data.link)["numConstancia"];

        data.num_constancia = numConstancia;

        deleteInterestContract(data).done(function (response) {

            console.log(response);

            interestedContainer.html("<button type='button' class='btn btn-primary interested-contract-add' data-contract='" + JSON.stringify(data) + "' ><i class='fa fa-thumbs-up'></i> Me Interesa</button></p>");

            // if (response.success) {

            //  }

        })
                .fail(function (x) {
                    console.log(x);
                });


    });



    /**
     * Inicialización del scroll sobre el contenedor de las publicaciones
     */
    scrollableContent();

//Función que obtiene las prublicaciones
//getPublications();







    /**
     * Función que obtiene un post
     * @param  Object   post  pos con toda su información
     * @param  Boolean  withContGrid Variable que determina si se devolvera el post con o sin contenedor
     * @return String   post
     */
    function getSinglePostCard(post, withContGrid) {




        var newContent = "<div class='col-xs-12 col-md-12 col-lg-12 item' >" +
                "<div class='timeline-block'>" +
                "<div class='panel panel-default'>" +
                // <!-- Contenedor de Encabezado -->
                "<div class='panel-heading'>" +
                "<div class='media'>" +
                "<div class='media-left'>" +
                "<a href=''>" +
                "<img src='" + baseUrl + "img/logo-kastella.jpg' style='width:50px; height:50px;' class='media-object'>" +
                "</a>" +
                "</div>" +
                // <!-- Cabezera -->
                "<div class='media-body'>" +
                // <!-- Edición - eliminación -->
                "<a href='#' class='pull-right text-muted'>";



        // "<i class='icon-reply-all-fill fa fa-2x '></i>"+
        newContent += "</a>" +
                // <!-- Fin Edición - eliminación -->

                "<a href=''>Tal vez te pueda interesar...</a>" +
                //fecha amigable formateada de la publicación
                // "<span>"+moment(post.Post.modified).fromNow()+"</span>"+
                "</div>" +
                // <!-- Fin Cabecera -->

                "</div>" +
                "</div>";
        // <!-- FIn contenedor de encabezado -->



        // <!-- Contenido -->
        newContent += "<div class='panel-body'>" +
                "<p>" +
                // "<b class='h4 margin-none'>"+post.Post.title+"</b><br/><div class='post-content'>"+

                fillCategoryContract(post);

        // postContent(post.Post);

        newContent += "</div><br /> <br />";



        newContent += "</div>" +
                "</div>" +
                "</div>";


        if (withContGrid) {

            return "<div class='grid-item'>" + newContent + "</div>";

        } else {
            return newContent;
        }



    }











    console.log(numConstancia);

    if (numConstancia != undefined) {

        $.ajax({
            url: baseUrl + 'InterestContracts/getContractByNumConstancia',
            type: 'post',
            dataType: 'json',
            data: {numConstancia: numConstancia},
            success: function (data) {

                console.log("ver... ");

                console.log(data);

                // console.log("post data by id....");

                var post = getSinglePostCard(data, false);


                $('#mainPublication').html(post);

                $('.heading-main-pub').removeClass('no-display');

            }

        });
    }




}



// $.ajax({
//     url: baseUrl + 'xmlreader/getByNumCons',
//     type: 'post',
//     success: function (data) {

//       console.log(data);

//     }
//   });









