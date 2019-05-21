<?php
/**
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
<html class="st-layout ls-top-navbar ls-bottom-footer show-sidebar sidebar-l2 " lang="en">

<head>
        <?php echo $this->Html->charset(); ?>
        <?php 


          // echo $this->Html->meta(
          //     'favicon.ico',
          //     '/favicon.ico',
          //     array('type' => 'icon')
          // );

        echo $this->Html->meta('icon'); ?>

	  <!-- <meta charset="utf-8"> -->
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <meta name="description" content="">
	  <meta name="author" content="">
	 

	<title>

    <?php if(isset($sharedInfo)): ?> 
      
      <?php

         echo  "Kastella - ". $sharedInfo;
       ?>

  <?php else: ?>

      Kastella - Red Social de Negocios con el Estado



  <?php endif; ?>

	</title>

  <?php 

          $loggedUser = AuthComponent::user();   

  ?>

    <script type="text/javascript">

       /**
         * variable con la ruta de la aplicación
         * @type {String}
         */
        var baseUrl = "<?php echo Router::url('/', true); ?>";

      </script>

      <?php 

        $baseUrl = Router::url('/', true);

      ?>

	<?php
		//echo $this->Html->meta('icon');

		// echo $this->Html->css('cake.generic');



		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

    echo $this->Html->css('bootstrap-tour.min');

    echo $this->Html->css('mystyle');

    
      	
    /**
     * Selectize library
     */
    echo $this->Html->css('magicsuggest-min');
		

	?>






  <!-- Vendor CSS BUNDLE
    Includes styling for all of the 3rd party libraries used with this module, such as Bootstrap, Font Awesome and others.
    TIP: Using bundles will improve performance by reducing the number of network requests the client needs to make when loading the page. -->

  <?php 
	  	// impresión de estilos css globales
	    echo $this->Html->css('theme/vendor/all');
  ?>


  <!-- Vendor CSS Standalone Libraries
        NOTE: Some of these may have been customized (for example, Bootstrap).
        See: src/less/themes/{theme_name}/vendor/ directory -->
  <!-- <link href="css/vendor/bootstrap.css" rel="stylesheet"> -->
  <!-- <link href="css/vendor/font-awesome.css" rel="stylesheet"> -->
  <!-- <link href="css/vendor/picto.css" rel="stylesheet"> -->
  <!-- <link href="css/vendor/material-design-iconic-font.css" rel="stylesheet"> -->
  <!-- <link href="css/vendor/datepicker3.css" rel="stylesheet"> -->
  <!-- <link href="css/vendor/jquery.minicolors.css" rel="stylesheet"> -->
  <!-- <link href="css/vendor/bootstrap-slider.css" rel="stylesheet"> -->
  <!-- <link href="css/vendor/railscasts.css" rel="stylesheet"> -->
  <!-- <link href="css/vendor/jquery-jvectormap.css" rel="stylesheet"> -->
  <!-- <link href="css/vendor/owl.carousel.css" rel="stylesheet"> -->
  <!-- <link href="css/vendor/slick.css" rel="stylesheet"> -->
  <!-- <link href="css/vendor/morris.css" rel="stylesheet"> -->
  <!-- <link href="css/vendor/ui.fancytree.css" rel="stylesheet"> -->
  <!-- <link href="css/vendor/daterangepicker-bs3.css" rel="stylesheet"> -->
  <!-- <link href="css/vendor/jquery.bootstrap-touchspin.css" rel="stylesheet"> -->
  <!-- <link href="css/vendor/select2.css" rel="stylesheet"> -->

  <!-- APP CSS BUNDLE [css/app/app.css]
INCLUDES:
    - The APP CSS CORE styling required by the "social-2" module, also available with main.css - see below;
    - The APP CSS STANDALONE modules required by the "social-2" module;
NOTE:
    - This bundle may NOT include ALL of the available APP CSS STANDALONE modules;
      It was optimised to load only what is actually used by the "social-2" module;
      Other APP CSS STANDALONE modules may be available in addition to what's included with this bundle.
      See src/less/themes/social-2/app.less
TIP:
    - Using bundles will improve performance by greatly reducing the number of network requests the client needs to make when loading the page. -->
 
  
  <?php 
  	 	// impresión de estilos css globales
  	 echo $this->Html->css('theme/app/app');
  ?>



  <!-- App CSS CORE
