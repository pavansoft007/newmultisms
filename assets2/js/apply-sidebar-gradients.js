// Apply sidebar gradient colors to match mainmenu cards
document.addEventListener('DOMContentLoaded', function() {
    // Get the sidebar element
    const sidebar = document.querySelector('.sidebar-left');
    if (!sidebar) return;
    
    // Get gradient preferences from localStorage or set defaults
    const sidebarGradient = localStorage.getItem('sidebar-gradient') || 'sidebar-gradient-blue-purple';
    const parentActiveGradient = localStorage.getItem('parent-active-gradient') || 'gradient-green-teal';
    const childActiveGradient = localStorage.getItem('child-active-gradient') || 'gradient-orange-red';
    
    // Apply base gradient class
    sidebar.classList.add('sidebar-gradient');
    
    // Apply specific gradient variation
    if (sidebarGradient) {
        // Remove any existing gradient classes
        const gradientClasses = [
            'sidebar-gradient-blue-purple',
            'sidebar-gradient-orange-red',
            'sidebar-gradient-green-teal',
            'sidebar-gradient-purple-pink',
            'sidebar-gradient-cyan-blue',
            'sidebar-gradient-indigo-purple',
            'sidebar-gradient-emerald-green'
        ];
        
        gradientClasses.forEach(cls => {
            sidebar.classList.remove(cls);
        });
        
        // Add the selected gradient class
        sidebar.classList.add(sidebarGradient);
    }
    
    // Custom style for active parent and child menu items
    const style = document.createElement('style');
    style.textContent = `
        html .sidebar-left .nano-content > .nav-main > li.nav-active > a {
            background: linear-gradient(135deg, var(--parent-gradient-start) 0%, var(--parent-gradient-end) 100%) !important;
        }
        
        html .sidebar-left .nano-content > .nav-main li .nav-children li.nav-active:not(.nav-parent) > a {
            background: linear-gradient(135deg, var(--child-gradient-start) 0%, var(--child-gradient-end) 100%) !important;
        }
    `;
    document.head.appendChild(style);
    
    // Set gradient variables based on selected gradients
    setGradientVariables(parentActiveGradient, 'parent');
    setGradientVariables(childActiveGradient, 'child');
    
    // Create sidebar settings panel
    createSidebarSettingsPanel();
    
    // Function to set CSS variables for gradients
    function setGradientVariables(gradientName, type) {
        let startColor, endColor;
        
        switch(gradientName) {
            case 'gradient-blue-purple':
                startColor = '#667eea';
                endColor = '#764ba2';
                break;
            case 'gradient-orange-red':
                startColor = '#f093fb';
                endColor = '#f5576c';
                break;
            case 'gradient-green-teal':
                startColor = '#4facfe';
                endColor = '#00f2fe';
                break;
            case 'gradient-purple-pink':
                startColor = '#a8edea';
                endColor = '#fed6e3';
                break;
            case 'gradient-cyan-blue':
                startColor = '#21d4fd';
                endColor = '#b721ff';
                break;
            case 'gradient-indigo-purple':
                startColor = '#6a11cb';
                endColor = '#2575fc';
                break;
            case 'gradient-emerald-green':
                startColor = '#00b09b';
                endColor = '#96c93d';
                break;
            default:
                startColor = '#667eea';
                endColor = '#764ba2';
        }
        
        document.documentElement.style.setProperty(`--${type}-gradient-start`, startColor);
        document.documentElement.style.setProperty(`--${type}-gradient-end`, endColor);
    }
    
    // Function to create sidebar settings panel
    function createSidebarSettingsPanel() {
        // Only create if we're in admin or settings page
        if (!document.querySelector('.settings-panel')) {
            return;
        }
        
        const settingsContainer = document.createElement('div');
        settingsContainer.className = 'sidebar-gradient-settings panel';
        settingsContainer.innerHTML = `
            <div class="panel-heading">
                <h3 class="panel-title">Sidebar Gradient Settings</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Sidebar Background Gradient</label>
                    <select id="sidebar-gradient-select" class="form-control">
                        <option value="sidebar-gradient-blue-purple" ${sidebarGradient === 'sidebar-gradient-blue-purple' ? 'selected' : ''}>Blue to Purple</option>
                        <option value="sidebar-gradient-orange-red" ${sidebarGradient === 'sidebar-gradient-orange-red' ? 'selected' : ''}>Orange to Red</option>
                        <option value="sidebar-gradient-green-teal" ${sidebarGradient === 'sidebar-gradient-green-teal' ? 'selected' : ''}>Green to Teal</option>
                        <option value="sidebar-gradient-purple-pink" ${sidebarGradient === 'sidebar-gradient-purple-pink' ? 'selected' : ''}>Purple to Pink</option>
                        <option value="sidebar-gradient-cyan-blue" ${sidebarGradient === 'sidebar-gradient-cyan-blue' ? 'selected' : ''}>Cyan to Blue</option>
                        <option value="sidebar-gradient-indigo-purple" ${sidebarGradient === 'sidebar-gradient-indigo-purple' ? 'selected' : ''}>Indigo to Purple</option>
                        <option value="sidebar-gradient-emerald-green" ${sidebarGradient === 'sidebar-gradient-emerald-green' ? 'selected' : ''}>Emerald to Green</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Parent Menu Active Gradient</label>
                    <select id="parent-active-gradient-select" class="form-control">
                        <option value="gradient-blue-purple" ${parentActiveGradient === 'gradient-blue-purple' ? 'selected' : ''}>Blue to Purple</option>
                        <option value="gradient-orange-red" ${parentActiveGradient === 'gradient-orange-red' ? 'selected' : ''}>Orange to Red</option>
                        <option value="gradient-green-teal" ${parentActiveGradient === 'gradient-green-teal' ? 'selected' : ''}>Green to Teal</option>
                        <option value="gradient-purple-pink" ${parentActiveGradient === 'gradient-purple-pink' ? 'selected' : ''}>Purple to Pink</option>
                        <option value="gradient-cyan-blue" ${parentActiveGradient === 'gradient-cyan-blue' ? 'selected' : ''}>Cyan to Blue</option>
                        <option value="gradient-indigo-purple" ${parentActiveGradient === 'gradient-indigo-purple' ? 'selected' : ''}>Indigo to Purple</option>
                        <option value="gradient-emerald-green" ${parentActiveGradient === 'gradient-emerald-green' ? 'selected' : ''}>Emerald to Green</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Child Menu Active Gradient</label>
                    <select id="child-active-gradient-select" class="form-control">
                        <option value="gradient-blue-purple" ${childActiveGradient === 'gradient-blue-purple' ? 'selected' : ''}>Blue to Purple</option>
                        <option value="gradient-orange-red" ${childActiveGradient === 'gradient-orange-red' ? 'selected' : ''}>Orange to Red</option>
                        <option value="gradient-green-teal" ${childActiveGradient === 'gradient-green-teal' ? 'selected' : ''}>Green to Teal</option>
                        <option value="gradient-purple-pink" ${childActiveGradient === 'gradient-purple-pink' ? 'selected' : ''}>Purple to Pink</option>
                        <option value="gradient-cyan-blue" ${childActiveGradient === 'gradient-cyan-blue' ? 'selected' : ''}>Cyan to Blue</option>
                        <option value="gradient-indigo-purple" ${childActiveGradient === 'gradient-indigo-purple' ? 'selected' : ''}>Indigo to Purple</option>
                        <option value="gradient-emerald-green" ${childActiveGradient === 'gradient-emerald-green' ? 'selected' : ''}>Emerald to Green</option>
                    </select>
                </div>
                
                <button id="save-sidebar-settings" class="btn btn-primary">Save Settings</button>
            </div>
        `;
        
        // Find a good place to append the settings panel
        const settingsTarget = document.querySelector('.settings-panel') || document.querySelector('.content-body');
        if (settingsTarget) {
            settingsTarget.appendChild(settingsContainer);
            
            // Add event listeners for settings changes
            document.getElementById('sidebar-gradient-select').addEventListener('change', function(e) {
                const selected = e.target.value;
                localStorage.setItem('sidebar-gradient', selected);
                
                // Remove existing gradient classes
                const gradientClasses = [
                    'sidebar-gradient-blue-purple',
                    'sidebar-gradient-orange-red',
                    'sidebar-gradient-green-teal',
                    'sidebar-gradient-purple-pink',
                    'sidebar-gradient-cyan-blue',
                    'sidebar-gradient-indigo-purple',
                    'sidebar-gradient-emerald-green'
                ];
                
                gradientClasses.forEach(cls => {
                    sidebar.classList.remove(cls);
                });
                
                // Add selected gradient class
                sidebar.classList.add(selected);
            });
            
            document.getElementById('parent-active-gradient-select').addEventListener('change', function(e) {
                const selected = e.target.value;
                localStorage.setItem('parent-active-gradient', selected);
                setGradientVariables(selected, 'parent');
            });
            
            document.getElementById('child-active-gradient-select').addEventListener('change', function(e) {
                const selected = e.target.value;
                localStorage.setItem('child-active-gradient', selected);
                setGradientVariables(selected, 'child');
            });
            
            document.getElementById('save-sidebar-settings').addEventListener('click', function() {
                alert('Sidebar gradient settings saved successfully!');
            });
        }
    }
});