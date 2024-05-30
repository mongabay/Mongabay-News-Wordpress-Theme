<!-- search -->
<?php //TODO replace with actual production url ?>
<form class="search" method="get" action="<?php echo (is_mirror_site(get_current_blog_id()) ? 'https://news.mongabaydev.wpengine.com': home_url()); ?>" role="search">
	<input class="search-input" type="search" name="s" placeholder="<?php _e( 'To search, type and hit enter.', 'mongabay' ); ?>"/>
</form>
<!-- /search -->
