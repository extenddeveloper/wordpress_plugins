<?php

namespace ExtendDevs\Academy;

class Admin {

    function __construct() {
        
        $instructors = new Admin\Instructors();

        new Admin\Menu( $instructors );

        $this->dispatch_actions( $instructors );

    }

    public function dispatch_actions( $instructors ){

        add_action( 'admin_init', array( $instructors, 'form_handler' ) );
        add_action( 'admin_post_extenddevs_academy_delete_instructor', array( $instructors, 'delete_handler' ) );

    }

}