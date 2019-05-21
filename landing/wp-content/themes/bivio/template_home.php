<?php 
if(is_blog()){
	return require(THEME_DIR . "/template_blog.php");
} ?>
<?php
$bgType 	 = wt_get_option('background','background_type');
$slide_bg_1  = wt_get_option('background','slide_bg_1');
$slide_bg_2  = wt_get_option('background','slide_bg_2');
$slide_bg_3  = wt_get_option('background','slide_bg_3');
$slide_bg_4  = wt_get_option('background','slide_bg_4');
$slide_bg_5  = wt_get_option('background','slide_bg_5');
$video_bg 	 = wt_get_option('background','video_link');
$overlayType = wt_get_option('general','overlay_type');
?>
<!DOCTYPE html>
<?php 
if(wt_get_option('general','enable_responsive')){ 
	$responsive = 'responsive ';
} else {
	$responsive = '';
}
$niceScroll   = wt_get_option('general', 'nice_scroll');
$smoothScroll = wt_get_option('general', 'smooth_scroll');
if($smoothScroll) {
	$niceScroll = false; // disable nicescroll if smoothScroll is enabled
}
?>
<!--[if lt IE 7]> <html class="<?php echo $responsive; ?>no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="<?php echo $responsive; ?>no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="<?php echo $responsive; ?>no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<html class="<?php echo $responsive; ?><?php if($niceScroll):?>wt-nice-scrolling <?php endif; ?><?php if($smoothScroll):?>wt-smooth-scrolling <?php endif; ?>no-js" <?php language_attributes(); ?>>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php wt_theme_generator('wt_title'); ?></title>
<?php if(wt_get_option('general','enable_responsive')){ ?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<?php } ?>
<?php 
if($favicon = wt_get_option('general','favicon')) { ?>
<link rel="shortcut icon" href="<?php echo wt_get_image_src($favicon); ?>" />
<?php } 
if($favicon_57 = wt_get_option('general','favicon_57')) { ?>
<link rel="apple-touch-icon" href="<?php echo wt_get_image_src($favicon_57); ?>" />
<?php } 
if($favicon_72 = wt_get_option('general','favicon_72')) { ?>
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo wt_get_image_src($favicon_72); ?>" />
<?php } 
if($favicon_114 = wt_get_option('general','favicon_114')) { ?>
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo wt_get_image_src($favicon_114); ?>" />
<?php } 
if($favicon_144 = wt_get_option('general','favicon_144')) { ?>
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo wt_get_image_src($favicon_144); ?>" />
<?php } ?>
<!--[if lt IE 10]>
    <style type="text/css">
    html:not(.is_smallScreen) .wt_animations .wt_animate { visibility:visible;}
    </style>
<![endif]-->
<?php wp_head(); ?>
</head>
<?php
if (is_blog()){
global $blog_page_id;
global $post;
$blog_page_id = wt_get_option('blog','blog_page');
$post->ID = get_object_id($blog_page_id,'page');
}
/* Site Alignement  */
if (get_post_meta($post->ID, '_site_alignment', true)) {
	$alignment = get_post_meta($post->ID, '_site_alignment', true);	
}	
else {
	$alignment = wt_get_option('general', 'site_alignment');
}

/* Sidebar Alignement  */
require_once (THEME_FILES . '/layout.php');

$type          = get_post_meta($post->ID, '_intro_type', true);
$stickyHeader  = wt_get_option('general', 'sticky_header');
$noStickyOnSS  = wt_get_option('general', 'no_sticky_on_ss');
$retinaLogo    = wt_get_option('general', 'logo_retina');
$enable_retina = wt_get_option('general', 'enable_retina');
$animations    = wt_get_option('general','enable_animation');
$pageLoader    = wt_get_option('general','page_loader');
$responsiveNav = wt_get_option('general', 'responsive_nav');
$menu_type     = 'top';
$bg            = wt_check_input(get_post_meta($post->ID, '_page_bg', true));
$bg_position   = get_post_meta($post->ID, '_page_position_x', true);
$bg_repeat     = get_post_meta($post->ID, '_page_repeat', true);

