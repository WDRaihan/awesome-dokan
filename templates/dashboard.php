<?php
//Remove default toggle button
add_filter( 'dokan_load_hamburger_menu', '__return_false' );
//Remove default common links
add_filter( 'dokan_dashboard_nav_common_link', '__return_false' );

/**
 * Removes the Dokan Color Scheme Customizer styles from the wp_head action.
 */
function my_remove_dokan_color_customizer_styles() {
    // Check if the Dokan Pro function and the specific module exist
    //if ( function_exists( 'dokan_pro' ) && isset( dokan_pro()->module->color_scheme_customizer ) ) {
        // Get the instance of the Color Scheme Customizer module
        $color_module_instance = dokan_pro()->module->color_scheme_customizer;

        // Remove the action
        remove_action( 'wp_head', [ $color_module_instance, 'load_styles' ], 10 );
    //}
}

// Hook into an action that runs after the Dokan plugin has been loaded
add_action( 'init', 'my_remove_dokan_color_customizer_styles', 20 );

add_action('dokan_dashboard_wrap_start', 'awesome_dokan_dashboard_wrap_start');
function awesome_dokan_dashboard_wrap_start(){
	?>
<div class="awesome-dokan-wrapper">

	<div class="modern-dokan-header">
		<div class="header-left">
			<div class="awesome-navigation-toggle"><a href="#" class="awesome-navigation-toggle-button"><i class="fa fa-bars" aria-hidden="true"></i></a></div>
			<a href="<?php echo home_url(); ?>" class="awesome-dashboard-logo">
				<img src="<?php echo get_site_icon_url(64); ?>" alt="Logo" />
				<?php
					$user_data = get_userdata( dokan_get_current_user_id() );
					$hour = (int) current_time('H');
					$greeting = ( $hour < 12 ) ? 'Good morning' : ( ( $hour < 18 ) ? 'Good afternoon' : 'Good evening' );
				?>
				<span><?php echo $greeting . ', ' . esc_html( $user_data->display_name ); ?></span>
			</a>
		</div>

		<div class="header-right">
			<?php
			$one_step_product_create = 'on' === dokan_get_option( 'one_step_product_create', 'dokan_selling', 'on' );
			$disable_product_popup   = $one_step_product_create || 'on' === dokan_get_option( 'disable_product_popup', 'dokan_selling', 'off' );
			$new_product_url = $one_step_product_create ? dokan_edit_product_url( 0, true ) : add_query_arg(
				[
					'_dokan_add_product_nonce' => wp_create_nonce( 'dokan_add_product_nonce' ),
				],
				dokan_get_navigation_url( 'new-product' )
			);
			?>
			<?php if ( dokan_is_seller_enabled( dokan_get_current_user_id() ) ) : ?>
				<?php if ( current_user_can( 'dokan_add_product' ) ) : ?>
					<a href="<?php echo esc_url( $new_product_url ); ?>" class="icon-btn <?php echo $disable_product_popup ? '' : 'dokan-add-new-product'; ?>" title="Add Product">
						<i class="fas fa-plus"></i>
					</a>
				<?php endif; ?>

				<?php
					do_action( 'dokan_after_add_product_btn' );
				?>
			<?php endif; ?>
			<a target="_blank" href="<?php echo dokan_get_store_url( dokan_get_current_user_id() ); ?>" class="icon-btn" title="Visit Store">
				<i class="fas fa-store"></i>
			</a>
			<a href="<?php echo esc_url( dokan_get_navigation_url( 'withdraw' ) ); ?>" class="icon-btn awesome-hide-mobile" title="Withdraw">
				<i class="fas fa-upload"></i>
			</a>

			<?php
			$new_orders = dokan_count_orders( dokan_get_current_user_id(), 'pending' );
			?>
			<a href="<?php echo esc_url( dokan_get_navigation_url( 'orders' ) ); ?>" class="icon-btn" title="New Orders">
				<i class="fas fa-shopping-cart"></i>
				<?php if ( $new_orders ) : ?>
				<span class="badge"><?php echo isset( $new_orders->count ) ? intval( $new_orders->count ) : 0; ?></span>

				<?php endif; ?>
			</a>

			<div class="avatar-wrap">
				<span class="awesome-user-avatar">
					<?php echo get_avatar( get_current_user_id(), 32 ); ?>
					<i class="fas fa-chevron-down"></i>
				</span>
				<ul class="avatar-dropdown">
					<li><a href="<?php echo esc_url( dokan_get_navigation_url( 'settings' ) ); ?>">Edit Account</a></li>
					<li><a href="<?php echo wp_logout_url( home_url() ); ?>">Log Out</a></li>
				</ul>
			</div>
		</div>
	</div>
	<?php
}

add_action('dokan_dashboard_wrap_end', 'awesome_dokan_dashboard_wrap_end');
function awesome_dokan_dashboard_wrap_end(){
	?>
</div>
<?php
}