<?php 
get_header(); ?>
</div> <!-- End headerWrapper -->
<div id="wt_containerWrapper" class="clearfix">
    <?php 
	$title = __('Search','wt_front');
	$text = sprintf(__('Search Results for: "%s"','wt_front'),stripslashes( strip_tags( get_search_query() ) ));
	echo '<header id="wt_intro" class="clearfix">';
	echo "\n\t\t".'<div class="container">'."\n";
		echo "\t\t\t".'<div id="introType" class="wt_intro"><div class="intro_text">';
		if (isset($title)) {
			echo '<h1>' . $title . '</h1>';
		}
		if (isset($text)) {
			echo '<h3 class="custom_title">'.$text.'</h3>';
		}
		echo "</div></div>\n\t\t";
		echo "</div>\n\t";
		echo "</header>\n";
	?>
	<div id="wt_containerWrapp" class="clearfix">
        <div id="wt_container" class="clearfix">
           <div id="wt_content">
                <div class="container">
                    <div class="row">
                    <?php wt_theme_generator('wt_breadcrumbs'); ?>
                    <?php if($layout != 'full') {
                        echo '<div id="wt_main" role="main" class="col-lg-9 col-md-9 col-sm-9 col-xs-12">'; 
                        echo '<div id="wt_mainInner">';
                    }?> 
                    <?php 
                        $page = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        $s = get_query_var('s');
                        query_posts("s=$s&paged=$page&cat=");
                        
                        get_template_part( 'loop','search');
                    ?>
                    <?php if (function_exists("wt_pagination")) {
                        wt_pagination();
                    } ?>
                    <?php if($layout != 'full') {
                        echo '</div> <!-- End wt_mainInner -->'; 
                        echo '</div> <!-- End wt_main -->'; 
                    }?>
                    
                    <?php if($layout != 'full') {
                        echo '<aside id="wt_sidebar" class="col-sm-3 col-md-3 col-lg-3 col-xs-12">';
                        get_sidebar(); 
                        echo '</aside> <!-- End wt_sidebar -->'; 
                    }?>
                    </div> <!-- End row -->
                </div> <!-- End container -->
            </div> <!-- End wt_content -->
        </div> <!-- End wt_container -->
    </div> <!-- End wt_containerWrapp -->
</div> <!-- End wt_containerWrapper -->
<?php get_footer(); ?>