<?php
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
add_action( 'woocommerce_before_main_content', 'theme_woocommerce_output_content_wrapper', 10);
add_action( 'woocommerce_after_main_content', 'theme_woocommerce_output_content_wrapper_end', 10);

function theme_woocommerce_output_content_wrapper() {
$layout = wt_get_option('general','woo_layout');	
if ($layout != 'full') {
	$product_class = 'wt_product_columns_3 ';
} else {
	$product_class = 'wt_product_columns_4 '; }
?>
</div> <!-- End headerWrapper -->
<div id="wt_containerWrapper" class="clearfix">
<div id="containerWrapp" class="clearfix">
<?php wt_theme_generator('wt_breadcrumbs',get_queried_object_id());?>
<?php wt_theme_generator('wt_custom_header',get_queried_object_id());?>
	<div id="wt_container" class="<?php echo $product_class; ?>clearfix">
    	<?php wt_theme_generator('wt_content',get_queried_object_id());?>
            <div class="container">
            	<div class="row">
                 <?php if(wt_get_option('general','woo_layout') == 'full') {
                    echo '<div  class="col-sm-12">'; 
				  }?> 
					<?php if(wt_get_option('general','woo_layout') == 'left') {
                            echo '<aside id="wt_sidebar" class="col-sm-3 col-md-3 col-lg-3 col-xs-12">';
                            get_sidebar(); 
                            echo '</aside> <!-- End wt_sidebar -->'; 
                        }?>
                    <?php if(wt_get_option('general','woo_layout') != 'full') {
                            echo '<div id="wt_main" role="main" class="col-lg-9 col-md-9 col-sm-9 col-xs-12">'; 
                            echo '<div id="wt_mainInner">';
                        }?> 
                   <?php
        }
        
        function theme_woocommerce_output_content_wrapper_end() {	
        ?>
                    <?php if(wt_get_option('general','woo_layout') != 'full') {
                            echo '</div> <!-- End wt_mainInner -->'; 
                            echo '</div> <!-- End wt_main -->'; 
                        }?>
                       
				   <?php if(wt_get_option('general','woo_layout') == 'right') {
                        echo '<aside id="wt_sidebar" class="col-sm-3 col-md-3 col-lg-3 col-xs-12">';
                        get_sidebar(); 
                        echo '</aside> <!-- End wt_sidebar -->'; 
                    }?>         
                 <?php if(wt_get_option('general','woo_layout') == 'full') {
                    echo '</div>'; 
				  }?> 
               </div> <!-- End wt_row -->
            </div> <!-- End container -->	
		</div> <!-- End wt_content -->
	</div> <!-- End wt_container -->
</div> <!-- End containerWrapper -->
</div> <!-- End containerWrapp -->
<?php //get_footer(); ?>

<?php
}

if (!function_exists('woocommerce_breadcrumb')) {
	function woocommerce_breadcrumb( $args = array() ) {
		
		$defaults = array(
			'delimiter' 	=> ' &rsaquo; ',
			'wrap_before' 	=> '<section id="breadcrumbs">',
			'wrap_after'	=> '</section>',
			'before' 		=> '',
			'after' 		=> '',
			'home' 			=> null
		);

		$args = wp_parse_args( $args, $defaults );

		//woocommerce_get_template('shop/breadcrumb.php', $args);
	}
}