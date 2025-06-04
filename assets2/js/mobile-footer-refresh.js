// Mobile Footer Refresh Script
(function($) {
    'use strict';
    
    // Function to reload the mobile footer
    function reloadMobileFooter() {
        // Get the current mobile footer
        var $mobileFooter = $('.mobile-footer');
        
        if ($mobileFooter.length) {
            // Get user role ID
            var roleId = 0;
            
            // First try to get role ID from the data attribute if available
            if (typeof loggedin_role_id !== 'undefined') {
                roleId = loggedin_role_id;
                console.log('Using role ID from global variable:', roleId);
            } 
            // Fallback to body classes
            else if ($('body').hasClass('student-logged-in')) {
                roleId = 7; // Student role ID
            } else if ($('body').hasClass('parent-logged-in')) {
                roleId = 6; // Parent role ID
            } else if ($('body').hasClass('teacher-logged-in')) {
                roleId = 3; // Teacher role ID
            } else if ($('body').hasClass('admin-logged-in')) {
                roleId = 2; // Admin role ID
            } else if ($('body').hasClass('superadmin-logged-in')) {
                roleId = 1; // Superadmin role ID
            }
            
            console.log('Detected role ID:', roleId);
            
            // Only proceed if we have a valid role ID
            if (roleId > 0) {
                // Make AJAX request to get updated menu items
                $.ajax({
                    url: base_url + 'settings_footer/get_menu_items',
                    type: 'POST',
                    data: {
                        role_id: roleId
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Clear existing menu
                        $('.mobile-footer-menu').empty();
                        
                        // Build new menu based on selected items
                        var menuItems = response.selected_items;
                        
                        // If no items selected, use default items
                        if (menuItems.length === 0) {
                            menuItems = ['dashboard'];
                            
                            if (roleId === 7 || roleId === 6) {
                                menuItems.push('homework');
                                menuItems.push('attendance');
                                menuItems.push('fees');
                            } else {
                                menuItems.push('students');
                                menuItems.push('payments');
                                menuItems.push('attendance');
                                menuItems.push('homework');
                                menuItems.push('fees');
                            }
                            
                            menuItems.push('message');
                        }
                        
                        // Build menu HTML
                        var menuHtml = '';
                        
                        // Loop through menu items
                        $.each(menuItems, function(index, item) {
                            switch (item) {
                                case 'dashboard':
                                    menuHtml += '<li><a href="' + base_url + 'dashboard"><i class="icons icon-grid"></i><span>' + translate.dashboard + '</span></a></li>';
                                    break;
                                case 'homework':
                                    // Show homework menu item for all roles if it's assigned
                                    var homeworkUrl = (roleId === 7 || roleId === 6) ? 'userrole/homework' : 'homework';
                                    menuHtml += '<li><a href="' + base_url + homeworkUrl + '"><i class="icons icon-note"></i><span>' + translate.homework + '</span></a></li>';
                                    break;
                                case 'attendance':
                                    if (roleId === 7 || roleId === 6) {
                                        menuHtml += '<li><a href="' + base_url + 'userrole/attendance"><i class="icons icon-chart"></i><span>' + translate.attendance + '</span></a></li>';
                                    } else {
                                        menuHtml += '<li><a href="' + base_url + 'attendance"><i class="icons icon-chart"></i><span>' + translate.attendance + '</span></a></li>';
                                    }
                                    break;
                                case 'fees':
                                    // Show fees menu item for all roles if it's assigned
                                    var feesUrl = (roleId === 7 || roleId === 6) ? 'userrole/invoice' : 'fees/invoice_list';
                                    menuHtml += '<li><a href="' + base_url + feesUrl + '"><i class="icons icon-calculator"></i><span>' + translate.fees + '</span></a></li>';
                                    break;
                                case 'students':
                                    if (roleId !== 7 && roleId !== 6) {
                                        menuHtml += '<li><a href="' + base_url + 'student/view"><i class="icon-graduation icons"></i><span>' + translate.students + '</span></a></li>';
                                    }
                                    break;
                                case 'payments':
                                    if (roleId !== 7 && roleId !== 6) {
                                        menuHtml += '<li><a href="' + base_url + 'fees/invoice_list"><i class="fab fa-wpforms"></i><span>' + translate.payments + '</span></a></li>';
                                    }
                                    break;
                                case 'message':
                                    menuHtml += '<li><a href="' + base_url + 'communication/mailbox/inbox"><i class="icons icon-envelope-open"></i><span>' + translate.message + '</span></a></li>';
                                    break;
                            }
                        });
                        
                        // Add menu HTML to footer
                        $('.mobile-footer-menu').html(menuHtml);
                        
                        // Set active menu item
                        setActiveMenuItem();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading menu items:', error);
                    }
                });
            }
        }
    }
    
    // Function to set active menu item
    function setActiveMenuItem() {
        var currentPath = window.location.pathname;
        $('.mobile-footer-menu li a').each(function() {
            var linkPath = $(this).attr('href');
            if (currentPath.indexOf(linkPath) !== -1) {
                $(this).addClass('active');
            }
        });
    }
    
    // Initialize on document ready
    $(document).ready(function() {
        // Add body classes based on user type
        if (typeof user_type !== 'undefined') {
            switch (user_type) {
                case 'student':
                    $('body').addClass('student-logged-in');
                    break;
                case 'parent':
                    $('body').addClass('parent-logged-in');
                    break;
                case 'teacher':
                    $('body').addClass('teacher-logged-in');
                    break;
                case 'admin':
                    $('body').addClass('admin-logged-in');
                    break;
                case 'superadmin':
                    $('body').addClass('superadmin-logged-in');
                    break;
            }
        }
        
        // Reload mobile footer on page load
        reloadMobileFooter();
        
        // Add refresh button for testing
        if ($('.mobile-footer').length && (user_type === 'admin' || user_type === 'superadmin')) {
            var $refreshButton = $('<button>', {
                'class': 'mobile-footer-refresh',
                'html': '<i class="fas fa-sync-alt"></i>',
                'title': 'Refresh Footer Menu'
            }).css({
                'position': 'fixed',
                'bottom': '80px',
                'right': '10px',
                'z-index': '1001',
                'background': '#672185',
                'color': '#fff',
                'border': 'none',
                'border-radius': '50%',
                'width': '40px',
                'height': '40px',
                'display': 'flex',
                'align-items': 'center',
                'justify-content': 'center',
                'box-shadow': '0 2px 5px rgba(0,0,0,0.2)'
            }).on('click', function() {
                reloadMobileFooter();
            });
            
            $('body').append($refreshButton);
        }
    });
    
})(jQuery);