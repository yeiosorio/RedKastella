<?php 
/*
Template Name: Full Screen
*/ 
if(is_blog()){
	return require(THEME_DIR . "/template_blog.php");
}
$type = get_post_meta($post->ID, '_intro_type', true);
$intro_title = get_post_meta($post->ID, '_intro_title', true);
$intro_text = get_post_meta($post->ID, '_intro_text', true);
?>
<?php get_header(); ?>
</div> <!-- End headerWrapper -->
<div id="wt_containerWrapper" class="clearfix">
	<?php wt_theme_generator('wt_breadcrumbs',$post->ID); ?>
    <?php wt_theme_generator('wt_custom_header',$post->ID); ?>
    <?php wt_theme_generator('wt_containerWrapp',$post->ID);?>
        <?php
            if (!empty($intro_title) || !empty($intro_text)) {
                echo '<section class="wt_intro_section"><div class="intro_box wt_animate wt_animate_if_visible" data-animation="fadeInUp">';
                echo apply_filters('the_content', get_post_meta($post->ID, '_intro_title', true));
                echo apply_filters('the_content', get_post_meta($post->ID, '_intro_text', true));
                echo '</div></section>';
            } ?>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php the_content(); ?>
            <?php endwhile; else: ?>
        <?php endif; ?>
    </div> <!-- End wt_containerWrapp -->
</div> <!-- End wt_containerWrapper -->
<?php get_footer(); ?>