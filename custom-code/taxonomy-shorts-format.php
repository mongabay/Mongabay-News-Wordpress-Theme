<?php
add_action('init', 'mongabay_tax_register_shorts_format', 0);

function mongabay_enqueue_short_article_alert_map_picker($hook_suffix)
{
  $screen = get_current_screen();
  if (!$screen || $screen->base !== 'post' || $screen->post_type !== 'short-article') {
    return;
  }

  $mapbox_token = mongabay_get_mapbox_public_token();
  $picker_script_path = get_template_directory() . '/js/lib/short-article-alert-map-picker.js';

  wp_enqueue_style('mapbox-gl-css', 'https://api.mapbox.com/mapbox-gl-js/v3.32.0/mapbox-gl.css', array(), '3.32.0');
  wp_enqueue_style('mongabay-fontello', get_template_directory_uri() . '/css/fontello.css', array(), '1.0.0');
  wp_enqueue_style('short-article-alert-map-picker', get_template_directory_uri() . '/css/short-article-alert-map-picker.css', array('mapbox-gl-css'), '1.0.0');
  wp_enqueue_script('mapbox-gl-js', 'https://api.mapbox.com/mapbox-gl-js/v3.32.0/mapbox-gl.js', array(), '3.32.0', true);
  wp_enqueue_script('short-article-alert-map-picker', get_template_directory_uri() . '/js/lib/short-article-alert-map-picker.js', array('jquery', 'mapbox-gl-js'), filemtime($picker_script_path), true);
  wp_localize_script('short-article-alert-map-picker', 'shortArticleAlertMapPickerConfig', array(
    'mapboxToken' => $mapbox_token,
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'stylesNonce' => wp_create_nonce('mongabay_mapbox_styles'),
    'mapUnavailable' => __('Mapbox is unavailable. Check the map token and try again.', 'mongabay'),
    'searchPlaceholder' => __('Search for a place', 'mongabay'),
    'searchLabel' => __('Search alert location', 'mongabay'),
    'noResults' => __('No places found.', 'mongabay'),
    'stylesPlaceholder' => __('Choose a Mapbox style', 'mongabay'),
    'stylesUnavailable' => __('Mapbox styles could not be loaded. You can still enter the style URL manually.', 'mongabay'),
  ));
}
add_action('admin_enqueue_scripts', 'mongabay_enqueue_short_article_alert_map_picker');

function mongabay_ajax_get_mapbox_styles()
{
  check_ajax_referer('mongabay_mapbox_styles', 'nonce');

  if (!current_user_can('edit_posts')) {
    wp_send_json_error(array('message' => __('You are not allowed to view Mapbox styles.', 'mongabay')), 403);
  }

  $token = defined('MAPBOX_STYLES_TOKEN')
    ? MAPBOX_STYLES_TOKEN
    : ($GLOBALS['MAPBOX_STYLES_TOKEN'] ?? '');

  if (empty($token)) {
    wp_send_json_error(array('message' => __('A server-side Mapbox secret token with styles:list scope must be configured as MAPBOX_STYLES_TOKEN.', 'mongabay')), 503);
  }

  $cache_key = 'mongabay_mapbox_styles_' . md5($token);
  $styles = get_transient($cache_key);
  if ($styles === false) {
    $response = wp_remote_get(add_query_arg(
      'access_token',
      $token,
      'https://api.mapbox.com/styles/v1/mongabay'
    ), array('timeout' => 10));

    if (is_wp_error($response)) {
      wp_send_json_error(array('message' => __('Could not connect to the Mapbox Styles API.', 'mongabay')), 502);
    }

    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code !== 200) {
      if ($response_code === 401) {
        $message = __('Mapbox rejected the styles token. Check that the server-side token is valid.', 'mongabay');
      } elseif ($response_code === 403) {
        $message = __('Mapbox denied access. The server-side token needs styles:list scope and access to the mongabay account.', 'mongabay');
      } elseif ($response_code === 429) {
        $message = __('Mapbox rate-limited the styles request. Try again later.', 'mongabay');
      } else {
        $message = sprintf(__('Mapbox Styles API returned HTTP %d.', 'mongabay'), $response_code);
      }

      $ajax_status = $response_code >= 400 && $response_code < 500 ? $response_code : 502;
      wp_send_json_error(array('message' => $message), $ajax_status);
    }

    $response_styles = json_decode(wp_remote_retrieve_body($response), true);
    if (!is_array($response_styles)) {
      wp_send_json_error(array('message' => __('Mapbox returned an invalid styles response.', 'mongabay')), 502);
    }

    $styles = array_values(array_filter(array_map(function ($style) {
      if (empty($style['id'])) {
        return null;
      }

      return array(
        'id' => sanitize_text_field($style['id']),
        'name' => sanitize_text_field($style['name'] ?? $style['id']),
      );
    }, $response_styles)));
    set_transient($cache_key, $styles, 12 * HOUR_IN_SECONDS);
  }

  $allowed_style_ids = array(
    'cmugswdmb004401qvdhv89ok0',
    'cmu5dbfs1003o01sa1cp63rrk',
    'cmv6jpgkt00u401s0bp824pko',
    'cmu5fb8iz003t01sg94d3e5lp',
  );
  $styles = array_values(array_filter($styles, function ($style) use ($allowed_style_ids) {
    return in_array($style['id'] ?? '', $allowed_style_ids, true);
  }));

  wp_send_json_success($styles);
}
add_action('wp_ajax_mongabay_get_mapbox_styles', 'mongabay_ajax_get_mapbox_styles');

