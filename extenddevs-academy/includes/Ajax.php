<?php

namespace ExtendDevs\Academy;

class Ajax {

     function __construct() {
        add_action('wp_ajax_wd_enquiry_form_submit',  array($this,'extenddevs_enquiry_form_function') );
        add_action('wp_ajax_nopriv_wd_enquiry_form_submit',  array($this,'extenddevs_enquiry_form_function') );
        add_action('wp_ajax_wd-academy-delete-instructor', array($this,'delete_instructor'));
     }

     public function extenddevs_enquiry_form_function() {
            
        if(!wp_verify_nonce($_REQUEST['_wpnonce'], 'wd_enquiry_nonce')){
            wp_send_json_error([
                'message' => __('Nonce verification failed', 'extenddev-academy'),
            ] );
            exit;
        }

        
        wp_send_json_success([
                'message' => __('Form submitted successfully', 'extenddev-academy'),
            ]);

        exit;

    }

    public function delete_instructor() {
        if(!wp_verify_nonce($_REQUEST['_wpnonce'], 'wd_ac_admin_nonce')){
            wp_send_json_error([
                'message' => __('Nonce verification failed', 'extenddev-academy'),
            ] );
            exit;
        }

        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

        if(!$id){
            wp_send_json_error([
                'message' => __('Invalid instructor id', 'extenddev-academy'),
            ] );
            exit;
        }

        if(!current_user_can('manage_options')){
            wp_send_json_error([
                'message' => __('You do not have sufficient permissions to access this page', 'extenddev-academy'),
            ] );
            exit;
        }

        if(!extenddevs_delete_instructor($id)){
            wp_send_json_error([
                'message' => __('Unable to delete instructor', 'extenddev-academy'),
            ] );
            exit;
        }

        wp_send_json_success([
            'message' => __('Instructor deleted successfully', 'extenddev-academy'),
        ]);

        exit;
    }

}