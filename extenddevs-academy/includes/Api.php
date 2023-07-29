<?php

namespace ExtendDevs\Academy;

/**
 * API
 * @package ExtendDevs\Academy
 * @since 1.0
 */
class Api {

    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_api' ) );
    }

    public function register_api() {
        
       $instructors = new Api\Instructors();

       $instructors->register_routes();

    }

}