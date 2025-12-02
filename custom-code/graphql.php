<?php
add_action('graphql_register_types', function () {
  register_graphql_field('NodeWithContentEditor', 'unencodedContent', [
    'type' => 'String',
    'resolve' => function ($post) {
      $content = get_post($post->databaseId)->post_content;
      return esc_html($content);
    }
  ]);
});

//Resolve some post custom meta values for GraphQL
add_action(
  'graphql_register_types',
  function () {
    register_graphql_enum_type('ShortArticleFormatEnum', [
      'description' => __('Different formats of a short article', 'wp-graphql'),
      'values'      => [
        'CONTENT' => [
          'value'       => 'content',
          'description' => __('A written content article', 'wp-graphql'),
        ],
        'VIDEO' => [
          'value'       => 'video',
          'description' => __('A video article', 'wp-graphql'),
        ],
        'AUDIO' => [
          'value'       => 'audio',
          'description' => __('An audio article', 'wp-graphql'),
        ],
      ],
    ]);

    register_graphql_field('Post', 'featuredAs', [
      'type' => 'String',
      'description' => __('If article is featured', 'wp-graphql'),
      'resolve' => function ($post) {
        $featured = get_post_meta($post->ID, 'featured_as', true);
        return !empty($featured) ? $featured : 'simple';
      }
    ]);

    register_graphql_field('Post', 'coordinates', [
      'type' => 'String',
      'description' => __('GPS coordinates', 'wp-graphql'),
      'resolve' => function ($post) {
        $gps_coordinates = get_post_meta($post->ID, 'coordinates', true);
        return !empty($gps_coordinates) ? $gps_coordinates : null;
      }
    ]);

    // Register bullet point fields for Post type
    foreach (range(1, 4) as $i) {
      register_graphql_field('Post', 'bulletPoint' . $i, [
        'type' => 'String',
        'description' => sprintf(__('Bulletpoint %d', 'wp-graphql'), $i),
        'resolve' => function ($post) use ($i) {
          $meta_key = 'mog_bullet_' . ($i - 1) . '_mog_bulletpoint';
          $bulletpoint = get_post_meta($post->ID, $meta_key, true);
          return !empty($bulletpoint) ? $bulletpoint : null;
        }
      ]);
    }

    register_graphql_field('ShortArticle', 'article_type', [
      'type' => 'ShortArticleFormatEnum',
      'description' => __('Short article type', 'wp-graphql'),
      'resolve' => function ($post) {
        $article_type = get_post_meta($post->ID, 'article_type', true);
        return !empty($article_type) ? $article_type : null;
      }
    ]);

    register_graphql_field('Podcast', 'podcast_embed', [
      'type' => 'String',
      'description' => __('Podcast embed code', 'wp-graphql'),
      'resolve' => function ($post) {
        $podcast_code = get_post_meta($post->ID, 'podcast_embed', true);
        return !empty($podcast_code) ? wp_strip_all_tags($podcast_code) : null;
      }
    ]);

    register_graphql_field('Video', 'video_source', [
      'type' => 'String',
      'description' => __('Video source', 'wp-graphql'),
      'resolve' => function ($post) {
        $video_source = get_post_meta($post->ID, 'video_source', true);
        return !empty($video_source) ? $video_source : null;
      }
    ]);

    register_graphql_field('ShortArticle', 'article_link', [
      'type' => 'String',
      'description' => __('Short article link', 'wp-graphql'),
      'resolve' => function ($post) {
        $article_link = get_post_meta($post->ID, 'article_link', true);
        return !empty($article_link) ? $article_link : null;
      }
    ]);

    register_graphql_field('ShortArticle', 'video_link', [
      'type' => 'String',
      'description' => __('Short article video link', 'wp-graphql'),
      'resolve' => function ($post) {
        $video_link = get_post_meta($post->ID, 'video_link', true);
        return !empty($video_link) ? $video_link : null;
      }
    ]);

    register_graphql_field('ShortArticle', 'audio_link', [
      'type' => 'String',
      'description' => __('Short article audio link', 'wp-graphql'),
      'resolve' => function ($post) {
        $audio_link = get_post_meta($post->ID, 'audio_link', true);
        return !empty($audio_link) ? $audio_link : null;
      }
    ]);

    register_graphql_field('ShortArticle', 'alert', [
      'type' => 'String',
      'description' => __('Short article as an alert', 'wp-graphql'),
      'resolve' => function ($post) {
        $alert = get_post_meta($post->ID, 'alert', true);
        return !empty($alert) ? $alert : null;
      }
    ]);

    register_graphql_field('ShortArticle', 'alertMapSource', [
      'type' => 'String',
      'description' => __('Short article alert map source', 'wp-graphql'),
      'resolve' => function ($post) {
        $map_source = get_post_meta($post->ID, 'alert_map_source', true);
        return !empty($map_source) ? $map_source : null;
      }
    ]);

    register_graphql_field('ShortArticle', 'alertMapCoordinates', [
      'type' => 'String',
      'description' => __('Short article alert map coordinates', 'wp-graphql'),
      'resolve' => function ($post) {
        $map_coordinates = get_post_meta($post->ID, 'alert_map_coordinates', true);
        return !empty($map_coordinates) ? $map_coordinates : null;
      }
    ]);

    register_graphql_field('ShortArticle', 'alertMapZoom', [
      'type' => 'String',
      'description' => __('Short article alert map zoom', 'wp-graphql'),
      'resolve' => function ($post) {
        $map_zoom = get_post_meta($post->ID, 'alert_map_zoom_level', true);
        return !empty($map_zoom) ? $map_zoom : null;
      }
    ]);

    register_graphql_field('ShortArticle', 'mapBoxStyle', [
      'type' => 'String',
      'description' => __('Mapbox styles URL', 'wp-graphql'),
      'resolve' => function ($post) {
        $styles_url = get_post_meta($post->ID, 'styles_url', true);
        return !empty($styles_url) ? $styles_url : null;
      }
    ]);

    register_graphql_field('Bylines', 'bylineCoverImage', [
      'type' => 'String',
      'description' => __('Byline cover image', 'wp-graphql'),
      'resolve' => function ($term) {
        $cover_image = get_term_meta($term->term_id, 'cover_image_url', true);
        return !empty($cover_image) ? $cover_image : null;
      }
    ]);
  }
);
