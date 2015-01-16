<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<title><?php bloginfo('name'); ?> | <?php is_home() ? bloginfo('description') : wp_title(''); if ($paged>1) { echo ' | Page '. $paged; } ?></title>

<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
<!--[if IE]><meta http-equiv="cleartype" content="on"><![endif]-->
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->

<!-- http://t.co/dKP3o1e -->
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="mobile-web-app-capable" content="yes" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=no" />

<!-- Character Set -->
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<!-- Pingback URL -->
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js" type="text/javascript"></script>
<![endif]-->

<!-- Icons -->
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon.ico" />
<link rel="icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/apple-touch-icon.png"  />
<link rel="apple-touch-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/apple-touch-icon.png"  />
<link rel="apple-touch-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/apple-touch-icon-76x76.png" sizes="76x76" />
<link rel="apple-touch-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/apple-touch-icon-120x120.png" sizes="120x120" />
<link rel="apple-touch-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/apple-touch-icon-152x152.png" sizes="152x152" />

<?php
if (file_exists( get_stylesheet_directory() . '/include/webfonts.php' ) ) {
	require_once get_stylesheet_directory() . '/include/webfonts.php';
}
else if (file_exists( get_template_directory() . '/include/webfonts.php' ) ) {
	require_once get_template_directory() . '/include/webfonts.php';
}
?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="contentwrap" class="page-wrap">
<header class="main-header">
	<nav id="site-menu">
		<?php if ( has_nav_menu( 'main-menu' ) ) { 
			wp_nav_menu( array( 'theme_location' => 'main-menu') ); 
		} ?>
	</nav>
</header>