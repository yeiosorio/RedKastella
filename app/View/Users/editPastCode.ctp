<?php
    $username=$this->session->read('User.username');
    $user_id=$this->session->read('User.id');
?>
<div class="st-content" style="overflow:scroll">
<div class="page-section" style="overflow:scroll">
    <div class="row">
        <div class="col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2" style="display:none;">
            <!-- <h4 class="page-section-heading">Editar Perfil</h4> -->

            <!-- Inicio panel de perfil -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <ol class="breadcrumb" style="margin-bottom: -14px;">
                        <li><a href="#" onclick="history.back()">Volver</a></li>
                        <li class="active">Editar mi perfil</li>
                    </ol>
                    <br /><br />
        
        <?php
            //formulario para editar los datos personales del usuario (nombre, ciudad, ocupación, etc ...)
            echo "<div style=\"color:red;\">".$this->Session->flash()."</div>";
            echo $this->Form->create('User',array('id'=>'form_edit', 'action'=>'edit', 'enctype'=>'multipart/form-data')); ?>
        
            <div class="form-group">
            <h5>Actualice los datos que compartirá con la comunidad de Kastella</h5>
                
            <?php 
                //Imagen para mostrar (visualización y subida de archivo)
                echo $this->html->image($datos_usuario['Person']['path_avatar'], array('width' => 200, 'height' => 200, 'id' => 'img_avatar')); 
                echo $this->Form->input('profile_picture',array( 'type' => 'file'));
            ?>
                <ul class="skins">

                    <li id="default" class="" onclick="cambiar_color('default');return false;"><span style="background: #16ae9f" ></span></li>

                    <li id="orange" class="" onclick="cambiar_color('orange');return false;"><span style="background: #e74c3c" ></span></li>

                    <li id="blue" class="" onclick="cambiar_color('blue');return false;"><span style="background: #4687ce" ></span></li>

                    <li id="purple" class="" onclick="cambiar_color('purple');return false;"><span style="background: #af86b9" ></span></li>

                    <li id="brown" class="" onclick="cambiar_color('brown');return false;"><span style="background: #c3a961" ></span></li>

                    <!--<li id="" class="changeColor"><span style="background: #242424 " data-skin="default-nav-inverse" data-file="skin-default-nav-inverse"></span></li>-->
                    
                    

                </ul>
                <span style="background: #16ae9f" ></span>
            <?php
                //Datos básicos
                echo $this->Form->input('username', array('label' => 'Nombre de Usuario:', 'class'=>'form-control', 'readonly' => 'readonly'));
                echo $this->Form->input('email', array('type'=>'email', 'label' => 'Correo Eléctrónico:', 'class'=>'form-control', 'readonly' => 'readonly'));
                echo $this->Form->input('name', array('label' => 'Nombres:', 'class'=>'form-control'));
                echo $this->Form->input('surname', array('label' => 'Apellidos:', 'class'=>'form-control'));

                //datos adicionales
                echo $this->Form->input('eslogan', array('value'=>$datos_usuario['Person']['eslogan'], 'label' => 'Eslogan:','class'=>'form-control'));
                echo $this->Form->input('city', array('value'=>$datos_usuario['Person']['city'], 'label' => 'Ciudad/Municipio:', 'class'=>'form-control'));
                echo $this->Form->input('state', array('value'=>$datos_usuario['Person']['state'], 'label' => 'Departamento:', 'class'=>'form-control'));
                echo $this->Form->input('occupation', array('value'=>$datos_usuario['Person']['occupation'], 'label' => 'Profesión:', 'class'=>'form-control'));
                echo $this->Form->input('organization', array('value'=>$datos_usuario['Organization']['name'], 'label' => 'Organizacion (nombre):', 'class'=>'form-control'));
                echo $this->Form->input(                                                                        
                                    //organization_name 
                                    'select_entity', //id
                                    array(
                                        'options' => $datos_org['Entidad'],
                                        'type' => 'hidden',
                                        //'empty' => 'Seleccione la entidad', 
                                        'label' => 'Entidad:',
                                        'class'=>'scale',
                                        //'Selected' => $datos_org['Entidad'][$datos_usuario['Person']['organization_id']]
                                        'selected' => '12'
                                    )
                                );

            ?>
            </div>
        <?php 
            echo "<center>";
            echo $this->Form->submit(__('Guardar Usuario',true), array('type' => 'submit', 'class'=>'btn btn-primary')); 
            echo $this->Form->end();
            echo "</center>";
        ?>
                    
        <?php
            // Edicion de password
                echo "<hr />";
                echo "<button id=\"togglear\" class=\"btn btn-primary\"> Modificar contraseña </button>";
                echo $this->Form->create('User',array('id'=>'form_pwd', 'default' => 'false'));
                echo "<div class=\"my_toggle\">";
                echo "<br />";
                echo $this->Form->input('password-old', array( 'type'=>'password', 'autocomplete'=>'off', 'label' => 'Contraseña Anterior:', 'class'=>'form-control', 'autocomplete'=>'off', 'required' => 'required'));
                echo $this->Form->input('password', array( 'type'=>'password', 'autocomplete'=>'off', 'label' => 'Contraseña Nueva:', 'class'=>'form-control', 'required' => 'required'));
                echo $this->Form->input('pwd_confirmation', array(/*'value'=>$datos_usuario['User']['password'],*/ 'type'=>'password', 'autocomplete'=>'off', 'label' => 'Confirmar Contraseña Nueva:', 'class'=>'form-control', 'required' => 'required'));
                echo "<center>";
                echo $this->Form->submit(__('Guardar Contraseña',true), array('type' => 'submit', 'class'=>'btn btn-primary')); 
                echo $this->Form->end();
                echo "</center>";
                echo "<div id=\"message_doc\"> </div>";
                echo "</div>";
        ?>
                </div>
            </div>
        <!-- fin panel de perfil -->


    </div>


