<?php get_header(); ?>
<?php
$layout = wt_get_option('general','layout');
$slogan = wt_get_option('general','intro_slogan');
$slogan_button_text = wt_get_option('general','intro_button_text');
$slogan_button_link = wt_get_option('general','intro_button_link');
$stype = wt_get_option('general','slideshow_type');
?>
</div> <!-- End headerWrapper -->
<div id="wt_containerWrapper" class="clearfix">
	<?php wt_theme_generator('wt_containerWrapp',$post->ID); ?>
            
        <div class="wt_section_area">	
            <div class="container">
                 <?php get_template_part( 'loop','blog'); ?>
            </div>
        </div>
    
    <?php if(!get_post_meta($post->ID, '_enable_fullcontact', true)): ?>
    </div> <!-- End wt_containerWrapp -->
</div> <!-- End wt_containerWrapper -->
<?php endif; ?>



<?php get_footer(); ?>