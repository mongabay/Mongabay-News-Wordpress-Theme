<?php
/* Meta tags */
add_action('wp_head', 'mongabay_meta', 10);

function mongabay_meta()
{

	global $post;
	$short_identifier = sanitize_title((string) get_query_var('short'));
	$shared_short = '';
	if ($short_identifier !== '') {
		$shared_short = ctype_digit($short_identifier)
			? get_post(absint($short_identifier))
			: get_page_by_path($short_identifier, OBJECT, 'short-article');
	}

	if ($shared_short instanceof WP_Post && $shared_short->post_type === 'short-article' && $shared_short->post_status === 'publish') {
		$title = get_the_title($shared_short);
		$description = wp_strip_all_tags(get_the_excerpt($shared_short));
		if ($description === '') {
			$description = wp_trim_words(wp_strip_all_tags($shared_short->post_content), 30, '...');
		}
		$thumbnail_url = get_the_post_thumbnail_url($shared_short, 'large');
		$shorts_page = get_page_by_path(sanitize_title(__('shorts', 'mongabay')));
		$shorts_url = $shorts_page ? get_permalink($shorts_page) : home_url('/shorts/');
		$share_url = trailingslashit($shorts_url) . rawurlencode($shared_short->post_name) . '/';

		echo '<link rel="canonical" href="' . esc_url($share_url) . '" />' . "\n";
		echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
		echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
		echo '<meta property="og:site_name" content="Mongabay Environmental News" />' . "\n";
		echo '<meta property="og:url" content="' . esc_url($share_url) . '" />' . "\n";
		echo '<meta property="og:type" content="article" />' . "\n";
		echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
		echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
		echo '<meta name="twitter:site" content="@mongabay" />' . "\n";
		echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />' . "\n";
		echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />' . "\n";

		if ($thumbnail_url) {
			echo '<meta property="og:image" content="' . esc_url($thumbnail_url) . '" />' . "\n";
			echo '<meta name="twitter:image" content="' . esc_url($thumbnail_url) . '" />' . "\n";
		}

		return;
	}

	// article
	if (is_single() && !is_front_page() && !is_home()) {

		$byline_term = get_the_terms($post->ID, 'byline');
		$byline_term_id = null;
		$byline_twitter = null;

		if (!empty($byline_term)) {
			$byline_term_id = $byline_term[0]->term_id;
			$byline_twitter = get_term_meta($byline_term_id, 'authors_twitter_account', false);
		}

		if (!empty($byline_twitter) && isset($byline_twitter[0])) {
			$byline_twitter = $byline_twitter[0];
		}

		echo '<meta name="description" content="' . get_the_excerpt($post->ID) . '" />' . "\n";
		echo '<meta name="Tags" content="Mongabay, Mongabay Environmental News, Environmental News, Conservation News" />' . "\n";
		echo '<meta property="keywords" content="Mongabay, Mongabay Environmental News, Environmental News, Conservation News" />' . "\n";
		echo '<meta name="robots" content="index, follow" />' . "\n";
		echo '<link rel="publisher" href="https://plus.google.com/+Mongabay/" />' . "\n";
		echo '<meta property="article:publisher" content="https://www.facebook.com/mongabay/"/>' . "\n";
		echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '"/>' . "\n";
		echo '<meta property="og:site_name" content="Mongabay Environmental News"/>' . "\n";
		echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '"/>' . "\n";
		echo '<meta property="og:type" content="article" />' . "\n";

		$thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');

		if (!empty($thumbnail_src[0])) {
			echo '<meta property="og:image" content="' . esc_url($thumbnail_src[0]) . '"/>' . "\n";
		}

		echo '<meta property="og:description" content="' . get_the_excerpt($post->ID) . '"/>' . "\n";

		$format = 'c';
		echo '<meta property="article:published_time" content="' . get_the_date($format) . '" />' . "\n";
		echo '<meta property="article:modified_time" content="' . get_the_modified_date($format) . '" />' . "\n";
		echo '<meta property="article:section" content="Environmental news" />' . "\n";
		echo '<meta property="article:tag" content="Mongabay, Mongabay Environmental News, Environmental News, Conservation News" />' . "\n";
		echo '<meta property="fb:admins" content="139042016164873" />' . "\n";
		echo '<meta property="og:locale" content="' . get_locale() . '" />' . "\n";
		echo '<meta property="fb:app_id" content="1559969950974398"/>' . "\n";
		echo '<meta name="twitter:card" content="summary_large_image"/>' . "\n";
		echo '<meta name="twitter:site" content="@mongabay"/>';
		echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '"/>' . "\n";
		echo '<meta name="twitter:description" content="' . get_the_excerpt($post->ID) . '"/>' . "\n";

		if (!empty($thumbnail_src[0])) {
			echo '<meta name="twitter:image" content="' . esc_url($thumbnail_src[0]) . '"/>' . "\n";
		}
		if (!empty($byline_twitter)) {
			echo '<meta name="twitter:creator" content="' . $byline_twitter . '"/>' . "\n";
		} else {
			$site_id = get_current_blog_id();
			$twitter_account = '';
			switch ($site_id) {
				case '25':
					$twitter_account = 'mongabaylatam';
					break;
				case '26':
					$twitter_account = 'mongabay_fr';
					break;
				case '28':
					$twitter_account = 'mongabay_japan';
					break;
				case '29':
					$twitter_account = 'mongabay_brasil';
					break;
				case '30':
					$twitter_account = 'mongabayindia';
					break;
				default:
					$twitter_account = 'Mongabay';
					break;
			};
			echo '<meta name="twitter:creator" content="@' . $twitter_account . '"/>' . "\n";
		}

		$coords = get_post_meta(get_the_ID(), 'coordinates', true);

		if (!empty($coords['lat']) && !empty($coords['lng'])) {
			$coords['lat'] = intval($coords['lat']);
			$coords['lng'] = intval($coords['lng']);

			echo '<meta name="ICBM" content="' . $coords['lat'] . ', ' . $coords['lng'] . '" />' . "\n";
			echo '<meta name="geo.position" content="' . $coords['lat'] . ';' . $coords['lng'] . '" />' . "\n";

			echo '<meta property="latitude" content="' . $coords['lat'] . '" />' . "\n";
			echo '<meta property="longitude" content="' . $coords['lat'] . '" />' . "\n";
		}
	} else {
		echo "\n" . '<meta name="description" content="' . __('Mongabay seeks to raise interest in and appreciation of wild lands and wildlife, while examining the impact of emerging trends in climate, technology, economics,and finance on conservation and development.', 'mongabay') . '" />' . "\n";
		echo '<meta name="Tags" content="Mongabay, Mongabay Environmental News, Environmental News, Conservation News" />' . "\n";
		echo '<meta property="keywords" content="Mongabay, Mongabay Environmental News, Environmental News, Conservation News" />' . "\n";
		echo '<meta name="robots" content="index, follow" />' . "\n";
		echo '<link rel="publisher" href="https://plus.google.com/+Mongabay/" />' . "\n";
		echo '<meta property="article:publisher" content="https://www.facebook.com/mongabay/"/>' . "\n";
	}
}
