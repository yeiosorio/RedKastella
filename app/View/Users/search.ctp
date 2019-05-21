<?php
    //https://www.pedroventura.com/cakephp/ajax-y-jquery-en-cakephp/
    $username=$this->session->read('User.username');
    //$this->Html->script('prototype', array('inline' => false));
?>

<div class="st-content-inner">
   
    <!--barra negra friends-->
    <?php echo $this->element('barra_friends') ?>
    <!--fin barra friends-->
    
    <div class="container-fluid">
        <div class="news" style="width:60%;float:left;">
        <?php echo $this->Form->create('User',array('id'=>'form_search', 'action'=>'search')); ?>
        <fieldset>
            <legend>
                <?php 
                    echo __('<h1>Búsqueda de Usuario</h1>'); 
                ?>
            </legend>

            <hr style="width:100%;">
            <p></p>
            <p>  
            <?php
                echo "Seleccione el tipo de búsqueda:";
                //OJO, la idea es que aqui se quede en el formulario si se selecciona usuarios, pero se redirija a documents/search (otra vista) si es seleccionado documento, y sea un formulario adecuado para la búsqueda de docs.
                echo $this->Form->input(
                                    'search_type', //id
                                    array(
                                        'options' => array('Tipo de búsqueda' => array('users' => 'Usuarios', 'documents' => 'Documentos')),           
                                        //'empty' => 'Seleccione el tipo de pago', 
                                        'Selected' => 'users',
                                        'label' => 'Seleccione el tipo de búsqueda:',
                                        'class'=>'scale'
                                    )
                                );
            ?>
            </p>
            <p>  
            <?php
                echo "Seleccione los parámetros que se ajusten a su búsqueda:";
            ?>
            </p>
            <?php
                echo $this->Form->input(                                                    //state
                                    'select_state', //id
                                    array(
                                        'options' => $list_state,           
                                        'empty' => 'Seleccione el departamento', //Your placeholder will goes here
                                        'label' => 'Departamento:',
                                        'class'=>'scale'
                                    )
                                );

                echo $this->Form->input(                                                    //state
                                    'select_municipality', //id
                                    array(
                                        'type'=>'select',           
                                        'empty' => 'Seleccione el municipio', 
                                        'label' => 'Ciudad o Municipio:',
                                        'class'=>'scale'
                                    )
                                );

                echo $this->Form->input(                                                    //organization_name
                                    'select_entity', //id
                                    array(
                                        'options' => array('Entidad' => array('users' => 'Usuarios', 'documents' => 'Documentos')),           
                                        'empty' => 'Seleccione la entidad', 
                                        'label' => 'Entidad:',
                                        'class'=>'scale'
                                    )
                                );
                echo $this->Form->input('keywords-search', array('value'=>'', 'label' => 'Palabras Clave:'));
                echo $this->Form->input('name-search', array('value'=>'', 'label' => 'Nombre:'));
                echo $this->Form->input('surname-search', array('value'=>'', 'label' => 'Apellido:'));
                echo $this->Form->input('position-search', array('value'=>'', 'label' => 'Cargo:')); //occupation
                echo $this->Form->input('email-search', array('type'=>'email', 'value'=>'', 'label' => 'Correo Eléctrónico:'));           
            ?>
        </fieldset>
        <?php 
            echo $this->Form->end(__('Buscar Usuario')); 
        ?>
        </div>
    </div>
</div>

<script>
    //Script que permite mediante Ajax listar los municipios pertenecientes a un departamento
    $("#UserSelectState").bind('click', function(){
        $.ajax({
            data: {"department":$(this).val() },
            type: "POST",
            url: "<?php echo $this->base; ?>/users/get_municipalities/"+jQuery("#UserSelectState").val(),

            dataType:"json",
            success: function(response, status) {
            if (response){
                        $("#UserSelectMunicipality").html("");
                        $("#UserSelectMunicipality").append('<option value="">Elije una ciudad</option>');
                        $.each(response.ciudades,function (k,v){
                        $("#UserSelectMunicipality").append('<option value='+k+'>'+v+'</option>');

                    });
                     }else{
                                alert("Elije un estado");
                            }
                },
        });
    });
</script>