<div class="col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">

        <div id="acc">

            <!-- sección de perfil -->
            <h3>Editar mi Perfil</h3>
            
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
                        echo "<div style=\"color:red;\">".$this->Session->flash()."</div>";
                        echo $this->Form->create('User',array('id'=>'form_edit', 'action'=>'edit', 'enctype'=>'multipart/form-data')); ?>
                    
                        <div class="form-group">
                        <h5>Actualice los datos que compartirá con la comunidad de Kastella</h5>
                            
                        <?php 
                            //Imagen para mostrar (visualización y subida de archivo)
                            echo $this->html->image($datos_usuario['Person']['path_avatar'], array('width' => 200, 'height' => 200, 'id' => 'img_avatar')); 
                            echo $this->Form->input('profile_picture',array( 'type' => 'file'));
                        ?>
                            <ul class="skins">

                                <li id="default" class="" onclick="cambiar_color('default');return false;"><span style="background: #16ae9f" ></span></li>

                                <li id="orange" class="" onclick="cambiar_color('orange');return false;"><span style="background: #e74c3c" ></span></li>

                                <li id="blue" class="" onclick="cambiar_color('blue');return false;"><span style="background: #4687ce" ></span></li>

                                <li id="purple" class="" onclick="cambiar_color('purple');return false;"><span style="background: #af86b9" ></span></li>

                                <li id="brown" class="" onclick="cambiar_color('brown');return false;"><span style="background: #c3a961" ></span></li>

                                <!--<li id="" class="changeColor"><span style="background: #242424 " data-skin="default-nav-inverse" data-file="skin-default-nav-inverse"></span></li>-->
                                
                                

                            </ul>
                            <span style="background: #16ae9f" ></span>
                        <?php
                            //Datos básicos
                            echo $this->Form->input('username', array('label' => 'Nombre de Usuario:', 'class'=>'form-control', 'readonly' => 'readonly'));
                            echo $this->Form->input('email', array('type'=>'email', 'label' => 'Correo Eléctrónico:', 'class'=>'form-control', 'readonly' => 'readonly'));
                            echo $this->Form->input('name', array('label' => 'Nombres:', 'class'=>'form-control'));
                            echo $this->Form->input('surname', array('label' => 'Apellidos:', 'class'=>'form-control'));

                            //datos adicionales
                            echo $this->Form->input('eslogan', array('value'=>$datos_usuario['Person']['eslogan'], 'label' => 'Eslogan:','class'=>'form-control'));
                            echo $this->Form->input('city', array('value'=>$datos_usuario['Person']['city'], 'label' => 'Ciudad/Municipio:', 'class'=>'form-control'));
                            echo $this->Form->input('state', array('value'=>$datos_usuario['Person']['state'], 'label' => 'Departamento:', 'class'=>'form-control'));
                            echo $this->Form->input('occupation', array('value'=>$datos_usuario['Person']['occupation'], 'label' => 'Profesión:', 'class'=>'form-control'));
                            echo $this->Form->input('organization', array('value'=>$datos_usuario['Organization']['name'], 'label' => 'Organizacion (nombre):', 'class'=>'form-control'));
                            echo $this->Form->input(                                                                        
                                                //organization_name 
                                                'select_entity', //id
                                                array(
                                                    'options' => $datos_org['Entidad'],
                                                    'type' => 'hidden',
                                                    //'empty' => 'Seleccione la entidad', 
                                                    'label' => 'Entidad:',
                                                    'class'=>'scale',
                                                    //'Selected' => $datos_org['Entidad'][$datos_usuario['Person']['organization_id']]
                                                    'selected' => '12'
                                                )
                                            );

                        ?>
                        </div>
                    <?php 
                        echo "<center>";
                        echo $this->Form->submit(__('Guardar Usuario',true), array('type' => 'submit', 'class'=>'btn btn-primary')); 
                        echo $this->Form->end();
                        echo "</center>";
                    ?>
                                
                    <?php
                        // Edicion de password
                            echo "<hr />";
                            echo "<button id=\"togglear\" class=\"btn btn-primary\"> Modificar contraseña </button>";
                            echo $this->Form->create('User',array('id'=>'form_pwd', 'default' => 'false'));
                            echo "<div class=\"my_toggle\">";
                            echo "<br />";
                            echo $this->Form->input('password-old', array( 'type'=>'password', 'autocomplete'=>'off', 'label' => 'Contraseña Anterior:', 'class'=>'form-control', 'autocomplete'=>'off', 'required' => 'required'));
                            echo $this->Form->input('password', array( 'type'=>'password', 'autocomplete'=>'off', 'label' => 'Contraseña Nueva:', 'class'=>'form-control', 'required' => 'required'));
                            echo $this->Form->input('pwd_confirmation', array(/*'value'=>$datos_usuario['User']['password'],*/ 'type'=>'password', 'autocomplete'=>'off', 'label' => 'Confirmar Contraseña Nueva:', 'class'=>'form-control', 'required' => 'required'));
                            echo "<center>";
                            echo $this->Form->submit(__('Guardar Contraseña',true), array('type' => 'submit', 'class'=>'btn btn-primary')); 
                            echo $this->Form->end();
                            echo "</center>";
                            echo "<div id=\"message_doc\"> </div>";
                            echo "</div>";
                    ?>
                </div>
            </div>
            </p>
            </div>
            <!-- fin sección de perfil -->


            <!-- Sección de contratos de interes -->
            <!-- Encabezado -->
            <h3>Preferencias de busqueda</h3>
            <div>
                <p>
                <!-- traemos el elemento de contratos de interes con sus funcionalidades -->
                    <?php
                        echo $this->element('contractsPreferences/contractsPreferences');
                    ?>
                </p>
            </div>
            <!-- Fin sección de contratos de interes  -->


            <!-- Sección de contratos de interes -->
            <!-- Encabezado -->
            <h3>Contratos de Interes</h3>
            <div>
                <p>
                <!-- traemos el elemento de contratos de interes con sus funcionalidades -->
                    <?php
                        echo $this->element('interestContracts/interestContracts');
                    ?>
                </p>
            </div>
            <!-- Fin sección de contratos de interes  -->
            
          
            </div>

        </div>
    </div>
