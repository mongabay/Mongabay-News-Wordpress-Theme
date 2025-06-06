<footer class="footer" role="contentinfo">
	<div class="container ph--40 pv--40 in-column gap--40">
		<div class="branding">
			<a href="<?php echo home_url(); ?>" class="theme-light"><img src="<?php echo get_template_directory_uri(); ?>/img/logo/mongabay_logo_news_black.svg" /></a><a href="" class="theme-dark"><img src="<?php echo get_template_directory_uri(); ?>/img/logo/mongabay_logo_news_white.svg" /></a>
		</div>
		<div class="grid--4 gap--40">
			<div>
				<div class="section-title gap--16 pv--8">
					<h4><?php _e('News formats', 'mongabay'); ?></h4>
					<div class="divider"></div>
				</div>
				<ul class="footer-links">
					<li><a href="<?php echo home_url() . '/' . get_menu_item('videos', 'slug'); ?>"><?php echo get_menu_item('videos', 'title'); ?></a></li>
					<?php if (get_enabled_features('podcasts')) : ?><li><a href="<?php echo home_url() . '/' . get_menu_item('podcasts', 'slug'); ?>"><?php echo get_menu_item('podcasts', 'title'); ?></a></li><?php endif; ?>
					<li><a href="<?php echo home_url() . '/' . get_menu_item('articles', 'slug'); ?>"><?php echo get_menu_item('articles', 'title'); ?></a></li>
					<li><a href="<?php echo home_url() . '/' . get_menu_item('specials', 'slug'); ?>"><?php echo get_menu_item('specials', 'title'); ?></a></li>
					<?php if (get_enabled_features('shorts')) : ?><li><a href="<?php echo home_url() . '/' . get_menu_item('shorts', 'slug'); ?>"><?php echo get_menu_item('shorts', 'title'); ?></a></li><?php endif; ?>
					<li><a href="<?php echo home_url() . '/' . get_menu_item('features', 'slug'); ?>"><?php echo get_menu_item('features', 'title'); ?></a></li>
					<li><a href="<?php echo home_url() . '/' . get_menu_item('thelatest', 'slug') . date('Y'); ?>"><?php echo get_menu_item('thelatest', 'title'); ?></a></li>
				</ul>
			</div>
			<div>
				<div class="section-title gap--16 pv--8">
					<h4><?php _e('About', 'mongabay'); ?></h4>
					<div class="divider"></div>
				</div>
				<ul class="footer-links">
					<li><a href="<?php echo home_url() . '/' . get_menu_item('about', 'slug'); ?>"><?php echo get_menu_item('about', 'title'); ?></a></li>
					<li><a href="https://news.mongabay.com/contact/"><?php _e('Contact', 'mongabay'); ?></a></li>
					<?php if (get_enabled_features('donate')) : ?><li><a href="<?php echo get_donate_link(); ?>"><?php _e('Donate', 'mongabay'); ?></a></li><?php endif; ?>
					<li><a href="<?php echo 'https://mongabay.org/' . get_menu_item('impacts', 'slug'); ?>"><?php echo get_menu_item('impacts', 'title'); ?></a></li>
					<li><a href="<?php echo get_subscribe_link_local(get_current_blog_id()); ?>"><?php _e('Newsletters', 'mongabay'); ?></a></li>
					<li><a href="<?php echo home_url() . '/' . get_menu_item('submissions', 'slug'); ?>"><?php echo get_menu_item('submissions', 'title'); ?></a></li>
					<li><a href="<?php echo home_url() . '/' . get_menu_item('terms', 'slug'); ?>"><?php echo get_menu_item('terms', 'title'); ?></a></li>
				</ul>
			</div>
			<div>
				<div class="section-title gap--16 pv--8">
					<h4><?php _e('External links', 'mongabay'); ?></h4>
					<div class="divider"></div>
				</div>
				<ul class="footer-links">
					<li><a href="https://www.wildmadagascar.org/" target="_blank"><?php _e('Wild Madagascar', 'mongabay'); ?></a></li>
					<li><a href="https://selvastropicales.org/" target="_blank"><?php _e('Selva tropicales', 'mongabay'); ?></a></li>
					<li><a href="https://kids.mongabay.com/" target="_blank"><?php _e('For Kids', 'mongabay'); ?></a></li>
					<li><a href="https://www.mongabay.org/" target="_blank"><?php _e('Mongabay.org', 'mongabay'); ?></a></li>
					<li><a href="https://reforestation.app/" target="_blank"><?php _e('Reforestation App', 'mongabay'); ?></a></li>
					<li><a href="https://www.planetaryhealthcheck.org/" target="_blank"><?php _e('Planetary Health Check', 'mongabay'); ?></a></li>
					<li><a href="https://www.conservationeffectiveness.org/" target="_blank"><?php _e('Conservation Effectiveness', 'mongabay'); ?></a></li>
					<li><a href="https://studio.mongabay.com/" target="_blank"><?php _e('Mongabay Data Studio', 'mongabay'); ?></a></li>
				</ul>
			</div>
			<div>
				<div class="section-title gap--16 pv--8">
					<h4><?php _e('Social media', 'mongabay'); ?></h4>
					<div class="divider"></div>
				</div>
				<ul class="footer-links">
					<?php if (get_social_links(get_blog_details()->blog_id)['linkedin']) : ?><li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['linkedin']; ?>" target="_blank"><?php _e('LinkedIn', 'mongabay'); ?></a></li><?php endif; ?>
					<?php if (get_social_links(get_blog_details()->blog_id)['instagram']) : ?><li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['instagram']; ?>" target="_blank"><?php _e('Instagram', 'mongabay'); ?></a></li><?php endif; ?>
					<?php if (get_social_links(get_blog_details()->blog_id)['youtube']) : ?><li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['youtube']; ?>" target="_blank"><?php _e('Youtube', 'mongabay'); ?></a></li><?php endif; ?>
					<?php if (get_social_links(get_blog_details()->blog_id)['x']) : ?><li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['x']; ?>" target="_blank"><?php _e('X', 'mongabay'); ?></a></li><?php endif; ?>
					<?php if (get_social_links(get_blog_details()->blog_id)['facebook']) : ?><li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['facebook']; ?>" target="_blank"><?php _e('Facebook', 'mongabay'); ?></a></li><?php endif; ?>
					<?php if (get_social_links(get_blog_details()->blog_id)['tiktok']) : ?><li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['tiktok']; ?>" target="_blank"><?php _e('Tiktok', 'mongabay'); ?></a></li><?php endif; ?>
					<?php if (get_social_links(get_blog_details()->blog_id)['redit']) : ?><li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['redit']; ?>" target="_blank"><?php _e('Redit', 'mongabay'); ?></a></li><?php endif; ?>
					<?php if (get_social_links(get_blog_details()->blog_id)['bluesky']) : ?><li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['bluesky']; ?>" target="_blank"><?php _e('BlueSky', 'mongabay'); ?></a></li><?php endif; ?>
					<?php if (get_social_links(get_blog_details()->blog_id)['whatsapp']) : ?><li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['whatsapp']; ?>" target="_blank"><?php _e('Whatsapp', 'mongabay'); ?></a></li><?php endif; ?>
					<?php if (get_social_links(get_blog_details()->blog_id)['telegram']) : ?><li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['whatsapp']; ?>" target="_blank"><?php _e('Telegram', 'mongabay'); ?></a></li><?php endif; ?>
					<?php if (get_social_links(get_blog_details()->blog_id)['mastodon']) : ?><li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['mastodon']; ?>" target="_blank"><?php _e('Mastodon', 'mongabay'); ?></a></li><?php endif; ?>
					<?php if (get_social_links(get_blog_details()->blog_id)['android']) : ?><li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['android']; ?>" target="_blank"><?php _e('Android App', 'mongabay'); ?></a></li><?php endif; ?>
					<?php if (get_social_links(get_blog_details()->blog_id)['apple']) : ?><li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['apple']; ?>" target="_blank"><?php _e('Apple News', 'mongabay'); ?></a></li><?php endif; ?>
					<li><a href="<?php echo home_url(); ?>/feed"><?php _e('RSS / XML', 'mongabay'); ?></a></li>
				</ul>
			</div>
		</div>
		<div class="divider"></div>
		<div>
			<p class="copyright">
				© <?php echo date('Y'); ?> Copyright Conservation news. Mongabay is a U.S.-based non-profit conservation and environmental science news platform. Our EIN or tax ID is 45-3714703.
			</p>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>

</html>