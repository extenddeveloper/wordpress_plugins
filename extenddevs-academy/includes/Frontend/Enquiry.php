<?php 

namespace ExtendDevs\Academy\Frontend;

/**
 * Enquiry Shortcode class handler
 * @since 1.0
 * @access public
 */

 class Enquiry {

     /**
      * initialize the shortcode
      */
     function __construct() {

         add_shortcode( 'wd_enquiry_form', array( $this, 'extenddevs_academy_render_enquiry_shortcode' ) );

     }

     /**
      * render the shortcode
      * @param array $atts
      * @param string $content
      *
      * @return string
      */

     public function extenddevs_academy_render_enquiry_shortcode($atts , $content = '') {

         wp_enqueue_style('extenddev-academy-frontend');
         wp_enqueue_script('extenddev-academy-frontend-enquiry');

         ob_start();

         require __DIR__ . '/views/enquiry-form.php';

         return ob_get_clean();

     }

 }