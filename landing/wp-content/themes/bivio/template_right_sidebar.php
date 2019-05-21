<?php
/*
Template Name: Right Sidebar
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
        <div id="wt_container" class="clearfix">
            <?php wt_theme_generator('wt_content',$post->ID);?>
				<?php
                if (!empty($intro_title) || !empty($intro_text)) {
                    echo '<div class="intro_box wt_animate wt_animate_if_visible" data-animation="fadeInUp">';
                    echo apply_filters('the_content', get_post_meta($post->ID, '_intro_title', true));
                    echo apply_filters('the_content', get_post_meta($post->ID, '_intro_text', true));
                    echo '</div>';
                } ?>
                <div class="container">
                    <div class="row">
                        <div id="wt_main" role="main" class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                            <div id="wt_mainInner">
                                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                                    <?php if(has_post_thumbnail()): ?>
                                    <div class="styled_image">
                                        <?php the_post_thumbnail('full'); ?>
                                    </div>
                                    <?php endif; ?>
                                     <?php the_content(); ?>
                                <?php endwhile; else: ?>
                                <?php endif; ?>
                            </div>  <!-- End wt_mainInner -->
                        </div> <!-- End wt_main -->
                        <aside id="wt_sidebar" class="col-sm-3 col-md-3 col-lg-3 col-xs-12">
                        <?php get_sidebar(); ?>
                        </aside>  <!-- End wt_sidebar -->	
                   </div> <!-- End row -->
                </div> <!-- End container -->
            </div> <!-- End wt_content -->
        </div> <!-- End wt_container -->
    </div> <!-- End wt_containerWrapp -->
</div> <!-- End wt_containerWrapper -->
<?php get_footer(); ?>