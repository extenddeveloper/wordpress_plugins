<?php

namespace ExtendDevs\Academy\Frontend;

class Shortcode {

    /**
     * initialize the shortcode
     */
    function __construct() {

        add_shortcode( 'extenddevs_academy', array( $this, 'extenddevs_academy_render_shortcode' ) );

    }

    /**
     * render the shortcode
     * @param array $atts
     * @param string $content
     *
     * @return string
     */
    public function extenddevs_academy_render_shortcode($atts , $content = '') {
        
        wp_enqueue_style('extenddev-academy-frontend');
        wp_enqueue_script('extenddev-academy-frontend');

        return '<div class="extenddev-academy-shortcode">Shortcode Text</div>';
        
    }

}