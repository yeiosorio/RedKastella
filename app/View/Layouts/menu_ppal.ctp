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
?>
<!DOCTYPE html>
<html class="st-layout ls-top-navbar ls-bottom-footer show-sidebar sidebar-l2 " lang="es-ES">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Kastella – Red Social de Negocios con el Estado </title>

  <?php echo $this->Html->charset(); ?>
  <?php // echo $title_for_layout; ?>

  <script type="text/javascript">

        // var baseUrl = "<?php echo Router::fullbaseUrl(); ?>";
        // var baseUrl = "<?php echo $this->webroot; ?>";

       /**
         * variable con la ruta de la aplicación
         * @type {String}
         */
        var baseUrl = "<?php echo Router::url('/', true); ?>";

      </script>



      <?php
     
      echo $this->Html->meta('icon');
      
      // impresión de bloques de codigo dinamico
      echo $this->fetch('meta');
     
      echo $this->fetch('script');
      // Fin bloques de código dinamico

      // impresión de estilos css globales
      echo $this->Html->css('vendor/all');
      echo $this->Html->css('app/app');
      echo $this->Html->css('mystyle');
      // Fin impresión estilos globales
      // 
      // 
       echo $this->fetch('css');


       /**
        * Selectize library
        */
      echo $this->Html->css('magicsuggest-min');
      
      
      /**
       * Color definido por el usuario
       * @var String
       */
      $color = $this->session->read('User.color');
      
      /**
       * Estructura de control usada para ajustar la configuración de color definida por el usuario
       */
      switch($color)
      {
        case "purple":  echo $this->Html->css('vendor/skin-purple');
        break;

        case "blue":    echo $this->Html->css('vendor/skin-blue');
        break;

        case "orange":  echo $this->Html->css('vendor/skin-orange');
        break;

        case "brown":   echo $this->Html->css('vendor/skin-brown');
        break;

        case "default": echo $this->Html->css('vendor/skin-default-nav-inverse');
        break;

        default:        echo $this->Html->css('vendor/skin-orange');
        break;
      }

      // Fin estructura de control de color
      ?>
      
      <!-- echo $this->Html->css('bootstrap.min.css');  -->

      <!-- echo $this->Html->css('mycss');  -->
      
      <!-- echo $this->Html->script(array('jquery.min', 'jquery'));     -->
    
    </head>
    <body>
 
      <?php 
          // Elemento de ventana modal de información
          echo $this->element('modal/modalInfo'); 
      ?>

      <!-- Wrapper required for sidebar transitions -->
      <div class="st-container">

      <?php echo $this->element('menu_sup'); ?>
      <?php echo $this->element('menu_lateral'); ?>
      <?php echo $this->element('side_friends'); ?>


        <!-- incluímos el contenido-->
        <div class="st-pusher" id="content">
          <!-- sidebar effects INSIDE of st-pusher: -->
          <!-- st-effect-3, st-effect-6, st-effect-7, st-effect-8, st-effect-14 -->
          <!-- extra div for emulating position:fixed of the menu -->

          <?php //echo $this->Session->flash(); ?>
          <?php echo $this->fetch('content'); ?>
          <!-- /st-content-inner -->
        </div>
        <!-- fin del contenido -->

      </div>

      <!-- <footer class="footer"><strong>Kastella</strong> v 1.0 &copy; Copyright 2015</footer> -->

      <script>
        var colors = {
          "danger-color": "#e74c3c",
          "success-color": "#81b53e",
          "warning-color": "#f0ad4e",
          "inverse-color": "#2c3e50",
          "info-color": "#2d7cb5",
          "default-color": "#6e7882",
          "default-light-color": "#cfd9db",
          "purple-color": "#9D8AC7",
          "mustard-color": "#d4d171",
          "lightred-color": "#e15258",
          "body-bg": "#f6f6f6"
        };
        var config = {
          theme: "social-3",
          skins: {
            "default": {
              "primary-color": "#16ae9f"
            },
            "orange": {
              "primary-color": "#e74c3c"
            },
            "blue": {
              "primary-color": "#4687ce"
            },
            "purple": {
              "primary-color": "#af86b9"
            },
            "brown": {
              "primary-color": "#c3a961"
            },
            "default-nav-inverse": {
              "color-block": "#242424"
            }
          }
        };
      </script>
      <!--fin -->

      <?php

      echo $this->Html->script('jquery');
      echo $this->Html->script('vendor/core/all'); // Include jQuery library

      echo $this->Html->script('app/app'); // Include jQuery library
      echo $this->Html->script('jquery.isotope.min');
      echo $this->Html->script('jquery.infinitescroll.min');

      //echo $this->Html->script('jquery-ias.min');
     

      ?>

      <!-- scripts para arreglar -->
      <script>

        //agrega un hash # a la pagina, de modo que se evita regresar atras
        function initControls () {
        /*if(history.forward(1))
        {
        location.replace(history.forward(1));
        }*/
        window.location.hash = "";
          window.location.hash = "" //chrome
          window.onhashchange=function(){window.location.hash="";}
          //window.location.replace(window.history.forward(0));
        }

        /*function my_mail()
        {
        alert("hola");
      }*/

      /************************ WebSockets ************************/
        /*function testWebSocket()
        {
        websocket = new WebSocket (wsUri);
        websocket.onopen = onOpen;
        websocket.onclose = onClose;
        websocket.onmessage = onMessage;
        websocket.onerror = onError;
        }

        function onOpen(evt)
        {
        writeToScreen("CONECTADO");
        doSend("WebSocket funciona");
        }

        function onClose(evt)
        {
        writeToScreen("DESCONECTADO");
        }

        function onMessage(evt)
        {
        writeToScreen('<span style="color: blue;">RESPUESTA: ' + evt.data + '</span>');
        websocket.close();
        }

        function onError(evt)
        {
        writeToScreen('<span style="color: red;">ERROR: </span>' + evt.data);
        }

        function doSend(message)
        {
        writeToScreen("ENVIADO: " + message);
        websocket.send(message);
        }

        function writeToScreen(message)
        {
        var pre = document.createElement("p");
        pre.style.wordWrap = "break-word";
        pre.innerHTML = message;
        output.appendChild(pre);
      }*/


      function MensajeFinal (msg) {
        $('#message_doc').html(msg);//A el div con la clase msg, le insertamos el mensaje en formato  thml
        $('#message_doc').show('slow');//Mostramos el div.
      }



        $(document).ready(function(){


        // remove item if clicked
        /*$container_doc.delegate( '.item', 'click', function(){
        $container_doc.isotope( 'remove', $(this) );
        //$container.isotope('reloadItems').isotope({ sortBy: 'original-order' });
        $container_doc.isotope('reLayout').isotope({ sortBy: 'original-order' });
      });*/

    /*********************************************************/

    $('#searchBox').click( function(){

            //alert("mostrar");
            var effect = 'slide';
            // Set the options for the effect type chosen
            var options = { direction: "right" };
            // Set the duration (default: 400 milliseconds)
            var duration = 500;
            $('#sidebar-chat').toggle(effect, options, duration);

    });


    $("#OrganizationDepartment").change(function(){
    
      $.post(baseUrl+"Users/get_municipalities", { id_category: $(this).val() }, function(data){
        $("#OrganizationMunicipality").html(data);
      });
    
    });



});
</script>
<!-- Fin scripts para arreglar -->

