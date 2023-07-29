<?php

namespace ExtendDevs\Academy\Traits;

/**
 * Error Handler Traits
 * @since 1.0.0
 * @return void
 */

trait Form_Error {
    
    /**
     * Array of errors
     *
     * @var array
     */
    public $errors = [];


    /**
     * Check if there is an error
     *
     * @param [type] $key
     * @return boolean
     */
    public function has_error($key){

        return isset($this->errors[$key]) ? true : false;
        
    }


    /**
     * Get error
     *
     * @return void
     */
    public function get_error($key){

        if(!empty($this->errors[$key])){

            return $this->errors[$key];
        }

        return false;

    }
    

}