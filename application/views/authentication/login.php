<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <meta name="keywords" content="">
    <meta name="description" content="<?php echo $global_config['institute_name'] ?>">
    <meta name="author" content="<?php echo $global_config['institute_name'] ?>">
    <title><?php echo translate('login');?></title>
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.png');?>">
    <link href="<?php echo is_secure('fonts.googleapis.com/css?family=Signika:300,400,600,700|Roboto:300,400,500,700'); ?>" rel="stylesheet"> 
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap/css/bootstrap.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome/css/all.min.css'); ?>">
    <script src="<?php echo base_url('assets/vendor/jquery/jquery.js');?>"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/sweetalert/sweetalert-custom.css');?>">
    <script src="<?php echo base_url('assets/vendor/sweetalert/sweetalert.min.js');?>"></script>
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Roboto', 'Signika', sans-serif;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Remove direct background image */
            background: #f4f6fa;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('<?php echo base_url('assets/login_page/image/school-bg.jpg'); ?>') no-repeat center center;
            background-size: 100%;
            opacity: 0.25; /* more visible watermark */
            z-index: 0;
            pointer-events: none;
        }
        .login-glass-card {
            background: rgba(255,255,255,0.90);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
            border-radius: 24px;
            padding: 48px 32px 32px 32px;
            max-width: 400px;
            width: 100%;
            margin: 40px 0;
            position: relative;
            z-index: 2;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 16px;
        }
        .login-logo img {
            height: 60px;
        }
        .login-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            color: #2d3a4b;
            margin-bottom: 8px;
        }
        .login-subtitle {
            text-align: center;
            color: #6b7280;
            font-size: 1rem;
            margin-bottom: 32px;
            font-weight: 400;
        }
        .form-group {
            margin-bottom: 24px;
        }
        .form-control {
            border-radius: 12px;
            padding: 16px 18px;
            font-size: 16px;
            border: 1.5px solid #e5e7eb;
            background: #f9fafb;
            color: #1f2937;
            transition: border 0.2s;
        }
        .form-control:focus {
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 2px #667eea33;
        }
        .input-group-addon {
            border-radius: 12px 0 0 12px;
            background: #f3f4f6;
            border: none;
        }
        .error {
            color: #ef4444;
            font-size: 13px;
            margin-top: 6px;
            font-weight: 500;
            display: block;
        }
        .forgot-text {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 14px;
        }
        .forgot-text a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-text a:hover {
            text-decoration: underline;
        }
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.18);
            transition: all 0.2s;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b21a8 100%);
            transform: translateY(-2px);
        }
        .sign-footer {
            text-align: center;
            margin-top: 32px;
            color: #9ca3af;
            font-size: 14px;
            font-weight: 400;
        }
        .f-social-links {
            text-align: center;
            margin-bottom: 16px;
        }
        .f-social-links a {
            display: inline-block;
            margin: 0 8px;
            color: #667eea;
            font-size: 20px;
            transition: color 0.2s;
        }
        .f-social-links a:hover {
            color: #5a67d8;
        }
        @media (max-width: 600px) {
            .login-glass-card {
                padding: 32px 8px 24px 8px;
                max-width: 98vw;
            }
        }
    </style>
    <script type="text/javascript">
        var base_url = '<?php echo base_url() ?>';
    </script>
</head>
<body>
    <div class="login-glass-card">
        <div class="login-logo">
            <img src="<?php echo get_instance()->application_model->getBranchImage($branch_id, 'logo'); ?>" alt="School Logo">
        </div>
        <div class="login-title"><?php echo $global_config['institute_name']; ?></div>
        <div class="login-subtitle"><?php echo translate('login_to_continue'); ?></div>
        <div class="f-social-links">
            <a href="<?php echo $global_config['facebook_url']; ?>" target="_blank"><span class="fab fa-facebook-f"></span></a>
            <a href="<?php echo $global_config['twitter_url']; ?>" target="_blank"><span class="fab fa-twitter"></span></a>
            <a href="<?php echo $global_config['linkedin_url']; ?>" target="_blank"><span class="fab fa-linkedin-in"></span></a>
            <a href="<?php echo $global_config['youtube_url']; ?>" target="_blank"><span class="fab fa-youtube"></span></a>
        </div>
        <?php echo form_open(get_instance()->uri->uri_string()); ?>
            <div class="form-group <?php if (form_error('email')) echo 'has-error'; ?>">
                <div class="input-group input-group-icon">
                    <span class="input-group-addon">
                        <span class="icon">
                            <i class="far fa-user"></i>
                        </span>
                    </span>
                    <input type="text" class="form-control" name="email" value="<?php echo set_value('email'); ?>" placeholder="<?php echo translate('username'); ?>" />
                </div>
                <span class="error"><?php echo form_error('email'); ?></span>
            </div>
            <div class="form-group <?php if (form_error('password')) echo 'has-error'; ?>">
                <div class="input-group input-group-icon">
                    <span class="input-group-addon">
                        <span class="icon"><i class="fas fa-unlock-alt"></i></span>
                    </span>
                    <input type="password" class="form-control" name="password" placeholder="<?php echo translate('password'); ?>" />
                </div>
                <span class="error"><?php echo form_error('password'); ?></span>
            </div>
            <div class="forgot-text">
                <label style="margin-bottom:0;">
                    <input type="checkbox" name="remember" id="remember" style="margin-right:6px;"> <?php echo translate('remember'); ?>
                </label>
                <a href="<?php echo base_url('authentication/forgot') . get_instance()->authentication_model->getSegment(3); ?>"><?php echo translate('lose_your_password'); ?></a>
            </div>
            <button type="submit" id="btn_submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> <?php echo translate('login'); ?>
            </button>
            <div class="sign-footer">
                <p><?php echo $global_config['footer_text']; ?></p>
            </div>
        <?php echo form_close(); ?>
    </div>
    <script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/jquery-placeholder/jquery-placeholder.js'); ?>"></script>
    <?php
    $CI = get_instance();
    $alertclass = "";
    if($CI->session->flashdata('alert-message-success')){
        $alertclass = "success";
    } else if ($CI->session->flashdata('alert-message-error')){
        $alertclass = "error";
    } else if ($CI->session->flashdata('alert-message-info')){
        $alertclass = "info";
    }
    if($alertclass != ''):
        $alert_message = $CI->session->flashdata('alert-message-'. $alertclass);
    ?>
        <script type="text/javascript">
            swal({
                toast: true,
                position: 'top-end',
                type: '<?php echo $alertclass;?>',
                title: '<?php echo $alert_message;?>',
                confirmButtonClass: 'btn btn-default',
                buttonsStyling: false,
                timer: 8000
            })
        </script>
    <?php endif; ?>
</body>
</html>