$color = get_post_meta($post->ID, '_page_bg_color', true);
if(!empty($color) && $color != "transparent"){
	$color = 'background-color:'.$color.';';
}else{
	$color = '';
}
if(!empty($bg)){
	$bg = 'background-image:url('.$bg.');background-position:top '.$bg_position.';background-repeat:'.$bg_repeat.'';
}else{
	$bg = '';
}
if ($stickyHeader) {
	wp_enqueue_script('jquery-sticky');
}
if($smoothScroll) {
	wp_enqueue_script('smooth-scroll');
}
if ($niceScroll) {
	wp_enqueue_script('nice-scroll');
}
?>
<body <?php if($alignment=='right'):?>id="right_alignment"<?php endif;?><?php if($alignment=='left'):?>id="left_alignment" <?php endif;?><?php body_class(); ?>  <?php if(!empty($color) || !empty($bg)){echo' style="'.$color.''.$bg.'"';} ?> <?php if($niceScroll):?> data-nice-scrolling="1"<?php endif; ?>>
<?php if($pageLoader){ ?>
    <div id="wt_loader"><div class="wt_loader_html"></div></div>
<?php } ?>
<?php 
if(wt_get_option('fonts','enable_cufon')){
	wt_add_cufon_code();
} ?>
<?php if($bgType == 'pattern') :?>
<section id="wt_section_home" class="wt_pattern<?php if($animations):?> wt_animations <?php endif;?><?php if($noStickyOnSS):?>wt_noSticky_on_ss_home<?php endif; ?>">
<?php require_once (THEME_FILES . '/homeContent.php'); ?>  
  <?php if($overlayType =='pattern') :?>
      <div class="wt_pattern_overlay"></div>
  <?php endif; ?>
   <?php if($overlayType =='color') :?>
      <div class="wt_color_overlay"></div>
  <?php endif; ?>
    <div id="wt_home_content">
        <div class="container">
            <div class="row">
			   <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                 <?php //wt_theme_generator('wt_custom_title',$post->ID); ?>
                     <?php the_content(); ?>
                <?php endwhile; else: ?>
                <?php endif; ?>  
            </div>
        </div>
    </div>
</section>
<?php elseif($bgType == 'image_bg') :?>
<section id="wt_section_home" class="<?php if($animations):?>wt_animations <?php endif; ?><?php if($noStickyOnSS):?>wt_noSticky_on_ss_home<?php endif; ?>">
<?php require_once (THEME_FILES . '/homeContent.php'); ?> 
  <?php if($overlayType =='pattern') :?>
      <div class="wt_pattern_overlay"></div>
  <?php endif; ?>
   <?php if($overlayType =='color') :?>
      <div class="wt_color_overlay"></div>
  <?php endif; ?>
  <div id="wt_home_content">
    <div class="container">
        <div class="row">
           <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				 <?php the_content(); ?>
            <?php endwhile; else: ?>
            <?php endif; ?>  
        </div>
    </div>
  </div>
</section>
<?php elseif($bgType == 'video') :?>
<?php
wp_enqueue_script('jquery-youtube');
?>
<section id="wt_section_home" class="<?php if($animations):?>wt_animations <?php endif; ?><?php if($noStickyOnSS):?>wt_noSticky_on_ss_home<?php endif; ?>">
<?php require_once (THEME_FILES . '/homeContent.php'); ?> 
  <div class="wt_bg_video_mobile" style="background-image: url(<?php echo wt_get_option('background','video_mobile_bg'); ?>);"></div>
  <div class="wt_bg_video">
	<a id="bgndVideo_home" class="wt_youtube_player" data-property="{videoURL:'<?php echo $video_bg; ?>', containment:'body', autoPlay:true, mute:true, startAt:0, opacity:1, ratio:'4/3', addRaster:true, showControls:false}"></a>
   </div>
  <?php if($overlayType =='pattern') :?>
      <div class="wt_pattern_overlay"></div>
  <?php endif; ?>
   <?php if($overlayType =='color') :?>
      <div class="wt_color_overlay"></div>
  <?php endif; ?>
  <a class="video-volume" onclick="jQuery('#bgndVideo_home').toggleVolume()"><i class="fa fa-volume-down"></i></a>
  <div id="wt_home_content">
    <div class="container">
        <div class="row">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				 <?php the_content(); ?>
            <?php endwhile; else: ?>
            <?php endif; ?>  
        </div>
    </div>
  </div>
