<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Default values from the original hardcoded config
$default_aisensy_api_key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjY4MzFmN2RjZDk4M2MxMGNjNDFhYzg5OSIsIm5hbWUiOiJWaWduYW5hIEJoYXJhdGhpIFZpZHlhbGF5bSBTY2hvb2wiLCJhcHBOYW1lIjoiQWlTZW5zeSIsImNsaWVudElkIjoiNjgzMWY3ZGNkOTgzYzEwY2M0MWFjODk0IiwiYWN0aXZlUGxhbiI6IkZSRUVfRk9SRVZFUiIsImlhdCI6MTc0ODEwNTE4MH0.UKnf-fvq9qzDa9_RDP4FBI6JkWbpRUozkEHghUq7auU';
$default_aisensy_api_url = 'https://backend.aisensy.com/campaign/t1/api/v2';
$default_aisensy_user_name = 'Vignana Bharathi Vidhyalam School';

// Initialize with defaults
$config['aisensy_api_key'] = $default_aisensy_api_key;
$config['aisensy_api_url'] = $default_aisensy_api_url;
$config['aisensy_user_name'] = $default_aisensy_user_name;

// Get the CodeIgniter super object
// This will only work if this config file is loaded after the main controller is instantiated.
// Typically, config files are loaded via $this->config->load('config_file_name');
if (class_exists('CI_Controller', false)) {
    $CI =& get_instance();

    // Check if database class is loaded and connected.
    // Note: $CI->load->database() might have already been called by autoload or core.
    // We check $CI->db->conn_id to ensure connection is active.
    if (isset($CI->db) && $CI->db->conn_id !== FALSE) {
        // Database is loaded and connected

        // Fetch AiSensy API Key
        $query_api_key = $CI->db->get_where('settings', array('type' => 'aisensy_api_key'), 1);
        if ($query_api_key && $query_api_key->num_rows() > 0) {
            $row = $query_api_key->row();
            if (isset($row->value) && !empty(trim($row->value))) {
                $config['aisensy_api_key'] = $row->value;
            }
        }

        // Fetch AiSensy API URL
        $query_api_url = $CI->db->get_where('settings', array('type' => 'aisensy_api_url'), 1);
        if ($query_api_url && $query_api_url->num_rows() > 0) {
            $row = $query_api_url->row();
            if (isset($row->value) && !empty(trim($row->value))) {
                $config['aisensy_api_url'] = $row->value;
            }
        }

        // Fetch AiSensy User Name
        $query_user_name = $CI->db->get_where('settings', array('type' => 'aisensy_user_name'), 1);
        if ($query_user_name && $query_user_name->num_rows() > 0) {
            $row = $query_user_name->row();
            if (isset($row->value) && !empty(trim($row->value))) {
                $config['aisensy_user_name'] = $row->value;
            }
        }
    } else {
        // Log a message if DB is not available (and logging class is available)
        if (isset($CI->log)) {
             $CI->log->log_message('debug', 'AiSensy Config: Database not available or not loaded. Using default values.');
        }
    }
} else {
    // Log a message if CI instance is not available (and log_message function exists)
    if (function_exists('log_message')) {
        log_message('debug', 'AiSensy Config: CI_Controller instance not available. Using default values.');
    }
}

/*
Note for the developer:
To make these settings truly dynamic, ensure you have a database table (e.g., 'settings')
with columns like 'type' (or 'name', 'key') and 'value'.
Then, add rows for:
- type: 'aisensy_api_key', value: 'YOUR_ACTUAL_API_KEY'
- type: 'aisensy_api_url', value: 'YOUR_AISENSY_API_URL' (if different from default)
- type: 'aisensy_user_name', value: 'YOUR_AISENSY_REGISTERED_NAME' (if different from default)

If the 'settings' table or these specific rows do not exist, or if the values retrieved are empty,
the system will fall back to the default values defined at the top of this file.
This ensures that the application continues to function even if the database settings are not yet configured.
*/