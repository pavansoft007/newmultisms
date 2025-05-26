<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package     School Management System
 * @subpackage  Controller
 * @category    Main Menu
 * @author      Iniquus
 */

class Mainmenu_simple extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Enable error reporting for debugging
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    public function index()
    {
        // Simple data array
        $data = array(
            'title' => 'Main Menu',
            'subtitle' => 'Welcome to the School Management System'
        );
        
        // Load a simple view
        $this->load->view('mainmenu_standalone', $data);
    }
}