function mongabay_tax_register_shorts_format()
{
  $labels = array(
    'name'              => _x('Shorts Format', 'taxonomy general name'),
    'singular_name'     => _x('Shorts Format', 'taxonomy singular name'),
    'search_items'      => __('Search Shorts Formats'),
    'popular_items'     => __('Popular Shorts Formats'),
    'all_items'         => __('All Shorts Formats'),
    'parent_item'       => NULL,
    'parent_item_colon' => NULL,
    'edit_item'         => __('Edit Shorts Format'),
    'update_item'       => __('Update Shorts Format'),
    'add_new_item'      => __('Add New Shorts Format'),
    'new_item_name'     => __('New Shorts Format Name'),
    'separate_items_with_commas' => __('Separate shorts formats with commas'),
    'add_or_remove_items'        => __('Add or remove shorts formats'),
    'choose_from_most_used'      => __('Choose from the most used shorts formats'),
    'not_found'                  => __('No shorts formats found.'),
    'menu_name'         => __('Shorts Formats'),
  );

  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array(
      'with_front' => true,
      'slug' => 'shorts-format'
    ),
    'show_in_rest'          => true,
    'rest_base'             => 'shorts-format',
    'rest_controller_class' => 'WP_REST_Terms_Controller',
    'show_in_graphql' => true,
    'graphql_single_name' => 'ShortsFormat',
    'graphql_plural_name' => 'ShortsFormats',
  );

  register_taxonomy('shorts_format', array('short-article'), $args);
}

add_action('init', function () {
  $terms = ['CONTENT', 'AUDIO', 'VIDEO'];

  foreach ($terms as $term) {
    if (!term_exists($term, 'shorts_format')) {
      wp_insert_term($term, 'shorts_format');
    }
  }
});

add_action('save_post', function ($post_id) {

  if (get_post_type($post_id) !== 'short-article') return;

  if (empty($_POST['tax_input']['shorts_format'])) {
    wp_set_object_terms($post_id, [], 'shorts_format');
    return;
  }

  // Flatten and sanitize
  $terms = array_map('intval', (array) $_POST['tax_input']['shorts_format']);

  // Remove the "0" placeholder WP adds
  $terms = array_filter($terms);

  // Keep ONLY the first selected checkbox
  $terms = array_slice($terms, 0, 1);

  wp_set_object_terms($post_id, $terms, 'shorts_format', false);
});


add_action('admin_footer', function () {
  $screen = get_current_screen();
  if ($screen->post_type !== 'short-article') return;
?>
  <script>
    jQuery(function($) {

      const FIELD_IDS = {
        "article-link": "#pods-form-ui-pods-meta-article-link",
        "video-link": "#pods-form-ui-pods-meta-video-link",
        "audio-link": "#pods-form-ui-pods-meta-audio-link",
      };

      const visibilityMap = {
        CONTENT: ["article-link"],
        VIDEO: ["video-link"],
        AUDIO: ["audio-link"],
      };

      const checkboxes = $('#shorts_format-all input[type=checkbox]');

      function hideAll() {
        Object.values(FIELD_IDS).forEach(selector => {
          const $row = $(selector).closest("tr.pods-field__container");
          if ($row.length) {
            $row.hide().attr("data-conditional-hidden", "1");
          }
        });
      }

      function getLabelText($checkbox) {
        return $checkbox.closest("label")
          .contents()
          .filter(function() {
            return this.nodeType === 3;
          }) // text nodes only
          .text()
          .trim()
          .toUpperCase();
      }

      function applyVisibilityFromSelection() {
        hideAll();

        const checked = checkboxes.filter(':checked');

        if (!checked.length) {
          $("#pods-form-ui-pods-meta-article-link").closest("tr.pods-field__container").show().removeAttr("data-conditional-hidden");
          return;
        }

        const fmt = getLabelText(checked.first());
        const allowedKeys = visibilityMap[fmt] || [];

        Object.entries(FIELD_IDS).forEach(([key, selector]) => {
          const $row = $(selector).closest("tr.pods-field__container");

          if (!$row.length) {
            return;
          };

          if (allowedKeys.includes(key)) {
            $row.show().removeAttr("data-conditional-hidden");
          }
        });
      }

      // Enforce single-selection and update fields
      checkboxes.on("change", function() {
        if ($(this).is(":checked")) {
          checkboxes.not(this).prop("checked", false);
        }
        applyVisibilityFromSelection();
      });

      setTimeout(applyVisibilityFromSelection, 50);

    });
  </script>

<?php
});

add_filter('shorts_format_edit_form_fields', '__return_false');
add_filter('shorts_format_add_form_fields', '__return_false');


add_action('pre_get_posts', function ($query) {
  if (is_admin() || ! $query->is_main_query()) {
    return;
  }

  if (!$query->is_tax()) {
    return;
  }

  $tax_obj    = get_queried_object();
  $taxonomy   = $tax_obj->taxonomy;
  $post_types = get_taxonomy($taxonomy)->object_type;

  if (empty($post_types)) {
    $post_types = ['post'];
  }

  if (is_string($post_types)) {
    $post_types = [$post_types];
  }

  if (!in_array('short-article', $post_types, true)) {
    return;
  }

  $shorts_format_terms = get_terms([
    'taxonomy'   => 'shorts_format',
    'fields'     => 'ids',
    'hide_empty' => false,
  ]);

  if (empty($shorts_format_terms)) {
    return;
  }

  $tax_query = (array) $query->get('tax_query');

  $tax_query[] = [
    'taxonomy' => 'shorts_format',
    'field'    => 'term_id',
    'terms'    => $shorts_format_terms,
    'operator' => 'NOT IN',
    'include_children' => false,
  ];

  $query->set('tax_query', $tax_query);
});
