<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?> class="no-js">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?> class="no-js">
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?> class="no-js">
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<title><?php wp_title(''); ?></title>

	<link href="//www.google-analytics.com" rel="dns-prefetch">
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.png" type="image/x-icon"/>
	<link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/img/icons/ico-s2.jpg">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/img/icons/ico-l2.jpg">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/img/icons/ico-s.jpg">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/img/icons/ico-l.jpg">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="description" content="<?php bloginfo('description'); ?>">

	<?php wp_head(); ?>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-12973256-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-12973256-1');
</script>
	


</head>
<body <?php body_class(); ?>>
<!-- Added 2023-06-07 -->
	<?php wp_body_open(); ?>
<!-- Added 2023-06-07 -->

	

	
	<header class="header fixed-top header-small" role="banner">
		<?php get_template_part( 'partials/navigation', 'featured' ); ?>
		<div class="logo-small">
			<a href="<?php echo home_url(); ?>"><svg width="120" height="26" aria-label="Mongabay"><image xlink:href="<?php echo get_template_directory_uri(); ?>/img/logo/mongabay_logo_black.svg" src="<?php echo get_template_directory_uri(); ?>/img/logo/mongabay_logo_black.png" width="120" height="26" alt="Mongabay"/></svg></a>
		</div>
		<div class="social hidden-xs-down">
			<?php get_template_part( 'partials/section', 'social' ); ?>
		</div>
		<div class="sharemobile hidden-sm-up">
			<a class="sharethis"></a>
		</div>
	</header>
	<?php if(wp_is_mobile()) {?>
	<div id="backdrop" class=""></div>
	<?php }?>