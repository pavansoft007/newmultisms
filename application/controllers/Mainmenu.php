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

class Mainmenu extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        // DO NOT overwrite parent data property!
        // $this->data = []; // REMOVE THIS LINE
        $this->load->library('session'); // Load session library
        $this->load->model('role_model'); // Load role_model
        $this->load->database();
        
        // Load all necessary helpers
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('common');
        $this->load->helper('menu');
        
        // Enable error reporting for debugging
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    public function index()
    {
        // Check if the user is logged in
        if (!$this->session->userdata('loggedin')) {
            redirect(base_url('authentication'));
        }
        $data = [];
        // Get user role ID
        $role_id = $this->session->userdata('loggedin_role_id');
        // Get all modules
        try {
            $data['modules'] = $this->role_model->getModulesList();
        } catch (Exception $e) {
            $data['modules'] = array();
            log_message('error', 'Error getting modules list: ' . $e->getMessage());
        }
        // Get user permissions
        try {
            $permissions = get_staff_permissions($role_id);
            $data['permissions'] = $permissions;
        } catch (Exception $e) {
            $data['permissions'] = array();
            log_message('error', 'Error getting permissions: ' . $e->getMessage());
        }
        // Get all menu items from sidebar
        try {
            $data['menu_items'] = get_main_menu_items($role_id);
        } catch (Exception $e) {
            $data['menu_items'] = array();
            log_message('error', 'Error getting menu items: ' . $e->getMessage());
        }
        // Get user info
        $data['user_name'] = $this->session->userdata('name');
        $data['user_role'] = loggedin_role_name();
        // Get theme and global config from MY_Controller
        $data['theme_config'] = $this->data['theme_config'];
        $data['global_config'] = $this->data['global_config'];
        $data['title'] = translate('main_menu');
        $data['main_menu'] = 'dashboard';
        $data['sub_page'] = 'main_menu_web';
        // --- Parent child selection logic ---
        if ($role_id == 6 && is_parent_loggedin()) {
            $parent_id = get_loggedin_user_id();
            $this->load->model('parents_model');
            $children = $this->parents_model->childsResult($parent_id);
            $selected_child_id = $this->session->userdata('myChildren_id');
            $data['children'] = $children;
            $data['selected_child_id'] = $selected_child_id;
        }
        // Check if the request is coming from a mobile device
        $is_mobile = false;
        if ($this->input->cookie('is_mobile') === 'true') {
            $is_mobile = true;
        } else {
            $screen_width = $this->input->cookie('screen_width');
            if ($screen_width !== false && intval($screen_width) <= 767) {
                $is_mobile = true;
            } else {
                $user_agent = $this->input->server('HTTP_USER_AGENT');
                $mobile_agents = array(
                    'Android', 'webOS', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 'Windows Phone',
                    'Mobile', 'Opera Mini', 'Opera Mobi', 'IEMobile', 'Silk', 'Kindle'
                );
                foreach ($mobile_agents as $agent) {
                    if (stripos($user_agent, $agent) !== false) {
                        $is_mobile = true;
                        break;
                    }
                }
            }
        }
        $data['is_mobile'] = $is_mobile;
        // Pass alert messages to view to avoid using session in view
        $alertclass = "";
        $alert_message = "";
        if ($this->session->flashdata('alert-message-success')) {
            $alertclass = "success";
            $alert_message = $this->session->flashdata('alert-message-success');
        } else if ($this->session->flashdata('alert-message-error')) {
            $alertclass = "error";
            $alert_message = $this->session->flashdata('alert-message-error');
        } else if ($this->session->flashdata('alert-message-info')) {
            $alertclass = "info";
            $alert_message = $this->session->flashdata('alert-message-info');
        }
        $data['alertclass'] = $alertclass;
        $data['alert_message'] = $alert_message;
        // Load the appropriate view based on device type
        if ($is_mobile) {
            $this->load->view('main_menu', $data);
        } else {
            $this->load->view('layout/index', $data);
        }
    }
}