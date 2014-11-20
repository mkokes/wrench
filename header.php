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

<input type="checkbox" class="site-oc-check" id="site-oc-check" />
<nav class="site-oc" id="site-oc">
<?php wp_nav_menu( array('menu' => 'Off Canvas Menu' )); ?>
</nav>

<div id="contentwrap" class="page-wrap">
<header class="main-header">
<div id="navicon">
	<label for="site-oc-check" class="toggle-menu">
	<svg class="naviconsvg" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" preserveAspectRatio="xMinYMin meet" viewBox="0 0 800 960"  xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
	<g fill="#CE1446">
	<path d="M417.4,224H94.6C77.7,224,64,238.3,64,256c0,17.7,13.7,32,30.6,32h322.8c16.9,0,30.6-14.3,30.6-32   C448,238.3,434.3,224,417.4,224z"/>
	<path d="M417.4,96H94.6C77.7,96,64,110.3,64,128c0,17.7,13.7,32,30.6,32h322.8c16.9,0,30.6-14.3,30.6-32   C448,110.3,434.3,96,417.4,96z"/>
	<path d="M417.4,352H94.6C77.7,352,64,366.3,64,384c0,17.7,13.7,32,30.6,32h322.8c16.9,0,30.6-14.3,30.6-32   C448,366.3,434.3,352,417.4,352z"/>
	</g>
	</svg>
	</label>
</div>
<div id="site-title"><h1><a href="<?php echo site_url();?>"><?php bloginfo('name'); ?></a></h1></div>
<div id="site-search-btn">
<svg class="searchsvg" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMinYMin meet" viewBox="0 0 800 960"
>
<path class="magglass" fill="none" stroke="#CE1446" stroke-width="36" stroke-linecap="round" d="m280,278a153,153 0 1,0-2,2l170,170m-91-117 110,110-26,26-110-110"/>
</svg>
</div>
<div id="site-menu"><?php wp_nav_menu( array('menu' => 'Main Menu' )); ?></div>

<!-- SET:Lower Menu -->
<div id="site-search-form">
  	<div class="dropdown-content">
  		<div class="site-search-field">
  			<form method="get" id="searchform" action="http://martysgarage.info/">
	  			<div class="search-container">
		  			<input type="text" size="18" value="" name="s" id="s" class="search-text" />
		  			<input type="submit" value="Search" name="commit" id="search-submit" class="search-submit"   />
		  		</div>
			</form>
		</div>
  	</div>
</div>
<!-- END:Lower Menu -->

</header>