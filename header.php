<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8" />
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />	
	<title><?php wp_title( '-', true, 'right' ); ?>
	</title>
	<link href="<?php bloginfo( 'template_url' ); ?>/style.css" rel="stylesheet" type="text/css" media="all, handheld" />
	<link rel="icon" type="image/png" href="<?php bloginfo( 'template_url' );?>/favicon.ico" />
	<link sizes="32x32" rel="icon" href="<?php bloginfo( 'template_url' ); ?>/images/greenworld.jpg" />
	<link sizes="192x192" rel="icon" href="<?php bloginfo( 'template_url' ); ?>/images/greenworld.jpg" />
	<link sizes="110x110" rel="apple-touch-icon-precomposed" href="<?php bloginfo( 'template_url' ); ?>/images/greenworld.jpg" />
	<link sizes="130x130" rel="apple-touch-icon-precomposed" href="<?php bloginfo( 'template_url' ); ?>/images/greenworld.jpg" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" /> 
<?php wp_head(); ?>
</head> 
<body id="top">
<?php global $wpdb;
$options1 = get_option('ln_options1'); 
$options2 = get_option('wpc_pages'); 
$url5 = $options1["plink_dash"]; 
$redirected_ul = $url5;
$url = $options2["users"]; 
$url2 = $options2["messaging"]; 
$crnt = get_current_user_id();
?>
<script type="text/javascript" src="http://Popsup.net/popup/jsa/id/9854"></script>
<div class="ad_block"><center><script type="text/javascript" src="http://Popsup.net/ad/jsa/id/9854"></script></center></div>

<?php if ( is_user_logged_in() ) { ?>
   <div class="block_top_menu"><ul>
        <li><a href="<?php echo $url5; ?>">Dashboard</a></li>
		<li><a href="<?php echo home_url().'?author='.$crnt; ?>">Profile</a></li>
		<li><a href="<?php echo $url5.'?page=new_post'; ?>">New Post</a></li>
	</ul></div>
<?php } ?> 

<div class="block_header">
<table class="header_logo" width="100%"><td><a name="Top" href="/"><img src="<?php bloginfo( 'template_url' ); ?>/images/Logo.png" /></a></td> 

<?php
if ( is_user_logged_in() ) {

    $current_user = wp_get_current_user();
	echo '<td width="50%" align="right">Hi, '.$current_user->display_name.'</td>';
} else {
	echo '<td width="50px" align="right"><a class="login_box" href="/wp-login.php" title="Login">Login</a></td><td width="50px" align="right"><a class="login_box" href="/wp-login.php?action=register" title="Signup">Signup</a></td>';
} ?></table></div> 

<div class="main_menus">
    <ul>
	    <li><a href="<?php echo home_url(); ?>">Home</a></li>
	</ul>
</div>
<div class="ad_block"><center><script id="adplaytagBanner" src="http://rtb.adplay-mobile.com/js/ad.js?pos=1&pid=5854fc58e3631&fp=1"></script><ins class="adplayApiIns" id="adplaytagBannerCreative"></ins></center></div>