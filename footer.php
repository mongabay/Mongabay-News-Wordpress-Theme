<footer class="footer" role="contentinfo">
	<div class="container ph--40 pv--40 full-width in-column gap--40">
		<div class="branding">
			<a href="<?php echo home_url(); ?>" class="theme-light"><img src="<?php echo get_template_directory_uri(); ?>/img/logo/mongabay_logo_news_black.svg" /></a><a href="" class="theme-dark"><img src="<?php echo get_template_directory_uri(); ?>/img/logo/mongabay_logo_news_white.svg" /></a>
		</div>
		<div class="grid--4 gap--40">
			<div class="section-title gap--16">
				<h4><?php _e('News formats', 'mongabay'); ?></h4>
				<div class="divider"></div>
				<ul class="footer-links">
					<li><a href=""><?php _e('Videos', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Podcasts', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Articles', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Series', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Short news', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Feature Stories', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('The Latest', 'mongabay'); ?></a></li>
				</ul>
			</div>
			<div class="section-title gap--16">
				<h4><?php _e('About', 'mongabay'); ?></h4>
				<div class="divider"></div>
				<ul class="footer-links">
					<li><a href=""><?php _e('About', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Contact', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Donate', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Newsletters', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Submissions', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Terms of Use', 'mongabay'); ?></a></li>
				</ul>
			</div>
			<div class="section-title gap--16">
				<h4><?php _e('External links', 'mongabay'); ?></h4>
				<div class="divider"></div>
				<ul class="footer-links">
					<li><a href=""><?php _e('Wild Madagascar', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Selva tropicales', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('For Kids', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Mongabay.org', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Tropical Forest Network', 'mongabay'); ?></a></li>
				</ul>
			</div>
			<div class="section-title gap--16">
				<h4><?php _e('Social media', 'mongabay'); ?></h4>
				<div class="divider"></div>
				<ul class="footer-links">
					<li><a href=""><?php _e('LinkedIn', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Instagram', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Youtube', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('X', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Facebook', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('RSS / XML', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Mastodon', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Android App', 'mongabay'); ?></a></li>
					<li><a href=""><?php _e('Apple News', 'mongabay'); ?></a></li>
				</ul>
			</div>
		</div>
		<div class="divider"></div>
		<div>
			<p class="copyright">
				© <?php echo date('Y'); ?> Copyright <?php bloginfo('name'); ?>
			</p>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>

</html>