</section>
<?php elseif($bgType == 'slideshow') :?>
<?php
wp_enqueue_script( 'jquery-supersized');
wp_enqueue_script( 'jquery-supersized-shutter');
?>
<section id="wt_section_home" class="<?php if($animations):?>wt_animations <?php endif; ?><?php if($noStickyOnSS):?>wt_noSticky_on_ss_home<?php endif; ?>">
<?php require_once (THEME_FILES . '/homeContent.php'); ?> 
  <div class="wt_fullscreen_slider" data-images='["<?php if(!empty($slide_bg_1)) { echo $slide_bg_1 .'"'; } ?><?php if(!empty($slide_bg_2)) { echo ', "' . $slide_bg_2 .'"'; } ?><?php if(!empty($slide_bg_3)) { echo ', "' . $slide_bg_3 .'"';  }?><?php if(!empty($slide_bg_4)) { echo ', "' . $slide_bg_4 .'"'; } ?><?php if(!empty($slide_bg_5)) { echo ', "' . $slide_bg_5 .'"'; } ?>]' data-autoplay="true" data-slideinterval="7000" data-transitionspeed="1500" data-transition="1">

  <!-- progress -->
    <!-- <div id="progress-back" class="load-item">
      <div id="progress-bar"></div>
    </div>
 -->

  </div>
  <?php if($overlayType =='pattern') :?>
      <div class="wt_pattern_overlay"></div>
  <?php endif; ?>
   <?php if($overlayType =='color') :?>
      <div class="wt_color_overlay"></div>
  <?php endif; ?>
  <div id="wt_home_content">
    <div class="container">
        <div class="row">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				 <?php the_content(); ?>
            <?php endwhile; else: ?>
            <?php endif; ?>  



        </div>
    </div>
  </div>
</section>
<?php endif; ?>
<script type="text/javascript">
/* <![CDATA[ */
var theme_uri="<?php echo THEME_URI;?>";
/* ]]> */
</script>
<?php
wt_scripts();
wt_add_cufon_code_footer();
if(wt_get_option('general','analytics')){
	echo stripslashes(wt_get_option('general','analytics'));
}



  /**
   * Directorio de archivos
   * @var [type]
   */
   $uploads = wp_upload_dir();

   $uploadsBaseUrl = $uploads['baseurl'];

?>


<div id="wt_footerWrapper" class="clearfix" >

<footer id="wt_footerBottom_main" class="clearfix"><div class="container">          

<div class="vc_row wpb_row vc_row-fluid ">
  <div class="vc_col-sm-12 wpb_column vc_column_container">
    <div class="wt_wpb_wrapper wt-background-image clearfix" style="background-position: top center;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;background-repeat: no-repeat;">
      <div class="vc_row wpb_row vc_inner vc_row-fluid">


<div class="wpb_column vc_column_container vc_col-sm-1">
  <div class="vc_column-inner ">
    <div class="wpb_wrapper">
      
    </div>
  </div>
</div>


      <div class="wpb_column vc_column_container vc_col-sm-2">
      <div class="vc_column-inner ">
      <div class="wpb_wrapper">
  <div class="wpb_single_image wpb_content_element vc_align_left">
    
    <figure class="wpb_wrapper vc_figure">

      <a href="https://www.dnp.gov.co/" target="_blank" class="vc_single_image-wrapper   vc_box_border_grey">
    
      <img 
        width="300" 
        height="119" 
        style="width: 80%" 
        src="<?php echo $uploadsBaseUrl; ?>/2014/09/nuevopais-300x119.png" 
        class="vc_single_image-img attachment-medium" 
        alt="nuevopais" 
        srcset="<?php echo $uploadsBaseUrl; ?>/2014/09/nuevopais-300x119.png 300w, <?php echo $uploadsBaseUrl; ?>/2014/09/nuevopais.png 694w" 
        sizes="(max-width: 300px) 100vw, 300px"
      >
        
      </a>

    </figure>
  </div>
