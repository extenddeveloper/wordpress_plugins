<?php

namespace ExtendDevs\Academy;

/**
 * load frontend plugin functions
 * @since 1.0
 * @access public 
 */

 class Frontend {

     function __constructor(){

         new Frontend\Shortcode();
         new Frontend\Enquiry();
     }

 }

