<?php 
/**
 * JavaScripts In Header
 */
function wt_enqueue_scripts() {
	if(is_admin()){
		return;
	}
	
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'modernizr', THEME_JS . '/vendor/modernizr-2.6.1.min.js', array('jquery'), null, false);
	wp_enqueue_script( 'conditional', THEME_JS . '/vendor/conditional.js', array('jquery'), null, true);	
	$bgType = wt_get_option('background','background_type');
	if($bgType == 'slideshow') {
		wp_enqueue_script( 'main', THEME_JS . '/main.js', array('jquery','jquery-supersized','jquery-supersized-shutter'), null, true);
	} else {
		wp_enqueue_script( 'main', THEME_JS . '/main.js', array('jquery', 'conditional'), null, true);}
	// Initialising WhoaThemes Shortcodes Scripts
	if (class_exists('WPBakeryVisualComposerAbstract')) {
		wp_enqueue_script( 'wt-visual-composer-extensions-front', THEME_URI. '/framework/shortcodes/assets/wt-visual-composer-extensions-front.js', array('jquery'), null, true);
	}
		
	wp_enqueue_script( 'plugins', THEME_JS . '/plugins.js', array('jquery'), null, true);
	wp_enqueue_script( 'fitvids', THEME_JS . '/vendor/jquery.fitvids.js', array('jquery'), null, true);		
	wp_enqueue_script( 'bootstrap', THEME_JS .'/vendor/bootstrap.min.js', array('jquery'), null, true);	
		
	if(wt_get_option('general','enable_retina')){
		wp_enqueue_script( 'retina', THEME_JS .'/vendor/retina.js', array('jquery'), null, true);	
	}
	if ( is_singular() && comments_open() ){
		wp_enqueue_script( 'comment-reply', true, true, true, true );
	}
	wp_register_script( 'jquery-supersized', THEME_JS .'/vendor/supersized.3.2.7.min.js', array('jquery'), null, true);
	wp_register_script( 'jquery-supersized-shutter', THEME_JS .'/vendor/supersized.shutter.min.js', array('jquery'), null, true);
		
	wp_register_script( 'nivo', THEME_JS . '/vendor/jquery.nivo.slider.pack.js', array('jquery'), null, true);
	wp_register_script( 'flex', THEME_JS . '/vendor/jquery.flexslider-min.js', array('jquery'), null, true);
	wp_register_script( 'owlCarousel', THEME_JS . '/vendor/jquery.owlCarousel.js', array('jquery'), null, true);	
	wp_register_script( 'bxSlider', THEME_JS . '/vendor/jquery.bxslider.js', array('jquery'), null, true);	
	wp_register_script( 'cycle', THEME_JS . '/vendor/jquery.cycle.min.js', array('jquery'), null, true);
	wp_register_script( 'cycle-vert', THEME_JS . '/vendor/jquery.cycle.scoll_vert.min.js', array('jquery'), null, true);
	wp_register_script( 'cycle-shuffle', THEME_JS . '/vendor/jquery.cycle.shuffle.min.js', array('jquery'), null, true);
	wp_register_script( 'cycle-tile', THEME_JS . '/vendor/jquery.cycle.tile.min.js', array('jquery'), null, true);
	wp_register_script( 'cycle-youtube', THEME_JS . '/vendor/jquery.cycle.youtube.min.js', array('jquery'), null, true);
		
	wp_register_script( 'mobileMenu', THEME_JS . '/vendor/init.mobileMenu.js', array('jquery'), null, true);
	wp_register_script( 'jquery-sticky', THEME_JS .'/vendor/jquery.sticky.js', array('jquery'), null, true);		
	wp_register_script( 'nice-scroll', THEME_JS . '/vendor/jquery.nicescroll.min.js', array('jquery'), null, true);
	wp_register_script( 'smooth-scroll', THEME_JS . '/vendor/SmoothScroll.min.js', array('jquery'), null, true);
	wp_register_script( 'cufon-yui', THEME_JS .'/vendor/cufon-yui.js', array('jquery'), null, true);
	wp_register_script( 'jquery-tweet', THEME_JS .'/vendor/jquery.tweet.js', array('jquery'), null, true);
	wp_register_script( 'jquery-flickr', THEME_JS .'/vendor/jquery.flickr.js', array('jquery'), null, true);
	wp_register_script( 'jquery-uisearch', THEME_JS .'/vendor/jquery.uisearch.js', array('jquery'), null, true);
	wp_register_script( 'jquery-isotope', THEME_JS .'/vendor/jquery.isotope.min.js', array('jquery'), null, true);
	wp_register_script( 'jquery-gmap-sensor', 'http://maps.google.com/maps/api/js?sensor=false', array('jquery'), null, true);
	wp_register_script( 'jquery-gmap', THEME_JS .'/vendor/jquery.mapmarker.js', array('jquery'), null, true);	
	
	wp_register_script( 'wt-validate', THEME_JS .'/vendor/validator/jquery.validate.js', array('jquery'), null, true);	
	$wt_locale = get_locale();
	$wt_spc_locales = array( 'pt_BR', 'pt_PT', 'zh_TW' );
	if ( ! in_array( $wt_locale, $wt_spc_locales ) ) {
		$wt_locale = current( explode( '_', $wt_locale ) );
	}
	if ($wt_locale != 'en') {
		wp_register_script( 'wt-validate-translation', THEME_JS . '/vendor/validator/localization/messages_' . $wt_locale . '.js', array('jquery','wt-validate'), null, true );
	}
	
	wp_register_script( 'theme-elastislide', THEME_JS .'/vendor/jquery.elastislide.js', array('jquery'), null);
	
	wp_register_script( 'mediaelementjs-scripts', THEME_URI .'/mediaelement/mediaelement-and-player.min.js', array('jquery'), null, true);
	wp_register_script( 'jquery-youtube', THEME_JS .'/vendor/jquery.mb.YTPlayer.js', array('mediaelementjs-scripts'), null, true);
	wp_register_script( 'ios6-bug', THEME_JS .'/vendor/ios6_bug.js', array('jquery'), null, true);
}
add_action('wp_print_scripts', 'wt_enqueue_scripts');


