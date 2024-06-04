<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?> class="no-js">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?> class="no-js">
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<?php
if (wp_is_mobile()) {
	$html_style = 'margin-top: 25px!important';
}
?>
<html <?php language_attributes(); ?> class="no-js">
<!--<![endif]-->

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<title><?php mongabay_custom_title(); ?></title>
	<!-- <link href="//www.google-analytics.com" rel="dns-prefetch"> -->

	<!-- Google tag (gtag.js) -->
	<!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-12973256-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'UA-12973256-1');
	</script> -->

	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.png" type="image/x-icon" />
	<link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/img/icons/ico-s2.jpg">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/img/icons/ico-l2.jpg">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/img/icons/ico-s.jpg">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/img/icons/ico-l.jpg">
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet" />
	<meta name="referrer" content="always" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="description" content="<?php bloginfo('description'); ?>" />
	<meta property="fb:pages" content="24436227877" />
	<?php
	// if (is_home()) {
	// 	echo '<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />';
	// 	echo '<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>';
	// 	echo '<script>window.addEventListener("load", function(){window.cookieconsent.initialise({
	// "palette": {"popup": {"background": "#000"},"button": {"background": "#f1d600"}}})});</script>';
	// }
	?>
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<!-- container -->
	<header class="header" role="banner">
		<?php
		if (wp_is_mobile()) {
		} else { ?>
			<div class="container in-row space-between align-center ph--40 pv--20">
				<div class="branding">
					<a href="<?php echo home_url(); ?>" class="theme-light"><img src="<?php echo get_template_directory_uri(); ?>/img/logo/mongabay_logo_news_black.svg" /></a><a href="" class="theme-dark"><img src="<?php echo get_template_directory_uri(); ?>/img/logo/mongabay_logo_news_white.svg" /></a>
				</div>
				<div class="menu-container align-center">
					<ul class="main-menu nav-desktop">
						<li><a href="<?php echo home_url(); ?>/feature""><?php _e('Feature', 'mongabay'); ?></a></li>
						<li><a href=" <?php echo home_url(); ?>/videos""><?php _e('Videos', 'mongabay'); ?></a></li>
						<li><a href="<?php echo home_url(); ?>/podcasts""><?php _e('Podcasts', 'mongabay'); ?></a></li>
						<li><a href=" <?php echo home_url(); ?>/series""><?php _e('Series', 'mongabay'); ?></a></li>
						<li><a href="<?php echo home_url(); ?>/articles"><?php _e('Articles', 'mongabay'); ?></a></li>
						<li><a href="<?php echo home_url(); ?>/shorts""><?php _e('Shorts', 'mongabay'); ?></a></li>
					</ul>
					<a class=" theme--button primary simple md-hide" href=""><?php _e('Donate', 'mongabay'); ?></a>
							<a id="theme-switch" class="icon icon-cog"></a>
							<a id="site-search"><span class="icon icon-search"></span></a>
							<a id="secondary-menu"><span class="icon icon-menu"></span></a>
				</div>
				<div id="off-canvas">
					<div class="container in-column ph--40 pv--20 gap--20">
						<div class="global-nav gap--20">
							<span class="icon icon-globe"></span>
							<ul class="">
								<li><a href="" class="active">English</a></li>
								<li><a href="">Español (Spanish)</a></li>
								<li><a href="">Bahasa Indonesia (Indonesian)</a></li>
								<li><a href="">Brasil (Portuguese)</a></li>
								<li><a href="">India (Hindi)</a></li>
							</ul>
							<span class="icon icon-cancel"></span>
						</div>
						<ul class="main-menu nav-desktop off-canvas">
							<li><a href="<?php echo home_url(); ?>/videos""><?php _e('Videos', 'mongabay'); ?></a></li>
							<li><a href=" <?php echo home_url(); ?>/podcasts""><?php _e('Podcasts', 'mongabay'); ?></a></li>
							<li><a href="<?php echo home_url(); ?>/articles"><?php _e('Articles', 'mongabay'); ?></a></li>
							<li><a href="<?php echo home_url(); ?>/shorts""><?php _e('Short News', 'mongabay'); ?></a></li>
							<li><a href=" <?php echo home_url(); ?>/feature""><?php _e('Feature Stories', 'mongabay'); ?></a></li>
							<li><a href="">The Latest</a></li>
							<li><a href="">Explore All</a></li>
						</ul>
					</div>
				</div>
			</div>
		<?php } ?>
		<script>
			const isDarkMode = window.localStorage.getItem("mongabay-theme");

			if (isDarkMode) {
				document.querySelector("body").classList.add("dark-mode");
			}

			function brandingDisplay() {
				if (document.querySelector("body").classList.contains("dark-mode")) {
					window.localStorage.setItem("mongabay-theme", "dark-mode");
					document.querySelector(".branding .theme-light").style.display = "none";
					document.querySelector(".branding .theme-dark").style.display = "block";
				} else {
					window.localStorage.removeItem("mongabay-theme");
					document.querySelector(".branding .theme-light").style.display = "block";
					document.querySelector(".branding .theme-dark").style.display = "none";
				}
			}

			brandingDisplay();

			document.getElementById("theme-switch").addEventListener("click", (e) => {
				e.preventDefault;
				e.stopPropagation;
				document.querySelector("body").classList.toggle("dark-mode");
				brandingDisplay();
			});

			document.getElementById("secondary-menu").addEventListener("click", (e) => {
				e.preventDefault;
				e.stopPropagation;
				document.getElementById("off-canvas").classList.add("active");
			});

			document.querySelector(".icon-cancel").addEventListener("click", (e) => {
				e.preventDefault;
				e.stopPropagation;
				document.getElementById("off-canvas").classList.remove("active");
			});
		</script>
	</header>