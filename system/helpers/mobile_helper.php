<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Check if the current device is a mobile device
 * 
 * @return bool True if the device is mobile, false otherwise
 */
if (!function_exists('is_mobile_device')) {
    function is_mobile_device() {
        $CI =& get_instance();
        
        // First check the cookie set by JavaScript (most reliable)
        if ($CI->input->cookie('is_mobile') === 'true') {
            return true;
        }
        
        // Check screen width from cookie
        $screen_width = $CI->input->cookie('screen_width');
        if ($screen_width !== false && intval($screen_width) <= 767) {
            return true;
        }
        
        // Then check user agent
        $user_agent = $CI->input->server('HTTP_USER_AGENT');
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