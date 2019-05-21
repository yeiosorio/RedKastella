<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 */
$featured_image_type = wt_get_option('portfolio', 'featured_image_type');
$layout=wt_get_option('portfolio','layout');

?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" class="blogEntry">
        <div <?php post_class(); ?>>              
		
		<?php if(wt_get_option('portfolio','featured_image')):?>
			<?php wt_theme_generator('wt_portfolio_featured_image',$layout); ?>
        <?php endif; ?>
        
        <?php if (!is_search()): ?>
        <footer class="blogEntry_metadata">
            <?php echo wt_theme_generator('wt_blog_meta'); ?>
        </footer>
        <?php endif; ?>
        <div class="blogEntry_content">        
        	<h2 class="blogEntry_title"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php printf( __("Permanent Link to %s", 'wt_front'), get_the_title() ); ?>"><?php the_title(); ?></a></h2>
        <?php 
            if(wt_get_option('blog','display_full')):
                global $more;
                $more = 0;
                the_content(__('Read more &raquo;','wt_front'),false);
            else:
                the_excerpt();
        ?>
        <a class="read_more_link" href="<?php the_permalink(); ?>"><?php echo __('Read more &raquo;','wt_front')?></a>
<?php endif; ?>
        </div>
        <?php if ($featured_image_type == 'left') { echo '<div class="wt_clearboth"></div>'; } ?>        
        </div>
    </article>
<?php endwhile;wp_reset_postdata();?>