</div></div></div><div class="wpb_column vc_column_container vc_col-sm-2"><div class="vc_column-inner "><div class="wpb_wrapper">
  <div class="wpb_single_image wpb_content_element vc_align_left">
    
    <figure class="wpb_wrapper vc_figure">
      <a href="http://www.mintic.gov.co/" target="_blank" class="vc_single_image-wrapper   vc_box_border_grey">
      
      <img 
        width="300" 
        height="91" 
        style="width: 90%; margin-top: 7px;"
        src="<?php echo $uploadsBaseUrl; ?>/2014/09/MinTIC_Colombia_logo-300x91.png" 
        class="vc_single_image-img attachment-medium" 
        alt="MinTIC_(Colombia)_logo" 
        srcset="<?php echo $uploadsBaseUrl; ?>/2014/09/MinTIC_Colombia_logo-300x91.png 300w, <?php echo $uploadsBaseUrl; ?>/2014/09/MinTIC_Colombia_logo-768x233.png 768w, <?php echo $uploadsBaseUrl; ?>/2014/09/MinTIC_Colombia_logo-1024x311.png 1024w, <?php echo $uploadsBaseUrl; ?>/2014/09/MinTIC_Colombia_logo.png 1071w" 
        sizes="(max-width: 300px) 100vw, 300px" >
      </a>
    </figure>
  </div>
</div></div></div><div class="wpb_column vc_column_container vc_col-sm-2"><div class="vc_column-inner "><div class="wpb_wrapper">
  <div class="wpb_single_image wpb_content_element vc_align_left">
    
    <figure class="wpb_wrapper vc_figure">
      <a href="http://www.vivedigital.gov.co/" target="_blank" class="vc_single_image-wrapper   vc_box_border_grey">
        <img
        width="300"
        height="69"
        style="width: 90%; margin-top: 15px;"
        src="<?php echo $uploadsBaseUrl; ?>/2014/09/customLogo-300x69.png" class="vc_single_image-img attachment-medium" alt="customLogo" srcset="<?php echo $uploadsBaseUrl; ?>/2014/09/customLogo-300x69.png 300w, <?php echo $uploadsBaseUrl; ?>/2014/09/customLogo-768x177.png 768w, <?php echo $uploadsBaseUrl; ?>/2014/09/customLogo.png 800w" sizes="(max-width: 300px) 100vw, 300px">
      </a>
    </figure>
  </div>
</div></div></div><div class="wpb_column vc_column_container vc_col-sm-2"><div class="vc_column-inner "><div class="wpb_wrapper">
  <div class="wpb_single_image wpb_content_element vc_align_left">
    
    <figure class="wpb_wrapper vc_figure">
      <a href="http://www.colciencias.gov.co/pf-colciencias/" target="_blank" class="vc_single_image-wrapper   vc_box_border_grey">
      
      <img width="300" height="112" src="<?php echo $uploadsBaseUrl; ?>/2014/09/Logo-Colciencias-paginaweb2015-300x112.png" class="vc_single_image-img attachment-medium" alt="Logo-Colciencias-paginaweb2015" srcset="<?php echo $uploadsBaseUrl; ?>/2014/09/Logo-Colciencias-paginaweb2015-300x112.png 300w, <?php echo $uploadsBaseUrl; ?>/2014/09/Logo-Colciencias-paginaweb2015.png 324w" sizes="(max-width: 300px) 100vw, 300px">
      </a>
    </figure>
  </div>
