<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="Bigwala Technologies  school management system">
	<meta name="author" content="Bigwala Technologies">
	<title><?php echo html_escape($title);?></title>
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.png');?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<!-- Material Design Fonts -->
	<link href="<?php echo is_secure('fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');?>" rel="stylesheet">
	<!-- Material Icons -->
	<link href="<?php echo is_secure('fonts.googleapis.com/icon?family=Material+Icons');?>" rel="stylesheet">
	<!-- Debug theme config -->
	
	<?php
	// Ensure $theme_config is set before accessing its keys
	$theme_config = isset($theme_config) ? $theme_config : [];
	?>
	<?php if (isset($theme_config['sidebar_text_color'])): ?>
	<meta name="sidebar-text-color" content="<?php echo $theme_config['sidebar_text_color']; ?>">
	<?php endif; ?>
	<?php if (isset($theme_config['menu_text_color'])): ?>
	<meta name="menu-text-color" content="<?php echo $theme_config['menu_text_color']; ?>">
	<?php endif; ?>
	<?php if (isset($theme_config['menu_bg_color'])): ?>
	<meta name="menu-bg-color" content="<?php echo $theme_config['menu_bg_color']; ?>">
	<?php endif; ?>
	<?php if (isset($theme_config['active_menu_text_color'])): ?>
	<meta name="active-menu-text-color" content="<?php echo $theme_config['active_menu_text_color']; ?>">
	<?php endif; ?>
	<?php if (isset($theme_config['active_menu_bg'])): ?>
	<meta name="active-menu-bg" content="<?php echo $theme_config['active_menu_bg']; ?>">
	<?php endif; ?>
	<?php if (isset($theme_config['menu_hover_style'])): ?>
	<meta name="menu-hover-style" content="<?php echo $theme_config['menu_hover_style']; ?>">
	<?php endif; ?>
	<!-- include stylesheet -->
	<?php include 'stylesheet.php';?>

	<!-- sidebar colors css -->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/sidebar-colors.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/menu-colors-fix.css');?>?v=<?php echo time(); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/sidebar-custom.css');?>?v=<?php echo time(); ?>">

	<?php
	if(isset($headerelements)) {
		foreach ($headerelements as $type => $element) {
			if($type == 'css') {
				if(count($element)) {
					foreach ($element as $keycss => $css) {
						echo '<link rel="stylesheet" href="'. base_url('assets/' . $css) . '">' . "\n";
					}
				}
			} elseif($type == 'js') {
				if(count($element)) {
					foreach ($element as $keyjs => $js) {
						echo '<script type="text/javascript" src="' . base_url('assets/' . $js). '"></script>' . "\n";
					}
				}
			}
		}
	}
	?>
	<!-- ramom css -->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/ramom.css');?>">
	<!-- Material Design 3 CSS -->
	<link href="<?php echo base_url('assets/css/material-design-3.css'); ?>" rel="stylesheet">
	<?php if (isset($theme_config['border_mode']) && $theme_config['border_mode'] == 'false'): ?>
		<link rel="stylesheet" href="<?php echo base_url('assets/css/skins/square-borders.css');?>">
	<?php endif; ?>

	<!-- If user have enabled CSRF proctection this function will take care of the ajax requests and append custom header for CSRF -->
	<script type="text/javascript">
		var base_url = '<?php echo base_url(); ?>';
		var csrfData = <?php echo json_encode(csrf_jquery_token()); ?>;
		$(function($) {
			$.ajaxSetup({
				data: csrfData
			});
		});
	</script>
	<!-- Mobile Detector Script -->
	<script src="<?php echo base_url('assets/js/mobile-detector.js'); ?>"></script>
</head>