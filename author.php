<?php

/**
 * The template for displaying author's page
 * as an archive
 */

defined('ABSPATH') || exit; // Exit if accessed directly

get_header();

$author_biography = get_the_author_meta('description');
$author_x = get_the_author_meta('twitter');
$author_fb = get_the_author_meta('facebook');
$author_web = get_the_author_meta('website');
$author_in = get_the_author_meta('linkedin');
$author_insta = get_the_author_meta('instagram');
$author_email = get_the_author_meta('email');
$author_username = get_the_author_meta('user_login');
?>

<div class="container in-column in-row ph--40 pv--40 gap--80">
	<div class="col--20">
		<div class="byline--overview byline-avatar">
			<?php echo get_avatar(get_the_author_meta('ID')); ?>
		</div>
	</div>
	<div class="container in-column byline--info col--80 gap--20">
		<h1 class=""><?php the_author(); ?></h1>
		<?php if ($author_biography) { ?>
			<div class="section-title gap--16">
				<h4><?php _e('About', 'mongabay'); ?></h4>
				<div class="divider"></div>
			</div>
			<p><?php echo wpautop($author_biography); ?></p>
		<?php } ?>
		<div class="container in-row gap--20">
			<?php if ($author_email) { ?>
				<a href="mailto:<?php echo $author_email; ?>">Email address</a>
			<?php } ?>
			<?php if ($author_web) { ?>
				<div class="col--50">
					<a href="<?php echo $author_web; ?>" target="_blank">Website</a>
				</div>
			<?php } ?>
		</div>
		<div class="container in-row gap--20">
			<?php if ($author_x) { ?>
				<div class="col--50">
					<a href="https://x.com/@<?php echo $author_x; ?>" target="_blank">X</a>
				</div>
			<?php } ?>
			<?php if ($author_fb) { ?>
				<div class="col--50">
					<a href="<?php echo $author_fb; ?>" target="_blank">Facebook</a>
				</div>
			<?php } ?>
			<?php if ($author_insta) { ?>
				<div class="col--50">
					<a href="<?php echo $author_insta; ?>" target="_blank">Instagram</a>
				</div>
			<?php } ?>
			<?php if ($author_in) { ?>
				<div class="col--50">
					<a href="<?php echo $author_in; ?>" target="_blank">Linkedin</a>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="container in-column ph--40 pv--40">
	<?php if (have_posts()) { ?>
		<?php
		//get query object
		global $wp_query;
		$total = $wp_query->found_posts;
		?>

		<div id="results">
			<div id="results-header">
				<div id="results-header-left">
					<a href="<?php echo home_url() . '/author/' . $author_username . '/feed'; ?>" target="_blank" id="results-rss" class="theme--button primary simple">RSS</a>
					<div id="results-total"><?php echo $total; ?> <?php _e($total > 1 ? 'stories' : 'story', 'mongabay'); ?></div>
				</div>
				<?php if (!wp_is_mobile()) { ?>
					<div id="results-view-toggles">
						<button id="list-view">L</button>
						<button id="grid-view" class="active">G</button>
					</div>
				<?php } ?>
			</div>
			<div class="container grid--4 gap--20 grid-view">
				<?php
				// Start the Loop
				while (have_posts()) :
					the_post();
				?>
					<div class="article--container pv--8">
						<a href="<?php the_permalink(); ?>">
							<?php if (has_post_thumbnail()) { ?>
								<div class="featured-image">
									<?php echo !wp_is_mobile() ? get_icon(get_the_ID()) : ''; ?>
									<?php the_post_thumbnail('medium') ?>
								</div>
							<?php }; ?>
							<?php if (wp_is_mobile()) { ?>
								<div class="wrapper">
								<?php } ?>
								<div class="title headline">
									<h3><?php the_title(); ?></h3>
								</div>
								<div class="post-meta pv--8">
									<?php echo wp_is_mobile() ? get_icon(get_the_ID()) : ''; ?>
									<span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
									<span class="date"><?php the_time('j M Y'); ?></span>
								</div>
								<?php if (wp_is_mobile()) { ?>
								</div>
							<?php } ?>
						</a>
					</div>
				<?php endwhile; ?>
			</div>
			<script>
				const gridViewButton = document.getElementById("grid-view");
				const listViewButton = document.getElementById("list-view");
				const resultsList = document.getElementById("post-results");

				gridViewButton.addEventListener("click", () => {
					resultsList.classList.remove("list-view");
					resultsList.classList.add("grid-view");
					gridViewButton.classList.add("active");
					listViewButton.classList.remove("active");
				});

				listViewButton.addEventListener("click", () => {
					resultsList.classList.remove("grid-view");
					resultsList.classList.add("list-view");
					listViewButton.classList.add("active");
					gridViewButton.classList.remove("active");
				});
			</script>
		</div>
		<div class="pagination container pv--40 centered gap--20">
			<?php mongabay_pagination(); ?>
		</div>
	<?php } else {
		_e('No results found', 'mongabay');
	}
	?>
</div>
<?php inspiration_banner(); ?>
<?php get_footer(); ?>