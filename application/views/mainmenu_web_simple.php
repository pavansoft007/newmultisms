<?php
// --- CHILD SELECTION LOGIC FOR PARENT ROLE (from controller) ---
if (isset($children) && is_array($children) && count($children) > 0) {
    echo '<div style="margin-bottom:20px;">';
    echo '<div class="btn-group" role="group">';
    foreach ($children as $child) {
        $active = (isset($selected_child_id) && $selected_child_id == $child['id']) ? 'btn-primary' : 'btn-default';
        $url = base_url('parents/select_child/' . $child['id']);
        echo '<a href="' . $url . '" class="btn ' . $active . '" style="margin-right:5px;">';
        echo '<img src="' . base_url('uploads/app_image/' . $child['photo']) . '" style="width:32px;height:32px;border-radius:50%;margin-right:6px;vertical-align:middle;">';
        echo htmlspecialchars($child['fullname']) . ' <span style="font-size:12px;color:#888;">(' . $child['class_name'] . ' - ' . $child['section_name'] . ')</span>';
        echo '</a>';
    }
    echo '</div>';
    echo '</div>';
    // If no child selected, default to first
    if (!isset($selected_child_id) || !$selected_child_id) {
        redirect(base_url('parents/select_child/' . $children[0]['id']));
    }
}
?>

<div class="row">
  <div class="col-md-12">
    <div class="panel">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-th-large"></i> <?php echo translate('main_menu'); ?></h4>
      </div>
      <div class="panel-body">
        <style>
          .module-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 15px;
          }
          
          .module-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            color: #333;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 180px;
            position: relative;
            overflow: hidden;
          }
          
          .module-card:hover {
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
            font-size: 48px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
          }
          
          .module-card:hover .module-icon {
            transform: scale(1.1);
          }
          
          .module-title {
            font-size: 16px;
            font-weight: 500;
            margin: 0 0 8px;
            line-height: 1.3;
          }
          
          .module-description {
            font-size: 13px;
            color: #666;
            margin: 0;
            line-height: 1.4;
          }
          
          .category-title {
            margin: 30px 0 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            color: #333;
            font-size: 18px;
            font-weight: 500;
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
                $desc = isset($module_details[$module]['desc']) ? $module_details[$module]['desc'] : '';
                // --- Parent child context logic ---
                if (isset($user_role) && strtolower($user_role) == 'parent') {
                  if (isset($selected_child_id) && $selected_child_id) {
                    // Append child_id as GET param
                    $url = base_url($module) . '?child_id=' . $selected_child_id;
                    $disabled = '';
                  } else {
                    // No child selected, disable link
                    $url = 'javascript:void(0);';
                    $disabled = 'style="pointer-events:none;opacity:0.5;" title="Please select a child"';
                  }
                } else {
                  $url = base_url($module);
                  $disabled = '';
                }
                echo '<a href="' . $url . '" class="module-card ' . $module . '" ' . $disabled . '>';
                echo '<i class="' . $icon . ' module-icon"></i>';
                echo '<div class="module-title">' . translate($module) . '</div>';
                if (!empty($desc)) {
                  echo '<div class="module-description">' . $desc . '</div>';
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