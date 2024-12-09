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
					<li><a href="<?php echo home_url(); ?>/videos"><?php _e('Videos', 'mongabay'); ?></a></li>
					<?php if (get_enabled_features('podcasts')) : ?><li><a href="<?php echo home_url(); ?>/podcasts"><?php _e('Podcasts', 'mongabay'); ?></a></li><?php endif; ?>
					<li><a href="<?php echo home_url(); ?>/articles"><?php _e('Articles', 'mongabay'); ?></a></li>
					<li><a href="<?php echo home_url(); ?>/specials"><?php _e('Specials', 'mongabay'); ?></a></li>
					<?php if (get_enabled_features('shorts')) : ?><li><a href="<?php echo home_url(); ?>/shorts"><?php _e('Short news', 'mongabay'); ?></a></li><?php endif; ?>
					<li><a href="<?php echo home_url(); ?>/features"><?php _e('Feature Stories', 'mongabay'); ?></a></li>
					<li><a href="<?php echo home_url(); ?>/?s=&formats=post+videos+podcasts+shorts+specials"><?php _e('The Latest', 'mongabay'); ?></a></li>
				</ul>
			</div>
			<div>
				<div class="section-title gap--16 pv--8">
					<h4><?php _e('About', 'mongabay'); ?></h4>
					<div class="divider"></div>
				</div>
				<ul class="footer-links">
					<li><a href="https://news.mongabay.com/about/"><?php _e('About', 'mongabay'); ?></a></li>
					<li><a href="https://news.mongabay.com/contact/"><?php _e('Contact', 'mongabay'); ?></a></li>
					<?php if (get_enabled_features('donate')) : ?><li><a href="<?php echo get_donate_link(); ?>"><?php _e('Donate', 'mongabay'); ?></a></li><?php endif; ?>
					<li><a href="<?php echo get_subscribe_link(); ?>"><?php _e('Newsletters', 'mongabay'); ?></a></li>
					<li><a href="https://news.mongabay.com/submissions/"><?php _e('Submissions', 'mongabay'); ?></a></li>
					<li><a href="https://news.mongabay.com/terms/"><?php _e('Terms of Use', 'mongabay'); ?></a></li>
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
					<li><a href="https://tropicalforestnetwork.org/" target="_blank"><?php _e('Tropical Forest Network', 'mongabay'); ?></a></li>
				</ul>
			</div>
			<div>
				<div class="section-title gap--16 pv--8">
					<h4><?php _e('Social media', 'mongabay'); ?></h4>
					<div class="divider"></div>
				</div>
				<ul class="footer-links">
					<li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['linkedin']; ?>" target="_blank"><?php _e('LinkedIn', 'mongabay'); ?></a></li>
					<li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['instagram']; ?>" target="_blank"><?php _e('Instagram', 'mongabay'); ?></a></li>
					<li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['youtube']; ?>" target="_blank"><?php _e('Youtube', 'mongabay'); ?></a></li>
					<li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['x']; ?>" target="_blank"><?php _e('X', 'mongabay'); ?></a></li>
					<li><a href="<?php echo get_social_links(get_blog_details()->blog_id)['facebook']; ?>" target="_blank"><?php _e('Facebook', 'mongabay'); ?></a></li>
					<li><a href="<?php echo home_url(); ?>/feed"><?php _e('RSS / XML', 'mongabay'); ?></a></li>
					<li><a href="https://mastodon.green/@mongabay" target="_blank"><?php _e('Mastodon', 'mongabay'); ?></a></li>
					<li><a href="https://play.google.com/store/apps/details?id=com.newsmongabay&hl=en" target="_blank"><?php _e('Android App', 'mongabay'); ?></a></li>
					<li><a href="https://apple.news/T7BxfNfp4QAiuOPNsUcn04Q/" target="_blank"><?php _e('Apple News', 'mongabay'); ?></a></li>
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