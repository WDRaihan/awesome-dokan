<?php
// Do not access this file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Force Dokan to use legacy dashboard design.
 */
add_action('admin_init', function() {
    $appearance = get_option( 'dokan_appearance', [] );

    if( $appearance['vendor_layout_style'] !== 'legacy' ){
        $appearance['vendor_layout_style'] = 'legacy';
        update_option( 'dokan_appearance', $appearance );
    }
});

/**
 * FIlter: Full-Screen Mode
 */
add_filter('awesome_dokan_fullscreen', 'awesome_dokan_fullscreen_class', 10);
function awesome_dokan_fullscreen_class($no_fullscreen){
	$user_id = get_current_user_id();
	$fullscreen = get_user_meta( $user_id, 'awesome_dokan_fullscreen', true );
	
	if( $fullscreen == 'on' ){
		return 'awesome-dokan-fullscreen-mode';
	}
	
	return $no_fullscreen;
}


/**
 * Add header inside dashboard content for Theme Two
 * Hooked into 'dokan_dashboard_content_inside_before'
 */
add_action('dokan_dashboard_content_before', 'awesome_dokan_add_header_inside_dashboard_content_before', 1);
function awesome_dokan_add_header_inside_dashboard_content_before(){
	$options = get_option( 'awesome_dokan_options' );
	$dashboard_theme = isset( $options['dashboard_theme'] ) ? $options['dashboard_theme'] : 'theme_one';
	
	if( $dashboard_theme == 'theme_two' ){
		awesome_dokan_dashboard_header();
		?>
		<script>
			jQuery(document).ready(function(){
				jQuery(".dokan-dashboard-content").prepend(jQuery('.awesome-dokan-header'));
			});
		</script>
		<?php
	}
}

/**
 * Full screen
 * Handle AJAX request
 */
add_action( 'wp_ajax_awesome_dokan_save_fullscreen_mode', 'awesome_dokan_save_fullscreen_mode' );
function awesome_dokan_save_fullscreen_mode() {
    check_ajax_referer( 'awesome_dokan_nonce', 'nonce' );

    $user_id = get_current_user_id();
    if ( $user_id ) {
        $meta_value = isset($_POST['meta_value']) ? sanitize_text_field($_POST['meta_value']) : '';
        update_user_meta( $user_id, 'awesome_dokan_fullscreen', $meta_value );
        wp_send_json_success( 'Fullscreen mode saved.' );
    }

    wp_send_json_error( 'User not logged in' );
	
	die;
}

/**
 * Function: Sidebar nav toggle HTML
 */
function awesome_dokan_sidebar_nav_toggle(){
	$options = get_option( 'awesome_dokan_options' );
	$sidebar_hide_show = isset( $options["sidebar_hide_show"] ) ? $options["sidebar_hide_show"] : '';
	
	if( $sidebar_hide_show == 'on' ){
	?>
		<a href="#" class="awesome-navigation-toggle-button awesome-desktop-navigation icon-btn tips" data-original-title="Hide/Show the sidebar"><i class="fa fa-bars" aria-hidden="true"></i></a>
	<?php
	}
}

/**
 * Function: Full-Screen button HTML
 */
function awesome_dokan_fullscreen_button(){
	?>
	<div class="awesome-toggle-button">
		<span class="awesome-fullscreen-toggle-title"><?php echo esc_html__('Full Screen: ', 'awesome-dokan-pro'); ?></span>
		<label class="awesome-toggle-switch">
			<?php
			$user_id = get_current_user_id();
			$fullscreen = get_user_meta( $user_id, 'awesome_dokan_fullscreen', true );
			?>
			<input class="awesome-fullscreen-toggle-button" type="checkbox" value="on" <?php if($fullscreen == 'on'){ echo esc_attr('checked'); } ?> ><span class="awesome-toggle-slider round"></span>
		</label>
	</div>
<?php
}