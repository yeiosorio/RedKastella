
<div class="page-section">
    <div class="row">
        <div class="col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <ol class="breadcrumb" style="margin-bottom: -14px;">
                        <li><a href="#" onclick="history.back()">Volver</a></li>
                        <li class="active">Registro</li>
                    </ol>
                    <br /><br />
                <?php
                    //Formulario de registro de nuevos usuarios
                    echo $this->Form->create(
                                        'User', 
                                        array(

                                            'url'=>array(
                                                'controller'=>'users',
                                                'action'=>'/add/'
                                            ), 
                                            'class'=>'form-horizontal',
                                            'id'=>'addUserForm'
                                        )
                                    ); 
                ?>
            
            <div class="form-group">
            <h5>Ingrese sus datos para realizar el registro y entrar a formar parte de Kastella</h5>

        <?php
            echo $this->Form->input('name', array('label' => 'Nombre:', 'class'=>'form-control'));
        
            echo $this->Form->input('surname', array( 'label' => 'Apellidos:', 'class'=>'form-control'));
        
            echo $this->Form->input('email', array('type'=>'email', 'label' => 'Correo Eléctrónico:', 'class'=>'form-control'));
        
            echo $this->Form->input('username', array('label' => 'Usuario:', 'class'=>'form-control'));
        
            echo $this->Form->input('password', array('type'=>'password', 'value'=>'', 'autocomplete'=>'off', 'label' => 'Contraseña:', 'class'=>'form-control'));
        
            echo $this->Form->input('pwd_confirmation', array('type'=>'password', 'value'=>'', 'autocomplete'=>'off', 'label' => 'Confirmar Contraseña:', 'class'=>'form-control'));

        ?>

        <!-- Elementos necesarios para mostrar los departamentos y las ciudades -->
        <label>Departamento:</label>
        <select id="selectDepartment" class="form-control"></select>

        <label>Ciudad:</label>
        <select id="selectMunicipality" name="data[User][municipalities_id]" class="form-control" required></select>

        <div class="input text required error no-display error-select-city">
            <div class="error-message">Por favor seleccione una ubicaci&oacute;n ***</div>
        </div>
        </div>
        
            Al hacer clic en "Regístrate", aceptas nuestros
        
        <?php echo $this->Html->link('Términos y Condiciones', 
                                array('controller' => 'users','action' => 'terminos_y_condiciones'), 
                                array('escape' => false, 'target' => '_blank')); 
        ?>
            y confirmas que leíste nuestra

        <?php echo $this->Html->link('Política de Privacidad', 
                                array('controller' => 'users','action' => 'politica_de_privacidad'), 
                                array('escape' => false, 'target' => '_blank'));
        ?>
        
        incluido el uso de cookies.
        <center>
        <?php 
            echo $this->Form->submit(__('Regístrate',true), array('type' => 'submit', 'class'=>'btn btn-primary', 'style' => 'margin-top:20px')); 
            echo $this->Form->end(); 
        ?>
        </center>

                </div>
            </div>
        </div>
    </div>
</div>


<?php 

    /**
     * Arreglo con los scripts necesarios
     * @var Array
     */
    $scripts = Array(

        /**
         * Scripts de departamentos
         */
        'users/usersAdd',
        
    );

    /**
     * Imprimimos los scripts
     */
    echo $this->Html->script($scripts, array('block' => 'scriptBottom')); 
 
?>




























