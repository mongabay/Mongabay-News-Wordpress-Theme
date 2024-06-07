<?php
// Google Analytics integration

add_action('wp_head', 'mongabay_google_analytics');
function mongabay_google_analytics()
{

	$lines = array();

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

?>
	<!-- Global site tag (gtag.js) - Google Analytics -->

<?php
}
