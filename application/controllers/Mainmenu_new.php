<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Bigwala Technologies school management system
 * @version : 1.0
 * @developed by : Bigwala Technologies
 * @support : bigwalatechnologies@bigwallatechnologies.com
 * @author url : https://bigwallatechnologies.com
 * @filename : Mainmenu.php
 * @copyright : Reserved Bigwala Technologiess Team
 */

class Mainmenu_new extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_model');
        
        // Enable error reporting for debugging
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    public function index()
    {
        // Check if the user is logged in
        if (!is_loggedin()) {
            redirect(base_url('authentication'));
        }

        // Get user role ID
        $role_id = $this->session->userdata('loggedin_role_id');
        
        // Get all modules
        try {
            $this->data['modules'] = $this->role_model->getModulesList();
        } catch (Exception $e) {
            $this->data['modules'] = array();
            log_message('error', 'Error getting modules list: ' . $e->getMessage());
        }
        
        // Get user permissions
        try {
            $permissions = get_staff_permissions($role_id);
            $this->data['permissions'] = $permissions;
        } catch (Exception $e) {
            $this->data['permissions'] = array();
            log_message('error', 'Error getting permissions: ' . $e->getMessage());
        }
        
        // Get user info
        $this->data['user_name'] = $this->session->userdata('name');
        $this->data['user_role'] = loggedin_role_name();
        
        // Check if the request is coming from a mobile device
        $is_mobile = $this->is_mobile_device();
        $this->data['is_mobile'] = $is_mobile;
        
        // For all views, use the layout with the simplified view
        $this->data['sub_page'] = 'mainmenu_web_simple';
        $this->data['main_menu'] = 'dashboard';
        $this->load->view('layout/index', $this->data);
    }
    
    // Function to detect if the request is coming from a mobile device
    private function is_mobile_device() {
        // First check the cookie set by JavaScript (most reliable)
        if ($this->input->cookie('is_mobile') === 'true') {
            return true;
        }
        
        // Check screen width from cookie
        $screen_width = $this->input->cookie('screen_width');
        if ($screen_width !== false && intval($screen_width) <= 767) {
            return true;
        }
        
        // Then check user agent
        $user_agent = $this->input->server('HTTP_USER_AGENT');
        $mobile_agents = array(
            'Android', 'webOS', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 'Windows Phone',
            'Mobile', 'Opera Mini', 'Opera Mobi', 'IEMobile', 'Silk', 'Kindle'
        );
        
        foreach ($mobile_agents as $agent) {
            if (stripos($user_agent, $agent) !== false) {
                return true;
            }
        }
        
        return false;
    }
}