This variant is to be used when loading the separate styling modules -->
  <!-- <link href="css/app/main.css" rel="stylesheet"> -->

  <!-- App CSS Standalone Modules
    As a convenience, we provide the entire UI framework broke down in separate modules
    Some of the standalone modules may have not been used with the current theme/module
    but ALL modules are 100% compatible -->

  <!-- <link href="css/app/essentials.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/layout.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/sidebar.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/sidebar-skins.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/navbar.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/media.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/player.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/timeline.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/cover.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/chat.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/charts.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/maps.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/colors-alerts.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/colors-background.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/colors-buttons.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/colors-calendar.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/colors-progress-bars.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/colors-text.css" rel="stylesheet" /> -->
  <!-- <link href="css/app/ui.css" rel="stylesheet" /> -->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries
WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!-- If you don't need support for Internet Explorer <= 8 you can safely remove these -->
  <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->


 <!-- <link type="text/css" rel="stylesheet" class="look" href="<?php  //echo $baseUrl; ?>css/theme/skin-brown.css"> -->


<!-- $('.look').attr("href", "http://localhost/kastella_theme/css/theme/skin-blue.css"); -->

<!-- skin-orange
skin-blue
skin-purple
skin-brown
skin-default-nav-inverse -->



  <?php 

      // pr($loggedUser);

  ?>
    <?php if(empty($loggedUser['skin'])): ?> 

      <script type="text/javascript">

          document.cookie="skin=default-nav-inverse";

      </script>

       <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>css/theme/skin-default-nav-inverse.css">

    <?php else: ?>


        <script type="text/javascript">

            document.cookie="skin=<?php echo $loggedUser['skin']['skin']; ?>";

        </script>

        <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>css/theme/<?php echo $loggedUser['skin']['skin_file']; ?>.css">        
        

    <?php endif; ?>

</head>

<body>

<!-- <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v2.7";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script> -->



  <!-- Wrapper required for sidebar transitions -->
  <div class="st-container">

    <?php echo $this->element('layout/top-menu-no-login'); ?>

   	 <!-- Sidebar component with st-effect-1 (set on the toggle button within the navbar) -->
    <div class="sidebar left sidebar-size-2 sidebar-offset-0 sidebar-visible-desktop sidebar-visible-mobile sidebar-skin-dark" id="sidebar-menu" data-type="collapse">
   		
   		<?php echo $this->element('layout/left-menu-no-login'); ?>

    </div>  

    
    <?php //echo $this->element('chat/chatSideBar'); ?>


    <!-- sidebar effects OUTSIDE of st-pusher: -->
    <!-- st-effect-1, st-effect-2, st-effect-4, st-effect-5, st-effect-9, st-effect-10, st-effect-11, st-effect-12, st-effect-13 -->

    <!-- content push wrapper -->
    <div class="st-pusher" id="content">

      <!-- sidebar effects INSIDE of st-pusher: -->
      <!-- st-effect-3, st-effect-6, st-effect-7, st-effect-8, st-effect-14 -->

      <!-- this is the wrapper for the content -->
      <div class="st-content">

        <!-- extra div for emulating position:fixed of the menu -->
        <div class="st-content-inner">

<!--           <nav class="navbar navbar-subnav navbar-static-top margin-bottom-none" role="navigation">
            <div class="container-fluid">

              <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#subnav">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="fa fa-ellipsis-h"></span>
                </button>
              </div>

              <div class="collapse navbar-collapse" id="subnav">
                <ul class="nav navbar-nav ">
                  <li class="active"><a href="index.html"><i class="fa fa-fw icon-ship-wheel"></i> Timeline</a></li>
                  <li><a href="user-public-profile.html"><i class="fa fa-fw icon-user-1"></i> About</a></li>
                  <li><a href="user-public-users.html"><i class="fa fa-fw fa-users"></i> Friends</a></li>
                </ul>
                <ul class="nav navbar-nav hidden-xs navbar-right ">
                  <li><a href="#" data-toggle="chat-box">Chat <i class="fa fa-fw fa-comment-o"></i></a></li>
                </ul>
              </div>
            </div>

          </nav> -->
          
          <div class="container-fluid">

          	<?php //echo $this->Flash->render(); ?>

			<?php echo $this->fetch('content'); ?>


          </div>

        </div>
        <!-- /st-content-inner -->

      </div>
      <!-- /st-content -->

    </div>
    <!-- /st-pusher -->

    <!-- Footer -->
    <footer class="footer">
      <strong>RedKastella</strong>  &copy; Copyright <?php echo date('Y'); ?>
    </footer>
    <!-- // Footer -->

  </div>
  <!-- /st-container -->

  <!-- Inline Script for colors and config objects; used by various external scripts; -->
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
      theme: "social-2",
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

  <!-- Vendor Scripts Bundle
    Includes all of the 3rd party JavaScript libraries above.
    The bundle was generated using modern frontend development tools that are provided with the package
    To learn more about the development process, please refer to the documentation.
    Do not use it simultaneously with the separate bundles above. -->
