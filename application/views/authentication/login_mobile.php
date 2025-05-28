<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo translate('login'); ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Roboto', Arial, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Animated background elements */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 2px, transparent 2px);
            background-size: 50px 50px;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-50px, -50px) rotate(360deg); }
        }

        .login-mobile-container {
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.12),
                0 2px 16px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px 32px;
            margin: 24px 16px;
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-mobile-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
        }

        .login-mobile-logo img {
            height: 72px;
            width: 72px;
            object-fit: contain;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            background: rgba(255, 255, 255, 0.9);
            padding: 8px;
        }

        .login-mobile-title {
            text-align: center;
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .login-mobile-subtitle {
            text-align: center;
            color: #6b7280;
            font-size: 1rem;
            margin-bottom: 32px;
            font-weight: 400;
        }

        .login-mobile-form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .login-mobile-form-group label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 500;
            font-size: 14px;
            letter-spacing: 0.025em;
        }

        .login-mobile-form-group label i {
            margin-right: 8px;
            color: #9ca3af;
            width: 16px;
        }

        .input-wrapper {
            position: relative;
        }

        .login-mobile-form-group input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            font-size: 16px;
            background: #fafafa;
            color: #1f2937;
            outline: none;
            transition: all 0.3s ease;
            font-weight: 400;
        }

        .login-mobile-form-group input:focus {
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .login-mobile-form-group input::placeholder {
            color: #9ca3af;
        }

        .error {
            color: #ef4444;
            font-size: 13px;
            margin-top: 6px;
            font-weight: 500;
            display: block;
        }

        .login-mobile-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 24px 0 32px 0;
            font-size: 14px;
        }

        .login-mobile-links label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 400 !important;
            color: #6b7280;
            cursor: pointer;
        }

        /* Custom checkbox styling */
        .login-mobile-links input[type="checkbox"] {
            position: relative;
            width: 20px;
            height: 20px;
            appearance: none;
            background: #f3f4f6;
            border: 2px solid #d1d5db;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-mobile-links input[type="checkbox"]:checked {
            background: #667eea;
            border-color: #667eea;
        }

        .login-mobile-links input[type="checkbox"]:checked::after {
            content: "";
            position: absolute;
            left: 6px;
            top: 2px;
            width: 6px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .login-mobile-links a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .login-mobile-links a:hover {
            color: #5a67d8;
            text-decoration: underline;
        }

        .login-mobile-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 0.025em;
        }

        .login-mobile-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
        }

        .login-mobile-btn:active {
            transform: translateY(0);
        }

        .login-mobile-btn i {
            font-size: 16px;
        }

        .login-mobile-footer {
            text-align: center;
            margin-top: 32px;
            color: #9ca3af;
            font-size: 14px;
            font-weight: 400;
        }

        /* Loading state */
        .login-mobile-btn.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .login-mobile-btn.loading::after {
            content: '';
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 8px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .login-mobile-container {
                padding: 32px 24px;
                margin: 16px;
                border-radius: 20px;
            }

            .login-mobile-title {
                font-size: 1.5rem;
            }

            .login-mobile-form-group input {
                padding: 14px 16px;
            }

            .login-mobile-btn {
                padding: 16px;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .login-mobile-container {
                background: rgba(17, 24, 39, 0.95);
                border: 1px solid rgba(75, 85, 99, 0.3);
            }

            .login-mobile-title {
                color: #f9fafb;
            }

            .login-mobile-subtitle {
                color: #9ca3af;
            }

            .login-mobile-form-group label {
                color: #d1d5db;
            }

            .login-mobile-form-group input {
                background: rgba(31, 41, 55, 0.5);
                border-color: #374151;
                color: #f9fafb;
            }

            .login-mobile-form-group input:focus {
                background: rgba(31, 41, 55, 0.8);
            }

            .login-mobile-links label {
                color: #9ca3af;
            }
        }

        /* Enhanced focus animations */
        .login-mobile-form-group {
            transition: transform 0.2s ease;
        }

        .login-mobile-form-group:focus-within {
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    <div class="login-mobile-container">
        <div class="login-mobile-logo">
            <img src="<?php echo $this->application_model->getBranchImage($branch_id, 'logo'); ?>" alt="Logo">
        </div>
        <div class="login-mobile-title"><?php echo $global_config['institute_name']; ?></div>
        <div class="login-mobile-subtitle"><?php echo translate('login_to_continue'); ?></div>
        
        <?php echo form_open($this->uri->uri_string()); ?>
            <div class="login-mobile-form-group">
                <label for="email">
                    <i class="far fa-user"></i> 
                    <?php echo translate('username'); ?>
                </label>
                <div class="input-wrapper">
                    <input type="text" 
                           name="email" 
                           id="email" 
                           value="<?php echo set_value('email'); ?>" 
                           placeholder="<?php echo translate('username'); ?>" 
                           required>
                </div>
                <span class="error"><?php echo form_error('email'); ?></span>
            </div>
            
            <div class="login-mobile-form-group">
                <label for="password">
                    <i class="fas fa-unlock-alt"></i> 
                    <?php echo translate('password'); ?>
                </label>
                <div class="input-wrapper">
                    <input type="password" 
                           name="password" 
                           id="password" 
                           placeholder="<?php echo translate('password'); ?>" 
                           required>
                </div>
                <span class="error"><?php echo form_error('password'); ?></span>
            </div>
            
            <div class="login-mobile-links">
                <label>
                    <input type="checkbox" name="remember" id="remember"> 
                    <?php echo translate('remember'); ?>
                </label>
                <a href="<?php echo base_url('authentication/forgot') . $this->authentication_model->getSegment(3); ?>">
                    <?php echo translate('lose_your_password'); ?>
                </a>
            </div>
            
            <button type="submit" class="login-mobile-btn" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i> 
                <?php echo translate('login'); ?>
            </button>
        <?php echo form_close(); ?>
        
        <div class="login-mobile-footer">
            <?php echo $global_config['footer_text']; ?>
        </div>
    </div>

    <script>
        // Enhanced form interactions
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const loginBtn = document.getElementById('loginBtn');
            const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');

            // Add loading state on form submission
            if (form && loginBtn) {
                form.addEventListener('submit', function() {
                    loginBtn.classList.add('loading');
                    loginBtn.style.pointerEvents = 'none';
                });
            }

            // Enhanced input interactions
            inputs.forEach(input => {
                // Focus animations
                input.addEventListener('focus', function() {
                    this.parentElement.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.parentElement.style.transform = 'scale(1)';
                });

                // Smooth typing effect
                input.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        this.style.fontWeight = '500';
                    } else {
                        this.style.fontWeight = '400';
                    }
                });
            });

            // Checkbox animation
            const checkbox = document.getElementById('remember');
            if (checkbox) {
                checkbox.addEventListener('change', function() {
                    this.style.transform = this.checked ? 'scale(1.1)' : 'scale(1)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            }
        });
    </script>
</body>
</html>