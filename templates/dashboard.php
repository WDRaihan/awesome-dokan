<?php
// Don't call this file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

//Remove default toggle button
add_filter( 'dokan_load_hamburger_menu', '__return_false' );

//Awesome dashboard header logo and title
function awesome_dokan_dashboard_header_logo_title(){
	$options = get_option( 'awesome_dokan_options' );
	$dashboard_logo = isset( $options['dashboard_logo'] ) ? $options['dashboard_logo'] : '';
	
	if( $dashboard_logo != 'none' ) :
	
	$logo_url = home_url();
	if( awesome_dokan_pro_is_active() && isset( $options['logo_url'] ) ){
		$logo_url = !empty( $options['logo_url'] ) ? esc_url( $options['logo_url'] ) : home_url();
	}
	
	?>
	<a href="<?php echo esc_url($logo_url); ?>" class="awesome-dashboard-logo">
		<?php
		$dashboard_logo_url = '';
		if( $dashboard_logo == 'site_icon' && has_site_icon() ){
			$dashboard_logo_url = get_site_icon_url(64);

		}elseif( $dashboard_logo == 'main_logo' && function_exists('get_custom_logo') && has_custom_logo() ){
			$custom_logo_id = get_theme_mod('custom_logo');
			$dashboard_logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');

		}elseif( $dashboard_logo == 'custom_logo' && awesome_dokan_pro_is_active() ){
			$dashboard_logo_url = apply_filters('awesome_dokan_custom_dashboard_logo','');

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

		$greeting_message = !empty($dashboard_greeting) ? str_replace( '{user}', $user_name, $dashboard_greeting ) : '<span>'. $greeting . ',</span> ' . $user_name;
	?>
	<h3 class="awesome-dokan-header-title awesome-hide-mobile"><?php echo wp_kses($greeting_message, array('br' => [], 'span' => [ 'class' => true, 'style' => true, 'id' => true ])); ?></h3>
	<?php
}

//Awesome dashboard header
function awesome_dokan_dashboard_header(){
	$options = get_option( 'awesome_dokan_options' );
	$dashboard_theme = apply_filters('awesome_dokan_dashboard_theme', 'theme_one');
	?>
	<div class="awesome-dokan-header">
		<div class="awesome-header-left">
			<div class="awesome-navigation-toggle"><a href="#" class="awesome-navigation-toggle-button"><i class="fa fa-bars" aria-hidden="true"></i></a></div>
			<?php
			/*Sidebar nav toggle*/
			if( $dashboard_theme == 'theme_two' ){
				if( function_exists('awesome_dokan_sidebar_nav_toggle') && awesome_dokan_pro_is_active() ){
					awesome_dokan_sidebar_nav_toggle();
				}
			}
			?>
			
			<?php
			if($dashboard_theme == 'theme_one'){
				awesome_dokan_dashboard_header_logo_title();
			}
			?>
		</div>
		
		<div class="awesome-header-center">
			<?php 
			/*Show full-screen button*/
			if( function_exists('awesome_dokan_fullscreen_button') && awesome_dokan_pro_is_active() ){
				awesome_dokan_fullscreen_button();
			}
			?>
		</div>
		
		<div class="awesome-header-right">
			<?php
			/*sidebar nav toggle*/
			if( $dashboard_theme == 'theme_one' ){
				if( function_exists('awesome_dokan_sidebar_nav_toggle') && awesome_dokan_pro_is_active() ){
					awesome_dokan_sidebar_nav_toggle();
				}
			}
			?>
			
			<?php
			/*Add new product icon*/
			if( function_exists('awesome_dokan_add_new_product_icon') && awesome_dokan_pro_is_active() ){
				awesome_dokan_add_new_product_icon();
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
			$notifications = isset( $options["enable_icon_order_notification"] ) ? $options["enable_icon_order_notification"] : '';
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

/**
 * Add logo and title to the Dokan dashboard sidebar (Theme Two)
 * Hooked into 'dokan_dashboard_sidebar_start'
 */
add_action('dokan_dashboard_sidebar_start', 'awesome_dokan_add_logo_title_dashboard_sidebar_start', 1);
function awesome_dokan_add_logo_title_dashboard_sidebar_start(){
	echo '<div class="awesome-header-logo-title">';
	awesome_dokan_dashboard_header_logo_title();
	echo '</div>';
}

/**
 * Add wrapper and custom styles to the Dokan dashboard (Theme One)
 * Hooked into 'dokan_dashboard_wrap_start'
 */
add_action('dokan_dashboard_wrap_start', 'awesome_dokan_dashboard_wrap_start', 1);
function awesome_dokan_dashboard_wrap_start(){
	$options = get_option( 'awesome_dokan_options' );
	$dashboard_theme = apply_filters('awesome_dokan_dashboard_theme', 'theme_one');
	
	do_action('awesome_dokan_before_wrapper');
	
	$fullscreen = apply_filters('awesome_dokan_fullscreen','');
	?>
	<div class="awesome-dokan-wrapper <?php echo esc_attr($dashboard_theme); ?> <?php echo esc_attr($fullscreen); ?>" id="awesome_dokan_wrapper">
	<?php 
	if( $dashboard_theme == 'theme_one' ){
		awesome_dokan_dashboard_header();	
	}
	?>
<?php
}

/**
 * Add wrapper end
 * Hooked into 'dokan_dashboard_wrap_end'
 */
add_action('dokan_dashboard_wrap_end', 'awesome_dokan_dashboard_wrap_end');
function awesome_dokan_dashboard_wrap_end(){
	?>
	</div>
<?php
}

/**
 * Set default color
 * Hooked into 'awesome_dokan_before_wrapper'
 */
add_action('awesome_dokan_before_wrapper', 'awesome_dokan_before_wrapper_add_style', 10);
function awesome_dokan_before_wrapper_add_style(){
	
	$dokan_is_pro_exists = apply_filters( 'dokan_is_pro_exists', false );
	
	if( !$dokan_is_pro_exists ) {
		return;
	}
	
	$colors         	  = dokan_get_option( 'store_color_pallete', 'dokan_colors', [] );
	
	$dash_nav_bg          = ! empty( $colors['dash_nav_bg'] ) ? $colors['dash_nav_bg'] : 'var(--dokan-sidebar-background-color, #322067)';
	$dash_nav_text        = ! empty( $colors['dash_nav_text'] ) ? $colors['dash_nav_text'] : '#ffffff';
	$dash_active_menu     = ! empty( $colors['dash_active_link'] ) ? $colors['dash_active_link'] : '#7047EB';
	$dash_nav_active_text = ! empty( $colors['dash_nav_active_text'] ) ? $colors['dash_nav_active_text'] : '#ffffff';
	?>
	<style>
		:root {
			--awesome-default-background-color: <?php echo esc_attr($dash_nav_bg); ?>;
			--awesome-default-font-color: <?php echo esc_attr($dash_nav_text); ?>;
			--awesome-default-font-background-color: <?php echo esc_attr($dash_active_menu); ?>;
			--awesome-default-sidebar-font-color: <?php echo esc_attr($dash_nav_text); ?>;
			--awesome-default-sidebar-font-active-color: <?php echo esc_attr($dash_nav_active_text); ?>;
		}
	</style>
	<?php
}