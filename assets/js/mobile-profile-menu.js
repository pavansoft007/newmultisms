// Mobile Profile Menu - Adds a profile icon to the mobile header
(function($) {
    'use strict';
    
    // Function to check if device is mobile
    function isMobile() {
        return window.innerWidth <= 767;
    }
    
    // Function to add mobile profile icon
    function addMobileProfileIcon() {
        if (!isMobile()) return;
        
        // Check if mobile profile icon already exists
        if ($('.mobile-profile-icon').length > 0) return;
        
        // Get user image from the userbox
        var userImage = $('#userbox .profile-picture img').attr('src');
        var userName = $('#userbox .u-text h4').text();
        var userRole = $('#userbox .u-text p').text();
        
        // Create mobile profile icon
        var mobileProfileIcon = $('<div class="mobile-profile-icon visible-xs">' +
            '<img src="' + userImage + '" alt="user-image">' +
            '</div>');
        
        // Create mobile profile menu
        var mobileProfileMenu = $('<div class="mobile-profile-menu">' +
            '<div class="mobile-profile-menu-header">' +
            '<img src="' + userImage + '" alt="user">' +
            '<div class="mobile-profile-menu-header-info">' +
            '<h4>' + userName + '</h4>' +
            '<p>' + userRole + '</p>' +
            '</div>' +
            '</div>' +
            '<ul>' +
            '</ul>' +
            '</div>');
        
        // Copy menu items from userbox dropdown
        $('#userbox .dropdown-menu ul.dropdown-user li').each(function() {
            if (!$(this).hasClass('user-p-box')) {
                var menuItem = $(this).clone();
                mobileProfileMenu.find('ul').append(menuItem);
            }
        });
        
        // Append mobile profile menu to mobile profile icon
        mobileProfileIcon.append(mobileProfileMenu);
        
        // Add mobile profile icon to header
        $('.header .logo-env').append(mobileProfileIcon);
        
        // Hide the original sidebar toggle
        $('.header .logo-env .toggle-sidebar-left').hide();
        
        // Add click event to toggle mobile profile menu
        $('.mobile-profile-icon').on('click', function(e) {
            e.stopPropagation();
            $('.mobile-profile-menu').toggleClass('active');
        });
        
        // Close menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.mobile-profile-icon').length) {
                $('.mobile-profile-menu').removeClass('active');
            }
        });
    }
    
    // Run on document ready
    $(document).ready(function() {
        addMobileProfileIcon();
    });
    
    // Run on window resize
    $(window).resize(function() {
        addMobileProfileIcon();
    });
    
    // Run on page load
    $(window).on('load', function() {
        addMobileProfileIcon();
    });
    
})(jQuery);