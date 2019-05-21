

<div class="st-content" style="overflow:scroll; margin-top: -35px;" >
<div class="page-section" style="overflow:scroll">



<div class="col-md-8">

            <div>
                <p>
                    <div class="panel panel-default">
                            <div class="panel-body">
                                <ol class="breadcrumb" style="margin-bottom: -14px;">
                                    <li><a href="#" onclick="history.back()">Volver</a></li>
                                    <li class="active">Editar mi perfil</li>
                                </ol>
                                <br /><br />
                    
                        <?php
                            //formulario para editar los datos personales del usuario (nombre, ciudad, ocupación, etc ...)
                            echo "<div style='color:red;'>".$this->Session->flash()."</div>";
                        ?>
                    
                        <div class="form-group">
                        <!-- <h5>Actualice los datos que compartirá con la comunidad de Kastella</h5> -->
                        
                        
                        <form method="post" enctype="multipart/form-data" id="profilePicForm">
                            
                            <!-- Imagen de perfil -->
                            <div class="edit-prof-picture edit-pic-prof"  style="background-image:url('<?php echo $user['User']['profilePic']; ?>')"></div>  

                            <!-- Campo escondido de tipo file para los archivos adjuntos -->
                            <input type="file" name="files[]" id="profilePicFile" />
                         
                        </form>

                        <?php 
                            //Imagen para mostrar (visualización y subida de archivo)
                            echo $this->Form->create('User',array('id'=>'user-form-edit', 'url'=>'edit', 'enctype'=>'multipart/form-data','method'=>'POST')); 
                        ?>

                        <br/>
                        <label style="float: left;">Color: </label>
                        <br />
                        
                        <ul class="nav navbar-nav" style="float:left; margin-top: -33px;">

                        <li class="hidden-sm" data-toggle="tooltip" data-placement="bottom" title="Cuando haces click en uno de estos colores, cambias el estilo de la aplicación y queda configurado como tu preferencia!">
                          <ul class="skins">

                            <li><span data-file="app/app" data-skin="default" style="background: #16ae9f "></span></li>

                            <li><span data-file="skin-orange" data-skin="orange" style="background: #e74c3c "></span></li>

                            <li><span data-file="skin-blue" data-skin="blue" style="background: #4687ce "></span></li>

                            <li><span data-file="skin-purple" data-skin="purple" style="background: #af86b9 "></span></li>

                            <li><span data-file="skin-brown" data-skin="brown" style="background: #c3a961 "></span></li>

                            <li><span data-file="skin-default-nav-inverse" data-skin="default-nav-inverse" style="background: #242424 "></span></li>

                          </ul>
                        </li>
                      </ul>

                      <br />


<!-- 
                            <ul class="skins">

                                <li id="default" class="" onclick="cambiar_color('default');return false;"><span style="background: #16ae9f" ></span></li>

                                <li id="orange" class="" onclick="cambiar_color('orange');return false;"><span style="background: #e74c3c" ></span></li>

                                <li id="blue" class="" onclick="cambiar_color('blue');return false;"><span style="background: #4687ce" ></span></li>

                                <li id="purple" class="" onclick="cambiar_color('purple');return false;"><span style="background: #af86b9" ></span></li>

                                <li id="brown" class="" onclick="cambiar_color('brown');return false;"><span style="background: #c3a961" ></span></li>

                                
                            </ul>
                            <span style="background: #16ae9f" ></span> -->
                        <?php
                            
                            
                            echo $this->Form->input('username', array('label' => 'Nombre de Usuario:', 'class'=>'form-control', 'readonly' => 'readonly'));
            
                            echo $this->Form->input('email', array('type'=>'email', 'label' => 'Correo Eléctrónico:', 'class'=>'form-control', 'readonly' => 'readonly'));
            
                            echo $this->Form->input('name', array('label' => 'Nombres:', 'class'=>'form-control'));
            
                            echo $this->Form->input('surname', array('label' => 'Apellidos:', 'class'=>'form-control'));


                        ?>

                         <!-- Elementos necesarios para mostrar los departamentos y las ciudades -->
                        <label>Departamento:</label>
                        <select id="selectDepartmentEditProf" class="form-control"></select>


                        <input type="hidden" id="user-municipality" value="<?php echo $user['User']['municipalities_id']; ?>">

                        <label>Ciudad:</label>
                        <select id="selectMunicipalityEditProf" name="data[User][municipalities_id]" class="form-control" required></select>

                        <div class="input text required error no-display error-select-city">
                            <div class="error-message">Por favor seleccione una ubicaci&oacute;n ***</div>
                        </div>
    

                        </div>
                        

                        <center>

                                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Actualizar Datos</button>
                             <?php
                         
                                echo $this->Form->end();
                            ?>
                        </center>
                                
                         <!-- Edicion de password -->
                        
                            <hr />
                            <button id='togglear' class='btn btn-primary'> Modificar contraseña </button>

                            <?php
                                echo $this->Form->create('User',array('id'=>'form_pwd', 'default' => 'false')); ?>
                                <div class='my_toggle'>
                                <br />

                            <?php 

                                echo $this->Form->input('password-old', array( 
                                                                    'type'=>'password', 
                                                                    'autocomplete'=>'off',
                                                                    'label' => 'Contraseña Anterior:',
                                                                    'class'=>'form-control',
                                                                    'autocomplete'=>'off',
                                                                    'required' => 'required')
                                    );

                                echo $this->Form->input('password', array( 'type'=>'password', 
                                                                    'autocomplete'=>'off', 
                                                                    'label' => 'Contraseña Nueva:', 
                                                                    'class'=>'form-control', 
                                                                    'required' => 'required')
                                    );


                                echo $this->Form->input('pwd_confirmation', array('type'=>'password', 
                                                                        'autocomplete'=>'off', 
                                                                        'label' => 'Confirmar Contraseña Nueva:', 
                                                                        'class'=>'form-control', 
                                                                        'required' => 'required')
                                    );
                                
                                ?>

                                <center>

                                <div id='message_doc'></div>
                                    <br />
                                    <button type="submit" class="btn btn-primary">Guardar Contraseña</button>

                                    <?php echo $this->Form->end();?>       
        
                                </center>
                                
                                </div>
                   
                </div>
            </div>
            </p>
            </div>
            <!-- fin sección de perfil -->
          
            </div>
        </div>

</div>



<?php 

    /**
     * Arreglo con los estilos necesarios
     * @var Array
     */
    $styles = Array(
        

    );


    /**
     * Arreglo con los scripts necesarios
     * @var Array
     */
    $scripts = Array(

        /**
         * Jquery ui
         */
        // 'jquery-ui/jquery-ui',

        /**
         * Scripts slider de selección
         */
        // 'slider/js/bootstrap-slider',
        
        /**
         * Edición de usuarios
         */
        'users/usersEdit',
    
    );
 
    /**
     * imprimimos los estilos
     */
    // echo $this->Html->css($styles, null, array('block' => 'css'));   
    
    /**
     * Imprimimos los scripts
     */
    echo $this->Html->script($scripts, array('block' => 'scriptBottom')); 
 

?>