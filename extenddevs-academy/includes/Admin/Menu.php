<?php

namespace ExtendDevs\Academy\Admin;

class Menu {
    
    public $instructors;

    function __construct($instructors) {
        
        $this->instructors = $instructors;
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );

    }

    public function admin_menu() {

        $parent_slug  = 'extenddev-academy';
        $capability   = 'manage_options';

        $hook = add_menu_page( __('ExtendDevs Academy', 'extenddev-academy'), __('ExtendDevs Academy', 'extenddev-academy'), $capability, $parent_slug, array($this, 'extenddevs_instructors_page'));
        add_submenu_page($parent_slug, __('Instructors', 'extenddev-academy') , __('Instructors', 'extenddev-academy'), $capability, $parent_slug, array($this, 'extenddevs_instructors_page'));
        add_submenu_page($parent_slug, __('Settings', 'extenddev-academy') , __('Settings', 'extenddev-academy'), $capability, 'extenddevs_settings', array($this, 'extenddevs_academy_form_settings'));

        add_action('admin_head-'. $hook, array($this, 'enqueue_assets'));

    }

    /**
     * Plugin page callback function to display the plugin page
     *
     * @return void
     */
    function extenddevs_instructors_page() {
        
        $this->instructors->instuctors_page();

    }

    /**
     * Settings page callback function to display the plugin page
     * @return void
     */

    function extenddevs_academy_form_settings() {
        echo 'Settings Page';
    }

    function enqueue_assets(){
        wp_enqueue_style('extenddev-academy-admin');
        wp_enqueue_script('extenddev-academy-admin');
    }
    
}