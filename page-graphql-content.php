<?php
    /* Template Name: Dump Content */
    get_header();
?>
<?php
	$id = 238444;
	$content = get_post($id);
	$trouble = $wpdb->get_results("SELECT post_content FROM $wpdb->posts WHERE ID = 238444");
?>
	<main role="main">
		<div class="row">
	        <div id="main" class="col-lg-8 single">
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                	<?php var_dump($trouble); ?>
                </article>
	        </div>
	</main>
</div>
<!-- /container -->
<?php get_footer(); ?>