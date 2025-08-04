<?php
// Google Analytics integration

add_action('wp_head', 'mongabay_google_analytics');
function mongabay_google_analytics()
{

	$lines = array();
	$g_tag_ids = array(
		20 => 'G-ZHFVHJ3CMZ',
		25 => '',
	);

	$site_g_tag_id = $g_tag_ids[get_current_blog_id()] ?? 'G-ZHFVHJ3CMZ';

	if (is_single()) {
		$args = array('orderby' => 'count', 'order' => 'DESC');
		$topics = wp_get_object_terms(get_the_ID(), 'topic', $args);
		$locations = wp_get_object_terms(get_the_ID(), 'location', $args);
		$bylines =  wp_get_object_terms(get_the_ID(), 'byline', $args);
		$serials =  wp_get_object_terms(get_the_ID(), 'serial', $args);
		$licenses =  wp_get_object_terms(get_the_ID(), 'license', $args);
		$editor = get_the_author();

		if (count($topics) > 0) {
			foreach ($topics as $topic) {
				if ($topic && property_exists($topic, 'slug')) {
					$topics_f[] = $topic->slug;
				}
			};
		}

		if (count($locations) > 0) {
			foreach ($locations as $location) {
				if ($location && property_exists($location, 'slug')) {
					$locations_f[] = $location->slug;
				};
			}
		};

		if (count($bylines) > 0) {
			foreach ($bylines as $byline) {
				if ($byline && property_exists($byline, 'slug')) {
					$bylines_f[] = $byline->slug;
				};
			};
		}

		if (count($serials) > 0) {
			foreach ($serials as $serial) {
				if ($serial && property_exists($serial, 'slug')) {
					$serials_f[] = $serial->slug;
				};
			};
		}

		if (!$licenses instanceof WP_Error) {
			foreach ($licenses as $license) {
				if ($license && property_exists($license, 'slug')) {
					$licenses_f[] = $license->slug;
				};
			};
		}

		if (isset($topics_f)) $lines[] = "'dimension1': '" . implode(' ', $topics_f) . " ', ";
		if (isset($locations_f)) $lines[] = "'dimension2': '" . implode(' ', $locations_f) . " ', ";
		if (isset($bylines_f)) $lines[] = "'dimension3': '" . implode(' ', $bylines_f) . " ', ";
		if (isset($serials_f)) $lines[] = "'dimension4': '" . implode(' ', $serials_f) . " ', ";
		if (isset($licenses_f)) $lines[] = "'dimension5': '" . implode(' ', $licenses_f) . " ', ";
		if (isset($editor)) $lines[] = "'dimension7': '" . $editor . " '";
	}
}

// Microsoft Clarity integration
add_action('wp_head', 'mongabay_ms_clarity');
function mongabay_ms_clarity()
{
	$site_id = get_current_blog_id();
	$clarity_ids = array(
		20 => 'hghawiq8qp',
		25 => 'hghbc1qyh5',
		26 => 'hhwnwlczpi',
		29 => 'hghc19lasq',
		30 => 'hghbl3h1bx',
		35 => 'hghbsvtt45',
	);

	$site_clarity_id = $clarity_ids[$site_id] ?? 'hghawiq8qp';

	if ($site_clarity_id) {
?>
		<script type="text/javascript">
			(function(c, l, a, r, i, t, y) {
				c[a] = c[a] || function() {
					(c[a].q = c[a].q || []).push(arguments)
				};
				t = l.createElement(r);
				t.async = 1;
				t.src = "https://www.clarity.ms/tag/" + i;
				y = l.getElementsByTagName(r)[0];
				y.parentNode.insertBefore(t, y);
			})(window, document, "clarity", "script", "<?php echo $site_clarity_id; ?>");
		</script>
	<?php
	}
}


// Clicky integration
add_action('wp_body_open', 'mongabay_clicky');

function mongabay_clicky()
{
	$site_id = get_current_blog_id();
	$clicky_ids = array(
		20 => '101412303',
		25 => '101413170',
		26 => '101413174',
		29 => '101413173',
		30 => '101413171',
		35 => '101413172',
	);

	$site_clicky_id = $clicky_ids[$site_id] ?? '101412303';

	if ($site_clicky_id) {
	?>
		<script async data-id="<?php echo $site_clicky_id; ?>" src="//static.getclicky.com/js"></script>
		<noscript>
			<p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/<?php echo $site_clicky_id; ?>ns.gif" /></p>
		</noscript>
<?php
	}
}
