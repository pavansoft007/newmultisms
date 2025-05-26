// Apply sidebar menu color settings with debug
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sidebar menu colors script loaded');
    
    // Menu Text Color
    const menuTextColor = document.querySelector('meta[name="menu-text-color"]');
    console.log('Menu Text Color:', menuTextColor ? menuTextColor.content : 'Not found');
    if (menuTextColor) {
        const menuItems = document.querySelectorAll('.sidebar-left .nano-content > .nav-main > li > a');
        console.log('Menu Items found:', menuItems.length);
        menuItems.forEach(function(item, index) {
            console.log('Processing menu item', index);
            if (menuTextColor.content === 'light') {
                item.classList.add('menu-text-light');
                console.log('Added menu-text-light to item', index);
            } else if (menuTextColor.content === 'dark') {
                item.classList.add('menu-text-dark');
                console.log('Added menu-text-dark to item', index);
            }
        });
    }

    // Menu Background Color
    const menuBgColor = document.querySelector('meta[name="menu-bg-color"]');
    console.log('Menu Background Color:', menuBgColor ? menuBgColor.content : 'Not found');
    if (menuBgColor && menuBgColor.content !== 'default') {
        const menuItems = document.querySelectorAll('.sidebar-left .nano-content > .nav-main > li');
        console.log('Menu Items found for bg:', menuItems.length);
        menuItems.forEach(function(item, index) {
            console.log('Processing menu bg item', index);
            if (menuBgColor.content === 'light') {
                item.classList.add('menu-bg-light');
                console.log('Added menu-bg-light to item', index);
            } else if (menuBgColor.content === 'dark') {
                item.classList.add('menu-bg-dark');
                console.log('Added menu-bg-dark to item', index);
            }
        });
    }

    // Active Menu Text Color
    const activeMenuTextColor = document.querySelector('meta[name="active-menu-text-color"]');
    console.log('Active Menu Text Color:', activeMenuTextColor ? activeMenuTextColor.content : 'Not found');
    if (activeMenuTextColor) {
        const activeMenuItems = document.querySelectorAll('.sidebar-left .nano-content > .nav-main > li.nav-active > a');
        console.log('Active Menu Items found:', activeMenuItems.length);
        activeMenuItems.forEach(function(item, index) {
            console.log('Processing active menu item', index);
            if (activeMenuTextColor.content === 'light') {
                item.classList.add('active-menu-text-light');
                console.log('Added active-menu-text-light to item', index);
            } else if (activeMenuTextColor.content === 'dark') {
                item.classList.add('active-menu-text-dark');
                console.log('Added active-menu-text-dark to item', index);
            } else if (activeMenuTextColor.content === 'primary') {
                item.classList.add('active-menu-text-primary');
                console.log('Added active-menu-text-primary to item', index);
            }
        });
    }

    // Active Menu Background
    const activeMenuBg = document.querySelector('meta[name="active-menu-bg"]');
    console.log('Active Menu Background:', activeMenuBg ? activeMenuBg.content : 'Not found');
    if (activeMenuBg && activeMenuBg.content !== 'default') {
        const activeMenuItems = document.querySelectorAll('.sidebar-left .nano-content > .nav-main > li.nav-active');
        console.log('Active Menu Items found for bg:', activeMenuItems.length);
        activeMenuItems.forEach(function(item, index) {
            console.log('Processing active menu bg item', index);
            if (activeMenuBg.content === 'light') {
                item.classList.add('active-menu-bg-light');
                console.log('Added active-menu-bg-light to item', index);
            } else if (activeMenuBg.content === 'primary') {
                item.classList.add('active-menu-bg-primary');
                console.log('Added active-menu-bg-primary to item', index);
            } else if (activeMenuBg.content === 'dark') {
                item.classList.add('active-menu-bg-dark');
                console.log('Added active-menu-bg-dark to item', index);
            }
        });
    }

    // Menu Hover Style
    const menuHoverStyle = document.querySelector('meta[name="menu-hover-style"]');
    console.log('Menu Hover Style:', menuHoverStyle ? menuHoverStyle.content : 'Not found');
    if (menuHoverStyle && menuHoverStyle.content !== 'default') {
        const menuItems = document.querySelectorAll('.sidebar-left .nano-content > .nav-main > li');
        console.log('Menu Items found for hover:', menuItems.length);
        menuItems.forEach(function(item, index) {
            console.log('Processing menu hover item', index);
            if (menuHoverStyle.content === 'light') {
                item.classList.add('menu-hover-light');
                console.log('Added menu-hover-light to item', index);
            } else if (menuHoverStyle.content === 'dark') {
                item.classList.add('menu-hover-dark');
                console.log('Added menu-hover-dark to item', index);
            } else if (menuHoverStyle.content === 'primary') {
                item.classList.add('menu-hover-primary');
                console.log('Added menu-hover-primary to item', index);
            }
        });
    }
});