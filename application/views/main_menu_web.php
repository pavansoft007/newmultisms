<div class="row">
  <div class="col-md-12">
    <div class="panel">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-th-large"></i> <?php echo translate('main_menu'); ?></h4>
      </div>
      <div class="panel-body">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/material-design-3.css'); ?>">
        <style>
          /* Hide mobile footer in web view */
          .mobile-footer {
            display: none !important;
          }
        </style>
        
        <?php
        // Display all menu items in a single grid
        echo '<div class="md3-module-grid">';
        
        // Loop through all menu items
        foreach ($menu_items as $item) {
            $name = $item['name'];
            $url = $item['url'];
            $icon = $item['icon'];
            $desc = $item['desc'];
            
            // Convert icon class if needed (for compatibility with both icon sets)
            if (strpos($icon, 'icons icon-') !== false) {
                $icon_class = str_replace('icons icon-', 'fas fa-', $icon);
                // Map some specific icons
                $icon_map = [
                    'fas fa-grid' => 'fas fa-th-large',
                    'fas fa-directions' => 'fas fa-map-signs',
                    'fas fa-user-follow' => 'fas fa-user-plus',
                    'fas fa-note' => 'fas fa-sticky-note'
                ];
                
                if (isset($icon_map[$icon_class])) {
                    $icon_class = $icon_map[$icon_class];
                }
            } else {
                $icon_class = $icon;
            }
            
            // Create the card
            echo '<a href="' . base_url($url) . '" class="md3-module-card ' . $name . '">';
            echo '<i class="' . $icon_class . ' md3-module-icon"></i>';
            echo '<div class="md3-module-title">' . translate($name) . '</div>';
            if (!empty($desc)) {
                echo '<div class="md3-module-description">' . $desc . '</div>';
            }
            echo '</a>';
        }
        
        echo '</div>';
        ?>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url('assets/js/main-menu-responsive.js'); ?>"></script><div class="row">
  <div class="col-md-12">
    <div class="panel">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-th-large"></i> <?php echo translate('main_menu'); ?></h4>
      </div>
      <div class="panel-body">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/material-design-3.css'); ?>">
        
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
          'Academic' => array('student', 'classes', 'subject', 'sections', 'syllabus'),
          'Student Activities' => array('attendance', 'exam', 'mark', 'homework', 'promotion'),
          'Finance' => array('fees', 'accounting/voucher_expense', 'income', 'accounting'),
          'Resources' => array('library', 'inventory', 'hostel', 'transport'),
          'Communication' => array('event', 'communication', 'sendsmsmail'),
          'Administration' => array('settings', 'reports', 'dashboard', 'leave', 'award'),
        );
        
        // Define module icons and descriptions
        $module_details = array(
          'student' => array('icon' => 'fas fa-user-graduate', 'desc' => 'Manage student profiles, admissions, and records'),
          'classes' => array('icon' => 'fas fa-chalkboard', 'desc' => 'Manage classes, sections, and assignments'),
          'subject' => array('icon' => 'fas fa-book', 'desc' => 'Manage subjects and curriculum'),
          'sections' => array('icon' => 'fas fa-puzzle-piece', 'desc' => 'Organize classes into sections'),
          'syllabus' => array('icon' => 'fas fa-list-alt', 'desc' => 'Manage course syllabi and content'),
          'attendance' => array('icon' => 'fas fa-check-double', 'desc' => 'Track student and staff attendance'),
          'exam' => array('icon' => 'fas fa-diagnoses', 'desc' => 'Manage exams, schedules, and halls'),
          'mark' => array('icon' => 'fas fa-poll', 'desc' => 'Record and manage student marks'),
          'homework' => array('icon' => 'fas fa-tasks', 'desc' => 'Assign and track homework'),
          'promotion' => array('icon' => 'fas fa-arrow-circle-up', 'desc' => 'Manage student promotions'),
          'fees' => array('icon' => 'fas fa-money-bill-wave', 'desc' => 'Manage student fees and payments'),
          'accounting/voucher_expense' => array('icon' => 'fas fa-minus-circle', 'desc' => 'Track and manage expenses'),
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
            echo '<h3 class="md3-category-title">' . translate($category) . '</h3>';
            echo '<div class="md3-module-grid">';
            
            foreach ($category_modules as $module) {
              if (has_module_permission($permissions, $module)) {
                $icon = isset($module_details[$module]['icon']) ? $module_details[$module]['icon'] : 'fas fa-cube';
                $desc = isset($module_details[$module]['desc']) ? $module_details[$module]['desc'] : '';
                
                echo '<a href="' . base_url($module) . '" class="md3-module-card ' . $module . '">';
                echo '<i class="' . $icon . ' md3-module-icon"></i>';
                echo '<div class="md3-module-title">' . translate($module) . '</div>';
                if (!empty($desc)) {
                  echo '<div class="md3-module-description">' . $desc . '</div>';
                }
                echo '</a>';
              }
            }
            
            echo '</div>';
          }
        }
        ?>
      </div>
    </div>
  </div>
</div>