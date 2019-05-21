<?php
if(is_blog()){
	return require(THEME_DIR . "/template_blog.php");
}elseif(is_front_page()){
	return require(THEME_DIR . "/template_home.php");
}
$type = get_post_meta($post->ID, '_intro_type', true);
?>
<?php get_header(); ?>
</div> <!-- End headerWrapper -->
<div id="wt_containerWrapper" class="clearfix">
	<?php wt_theme_generator('wt_breadcrumbs',$post->ID); ?>
    <?php wt_theme_generator('wt_custom_header',$post->ID); ?>
    <?php wt_theme_generator('wt_containerWrapp',$post->ID);?>
        <div id="wt_container" class="clearfix">
            <?php wt_theme_generator('wt_content',$post->ID);?>
                <div class="container">
                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                        <?php if(has_post_thumbnail()): ?>
                        <div class="styled_image">
                            <?php the_post_thumbnail('full'); ?>
                        </div>
                        <?php endif; ?>
                         <?php 
                         the_content(); ?>
                    <?php endwhile; else: ?>
                    <?php endif; ?>
                </div> <!-- End container -->
            </div> <!-- End wt_content -->
        </div> <!-- End wt_container -->
	</div> <!-- End wt_containerWrapp -->
</div> <!-- End wt_containerWrapper -->
<?php get_footer(); ?>