<?php
/**
 *  This is a modified copy of Walker_Nav_Menu_Edit class
 * 
 * Create HTML list of nav menu input items.
 *
 * @package WordPress
 * @since 3.0.0
 * @uses Walker_Nav_Menu
 */
class Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu  {
	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker_Nav_Menu::end_lvl()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   Not used.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {}
	
	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
	    global $_wp_nav_menu_max_depth;
		$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

		ob_start();
		$item_id = esc_attr( $item->ID );
		$removed_args = array(
			'action',
			'customlink-tab',
			'edit-menu-item',
			'menu-item',
			'page-tab',
			'_wpnonce',
		);

		$original_title = '';
		if ( 'taxonomy' == $item->type ) {
			$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
			if ( is_wp_error( $original_title ) )
				$original_title = false;
		} elseif ( 'post_type' == $item->type ) {
			$original_object = get_post( $item->object_id );
			$original_title = get_the_title( $original_object->ID );
		}

		$classes = array(
			'menu-item menu-item-depth-' . $depth,
			'menu-item-' . esc_attr( $item->object ),
			'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
		);

		$title = $item->title;

		if ( ! empty( $item->_invalid ) ) {
			$classes[] = 'menu-item-invalid';
			$title = sprintf( __( '%s (Invalid)', 'wt_admin' ), $item->title );
		} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
			$classes[] = 'pending';
			/* translators: %s: title of menu item in draft status */
			$title = sprintf( __('%s (Pending)', 'wt_admin'), $item->title );
		}

		$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

		$submenu_text = '';
		if ( 0 == $depth )
			$submenu_text = 'style="display: none;"';
		// set default item fields
		$default_mega_menu_fields = array(
			'wt_megamenu_icon' => '',
			'wt_megamenu_enabled' => 0,
			'wt_megamenu_fullwidth' => 0,
			'wt_megamenu_columns' => 1,
			'wt_megamenu_styled_title' => 0,
			'wt_megamenu_hide_title' => 0,
			'wt_megamenu_remove_link' => 0,
			'wt_megamenu_html_content' => ''
		);

		// set defaults
		foreach ( $default_mega_menu_fields as $field=>$value ) {
			if ( !isset($item->$field) ) {
				$item->$field = $value;
			}
		}

		if ( empty( $item->wt_megamenu_columns ) ) {
			$item->wt_megamenu_columns = 1;
		}
		
		$mega_menu_container_classes = array( 'wt-megamenu-fields' );
		if ( !empty($item->wt_megamenu_enabled) ) {
			$classes[] = 'field-wt-megamenu-enabled';
		}
		
		$mega_menu_container_classes = implode( ' ', $mega_menu_container_classes );
		?>
		<li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo $submenu_text; ?>><?php _e( 'sub item', 'wt_admin' ); ?></span></span>
					<span class="item-controls">
						<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
						<span class="item-order hide-if-js">
							<a href="<?php
								echo wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-up-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								);
							?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
							|
							<a href="<?php
								echo wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-down-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								);
							?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
						</span>
						<a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
							echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
						?>"><?php _e( 'Edit Menu Item', 'wt_admin' ); ?></a>
					</span>
				</dt>
			</dl>

			<div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
				<?php if( 'custom' == $item->type ) : ?>
					<p class="field-url description description-wide">
						<label for="edit-menu-item-url-<?php echo $item_id; ?>">
							<?php _e( 'URL', 'wt_admin' ); ?><br />
							<input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
						</label>
					</p>
				<?php endif; ?>
				<p class="description description-thin">
					<label for="edit-menu-item-title-<?php echo $item_id; ?>">
						<?php _e( 'Navigation Label', 'wt_admin' ); ?><br />
						<input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
					</label>
				</p>
				<p class="description description-thin">
					<label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
						<?php _e( 'Title Attribute', 'wt_admin' ); ?><br />
						<input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
					</label>
				</p>
				<p class="field-link-target description">
					<label for="edit-menu-item-target-<?php echo $item_id; ?>">
						<input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
						<?php _e( 'Open link in a new window/tab', 'wt_admin' ); ?>
					</label>
				</p>
				<p class="field-css-classes description description-thin">
					<label for="edit-menu-item-classes-<?php echo $item_id; ?>">
						<?php _e( 'CSS Classes (optional)', 'wt_admin' ); ?><br />
						<input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
					</label>
				</p>
				<p class="field-xfn description description-thin">
					<label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
						<?php _e( 'Link Relationship (XFN)', 'wt_admin' ); ?><br />
						<input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
					</label>
				</p>      
				
	            <p class="field-description description description-wide">
					<label for="edit-menu-item-description-<?php echo $item_id; ?>">
						<?php _e( 'Description', 'wt_admin' ); ?><br />
						<textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
						<span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.', 'wt_admin' ); ?></span>
					</label>
				</p>
				<?php
	            /* New fields insertion starts here */
	            ?>	               
	            <!-- Whoathemes Mega Menu -->

				<div class="<?php echo esc_attr( $mega_menu_container_classes ); ?>">

                    <h4>Custom Menu Options</h4>
					
                    <p class="field-wt-icon description description-wide">
		                 <label for="edit-menu-item-wt-icon-<?php echo $item_id; ?>">
							 <?php _e( 'Menu Icon (Font Awesome, Entypo or Glyphicons) - use prefix (for example: <strong>fa-</strong>angle-right, <strong>entypo-</strong>flag or <strong>glyphicon-</strong>leaf)', 'wt_admin' ); ?>
                             <input type="text" id="edit-menu-item-wt-icon-[<?php echo $item_id; ?>]" class="widefat edit-menu-item-wt-icon" name="menu-item-wt-icon[<?php echo $item_id; ?>]" value="<?php echo esc_html( $item->wt_megamenu_icon ); ?>" style=" margin-top: 5px;" />
		                 </label>
		             </p>

					<!-- first level -->
					<p class="field-wt-enable-megamenu">
						<label for="edit-menu-item-wt-enable-megamenu-<?php echo $item_id; ?>">
							<?php _ex( 'Enable Mega Menu', 'edit menu walker', 'wt_admin' ); ?>
							<input id="edit-menu-item-wt-enable-megamenu-<?php echo $item_id; ?>" type="checkbox" class="menu-item-wt-enable-megamenu" name="menu-item-wt-enable-megamenu[<?php echo $item_id; ?>]" <?php checked( $item->wt_megamenu_enabled ); ?>/>
						</label>
					</p>
					<p class="field-wt-fullwidth-menu">
						<label for="edit-menu-item-wt-fullwidth-menu-<?php echo $item_id; ?>">
							<?php _ex( 'Fullwidth', 'edit menu walker', 'wt_admin' ); ?>
							<input id="edit-menu-item-wt-fullwidth-menu-<?php echo $item_id; ?>" type="checkbox" name="menu-item-wt-fullwidth-menu[<?php echo $item_id; ?>]" <?php checked( $item->wt_megamenu_fullwidth ); ?>/>
						</label>
					</p>
                    <p class="field-wt-columns description description-wide">
						<?php _ex( 'Number of columns: ', 'edit menu walker', 'wt_admin' ); ?>
						<select name="menu-item-wt-columns[<?php echo $item_id; ?>]" id="edit-menu-item-wt-columns-<?php echo $item_id; ?>">
							<?php foreach( array( 'One column' => 1, 'Two columns' => 2, 'Three columns' => 3, 'Fourth columns' => 4 ) as $title=>$value): ?>
								<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $item->wt_megamenu_columns); ?>><?php echo esc_html($title); ?></option>
							<?php endforeach; ?>
						</select>
					</p>
					<!-- second level -->
					<p class="field-wt-styled-title">
						<label for="edit-menu-item-wt-styled-title-<?php echo $item_id; ?>">
							<?php _ex( 'Styled title in mega menu', 'edit menu walker', 'wt_admin' ); ?>
							<input id="edit-menu-item-wt-styled-title-<?php echo $item_id; ?>" type="checkbox" name="menu-item-wt-styled-title[<?php echo $item_id; ?>]" <?php checked( $item->wt_megamenu_styled_title ); ?>/>
						</label>
					</p>
                    <p class="field-wt-hide-title">
						<label for="edit-menu-item-wt-hide-title-<?php echo $item_id; ?>">
							<?php _ex( 'Hide title in mega menu', 'edit menu walker', 'wt_admin' ); ?>
							<input id="edit-menu-item-wt-hide-title-<?php echo $item_id; ?>" type="checkbox" name="menu-item-wt-hide-title[<?php echo $item_id; ?>]" <?php checked( $item->wt_megamenu_hide_title ); ?>/>
						</label>
					</p>
					<p class="field-wt-remove-link">
						<label for="edit-menu-item-wt-remove-link-<?php echo $item_id; ?>">
							<?php _ex( 'No Link Title', 'edit menu walker', 'wt_admin' ); ?>
							<input id="edit-menu-item-wt-remove-link-<?php echo $item_id; ?>" type="checkbox" name="menu-item-wt-remove-link[<?php echo $item_id; ?>]" <?php checked( $item->wt_megamenu_remove_link ); ?>/>
						</label>
					</p>
                    <?php if ($depth != 0) { ?>
					<p class="field-wt-html-content description description-wide">
                        <label for="edit-menu-item-wt-html-content-<?php echo $item_id; ?>">
						<?php _e( 'Custom HTML Column (Shortcode accepted)', 'wt_admin'  ); ?>
                        <textarea id="edit-menu-item-wt-html-content-<?php echo $item_id; ?>" name="menu-item-wt-html-content[<?php echo $item_id; ?>]" cols="30" rows="4"><?php echo esc_html( $item->wt_megamenu_html_content ); ?></textarea>
		                 </label>
					</p>
					<?php } ?>

				</div>

				<?php do_action( 'wt_edit_menu_walker_print_item_settings', $item, $depth, $args, $id, $item_id ); ?>

				<!-- Whoathemes Mega Menu -->
	            <script>
				
					jQuery(function($) {
						$( "#radio<?php echo $item_id; ?>, #column<?php echo $item_id; ?>, #thumb<?php echo $item_id; ?>, #nav_label<?php echo $item_id; ?>" ).buttonset({
								create: function( event, ui ) {
						    		event.preventDefault();
								}
								});
						return false;
					});
				</script>
				<style type="text/css">
						input[type="radio"].ui-helper-hidden-accessible{
							visibility: hidden;
							top: 0;
							left: 0;
						}
				</style>
	            <?php
	            /* New fields insertion ends here */
	            ?>
				<p class="field-move hide-if-no-js description description-wide">
					<label>
						<span><?php _e( 'Move', 'wt_admin' ); ?></span>
						<a href="#" class="menus-move-up"><?php _e( 'Up one', 'wt_admin' ); ?></a>
						<a href="#" class="menus-move-down"><?php _e( 'Down one', 'wt_admin' ); ?></a>
						<a href="#" class="menus-move-left"></a>
						<a href="#" class="menus-move-right"></a>
						<a href="#" class="menus-move-top"><?php _e( 'To the top', 'wt_admin' ); ?></a>
					</label>
				</p>

				<div class="menu-item-actions description-wide submitbox">
					<?php if( 'custom' != $item->type && $original_title !== false ) : ?>
						<p class="link-to-original">
							<?php printf( __('Original: %s', 'wt_admin'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
						</p>
					<?php endif; ?>
					<a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
					echo wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'delete-menu-item',
								'menu-item' => $item_id,
							),
							admin_url( 'nav-menus.php' )
						),
						'delete-menu_item_' . $item_id
					); ?>"><?php _e( 'Remove', 'wt_admin' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
						?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel', 'wt_admin'); ?></a>
				</div>

				<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
				<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
				<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
				<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
				<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
				<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
		<?php
		$output .= ob_get_clean();
	}

} // Walker_Nav_Menu_Edit