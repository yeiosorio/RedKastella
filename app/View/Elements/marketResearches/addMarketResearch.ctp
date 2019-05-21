
<div class="row">
    <div class="col-md-12 col-lg-12 col-md-offset-1 col-lg-offset-0">
        <div class="panel panel-default" style="border-radius:6px; margin-left:15px;">
            <div class="panel-body">
                <div class="row">

                    <!-- Breadcumbs -->
                    <ol class="breadcrumb" >
                        <li><a href="#" onclick="history.back()">Volver</a></li>
                        <li class="active">Estudios de Mercado</li>
                    </ol>

                    <!-- Fin Breadcumbs -->

                    <!-- Sección de formulario -->
                    <div class="col-md-12">

                        <!-- Fomulario de Nueva Publicación -->
                        <form method="post" enctype="multipart/form-data" id="publicationsForm">

                            <!-- Titulo -->
                            <input name="title" type="text" placeholder="Ingrese un título para su publicación" class="form-control title-publication" style="background-color:white;" maxlength="100" required/>

                            <!-- Contenido -->
                            <textarea name="content" placeholder="Ingrese su texto..." rows="5" class="form-control" style="background-color:white;" maxlength="1000" required></textarea>


                            <!-- Campo escondido de tipo file para los archivos adjuntos -->
                            <input type="file" name="files[]" class="attachedDocuments" style="display:none;" multiple />

                            <!-- Sección de botones -->
                            <div class="panel-body buttons-spacing-vertical">
                                <p style="padding-left: 26%">
                                
                                    <!-- Botón de Adjuntar documentos -->
                                    <button type="button" class="attachDocuments btn btn-default" style="height:33px;">Adjuntar Documentos</button>

                                    <!-- Botón de seleccione de visibilidad -->
                                    <select name="privacyId" class="btn btn-primary dropdown-toggle privacies custom-dropdown-height" data-toggle="dropdown"></select>

                                    <!-- Enviar -->
                                    <button type="submit" class="btn btn-primary" ><i class="fa fa-cloud-upload btn-publish-gls"></i> Publicar</button>
                                </p>
                            </div>
                            <!-- Fin sección de botones -->
                        </form>
                        <!-- Fin Formulario nueva Publicación  -->
                    </div>
                    <!-- Fin Sección de formulario -->
                    
                    <!-- mensaje -->
                    <div id="message_doc"> </div>
                </div>
            </div>
        </div>
    </div>
</div>