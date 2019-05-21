<?php
/*
Template Name: Contact Page
*/
if(is_blog()){
	return require(THEME_DIR . "/template_blog.php");
}
?>
<?php get_header(); ?>
</div> <!-- End headerWrapper -->
<div id="wt_containerWrapper" class="clearfix">
	<?php wt_theme_generator('wt_breadcrumbs',$post->ID); ?>
    <?php wt_theme_generator('wt_custom_header',$post->ID); ?>
    <?php wt_theme_generator('wt_containerWrapp',$post->ID);?>
        <div id="wt_container" class="clearfix">
        	<?php echo apply_filters('the_content', get_post_meta($post->ID, '_fullcontact_gmap', true)); ?>
            <div class="container wt_contact">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                     <?php the_content(); ?>
                <?php endwhile; else: ?>
                <?php endif; ?>
            </div> <!-- End container -->
        </div> <!-- End wt_container -->
    </div> <!-- End wt_containerWrapp -->
</div> <!-- End wt_containerWrapper -->
<?php get_footer(); ?>