<script type="text/javascript">


    /**
     * Objeto que contendra la información del usuario
     */
    var userInfo;

    /**
     * Función que obtiene la informacion del usuario
     * @return Ajax
     */
   function getUserInfo() {
    return $.ajax({
        type:'GET',
        dataType: "json",
        url: baseUrl+"Users/getUserInfo"
    });
  }


  /**
   * Función que inserta un like a una entidad asociada
   * @param  Object datos a enviars
   */
  function like(data){

    return $.ajax({
      url: baseUrl+'Likes/like',
      type: 'post',
      dataType: 'json',
      data: data
    });


  }


/**
 * Function to get params form url
 */

function getUrlVars(url) {
    var vars = {};
    var parts = url.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
    function(m,key,value) {
      vars[key] = value;
    });
    return vars;
  }
  

</script>




<?php 

      //echo $this->Html->script('https://cdn.socket.io/socket.io-1.2.0.js');  
      echo $this->Html->script('ws-notifications/ws-connect'); 
?>

<!-- Función que renderiza scripts en un bloque en este caso scriptbottom -->
<?php echo $this->fetch('scriptBottom'); ?>
<!-- Fin bloque scriptBottom -->

        <script type="text/javascript">

        
          // $('#tb_list_serv tr').each(function(){
          //   console.log($(this).find('td:last a').html());
          //   console.log($(this).find('td:last a').attr('href'));
          // });

        </script>
    


        <!-- Selectize library -->
       <?php echo $this->Html->script('magicsuggest-min'); ?>  


       <?php echo $this->Html->script('globalSearch/globalSearch'); ?> 

       <?php echo $this->Html->script('globalNotifications/globalNotifications'); ?> 



       <script type="text/javascript">

    

          // $.ajax({
          //     url: 'http://cdcsam.com/gl/launcher/sam.php?wsdl',
          //     type: 'get',
          //     success: function (data) {
              
          //         console.log(data);

          //      }
          //   });

       </script>








    </body>
</html>





