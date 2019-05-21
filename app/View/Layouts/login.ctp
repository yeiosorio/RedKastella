<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>

<html class="hide-sidebar ls-bottom-footer" lang="es-ES">
    <head>
        <?php echo $this->Html->charset(); ?>
        <?php echo $this->Html->meta('icon'); ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Augusta Consultores, Kieigi">

    
        <title>
            Kastella – Red Social de Negocios con el Estado
        </title>
        <?php

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        
        echo $this->Html->css('vendor/all'); // Include bootstrap
        echo $this->Html->css('app/app'); // Include bootstrap
        echo $this->Html->css('mystyle'); 
        
    ?>
</head>
<body  style="background: url('../../img/lock-1.jpg');">


<div class="login" >
    

<!--     <div id="remember" class="modalmask">
        <div class="modalbox movedown">
            <a href="#close" title="Close" class="close">X</a>
            <h2>DESLIZAR</h2>
            <p>La ventana modal aparece por arriba y se desliza hasta su posición. Un efecto simple pero elegante.</p>
            <p>Aquí puedes incluir cualquier cosa como vídeos, mapas, formularios...</p>
        </div>
    </div> -->


<style type="text/css">

    
    .entity-logos{

        width: 75%;
    }


  .logo-1{

   
    /*width: 115%;*/
  }


  .logo-2{
    margin-top: 12px;
  }


  .logo-3{
    margin-top: 22px;
  }


  .logo-4{

    margin-top: 12px;
  }


  .logo-5{
    margin-top: 15px;
    width: 70%;
  }


  .logo-6{
    
    width: 50%;
 
  }


  .logo-7{
    
    width: 50%;
  }


  .logo-8{

    margin-top: 10px;
    width: 55%;
  }

</style>


<?php 

    
    $uploadsBaseUrl = Router::url('/landing/wp-content/uploads/', true);
  
  $arrImages = Array(

    [

      "img"   =>  "2014/09/nuevopais-300x119.png", 
      "class" =>  " ",
      "url"   =>  "https://www.dnp.gov.co/",
      "img-class" => " entity-logos logo-1"
    ], 

    [
      "img"   => "2014/09/MinTIC_Colombia_logo-300x91.png", 
      "class" => " ",
      "url"   =>  "http://www.mintic.gov.co/",
      "img-class" => " entity-logos logo-2"
    ], 
     
    [
      "img"   => "2014/09/customLogo-300x69.png", 
      "class" => " ",
      "url"   =>  "http://www.vivedigital.gov.co/",
      "img-class" => " entity-logos logo-3"
    ], 

    [
      "img"   => "2014/09/Logo-Colciencias-paginaweb2015-300x112.png", 
      "class" => " ",
      "url"   =>  "http://www.colciencias.gov.co/pf-colciencias/",
      "img-class" => " entity-logos logo-4"
    ],

    [
      "img"   => "2014/09/logo1-300x101.png", 
      "class" => " ",
      "url"   =>  "http://augustaconsultores.com/",
      "img-class" => " entity-logos logo-5"
    ], 
     
    [
      "img"   => "2016/09/logo-de-jardin_02.png", 
      "class" => " ",
      "url"   =>  "http://www.eljardin-antioquia.gov.co/",
      "img-class" => " entity-logos logo-6"
    ], 
       
    [
      "img"   => "2016/09/Génova.png", 
      "class" => " ",
      "url"   =>  "http://www.genova-quindio.gov.co/index.shtml",
      "img-class" => " entity-logos logo-7"
    ], 

    [
      "img"   => "2016/09/ARMENIA.png", 
      "class" => " ",
      "url"   =>  "http://www.armenia.gov.co/",
      "img-class" => " entity-logos logo-8"
    ]
  );

// $uploadsBaseUrl.$arrImages[$i]['img'];

// $arrImages[$i]['class'];

// $arrImages[$i]['img-class'];

?>

    <div id="content">
    
            <?php //echo $this->Session->flash(); ?>
            <?php echo $this->fetch('content'); ?>
    </div>


</div>

    <div class="row" style="background-color: rgba(16, 16, 16, 0.7);  margin-top: 155px; margin-bottom: -170px;">
    
    <div class="col-md-12">
        

    <div class="col-md-1">
    </div>

    <?php for ($i=0; $i < 5; $i++) : ?> 
       
        <div class="col-md-2 text-center" >
            <a href="<?php echo $arrImages[$i]['url']; ?>" target="_blank">
                <img src="<?php echo $uploadsBaseUrl.$arrImages[$i]['img']; ?>"  class = "<?php echo $arrImages[$i]['img-class'];  ?>" />
            </a>
        </div>

    <?php endfor; ?>


    </div>

    <div class="col-md-12">


       <div class="col-md-3">
    </div>

    <?php for ($i=5; $i < 8; $i++) : ?> 
       
        <div class="col-md-2 text-center">
            <a href="<?php echo $arrImages[$i]['url']; ?>" target="_blank">
                <img src="<?php echo $uploadsBaseUrl.$arrImages[$i]['img']; ?>"  class = "<?php echo $arrImages[$i]['img-class'];  ?>" />
            </a>
        </div>

    <?php endfor; ?>
        
    </div>

   
    </div>
            
    <!-- <footer class="footer"> -->

            <?php // echo $this->Html->image('foot1.jpg', array('alt' => 'minTIC', 'class'=>''))?>
             <?php // echo $this->Html->image('foot2.jpg', array('alt' => 'Colciencias', 'class'=>''))?> 
            <?php // echo $this->Html->image('foot3.jpg', array('alt' => 'ViveDigital', 'class'=>''))?>
            <?php // echo $this->Html->image('foot4.jpg', array('alt' => 'Gobierno en línea', 'class'=>''))?>
            <?php // echo $this->Html->image('foot5.jpg', array('alt' => 'Augusta', 'class'=>''))?>
<!--            <strong>Kastella</strong> v 1.0 -->
    <!-- </footer> -->

<!-- Libreria necesaria por colores-->








</body>
</html>
