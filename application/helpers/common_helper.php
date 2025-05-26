<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Common Helper Functions
 *
 * This helper contains common functions used throughout the application
 */

// If this function doesn't exist yet
if (!function_exists('is_admin')) {
    /**
     * Check if the current user is an admin
     *
     * @return bool
     */
    function is_admin() {
        $CI =& get_instance();
        if ($CI->session->userdata('loggedin')) {
            $role_id = $CI->session->userdata('loggedin_role_id');
            return $role_id == 1; // Assuming 1 is the admin role ID
        }
        return false;
    }
}

// If this function doesn't exist yet
if (!function_exists('is_loggedin')) {
    /**
     * Check if user is logged in
     *
     * @return bool
     */
    function is_loggedin() {
        $CI =& get_instance();
        return $CI->session->userdata('loggedin');
    }
}

// If this function doesn't exist yet
if (!function_exists('is_student_loggedin')) {
    /**
     * Check if a student is logged in
     *
     * @return bool
     */
    function is_student_loggedin() {
        $CI =& get_instance();
        if ($CI->session->userdata('loggedin')) {
            $role_id = $CI->session->userdata('loggedin_role_id');
            return $role_id == 7; // Assuming 7 is the student role ID
        }
        return false;
    }
}

// If this function doesn't exist yet
if (!function_exists('is_parent_loggedin')) {
    /**
     * Check if a parent is logged in
     *
     * @return bool
     */
    function is_parent_loggedin() {
        $CI =& get_instance();
        if ($CI->session->userdata('loggedin')) {
            $role_id = $CI->session->userdata('loggedin_role_id');
            return $role_id == 6; // Assuming 6 is the parent role ID
        }
        return false;
    }
}

// If this function doesn't exist yet
if (!function_exists('get_loggedin_user_id')) {
    /**
     * Get logged in user ID
     *
     * @return int
     */
    function get_loggedin_user_id() {
        $CI =& get_instance();
        return $CI->session->userdata('loggedin_id');
    }
}

// If this function doesn't exist yet
if (!function_exists('loggedin_role_name')) {
    /**
     * Get logged in user role name
     *
     * @return string
     */
    function loggedin_role_name() {
        $CI =& get_instance();
        $roleID = $CI->session->userdata('loggedin_role_id');
        $CI->db->select('name');
        $CI->db->where('id', $roleID);
        $query = $CI->db->get('roles');
        if ($query->num_rows() > 0) {
            return $query->row()->name;
        }
        return '';
    }
}

// If this function doesn't exist yet
if (!function_exists('translate')) {
    /**
     * Translate a string
     *
     * @param string $word
     * @return string
     */
    function translate($word) {
        if (empty($word)) {
            return '';
        }
        
        $CI =& get_instance();
        try {
            if ($CI->session->has_userdata('set_lang')) {
                $set_lang = $CI->session->userdata('set_lang');
            } else {
                $set_lang = get_global_setting('translation');
            }
            
            if ($set_lang == '') {
                $set_lang = 'english';
            }
            
            // Try to load the language file
            try {
                $CI->lang->load('custom_language', $set_lang);
            } catch (Exception $e) {
                // If loading fails, just continue with default behavior
                log_message('error', 'Error loading language file: ' . $e->getMessage());
            }
            
            // Try to get the translation
            $translated = $CI->lang->line($word);
            if ($translated) {
                return $translated;
            }
        } catch (Exception $e) {
            // Log the error but continue with default behavior
            log_message('error', 'Error in translate function: ' . $e->getMessage());
        }
        
        // If no translation found or an error occurred, return the word with first letter capitalized
        return ucfirst(str_replace('_', ' ', $word));
    }
}

// If this function doesn't exist yet
if (!function_exists('get_global_setting')) {
    /**
     * Get global setting value
     *
     * @param string $name
     * @return string
     */
    function get_global_setting($name) {
        $CI =& get_instance();
        try {
            // Check if the global_settings table exists
            $tables = $CI->db->list_tables();
            if (in_array('global_settings', $tables)) {
                $CI->db->select($name);
                $CI->db->from('global_settings');
                $CI->db->where('id', 1);
                $query = $CI->db->get();
                if ($query && $query->num_rows() > 0) {
                    return $query->row()->$name;
                }
            }
        } catch (Exception $e) {
            // Log the error
            log_message('error', 'Error getting global setting: ' . $e->getMessage());
        }
        
        // Default values for common settings
        $defaults = array(
            'translation' => 'english',
            'timezone' => 'UTC',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i:s',
            'currency' => 'USD',
            'currency_symbol' => '$',
            'system_name' => 'School Management System',
            'system_title' => 'SMS',
            'address' => '123 School Street',
            'phone' => '123-456-7890',
            'email' => 'info@school.com',
            'image_extension' => 'jpg,jpeg,png,gif',
            'image_size' => '2048',
            'file_extension' => 'pdf,doc,docx,xls,xlsx,ppt,pptx,txt',
            'file_size' => '4096'
        );
        
        return isset($defaults[$name]) ? $defaults[$name] : '';
    }
}