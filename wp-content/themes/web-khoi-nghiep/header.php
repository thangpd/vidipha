<!DOCTYPE html>
<!--[if IE 9 ]> <html <?php language_attributes(); ?> class="ie9 <?php flatsome_html_classes(); ?>"> <![endif]-->
<!--[if IE 8 ]> <html <?php language_attributes(); ?> class="ie8 <?php flatsome_html_classes(); ?>"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?> class="<?php flatsome_html_classes(); ?>"> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>
	<meta property="fb:app_id" content="104537736801666" />
<meta property="fb:admins" content="100001613853961"/>

	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.9&appId=104537736801666";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script type="text/javascript">
	jQuery(function() {
    jQuery('marquee').mouseover(function() {
        jQuery(this).attr('scrollamount',0);
    }).mouseout(function() {
         jQuery(this).attr('scrollamount',5);
    });
});
</script>
</head>

<body <?php body_class(); // Body classes is added from inc/helpers-frontend.php ?>>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'flatsome' ); ?></a>

<div id="wrapper">

<?php do_action('flatsome_before_header'); ?>

<header id="header" class="header <?php flatsome_header_classes();  ?>">
   <div class="header-wrapper">
	<?php
		get_template_part('template-parts/header/header', 'wrapper');
	?>
   </div><!-- header-wrapper-->
</header>

<?php do_action('flatsome_after_header'); ?>
<marquee direction="left" behavior="scroll" scrolldelay="5" scrollamount="5" onmouseover="this.stop();" onmouseout="this.start();"  align="top"><span class="cau-chao">CÔNG TY CỔ PHẦN DƯỢC PHẨM TRUNG ƯƠNG VIDIPHA</span></marquee>

<main id="main" class="<?php flatsome_main_classes();  ?>">
