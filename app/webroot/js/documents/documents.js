

/************** ISOTOPE E INFINITE SCROLL DOCUMENTOS ****************/
var $container_doc = $('#todos_mis_documentos');

$container_doc.isotope({
    itemSelector: '.item'
});

$('#insert a').click(function () {
    var $newEls = $(fakeElement.getGroup());
    $container_doc.isotope('insert', $newEls);

    return false;
});

$('#append a').click(function () {
    var $newEls = $(fakeElement.getGroup());
    $container_doc.append($newEls).isotope('appended', $newEls);

    return false;
});


$('#prepend a').click(function () {
    var $newEls = $(fakeElement.getGroup());
    $container_doc
            .prepend($newEls).isotope('reloadItems').isotope({sortBy: 'original-order'})
            // set sort back to symbol for inserting
            .isotope('option', {sortBy: 'symbol'});
    return false;
});

$('#removable a').click(function (jQEvent) {
    var selector = $(this).attr('data-option-value');
    var $removable = $container_doc.find(selector);
    $container_doc.isotope('remove', $removable);
    jQEvent.preventDefault();
});

$('#add-remove a').click(function (jQEvent) {
    var $newEls = $(fakeElement.getGroup());
    var $firstTwoElems = $container_doc.data('isotope')
            .$filteredAtoms.filter(function (i) {
                return i < 2;
            });

    $container_doc
            .isotope('insert', $newEls)
            .isotope('remove', $firstTwoElems, function () {
                // console.log('items removed')
            });
    jQEvent.preventDefault();
});





/**
 * Editar documento
 * @param  Int my_id identificador del documento
 */
function edit_doc(my_id)
{

    $.post(baseUrl + "Documents/pre_edit/" + my_id, function (data) {

        // Ponemos la respuesta de nuestro script en el td que corresponde al titulo de la publicacion
        $('#document_box' + my_id).html(data);
        $('#todos_mis_documentos').isotope('reloadItems').isotope({sortBy: 'original-order'});
    });
}



$("#DocumentIndexForm").submit(
        function () {
            // esta funcion recuperara los archivos seleccionados y los agrupara en un arreglo del tipo FormData(), este objeto permite ordenar un arreglo de datos con el formato valor,dato y serializarlo para después pasarlo por método POST.
            var archivos = document.getElementById("DocumentDocuments");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'Documents'
            var archivo = archivos.files; //Obtenemos los archivos seleccionados en el input
            //Creamos una instancia del Objeto FormDara.
            var archivos = new FormData();
            /* Como son multiples archivos creamos un ciclo +que recorra la el arreglo de los archivos seleccionados en el input  y añadimos cada elemento al formulario FormData en forma de arreglo, utilizando la variable i (autoincremental) como indice para cada archivo, si no hacemos esto, los valores del arreglo se sobre escriben*/
            extensiones_permitidas = new Array('.pdf', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.jpg', '.bmp', '.gif', '.jpeg', '.png');
            mssg_js = "";
            permitida = false;
            error = false;

            //archivos.append('username',"Kieigi");
            archivos.append('user_id', $('#DocumentUserId').val());
            archivos.append('title_document', $('#DocumentTitleDocument').val());
            archivos.append('content_document', $('#DocumentContentDocument').val());
            archivos.append('link_secop', $('#DocumentLinkSecop').val());
            archivos.append('privacy_id', $('#DocumentPrivacyType').val());
            k = 0;
            for (i = 0; i < archivo.length; i++) {
                extension = (archivo[i].name.substring(archivo[i].name.lastIndexOf("."))).toLowerCase();
                permitida = false;
                for (j = 0; j < extensiones_permitidas.length; j++)
                {
                    if (extensiones_permitidas[j] == extension)
                    {
                        permitida = true;
                        j = extensiones_permitidas.length;
                    }
                }
                if (permitida)
                {
                    archivos.append('archivo' + k, archivo[i]); //Añadimos cada archivo a el arreglo con un indice direfente
                    k++;
                    if (!error)
                    {
                        mssg_js = mssg_js + archivo[i].name + ", ";
                    }
                } else
                {
                    mssg_js = "Extensión no permitida. El archivo no fue publicado. Por favor ingrese únicamente archivos PDF o tipo documento";
                    error = true;
                }
            }
            if ((!error) && (archivo.length > 0))
            {
                mssg_js = mssg_js + "fue subido correctamente.";
            }
            endmsg = -1;

            /*Ejecutamos la función ajax de jQuery*/
            $.ajax({
                url: baseUrl + "Documents/add_new_document/",
                type: 'POST', //Metodo que usaremos
                contentType: false, //Debe estar en false para que pase el objeto sin procesar
                data: archivos, //Le pasamos el objeto que creamos con los archivos
                processData: false, //Debe estar en false para que JQuery no procese los datos a enviar
                cache: false, //Para que el formulario no guarde cache
                error: function (response) {
                    console.error("El doc no fue enviado: ", response);
                },
                success: function (response) {
                    //console.error("El doc SI fue enviado: ", response);
                    //

                    // window.location.href= window.location.href;

                    $('#PublicationTitlePublication').val('');
                    $('#PublicationContentPublication').val('');
                    $('.b_editar').removeAttr('onclick');

                }
            }).done(function (msg) {//Escuchamos la respuesta y capturamos el mensaje msg

                // console.log(msg);

                endmsg = msg.indexOf("<div class=");
                mssg = msg.substring(0, endmsg);
                //MensajeFinal(mssg);
                MensajeFinal(mssg_js);
                pub = msg.replace(mssg, '');
                $('#todos_mis_documentos').prepend(pub);
                $container_doc
                        .isotope('reloadItems').isotope({sortBy: 'original-order'});

                $('#DocumentTitleDocument').val('');
                $('#DocumentContentDocument').val('');
                $('#DocumentLinkSecop').val('');
                if (/MSIE/.test(navigator.userAgent)) {
                    $('#DocumentDocuments').replaceWith($(this).clone(true));
                } else {
                    $('#DocumentDocuments').val('');
                }
                $('.b_editar').removeAttr('onclick');

            });

            //if (endmsg != -1 )
            //{
            $('#DocumentTitleDocument').val('');
            $('#DocumentContentDocument').val('');
            $('#DocumentDocuments').val('');
            //}
        }
);



// Editar documentos
$('#todos_mis_documentos').on('click', '#b_edit_document',
        function ()
        {
            //var my_id = ((this.parents()).parents()).id;
            var my_id; // = $(this).closest("td").attr("id");
            my_id = $('#DocumentId').val();

            if (($('#DocumentPreEditForm>div>#DocumentTitleDocument').val() == '') || ($('#DocumentPreEditForm>div>#DocumentContentDocument').val() == ''))
            {
                $(location).attr('href', '#no-empty');
            } else
            {
                alert();
            }

            $.ajax({
                type: 'POST',
                async: true,
                cache: false,
                url: baseUrl + "Documents/edit/" + my_id,
                success: function (response) {
                    //$('#nueva_publicacion').html(response);
                    $('#document_box' + my_id).html(response);
                    $container_doc
                            .isotope('reloadItems').isotope({sortBy: 'original-order'});
                    console.log("Llego el contenido y no hubo error", response);
                },
                error: function (response) {
                    console.error("Este callback maneja los errores", response);
                },
                data: $('form').serialize()
            });
            return false;
        }
);
            