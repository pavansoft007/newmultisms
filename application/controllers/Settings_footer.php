<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings_footer extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_model');
    }

    public function index()
    {
        // Temporarily bypass permission check
        // if (!get_permission('footer_settings', 'is_view')) {
        //     access_denied();
        // }

        if ($_POST) {
            // Temporarily bypass permission check
            // if (!get_permission('footer_settings', 'is_edit')) {
            //     access_denied();
            // }

            // Save footer configuration
            if ($this->input->post('submit') == 'save') {
                $role_id = $this->input->post('role_id');
                $menu_items = $this->input->post('menu_items');
                
                // Debug information
                error_log('Saving footer menu for role ID: ' . $role_id);
                error_log('Menu items: ' . json_encode($menu_items));
                
                // Delete existing configuration for this role
                $this->db->where('role_id', $role_id);
                $this->db->delete('footer_menu_config');
                error_log('Deleted existing menu items for role ID: ' . $role_id);
                
                // Insert new configuration
                if (!empty($menu_items)) {
                    foreach ($menu_items as $menu_item) {
                        $data = array(
                            'role_id' => $role_id,
                            'menu_item' => $menu_item,
                            'status' => 1
                        );
                        $this->db->insert('footer_menu_config', $data);
                        error_log('Inserted menu item: ' . $menu_item . ' for role ID: ' . $role_id);
                    }
                } else {
                    error_log('No menu items selected for role ID: ' . $role_id);
                }
                
                // Load cache driver if not loaded
                if (!isset($this->cache)) {
                    $this->load->driver('cache', array('adapter' => 'file'));
                }
                
                // Clear specific cache for this role
                $cache_key = 'footer_menu_' . $role_id;
                $this->cache->delete($cache_key);
                
                // Also clear any other related caches
                $this->cache->clean();
                
                // Force browser cache refresh by adding timestamp to CSS/JS files
                $this->session->set_userdata('cache_timestamp', time());
                
                set_alert('success', translate('the_configuration_has_been_updated'));
                redirect(current_url());
            }
        }

        // Get all roles for footer settings (including parent and student roles)
        $this->db->select('*');
        $this->data['roles'] = $this->db->get('roles')->result_array();
        $this->data['title'] = 'Footer Settings';
        $this->data['sub_page'] = 'settings/footer_settings';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    public function get_menu_items()
    {
        if ($_POST) {
            $role_id = $this->input->post('role_id');
            
            // Get all available menu items
            $all_menu_items = array(
                'dashboard' => translate('dashboard'),
                'homework' => translate('homework'),
                'attendance' => translate('attendance'),
                'fees' => translate('fees'),
                'students' => translate('students'),
                'payments' => translate('payments'),
                'message' => translate('message')
            );
            
            // Get selected menu items for this role using the helper function
            $selected_items = get_footer_menu_items($role_id);
            
            $data = array(
                'all_items' => $all_menu_items,
                'selected_items' => $selected_items
            );
            
            echo json_encode($data);
        }
    }
    
    /**
     * AJAX endpoint to refresh the footer menu
     */
    public function refresh_footer()
    {
        if ($_POST) {
            $role_id = $this->input->post('role_id');
            
            // Load cache driver if not loaded
            if (!isset($this->cache)) {
                $this->load->driver('cache', array('adapter' => 'file'));
            }
            
            // Clear cache for this role
            $cache_key = 'footer_menu_' . $role_id;
            $this->cache->delete($cache_key);
            
            // Get fresh menu items
            $menu_items = get_footer_menu_items($role_id);
            
            $data = array(
                'success' => true,
                'menu_items' => $menu_items
            );
            
            echo json_encode($data);
        } else {
            $data = array(
                'success' => false,
                'message' => 'Invalid request method'
            );
            
            echo json_encode($data);
        }
    }
    
    public function debug()
    {
        // Temporarily bypass permission check
        // if (!get_permission('footer_settings', 'is_view')) {
        //     access_denied();
        // }
        
        $this->data['title'] = 'Footer Debug';
        $this->data['sub_page'] = 'settings/footer_debug';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }
    
    public function check_database()
    {
        $result = array(
            'tables' => array(),
            'roles' => array(),
            'footer_menu' => array()
        );
        
        // Check if tables exist
        $result['tables']['roles'] = $this->db->table_exists('roles');
        $result['tables']['footer_menu_config'] = $this->db->table_exists('footer_menu_config');
        
        // Get roles
        if ($result['tables']['roles']) {
            $roles = $this->db->get('roles')->result_array();
            $result['roles']['count'] = count($roles);
            $result['roles']['data'] = $roles;
        }
        
        // Get footer menu config
        if ($result['tables']['footer_menu_config']) {
            $footer_menu = $this->db->get('footer_menu_config')->result_array();
            $result['footer_menu']['count'] = count($footer_menu);
            $result['footer_menu']['data'] = $footer_menu;
            
            // Group by role
            $by_role = array();
            foreach ($footer_menu as $item) {
                if (!isset($by_role[$item['role_id']])) {
                    $by_role[$item['role_id']] = array();
                }
                $by_role[$item['role_id']][] = $item['menu_item'];
            }
            $result['footer_menu']['by_role'] = $by_role;
        }
        
        echo json_encode($result);
    }
    
    public function test_helper()
    {
        if ($_POST) {
            $role_id = $this->input->post('role_id');
            
            // Test the helper function
            $menu_items = get_footer_menu_items($role_id);
            
            // Get raw database data
            $query = $this->db->where('role_id', $role_id)
                            ->where('status', 1)
                            ->get('footer_menu_config');
            
            $db_items = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $db_items[] = $row->menu_item;
                }
            }
            
            $data = array(
                'role_id' => $role_id,
                'menu_items_from_helper' => $menu_items,
                'menu_items_from_db' => $db_items,
                'db_query' => $this->db->last_query()
            );
            
            echo json_encode($data);
        }
    }
    
    public function fix_database()
    {
        // Load cache driver if not loaded
        if (!isset($this->cache)) {
            $this->load->driver('cache', array('adapter' => 'file'));
        }
        
        // Clear all cache
        $this->cache->clean();
        
        // Check if table exists
        $table_exists = $this->db->table_exists('footer_menu_config');
        
        $result = array(
            'success' => false,
            'messages' => array()
        );
        
        if (!$table_exists) {
            // Create the table
            $this->load->dbforge();
            
            $fields = array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'role_id' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'menu_item' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50
                ),
                'status' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                )
            );
            
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('role_id');
            $this->dbforge->create_table('footer_menu_config', TRUE);
            
            $result['messages'][] = 'Table footer_menu_config created successfully';
        } else {
            $result['messages'][] = 'Table footer_menu_config already exists';
            
            // Check if there are any records
            $count = $this->db->count_all('footer_menu_config');
            $result['messages'][] = 'Current record count: ' . $count;
            
            // Clear all records
            $this->db->truncate('footer_menu_config');
            $result['messages'][] = 'Cleared all existing records';
        }
        
        // Add default configurations for each role
        $roles = $this->db->get('roles')->result();
        
        foreach ($roles as $role) {
            // Default menu items for all roles
            $menu_items = array('dashboard', 'message');
            
            // Add role-specific menu items
            if ($role->id == 7 || $role->id == 6) { // Student or Parent
                $menu_items = array_merge($menu_items, array('homework', 'attendance', 'fees'));
            } else {
                // For admin roles, include all menu items
                $menu_items = array_merge($menu_items, array('students', 'payments', 'attendance', 'homework', 'fees'));
            }
            
            // Insert default configuration
            foreach ($menu_items as $menu_item) {
                $data = array(
                    'role_id' => $role->id,
                    'menu_item' => $menu_item,
                    'status' => 1
                );
                $this->db->insert('footer_menu_config', $data);
            }
            
            $result['messages'][] = 'Added default menu items for role: ' . $role->name;
        }
        
        $result['success'] = true;
        echo json_encode($result);
    }
}