// Apply sidebar menu color settings
document.addEventListener('DOMContentLoaded', function() {
    // Add body classes based on meta tags
    const body = document.body;
    
    // Menu Text Color
    const menuTextColor = document.querySelector('meta[name="menu-text-color"]');
    if (menuTextColor) {
        body.classList.add('menu-text-' + menuTextColor.content);
    }

    // Menu Background Color
    const menuBgColor = document.querySelector('meta[name="menu-bg-color"]');
    if (menuBgColor && menuBgColor.content !== 'default') {
        body.classList.add('menu-bg-' + menuBgColor.content);
    }

    // Active Menu Text Color
    const activeMenuTextColor = document.querySelector('meta[name="active-menu-text-color"]');
    if (activeMenuTextColor) {
        body.classList.add('active-menu-text-' + activeMenuTextColor.content);
    }

    // Active Menu Background
    const activeMenuBg = document.querySelector('meta[name="active-menu-bg"]');
    if (activeMenuBg && activeMenuBg.content !== 'default') {
        body.classList.add('active-menu-bg-' + activeMenuBg.content);
    }

    // Menu Hover Style
    const menuHoverStyle = document.querySelector('meta[name="menu-hover-style"]');
    if (menuHoverStyle && menuHoverStyle.content !== 'default') {
        body.classList.add('menu-hover-' + menuHoverStyle.content);
    }
});