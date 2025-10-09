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
	<link href="//www.google-analytics.com" rel="dns-prefetch">

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-12973256-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());
		gtag('config', 'UA-12973256-1');
	</script>
	<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" sizes="any">
	<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.svg" type="image/svg+xml">
	<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/manifest.webmanifest">
	<link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/img/icons/ico-s2.jpg">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/img/icons/ico-l2.jpg">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/img/icons/ico-s.jpg">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/img/icons/ico-l.jpg">
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<meta name="referrer" content="always" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="description" content="<?php bloginfo('description'); ?>" />
	<meta property="fb:pages" content="24436227877" />
	<?php
	if (is_home()) {
		echo '<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />';
		echo '<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>';
		echo '<script>window.addEventListener("load", function(){window.cookieconsent.initialise({
	"palette": {"popup": {"background": "#000"},"button": {"background": "#f1d600"}}})});</script>';
	}
	?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<header class="header sticky" role="banner">
		<div class="container in-row space-between align-center ph--40 pv--20">
			<div class="branding">
				<a href="<?php echo home_url(); ?>" class="theme-light"><img src="<?php echo get_template_directory_uri(); ?>/img/logo/mongabay_logo_news_black.svg" /></a><a href="<?php echo home_url(); ?>" class="theme-dark"><img src="<?php echo get_template_directory_uri(); ?>/img/logo/mongabay_logo_news_white.svg" /></a>
			</div>
			<div class="menu-container align-center">
				<ul class="main-menu nav-desktop">
					<li><a href="<?php echo home_url(); ?>/<?php echo get_menu_item('features', 'slug'); ?>" class="<?php echo is_page(get_menu_item('features', 'title')) ? 'active' : ''; ?>"><?php echo get_menu_item('features', 'title'); ?></a></li>
					<?php if (get_enabled_features('videos')) : ?><li><a href=" <?php echo home_url(); ?>/<?php echo get_menu_item('videos', 'slug'); ?>" class="<?php echo is_page(get_menu_item('videos', 'title')) ? 'active' : ''; ?>"><?php echo get_menu_item('videos', 'title'); ?></a></li><?php endif; ?>
					<?php if (get_enabled_features('podcasts')) : ?><li><a href="<?php echo home_url(); ?>/<?php echo get_menu_item('podcasts', 'slug'); ?>" class="<?php echo is_page(get_menu_item('podcasts', 'title')) ? 'active' : ''; ?>"><?php echo get_menu_item('podcasts', 'title'); ?></a></li><?php endif; ?>
					<?php if (get_enabled_features('specials')) : ?><li><a href=" <?php echo home_url(); ?>/<?php echo get_menu_item('specials', 'slug'); ?>" class="<?php echo (is_page(get_menu_item('specials', 'title')) || is_tax('serial')) ? 'active' : ''; ?>"><?php echo get_menu_item('specials', 'title'); ?></a></li><?php endif; ?>
					<?php if (get_enabled_features('latest')) : ?><li><a href=" <?php echo home_url(); ?>/<?php echo get_menu_item('latest', 'slug'); ?>" class="<?php echo (is_page(get_menu_item('latest', 'title'))) ? 'active' : ''; ?>"><?php echo get_menu_item('latest', 'title'); ?></a></li><?php endif; ?>
					<li><a href="<?php echo home_url(); ?>/<?php echo get_menu_item('articles', 'slug'); ?>" class="<?php echo is_page(get_menu_item('articles', 'title')) ? 'active' : ''; ?>"><?php echo get_menu_item('articles', 'title'); ?></a></li>
					<?php if (get_enabled_features('shorts')) : ?><li><a href="<?php echo home_url(); ?>/<?php echo get_menu_item('shorts', 'slug'); ?>" class="<?php echo is_page(get_menu_item('shorts', 'title')) ? 'active' : ''; ?>"><?php echo get_menu_item('shorts', 'title'); ?></a></li><?php endif; ?>
				</ul>
				<a class="theme--button donate simple md-hide" href="<?php echo get_enabled_features('donate') ? get_menu_item('donate', 'url') : get_subscribe_link_local(get_current_blog_id()); ?>"><?php echo (get_enabled_features('donate') ? __('Donate', 'mongabay') : __('Subscribe', 'mongabay')); ?></a>
				<a id="theme-switch" class="icon icon-mode"></a>
				<a id="site-search" href="<?php echo home_url(); ?>/?s="><span class="icon icon-search"></span></a>
				<a id="secondary-menu"><span class="icon icon-menu"></span></a>
			</div>
			<div id="off-canvas">
				<?php if (wp_is_mobile()) { ?>
					<span class="icon icon-cancel"></span>
				<?php } ?>
				<div class="container in-column ph--40 pv--20 gap--20 <?php echo !wp_is_mobile() ? 'full-height' : ''; ?>" style="justify-content: space-between">
					<div class="global-nav gap--20">
						<span class="icon icon-globe md-hide"></span>
						<ul class="global-languages">
							<li><a href="https://news.mongabay.com" class="<?php echo get_home_url() === 'https://news.mongabay.com' ? 'active' : ''; ?>">English</a></li>
							<li><a href="https://es.mongabay.com" class="<?php echo get_home_url() === 'https://es.mongabay.com' ? 'active' : ''; ?>">Español (Spanish)</a></li>
							<li><a href="https://fr.mongabay.com" class="<?php echo get_home_url() === 'https://fr.mongabay.com' ? 'active' : ''; ?>">Français (French)</a></li>
							<li><a href="https://www.mongabay.co.id" class="<?php echo get_home_url() === 'https://www.mongabay.co.id' ? 'active' : ''; ?>">Bahasa Indonesia (Indonesian)</a></li>
							<li><a href="https://brasil.mongabay.com" class="<?php echo get_home_url() === 'https://brasil.mongabay.com' ? 'active' : ''; ?>">Brasil (Portuguese)</a></li>
							<li><a href="https://india.mongabay.com" class="<?php echo get_home_url() === 'https://india.mongabay.com' ? 'active' : ''; ?>">India (English)</a></li>
							<li><a href="https://hindi.mongabay.com" class="<?php echo get_home_url() === 'https://hindi.mongabay.co.id' ? 'active' : ''; ?>">हिंदी (Hindi)</a></li>
						</ul>
						<?php if (!wp_is_mobile()) { ?>
							<span class="icon icon-cancel"></span>
						<?php } ?>
					</div>
					<ul class="main-menu nav-desktop off-canvas">
						<?php if (get_enabled_features('videos')) : ?><li><a href=" <?php echo home_url(); ?>/<?php echo get_menu_item('videos', 'slug'); ?>"><?php echo get_menu_item('videos', 'title'); ?></a></li><?php endif; ?>
						<?php if (get_enabled_features('podcasts')) : ?><li><a href=" <?php echo home_url(); ?>/<?php echo get_menu_item('podcasts', 'slug'); ?>"><?php echo get_menu_item('podcasts', 'title'); ?></a></li><?php endif; ?>
						<li><a href=" <?php echo home_url(); ?>/<?php echo get_menu_item('articles', 'slug'); ?>"><?php echo get_menu_item('articles', 'title'); ?></a></li>
						<?php if (get_enabled_features('shorts')) : ?><li><a href=" <?php echo home_url(); ?>/<?php echo get_menu_item('shortnews', 'slug'); ?>"><?php echo get_menu_item('shortnews', 'title'); ?></a></li><?php endif; ?>
						<li><a href=" <?php echo home_url(); ?>/<?php echo get_menu_item('featurestories', 'slug'); ?>"><?php echo get_menu_item('featurestories', 'title'); ?></a></li>
						<li><a href=" <?php echo home_url(); ?>/<?php echo get_menu_item('thelatest', 'slug'); ?>"><?php echo get_menu_item('thelatest', 'title'); ?></a></li>
						<li><a href=" <?php echo home_url(); ?>/<?php echo get_menu_item('exploreall', 'slug'); ?>"><?php echo get_menu_item('exploreall', 'title'); ?></a></li>
					</ul>
					<?php if (!wp_is_mobile()) { ?>
						<div class="footer gap--20 grid--5 pv--20">
							<ul class="footer-links">
								<li><a href="<?php echo get_menu_item('footerabout', 'url'); ?>"><?php echo get_menu_item('footerabout', 'title'); ?></a></li>
								<li><a href="<?php echo home_url(); ?>/<?php echo get_menu_item('footerteam', 'slug'); ?>"><?php echo get_menu_item('footerteam', 'title'); ?></a></li>
								<li><a href="<?php echo home_url(); ?>/<?php echo get_menu_item('footercontact', 'slug'); ?>"><?php echo get_menu_item('footercontact', 'title'); ?></a></li>
							</ul>
							<ul class="footer-links">
								<?php if (function_exists('get_donate_link') && get_enabled_features('donate')) { ?>
									<li><a href="<?php echo get_menu_item('donate', 'url'); ?>"><?php _e('Donate', 'mongabay'); ?></a></li>
								<?php } ?>
								<?php if (function_exists('get_subscribe_link')) { ?>
									<li><a href="<?php echo get_subscribe_link_local(get_current_blog_id()); ?>"><?php _e('Subscribe page', 'mongabay'); ?></a></li>
								<?php } ?>
								<li><a href="<?php echo home_url() . '/' . get_menu_item('submissions', 'slug'); ?>"><?php _e('Submissions', 'mongabay'); ?></a></li>
							</ul>
							<ul class="footer-links">
								<li><a href="https://www.mongabay.com/privacy"><?php _e('Privacy Policy', 'mongabay'); ?></a></li>
								<li><a href="https://www.mongabay.com/terms"><?php _e('Terms of Use', 'mongabay'); ?></a></li>
								<li><a href="https://www.mongabay.com/advertising"><?php _e('Advertising', 'mongabay'); ?></a></li>
							</ul>
							<ul class="footer-links">
								<li><a href="https://www.wildmadagascar.org/"><?php _e('Wild Madagascar', 'mongabay'); ?></a></li>
								<li><a href="https://kids.mongabay.com/"><?php _e('For Kids', 'mongabay'); ?></a></li>
								<li><a href="https://mongabay.org/"><?php _e('Mongabay.org', 'mongabay'); ?></a></li>
								<li><a href="https://reforestation.app/"><?php _e('Reforestation App', 'mongabay'); ?></a></li>
							</ul>
							<ul class="footer-links">
								<li><a href="https://www.planetaryhealthcheck.org/"><?php _e('Planetary Health Check', 'mongabay'); ?></a></li>
								<li><a href="https://www.conservationeffectiveness.org/"><?php _e('Conservation Effectiveness', 'mongabay'); ?></a></li>
								<li><a href="https://studio.mongabay.com/"><?php _e('Mongabay Data Studio', 'mongabay'); ?></a></li>
							</ul>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<script>
			const isDarkMode = window.localStorage.getItem("mongabay-theme");

			if (isDarkMode) {
				document.querySelector("body").classList.add("dark-mode");
			}

			function brandingDisplay() {
				if (document.querySelector("body").classList.contains("dark-mode")) {
					window.localStorage.setItem("mongabay-theme", "dark-mode");
					document.querySelectorAll(".branding .theme-light").forEach((el) => {
						el.style.display = "none";
					});
					document.querySelectorAll(".branding .theme-dark").forEach((el) => {
						el.style.display = "block";
					});
				} else {
					window.localStorage.removeItem("mongabay-theme");
					document.querySelectorAll(".branding .theme-light").forEach((el) => {
						el.style.display = "block";
					});
					document.querySelectorAll(".branding .theme-dark").forEach((el) => {
						el.style.display = "none";
					});
				}
			}

			brandingDisplay();

			document.getElementById("theme-switch").addEventListener("click", (e) => {
				e.preventDefault;
				e.stopPropagation;
				document.querySelector("body").classList.toggle("dark-mode");
				document.getElementById("theme-switch").classList.toggle("dark-mode");
				brandingDisplay();
			});

			document.getElementById("secondary-menu").addEventListener("click", (e) => {
				e.preventDefault;
				e.stopPropagation;
				document.getElementById("off-canvas").classList.add("active");
				document.querySelector("body").classList.add("no-scroll");
			});

			document.querySelector(".icon-cancel").addEventListener("click", (e) => {
				e.preventDefault;
				e.stopPropagation;
				document.getElementById("off-canvas").classList.remove("active");
				document.querySelector("body").classList.remove("no-scroll");
			});
		</script>
	</header>