<?php 

      echo $this->Html->script('theme/vendor/all'); 
?>

  <!-- Vendor Scripts Standalone Libraries -->
  <!-- <script src="js/vendor/core/all.js"></script> -->
  <!-- <script src="js/vendor/core/jquery.js"></script> -->
  <!-- <script src="js/vendor/core/bootstrap.js"></script> -->
  <!-- <script src="js/vendor/core/breakpoints.js"></script> -->
  <!-- <script src="js/vendor/core/jquery.nicescroll.js"></script> -->
  <!-- <script src="js/vendor/core/isotope.pkgd.js"></script> -->
  <!-- <script src="js/vendor/core/packery-mode.pkgd.js"></script> -->
  <!-- <script src="js/vendor/core/jquery.grid-a-licious.js"></script> -->
  <!-- <script src="js/vendor/core/jquery.cookie.js"></script> -->
  <!-- <script src="js/vendor/core/jquery-ui.custom.js"></script> -->
  <!-- <script src="js/vendor/core/jquery.hotkeys.js"></script> -->
  <!-- <script src="js/vendor/core/handlebars.js"></script> -->
  <!-- <script src="js/vendor/core/jquery.hotkeys.js"></script> -->
  <!-- <script src="js/vendor/core/load_image.js"></script> -->
  <!-- <script src="js/vendor/core/jquery.debouncedresize.js"></script> -->
  <!-- <script src="js/vendor/tables/all.js"></script> -->
  <!-- <script src="js/vendor/forms/all.js"></script> -->
  <!-- <script src="js/vendor/media/all.js"></script> -->
  <!-- <script src="js/vendor/player/all.js"></script> -->
  <!-- <script src="js/vendor/charts/all.js"></script> -->
  <!-- <script src="js/vendor/charts/flot/all.js"></script> -->
  <!-- <script src="js/vendor/charts/easy-pie/jquery.easypiechart.js"></script> -->
  <!-- <script src="js/vendor/charts/morris/all.js"></script> -->
  <!-- <script src="js/vendor/charts/sparkline/all.js"></script> -->
  <!-- <script src="js/vendor/maps/vector/all.js"></script> -->
  <!-- <script src="js/vendor/tree/jquery.fancytree-all.js"></script> -->
  <!-- <script src="js/vendor/nestable/jquery.nestable.js"></script> -->
  <!-- <script src="js/vendor/angular/all.js"></script> -->

  <!-- App Scripts Bundle
    Includes Custom Application JavaScript used for the current theme/module;
    Do not use it simultaneously with the standalone modules below. -->

	<?php //echo $this->Html->script('theme/app/app'); ?>

  <!-- App Scripts Standalone Modules
    As a convenience, we provide the entire UI framework broke down in separate modules
    Some of the standalone modules may have not been used with the current theme/module
    but ALL the modules are 100% compatible -->

  <!-- <script src="js/app/essentials.js"></script> -->
  <!-- <script src="js/app/layout.js"></script> -->
  <!-- <script src="js/app/sidebar.js"></script> -->
  <!-- <script src="js/app/media.js"></script> -->
  <!-- <script src="js/app/player.js"></script> -->
  <!-- <script src="js/app/timeline.js"></script> -->
  <!-- <script src="js/app/chat.js"></script> -->
  <!-- <script src="js/app/maps.js"></script> -->
  <!-- <script src="js/app/charts/all.js"></script> -->
  <!-- <script src="js/app/charts/flot.js"></script> -->
  <!-- <script src="js/app/charts/easy-pie.js"></script> -->
  <!-- <script src="js/app/charts/morris.js"></script> -->
  <!-- <script src="js/app/charts/sparkline.js"></script> -->

  <!-- App Scripts CORE [social-2]:
        Includes the custom JavaScript for this theme/module;
        The file has to be loaded in addition to the UI modules above;
        app.js already includes main.js so this should be loaded
        ONLY when using the standalone modules; -->

  <!-- <script src="js/app/main.js"></script> -->

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
        url: baseUrl+"Users/getUserInfoPublic"
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

