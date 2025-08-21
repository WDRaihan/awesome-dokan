<?php
/**
 * Plugin Name:       Awesome Dokan
 * Requires Plugins:  dokan-lite
 * Description:       Replaces the default Dokan dashboard with a fresh, modern, and user-friendly design.
 * Version:           1.0.0
 * Author:            Raihan
 * Requires at least: 5.2
 * Requires PHP: 	  7.2
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       awesome-dokan
 * Domain Path:       /languages
 */

// Don't call this file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * The main class for the Awesome Dokan plugin.
 */
final class Awesome_Dokan {

    /**
     * Plugin version.
     *
     * @var string
     */
    const VERSION = '1.1.0';

    /**
     * Class constructor.
     */
    private function __construct() {
        $this->define_constants();
        $this->includes();
        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
        add_filter( 'plugin_action_links_' . plugin_basename( AWESOME_DOKAN_FILE ), [ $this, 'add_settings_link' ] );
    }

    /**
     * Initializes a singleton instance.
     *
     * @return \Awesome_Dokan
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants.
     *
     * @return void
     */
    public function define_constants() {
        define( 'AWESOME_DOKAN_VERSION', self::VERSION );
        define( 'AWESOME_DOKAN_FILE', __FILE__ );
        define( 'AWESOME_DOKAN_PATH', __DIR__ );
        define( 'AWESOME_DOKAN_URL', plugins_url( '', AWESOME_DOKAN_FILE ) );
        define( 'AWESOME_DOKAN_ASSETS', AWESOME_DOKAN_URL . '/assets' );
    }

    /**
     * Include required files.
     *
     * @return void
     */
    public function includes() {
        if ( is_admin() ) {
            require_once AWESOME_DOKAN_PATH . '/includes/class-awesome-dokan-settings.php';
        }
    }

    /**
     * Initialize the plugin.
     *
     * @return void
     */
    public function init_plugin() {
        // Get our setting
        $options = get_option( 'awesome_dokan_options' );
        $is_enabled = isset( $options['enable_new_design'] ) ? $options['enable_new_design'] : '';

        // Only load the new design if the setting is checked
        if ( 'on' == $is_enabled ) {
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
            $this->override_dokan_template();
        }
    }

    /**
     * Enqueue plugin scripts and styles.
     *
     * @return void
     */
    public function enqueue_assets() {
        if ( function_exists( 'dokan_is_seller_dashboard' ) && dokan_is_seller_dashboard() ) {
            wp_enqueue_style( 'awesome-dokan-style', AWESOME_DOKAN_ASSETS . '/css/dashboard.css', [], AWESOME_DOKAN_VERSION );
            wp_enqueue_script( 'awesome-dokan-script', AWESOME_DOKAN_ASSETS . '/js/dashboard.js', [ 'jquery' ], AWESOME_DOKAN_VERSION, true );
        }
    }

    /**
     * Override Dokan templates with our custom ones.
     *
     * @param string $template      The original template path.
     * @param string $slug          The template slug.
     * @param string $name          The template name.
     *
     * @return string The new template path.
     */
    public function override_dokan_template() {
        require_once AWESOME_DOKAN_PATH . '/templates/dashboard.php';
    }

    /**
     * Add a "Settings" link to the plugin actions.
     *
     * @param array $links
     * @return array
     */
    public function add_settings_link( $links ) {
        $settings_link = '<a href="' . admin_url( 'options-general.php?page=awesome-dokan' ) . '">' . __( 'Settings', 'awesome-dokan' ) . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }
}

/**
 * Initializes the main plugin.
 *
 * @return \Awesome_Dokan
 */
function awesome_dokan() {
    return Awesome_Dokan::init();
}

// Kick-off the plugin
awesome_dokan();
