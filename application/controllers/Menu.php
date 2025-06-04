<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : School Management System
 * @version : 1.0
 * @author : Iniquus
 */

class Menu extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_model');
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
        $this->data['modules'] = $this->role_model->getModulesList();
        
        // Get user permissions
        $permissions = get_staff_permissions($role_id);
        $this->data['permissions'] = $permissions;
        
        // Get user info
        $this->data['user_name'] = $this->session->userdata('name');
        $this->data['user_role'] = loggedin_role_name();
        
        // Load the view
        $this->data['sub_page'] = 'mainmenu_web_simple';
        $this->data['main_menu'] = 'dashboard';
        $this->load->view('layout/index', $this->data);
    }
}<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : School Management System
 * @version : 1.0
 * @author : Iniquus
 */

class Menu extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_model');
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
        $this->data['modules'] = $this->role_model->getModulesList();
        
        // Get user permissions
        $permissions = get_staff_permissions($role_id);
        $this->data['permissions'] = $permissions;
        
        // Get user info
        $this->data['user_name'] = $this->session->userdata('name');
        $this->data['user_role'] = loggedin_role_name();
        
        // Load the view
        $this->data['sub_page'] = 'mainmenu_web_simple';
        $this->data['main_menu'] = 'dashboard';
        $this->load->view('layout/index', $this->data);
    }
}