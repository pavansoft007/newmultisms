<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#6750A4">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/material-design-3.css'); ?>">
    <style>
        /* Mobile-specific overrides */
        @media (max-width: 767px) {
            body {
                -webkit-tap-highlight-color: transparent;
                overscroll-behavior: none;
            }
            
            .md3-app-bar {
                padding: 12px 16px;
            }
            
            .md3-container {
                padding: 12px;
            }
            
            .md3-module-card {
                border-radius: 16px;
            }
            
            .md3-bottom-nav {
                padding: 6px 0;
            }
            
            .md3-module-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr); /* 2 columns for mobile */
                gap: 8px;
            }
            
        }
    </style>
    <style>
        .mainmenu-header-btn {
            display: inline-flex;
            align-items: center;
            margin-left: 12px;
            padding: 7px 16px 7px 12px;
            background: var(--md-primary, #6750A4);
            color: #fff !important;
            border-radius: 8px;
            font-size: 17px;
            font-weight: 500;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(103,80,164,0.08);
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        .mainmenu-header-btn i {
            margin-right: 8px;
            font-size: 20px;
        }
        .mainmenu-header-btn:hover, .mainmenu-header-btn:focus {
            background: #54318c;
            color: #fff !important;
            text-decoration: none;
        }
        .mainmenu-header-label {
            display: inline;
        }
        @media (max-width: 991px) {
            .mainmenu-header-label {
                display: none;
            }
            .mainmenu-header-btn {
                padding: 7px 10px;
                font-size: 18px;
            }
        }
        @media (max-width: 767px) {
            .mainmenu-header-btn {
                display: none !important;
            }
            
        }
        /* Child Selection Tabs */
.child-selection-container {
    margin-bottom: 24px;
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.child-tabs-header {
    background: #f8f9fa;
    padding: 16px 20px 8px;
    border-bottom: 1px solid #e9ecef;
}

.child-tabs-title {
    font-size: 16px;
    font-weight: 500;
    color: #333;
    margin-bottom: 8px;
}

.child-tabs-scroll {
    overflow-x: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.child-tabs-scroll::-webkit-scrollbar {
    display: none;
}

.child-tabs {
    display: flex;
    gap: 8px;
    padding: 8px 0;
    min-width: max-content;
}

.child-tab {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    text-decoration: none;
    color: #666;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    white-space: nowrap;
    min-width: 140px;
    justify-content: flex-start;
}

.child-tab:hover {
    border-color: #6750A4;
    color: #6750A4;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(103, 80, 164, 0.15);
}

.child-tab.active {
    background: linear-gradient(135deg, #6750A4, #7c63c7);
    border-color: #6750A4;
    color: white;
    box-shadow: 0 4px 16px rgba(103, 80, 164, 0.3);
}

.child-tab.active:hover {
    background: linear-gradient(135deg, #5a47a1, #6750A4);
    color: white;
}

.child-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 10px;
    border: 2px solid rgba(255,255,255,0.3);
    flex-shrink: 0;
    object-fit: cover;
}

.child-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.child-name {
    font-weight: 500;
    line-height: 1.2;
    margin-bottom: 2px;
}

.child-class {
    font-size: 11px;
    opacity: 0.8;
    line-height: 1;
}

/* Touch Effects */
.touch-effect {
    transition: transform 0.1s ease;
}

.touch-effect:active {
    transform: scale(0.95);
}

/* Mobile Responsive for Child Tabs */
@media (max-width: 767px) {
    .child-tabs-header {
        padding: 12px 16px 6px;
    }
    
    .child-tabs {
        padding: 6px 0 12px;
    }
    
    .child-tab {
        min-width: 120px;
        padding: 10px 12px;
        font-size: 13px;
    }
    
    .child-avatar {
        width: 28px;
        height: 28px;
        margin-right: 8px;
    }
    
    .child-class {
        font-size: 10px;
    }
}

@media (max-width: 480px) {
    .child-tab {
        min-width: 100px;
        padding: 8px 10px;
        font-size: 12px;
    }
    
    .child-avatar {
        width: 24px;
        height: 24px;
        margin-right: 6px;
    }
}

    </style>
</head>
<body>
    <div class="md3-app-bar">
        <div>
            <h1 class="md3-app-bar-title"><?php echo translate('main_menu'); ?></h1>
            <p class="md3-app-bar-subtitle"><?php echo translate('welcome') . ', ' . $user_name; ?> (<?php echo $user_role; ?>)</p>
        </div>
        <a href="<?php echo base_url('mainmenu'); ?>" class="mainmenu-header-btn hidden-xs" title="Main Menu">
            <i class="fas fa-th-large"></i>
            <span class="mainmenu-header-label">Main Menu</span>
        </a>
    </div>
    
    <div class="md3-container">
     <?php
// --- CHILD SELECTION LOGIC FOR PARENT ROLE (updated with tabs) ---
if (isset($children) && is_array($children) && count($children) > 0) {
    echo '<div class="child-selection-container">';
    echo '<div class="child-tabs-header">';
    echo '<div class="child-tabs-title">' . translate('select_child') . '</div>';
    echo '<div class="child-tabs-scroll">';
    echo '<div class="child-tabs">';
    
    foreach ($children as $child) {
        $active = (isset($selected_child_id) && $selected_child_id == $child['id']) ? 'active' : '';
        $url = base_url('parents/select_child/' . $child['id']);
        
        echo '<a href="' . $url . '" class="child-tab ' . $active . ' touch-effect">';
        echo '<img src="' . base_url('uploads/app_image/' . $child['photo']) . '" alt="' . htmlspecialchars($child['fullname']) . '" class="child-avatar">';
        echo '<div class="child-info">';
        echo '<span class="child-name">' . htmlspecialchars($child['fullname']) . '</span>';
        echo '<span class="child-class">' . htmlspecialchars($child['class_name'] . ' - ' . $child['section_name']) . '</span>';
        echo '</div>';
        echo '</a>';
    }
    
    echo '</div>'; // child-tabs
    echo '</div>'; // child-tabs-scroll
    echo '</div>'; // child-tabs-header
    echo '</div>'; // child-selection-container
    
    // If no child selected, default to first
    if (!isset($selected_child_id) || !$selected_child_id) {
        redirect(base_url('parents/select_child/' . $children[0]['id']));
    }
}
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
    
    <?php if ($is_mobile): ?>
    <div class="md3-bottom-nav">
        <a href="<?php echo base_url('dashboard'); ?>" class="md3-bottom-nav-item">
            <i class="fas fa-tachometer-alt md3-bottom-nav-icon"></i>
            <span class="md3-bottom-nav-label"><?php echo translate('dashboard'); ?></span>
        </a>
        <a href="<?php echo base_url('mainmenu'); ?>" class="md3-bottom-nav-item active">
            <i class="fas fa-th-large md3-bottom-nav-icon"></i>
            <span class="md3-bottom-nav-label"><?php echo translate('menu'); ?></span>
        </a>
        <?php if (is_student_loggedin() || (is_parent_loggedin() && !empty(get_activeChildren_id()))): ?>
        <a href="<?php echo base_url('userrole/attendance'); ?>" class="md3-bottom-nav-item">
            <i class="fas fa-check-double md3-bottom-nav-icon"></i>
            <span class="md3-bottom-nav-label"><?php echo translate('attendance'); ?></span>
        </a>
        <a href="<?php echo base_url('userrole/invoice'); ?>" class="md3-bottom-nav-item">
            <i class="fas fa-money-bill-wave md3-bottom-nav-icon"></i>
            <span class="md3-bottom-nav-label"><?php echo translate('fees'); ?></span>
        </a>
        <?php else: ?>
        <a href="<?php echo base_url('student'); ?>" class="md3-bottom-nav-item">
            <i class="fas fa-user-graduate md3-bottom-nav-icon"></i>
            <span class="md3-bottom-nav-label"><?php echo translate('student'); ?></span>
        </a>
        <a href="<?php echo base_url('profile'); ?>" class="md3-bottom-nav-item">
            <i class="fas fa-user-cog md3-bottom-nav-icon"></i>
            <span class="md3-bottom-nav-label"><?php echo translate('profile'); ?></span>
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <script src="<?php echo base_url('assets/js/mobile-detector.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/main-menu-responsive.js'); ?>"></script>
    <script>
        // Add touch effect for mobile devices
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.md3-module-card');
            const navItems = document.querySelectorAll('.md3-bottom-nav-item');
            
            // Function to add touch effect
            function addTouchEffect(elements) {
                elements.forEach(element => {
                    element.addEventListener('touchstart', function() {
                        this.style.transform = 'scale(0.95)';
                        this.style.opacity = '0.9';
                    }, { passive: true });
                    
                    element.addEventListener('touchend', function() {
                        this.style.transform = '';
                        this.style.opacity = '';
                    }, { passive: true });
                    
                    element.addEventListener('touchcancel', function() {
                        this.style.transform = '';
                        this.style.opacity = '';
                    }, { passive: true });
                });
            }
            
            // Add effects
            addTouchEffect(cards);
            addTouchEffect(navItems);
        });
    </script>
</body>
</html>