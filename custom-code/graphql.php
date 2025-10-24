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
        $post_id = isset($post->databaseId) ? $post->databaseId : $post->ID;
        $article_type = get_post_meta($post_id, 'article_type', true);
        return !empty($article_type) ? $article_type : null;
      }
    ]);

    register_graphql_field('Podcast', 'podcast_embed', [
      'type' => 'String',
      'description' => __('Podcast embed code', 'wp-graphql'),
      'resolve' => function ($post) {
        $podcast_code = get_post_meta($post->ID, 'podcast_embed', true);
        return !empty($podcast_code) ? $podcast_code : null;
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
        $post_id = isset($post->databaseId) ? $post->databaseId : $post->ID;
        $article_link = get_post_meta($post_id, 'article_link', true);
        return !empty($article_link) ? $article_link : null;
      }
    ]);

    register_graphql_field('ShortArticle', 'video_link', [
      'type' => 'String',
      'description' => __('Short article video link', 'wp-graphql'),
      'resolve' => function ($post) {
        $post_id = isset($post->databaseId) ? $post->databaseId : $post->ID;
        $video_link = get_post_meta($post_id, 'video_link', true);
        return !empty($video_link) ? $video_link : null;
      }
    ]);

    register_graphql_field('ShortArticle', 'audio_link', [
      'type' => 'String',
      'description' => __('Short article audio link', 'wp-graphql'),
      'resolve' => function ($post) {
        $post_id = isset($post->databaseId) ? $post->databaseId : $post->ID;
        $audio_link = get_post_meta($post_id, 'audio_link', true);
        return !empty($audio_link) ? $audio_link : null;
      }
    ]);

    register_graphql_field('ShortArticle', 'alert', [
      'type' => 'String',
      'description' => __('Short article as an alert', 'wp-graphql'),
      'resolve' => function ($post) {
        $post_id = isset($post->databaseId) ? $post->databaseId : $post->ID;
        $alert = get_post_meta($post_id, 'alert', true);
        // Return the value even if it's "0" - only return null if truly empty
        return ($alert !== '' && $alert !== false && $alert !== null) ? $alert : null;
      }
    ]);

    register_graphql_field('ShortArticle', 'alertMapSource', [
      'type' => 'String',
      'description' => __('Short article alert map source', 'wp-graphql'),
      'resolve' => function ($post) {
        $post_id = isset($post->databaseId) ? $post->databaseId : $post->ID;
        $map_source = get_post_meta($post_id, 'alert_map_source', true);
        return !empty($map_source) ? $map_source : null;
      }
    ]);

    register_graphql_field('ShortArticle', 'alertMapCoordinates', [
      'type' => 'String',
      'description' => __('Short article alert map coordinates', 'wp-graphql'),
      'resolve' => function ($post) {
        $post_id = isset($post->databaseId) ? $post->databaseId : $post->ID;
        $map_coordinates = get_post_meta($post_id, 'alert_map_coordinates', true);
        return !empty($map_coordinates) ? $map_coordinates : null;
      }
    ]);

    register_graphql_field('ShortArticle', 'alertMapZoom', [
      'type' => 'String',
      'description' => __('Short article alert map zoom', 'wp-graphql'),
      'resolve' => function ($post) {
        $post_id = isset($post->databaseId) ? $post->databaseId : $post->ID;
        $map_zoom = get_post_meta($post_id, 'alert_map_zoom_level', true);
        return !empty($map_zoom) ? $map_zoom : null;
      }
    ]);
  }
);

