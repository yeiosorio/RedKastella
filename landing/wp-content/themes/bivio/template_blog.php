<?php 
/*
Template Name: Blog Page
*/
get_header(); ?>
<?php
global $blog_page_id, $r;
//$blog_page_id = wt_get_option('blog','blog_page');
//$post->ID = get_object_id($blog_page_id,'page');
?>
</div> <!-- End headerWrapper -->
<div id="wt_containerWrapper" class="clearfix">
    <?php wt_theme_generator('wt_breadcrumbs',$post->ID); ?>
	<?php wt_theme_generator('wt_custom_header',$post->ID); ?>
    <?php wt_theme_generator('wt_containerWrapp',$post->ID);?>
        <div id="wt_container" class="clearfix">
            <?php wt_theme_generator('wt_content',$post->ID);?>
                <div class="container">
                    <div class="row">
                    	<?php if($layout == 'full') {
                            echo '<div id="wt_content_inner" class="col-md-12">';
						}?>
						<?php if($layout == 'left') {
                            echo '<aside id="wt_sidebar" class="col-sm-3 col-md-3 col-lg-3 col-xs-12">';
                            get_sidebar(); 
                            echo '</aside> <!-- End wt_sidebar -->'; 
                        }?>
                        <?php if($layout != 'full') {
                            echo '<div id="wt_main" role="main" class="col-sm-9 col-md-9 col-lg-9 col-xs-12">'; 
                            echo '<div id="wt_mainInner">';
                        }?>
                            <?php 
                                $featured_image_type = wt_get_option('blog', 'featured_image_type');
								$layout = wt_get_option('blog','layout');
								
								?>
								<?php 
								$exclude_cats = wt_get_option('blog','exclude_categorys');
								$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
								if(is_array($exclude_cats)){
									foreach ($exclude_cats as $key => $value) {
										$exclude_cats[$key] = -$value;
									}
									$query = array(
										'post_type' => 'post',
										'cat' => implode(",",$exclude_cats),
										'category__and'       => '',
										'paged' => $paged
									);
								} else {
									$query = array(
										'post_type' => 'post',
										'category__and'       => '',
										'paged' => $paged
									);
								}
								$r = new WP_Query($query);
										while($r->have_posts()) {
											$r->the_post(); ?>
									<article id="post-<?php the_ID(); ?>" class="blogEntry">
										<div <?php post_class(); ?>>
											  
										<?php    
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
												//improved_trim_excerpt();
												the_excerpt();
										?>
										<?php wp_link_pages( array( 'before' => '<div class="wp-pagenavi post_navi"><span class="page-links-title">' . __( 'Pages:', 'wt_front' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
										<a class="read_more_link" href="<?php the_permalink(); ?>"><?php echo __('Read more &raquo;','wt_front')?></a>
										<?php endif; ?>
										</div>
										<?php if ($featured_image_type == 'left') { echo '<div class="wt_clearboth"></div>'; } ?>        
										</div>
									</article>
								<?php wp_reset_postdata(); }
								if (function_exists("wt_blog_pagenavi")) {
									wt_blog_pagenavi('','', $r, $paged);
								} 
								 ?>
                        <?php if($layout != 'full') {
                            echo '</div> <!-- End wt_mainInner -->'; 
                            echo '</div> <!-- End wt_main -->'; 
                        }?>
                        <?php if($layout == 'right') {
                            echo '<aside id="wt_sidebar" class="col-sm-3 col-md-3 col-lg-3 col-xs-12">';
                            get_sidebar(); 
                            echo '</aside> <!-- End wt_sidebar -->'; 
                        }?>                        
                    	<?php if($layout == 'full') {
                            echo '</div>'; // End "wt_content_inner" if layout is full
						}?>
                    </div> <!-- End row -->
                </div> <!-- End container -->
            </div> <!-- End wt_content -->
        </div> <!-- End wt_container -->
    </div> <!-- End wt_containerWrapp -->
</div> <!-- End wt_containerWrapper -->
<?php get_footer(); ?>