/**
 * Main Menu Responsive Script
 * Handles responsive behavior for the main menu page
 */
(function() {
    // Function to handle responsive behavior
    function handleResponsive() {
        var isMobile = window.innerWidth <= 991;
        
        // Set mobile cookie for server-side detection
        document.cookie = "is_mobile=" + isMobile + "; path=/; max-age=3600";
        document.cookie = "screen_width=" + window.innerWidth + "; path=/; max-age=3600";
        
        // Handle mobile footer visibility
        var mobileFooter = document.querySelector('.mobile-footer');
        if (mobileFooter) {
            if (isMobile) {
                mobileFooter.style.display = 'block';
            } else {
                mobileFooter.style.display = 'none';
            }
        }
        
        // Handle MD3 bottom nav visibility
        var md3BottomNav = document.querySelector('.md3-bottom-nav');
        if (md3BottomNav) {
            if (isMobile) {
                md3BottomNav.style.display = 'flex';
            } else {
                md3BottomNav.style.display = 'none';
            }
        }
    }
    
    // Run on page load
    window.addEventListener('load', handleResponsive);
    
    // Run on window resize
    window.addEventListener('resize', handleResponsive);
})();