<!-- Modal -->
<div class="modal fade" id="modalFirstTimeInfo" tabindex="-1" role="dialog" aria-labelledby="modalFirstTimeInfoLabel">
  <div class="modal-dialog" role="document" style="    margin-top: 100px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalFirstTimeInfoLabel">¡Bienvenido a Kastella!</h4>
      </div>

      <div class="modal-body" style="height: 240px;">

      <p style="float: left; margin-right: 10px;">

        <span class="badge floating badge-primary modal-numbers">1</span>
      </p>
      <p style="float:left; width: 75%; margin-top: 5px;">
        Puedes ir a configurar tus preferencias de busqueda puedes ir a la sección preferencias del menu principal 


      </p>
         
      <div style="clear: both;"></div>

      <p style="float: left; margin-right: 10px;">
        <span class="badge floating badge-primary modal-numbers">2</span>
       
      </p>

      <p style="float:left; width: 75%; margin-top: 5px;">
         Ir a la secci&oacute;n de contratos de tu interes en el menu principal

      </p>
         
      <div style="clear: both;"></div>

      <p style="float: left; margin-right: 10px;">
        <span class="badge floating badge-primary modal-numbers">3</span>
       
      </p>

      <p style="float:left; width: 75%; margin-top: 5px;">
         Ir a la secci&oacute;n de contratos de tu interes en el menu principal

      </p>

      </div>
<!--       <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>



  

<!-- Is library -->
<?php echo $this->Html->script('is/is.min'); ?>

<!-- Selectize library -->
<?php echo $this->Html->script('magicsuggest-min'); ?>  


<!-- Función que renderiza scripts en un bloque en este caso scriptbottom -->
<?php echo $this->fetch('scriptBottom'); ?>



<?php echo $this->Html->script('globalSearch/globalSearch'); ?> 

<?php echo $this->Html->script('globalNotifications/globalNotifications'); ?> 


<?php 
      // echo $this->Html->script('https://cdn.socket.io/socket.io-1.2.0.js');  
      // echo $this->Html->script('ws-notifications/ws-connect'); 
?>

<?php echo $this->Html->script('bootstrap-tour.min'); ?> 


<script type="text/javascript">

    /**
     * Función que obtiene la informacion del usuario
     * @return Ajax
     */
     function setRecentlyRegistered() {
      return $.ajax({
          type:'POST',
          dataType: "json",
          url: baseUrl+"Users/setRecentlyRegistered"
      });

    }

    /**
     * si no es un dispotivo
     * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
     */
     if(!is.mobile()){

       $('.share-on-wp-link').remove();

     }


  // Instance the tour
  var tour = new Tour({
    // backdrop: true,
    onEnd: function (tour) {


      setRecentlyRegistered();

    },
    steps: [
    {
      element: ".idenTcontractPreferences",
      title: ' <span class="badge floating badge-primary b-numbers" >1</span>'+"Preferencias",
      content: "Aqui configuras la busqueda de contratos segun tus preferencias"
    },
    {
      element: ".idenTinterestContracts",
      title: '<span class="badge floating badge-primary b-numbers" >2</span>'+"Buscador Global",
      content: "Aqui Kastella se encarga de buscar contratos según tus preferencias y daras me interesa!"
    },
    {
      element: ".idenTinterestedInContracts",
      title: '<span class="badge floating badge-primary b-numbers" >3</span>'+"De mi interes",
      content: "Aqui Estan agrupados los contratos de tu interes para cuando quieras consultarlos"
    }

  ]});


 var recentlyRegistered  = <?php echo $loggedUser['recently_registered']; ?>

 function getKastTour(){

   /**
    * check if is mobile or desktop
    */
    if(is.mobile()){

      $('#modalFirstTimeInfo').modal('show');


      }else{
      
      // Initialize the tour
        tour.init();

        // Start the tour
        tour.start();
        
        tour.restart();
    }


  }


/**
 * evento del boton del tutorial
 */
 $(document).on('click','.getTutorial',function(){


    getKastTour();

 });



function setRespInfoButton(){


       if ($(window).width() <= 768 ){

 
              $('.resp-tut-btn').removeClass('no-display');

        }else{


              $('.resp-tut-btn').addClass('no-display');

      }

}

setRespInfoButton();

$(window).resize(function(){     


  setRespInfoButton();


});




 if (recentlyRegistered == 1) {

    /**
     * Show tour or modal
     */
    getKastTour();

  }



  $('#modalFirstTimeInfo').on('hidden.bs.modal', function () {

      setRecentlyRegistered();
  });


  /**
   * when tour end or when modal close! call setRecentlyRegistered to set the value to 0
   */
  



// tour.restart();
    

    //

      
    // var views = localStorage.getItem('views');



    // if (views === "null") {

    
    //   localStorage.setItem('views',1);      

    //   views = localStorage.getItem('views');

    //     $('#myModal').modal('show');

  
    // }



    // function to set the active menu highlight
  $(function() {

        var thisUrl = window.location.href;
  
        $("#side-menu li a").each(function(){

            if($(this).attr("href") == thisUrl ){
  
              $(this).parent().addClass("active");            
            }
       
       });
        
  });
  
  


</script>

</body>

</html>