</div></div></div><div class="wpb_column vc_column_container vc_col-sm-2"><div class="vc_column-inner "><div class="wpb_wrapper">
  <div class="wpb_single_image wpb_content_element vc_align_left">
    
    <figure class="wpb_wrapper vc_figure">

     <a href="http://augustaconsultores.com/" target="_blank" class="vc_single_image-wrapper   vc_box_border_grey">

      <img 
        width="300" 
        height="101" 
        style="width: 75%; margin-top: 7px;" 
        src="<?php echo $uploadsBaseUrl; ?>/2014/09/logo1-300x101.png" 
        class="vc_single_image-img attachment-medium" alt="logo1" srcset="<?php echo $uploadsBaseUrl; ?>/2014/09/logo1-300x101.png 300w, <?php echo $uploadsBaseUrl; ?>/2014/09/logo1-465x157.png 465w, <?php echo $uploadsBaseUrl; ?>/2014/09/logo1.png 467w" sizes="(max-width: 300px) 100vw, 300px">
     </a> 
    </figure>
  </div>
</div></div></div></div>



<div class="vc_row wpb_row vc_inner vc_row-fluid" style="margin-top: 10px;">
<div class="wpb_column vc_column_container vc_col-sm-2">
<div class="vc_column-inner "><div class="wpb_wrapper"></div></div>
</div>

<div class="wpb_column vc_column_container vc_col-sm-1">
  <div class="vc_column-inner ">
    <div class="wpb_wrapper">
      
    </div>
  </div>
</div>

<div class="wpb_column vc_column_container vc_col-sm-2"><div class="vc_column-inner "><div class="wpb_wrapper">
  <div class="wpb_single_image wpb_content_element vc_align_left">
    
    <figure class="wpb_wrapper vc_figure">
      <a href="http://www.eljardin-antioquia.gov.co/" target="_blank" class="vc_single_image-wrapper   vc_box_border_grey">

        <img 
          width="100" 
          height="77" 
          style="width: 78%; padding-left: 35px; margin-top: -6px;" 
          src="<?php echo $uploadsBaseUrl; ?>/2014/09/logo-de-jardin_01.png" 
          class="vc_single_image-img attachment-thumbnail" 
          alt="logo-de-jardin_1"
        >
      </a>
    </figure>
  </div>
</div></div></div>


<div class="wpb_column vc_column_container vc_col-sm-2" >
<div class="vc_column-inner "><div class="wpb_wrapper">
  <div class="wpb_single_image wpb_content_element vc_align_center">
    
    <figure class="wpb_wrapper vc_figure">
      <a href="http://www.genova-quindio.gov.co/index.shtml" target="_blank" class="vc_single_image-wrapper   vc_box_border_grey">
      <img 
      width="100" 
      height="100" 
      style="width: 76%; margin-top: -5px;" 
      src="<?php echo $uploadsBaseUrl; ?>/2014/09/Génova02.png" 
      class="vc_single_image-img attachment-medium" alt="Génova02" srcset="<?php echo $uploadsBaseUrl; ?>/2014/09/Génova02-55x55.png 55w, <?php echo $uploadsBaseUrl; ?>/2014/09/Génova02.png 100w" sizes="(max-width: 100px) 100vw, 100px"></a>
    </figure>
  </div>
</div></div></div><div class="wpb_column vc_column_container vc_col-sm-2"><div class="vc_column-inner "><div class="wpb_wrapper">
  <div class="wpb_single_image wpb_content_element vc_align_center">
    
    <figure class="wpb_wrapper vc_figure">
      <a href="http://www.armenia.gov.co/" target="_blank" class="vc_single_image-wrapper   vc_box_border_grey">
      <img 
      width="110" 
      height="77" 
      style="width: 85%; padding-left: 10px; margin-top: -5px;" 
      src="<?php echo $uploadsBaseUrl; ?>/2014/09/ARMENIA01.png" class="vc_single_image-img attachment-large" alt="ARMENIA01"></a>
    </figure>
  </div>
</div></div></div><div class="wpb_column vc_column_container vc_col-sm-2"><div class="vc_column-inner "><div class="wpb_wrapper"></div></div></div><div class="wpb_column vc_column_container vc_col-sm-2"><div class="vc_column-inner "><div class="wpb_wrapper"></div></div></div></div>

    </div>
  </div> 
</div>
              
  </div> <!-- End container -->
</footer> <!-- End footerBottom -->


</div>














<?php
wp_footer();
?>
</body>
</html>