function wt_scripts() {
	echo "\n";
	wp_print_scripts('main');
	wp_print_scripts('plugins');
	if (class_exists('WPBakeryVisualComposerAbstract')) {
		wp_print_scripts( 'wt-visual-composer-extensions-front');
	}
	wp_print_scripts('fitvids');
	wp_print_scripts('bootstrap');
}

function wt_enqueue_styles(){
	if(is_admin()){
		return;
	}
	wp_enqueue_style('theme-boostrap', THEME_CSS.'/bootstrap.css', array(), null, 'all');
	wp_enqueue_style('theme-style', THEME_CSS.'/main.css', array(), null, 'all');
	if(wt_get_option('general','woocommerce')){
		wp_enqueue_style('theme-woocommerce', THEME_CSS.'/woocommerce.css', array('theme-style'), null, 'all');
	}
	
	// Initialising WhoaThemes Shortcodes Styles
	if (class_exists('WPBakeryVisualComposerAbstract')) {
		wp_enqueue_style( 'wt-visual-composer-extensions-front', THEME_URI. '/framework/shortcodes/assets/wt-visual-composer-extensions-front.css', array(), null, 'all');
	}
	
	wp_enqueue_style('theme-awesome', THEME_CSS.'/font-awesome.css', array(), null, 'all');
	wp_enqueue_style('theme-entypo', THEME_CSS.'/entypo-fontello.css', array(), null, 'all');
	//wp_register_style('theme-bx', THEME_CSS.'/jquery.bxslider.css', array(), null, 'all');
	if(wt_get_option('general','enable_animation')){
		wp_enqueue_style('theme-animation', THEME_CSS.'/animate.css', array('theme-style'), null, 'all');
	}
    wp_enqueue_style('theme-lightbox', THEME_CSS.'/prettyPhoto.css', array(), null, 'all');
	if(wt_get_option('general','enable_responsive')){
		wp_enqueue_style('theme-media-styles', THEME_CSS.'/main-media.css', array('theme-style'), null, 'all');
	}
	$skin_type = wt_get_option('general', 'skin_type');
	if($skin_type == 'dark'){	
		wp_enqueue_style('theme-dark', THEME_CSS.'/skins/dark.css', array(), null, 'all');
	}
	$skin = wt_get_option('general', 'skin');
	switch($skin){		
		case 'amethyst':
			wp_enqueue_style('theme-amethyst', THEME_CSS.'/skins/amethyst.css', array(), null, 'all');
			break;
		case 'bluesky':
			wp_enqueue_style('theme-bluesky', THEME_CSS.'/skins/bluesky.css', array(), null, 'all');
			break;
		case 'carrot':
			wp_enqueue_style('theme-carrot', THEME_CSS.'/skins/carrot.css', array(), null, 'all');
			break;
		case 'green':
			wp_enqueue_style('theme-green', THEME_CSS.'/skins/green.css', array(), null, 'all');
			break;	
		case 'orange':
			wp_enqueue_style('theme-orange', THEME_CSS.'/skins/orange.css', array(), null, 'all');
			break;	
		case 'pink':
			wp_enqueue_style('theme-pink', THEME_CSS.'/skins/pink.css', array(), null, 'all');
			break;
		case 'red':
			wp_enqueue_style('theme-red', THEME_CSS.'/skins/red.css', array(), null, 'all');
			break;	
		case 'turquoise':
			wp_enqueue_style('theme-turquoise', THEME_CSS.'/skins/turquoise.css', array(), null, 'all');
			break;	
		case 'yellow':
			wp_enqueue_style('theme-yellow', THEME_CSS.'/skins/yellow.css', array(), null, 'all');
			break;
		/*case 'black':
			wp_enqueue_style('theme-black', THEME_CSS.'/skins/black.css', array(), null, 'all');
			break;
		case 'yellow':
			wp_enqueue_style('theme-yellow', THEME_CSS.'/skins/yellow.css', array(), null, 'all');
			break;
		case 'red':
			wp_enqueue_style('theme-red', THEME_CSS.'/skins/red.css', array(), null, 'all');
			break;		
		case 'white':
			wp_enqueue_style('theme-light', THEME_CSS.'/skins/light.css', array(), null, 'all');
			break;*/
	}
	
	if(is_multisite()){
		global $blog_id;
		wp_enqueue_style('theme-skin', THEME_CACHE_URI.'/skin_'.$blog_id.'.css', array('theme-style'), false, 'all');
	}else{
		wp_enqueue_style('theme-skin', THEME_CACHE_URI.'/skin.css', array('theme-style'), false, 'all');
	}
	//wp_enqueue_style('theme-skin', THEME_CACHE_URI.'/skin.css', array('theme-style'), false, 'all');
		
	wp_register_style('mediaelementjs-styles', THEME_URI.'/mediaelement/mediaelementplayer.css', array(), null, 'all');
	wp_register_style('mediaelementjs-skin-styles', THEME_URI.'/mediaelement/mejs-skins.css', array(), null, 'all');
	
}
add_action('wp_enqueue_scripts', 'wt_enqueue_styles');

if(wt_get_option('fonts','enable_googlefonts')){
	function theme_add_googlefonts_lib(){
		$http = (!empty($_SERVER['HTTPS'])) ? "https" : "http";
		$fonts = wt_get_option('fonts','used_googlefonts');
		if(is_array($fonts)){
			foreach ($fonts as $font){
				wp_enqueue_style('font|'.$font,$http.'://fonts.googleapis.com/css?family='.$font.'&subset=latin,latin-ext');
			}
		}
	}
	add_action("wp_enqueue_scripts", 'theme_add_googlefonts_lib');
}
if(wt_get_option('fonts','enable_cufon')){
	function theme_add_cufon_script(){
		$fonts = wt_get_option('fonts','cufonfonts');
		if(is_array($fonts)){
			foreach ($fonts as $font){
				wp_register_script($font, THEME_FONT_URI .'/'.$font, array('cufon-yui'));
				wp_enqueue_script($font);
			}
		}
		wp_enqueue_script('cufon-yui');
	}
	add_filter('wp_enqueue_scripts','theme_add_cufon_script');	
}