<?php

namespace ExtendDevs\Academy;

class Assets {

    function __construct() {

        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);

    }

    function get_styles(){
        return [
            'extenddev-academy-admin' => [
                'src'     => EXTENDDEVS_ACADEMY_ASSETS . 'css/admin.css',
                'version' => filemtime(EXTENDDEVS_ACADEMY_DIR . 'assets/css/admin.css'),
            ],
            'extenddev-academy-frontend' => [
                'src'     => EXTENDDEVS_ACADEMY_ASSETS . 'css/frontend.css',
                'version' => filemtime(EXTENDDEVS_ACADEMY_DIR . 'assets/css/frontend.css'),
            ]
        ];
    }

    function get_scripts(){

        return [
            'extenddev-academy-admin' => [
                'src'     => EXTENDDEVS_ACADEMY_ASSETS . 'js/admin.js',
                'version' => filemtime(EXTENDDEVS_ACADEMY_DIR . 'assets/js/admin.js'),
                'deps'    => ['jquery', 'wp-util']
            ],
            'extenddev-academy-frontend' => [
                'src'     => EXTENDDEVS_ACADEMY_ASSETS . 'js/frontend.js',
                'version' => filemtime(EXTENDDEVS_ACADEMY_DIR . 'assets/js/frontend.js'),
                'deps'    => ['jquery']
            ],
            'extenddev-academy-frontend-enquiry' => [
                'src'     => EXTENDDEVS_ACADEMY_ASSETS . 'js/enquiry.js',
                'version' => filemtime(EXTENDDEVS_ACADEMY_DIR . 'assets/js/enquiry.js'),
                'deps'    => ['jquery']
            ]
        ];

    }

    public function enqueue_assets(){


        /**
         * register styles
         * @var array
         */
        $styles = $this->get_styles();

        foreach($styles as $handle => $style){
            $deps = isset($style['deps']) ? $style['deps'] : false;
            wp_register_style($handle, $style['src'], $deps, $style['version'], 'all');
        }

        /**
         * register scripts
         * @var array
         */
        $scripts = $this->get_scripts();

        foreach($scripts as $handle => $script){
            $deps = isset($script['deps']) ? $script['deps'] : false;
            wp_register_script($handle, $script['src'], $script['deps'], $script['version'], true);
        }

        wp_localize_script('extenddev-academy-frontend-enquiry', 'enquiry', [
           
            'ajaxurl' => admin_url('admin-ajax.php'),
            'message' => __('Your message has been sent successfully', 'extenddev-academy'),
            'error' => __('Something went wrong', 'extenddev-academy')
            
        ]);
        wp_localize_script('extenddev-academy-admin', 'wd_admin_util_ajax', [
            
            'nonce' => wp_create_nonce('wd_ac_admin_nonce'),
            'confirm' => __('Are you sure?', 'extenddev-academy'),
            'error' => __('Something went wrong', 'extenddev-academy')
            
        ]);

    }



}