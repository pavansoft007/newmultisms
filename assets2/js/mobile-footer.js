// Mobile Footer Functionality
(function($) {
    'use strict';
    
    // Function to check if device is mobile
    function isMobile() {
        return window.innerWidth <= 767;
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
    
    // Initialize mobile footer
    $(document).ready(function() {
        if (isMobile()) {
            $('body').addClass('has-mobile-footer');
            setActiveMenuItem();
        }
        
        // Handle window resize
        $(window).resize(function() {
            if (isMobile()) {
                $('body').addClass('has-mobile-footer');
            } else {
                $('body').removeClass('has-mobile-footer');
            }
        });
    });
    
})(jQuery);