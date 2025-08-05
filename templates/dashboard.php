<?php
//Remove default toggle button
add_filter( 'dokan_load_hamburger_menu', '__return_false' );
//Remove default common links
//add_filter( 'dokan_dashboard_nav_common_link', '__return_false' );

/**
 * Removes the Dokan Color Scheme Customizer styles from the wp_head action.
 */
function awesome_dokan_remove_dokan_color_customizer_styles() {
    // Check if the Dokan Pro function and the specific module exist
    if ( function_exists( 'dokan_pro' ) ) {
        // Get the instance of the Color Scheme Customizer module
        $color_module_instance = dokan_pro()->module->color_scheme_customizer;

        // Remove the action
        remove_action( 'wp_head', [ $color_module_instance, 'load_styles' ], 10 );
    }
}

// Hook into an action that runs after the Dokan plugin has been loaded
add_action( 'init', 'awesome_dokan_remove_dokan_color_customizer_styles', 20 );

add_action('dokan_dashboard_wrap_start', 'awesome_dokan_dashboard_wrap_start');
function awesome_dokan_dashboard_wrap_start(){
	$options = get_option( 'awesome_dokan_options' );
	?>
<div class="awesome-dokan-wrapper">

	<div class="awesome-dokan-header">
		<div class="header-left">
			<div class="awesome-navigation-toggle"><a href="#" class="awesome-navigation-toggle-button"><i class="fa fa-bars" aria-hidden="true"></i></a></div>
			<?php 
			$dashboard_logo = isset( $options['dashboard_logo'] ) ? $options['dashboard_logo'] : '';
			
			if( $dashboard_logo != 'none' ) :
	
			$logo_url = isset( $options['logo_url'] ) ? esc_url( $options['logo_url'] ) : home_url();
			?>
			<a href="<?php echo esc_url($logo_url); ?>" class="awesome-dashboard-logo">
				<?php
				$dashboard_logo_url = '';
				if( $dashboard_logo == 'site_icon' && has_site_icon() ){
					$dashboard_logo_url = get_site_icon_url(64);
					
				}elseif( $dashboard_logo == 'main_logo' && function_exists('get_custom_logo') && has_custom_logo() ){
					$custom_logo_id = get_theme_mod('custom_logo');
    				$dashboard_logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
					
				}elseif( $dashboard_logo == 'custom_logo' ){
					$dashboard_logo_url = isset( $options['custom_logo'] ) ? $options['custom_logo'] : '';
					
				}else{
					$dashboard_logo_url = '';
				}
				?>
				
				<?php 
				if($dashboard_logo_url != ''){
					?>
					<img src="<?php echo esc_url($dashboard_logo_url); ?>" alt="Icon" />
					<?php
				}
				if( $dashboard_logo == 'dashboard_icon' ){
					?>
					<i class="fas fa-tachometer-alt"></i>
					<?php
				}
				?>
			</a>
			<?php endif; ?>
			<?php
				$user_data = get_userdata( dokan_get_current_user_id() );
				$user_name = $user_data->display_name;

				$hour = (int) current_time('H');
				$greeting = ( $hour < 12 ) ? __('Good morning', 'awesome-dokan') : ( ( $hour < 18 ) ? __('Good afternoon', 'awesome-dokan') : __('Good evening', 'awesome-dokan') );

				$dashboard_greeting = '';

				if ( is_array( $options ) && isset( $options['dashboard_greeting'] ) ) {
					$dashboard_greeting = trim( (string) $options['dashboard_greeting'] );
				}

				$greeting_message = !empty($dashboard_greeting) ? str_replace( '{user}', $user_name, $dashboard_greeting ) : $greeting . ', ' . $user_name;
			?>
			<h3 class="awesome-dokan-header-title awesome-hide-mobile"><?php echo esc_html($greeting_message); ?></h3>
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
					<a href="<?php echo esc_url( $new_product_url ); ?>" class="icon-btn <?php echo $disable_product_popup ? '' : 'dokan-add-new-product'; ?>" data-original-title="Add New Product">
						<i class="fas fa-plus"></i>
					</a>
				<?php endif; ?>

				<?php
					do_action( 'dokan_after_add_product_btn' );
				?>
			<?php endif; ?>
			<a target="_blank" href="<?php echo dokan_get_store_url( dokan_get_current_user_id() ); ?>" class="icon-btn" data-original-data-original-title="Visit Store">
				<i class="fas fa-store"></i>
			</a>
			<a href="<?php echo esc_url( dokan_get_navigation_url( 'withdraw' ) ); ?>" class="icon-btn awesome-hide-mobile" data-original-title="Withdraw">
				<i class="fas fa-upload"></i>
			</a>

			<?php
			$new_orders = dokan_count_orders( dokan_get_current_user_id(), 'pending' );
			?>
			<a href="<?php echo esc_url( dokan_get_navigation_url( 'orders' ) ); ?>" class="icon-btn" data-original-title="New Orders">
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
					<li><a href="<?php echo esc_url( dokan_get_navigation_url( 'edit-account' ) ); ?>"><i class="fas fa-user"></i> <?php echo esc_html__('Edit Account', 'awesome-dokan'); ?></a></li>
					<li><a href="<?php echo wp_logout_url( home_url() ); ?>"><i class="fas fa-power-off"></i> <?php echo esc_html__('Log Out', 'awesome-dokan'); ?></a></li>
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