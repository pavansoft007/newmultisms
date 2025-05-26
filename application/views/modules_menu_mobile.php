<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo translate('main_menu'); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/material-design-mobile.css'); ?>">
    <style>
        :root {
            --primary-color: #1976d2;
            --primary-light: #4791db;
            --primary-dark: #115293;
            --secondary-color: #f5f5f5;
            --text-primary: #212121;
            --text-secondary: #757575;
            --divider-color: #e0e0e0;
            --surface-color: #ffffff;
            --error-color: #d32f2f;
            --success-color: #388e3c;
            --warning-color: #f57c00;
            --info-color: #0288d1;
        }

        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-primary);
        }

        .header {
            background: var(--primary-color);
            color: white;
            padding: 16px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 500;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 14px;
            opacity: 0.8;
        }

        .container {
            padding: 16px;
            max-width: 1200px;
            margin: 0 auto;
            padding-bottom: 70px; /* Space for bottom nav */
        }

        .category-title {
            margin: 24px 0 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--divider-color);
            color: var(--primary-dark);
            font-size: 18px;
            font-weight: 500;
        }

        .module-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 16px;
            margin-top: 12px;
        }

        .module-card {
            background: var(--surface-color);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            color: var(--text-primary);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 120px;
            position: relative;
            overflow: hidden;
        }

        .module-card:hover, .module-card:focus {
            transform: translateY(-6px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .module-card:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            pointer-events: none;
        }

        .module-icon {
            font-size: 36px;
            margin-bottom: 12px;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .module-card:hover .module-icon {
            transform: scale(1.1);
        }

        .module-title {
            font-size: 14px;
            font-weight: 500;
            margin: 0;
            line-height: 1.3;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            display: flex;
            background: var(--surface-color);
            box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.1);
            z-index: 10;
            padding: 8px 0;
        }

        .bottom-nav a {
            flex: 1;
            padding: 8px 0;
            background: none;
            border: none;
            font-size: 12px;
            color: var(--text-secondary);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .bottom-nav a.active {
            color: var(--primary-color);
        }

        .bottom-nav a.active .module-icon {
            transform: translateY(-4px);
        }

        .bottom-nav a:hover {
            color: var(--primary-color);
        }

        /* Module-specific colors */
        .module-card.student { border-top: 3px solid #00796b; }
        .module-card.student .module-icon { color: #00796b; }
        
        .module-card.attendance { border-top: 3px solid #0288d1; }
        .module-card.attendance .module-icon { color: #0288d1; }
        
        .module-card.fees { border-top: 3px solid #f57c00; }
        .module-card.fees .module-icon { color: #f57c00; }
        
        .module-card.classes { border-top: 3px solid #7b1fa2; }
        .module-card.classes .module-icon { color: #7b1fa2; }
        
        .module-card.exam { border-top: 3px solid #c2185b; }
        .module-card.exam .module-icon { color: #c2185b; }
        
        .module-card.event { border-top: 3px solid #388e3c; }
        .module-card.event .module-icon { color: #388e3c; }
        
        .module-card.reports { border-top: 3px solid #455a64; }
        .module-card.reports .module-icon { color: #455a64; }
        
        .module-card.library { border-top: 3px solid #795548; }
        .module-card.library .module-icon { color: #795548; }
        
        .module-card.accounting { border-top: 3px solid #607d8b; }
        .module-card.accounting .module-icon { color: #607d8b; }
        
        .module-card.settings { border-top: 3px solid #616161; }
        .module-card.settings .module-icon { color: #616161; }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo translate('main_menu'); ?></h1>
        <p><?php echo translate('welcome') . ', ' . $user_name; ?></p>
    </div>
    
    <div class="container">
        <?php
        // Function to check if user has permission to access a module
        function has_module_permission($permissions, $module_name) {
          if (empty($permissions)) {
            return false;
          }
          
          foreach ($permissions as $permission) {
            if ($permission->permission_prefix == $module_name && $permission->is_view == '1') {
              return true;
            }
          }
          return false;
        }
        
        // Define module categories and their modules
        $module_categories = array(
          'Academic' => array('student', 'classes', 'subject', 'section', 'syllabus'),
          'Student Activities' => array('attendance', 'exam', 'mark', 'homework', 'promotion'),
          'Finance' => array('fees', 'expense', 'income', 'accounting'),
          'Resources' => array('library', 'inventory', 'hostel', 'transport'),
          'Communication' => array('event', 'communication', 'sendsmsmail'),
          'Administration' => array('settings', 'reports', 'dashboard', 'leave', 'award'),
        );
        
        // Define module icons and descriptions
        $module_details = array(
          'student' => array('icon' => 'fas fa-user-graduate', 'desc' => 'Manage student profiles, admissions, and records'),
          'classes' => array('icon' => 'fas fa-chalkboard', 'desc' => 'Manage classes, sections, and assignments'),
          'subject' => array('icon' => 'fas fa-book', 'desc' => 'Manage subjects and curriculum'),
          'section' => array('icon' => 'fas fa-puzzle-piece', 'desc' => 'Organize classes into sections'),
          'syllabus' => array('icon' => 'fas fa-list-alt', 'desc' => 'Manage course syllabi and content'),
          'attendance' => array('icon' => 'fas fa-check-double', 'desc' => 'Track student and staff attendance'),
          'exam' => array('icon' => 'fas fa-diagnoses', 'desc' => 'Manage exams, schedules, and halls'),
          'mark' => array('icon' => 'fas fa-poll', 'desc' => 'Record and manage student marks'),
          'homework' => array('icon' => 'fas fa-tasks', 'desc' => 'Assign and track homework'),
          'promotion' => array('icon' => 'fas fa-arrow-circle-up', 'desc' => 'Manage student promotions'),
          'fees' => array('icon' => 'fas fa-money-bill-wave', 'desc' => 'Manage student fees and payments'),
          'expense' => array('icon' => 'fas fa-minus-circle', 'desc' => 'Track and manage expenses'),
          'income' => array('icon' => 'fas fa-plus-circle', 'desc' => 'Record and manage income'),
          'accounting' => array('icon' => 'fas fa-calculator', 'desc' => 'Financial accounting and reports'),
          'library' => array('icon' => 'fas fa-book-reader', 'desc' => 'Manage library books and resources'),
          'inventory' => array('icon' => 'fas fa-boxes', 'desc' => 'Track school inventory and assets'),
          'hostel' => array('icon' => 'fas fa-hotel', 'desc' => 'Manage student hostels and rooms'),
          'transport' => array('icon' => 'fas fa-bus', 'desc' => 'Manage school transportation'),
          'event' => array('icon' => 'fas fa-calendar-alt', 'desc' => 'Manage school events and activities'),
          'communication' => array('icon' => 'fas fa-comments', 'desc' => 'School-wide communication tools'),
          'sendsmsmail' => array('icon' => 'fas fa-envelope', 'desc' => 'Send SMS and email notifications'),
          'settings' => array('icon' => 'fas fa-cogs', 'desc' => 'System settings and configuration'),
          'reports' => array('icon' => 'fas fa-chart-bar', 'desc' => 'Generate and view reports'),
          'dashboard' => array('icon' => 'fas fa-tachometer-alt', 'desc' => 'School performance dashboard'),
          'leave' => array('icon' => 'fas fa-sign-out-alt', 'desc' => 'Manage staff and student leaves'),
          'award' => array('icon' => 'fas fa-trophy', 'desc' => 'Manage awards and recognitions')
        );
        
        // Display modules by category
        foreach ($module_categories as $category => $category_modules) {
          $has_modules = false;
          
          // Check if user has access to any module in this category
          foreach ($category_modules as $module) {
            if (has_module_permission($permissions, $module)) {
              $has_modules = true;
              break;
            }
          }
          
          // Only display category if user has access to at least one module
          if ($has_modules) {
            echo '<h3 class="category-title">' . translate($category) . '</h3>';
            echo '<div class="module-grid">';
            
            foreach ($category_modules as $module) {
              if (has_module_permission($permissions, $module)) {
                $icon = isset($module_details[$module]['icon']) ? $module_details[$module]['icon'] : 'fas fa-cube';
                
                echo '<a href="' . base_url($module) . '" class="module-card ' . $module . '">';
                echo '<i class="' . $icon . ' module-icon"></i>';
                echo '<div class="module-title">' . translate($module) . '</div>';
                echo '</a>';
              }
            }
            
            echo '</div>';
          }
        }
        ?>
    </div>
    
    <div class="bottom-nav">
        <a href="<?php echo base_url('dashboard'); ?>" class="<?php echo ($main_menu == 'dashboard' ? 'active' : ''); ?>">
            <i class="fas fa-tachometer-alt module-icon"></i>
            <span><?php echo translate('dashboard'); ?></span>
        </a>
        <a href="<?php echo base_url('mainmenu'); ?>" class="active">
            <i class="fas fa-th-large module-icon"></i>
            <span><?php echo translate('menu'); ?></span>
        </a>
        <a href="<?php echo base_url('student'); ?>" class="<?php echo ($main_menu == 'student' ? 'active' : ''); ?>">
            <i class="fas fa-user-graduate module-icon"></i>
            <span><?php echo translate('student'); ?></span>
        </a>
        <a href="<?php echo base_url('settings/profile'); ?>" class="<?php echo ($main_menu == 'settings' ? 'active' : ''); ?>">
            <i class="fas fa-user-cog module-icon"></i>
            <span><?php echo translate('profile'); ?></span>
        </a>
    </div>
</body>
</html><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo translate('main_menu'); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/material-design-mobile.css'); ?>">
    <style>
        :root {
            --primary-color: #1976d2;
            --primary-light: #4791db;
            --primary-dark: #115293;
            --secondary-color: #f5f5f5;
            --text-primary: #212121;
            --text-secondary: #757575;
            --divider-color: #e0e0e0;
            --surface-color: #ffffff;
            --error-color: #d32f2f;
            --success-color: #388e3c;
            --warning-color: #f57c00;
            --info-color: #0288d1;
        }

        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-primary);
        }

        .header {
            background: var(--primary-color);
            color: white;
            padding: 16px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 500;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 14px;
            opacity: 0.8;
        }

        .container {
            padding: 16px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .category-title {
            margin: 24px 0 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--divider-color);
            color: var(--primary-dark);
            font-size: 18px;
            font-weight: 500;
        }

        .module-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 16px;
            margin-top: 12px;
        }

        .module-card {
            background: var(--surface-color);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            color: var(--text-primary);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 120px;
            position: relative;
            overflow: hidden;
        }

        .module-card:hover, .module-card:focus {
            transform: translateY(-6px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .module-card:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            pointer-events: none;
        }

        .module-icon {
            font-size: 36px;
            margin-bottom: 12px;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .module-card:hover .module-icon {
            transform: scale(1.1);
        }

        .module-title {
            font-size: 14px;
            font-weight: 500;
            margin: 0;
            line-height: 1.3;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            display: flex;
            background: var(--surface-color);
            box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.1);
            z-index: 10;
            padding: 8px 0;
        }

        .bottom-nav a {
            flex: 1;
            padding: 8px 0;
            background: none;
            border: none;
            font-size: 12px;
            color: var(--text-secondary);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .bottom-nav a.active {
            color: var(--primary-color);
        }

        .bottom-nav a.active .module-icon {
            transform: translateY(-4px);
        }

        .bottom-nav a:hover {
            color: var(--primary-color);
        }

        /* Module-specific colors */
        .module-card.student { border-top: 3px solid #00796b; }
        .module-card.student .module-icon { color: #00796b; }
        
        .module-card.attendance { border-top: 3px solid #0288d1; }
        .module-card.attendance .module-icon { color: #0288d1; }
        
        .module-card.fees { border-top: 3px solid #f57c00; }
        .module-card.fees .module-icon { color: #f57c00; }
        
        .module-card.classes { border-top: 3px solid #7b1fa2; }
        .module-card.classes .module-icon { color: #7b1fa2; }
        
        .module-card.exam { border-top: 3px solid #c2185b; }
        .module-card.exam .module-icon { color: #c2185b; }
        
        .module-card.event { border-top: 3px solid #388e3c; }
        .module-card.event .module-icon { color: #388e3c; }
        
        .module-card.reports { border-top: 3px solid #455a64; }
        .module-card.reports .module-icon { color: #455a64; }
        
        .module-card.library { border-top: 3px solid #795548; }
        .module-card.library .module-icon { color: #795548; }
        
        .module-card.accounting { border-top: 3px solid #607d8b; }
        .module-card.accounting .module-icon { color: #607d8b; }
        
        .module-card.settings { border-top: 3px solid #616161; }
        .module-card.settings .module-icon { color: #616161; }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo translate('main_menu'); ?></h1>
        <p><?php echo translate('welcome') . ', ' . $user_name; ?></p>
    </div>
    
    <div class="container">
        <?php
        // Function to check if user has permission to access a module
        function has_module_permission($permissions, $module_name) {
          if (empty($permissions)) {
            return false;
          }
          
          foreach ($permissions as $permission) {
            if ($permission->permission_prefix == $module_name && $permission->is_view == '1') {
              return true;
            }
          }
          return false;
        }
        
        // Define module categories and their modules
        $module_categories = array(
          'Academic' => array('student', 'classes', 'subject', 'section', 'syllabus'),
          'Student Activities' => array('attendance', 'exam', 'mark', 'homework', 'promotion'),
          'Finance' => array('fees', 'expense', 'income', 'accounting'),
          'Resources' => array('library', 'inventory', 'hostel', 'transport'),
          'Communication' => array('event', 'communication', 'sendsmsmail'),
          'Administration' => array('settings', 'reports', 'dashboard', 'leave', 'award'),
        );
        
        // Define module icons and descriptions
        $module_details = array(
          'student' => array('icon' => 'fas fa-user-graduate', 'desc' => 'Manage student profiles, admissions, and records'),
          'classes' => array('icon' => 'fas fa-chalkboard', 'desc' => 'Manage classes, sections, and assignments'),
          'subject' => array('icon' => 'fas fa-book', 'desc' => 'Manage subjects and curriculum'),
          'section' => array('icon' => 'fas fa-puzzle-piece', 'desc' => 'Organize classes into sections'),
          'syllabus' => array('icon' => 'fas fa-list-alt', 'desc' => 'Manage course syllabi and content'),
          'attendance' => array('icon' => 'fas fa-check-double', 'desc' => 'Track student and staff attendance'),
          'exam' => array('icon' => 'fas fa-diagnoses', 'desc' => 'Manage exams, schedules, and halls'),
          'mark' => array('icon' => 'fas fa-poll', 'desc' => 'Record and manage student marks'),
          'homework' => array('icon' => 'fas fa-tasks', 'desc' => 'Assign and track homework'),
          'promotion' => array('icon' => 'fas fa-arrow-circle-up', 'desc' => 'Manage student promotions'),
          'fees' => array('icon' => 'fas fa-money-bill-wave', 'desc' => 'Manage student fees and payments'),
          'expense' => array('icon' => 'fas fa-minus-circle', 'desc' => 'Track and manage expenses'),
          'income' => array('icon' => 'fas fa-plus-circle', 'desc' => 'Record and manage income'),
          'accounting' => array('icon' => 'fas fa-calculator', 'desc' => 'Financial accounting and reports'),
          'library' => array('icon' => 'fas fa-book-reader', 'desc' => 'Manage library books and resources'),
          'inventory' => array('icon' => 'fas fa-boxes', 'desc' => 'Track school inventory and assets'),
          'hostel' => array('icon' => 'fas fa-hotel', 'desc' => 'Manage student hostels and rooms'),
          'transport' => array('icon' => 'fas fa-bus', 'desc' => 'Manage school transportation'),
          'event' => array('icon' => 'fas fa-calendar-alt', 'desc' => 'Manage school events and activities'),
          'communication' => array('icon' => 'fas fa-comments', 'desc' => 'School-wide communication tools'),
          'sendsmsmail' => array('icon' => 'fas fa-envelope', 'desc' => 'Send SMS and email notifications'),
          'settings' => array('icon' => 'fas fa-cogs', 'desc' => 'System settings and configuration'),
          'reports' => array('icon' => 'fas fa-chart-bar', 'desc' => 'Generate and view reports'),
          'dashboard' => array('icon' => 'fas fa-tachometer-alt', 'desc' => 'School performance dashboard'),
          'leave' => array('icon' => 'fas fa-sign-out-alt', 'desc' => 'Manage staff and student leaves'),
          'award' => array('icon' => 'fas fa-trophy', 'desc' => 'Manage awards and recognitions')
        );
        
        // Display modules by category
        foreach ($module_categories as $category => $category_modules) {
          $has_modules = false;
          
          // Check if user has access to any module in this category
          foreach ($category_modules as $module) {
            if (has_module_permission($permissions, $module)) {
              $has_modules = true;
              break;
            }
          }
          
          // Only display category if user has access to at least one module
          if ($has_modules) {
            echo '<h3 class="category-title">' . translate($category) . '</h3>';
            echo '<div class="module-grid">';
            
            foreach ($category_modules as $module) {
              if (has_module_permission($permissions, $module)) {
                $icon = isset($module_details[$module]['icon']) ? $module_details[$module]['icon'] : 'fas fa-cube';
                
                echo '<a href="' . base_url($module) . '" class="module-card ' . $module . '">';
                echo '<i class="' . $icon . ' module-icon"></i>';
                echo '<div class="module-title">' . translate($module) . '</div>';
                echo '</a>';
              }
            }
            
            echo '</div>';
          }
        }
        ?>
    </div>
    
    <div class="bottom-nav">
        <a href="<?php echo base_url('dashboard'); ?>" class="<?php echo ($main_menu == 'dashboard' ? 'active' : ''); ?>">
            <i class="fas fa-tachometer-alt module-icon"></i>
            <span><?php echo translate('dashboard'); ?></span>
        </a>
        <a href="<?php echo base_url('mainmenu'); ?>" class="active">
            <i class="fas fa-th-large module-icon"></i>
            <span><?php echo translate('menu'); ?></span>
        </a>
        <a href="<?php echo base_url('student'); ?>" class="<?php echo ($main_menu == 'student' ? 'active' : ''); ?>">
            <i class="fas fa-user-graduate module-icon"></i>
            <span><?php echo translate('student'); ?></span>
        </a>
        <a href="<?php echo base_url('settings/profile'); ?>" class="<?php echo ($main_menu == 'settings' ? 'active' : ''); ?>">
            <i class="fas fa-user-cog module-icon"></i>
            <span><?php echo translate('profile'); ?></span>
        </a>
    </div>
</body>
</html>