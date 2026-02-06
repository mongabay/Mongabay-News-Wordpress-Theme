<?php
add_action('init', 'mongabay_tax_register_shorts_format', 0);

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
