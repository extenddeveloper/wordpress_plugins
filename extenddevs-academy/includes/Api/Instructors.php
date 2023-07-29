<?php 

namespace ExtendDevs\Academy\Api;

use WP_REST_Controller;
use WP_REST_Server;
use WP_Error;

/**
 * API
 * @package ExtendDevs\Academy
 * @since 1.0
 * @author Jewel Hossain
 * @version 1.0
 */

class Instructors extends WP_REST_Controller {

    function __construct(){

        $this->namespace = 'academy/v1';
        $this->rest_base = 'instructors';
        
    }

    public function register_routes(){
        register_rest_route( 
            $this->namespace,
            '/' . $this->rest_base,
            array(
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_items' ),
                    'permission_callback' => array( $this, 'get_items_permissions_check' ),
                    'args'                => $this->get_collection_params(),
                ],
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array( $this, 'create_item' ),
                    'permission_callback' => array( $this, 'get_create_permissions_check' ),
                    'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
                ],
                'schema' => $this->get_item_schema(),
            ) 
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)',
            [
                'args' => [
                    'id' => [
                        'description' => 'Unique identifier for the resource.',
                        'type' => 'integer',
                    ]
                ],
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_item' ),
                    'permission_callback' => array( $this, 'get_item_permissions_check' ),
                    'args'                => [
                        'context' => $this->get_context_param( [ 'default' => 'view' ] ),
                    ]
                ],
                [
                    'methods'             => WP_REST_Server::EDITABLE,
                    'callback'            => array( $this, 'update_item' ),
                    'permission_callback' => array( $this, 'get_edit_permissions_check' ),
                    'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
                ],
                [
                    'methods'             => WP_REST_Server::DELETABLE,
                    'callback'            => array( $this, 'delete_item' ),
                    'permission_callback' => array( $this, 'get_delete_permissions_check' ),
                ],
                'schema' => $this->get_item_schema(),

            ]
        );

    }


    /**
     * get instructor if exists by id
     *
     * @param int $id
     * @return \WP_REST_Response | \WP_Error
     */
    protected function get_instructor( $id ){
        
        $instructor = extenddevs_get_instructor( $id );

        if(!$instructor){
            return new WP_Error( 'Rest instructor invalid id', __( 'Instructor not found.', 'extenddev-academy' ), [ 'status' => 404 ] );
        }

        return $instructor;

    }

    /**
     * check if a given request has access to read items
     *
     * @param \WP_REST_Request $request
     * @return boolean
     */
    public function get_item_permissions_check( $request ){

        if( !current_user_can( 'manage_options' ) ){
            return false;
        }

        $instructor = $this->get_instructor( $request['id'] );

        if( is_wp_error( $instructor ) ){
            return $instructor;
        }

        return true;

    } 

    /**
     * get instructor by id from the collection
     *
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response | \WP_Error
     */
    public function get_item( $request ){
        $instructor = $this->get_instructor( $request['id'] );

        $response = $this->prepare_item_for_response( $instructor, $request );
        $response = rest_ensure_response( $response );

        return $response;

    }

    /**
     * check if a given request has access to delete items
     *
     * @param \WP_REST_Request $request
     * @return boolean
     */
    public function get_delete_permissions_check( $request ){
        
        return $this->get_item_permissions_check( $request );

    }


    /**
     * delete instructor by id from the collection
     *
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response | \WP_Error
     */
    public function delete_item( $request ){
        
        $instructor = $this->get_instructor( $request['id'] );
        $response = $this->prepare_item_for_response( $instructor, $request );

        $deleted = extenddevs_delete_instructor( $request['id'] );

        if( ! $deleted ){
            return new WP_Error( 'Rest instructor not deleted', __( 'Instructor Can not be deleted.', 'extenddev-academy' ), [ 'status' => 400 ] );
        }

        $data = [
            'deleted' => true,
            'previous' => $instructor 
        ];

        $response = rest_ensure_response( $data );

        return $data;

    }


    /**
     * check if a given request has access to create items
     *
     * @param \WP_REST_Request $request
     * @return boolean
     */
    public function get_create_permissions_check( $request ){
        return $this->get_items_permissions_check( $request );
    }


    /**
     * create instructor from the collection of items
     *
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response | \WP_Error
     */
    public function create_item( $request ){
        
        $instructor = $this->prepare_items_for_database( $request );

        if(is_wp_error( $instructor )){
            return $instructor;
        }

        $instructor_id = extenddevs_insert_instructor( $instructor );

        if(is_wp_error( $instructor_id )){
            $instructor_id->add_data(['status'=> 400]);
            return $instructor_id;
        }

        $instructor = $this->get_instructor( $instructor_id );
        $response = $this->prepare_item_for_response( $instructor, $request );
        $response->set_status(201);
        $response->header('location', rest_url( sprintf( '/%s/%s/%d', $this->namespace, $this->rest_base, $instructor_id) ));

        return rest_ensure_response($response);

    }

    public function prepare_items_for_database($request){
        $prepared = [];

        if( isset( $request['name'] ) ){
            $prepared['name'] = sanitize_text_field( $request['name'] );
        }
        if( isset( $request['email'] ) ){
            $prepared['email'] = sanitize_email( $request['email'] );
        }
        if( isset( $request['phone'] ) ){
            $prepared['phone'] = sanitize_text_field( $request['phone'] );
        }
        if( isset( $request['address'] ) ){
            $prepared['address'] = sanitize_text_field( $request['address'] );
        }

        return $prepared;

    }

    /**
     * update instructor from the collection of items
     *
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response | \WP_Error
     */
    public function update_item( $request ){
        
        $instructor = $this->get_instructor( $request['id'] );
        $prepared = $this->prepare_items_for_database( $request );

        $prepared = array_merge( (array) $instructor, $prepared );

        $updated = extenddevs_insert_instructor( $prepared );

        if(is_wp_error( $updated )){
            return new WP_Error( 'Rest instructor not updated', __( 'Instructor Can not be updated.', 'extenddev-academy' ), [ 'status' => 400 ] );
        }

        $instructor = $this->get_instructor( $request['id'] );
        $response = $this->prepare_item_for_response( $instructor, $request );
        $response->set_status(200);

        return rest_ensure_response($response);

    }

    /**
     * check if a given request has access to edit items
     * 
     * @param \WP_REST_Request $request
     * @return boolean
     */

    public function get_edit_permissions_check( $request ){
        return $this->get_item_permissions_check( $request );
    }


    /**
     * check if a given request has access to read items
     *
     * @param \WP_REST_Request $request
     * @return boolean
     */
    public function get_items_permissions_check( $request ){
        
        if( !current_user_can( 'manage_options' ) ){
            return false;
        }
        
        return true;      

    }

    /**
     *  get items from the collection of items
     *
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response|\WP_Error
     */
    public function get_items( $request ){
        
        $args = [];
        $params = $this->get_collection_params();

        foreach( $params as $key => $value ){
            if( isset( $request[ $key ] ) ){
                $args[ $key ] = $request[ $key ];
            }
        }

        $args['offset'] = $args['per_page'] * ($args['page'] - 1);

        unset( $args['page'] );

        $data = [];
        $instructors = extenddevs_get_instructors( $args );

        // var_dump($instructors);

        foreach( $instructors as $instructor ){
            $response = $this->prepare_item_for_response( $instructor, $request );
            $data[]   = $this->prepare_response_for_collection( $response );
        }

        $total     = count( $instructors);
        $max_pages = ceil( $total / $args['per_page'] );
        $response = rest_ensure_response( $data );

        $response->header( 'X-WP-Total', (int) $total );
        $response->header( 'X-WP-TotalPages', (int) $max_pages );


        return  $response;

    }


    /**
     * prepare item for response
     *
     * @param  mixed $instructor wordpress representation of the item
     * @param  \WP_REST_Request $request
     * @return \WP_REST_Response | \WP_Error
     */
    public function prepare_item_for_response( $instructor, $request ){
        
        $data = [];
        $fields = $this->get_fields_for_response( $request );

        if( in_array('id', $fields, true)){
            $data['id'] = (int) $instructor['id'];
        }
        if( in_array('name', $fields, true)){
            $data['name'] = $instructor['name'];
        }
        if( in_array('email', $fields, true)){
            $data['email'] = $instructor['email'];
        }
        if( in_array('phone', $fields, true)){
            $data['phone'] = $instructor['phone'];
        }
        if( in_array('address', $fields, true)){
            $data['address'] = $instructor['address'];
        }
        if( in_array('date', $fields, true)){
            $data['date'] = mysql_to_rfc3339($instructor['created_at'] );
        }

        $context = ! empty( $request['context'] ) ? $request['context'] : 'view';
        $data = $this->filter_response_by_context( $data, $context );

        $response = rest_ensure_response( $data );
        $response->add_links( $this->prepare_links( $instructor ) );

        return $response;

    }

    /**
     * prepare links for the item response object 
     *
     * @param  \WP_REST_User $instructor
     * @return array
     */
    public function prepare_links( $instructor ){

        $links = array(
            'self' => array(
                'href' => rest_url( sprintf( '/%s/%s/%d', $this->namespace, $this->rest_base, $instructor['id'] ) ),
            ),
            'collection' => array(
                'href' => rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ),
            ),
        );

        return $links;

    }



    /**
     * get item schema
     *
     * @return array
     */
    public function get_item_schema(){

        if($this->schema ){
            return $this->add_additional_fields_schema( $this->schema );
        }

        $schema = [
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'title' => 'instructors',
            'type' => 'object',
            'properties' => [
                'id' => [
                    'description' => 'Unique identifier for the resource.',
                    'type' => 'integer',
                    'context' => [ 'view', 'edit' ],
                    'readonly' => true,
                ],
                'name' => [
                    'description' => 'Instructor name.',
                    'type' => 'string',
                    'context' => [ 'view', 'edit' ],
                    'required' => true,
                    'arg_options' => [
                        'sanitize_callback' => 'sanitize_text_field',
                    ],
                ],
                'email' => [
                    'description' => 'Instructor email.',
                    'type' => 'string',
                    'context' => [ 'view', 'edit' ],
                    'required' => true,
                    'arg_options' => [
                        'sanitize_callback' => 'sanitize_email',
                    ]
                ],
                'phone' => [
                    'description' => 'Instructor phone.',
                    'type' => 'string',
                    'context' => [ 'view', 'edit' ],
                    'required' => true,
                    'arg_options' => [
                        'sanitize_callback' => 'sanitize_text_field',
                    ]
                ],
                'date' => [
                    'description' => 'Instructor date.',
                    'type' => 'string',
                    'format' => 'date-time',
                    'context' => [ 'view' ],
                    'readonly' => true,
                ]
            ]    
        ];

        $this->schema = $schema;

        return $this->add_additional_fields_schema( $schema );

    }


    public function get_collection_params(){
        
        $params = parent::get_collection_params();
        unset( $params['search'] );

        return $params;

    }

}