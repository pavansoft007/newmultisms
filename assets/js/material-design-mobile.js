/**
 * Material Design 3 Mobile Interactions
 * This file contains JavaScript functions for Material Design 3 mobile interactions
 */

(function($) {
    'use strict';
    
    // Function to check if device is mobile
    function isMobile() {
        return window.innerWidth <= 767;
    }
    
    // Apply Material Design classes to elements
    function applyMaterialDesign() {
        if (!isMobile()) return;
        
        // Add ripple effect to buttons
        $('.btn').addClass('md-ripple');
        
        // Add Material Design classes to cards
        $('.card').addClass('md-card');
        
        // Add Material Design classes to form elements
        $('input, select, textarea').addClass('md-input');
        
        // Add Material Design classes to tables
        $('.table').addClass('md-table');
        $('.table-responsive').each(function() {
            if (!$(this).parent().hasClass('table-container')) {
                $(this).wrap('<div class="table-container"></div>');
            }
        });
        
        // Convert tables to mobile-friendly format on small screens
        $('.table:not(.dataTable)').each(function() {
            var $table = $(this);
            if (!$table.hasClass('table-mobile') && $table.is(':visible')) {
                $table.addClass('table-mobile');
                
                // Add data-label attributes to cells based on header text
                var headerTexts = [];
                $table.find('thead th').each(function(index) {
                    headerTexts[index] = $(this).text().trim();
                });
                
                $table.find('tbody tr').each(function() {
                    $(this).find('td').each(function(index) {
                        if (headerTexts[index]) {
                            $(this).attr('data-label', headerTexts[index]);
                        }
                    });
                });
            }
        });
        
        // Add Material Design classes to alerts
        $('.alert').each(function() {
            var $alert = $(this);
            if (!$alert.find('.alert-icon').length) {
                var icon = '';
                if ($alert.hasClass('alert-success')) {
                    icon = '<i class="alert-icon fas fa-check-circle"></i>';
                } else if ($alert.hasClass('alert-danger')) {
                    icon = '<i class="alert-icon fas fa-exclamation-circle"></i>';
                } else if ($alert.hasClass('alert-warning')) {
                    icon = '<i class="alert-icon fas fa-exclamation-triangle"></i>';
                } else if ($alert.hasClass('alert-info')) {
                    icon = '<i class="alert-icon fas fa-info-circle"></i>';
                }
                
                var content = $alert.html();
                $alert.html(icon + '<div class="alert-content">' + content + '</div>');
            }
            $alert.addClass('md-alert');
        });
        
        // Add Material Design classes to list groups
        $('.list-group').addClass('md-list');
        $('.list-group-item').addClass('md-list-item');
        
        // Add Material Design classes to tabs
        $('.nav-tabs').addClass('md-tabs');
        $('.nav-tabs .nav-link').addClass('md-tab');
        
        // Add Material Design classes to mobile footer
        $('.mobile-footer').addClass('md-bottom-navigation');
        $('.mobile-footer-menu li a').addClass('md-ripple');
        
        // Enhance dashboard if we're on the dashboard page
        if (window.location.pathname.indexOf('/dashboard') !== -1) {
            enhanceDashboard();
        }
    }
    
    // Create a floating action button (FAB) if needed
    function createFAB() {
        if (!isMobile()) return;
        
        // Check if we're on a page that needs a FAB
        var currentPath = window.location.pathname;
        
        // Pages that might need a FAB for adding new items
        var addPages = [
            '/student/view',
            '/employee/view',
            '/fees/invoice_list',
            '/homework',
            '/communication/mailbox'
        ];
        
        var needsFAB = false;
        var fabAction = '';
        var fabIcon = 'fas fa-plus';
        
        for (var i = 0; i < addPages.length; i++) {
            if (currentPath.indexOf(addPages[i]) !== -1) {
                needsFAB = true;
                
                // Set specific actions based on page
                if (currentPath.indexOf('/student/view') !== -1) {
                    fabAction = base_url + 'student/add';
                } else if (currentPath.indexOf('/employee/view') !== -1) {
                    fabAction = base_url + 'employee/add';
                } else if (currentPath.indexOf('/fees/invoice_list') !== -1) {
                    fabAction = base_url + 'fees/invoice_add';
                } else if (currentPath.indexOf('/homework') !== -1) {
                    fabAction = base_url + 'homework/add';
                } else if (currentPath.indexOf('/communication/mailbox') !== -1) {
                    fabAction = base_url + 'communication/mailbox/compose';
                    fabIcon = 'fas fa-envelope';
                }
                
                break;
            }
        }
        
        // Add FAB if needed
        if (needsFAB && fabAction) {
            // Remove existing FAB if any
            $('.md-fab').remove();
            
            // Create and append the FAB
            var fab = $('<a>', {
                'class': 'md-fab md-ripple',
                'href': fabAction,
                'html': '<i class="' + fabIcon + '"></i>'
            });
            
            $('body').append(fab);
        }
    }
    
    // Show a Material Design snackbar message
    function showSnackbar(message, action, actionText, duration) {
        if (!isMobile()) return;
        
        // Remove existing snackbar if any
        $('.md-snackbar').remove();
        
        // Default values
        duration = duration || 4000;
        actionText = actionText || 'OK';
        
        // Create snackbar elements
        var snackbar = $('<div>', {
            'class': 'md-snackbar',
            'style': 'opacity: 0; transform: translateX(-50%) translateY(20px)'
        });
        
        var snackbarText = $('<div>', {
            'class': 'md-snackbar-text',
            'text': message
        });
        
        snackbar.append(snackbarText);
        
        // Add action button if action is provided
        if (action) {
            var snackbarAction = $('<button>', {
                'class': 'md-snackbar-action',
                'text': actionText
            });
            
            snackbarAction.on('click', function() {
                action();
                snackbar.remove();
            });
            
            snackbar.append(snackbarAction);
        }
        
        // Append to body and animate in
        $('body').append(snackbar);
        
        setTimeout(function() {
            snackbar.css({
                'opacity': '1',
                'transform': 'translateX(-50%) translateY(0)'
            });
        }, 10);
        
        // Auto dismiss after duration
        setTimeout(function() {
            snackbar.css({
                'opacity': '0',
                'transform': 'translateX(-50%) translateY(20px)'
            });
            
            setTimeout(function() {
                snackbar.remove();
            }, 300);
        }, duration);
    }
    
    // Override SweetAlert with Material Design styling
    function setupMaterialAlerts() {
        if (!isMobile() || typeof swal === 'undefined') return;
        
        // Store the original swal function
        var originalSwal = swal;
        
        // Override swal with our Material Design version
        window.swal = function() {
            var args = Array.prototype.slice.call(arguments);
            
            // If first argument is an object, add our Material Design classes
            if (typeof args[0] === 'object') {
                args[0].customClass = args[0].customClass || {};
                args[0].customClass.container = 'md-dialog-container';
                args[0].customClass.popup = 'md-dialog';
                args[0].customClass.header = 'md-dialog-header';
                args[0].customClass.title = 'md-dialog-title';
                args[0].customClass.content = 'md-dialog-content';
                args[0].customClass.actions = 'md-dialog-actions';
                args[0].customClass.confirmButton = 'md-dialog-confirm';
                args[0].customClass.cancelButton = 'md-dialog-cancel';
            }
            
            return originalSwal.apply(this, args);
        };
        
        // Copy all properties from the original swal
        for (var prop in originalSwal) {
            if (originalSwal.hasOwnProperty(prop)) {
                window.swal[prop] = originalSwal[prop];
            }
        }
    }
    
    // Enhance dashboard with Material Design 3
    function enhanceDashboard() {
        // Check if dashboard has already been enhanced
        if ($('.dashboard-header').length) return;
        
        // Add dashboard class to body
        $('body').addClass('dashboard-page');
        
        // Create dashboard header
        var pageTitle = $('.page-header h2').text();
        var dashboardHeader = $('<div class="dashboard-header">' +
            '<h2>' + pageTitle + '</h2>' +
            '<p>Welcome back!</p>' +
            '</div>');
        
        // Insert dashboard header at the top of content body
        $('.content-body').prepend(dashboardHeader);
        
        // Hide original page header
        $('.page-header').hide();
        
        // Wrap dashboard cards in container
        var dashboardStats = $('<div class="dashboard-stats"></div>');
        $('.content-body > .row:first').wrap(dashboardStats);
        
        // Enhance dashboard cards
        $('.card').each(function() {
            var $card = $(this);
            
            // Skip cards that have already been enhanced
            if ($card.hasClass('dashboard-card')) return;
            
            // Get card title and content
            var cardTitle = $card.find('.card-header').text().trim();
            var cardContent = $card.find('.card-body');
            
            // Look for numeric values that might be statistics
            var numericValue = cardContent.text().match(/\d[\d,.]*/);
            var valueText = numericValue ? numericValue[0] : '';
            
            // Determine card type based on title or content
            var cardType = 'primary';
            var iconClass = 'fas fa-chart-line';
            
            if (cardTitle.toLowerCase().includes('student') || cardContent.text().toLowerCase().includes('student')) {
                cardType = 'primary';
                iconClass = 'fas fa-user-graduate';
            } else if (cardTitle.toLowerCase().includes('teacher') || cardContent.text().toLowerCase().includes('teacher')) {
                cardType = 'secondary';
                iconClass = 'fas fa-chalkboard-teacher';
            } else if (cardTitle.toLowerCase().includes('parent') || cardContent.text().toLowerCase().includes('parent')) {
                cardType = 'info';
                iconClass = 'fas fa-user-friends';
            } else if (cardTitle.toLowerCase().includes('class') || cardContent.text().toLowerCase().includes('class')) {
                cardType = 'success';
                iconClass = 'fas fa-school';
            } else if (cardTitle.toLowerCase().includes('fee') || cardContent.text().toLowerCase().includes('fee') || 
                      cardTitle.toLowerCase().includes('payment') || cardContent.text().toLowerCase().includes('payment')) {
                cardType = 'warning';
                iconClass = 'fas fa-money-bill-wave';
            } else if (cardTitle.toLowerCase().includes('attendance') || cardContent.text().toLowerCase().includes('attendance')) {
                cardType = 'info';
                iconClass = 'fas fa-calendar-check';
            } else if (cardTitle.toLowerCase().includes('exam') || cardContent.text().toLowerCase().includes('exam')) {
                cardType = 'error';
                iconClass = 'fas fa-file-alt';
            }
            
            // Create card icon
            var cardIcon = $('<div class="card-icon ' + cardType + '"><i class="' + iconClass + '"></i></div>');
            
            // Create new card structure
            var newCardContent = $('<div class="card-title">' + cardTitle + '</div>');
            
            if (valueText) {
                newCardContent.append('<div class="card-value">' + valueText + '</div>');
                
                // Add a random change percentage for demonstration
                var isPositive = Math.random() > 0.5;
                var changePercent = (Math.random() * 10).toFixed(1);
                var changeIcon = isPositive ? 'fas fa-arrow-up' : 'fas fa-arrow-down';
                var changeClass = isPositive ? 'positive' : 'negative';
                
                newCardContent.append('<div class="card-change ' + changeClass + '">' +
                    '<i class="' + changeIcon + '"></i> ' +
                    changePercent + '% from last month' +
                    '</div>');
            }
            
            // Replace card content
            $card.addClass('dashboard-card')
                .removeClass('card-widget')
                .empty()
                .append(cardIcon)
                .append(newCardContent);
        });
        
        // Create horizontal scrollable stat cards if there are more than 3 cards
        if ($('.dashboard-stats .row > div').length > 3) {
            var $statCards = $('<div class="stat-cards"></div>');
            
            $('.dashboard-stats .row > div').each(function() {
                var $card = $(this).find('.card');
                var cardTitle = $card.find('.card-title').text();
                var cardValue = $card.find('.card-value').text();
                var cardChange = $card.find('.card-change').clone();
                
                var $statCard = $('<div class="stat-card"></div>')
                    .append('<div class="stat-title">' + cardTitle + '</div>')
                    .append('<div class="stat-value">' + cardValue + '</div>')
                    .append(cardChange);
                
                $statCards.append($statCard);
            });
            
            // Insert stat cards before the main cards row
            $('.dashboard-stats').prepend($statCards);
        }
        
        // Add section headers for other content
        $('.content-body > .row:not(:first)').each(function(index) {
            var sectionTitle = 'Recent Activity';
            
            if ($(this).find('.card-header').length) {
                sectionTitle = $(this).find('.card-header:first').text().trim();
            } else if (index === 1) {
                sectionTitle = 'Recent Activity';
            } else if (index === 2) {
                sectionTitle = 'Performance Overview';
            } else if (index === 3) {
                sectionTitle = 'Upcoming Events';
            }
            
            $(this).before('<h3 class="section-header">' + sectionTitle + '</h3>');
        });
    }
    
    // Initialize Material Design
    $(document).ready(function() {
        if (isMobile()) {
            // Add Material Design 3 body class
            $('body').addClass('md-body');
            
            // Apply Material Design to elements
            applyMaterialDesign();
            
            // Create FAB if needed
            createFAB();
            
            // Setup Material Design alerts
            setupMaterialAlerts();
            
            // Override default alert with snackbar
            var originalAlert = window.alert;
            window.alert = function(message) {
                if (isMobile()) {
                    showSnackbar(message);
                } else {
                    originalAlert(message);
                }
            };
        }
        
        // Handle window resize
        $(window).resize(function() {
            if (isMobile()) {
                $('body').addClass('md-body');
                applyMaterialDesign();
                createFAB();
            } else {
                $('body').removeClass('md-body');
            }
        });
    });
    
    // Expose functions to global scope
    window.MaterialDesign = {
        showSnackbar: showSnackbar,
        applyMaterialDesign: applyMaterialDesign,
        createFAB: createFAB,
        enhanceDashboard: enhanceDashboard
    };
    
})(jQuery);