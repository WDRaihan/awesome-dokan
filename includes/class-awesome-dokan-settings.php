<?php
/**
 * Handles the settings page for the Awesome Dokan plugin.
 *
 * @package Awesome_Dokan
 */

// Don't call this file directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Settings Class.
 */
class Awesome_Dokan_Settings {

    /**
     * The single instance of the class.
     *
     * @var Awesome_Dokan_Settings
     */
    private static $_instance = null;

    /**
     * Ensures only one instance of the class is loaded.
     *
     * @return Awesome_Dokan_Settings - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
        add_action( 'admin_init', [ $this, 'settings_init' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
    }

    /**
     * Enqueue WordPress media uploader.
     */
    public function enqueue_admin_scripts() {
        $page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
		if ( $page !== 'awesome-dokan' ) {
			return;
		}
        wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'awesome-dokan-admin-js', AWESOME_DOKAN_ASSETS . '/js/admin.js', [ 'jquery', 'wp-color-picker' ], AWESOME_DOKAN_VERSION, true );
        wp_enqueue_style( 'awesome-dokan-admin-css', AWESOME_DOKAN_ASSETS . '/css/admin.css', [], AWESOME_DOKAN_VERSION );
    }

    /**
     * Add the admin menu for the settings page.
     */
    public function add_admin_menu() {
        add_menu_page(
            __( 'Awesome Dokan Settings', 'awesome-dokan' ),
            __( 'Awesome Dokan', 'awesome-dokan' ),
            'manage_options',
            'awesome-dokan',
            [ $this, 'settings_page_html' ]
        );
    }

    /**
     * Initialize the settings.
     */
    public function settings_init() {
        register_setting( 'awesome_dokan_settings_group', 'awesome_dokan_options', array( 'sanitize_callback' => [$this, 'awesome_dokan_sanitize_options'], ) );
        register_setting( 'awesome_dokan_settings_group', 'awesome_dokan_styles', array( 'sanitize_callback' => [$this, 'awesome_dokan_sanitize_options'], ) );

        add_settings_section(
            'awesome_dokan_general_section',
            __( 'General Settings', 'awesome-dokan' ),
            '__return_false',
            'awesome_dokan_settings_group'
        );

        add_settings_field(
            'enable_new_dashboard_design',
            __( 'Enable New Dashboard Design', 'awesome-dokan' ),
            [ $this, 'render_enable_design_field' ],
            'awesome_dokan_settings_group',
            'awesome_dokan_general_section',
        );

        add_settings_field(
            'dashboard_theme',
            __( 'Select Dashboard Theme', 'awesome-dokan' ),
            [ $this, 'render_dashboard_theme_field' ],
            'awesome_dokan_settings_group',
            'awesome_dokan_general_section',
        );

        add_settings_field(
            'dashboard_greeting',
            __( 'Custom Greeting Text', 'awesome-dokan' ),
            [ $this, 'render_greeting_field' ],
            'awesome_dokan_settings_group',
            'awesome_dokan_general_section',
        );

        add_settings_field(
            'dashboard_logo',
            __( 'Dashboard Logo Source', 'awesome-dokan' ),
            [ $this, 'render_logo_field' ],
            'awesome_dokan_settings_group',
            'awesome_dokan_general_section',
        );

        add_settings_field(
            'custom_logo',
            __( 'Upload Custom Logo', 'awesome-dokan' ),
            [ $this, 'render_custom_logo_field' ],
            'awesome_dokan_settings_group',
            'awesome_dokan_general_section'
        );

        add_settings_field(
            'logo_url',
            __( 'Custom Logo URL', 'awesome-dokan' ),
            [ $this, 'render_logo_url_field' ],
            'awesome_dokan_settings_group',
            'awesome_dokan_general_section'
        );
		
		add_settings_field(
            'sidebar_hide_show',
            __( 'Show "Sidebar Hide/Show" Icon', 'awesome-dokan' ),
            [ $this, 'render_sidebar_hide_show_field' ],
            'awesome_dokan_settings_group',
            'awesome_dokan_general_section'
        );
		
		add_settings_field(
            'add_new_product',
            __( 'Show "Add New Product" Icon', 'awesome-dokan' ),
            [ $this, 'render_add_new_product_field' ],
            'awesome_dokan_settings_group',
            'awesome_dokan_general_section'
        );

        $icons = [ 'visit_store', 'withdraw', 'order_notification' ];
        foreach ( $icons as $icon ) {
            add_settings_field(
                "enable_icon_{$icon}",
                sprintf( __( 'Show "%s" Icon', 'awesome-dokan' ), ucwords( str_replace('_', ' ', $icon) ) ),
                function() use ( $icon ) {
                    $options = get_option( 'awesome_dokan_options' );
                    $checked = isset( $options["enable_icon_{$icon}"] ) ? $options["enable_icon_{$icon}"] : '';
                    echo '<label><input type="checkbox" name="awesome_dokan_options[enable_icon_' . esc_attr($icon) . ']" value="on" ' . checked( $checked, 'on', false ) . '> ' . esc_html__( 'Enable this icon in the header', 'awesome-dokan' ) . '</label>';
                },
                'awesome_dokan_settings_group',
                'awesome_dokan_general_section'
            );
        }
		
		add_settings_field(
            'hide_nav_common_links',
            __( 'Hide Default Nav Common Links', 'awesome-dokan' ),
            [ $this, 'render_hide_nav_common_links_field' ],
            'awesome_dokan_settings_group',
            'awesome_dokan_general_section'
        );
		
		add_settings_section(
            'awesome_dokan_theme_color_section',
            __( 'Theme Color', 'awesome-dokan' ),
            [ $this, 'render_theme_color_field' ],
            'awesome_dokan_styles_group'
        );

        add_settings_field(
            'theme_primary_color',
            __( 'Primary Color', 'awesome-dokan' ),
            [ $this, 'render_theme_primary_color_field' ],
            'awesome_dokan_styles_group',
            'awesome_dokan_theme_color_section',
        );

        add_settings_field(
            'theme_secondary_color',
            __( 'Secondary Color', 'awesome-dokan' ),
            [ $this, 'render_theme_secondary_color_field' ],
            'awesome_dokan_styles_group',
            'awesome_dokan_theme_color_section',
        );
		
		add_settings_section(
            'awesome_dokan_style_section',
            __( 'Customize Vendor Dashboard Color', 'awesome-dokan' ),
            '__return_false',
            'awesome_dokan_styles_group'
        );

        add_settings_field(
            'header_bg_color',
            __( 'Header Background Color', 'awesome-dokan' ),
            [ $this, 'render_header_bg_color_field' ],
            'awesome_dokan_styles_group',
            'awesome_dokan_style_section',
        );

        add_settings_field(
            'sidebar_bg_color',
            __( 'Sidebar Background Color', 'awesome-dokan' ),
            [ $this, 'render_sidebar_bg_color_field' ],
            'awesome_dokan_styles_group',
            'awesome_dokan_style_section',
        );

        add_settings_field(
            'content_bg_color',
            __( 'Content Background Color', 'awesome-dokan' ),
            [ $this, 'render_content_bg_color_field' ],
            'awesome_dokan_styles_group',
            'awesome_dokan_style_section',
        );

        add_settings_field(
            'header_font_color',
            __( 'Header Font Color', 'awesome-dokan' ),
            [ $this, 'render_header_font_color_field' ],
            'awesome_dokan_styles_group',
            'awesome_dokan_style_section',
        );

        add_settings_field(
            'sidebar_font_color',
            __( 'Sidebar Font Color', 'awesome-dokan' ),
            [ $this, 'render_sidebar_font_color_field' ],
            'awesome_dokan_styles_group',
            'awesome_dokan_style_section',
        );

        add_settings_field(
            'sidebar_font_active_color',
            __( 'Sidebar Font Active Color', 'awesome-dokan' ),
            [ $this, 'render_sidebar_font_active_color_field' ],
            'awesome_dokan_styles_group',
            'awesome_dokan_style_section',
        );

        add_settings_field(
            'sidebar_font_active_bg_color',
            __( 'Sidebar Font Active Background Color', 'awesome-dokan' ),
            [ $this, 'render_sidebar_font_active_bg_color_field' ],
            'awesome_dokan_styles_group',
            'awesome_dokan_style_section',
        );
    }

	public function render_enable_design_field() {
        $options = get_option( 'awesome_dokan_options' );
        $checked = isset( $options['enable_new_dashboard_design'] ) ? $options['enable_new_dashboard_design'] : '';
        ?>
        <label for="enable_new_dashboard_design">
            <input type="checkbox" name="awesome_dokan_options[enable_new_dashboard_design]" id="enable_new_dashboard_design" value="on" <?php checked( $checked, 'on' ); ?>>
            <?php echo '<span class="description">'.esc_html__( 'Check this box to replace the default Dokan dashboard with the new design.', 'awesome-dokan' ).'</span>'; ?>
        </label>
        <?php
    }

	public function render_dashboard_theme_field() {
        $options = get_option( 'awesome_dokan_options' );
        $checked = isset( $options['dashboard_theme'] ) ? $options['dashboard_theme'] : 'theme_one';
        ?>
        <label for="dashboard_theme_one">
            <input type="radio" name="awesome_dokan_options[dashboard_theme]" id="dashboard_theme_one" value="theme_one" <?php checked( $checked, 'theme_one' ); ?>> <?php echo esc_html__('Theme One','awesome-dokan'); ?>
        </label><br>
        <?php 
		if( function_exists('awesome_dokan_render_dashboard_theme_field') ){
			awesome_dokan_render_dashboard_theme_field();
		}else{
			?>
			<label>
				<input type="radio" disabled> <?php echo esc_html__('Theme Two','awesome-dokan'); ?> <span class="awesome-dokan-pro-badge">(Pro)</span>
			</label>
		<?php
		}
		?>
        <p><?php echo esc_html__('Select a Visual Style for the Dashboard.','awesome-dokan'); ?></p>
        <?php
    }

    public function render_greeting_field() {
        $options = get_option( 'awesome_dokan_options' );
        $value = isset( $options['dashboard_greeting'] ) ? $options['dashboard_greeting'] : '';
		?>
        <input type="text" name="awesome_dokan_options[dashboard_greeting]" value="<?php echo esc_attr( $value ); ?>" class="regular-text" placeholder="e.g., Hi, {user}">
		<p class="description"><?php echo esc_html__( 'To display the username, use this shortcode: {user}. Leave blank to show time-based greeting.', 'awesome-dokan' ); ?></p>
   		<?php
    }

    public function render_logo_field() {
        $options = get_option( 'awesome_dokan_options' );
        $value = isset( $options['dashboard_logo'] ) ? $options['dashboard_logo'] : '';
        ?>
        <select name="awesome_dokan_options[dashboard_logo]">
            <option value="site_icon" <?php selected( $value, 'site_icon' ); ?>><?php echo esc_html__( 'Site Icon', 'awesome-dokan' ); ?></option>
            <option value="main_logo" <?php selected( $value, 'main_logo' ); ?>><?php echo esc_html__( 'Main Logo', 'awesome-dokan' ); ?></option>
            <?php
			if( function_exists('awesome_dokan_dashboard_logo_source') && awesome_dokan_pro_is_active() ){
				awesome_dokan_dashboard_logo_source();
			}else{
				echo '<option disabled> '. esc_html__( 'Custom Logo', 'awesome-dokan' ) .' - (Pro)</option>';
			}
			?>
            <option value="none" <?php selected( $value, 'none' ); ?>><?php echo esc_html__( 'None', 'awesome-dokan' ); ?></option>
        </select>
        <?php
    }

    public function render_custom_logo_field() {
		if( function_exists('awesome_dokan_render_custom_logo_field') && awesome_dokan_pro_is_active() ){
			awesome_dokan_render_custom_logo_field();
			return;
		}
        ?>
        <input type="text" value="" class="regular-text" disabled>
        <button type="button" class="button" disabled><?php echo esc_html__('Upload Logo','awesome-dokan'); ?></button> <span class="awesome-dokan-pro-badge">(Pro)</span>
        <?php
    }

    public function render_logo_url_field() {
		if( function_exists('awesome_dokan_render_logo_url') && awesome_dokan_pro_is_active() ){
			awesome_dokan_render_logo_url();
			return;
		}
        ?>
        <input type="text" class="regular-text" placeholder="Enter logo URL" disabled> <span class="awesome-dokan-pro-badge">(Pro)</span>
        <p class="description"><?php echo esc_html__('Default is set to home URL','awesome-dokan'); ?></p>
        <?php
    }

    public function render_sidebar_hide_show_field() {
		if( function_exists('awesome_dokan_render_sidebar_hide_show_field') && awesome_dokan_pro_is_active() ){
			awesome_dokan_render_sidebar_hide_show_field();
			return;
		}
        ?>
        <label><input type="checkbox" disabled class="regular-text"> <?php echo esc_html__('Show this icon in the header when using the desktop site.','awesome-dokan'); ?> <span class="awesome-dokan-pro-badge">(Pro)</span></label>
        <?php
    }

    public function render_add_new_product_field() {
		if( function_exists('awesome_dokan_render_add_new_product_field') && awesome_dokan_pro_is_active() ){
			awesome_dokan_render_add_new_product_field();
			return;
		}
        ?>
        <label><input type="checkbox" disabled class="regular-text"> <?php echo esc_html__('Show this icon in the header.','awesome-dokan'); ?> <span class="awesome-dokan-pro-badge">(Pro)</span></label>
        <?php
    }

    public function render_hide_nav_common_links_field() {
		if( function_exists('awesome_dokan_render_hide_nav_common_links_field') && awesome_dokan_pro_is_active() ){
			awesome_dokan_render_hide_nav_common_links_field();
			return;
		}
        ?>
        <label><input type="checkbox" disabled class="regular-text"> <?php echo esc_html__('Common links such as Visit Store, Edit Account, and Logout are located at the bottom of the sidebar navigation. (These links are available in the dashboard header).','awesome-dokan'); ?> <span class="awesome-dokan-pro-badge">(Pro)</span></label>
        <?php
    }
	
	public function render_theme_color_field() {
        echo '<p class="description">'.esc_html__( 'Select your theme colors. These colors will be applied to your Dokan marketplace.', 'awesome-dokan' ).'</p>';
    }
	
	public function render_theme_primary_color_field() {
        $styles = get_option( 'awesome_dokan_styles' );
        $theme_primary_color = isset( $styles['theme_primary_color'] ) ? $styles['theme_primary_color'] : '';
        ?>
        <label for="theme_primary_color">
            <input type="text" name="awesome_dokan_styles[theme_primary_color]" class="awesome-dokan-color-field" id="theme_primary_color" value="<?php echo esc_attr($theme_primary_color); ?>">
        </label>
        <?php
    }
	
	public function render_theme_secondary_color_field() {
        $styles = get_option( 'awesome_dokan_styles' );
        $theme_secondary_color = isset( $styles['theme_secondary_color'] ) ? $styles['theme_secondary_color'] : '';
        ?>
        <label for="theme_secondary_color">
            <input type="text" name="awesome_dokan_styles[theme_secondary_color]" class="awesome-dokan-color-field" id="theme_secondary_color" value="<?php echo esc_attr($theme_secondary_color); ?>">
        </label>
        <br><br>
        <?php
    }
	
	public function render_header_bg_color_field() {
        $styles = get_option( 'awesome_dokan_styles' );
        $header_bg_color = isset( $styles['header_bg_color'] ) ? $styles['header_bg_color'] : '';
        ?>
        <label for="header_bg_color">
            <input type="text" name="awesome_dokan_styles[header_bg_color]" class="awesome-dokan-color-field" id="header_bg_color" value="<?php echo esc_attr($header_bg_color); ?>">
        </label>
        <?php
    }
	
	public function render_sidebar_bg_color_field() {
        $styles = get_option( 'awesome_dokan_styles' );
        $sidebar_bg_color = isset( $styles['sidebar_bg_color'] ) ? $styles['sidebar_bg_color'] : '';
        ?>
        <label for="sidebar_bg_color">
            <input type="text" name="awesome_dokan_styles[sidebar_bg_color]" class="awesome-dokan-color-field" id="sidebar_bg_color" value="<?php echo esc_attr($sidebar_bg_color); ?>">
        </label>
        <?php
    }
	
	public function render_content_bg_color_field() {
        $styles = get_option( 'awesome_dokan_styles' );
        $content_bg_color = isset( $styles['content_bg_color'] ) ? $styles['content_bg_color'] : '';
        ?>
        <label for="content_bg_color">
            <input type="text" name="awesome_dokan_styles[content_bg_color]" class="awesome-dokan-color-field" id="content_bg_color" value="<?php echo esc_attr($content_bg_color); ?>">
        </label>
        <?php
    }
	
	public function render_header_font_color_field() {
        $styles = get_option( 'awesome_dokan_styles' );
        $header_font_color = isset( $styles['header_font_color'] ) ? $styles['header_font_color'] : '';
        ?>
        <label for="header_font_color">
            <input type="text" name="awesome_dokan_styles[header_font_color]" class="awesome-dokan-color-field" id="header_font_color" value="<?php echo esc_attr($header_font_color); ?>">
        </label>
        <?php
    }
	
	public function render_sidebar_font_color_field() {
        $styles = get_option( 'awesome_dokan_styles' );
        $header_font_color = isset( $styles['sidebar_font_color'] ) ? $styles['sidebar_font_color'] : '';
        ?>
        <label for="sidebar_font_color">
            <input type="text" name="awesome_dokan_styles[sidebar_font_color]" class="awesome-dokan-color-field" id="sidebar_font_color" value="<?php echo esc_attr($header_font_color); ?>">
        </label>
        <?php
    }
	
	public function render_sidebar_font_active_color_field() {
        $styles = get_option( 'awesome_dokan_styles' );
        $sidebar_font_active_color = isset( $styles['sidebar_font_active_color'] ) ? $styles['sidebar_font_active_color'] : '';
        ?>
        <label for="sidebar_font_active_color">
            <input type="text" name="awesome_dokan_styles[sidebar_font_active_color]" class="awesome-dokan-color-field" id="sidebar_font_active_color" value="<?php echo esc_attr($sidebar_font_active_color); ?>">
        </label>
        <?php
    }
	
	public function render_sidebar_font_active_bg_color_field() {
        $styles = get_option( 'awesome_dokan_styles' );
        $sidebar_font_active_bg_color = isset( $styles['sidebar_font_active_bg_color'] ) ? $styles['sidebar_font_active_bg_color'] : '';
        ?>
        <label for="sidebar_font_active_bg_color">
            <input type="text" name="awesome_dokan_styles[sidebar_font_active_bg_color]" class="awesome-dokan-color-field" id="sidebar_font_active_bg_color" value="<?php echo esc_attr($sidebar_font_active_bg_color); ?>">
        </label>
        <?php
    }

    public function settings_page_html() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <?php settings_errors(); ?>
            <form action="options.php" method="post">
				<div class="awesome-dokan-container">
					<?php settings_fields( 'awesome_dokan_settings_group' ); ?>
					<?php //settings_fields( 'awesome_dokan_styles_group' ); ?>
					<!-- Tab Navigation -->
					<div class="awesome-dokan-tabs-wrapper">
						<ul class="awesome-dokan-tabs-nav">
							<li>
								<a href="#awesome-dokan-tab1" class="active">General Settings</a>
							</li>
							<li>
								<a href="#awesome-dokan-tab2">Styles</a>
							</li>
						</ul>
					</div>

					<!-- Tab Content -->
					<div class="awesome-dokan-tabs-content">
						<!-- Tab 1 Content -->
						<div id="awesome-dokan-tab1" class="awesome-dokan-tab-panel">
							<?php
							do_settings_sections( 'awesome_dokan_settings_group' );
							?>
						</div>

						<!-- Tab 2 Content -->
						<div id="awesome-dokan-tab2" class="awesome-dokan-tab-panel" style="display: none;">
							<?php
							do_settings_sections( 'awesome_dokan_styles_group' );
							?>
						</div>
					</div>
					<?php
					submit_button( __( 'Save Settings', 'awesome-dokan' ) );
					?>
				</div>
            </form>
        </div>
        <?php
    }
	
	//Sanitize fields
	public function awesome_dokan_sanitize_options( $input ) {
		$output = array();

		if ( is_array( $input ) ) {
			foreach ( $input as $key => $value ) {

				switch ( $key ) {
					case 'custom_logo':
					case 'logo_url':
						$output[ $key ] = sanitize_url( $value );
						break;

					default:
						$output[ $key ] = sanitize_text_field( $value );
						break;
				}

			}
		} else {
			$output = sanitize_text_field( $input );
		}

		return $output;
	}

}

Awesome_Dokan_Settings::instance();
