<?php $user_id=$this->Session->read('User.id'); ?>
<div class="row">
    <div class="col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">

                    <?php 
                    echo $this->Form->create('Organization',array('id'=>'addOrganizationForm'));
                    
                    ?>

                    
                    <div class="form-group">
                        <?php
                            echo $this->Form->input('nit', Array(
                                    // 'placeholder'=>'Ingrese el nit de la entidad', 
                                    'label'=>'Nit:', 
                                    'class'=>'form-control',
                                    'type' => 'text', 
                                    'required' => 'required', 
                                    'style'=> Array('background-color:white')
                                    ));
                                    ?>
                                    <br />
                            <?php

                            echo $this->Form->input('name', Array(

                                  // 'placeholder'=>'Ingrese el nombre de la entidad', 
                                  'label'=>'Nombre:', 
                                  'maxlength' => '100', 
                                  'class'=>'form-control',
                                  'type' => 'text', 
                                  'required' => 'required',  
                                  'style'=> Array('background-color:white')
                            ));
                            ?>
                            <br />
                            <?php


                            echo $this->Form->input('slogan', Array(

                                  // 'placeholder'=>'Ingrese el slogan de la entidad', 
                                  'label'=>'Slogan:', 
                                  'maxlength' => '100', 
                                  'class'=>'form-control',
                                  'type' => 'text', 
                                  'required' => 'required',  
                                  'style'=> Array('background-color:white')
                            ));
                            ?>
                            <br />
                            <?php


                            echo $this->Form->input('address', Array(
                                // 'placeholder'=>'Ingrese la dirección de la entidad', 
                                'label'=>'Dirección:', 
                                'maxlength' => '100', 
                                'class'=>'form-control',
                                'type' => 'text', 
                                'required' => 'required',  
                                'style'=> Array('background-color:white')
                            ));
                        
                            ?>

                            <br />
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- Elementos necesarios para mostrar los departamentos y las ciudades -->
                                    <label>Departamento:</label><br />
                                    <select id="selectDepartment" class="btn btn-primary dropdown-toggle  custom-dropdown-height"></select>
                                </div>
                            </div>
                            <br />
                            <div class="row">    
                                <div class="col-md-2">

                                    <div class="form-group">

                                        <label>Ciudad:</label><br />
                                        <select id="selectMunicipality" name="data[Organization][municipality_id]" class="btn btn-primary dropdown-toggle  custom-dropdown-height" required></select>

                                        <div class="input text required error no-display error-select-city">
                                        <div class="error-message">Por favor seleccione una ubicaci&oacute;n ***</div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary" ><i class="fa fa-cloud-upload"></i> Publicar</button>
                                </div>
                            </div>

                        </div>

                        <?php echo $this->Form->end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>

