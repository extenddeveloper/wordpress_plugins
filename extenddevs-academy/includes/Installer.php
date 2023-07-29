<?php

namespace ExtendDevs\Academy;

class Installer {

    public function run() {

        $this->add_version();
        $this->create_table();

    }

    public function add_version() {

        $installed = get_option( 'extenddevs_academy_installed' );
        
        if( !$installed ) {

            update_option( 'extenddevs_academy_installed', time() );

        }

        update_option('extenddevs_academy_version', EXTENDDEVS_ACADEMY_VERSION);

    }

    public function create_table(){

        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $schema = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}extenddevs_academy_instructors (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL DEFAULT '',
            email VARCHAR(255) DEFAULT NULL,
            address VARCHAR(255) DEFAULT NULL,
            phone VARCHAR(30) DEFAULT NULL,
            created_by bigint(20) UNSIGNED NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) {$charset_collate};";

        if( ! function_exists( 'dbDelta' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        }

        dbDelta( $schema );


    }

}