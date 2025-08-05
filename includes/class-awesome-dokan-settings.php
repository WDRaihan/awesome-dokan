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
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_media_uploader' ] );
    }

    /**
     * Enqueue WordPress media uploader.
     */
    public function enqueue_media_uploader() {
        if ( 'awesome-dokan' != $_GET['page'] ) {
            return;
        }
        wp_enqueue_media();
        wp_enqueue_script( 'awesome-dokan-admin-js', AWESOME_DOKAN_ASSETS . '/js/admin.js', [ 'jquery' ], null, true );
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
        register_setting( 'awesome_dokan_settings_group', 'awesome_dokan_options' );

        add_settings_section(
            'awesome_dokan_general_section',
            __( 'General Settings', 'awesome-dokan' ),
            '__return_false',
            'awesome_dokan_settings_group'
        );

        add_settings_field(
            'enable_new_design',
            __( 'Enable New Dashboard Design', 'awesome-dokan' ),
            [ $this, 'render_enable_design_field' ],
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

        $icons = [ 'visit_store', 'add_product', 'withdraw', 'notifications', 'avatar' ];
        foreach ( $icons as $icon ) {
            add_settings_field(
                "enable_icon_{$icon}",
                sprintf( __( 'Show "%s" Icon', 'awesome-dokan' ), ucwords( str_replace('_', ' ', $icon) ) ),
                function() use ( $icon ) {
                    $options = get_option( 'awesome_dokan_options' );
                    $checked = isset( $options["enable_icon_{$icon}"] ) ? $options["enable_icon_{$icon}"] : 'on';
                    echo '<label><input type="checkbox" name="awesome_dokan_options[enable_icon_' . esc_attr($icon) . ']" ' . checked( $checked, 'on', false ) . '> ' . __( 'Enable this icon in the header', 'awesome-dokan' ) . '</label>';
                },
                'awesome_dokan_settings_group',
                'awesome_dokan_general_section'
            );
        }
    }

    public function render_enable_design_field() {
        $options = get_option( 'awesome_dokan_options' );
        $checked = isset( $options['enable_new_design'] ) ? $options['enable_new_design'] : 'on';
        ?>
        <label for="enable_new_design">
            <input type="checkbox" name="awesome_dokan_options[enable_new_design]" id="enable_new_design" <?php checked( $checked, 'on' ); ?>>
            <?php echo '<p class="description">'.esc_html__( 'Check this box to replace the default Dokan dashboard with the new design.', 'awesome-dokan' ).'</p>'; ?>
        </label>
        <?php
    }

    public function render_greeting_field() {
        $options = get_option( 'awesome_dokan_options' );
        $value = isset( $options['dashboard_greeting'] ) ? $options['dashboard_greeting'] : '';
        echo '<input type="text" name="awesome_dokan_options[dashboard_greeting]" value="' . esc_attr( $value ) . '" class="regular-text" placeholder="e.g., Hi, {user}">';
		echo '<p class="description">'.esc_html__( 'To display the username, use this shortcode: {user}. Leave blank to show time-based greeting.', 'awesome-dokan' ).'</p>';
    }

    public function render_logo_field() {
        $options = get_option( 'awesome_dokan_options' );
        $value = isset( $options['dashboard_logo'] ) ? $options['dashboard_logo'] : '';
        ?>
        <select name="awesome_dokan_options[dashboard_logo]">
            <option value="site_icon" <?php selected( $value, 'site_icon' ); ?>><?php esc_html_e( 'Site Icon', 'awesome-dokan' ); ?></option>
            <option value="main_logo" <?php selected( $value, 'main_logo' ); ?>><?php esc_html_e( 'Main Logo', 'awesome-dokan' ); ?></option>
            <option value="custom_logo" <?php selected( $value, 'custom_logo' ); ?>><?php esc_html_e( 'Custom Logo', 'awesome-dokan' ); ?></option>
            <option value="dashboard_icon" <?php selected( $value, 'dashboard_icon' ); ?>><?php esc_html_e( 'Dashboard Icon', 'awesome-dokan' ); ?></option>
            <option value="none" <?php selected( $value, 'none' ); ?>><?php esc_html_e( 'None', 'awesome-dokan' ); ?></option>
        </select>
        <?php
    }

    public function render_custom_logo_field() {
        $options = get_option( 'awesome_dokan_options' );
        $logo_url = isset( $options['custom_logo'] ) ? esc_url( $options['custom_logo'] ) : '';
        ?>
        <input type="text" name="awesome_dokan_options[custom_logo]" id="custom_logo" value="<?php echo $logo_url; ?>" class="regular-text">
        <button type="button" class="button" id="upload_custom_logo">Upload Logo</button>
        <div id="custom_logo_preview" style="margin-top:10px;">
            <?php if ( $logo_url ) : ?>
                <img src="<?php echo $logo_url; ?>" style="max-height: 50px;" />
            <?php endif; ?>
        </div>
        <?php
    }

    public function render_logo_url_field() {
        $options = get_option( 'awesome_dokan_options' );
        $logo_url = isset( $options['logo_url'] ) ? esc_url( $options['logo_url'] ) : '';
        ?>
        <input type="text" name="awesome_dokan_options[logo_url]" id="logo_url" value="<?php echo esc_url($logo_url); ?>" class="regular-text" placeholder="Enter logo URL">
        <p class="description">Default home URL</p>
        <?php
    }

    public function settings_page_html() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'awesome_dokan_settings_group' );
                do_settings_sections( 'awesome_dokan_settings_group' );
                submit_button( __( 'Save Settings', 'awesome-dokan' ) );
                ?>
            </form>
        </div>
        <?php
    }
}

Awesome_Dokan_Settings::instance();
