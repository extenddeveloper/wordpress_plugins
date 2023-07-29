<?php

/**
 * Inserts a new instructor
 * @since 1.0.0
 * @return void
 * @param array $args
 * 
 * @return int|\WP_Error
 *
 */
function extenddevs_insert_instructor($args = []){
    
    global $wpdb;

    if(!isset($args['name']) || empty($args['name'])){

        return new \WP_Error('invalid-name', __('Name is required', 'extenddev-academy'));

    }

    $default = [
        'name' => '',
        'email' => '',
        'phone' => '',
        'address' => '',
        'created_by' => get_current_user_id(),
        'created_at' => date('Y-m-d H:i:s'),
    ];

    $data = wp_parse_args($args, $default);

    // print_r($data);

    if($data['id']){

       $updated = $wpdb->update(

                    $wpdb->prefix . 'extenddevs_academy_instructors',
                    $data,
                    ['id' => $data['id']],
                    ['%s', '%s', '%s', '%s', '%d', '%s'],
                    ['%d']

                );
        return $updated;

    }else{

        $inserted = $wpdb->insert(
            $wpdb->prefix . 'extenddevs_academy_instructors',
            $data,
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
                '%s'
            )
        );
    
        if( !$inserted ){
    
            return new \WP_Error('failed-to-insert', $wpdb->last_error);
    
        }
    
        return $wpdb->insert_id;

    }


}


/**
 * Fetch instructors
 * @since 1.0.0
 * @return array
 * 
 */

function extenddevs_get_instructors($args = []){

    global $wpdb;

    $default = [
        'per_page' => 20,
        'offset' => 0,
        'orderby' => 'id',
        'order' => 'DESC',
    ];

    $args = wp_parse_args($args, $default);

    $table = $wpdb->prefix . 'extenddevs_academy_instructors';
    $instructors = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table
            ORDER BY $args[orderby] $args[order]
            LIMIT %d, %d",
            $args['offset'], $args['per_page']
        ), ARRAY_A

    );
    return $instructors;
    
}

/**
 * Get the count of total instructors
 * @since 1.0.0
 * @return int
 */

function extenddevs_get_instructors_count(){
    global $wpdb;
    $table = $wpdb->prefix . 'extenddevs_academy_instructors';

    return (int) $wpdb->get_var(
        "SELECT COUNT(id) FROM $table"
    );

}


/**
 * Get single instructor
 * @since 1.0.0
 * @return array
 */

 function extenddevs_get_instructor($id){
    global $wpdb;
    $table = $wpdb->prefix . 'extenddevs_academy_instructors';
    return $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $table WHERE id = %d", $id
        ), ARRAY_A
    );
 }

 /**
  * Delete instructor
  * @since 1.0.0
  * @return void
  */

 function extenddevs_delete_instructor($id){
    
    global $wpdb;
    $table = $wpdb->prefix . 'extenddevs_academy_instructors';
    return $wpdb->delete(
        $table,
        [
            'id' => $id
        ],
        [
            '%d'
        ]
    );

 }