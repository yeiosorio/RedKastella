<?php get_header(); ?>
</div> <!-- End headerWrapper -->
<div id="wt_containerWrapper" class="clearfix">
	<?php wt_theme_generator('wt_custom_header',$post->ID); ?>
	<?php wt_theme_generator('wt_containerWrapp',$post->ID);?>
        <div id="wt_container" class="clearfix">
            <?php wt_theme_generator('wt_content',$post->ID);?>
                <div class="container">
                   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                   <h2><?php _e('Perhaps this will help','wt_front');?></h2>
                    <ol>
                        <li><?php _e('Double check the web address for typos.','wt_front');?></li>
                        <li><?php _e('Head back to our home page via the main navigation.','wt_front');?></li>
                        <li><?php _e('Try using the serch box or our sitemap below.','wt_front');?></li>
                    </ol>
                       <?php get_search_form(); ?>
                      <div class="error_page">
                          <a href="" target="_self" class="wt_button small"><span>Back to Home</span></a>
                      </div>
    
                     </div>
                   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
                  <?php $skin = wt_get_option('general', 'skin'); ?>
                       <img src="<?php echo get_template_directory_uri(); ?>/css/skins/<?php echo $skin; ?>/404.png" alt="" />
                   </div>
                </div> <!-- End container -->
            </div> <!-- End wt_content -->
        </div> <!-- End wt_container -->
    </div> <!-- End wt_containerWrapp -->
</div> <!-- End wt_containerWrapper -->
<?php get_footer(); ?>