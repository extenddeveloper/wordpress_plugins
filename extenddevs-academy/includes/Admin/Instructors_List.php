<?php

namespace ExtendDevs\Academy\Admin;

if(! class_exists("WP_List_Table")) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Instructors_List extends \WP_List_Table {
    
    public $errors = array();
    
    public function __construct() {
        parent::__construct(array(
            'singular' => 'instructor',
            'plural' => 'instructors',
            'ajax' => false
        ));
    }

    public function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name', 'extenddev-academy'),
            'email' => __('Email', 'extenddev-academy'),
            'address' => __('Address', 'extenddev-academy'),
            'phone' => __('Phone', 'extenddev-academy'),
            'created_at' => __('Date', 'extenddev-academy')
        );
    }

    public function column_cb($item) {
        return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item['id']);
    }

    public function get_sortable_columns() {
        return array(
            'name' => array('name', true),
            'email' => array('email', true),
            'address' => array('address', true),
            'phone' => array('phone', true),
            'created_at' => array('created_at', true),
        );
    }
    public function prepare_items() {

        $orderby = isset($_GET['orderby']) ? trim($_GET['orderby']) : '';
        $order = isset($_GET['order']) ? trim($_GET['order']) : '';
        $search_term = isset($_POST['s']) ? trim($_POST['s']) : '';
        $args = [];

        if(!$orderby == '' && !$order == ''){
            $args = [
                'orderby' => $orderby,
                'order' => $order,
                // 'search_term' => $search_term
            ];
        }
        
        $columns = $this->get_columns();
        $hidden = array();
        $data = extenddevs_get_instructors($args);
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        // echo "<pre>";
        // print_r($this->items);
        // echo "</pre>";

        $per_page = 5;
        $current_page = $this->get_pagenum();
        $total = count($data);
        $this->set_pagination_args(array(
            'total_items' => $total,
            'per_page' => $per_page,
        ));

        $this->items = array_slice($data,(($current_page - 1) * $per_page), $per_page);

    }
    
    protected function column_default($item, $column_name) {
        
        switch($column_name) {
            case 'name':
                return $item['name'];
                break;
            case 'email':
                return $item['email'];
                break;
            case 'address':
                return $item['address'];
                break;
            case 'phone':
                return $item['phone'];
                break;
            case 'created_at':
                return $item['created_at'];
                break;
            default:
                return $item[$column_name];
                break;

        }

    }
    
    public function column_name($item) {

        $actions = [];

        $actions['edit'] = sprintf('<a href="%1$s">%2$s</a>', admin_url('admin.php?page=extenddev-academy&action=edit&id=' . $item['id']), __('Edit', 'extenddev-academy'));
        $actions['view'] = sprintf('<a href="%1$s">%2$s</a>', admin_url('admin.php?page=extenddev-academy&action=view&id=' . $item['id']), __('View', 'extenddev-academy'));
        $actions['delete'] = sprintf('<a href="%1$s" class="submitdeletebtn" onclick="return confirm(\'Are You Sure?\');">%2$s</a>', wp_nonce_url( admin_url('admin-post.php?action=extenddevs_academy_delete_instructor&id=' . $item['id']), 'wd_ac_delete_instructor') , __('Delete', 'extenddev-academy'));
        $actions['ajax-delete'] = sprintf('<a href="#" class="submitdelete" data-id="%s">%s</a>', $item['id'], __('Ajax Delete', 'extenddev-academy'));

        // $actions['delete'] = sprintf('<a href="%1$s" class="submitdelete" onclick="return confirm(\'Are You Sure?\');">%2$s</a>', admin_url('admin.php?page=extenddev-academy&action=delete&id=' . $item['id']), __('Delete', 'extenddev-academy'));


        return sprintf('<a href="%1$s"><strong>%2$s<strong></a>%3$s', admin_url('admin.php?page=extenddev-academy&action=edit&id=' . $item['id']), $item['name'], $this->row_actions($actions));
    }
    
    // public function column_email($item) {
    //     return $item['email'];
    // }
    
    // public function column_address($item) {
    //     return $item['address'];
    // }
    
    // public function column_phone($item) {
    //     return $item['phone'];
    // }

    // public function column_actions($item) {
    //     return '<a href="' . admin_url('admin.php?page=extenddev-academy&action=edit&id=' . $item['id']) . '">Edit</a>';
    // }

    




}