<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper function to get footer menu items for a specific role
 * 
 * @param int $role_id The role ID
 * @return array Array of menu items
 */
if (!function_exists('get_footer_menu_items')) {
    function get_footer_menu_items($role_id) {
        $CI =& get_instance();
        
        // Debug the role ID
        error_log('Getting footer menu items for role ID: ' . $role_id);
        
        // Check if table exists to prevent errors
        if (!$CI->db->table_exists('footer_menu_config')) {
            error_log('Table footer_menu_config does not exist');
            return get_default_menu_items($role_id);
        }
        
        // Check if cache library is loaded
        if (!isset($CI->cache)) {
            $CI->load->driver('cache', array('adapter' => 'file'));
        }
        
        // Try to get from cache first
        $cache_key = 'footer_menu_' . $role_id;
        $menu_items = $CI->cache->get($cache_key);
        
        if ($menu_items === FALSE) {
            // Not in cache, get from database
            // Get footer menu items for this role - use direct query for debugging
            $query = "SELECT * FROM footer_menu_config WHERE role_id = ? AND status = 1";
            $result = $CI->db->query($query, array($role_id));
            $footer_items = $result->result_array();
            
            // Debug the query
            error_log('SQL Query: ' . $CI->db->last_query());
            error_log('Number of items found: ' . count($footer_items));
            
            $menu_items = array();
            if (!empty($footer_items)) {
                foreach ($footer_items as $item) {
                    $menu_items[] = $item['menu_item'];
                }
                
                // Log for debugging
                error_log('Footer menu items for role ' . $role_id . ': ' . json_encode($menu_items));
                
                // Save to cache for 1 hour
                $CI->cache->save($cache_key, $menu_items, 3600);
            } else {
                error_log('No footer menu items found for role ' . $role_id . ' - using defaults');
                $menu_items = get_default_menu_items($role_id);
                
                // Save default items to cache for 1 hour
                $CI->cache->save($cache_key, $menu_items, 3600);
            }
            
            // Important: If menu items array is empty, don't return it
            // This ensures we never return an empty array
            if (empty($menu_items)) {
                error_log('Menu items array is empty, using defaults');
                $menu_items = get_default_menu_items($role_id);
                $CI->cache->save($cache_key, $menu_items, 3600);
            }
        } else {
            error_log('Got footer menu items from cache for role ' . $role_id);
        }
        
        return $menu_items;
    }
    
    /**
     * Helper function to get default menu items for a specific role
     * 
     * @param int $role_id The role ID
     * @return array Array of default menu items
     */
    function get_default_menu_items($role_id) {
        // Default menu items
        $menu_items = array('dashboard');
        
        if ($role_id == 7 || ($role_id == 6 && function_exists('get_activeChildren_id') && !empty(get_activeChildren_id()))) {
            $menu_items[] = 'homework';
            $menu_items[] = 'attendance';
            $menu_items[] = 'fees';
        } else {
            $menu_items[] = 'students';
            $menu_items[] = 'payments';
            $menu_items[] = 'attendance';
        }
        
        $menu_items[] = 'message';
        
        return $menu_items;
    }
}