// Register a custom unified content query with advanced filtering
add_action('graphql_register_types', function () {
  // Register an enum for content type filtering
  register_graphql_enum_type('MongabayContentTypeEnum', [
    'description' => __('Content types available for filtering', 'wp-graphql'),
    'values' => [
      'POST' => [
        'value' => 'post',
        'description' => __('Regular posts/articles', 'wp-graphql'),
      ],
      'VIDEO' => [
        'value' => 'videos',
        'description' => __('Video content', 'wp-graphql'),
      ],
      'PODCAST' => [
        'value' => 'podcasts',
        'description' => __('Podcast content', 'wp-graphql'),
      ],
      'SHORT_ARTICLE' => [
        'value' => 'short-article',
        'description' => __('Short articles/news', 'wp-graphql'),
      ],
      'CUSTOM_STORY' => [
        'value' => 'custom-story',
        'description' => __('Custom stories', 'wp-graphql'),
      ],
      'SPECIALS' => [
        'value' => 'specials',
        'description' => __('Special issues', 'wp-graphql'),
      ],
    ],
  ]);

  // Register enum for short article types (matching your existing enum)
  register_graphql_enum_type('ShortArticleTypeFilterEnum', [
    'description' => __('Filter short articles by type', 'wp-graphql'),
    'values' => [
      'CONTENT' => [
        'value' => 'content',
        'description' => __('Written content short articles', 'wp-graphql'),
      ],
      'VIDEO' => [
        'value' => 'video',
        'description' => __('Video short articles', 'wp-graphql'),
      ],
      'AUDIO' => [
        'value' => 'audio',
        'description' => __('Audio short articles', 'wp-graphql'),
      ],
      'ALERT' => [
        'value' => 'alert',
        'description' => __('Alert short articles (content with alert=1)', 'wp-graphql'),
      ],
    ],
  ]);

  // Register object type for pagination info
  register_graphql_object_type('AllContentPageInfo', [
    'description' => __('Pagination information for allContent query', 'wp-graphql'),
    'fields' => [
      'total' => [
        'type' => 'Int',
        'description' => __('Total number of items', 'wp-graphql'),
      ],
      'offset' => [
        'type' => 'Int',
        'description' => __('Current offset', 'wp-graphql'),
      ],
      'hasMore' => [
        'type' => 'Boolean',
        'description' => __('Whether there are more items available', 'wp-graphql'),
      ],
      'totalPages' => [
        'type' => 'Int',
        'description' => __('Total number of pages', 'wp-graphql'),
      ],
      'currentPage' => [
        'type' => 'Int',
        'description' => __('Current page number', 'wp-graphql'),
      ],
    ],
  ]);

  // Register the response object type
  register_graphql_object_type('AllContentResponse', [
    'description' => __('Response for allContent query with pagination', 'wp-graphql'),
    'fields' => [
      'nodes' => [
        'type' => ['list_of' => 'ContentNode'],
        'description' => __('The content nodes', 'wp-graphql'),
      ],
      'pageInfo' => [
        'type' => 'AllContentPageInfo',
        'description' => __('Pagination information', 'wp-graphql'),
      ],
    ],
  ]);

  // Register the unified content query field
  register_graphql_field('RootQuery', 'allContent', [
    'type' => 'AllContentResponse',
    'description' => __('Query all content types with advanced filtering and pagination', 'wp-graphql'),
    'args' => [
      'first' => [
        'type' => 'Int',
        'description' => __('Number of items to return', 'wp-graphql'),
        'defaultValue' => 10,
      ],
      'offset' => [
        'type' => 'Int',
        'description' => __('Number of items to skip for pagination', 'wp-graphql'),
        'defaultValue' => 0,
      ],
      'contentTypes' => [
        'type' => ['list_of' => 'MongabayContentTypeEnum'],
        'description' => __('Filter by content types (if not provided, returns all types)', 'wp-graphql'),
      ],
      'shortArticleTypes' => [
        'type' => ['list_of' => 'ShortArticleTypeFilterEnum'],
        'description' => __('Filter short articles by their type (content, video, audio). Only applies when SHORT_ARTICLE is in contentTypes', 'wp-graphql'),
      ],
      'excludeShortArticleTypes' => [
        'type' => ['list_of' => 'ShortArticleTypeFilterEnum'],
        'description' => __('Exclude specific short article types. Only applies when SHORT_ARTICLE is in contentTypes', 'wp-graphql'),
      ],
      'includeAlerts' => [
        'type' => 'Boolean',
        'description' => __('Include only short articles marked as alerts', 'wp-graphql'),
      ],
      'excludeAlerts' => [
        'type' => 'Boolean',
        'description' => __('Exclude short articles marked as alerts', 'wp-graphql'),
      ],
      'search' => [
        'type' => 'String',
        'description' => __('Search term to filter content', 'wp-graphql'),
      ],
      'topicSlug' => [
        'type' => 'String',
        'description' => __('Filter by topic taxonomy slug', 'wp-graphql'),
      ],
      'topicSlugs' => [
        'type' => ['list_of' => 'String'],
        'description' => __('Filter by multiple topic taxonomy slugs (OR relation)', 'wp-graphql'),
      ],
      'locationSlug' => [
        'type' => 'String',
        'description' => __('Filter by location taxonomy slug', 'wp-graphql'),
      ],
      'locationSlugs' => [
        'type' => ['list_of' => 'String'],
        'description' => __('Filter by multiple location taxonomy slugs (OR relation)', 'wp-graphql'),
      ],
      'authorId' => [
        'type' => 'Int',
        'description' => __('Filter by author ID', 'wp-graphql'),
      ],
      'dateQuery' => [
        'type' => 'String',
        'description' => __('Filter by date range (format: YYYY-MM-DD)', 'wp-graphql'),
      ],
      'orderBy' => [
        'type' => 'String',
        'description' => __('Order by field (date, title, modified)', 'wp-graphql'),
        'defaultValue' => 'date',
      ],
      'order' => [
        'type' => 'String',
        'description' => __('Order direction (ASC, DESC)', 'wp-graphql'),
        'defaultValue' => 'DESC',
      ],
    ],
    'resolve' => function($source, $args, $context, $info) {
      // Set default content types to all if not specified
      $post_types = isset($args['contentTypes']) && !empty($args['contentTypes']) 
        ? $args['contentTypes'] 
        : ['post', 'videos', 'podcasts', 'short-article', 'custom-story', 'specials'];

      // Build WP_Query args
      $query_args = [
        'post_type' => $post_types,
        'posts_per_page' => isset($args['first']) ? intval($args['first']) : 10,
        'offset' => isset($args['offset']) ? intval($args['offset']) : 0,
        'post_status' => 'publish',
        'orderby' => isset($args['orderBy']) ? sanitize_text_field($args['orderBy']) : 'date',
        'order' => isset($args['order']) ? sanitize_text_field($args['order']) : 'DESC',
      ];

      // Add author filter
      if (isset($args['authorId']) && !empty($args['authorId'])) {
        $query_args['author'] = intval($args['authorId']);
      }

      // Add search if provided
      if (isset($args['search']) && !empty($args['search'])) {
        $query_args['s'] = sanitize_text_field($args['search']);
      }

      // Add taxonomy filters
      $tax_query = [];
      
      // Handle single or multiple topics
      if (isset($args['topicSlug']) && !empty($args['topicSlug'])) {
        $tax_query[] = [
          'taxonomy' => 'topic',
          'field' => 'slug',
          'terms' => sanitize_text_field($args['topicSlug']),
        ];
      } elseif (isset($args['topicSlugs']) && !empty($args['topicSlugs'])) {
        $tax_query[] = [
          'taxonomy' => 'topic',
          'field' => 'slug',
          'terms' => array_map('sanitize_text_field', $args['topicSlugs']),
          'operator' => 'IN',
        ];
      }

      // Handle single or multiple locations
      if (isset($args['locationSlug']) && !empty($args['locationSlug'])) {
        $tax_query[] = [
          'taxonomy' => 'location',
          'field' => 'slug',
          'terms' => sanitize_text_field($args['locationSlug']),
        ];
      } elseif (isset($args['locationSlugs']) && !empty($args['locationSlugs'])) {
        $tax_query[] = [
          'taxonomy' => 'location',
          'field' => 'slug',
          'terms' => array_map('sanitize_text_field', $args['locationSlugs']),
          'operator' => 'IN',
        ];
      }

      if (!empty($tax_query)) {
        if (count($tax_query) > 1) {
          $tax_query['relation'] = 'AND';
        }
        $query_args['tax_query'] = $tax_query;
      }

      // Handle short article type filtering with meta query
      $meta_query = [];
      $has_short_article = in_array('short-article', $post_types);

      if ($has_short_article) {
        // Handle ALERT type specially - it's a content type with alert=1
        $has_alert_type = false;
        $exclude_alert_type = false;
        $filter_types = [];
        $exclude_types = [];

        if (isset($args['shortArticleTypes']) && !empty($args['shortArticleTypes'])) {
          foreach ($args['shortArticleTypes'] as $type) {
            if ($type === 'alert') {
              $has_alert_type = true;
            } else {
              $filter_types[] = sanitize_text_field($type);
            }
          }
        }

        if (isset($args['excludeShortArticleTypes']) && !empty($args['excludeShortArticleTypes'])) {
          foreach ($args['excludeShortArticleTypes'] as $type) {
            if ($type === 'alert') {
              $exclude_alert_type = true;
            } else {
              $exclude_types[] = sanitize_text_field($type);
            }
          }
        }

        // Build meta query based on filter combinations
        if ($has_alert_type) {
          // User wants ALERT type specifically
          if (!empty($filter_types)) {
            // ALERT + other types (e.g., [ALERT, CONTENT])
            $meta_query[] = [
              'relation' => 'OR',
              [
                'relation' => 'AND',
                [
                  'key' => 'article_type',
                  'value' => $filter_types,
                  'compare' => 'IN',
                ],
                [
                  'key' => 'alert',
                  'value' => '1',
                  'compare' => '=',
                ],
              ],
              [
                'key' => 'alert',
                'value' => '1',
                'compare' => '=',
              ],
            ];
          } else {
            // Only ALERT type
            $meta_query[] = [
              'key' => 'alert',
              'value' => '1',
              'compare' => '=',
            ];
          }
        } else {
          // No ALERT type in filter
          // Filter by specific short article types (include)
          if (!empty($filter_types)) {
            $meta_query[] = [
              'key' => 'article_type',
              'value' => $filter_types,
              'compare' => 'IN',
            ];
          }

          // Exclude specific short article types
          if (!empty($exclude_types)) {
            $meta_query[] = [
              'key' => 'article_type',
              'value' => $exclude_types,
              'compare' => 'NOT IN',
            ];
          }

          // Exclude ALERT type if specified
          if ($exclude_alert_type) {
            $meta_query[] = [
              'relation' => 'OR',
              [
                'key' => 'alert',
                'compare' => 'NOT EXISTS',
              ],
              [
                'key' => 'alert',
                'value' => '1',
                'compare' => '!=',
              ],
            ];
          }

          // Filter by alerts (backward compatibility)
          if (isset($args['includeAlerts']) && $args['includeAlerts'] === true) {
            $meta_query[] = [
              'key' => 'alert',
              'value' => '1',
              'compare' => '=',
            ];
          }

          // Exclude alerts (backward compatibility)
          if (isset($args['excludeAlerts']) && $args['excludeAlerts'] === true) {
            $meta_query[] = [
              'relation' => 'OR',
              [
                'key' => 'alert',
                'compare' => 'NOT EXISTS',
              ],
              [
                'key' => 'alert',
                'value' => '1',
                'compare' => '!=',
              ],
            ];
          }
        }
      }

      if (!empty($meta_query)) {
        if (count($meta_query) > 1) {
          $meta_query['relation'] = 'AND';
        }
        $query_args['meta_query'] = $meta_query;
      }

      // Execute main query (WP_Query automatically calculates found_posts)
      $query = new WP_Query($query_args);
      $posts = [];

      if ($query->have_posts()) {
        while ($query->have_posts()) {
          $query->the_post();
          $posts[] = $context->get_loader('post')->load_deferred(get_the_ID());
        }
      }

      wp_reset_postdata();

      // Get total from main query (no extra query needed!)
      $total_items = $query->found_posts;

      // Calculate pagination info
      $first = $query_args['posts_per_page'];
      $offset = $query_args['offset'];
      $has_more = ($offset + $first) < $total_items;
      $total_pages = $first > 0 ? ceil($total_items / $first) : 0;
      $current_page = $first > 0 ? floor($offset / $first) + 1 : 1;

      return [
        'nodes' => $posts,
        'pageInfo' => [
          'total' => $total_items,
          'offset' => $offset,
          'hasMore' => $has_more,
          'totalPages' => $total_pages,
          'currentPage' => $current_page,
        ],
      ];
    },
  ]);
});
