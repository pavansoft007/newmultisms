// Mobile device detector with Material Design support
(function() {
    function detectMobileDevice() {
        if (!document.body) return;
        var isMobile = false;
        
        // Check for mobile user agent
        var userAgent = navigator.userAgent || navigator.vendor || window.opera;
        if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(userAgent.substr(0, 4))) {
            isMobile = true;
        }
        
        // Also check screen width
        if (window.innerWidth <= 767) {
            isMobile = true;
        }
        
        // Set cookie for server-side detection
        document.cookie = "is_mobile=" + isMobile + "; path=/; max-age=3600";
        document.cookie = "screen_width=" + window.innerWidth + "; path=/; max-age=3600";
        
        // Add classes to the body for CSS targeting
        if (isMobile) {
            document.body.classList.add('mobile-device');
            document.body.classList.add('md-body'); // Material Design body class
            document.body.classList.add('has-mobile-footer'); // Add class for mobile footer
            
            // Check if we're on Android
            if (/android/i.test(userAgent)) {
                document.body.classList.add('android-device');
            }
            
            // Check if we're on iOS
            if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                document.body.classList.add('ios-device');
            }
            
            // Apply Material Design theme color to status bar on Android
            var metaThemeColor = document.querySelector('meta[name=theme-color]');
            if (!metaThemeColor) {
                metaThemeColor = document.createElement('meta');
                metaThemeColor.name = 'theme-color';
                metaThemeColor.content = '#6750A4'; // Material Design primary color
                document.head.appendChild(metaThemeColor);
            }
            
            // Apply Material Design to status bar on iOS
            var metaAppleStatusBar = document.querySelector('meta[name=apple-mobile-web-app-status-bar-style]');
            if (!metaAppleStatusBar) {
                var metaAppleCapable = document.createElement('meta');
                metaAppleCapable.name = 'apple-mobile-web-app-capable';
                metaAppleCapable.content = 'yes';
                document.head.appendChild(metaAppleCapable);
                
                metaAppleStatusBar = document.createElement('meta');
                metaAppleStatusBar.name = 'apple-mobile-web-app-status-bar-style';
                metaAppleStatusBar.content = 'black-translucent';
                document.head.appendChild(metaAppleStatusBar);
            }
            
            // Add viewport meta tag if not present
            var metaViewport = document.querySelector('meta[name=viewport]');
            if (!metaViewport) {
                metaViewport = document.createElement('meta');
                metaViewport.name = 'viewport';
                metaViewport.content = 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no';
                document.head.appendChild(metaViewport);
            }
            
            // Ensure mobile footer is visible
            var mobileFooter = document.querySelector('.mobile-footer');
            if (mobileFooter) {
                mobileFooter.style.display = 'block';
                
                // Add padding to body to account for mobile footer
                document.body.style.paddingBottom = '80px';
                
                // Initialize mobile footer if jQuery is available
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.ready === 'function') {
                    jQuery(document).ready(function($) {
                        if (typeof reloadMobileFooter === 'function') {
                            reloadMobileFooter();
                        }
                    });
                }
            }
            
            // Add Material Design touch feedback to interactive elements
            addMaterialRipple();
        } else {
            document.body.classList.remove('mobile-device');
            document.body.classList.remove('md-body');
            document.body.classList.remove('android-device');
            document.body.classList.remove('ios-device');
            document.body.classList.remove('has-mobile-footer');
            
            // Hide mobile footer
            var mobileFooter = document.querySelector('.mobile-footer');
            if (mobileFooter) {
                mobileFooter.style.display = 'none';
            }
            
            // Reset body padding
            document.body.style.paddingBottom = '';
        }
        
        return isMobile;
    }
    
    // Add Material Design ripple effect to interactive elements
    function addMaterialRipple() {
        // Only add once
        if (window.materialRippleAdded) return;
        
        // Add ripple effect style
        var style = document.createElement('style');
        style.textContent = `
            .md-ripple {
                position: relative;
                overflow: hidden;
            }
            
            .md-ripple-effect {
                position: absolute;
                border-radius: 50%;
                background-color: rgba(255, 255, 255, 0.3);
                transform: scale(0);
                animation: md-ripple-animation 0.6s linear;
                pointer-events: none;
            }
            
            @keyframes md-ripple-animation {
                to {
                    transform: scale(2.5);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Add ripple effect to buttons, links, and other interactive elements
        document.addEventListener('click', function(e) {
            var target = e.target;
            
            // Find closest interactive element
            while (target && !(
                target.tagName === 'BUTTON' || 
                target.tagName === 'A' || 
                target.classList.contains('card') ||
                target.classList.contains('list-group-item') ||
                target.classList.contains('nav-link')
            )) {
                target = target.parentElement;
            }
            
            // If we found an interactive element
            if (target) {
                // Add ripple class if not present
                target.classList.add('md-ripple');
                
                // Get position
                var rect = target.getBoundingClientRect();
                var x = e.clientX - rect.left;
                var y = e.clientY - rect.top;
                
                // Create ripple element
                var ripple = document.createElement('span');
                ripple.className = 'md-ripple-effect';
                
                // Calculate size (max of width or height * 2)
                var size = Math.max(rect.width, rect.height) * 2;
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x - (size/2) + 'px';
                ripple.style.top = y - (size/2) + 'px';
                
                // Add to target
                target.appendChild(ripple);
                
                // Remove after animation
                setTimeout(function() {
                    ripple.remove();
                }, 600);
            }
        });
        
        window.materialRippleAdded = true;
    }
    
    // Run on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof detectMobileDevice === 'function') detectMobileDevice();
        });
    } else {
        if (typeof detectMobileDevice === 'function') detectMobileDevice();
    }

    // Run on window resize
    window.addEventListener('resize', function() {
        if (typeof detectMobileDevice === 'function') detectMobileDevice();
    });

    // Expose to global scope
    window.detectMobileDevice = detectMobileDevice;
})();