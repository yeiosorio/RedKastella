<?php

$layout = wt_get_option('general','woo_layout');	
	if ($layout != 'full') {
		add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 9;' ), 20 ); // Display 12 products per page.
	} else {
		add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 ); // Display 12 products per page.
}

function remove_loop_button(){
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
}
add_action('init','remove_loop_button');

add_filter( 'woocommerce_page_title', 'woo_shop_page_title');

function woo_shop_page_title( $page_title ) {
	
	if( 'Shop' == $page_title) {
		return "";
	}
}
remove_action('woocommerce_single_product_summary','woocommerce_template_single_title',5);
    add_action('woocommerce_single_product_summary', 'woocommerce_my_single_title',5);

    if ( ! function_exists( 'woocommerce_my_single_title' ) ) {
    function woocommerce_my_single_title() {
        ?>
            <h2 itemprop="name" class="product_title entry-title"><?php the_title(); ?></h2>
        <?php
    }
}

// Change number or products per row to 3
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		$layout = wt_get_option('general','woo_layout');	
			if ($layout != 'full') {
				return 3; // 3 products per row
			} else {
				return 4; // 3 products per row; 
		}
	}
}

/**
 * WooCommerce Extra Feature
 * --------------------------
 *
 * Change number of related products on product page
 * Set your own value for 'posts_per_page'
 *
 */ 
add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args' );
  function jk_related_products_args( $args ) {
 
	$layout = wt_get_option('general','woo_layout');	
		if ($layout != 'full') {
			$args['posts_per_page'] = 3; // 4 related products
			$args['columns'] = 3; // arranged in 2 columns

		} else {
			$args['posts_per_page'] = 4; // 4 related products
			$args['columns'] = 4; // arranged in 4 columns
		}
	return $args;
}

/**
 * Hook in on activation
 */
global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action( 'init', 'yourtheme_woocommerce_image_dimensions', 1 );
 
#
# removes the default post image from shop overview pages and replaces it with this image
#

add_action( 'woocommerce_before_shop_loop_item_title', 'wt_woocommerce_thumbnail', 10);
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

function wt_woocommerce_thumbnail($asdf)
{
	global $product;
	
	$id = get_the_ID();
	$size = 'shop_catalog';
	
	echo "<div class='thumbnail_container'>";
		echo wt_woocommerce_gallery_first_thumbnail( $id , $size);
		echo get_the_post_thumbnail( $id , $size );
		if($product->product_type == 'simple') echo "<span class='cart-loading'></span>";
	echo "</div>";
}


function wt_woocommerce_gallery_first_thumbnail($id, $size) {
	$active_hover = get_post_meta( $id, '_product_hover', true );

	if($active_hover == 'hover_active')
	{
		$product_gallery = get_post_meta( $id, '_product_image_gallery', true );
		
		if(!empty($product_gallery))
		{
			$gallery	= explode(',',$product_gallery);
			$image_id 	= $gallery[0];
			$image 		= wp_get_attachment_image( $image_id, $size, false, array( 'class' => "attachment-$size wt-product-hover" ));
			
			if(!empty($image)) return $image;
		}
	}
}

add_action( 'woocommerce_after_shop_loop_item', 'wt_add_cart_button', 16);
function wt_add_cart_button()
{
	global $product;

	if ($product->product_type == 'bundle' ){
		$product = new WC_Product_Bundle($product->id);
	}

	$extraClass  = "";

	ob_start();
	woocommerce_template_loop_add_to_cart();
	$output = ob_get_clean();

	if(!empty($output))
	{
		$pos = strpos($output, ">");
		
		if ($pos !== false) {
		    $output = substr_replace($output,"><i class='entypo-basket'></i> ", $pos , strlen(1));
		}
	}

	if($product->product_type == 'simple')
	{
		$output .= "<a class='button show_details_button' href='".get_permalink($product->id)."'><i class='entypo-doc-text'></i> ".__('View Details','wt_admin')."</a>";
	}
	else
	{
		$extraClass  = " single_button";
	}	
	
	if($output) echo "<div class='wt_cart_buttons $extraClass'>$output</div>";
}

#
# wrap products on overview pages into an extra div for improved styling options. adds "product_on_sale" class if prodct is on sale
#

add_action( 'woocommerce_before_shop_loop_item', 'wt_shop_overview_extra_div', 5);
function wt_shop_overview_extra_div() {
	global $product;
	$product_class = $product->is_on_sale() ? "product_on_sale" : "";

	echo "<div class='wt_inner_product main_color wrapped_style $product_class'>";
}

add_action( 'woocommerce_after_shop_loop_item',  'wt_woo_close_div', 1000);
function wt_woo_close_div() {
	echo "</div>";
}
?>