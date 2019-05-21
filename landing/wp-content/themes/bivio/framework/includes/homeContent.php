<div id="wt_wrapper" class="<?php if($layout=='right'):?>withSidebar rightSidebar<?php endif;?><?php if($layout=='left'):?>withSidebar leftSidebar<?php endif;?><?php if($layout=='full'):?> fullWidth<?php endif; ?><?php if($stickyHeader):?> wt_stickyHeader<?php endif; ?><?php if($noStickyOnSS):?> wt_noSticky_on_ss<?php endif; ?><?php if($type=='disable'):?> wt_intro_disabled<?php endif; ?><?php if($animations):?> wt_animations<?php endif; ?><?php if($menu_type == "top"):?> wt_nav_top<?php else:?> wt_nav_side<?php endif; ?> clearfix">
<div id="wt_page" class="<?php if(wt_get_option('general','layout_style')== 'wt_boxed'){echo 'wt_boxed';} else {echo 'wt_wide';} ?>">
<?php wt_theme_generator('wt_headerWrapper',$post->ID);?>
	<?php wt_theme_generator('wt_header',$post->ID);?>
    	<div class="container">
			<?php if(!wt_get_option('general','display_logo') && $custom_logo = wt_get_option('general','logo')): ?>			
                <div id="logo" class="navbar-header">
                    <?php if($enable_retina): ?>
                        <?php if(is_front_page()): ?>
                            <a class="navbar-brand" href="<?php echo home_url( '/' ); ?>"><img src="<?php echo wt_get_option('general','logo_alt'); ?>" data-at2x="<?php echo $retinaLogo; ?>" alt="" /></a>
                        <?php else:?>
                            <a class="navbar-brand" href="<?php echo home_url( '/' ); ?>"><img src="<?php echo $custom_logo_home; ?>" data-at2x="<?php echo $retinaLogo; ?>" alt="" /></a>
                        <?php endif; ?> 
                    <?php else:?>
                        <?php if(is_front_page()): ?>
                            <a class="navbar-brand" href="<?php echo home_url( '/' ); ?>"><img src="<?php echo wt_get_option('general','logo_alt'); ?>" alt="" /></a>
                        <?php else:?>
                            <a class="navbar-brand" href="<?php echo home_url( '/' ); ?>"><img src="<?php echo $custom_logo; ?>" alt="" /></a>
                        <?php endif; ?> 
                    <?php endif; ?> 
                </div>
            <?php else:?>
                <div id="logo" class="navbar-header">
                    <a class="navbar-brand" href="<?php echo home_url( '/' ); ?>"><?php echo wt_get_option('general','plain_logo'); ?></a>
                <?php if(wt_get_option('general','display_site_desc')){
                        $site_desc = get_bloginfo( 'description' );
                        if(!empty($site_desc)):?>
                        <div id="siteDescription"><?php bloginfo( 'description' ); ?></div>
                <?php endif;}?>
                </div>
            <?php endif; ?>  
            <div id="headerWidget"> <?php dynamic_sidebar(__('Header Area','wt_admin')); ?> </div> 
            <?php  		
            if ( $responsiveNav == 'drop_down' ) { 
                wp_enqueue_script('mobileMenu');
            }			
            ?> 
            <!-- Navigation -->
            <?php wt_theme_generator('wt_nav',$post->ID);?>      
            <?php  if ( has_nav_menu( 'primary-menu' ) ) {
                wt_theme_generator('wt_menu');
            } else {
            echo '<ul class="menu nav navbar-nav navbar-right">';
                $short_walker = new My_Page_Walker; wp_list_pages(array( 'walker' => $short_walker,'link_before' => '<span>','link_after' => '</span>','title_li' => '' ));
            echo '</ul>';
            }
            ?>
            </nav>
		</div> 	<!-- End container -->    
	</header> <!-- End header --> <?php echo "\n"; ?> 
</div> <!-- End headerWrapper -->
</div> <!-- End wt_page -->
</div> <!-- End wrapper -->