</div>
</div>

<!-- requerimos el css necesario -->
<?php echo $this->Html->css('jquery-ui/jquery-ui.min', null, array('block' => 'css')); ?>
<?php echo $this->Html->css('jquery-ui/jquery-ui.theme.min', null, array('block' => 'css')); ?>
<?php echo $this->Html->css('slider/css/slider', null, array('block' => 'css')); ?>

<!-- obtenemos los scripts necesarios y los imprimimos en el bloque scriptbottom -->
<?php echo $this->Html->script('jquery-ui/jquery-ui', array('block' => 'scriptBottom')); ?>


<!-- scripts slider de selección -->
<?php echo $this->Html->script('slider/js/bootstrap-slider', array('block' => 'scriptBottom')); ?>
<!-- scripts para el formateo de los valores de tipo dinero -->
<?php echo $this->Html->script('jquery.formatCurrency-1.4.0', array('block' => 'scriptBottom')); ?>




<!-- edicion de usuarios -->
<?php echo $this->Html->script('users/usersEdit', array('block' => 'scriptBottom')); ?>

<!-- scripts de contractos de interes -->
<?php echo $this->Html->script('interestContracts/interestContracts', array('block' => 'scriptBottom')); ?>


<!-- scripts de preferencias de busqueda de contratos -->
<?php echo $this->Html->script('contractsPreferences/contractsPreferences', array('block' => 'scriptBottom')); ?>

<!-- scripts de departamentos -->
<?php echo $this->Html->script('localization/departments', array('block' => 'scriptBottom')); ?>

<!-- scripts de ciudades -->
<?php echo $this->Html->script('localization/municipalities', array('block' => 'scriptBottom')); ?>



