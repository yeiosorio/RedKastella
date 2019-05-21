<?php 

$loggedUser = AuthComponent::user();  
$baseUrl = Router::url('/', true);

print '<script type="text/javascript">;
		var listContracts = '.json_encode($listContracts).'
		var favorites = '.json_encode($favorites).'
		var loggedUser = '.json_encode($loggedUser).'
	</script>'; 
?>
<!DOCTYPE html>
<html>

  <head>
  	<?php echo $this->Html->charset(); ?>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
      <title></title>
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic">
      <link rel="stylesheet" href="<?=$baseUrl?>fonts/fontawesome-all.min.css">
      <link rel="stylesheet" href="<?=$baseUrl?>fonts/font-awesome.min.css">
      <link rel="stylesheet" href="<?=$baseUrl?>fonts/simple-line-icons.min.css">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Actor">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amiko">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Antic">
      <link rel="stylesheet" href="<?=$baseUrl?>css/bootstrap.min.css">
      <link rel="stylesheet" href="<?=$baseUrl?>css/styles.min.css">
      <link rel="stylesheet" href="<?=$baseUrl?>css/Bold-BS4-Pricing-Table-Style-50-1.css">
	  <link rel="stylesheet" href="<?=$baseUrl?>css/Bold-BS4-Pricing-Table-Style-50.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/css/bootstrap-slider.min.css">
  </head>

  <body>
    <?php echo $this->element('layout/top-menu'); ?>

    <?php echo $this->fetch('content'); ?>

	<script type="text/javascript">
			var baseUrl = "<?php echo Router::url('/', true); ?>";
			var searchInput = "<?php echo $searchInput ?>";
			var dataSearchPreference = "<?php echo $dataSearchPreference ?>";

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

		var recentlyRegistered  = "<?php echo $loggedUser['recently_registered']; ?>";	

	</script>

	<script src="<?=$baseUrl?>js/jquery.min.js"></script>
  <script src="<?=$baseUrl?>js/bootstrap.min.js"></script>
	<script src="<?=$baseUrl?>js/moment/moment-with-locales.js"></script>
	<script src="<?=$baseUrl?>js/jquery.formatCurrency-1.4.0.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/bootstrap-slider.min.js"></script>
	<script src="<?=$baseUrl?>js/jquery.smartWizard.js"></script>
	
	<script src="<?=$baseUrl?>js/home/searchContracts.js"></script>
	<script src="<?=$baseUrl?>js/favorites/favorites.js"></script>
	<script src="<?=$baseUrl?>js/script.min.js"></script>

	<?php echo $this->fetch('scriptBottom'); ?>
	
  </body>

</html>