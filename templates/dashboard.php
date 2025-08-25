<?php
// Don't call this file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

//Remove default toggle button
add_filter( 'dokan_load_hamburger_menu', '__return_false' );
//Remove default common links
add_filter( 'dokan_dashboard_nav_common_link', '__return_false' );

//Removes the Dokan Color Scheme Customizer styles from the wp_head action.
function awesome_dokan_remove_dokan_color_customizer_styles() {
    // Check if the Dokan Pro function and the specific module exist
    if ( function_exists( 'dokan_pro' ) ) {
        // Get the instance of the Color Scheme Customizer module
        $color_module_instance = dokan_pro()->module->color_scheme_customizer;

        // Remove the action
        remove_action( 'wp_head', [ $color_module_instance, 'load_styles' ], 10 );
    }
}
add_action( 'init', 'awesome_dokan_remove_dokan_color_customizer_styles', 20 );

add_action('dokan_dashboard_wrap_start', 'awesome_dokan_dashboard_wrap_start');
function awesome_dokan_dashboard_wrap_start(){
	$options = get_option( 'awesome_dokan_options' );
	$styles = get_option( 'awesome_dokan_styles' );
	$header_bg_color = isset( $styles['header_bg_color'] ) ? $styles['header_bg_color'] : '';
	$header_font_color = isset( $styles['header_font_color'] ) ? $styles['header_font_color'] : '';
	$sidebar_bg_color = isset( $styles['sidebar_bg_color'] ) ? $styles['sidebar_bg_color'] : '';
	$sidebar_font_active_bg_color = isset( $styles['sidebar_font_active_bg_color'] ) ? $styles['sidebar_font_active_bg_color'] : '';
	$sidebar_font_color = isset( $styles['sidebar_font_color'] ) ? $styles['sidebar_font_color'] : '';
	$sidebar_font_active_color = isset( $styles['sidebar_font_active_color'] ) ? $styles['sidebar_font_active_color'] : '';
	$content_bg_color = isset( $styles['content_bg_color'] ) ? $styles['content_bg_color'] : '';
	?>
	<style>
	:root {
		<?php
		if(!empty($header_bg_color)){
			echo esc_attr('--awesome-header-background-color: '.$header_bg_color.';');
		}
		if(!empty($header_font_color)){
			echo esc_attr('--awesome-header-font-color: '.$header_font_color.';');
		}
		if(!empty($sidebar_bg_color)){
			echo esc_attr('--awesome-sidebar-background-color: '.$sidebar_bg_color.';');
		}
		if(!empty($sidebar_font_active_bg_color)){
			echo esc_attr('--awesome-sidebar-font-background-color: '.$sidebar_font_active_bg_color.';');
		}
		if(!empty($sidebar_font_color)){
			echo esc_attr('--awesome-sidebar-font-color: '.$sidebar_font_color.';');
		}
		if(!empty($sidebar_font_active_color)){
			echo esc_attr('--awesome-sidebar-font-active-color: '.$sidebar_font_active_color.';');
		}
		if(!empty($content_bg_color)){
			echo esc_attr('--awesome-content-background-color: '.$content_bg_color.';');
		}
		?>
	}
	</style>
<div class="awesome-dokan-wrapper awesome-dokan-fullscreen-mode-" id="awesome_dokan_wrapper">

	<div class="awesome-dokan-header">
		<div class="awesome-header-left">
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

		<div class="awesome-header-right">
			<?php
			$sidebar_hide_show = isset( $options["sidebar_hide_show"] ) ? $options["sidebar_hide_show"] : '';
			if( $sidebar_hide_show == 'on' ){
			?>
				<a href="#" class="awesome-navigation-toggle-button icon-btn tips" data-original-title="Hide/Show the sidebar"><i class="fa fa-bars" aria-hidden="true"></i></a>
			<?php } ?>
			
			<?php
			$add_product = isset( $options["enable_icon_add_product"] ) ? $options["enable_icon_add_product"] : '';
	
			if( $add_product == 'on' && dokan_is_seller_enabled( dokan_get_current_user_id() ) ) {
			
				$one_step_product_create = 'on' === dokan_get_option( 'one_step_product_create', 'dokan_selling', 'on' );
				$disable_product_popup   = $one_step_product_create || 'on' === dokan_get_option( 'disable_product_popup', 'dokan_selling', 'off' );
				$new_product_url = $one_step_product_create ? dokan_edit_product_url( 0, true ) : add_query_arg(
					[
						'_dokan_add_product_nonce' => wp_create_nonce( 'dokan_add_product_nonce' ),
					],
					dokan_get_navigation_url( 'new-product' )
				);
			
				if ( current_user_can( 'dokan_add_product' ) ) {
				?>
				<a href="<?php echo esc_url( $new_product_url ); ?>" class="icon-btn tips <?php echo $disable_product_popup ? '' : esc_attr('dokan-add-new-product'); ?>" data-original-title="Add New Product">
					<i class="fas fa-plus"></i>
				</a>
				<?php
				}
			}
			?>
			<?php 
			$visit_store = isset( $options["enable_icon_visit_store"] ) ? $options["enable_icon_visit_store"] : '';
			if( $visit_store == 'on' ){
				?>
				<a target="_blank" href="<?php echo esc_url(dokan_get_store_url( dokan_get_current_user_id() )); ?>" class="icon-btn tips" data-original-title="Visit Store">
					<i class="fas fa-store"></i>
				</a>
			<?php } ?>
			<?php 
			$withdraw = isset( $options["enable_icon_withdraw"] ) ? $options["enable_icon_withdraw"] : '';
			if( $withdraw == 'on' ){
				?>
				<a href="<?php echo esc_url( dokan_get_navigation_url( 'withdraw' ) ); ?>" class="icon-btn tips awesome-hide-mobile" data-original-title="Withdraw">
					<i class="fas fa-upload"></i>
				</a>
				<?php } ?>
			<?php
			$notifications = isset( $options["enable_icon_notifications"] ) ? $options["enable_icon_notifications"] : '';
				if( $notifications == 'on' ){
				$new_orders = (array) dokan_count_orders( dokan_get_current_user_id() );
				?>
				<a href="<?php echo esc_url( dokan_get_navigation_url( 'orders' ) ); ?>" class="icon-btn tips" data-original-title="New Orders">
					<i class="fas fa-shopping-cart"></i>
					<?php if ( $new_orders ) : ?>
					<span class="badge"><?php echo isset( $new_orders['wc-processing'] ) ? intval( $new_orders['wc-processing'] ) : 0; ?></span>

					<?php endif; ?>
				</a>
			<?php } ?>
			<div class="awesome-avatar-wrap">
				<span class="awesome-user-avatar">
					<?php echo get_avatar( get_current_user_id(), 32 ); ?>
					<i class="fas fa-chevron-down"></i>
				</span>
				<ul class="awesome-avatar-dropdown">
					<li><a href="<?php echo esc_url( dokan_get_navigation_url( 'edit-account' ) ); ?>"><i class="fas fa-user"></i> <?php echo esc_html__('Edit Account', 'awesome-dokan'); ?></a></li>
					<li><a href="<?php echo esc_url(wp_logout_url( home_url() )); ?>"><i class="fas fa-power-off"></i> <?php echo esc_html__('Log Out', 'awesome-dokan'); ?></a></li>
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