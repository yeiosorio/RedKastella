
            <div class="panel-body">
                <div class="row">

                    <!-- Sección de formulario -->
                    <div class="col-md-12">

                        <!-- Fomulario de Nueva Publicación -->
                        <form method="post" enctype="multipart/form-data" id="publicationsForm">

                            <!-- Titulo -->
                            <input name="title" type="text" placeholder="Ingrese un título para su publicación" class="form-control title-publication" style="background-color:white;" maxlength="100" required/>
                            <br/>

                            <!-- Contenido -->
                            <textarea name="content" placeholder="Ingrese su texto..." rows="5" class="form-control" style="background-color:white;" maxlength="1000" required></textarea>

                            <!-- Campo escondido de tipo file para los archivos adjuntos -->
                            <input type="file" name="files[]" class="attachedDocuments" style="display:none;" multiple />

                            <!-- Sección de botones -->
                            <div class="panel-body buttons-spacing-vertical">
                             

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
    