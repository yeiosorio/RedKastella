<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 */
$featured_image_type = wt_get_option('blog', 'featured_image_type');
$layout=wt_get_option('blog','layout');

?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" class="blogEntry">
        <div <?php post_class(); ?>>
              
		<?php    
		if (wt_get_option('blog','meta_date') && !is_search() && has_post_thumbnail()){
			//echo '<div class="wt_dates"><div class="entry_date">';
			//echo '<a href="'.get_month_link(get_the_time('Y'), get_the_time('m')).'"><span class="day">'.get_the_time('d').'</span><span class="month">'.get_the_time('M').'</span></a></div>';
			//echo '<div class="post-category"><i class="fa fa-picture-o"></i> </div> </div>';
		}    
			$thumbnail_type = get_post_meta($post->ID, '_thumbnail_type', true);
				switch($thumbnail_type){					
					case "timage" : 
					if ( has_post_thumbnail() ) {
						echo '<header class="blogEntry_frame entry_' . $featured_image_type . '">  ';
						echo wt_theme_generator('wt_blog_featured_image',$featured_image_type,$layout); 
						echo '</header>';	
						}
						break;
					case "tvideo" : 
						$video_link = get_post_meta($post->ID, '_featured_video', true);
						echo '<header class="blogEntry_frame entry_' . $featured_image_type . '">  ';
						echo '<div class="blog-thumbnail-video">';
						echo wt_video_featured($video_link,$featured_image_type,$layout);
						echo '</div>';	
						echo '</header>';													
						break;
					case "tplayer" : 
						$player_link = get_post_meta($post->ID,'_thumbnail_player', true);
						echo '<header class="blogEntry_frame entry_' . $featured_image_type . '">  ';
						echo '<div class="blog-thumbnail-player">';
						echo wt_media_player($featured_image_type,$layout,$player_link);
						echo '</div>';	
						echo '</header>';													
						break;
					case "tslide" : 
						echo '<header class="blogEntry_frame entry_' . $featured_image_type . '">  ';
						echo '<div class="blog-thumbnail-slide">';
						echo wt_get_slide($featured_image_type,$layout);
						echo '</div>';	
						echo '</header>';													
						break;
				}
             ?>
        
        <div class="blogEntry_content">
        	<h2 class="blogEntry_title"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php printf( __("Permanent Link to %s", 'wt_front'), get_the_title() ); ?>"><?php the_title(); ?></a></h2>
        <?php if (!is_search()): ?>
        <footer class="blogEntry_metadata">
            <?php echo wt_theme_generator('wt_blog_meta'); ?>
        </footer>
        <?php endif; ?>
        <?php 
            if(wt_get_option('blog','display_full')):
                global $more;
                $more = 0;
                the_content(__('Read more &raquo;','wt_front'),false);
            else:
                the_excerpt();
        ?>
        <?php wp_link_pages( array( 'before' => '<div class="wp-pagenavi post_navi"><span class="page-links-title">' . __( 'Pages:', 'wt_front' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
        <a class="read_more_link" href="<?php the_permalink(); ?>"><?php echo __('Read more &raquo;','wt_front')?></a>
		<?php endif; ?>
        </div>
        <?php if ($featured_image_type == 'left') { echo '<div class="wt_clearboth"></div>'; } ?>        
        </div>
    </article>
<?php endwhile;wp_reset_postdata();?>