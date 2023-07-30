<?php

/**
 * Plugin Name: ExtendDevs Academy
 * Plugin URI: https://extenddeveloper.com
 * Description: ExtendDevs Academy
 * Author: Jewel Hossain
 * Version: 1.0
 * Author URI: https://extenddeveloper.com
 * Text Domain: extenddev-academy
 * Domain Path: /languages
 * WC requires at least: 3.0
 * WC tested up to: 6.0
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 require_once __DIR__ . '/vendor/autoload.php';

final class ExtendDevs_Academy {

    /**
     * Version of the plugin.
     * @var string
     */
    const VERSION = '1.0';

    private function __construct() {

        $this->define_constances();

        register_activation_hook( __FILE__ , array( $this, 'activate' ) );

        register_activation_hook( __FILE__ , array( $this, 'deactivate' ) );

        add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );

    }
    public function init_plugin() {

        load_plugin_textdomain( 'extenddev-academy', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

        new ExtendDevs\Academy\Assets();

        if( defined('DOING_AJAX') && DOING_AJAX ){
            new ExtendDevs\Academy\Ajax();
        }

        if( is_admin() ){

            new ExtendDevs\Academy\Admin();

        }else{

            new ExtendDevs\Academy\Frontend(); // missing frontend shortcode in this way called

            new ExtendDevs\Academy\Frontend\Shortcode();
            new ExtendDevs\Academy\Frontend\Enquiry();

        }

        new ExtendDevs\Academy\Api();

    }


    /**
     * Initialize the singleton instance.
     * @return \ExtendDevs_Academy
     * @since 1.0
     * @access public
     * @static
     */

    public static function get_instance() {

        static $instance = false;

        if ( ! $instance  ) {

            $instance = new self;
        }

        return $instance;

    }

    public function define_constances() {

        define( 'EXTENDDEVS_ACADEMY_DIR', plugin_dir_path( __FILE__ ) );
        define( 'EXTENDDEVS_ACADEMY_URL', plugin_dir_url( __FILE__ ) );
        define( 'EXTENDDEVS_ACADEMY_VERSION', self::VERSION );
        define( 'EXTENDDEVS_ACADEMY_FILE', __FILE__ );
        define( 'EXTENDDEVS_ACADEMY_PATH', __DIR__ );
        define( 'EXTENDDEVS_ACADEMY_ASSETS', EXTENDDEVS_ACADEMY_URL . 'assets/' );

    }

    /**
     * Activate Functions for the plugin
     *
     * @return void
     */
    public function activate() {

        $installer = new ExtendDevs\Academy\Installer();
        $installer->run();
        
    }

    /**
     * Deactivate Functions for the plugin
     *
     * @return void
     */
    public function deactivate() {
        
    }

}

/**
 * Initialize the main instance of EXTENDDEVS_ACADEMY.
 * @return \ExtendDevs_Academy
 */

 function extenddevs_academy() {

    return ExtendDevs_Academy::get_instance();

 }

extenddevs_academy();
