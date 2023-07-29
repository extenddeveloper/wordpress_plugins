<?php

namespace ExtendDevs\Academy\Admin;

use \ExtendDevs\Academy\Traits\Form_Error;

class Instructors {

    use Form_Error;

    public function instuctors_page(){

        $action = isset($_GET['action']) ? $_GET['action'] : 'list';
        $id     = isset($_GET['id']) ? $_GET['id'] : 0;

        switch($action){
            case 'view':
                $instructor = extenddevs_get_instructor($id);
                $template   = __DIR__ . '/views/instructor/view.php';
                break;
            case 'edit':
                $instructor = extenddevs_get_instructor($id);
                $template   = __DIR__ . '/views/instructor/edit.php';
                break;
            case 'new':
                $template   = __DIR__ . '/views/instructor/new.php';
                break;
            default:
                $template   = __DIR__ . '/views/instructor/list.php';
                break;
        }

        if(file_exists($template)){

            include $template;

        }

    }

    public function form_handler(){

        if( !isset($_POST['submit_instructor'])){
            return;
        }

        if(!wp_verify_nonce($_POST['_wpnonce'], 'new-instructor')){
            
            wp_die(__('Nonce verification failed', 'extenddev-academy'));

        }

        if(!current_user_can('manage_options')){

            wp_die(__('You do not have sufficient permissions to access this page', 'extenddev-academy'));

        }

        $id      = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $name    = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email   = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $phone   = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $address = isset($_POST['address']) ? sanitize_textarea_field($_POST['address']) : '';

        if( empty($name)){
            $this->errors['name'] = __('Name is required', 'extenddev-academy');
        }

        if( empty($email)){
            $this->errors['email'] = __('Email is required', 'extenddev-academy');
        }

        if( empty($phone)){
            $this->errors['phone'] = __('Phone is required', 'extenddev-academy');
        }

        if( empty($address)){
            $this->errors['address'] = __('Address is required', 'extenddev-academy');
        }

        if(!empty($this->errors)){
            return;
        }

        $args = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'created_by' => get_current_user_id(),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if($id){
            $args['id'] = $id;
        }

        $insert_id = extenddevs_insert_instructor($args);

        if( is_wp_error($insert_id) ){
            wp_die( $insert_id->get_error_message() );
        }

        if($id){

            wp_redirect(admin_url('admin.php?page=extenddev-academy&action=edit&updated=true&id='.$id));

        }else{

            $redirected_to = admin_url('admin.php?page=extenddev-academy&inserted=true');

        }

        wp_redirect($redirected_to);

        // var_dump(extenddevs_insert_instructor());
        // die();

        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";

    }

    public function delete_handler(){

        if(!wp_verify_nonce($_REQUEST['_wpnonce'], 'wd_ac_delete_instructor')){
            
            wp_die(__('Nonce verification failed', 'extenddev-academy'));

        }

        if(!current_user_can('manage_options')){

            wp_die(__('You do not have sufficient permissions to access this page', 'extenddev-academy'));

        }

        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

        // if(!$id){

        //     wp_die(__('Invalid instructor id', 'extenddev-academy'));
            
        // }

        if(extenddevs_delete_instructor($id)){
            $redirected_to = admin_url('admin.php?page=extenddev-academy&instructor-deleted=true');
        }else{
            $redirected_to = admin_url('admin.php?page=extenddev-academy&instructor-deleted=false');
        }

        wp_redirect($redirected_to);
        

    }




}