<style type="text/css" scoped="scoped">
    .btn:hover 
    {
        background-color: #E0500E !important;
        border-color: #E0500E !important;
    }
</style>
<div class="container-fluid">

    <div class="lock-container">
          
        
        <h1>Recuperar Contraseña</h1>
         
        <div class="panel panel-default text-center">
            <h2 style="text-align:center; color: grey; font-size: 14px; margin-top: 10px; margin-bottom: 10px;">  Kastella es la Comunidad de Contratistas <br>del Estado Colombiano </h2>

            <?php echo $this->html->image("logo-kastella.jpg", array("alt" => "img user", "class"=>"img-circle"));?>
            
            <div class="panel-body">
                <?php 
                    // Inicia formulario de inicio de sesión
                    echo $this->Form->create('User'); 
                ?>
            <!--<input class="form-control" type="text" placeholder="Username">
            <input class="form-control" type="password" placeholder="Enter Password">-->
              
                <?php 
                    echo $this->Form->input('email',
                            array('type'=>'email', 'value'=>'', 'placeholder' => 'Correo Electrónico','class' =>'form-control', 'label'=>'', 'div' => 'false', 'style' => 'border:#CFCECC'));
                ?>
                
              
                <?php 
                    //Finaliza formulario, por el momento el botón de submit tiene la clase button form
                    echo $this->Form->submit(__('Enviar',true), array('type' => 'submit', 'class'=>'btn btn-primary', 'style' => 'background-color: #FC6621; border-color: #9E9E9E !important')); 
                    echo $this->Form->end();
                ?>
                <?php //echo $this->Session->flash('auth'); ?>
                <?php echo $this->Session->flash(); ?>

                <?php 
                    $div_bregistro = $this->html->div('button_form','Aún no eres miembro  <br> Regístrate aquí', array('style'=>'margin-left: 1.5%; color: #FC6621;')); 
                    //se emplea un div al interior de un link.
                    echo $this->html->link($div_bregistro,"/users/add/",array('class'=>'forgot-password', 'escape'=>false));

                ?>
              

                <h5 style="text-align:center; color: grey">  

                Encuentra aliados, proveedores y entidades conectándote con toda la información que necesitas para tus negocios con el Estado.</h5>
            </div> <!-- cierra panel body -->
        </div> <!-- cierra panel default -->
    </div>
</div>