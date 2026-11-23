<?php
include(get_template_directory() . '/custom-code/url-rewrites.php');
include(get_template_directory() . '/custom-code/figure-caption.php');
include(get_template_directory() . '/custom-code/taxonomy-location.php');
include(get_template_directory() . '/custom-code/taxonomy-serial.php');
include(get_template_directory() . '/custom-code/taxonomy-topic.php');
include(get_template_directory() . '/custom-code/taxonomy-entity.php');
include(get_template_directory() . '/custom-code/taxonomy-shorts-format.php');
include(get_template_directory() . '/custom-code/thumbnailed-recent-posts.php');
include(get_template_directory() . '/custom-code/feed-query.php');
include(get_template_directory() . '/custom-code/meta.php');
include(get_template_directory() . '/components/functions.php');
include(get_template_directory() . '/custom-code/post-type-formats.php');
include(get_template_directory() . '/custom-code/analytics.php');
include(get_template_directory() . '/custom-code/graphql.php');
if (function_exists('add_theme_support')) {
    add_theme_support('menus');
    add_theme_support('post-formats', array('aside'));
    add_theme_support('post-thumbnails');
    add_image_size('large', 1200, 800, true); // Large Thumbnail
    add_image_size('wide', 1200); // Large No Crop Thumbnail
    add_image_size('medium', 768, 512, true); // Medium Thumbnail
    add_image_size('cover-image-large', 1800, 1012, true); // Large Cover Thumbnail
    add_image_size('thumbnail', 100, 100, true); // Small Thumbnail
    add_image_size('thumbnail-medium', 350, 233, true); // Medium Thumbnail
    load_theme_textdomain('mongabay', get_template_directory() . '/languages');
}

function mongabay_comments()
{
    $site_id = get_current_blog_id();
    $post_id = get_the_ID();
    $spot_id = '';
    switch ($site_id) {
        case '20':
            $spot_id = 'sp_8TLFqmLV';
            break;
        case '23':
            $spot_id = 'sp_A3SnHcgH';
            break;
        case '24':
            $spot_id = 'sp_ZwMqGXOu';
            break;
        case '25':
            $spot_id = 'sp_1xRecNST';
            break;
        case '26':
            $spot_id = 'sp_sbJbR9zO';
            break;
        case '27':
            $spot_id = 'sp_NNvA5Ksj';
            break;
        case '28':
            $spot_id = 'sp_elj4Klyl';
            break;
        case '29':
            $spot_id = 'sp_yZlmuqld';
            break;
        case '30':
            $spot_id = 'sp_uf34MHbE';
            break;
    };
    print '<script async src="https://recirculation.spot.im/spot/' . $spot_id . '"></script>
        <div data-spotim-module="recirculation" data-spot-id="' . $spot_id . '"></div><script async data-spotim-module="spotim-launcher" src="https://launcher.spot.im/spot/' . $spot_id . '" data-post-id="' . $post_id . '"></script>';
}
// Get current host
function mongabay_subdomain_name()
{
    $parsedUrl = parse_url($_SERVER['SERVER_NAME']);
    $host = explode('.', $parsedUrl['path']);
    $domain = $host[0];
    return $domain;
}

// Main WP_query modifier to process multiple vars
function mongabay_get_list_term($slug)
{
    if (!is_string($slug) || $slug === '') {
        return null;
    }

    foreach (array('topic', 'location') as $taxonomy) {
        $term = get_term_by('slug', $slug, $taxonomy);
        if ($term && !is_wp_error($term)) {
            return $term;
        }
    }

    return null;
}

function mongabay_mega_query($query)
{
    if ($query->is_home() && $query->is_main_query() && !is_admin()) {
        $home_url = esc_url(home_url('/'));
        $section = get_query_var('section');
        $term_slugs = array(
            get_query_var('nc1'),
            get_query_var('nc2'),
            get_query_var('nc3'),
        );
        $term_slugs = array_values(array_filter($term_slugs, function ($slug) {
            return is_string($slug) && $slug !== '';
        }));

        if ($section == 'moved') {
            $moved_query = array('post_type' => 'post', 'posts_per_page' => 1, 'offset' => 0, array('key' => 'mongabay_post_legacy_url', 'value' => get_query_var('nc2'), 'compare' => '='));
            $query->set('meta_query', $moved_query);
        }
        if ($section == 'list' && empty($term_slugs[0])) {
            wp_redirect($home_url);
            exit;
        }

        if ($section === 'list' && $term_slugs[0] !== 'specials') {
            $tax_query = array('relation' => 'AND');
            $terms = array();

            foreach ($term_slugs as $slug) {
                $term = mongabay_get_list_term($slug);
                if (!$term) {
                    $terms = array();
                    break;
                }
                $terms[] = $term;
                $tax_query[] = array(
                    'taxonomy' => $term->taxonomy,
                    'field' => 'slug',
                    'terms' => $term->slug,
                );
            }

            $query->set('post_type', array('post', 'custom-story', 'videos', 'podcasts', 'short-article', 'specials'));
            $query->set('posts_per_page', 20);
            if (count($terms) === count($term_slugs)) {
                if (
                    count($terms) === 2
                    && $terms[0]->taxonomy === 'location'
                    && $terms[1]->taxonomy === 'topic'
                ) {
                    $redirect_url = home_url('/list/' . $terms[1]->slug . '/' . $terms[0]->slug . '/');
                    $paged = (int) $query->get('paged');
                    if ($paged > 1) {
                        $redirect_url = trailingslashit($redirect_url . 'page/' . $paged);
                    }
                    wp_safe_redirect($redirect_url);
                    exit;
                }
                $query->set('tax_query', $tax_query);
            } else {
                $query->set('post__in', array(0));
            }
        }
    }
}


// Fix topics links
function mongabay_topic_link($link, $term, $taxonomy)
{
    if ($taxonomy !== 'topic')
        return $link;

    return str_replace('topic/', 'list/', $link);
}


// Fix locations links
function mongabay_location_link($link, $term, $taxonomy)
{
    if ($taxonomy !== 'location')
        return $link;

    return str_replace('location/', 'list/', $link);
}


// Fix byline links
function mongabay_byline_link($link, $term, $taxonomy)
{
    if ($taxonomy !== 'byline')
        return $link;

    return str_replace('byline/', 'by/', $link);
}


// Function to detect if we are dealing with featured aside article
function mongabay_layout()
{
    if (is_single()) {
        $post_id = get_the_ID();
        $aside = get_post_format($post_id);
        $featured = get_post_meta($post_id, 'featured_as', false);
        $mobile_safe = get_post_meta($post_id, 'mobile_safe', true);
        if ($aside == 'aside' && in_array('featured', $featured) && $mobile_safe == '') {
            $container = 'container-fluid';
        } elseif ($aside == 'aside' && in_array('featured', $featured) && $mobile_safe == '1') {
            $container = 'container-mobile-safe';
        } else {
            $container = 'container';
        }
    } else {
        $container = 'container';
    }
    return $container;
}


// Sanitize content
function mongabay_sanitized_content($post_id)
{
    if (get_post_meta($post_id, "mongabay_post_legacy_status", true) == 'yes') {
        $content = get_the_content();
        $content = preg_replace('/<font.*?>/', '', $content);
        $content = preg_replace('/<\/font>/', '', $content);
        $content = str_replace(']]>', '', $content);
        $content = str_replace(array("}", "{"), '', $content);
        $content = str_replace(array('<br>', '<BR>', '<br/>', '<BR/>'), "\n", $content);
        $content = apply_filters('the_content', $content);
        $content = str_replace('<p></p>', '', $content);
        echo $content;
    } else {
        the_content();
    }
}


// Load scripts
function mongabay_header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
        wp_register_script('addtohomescreen', get_template_directory_uri() . '/js/lib/addtohomescreen.min.js', array(), '3.2.3', true);
        wp_enqueue_script('addtohomescreen');
    }

    wp_register_script('dialog-polyfill', get_template_directory_uri() . '/js/lib/dialog-polyfill.js', array(), '0.0.1', 'all');
    wp_enqueue_script('dialog-polyfill');
}

// Load conditional scripts
function mongabay_conditional_scripts()
{
    if (get_query_var('section') === 'list' || is_tax(array('topic', 'location'))) {
        $listing_filters_path = get_stylesheet_directory() . '/js/lib/listing-filters.js';
        wp_enqueue_script(
            'mongabay-listing-filters',
            get_stylesheet_directory_uri() . '/js/lib/listing-filters.js',
            array(),
            file_exists($listing_filters_path) ? filemtime($listing_filters_path) : '1.0.0',
            true
        );
        $filter_defaults = array(
            'topic' => array(
                array(__('Animals', 'mongabay'), __('animals', 'mongabay')),
                array(__('Forests', 'mongabay'), __('forests', 'mongabay')),
                array(__('Oceans', 'mongabay'), __('oceans', 'mongabay')),
                array(__('Conservation', 'mongabay'), __('conservation', 'mongabay')),
                array(__('Indigenous Peoples', 'mongabay'), __('indigenous-peoples', 'mongabay')),
            ),
            'location' => array(
                array(__('Asia', 'mongabay'), __('asia', 'mongabay')),
                array(__('Africa', 'mongabay'), __('africa', 'mongabay')),
                array(__('South America', 'mongabay'), __('south-america', 'mongabay')),
                array(__('Indonesia', 'mongabay'), __('indonesia', 'mongabay')),
                array(__('Amazon', 'mongabay'), __('amazon', 'mongabay')),
                array(__('Congo Basin', 'mongabay'), __('congo-basin', 'mongabay')),
            ),
        );
        wp_localize_script('mongabay-listing-filters', 'listingFilterConfigLocal', array(
            'graphqlUrl' => esc_url_raw(home_url('/graphql')),
            'defaults' => $filter_defaults,
            'labels' => array(
                'topic' => __('Topic', 'mongabay'),
                'location' => __('Location', 'mongabay'),
                'addFilter' => __('Add a topic or location', 'mongabay'),
                'removeFilter' => __('Remove %s filter', 'mongabay'),
                'noResults' => __('No matching topics or locations found.', 'mongabay'),
                'searchError' => __('Could not load suggestions. Please try again.', 'mongabay'),
            ),
        ));
    }

    if (mongabay_layout() == 'container-fluid') {
        wp_register_script('parallax', get_template_directory_uri() . '/js/lib/parallax.min.js', array(), '1.4.2', true);
        wp_enqueue_script('parallax');
        wp_register_script('iframeresize', 'https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/4.3.1/iframeResizer.min.js', array(), '4.3.1', true);
        wp_enqueue_script('iframeresize');
    }

    if (is_front_page() || is_page([__('articles', 'mongabay'), __('specials', 'mongabay'), __('videos', 'mongabay'), __('podcasts', 'mongabay'), __('features', 'mongabay')])) {
        wp_register_style('slick-main', get_template_directory_uri() . '/js/lib/slick/slick.css', array(), '1.8.1', 'all');
        wp_enqueue_style('slick-main');
        wp_register_style('slick-theme', get_template_directory_uri() . '/js/lib/slick/slick-theme.css', array(), '1.8.1', 'all');
        wp_enqueue_style('slick-theme');
        wp_register_script('slick', get_template_directory_uri() . '/js/lib/slick/slick.min.js', array(), '1.8.1', true);
        wp_enqueue_script('slick');
        wp_register_script('slick-init', get_template_directory_uri() . '/js/slick-init.js', array(), '1.0.0', true);
        wp_enqueue_script('slick-init');

        // Masonry for shorts layout
        wp_register_script('masonry-lib', get_stylesheet_directory_uri() . '/js/lib/masonry-grid-vanilla.js', array(), '1.1.0', true);
        wp_enqueue_script('masonry-lib');
        wp_register_script('masonry-init', get_template_directory_uri() . '/js/lib/masonry-init.js', array('masonry-lib'), '1.0.0', true);
        wp_enqueue_script('masonry-init');

        // Short videos horizontal scroll loading
        wp_register_script('short-videos-scroll', get_template_directory_uri() . '/js/lib/short-videos-scroll.js', array(), '1.0.0', true);
        wp_enqueue_script('short-videos-scroll');
        wp_localize_script('short-videos-scroll', 'shortVideosScrollConfig', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
    }

    if (is_search() && isset($_GET['s'])) {
        wp_register_script('masonry-lib', get_stylesheet_directory_uri() . '/js/lib/masonry-grid-vanilla.js', array(), '1.1.0', true);
        wp_enqueue_script('masonry-lib');
        wp_register_script('search-js', get_stylesheet_directory_uri() . '/js/lib/search.js', array('masonry-lib'), filemtime(get_stylesheet_directory() . '/js/lib/search.js'), true);
        wp_enqueue_script('search-js');
        wp_localize_script('search-js', 'formatOptionsLocal', array(
            'post'          => array(__('Articles', 'mongabay'), 'POST'),
            'custom_story'  => array(__('Custom Story', 'mongabay'), 'CUSTOM_STORY'),
            'short_audio'   => array(__('Shorts: Audio', 'mongabay'), 'SHORT_AUDIO'),
            'short_video'   => array(__('Shorts: Video', 'mongabay'), 'SHORT_VIDEO'),
            'short_content' => array(__('Shorts: Content', 'mongabay'), 'SHORT_CONTENT'),
            'videos'        => array(__('Video', 'mongabay'), 'VIDEOS'),
            'podcasts'      => array(__('Podcast', 'mongabay'), 'PODCASTS'),
            'specials'      => array(__('Specials', 'mongabay'), 'SPECIALS'),
        ));
        wp_localize_script('search-js', 'articlesDataLocal', array(
            0 => array(__('Forests', 'mongabay')),
            1 => array(__('Wildlife', 'mongabay')),
            2 => array(__('Oceans', 'mongabay')),
            3 => array(__('Feature', 'mongabay')),
        ));
        wp_localize_script('search-js', 'locationsDataLocal', array(
            0 => array(__('Asia', 'mongabay'), __('asia', 'mongabay')),
            1 => array(__('Africa', 'mongabay'), __('africa', 'mongabay')),
            2 => array(__('South America', 'mongabay'), __('south-america', 'mongabay')),
            3 => array(__('Indonesia', 'mongabay'), __('indonesia', 'mongabay')),
            4 => array(__('Amazon', 'mongabay'), __('amazon', 'mongabay')),
            5 => array(__('Congo Basin', 'mongabay'), __('congo-basin', 'mongabay')),
        ));
        wp_localize_script('search-js', 'topicsDataLocal', array(
            0 => array(__('Animals', 'mongabay'), __('animals', 'mongabay')),
            1 => array(__('Forests', 'mongabay'), __('forests', 'mongabay')),
            2 => array(__('Oceans', 'mongabay'), __('oceans', 'mongabay')),
            3 => array(__('Conservation', 'mongabay'), __('conservation', 'mongabay')),
            4 => array(__('Indigenous Peoples', 'mongabay'), __('indigenous-peoples', 'mongabay')),
        ));
        wp_localize_script('search-js', 'loadMoreLocal', __('Load more', 'mongabay'));
        wp_localize_script('search-js', 'storiesLocal', __('stories', 'mongabay'));
        wp_localize_script('search-js', 'storyLocal', __('story', 'mongabay'));
    }
    if (is_page(['contact', 'terms']) && !wp_is_mobile()) {
        wp_register_script('highlighter', get_template_directory_uri() . '/js/lib/sidebar-highlighter.js', array(), '1.0.0', true);
        wp_enqueue_script('highlighter');
    }
    $shared_short_slug = sanitize_title((string) get_query_var('short'));
    $shared_short = $shared_short_slug
        ? get_page_by_path($shared_short_slug, OBJECT, 'short-article')
        : null;
    if (is_page('shorts') || ($shared_short instanceof WP_Post && $shared_short->post_status === 'publish')) {
        wp_register_script('shorts-previewer', get_template_directory_uri() . '/js/lib/shorts-previewer.js', array(), '1.0.0', true);
        wp_enqueue_script('shorts-previewer');

        if ($shared_short instanceof WP_Post && $shared_short->post_status === 'publish') {
            wp_localize_script('shorts-previewer', 'shortsPreviewData', array(
                'title' => get_the_title($shared_short),
                'slug' => $shared_short->post_name,
                'date' => get_the_date('j M Y', $shared_short),
                'content' => apply_filters('the_content', $shared_short->post_content),
                'articleLink' => get_post_meta($shared_short->ID, 'article_link', true),
                'byline' => getPostBylines($shared_short->ID),
            ));
        }
    }
}


// Load word count script on short articles
function enqueue_word_count_script($hook)
{
    // Only enqueue script on the edit page for the custom post type
    if ($hook === 'post.php' || $hook === 'post-new.php') {
        global $post;
        if ($post->post_type === 'short-article') {
            wp_enqueue_script('word-count-js', get_template_directory_uri() . '/js/lib/word-count.js', array('jquery'), null, true);
        }
    }
}

// Featured articles template
function mongabay_featured()
{
    if (mongabay_layout() == "container-fluid") {
        include(TEMPLATEPATH . '/single-featured.php');
        exit;
    }
}


// Load styles
function mongabay_styles()
{
    $css_version = filemtime(get_template_directory() . '/css/style.css');
    wp_register_style('framework', get_template_directory_uri() . '/css/style.css', array(), $css_version, 'all');
    wp_enqueue_style('framework');
    wp_register_style('icon-fonts', get_template_directory_uri() . '/css/fontello.css', array(), '1.0', 'all');
    wp_enqueue_style('icon-fonts');
    wp_register_style('addtohomescreen', get_template_directory_uri() . '/css/addtohomescreen.min.css', array(), '3.2.3', 'all');
    wp_enqueue_style('addtohomescreen');
    wp_register_style('dialog-polyfill', get_template_directory_uri() . '/css/dialog-polyfill.css', array(), '0.0.1', 'all');
    wp_enqueue_style('dialog-polyfill');
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class
function mongabay_ocean_topic_details()
{
    $ocean_slug = sanitize_title(__('oceans', 'mongabay'));
    $ocean_term = get_term_by('slug', $ocean_slug, 'topic');
    if (!$ocean_term && $ocean_slug !== 'oceans') {
        $ocean_term = get_term_by('slug', 'oceans', 'topic');
    }

    return array(
        'slug' => $ocean_term && !is_wp_error($ocean_term) ? $ocean_term->slug : $ocean_slug,
        'term_id' => $ocean_term && !is_wp_error($ocean_term) ? (int) $ocean_term->term_id : 0,
        'term_taxonomy_id' => $ocean_term && !is_wp_error($ocean_term) ? (int) $ocean_term->term_taxonomy_id : 0,
    );
}

function mongabay_tax_query_has_ocean_term($clauses, $ocean)
{
    foreach ($clauses as $clause) {
        if (!is_array($clause)) {
            continue;
        }

        if (($clause['taxonomy'] ?? '') === 'topic') {
            $field = $clause['field'] ?? 'term_id';
            $terms = (array) ($clause['terms'] ?? array());
            foreach ($terms as $term) {
                if ($field === 'slug' && sanitize_title((string) $term) === $ocean['slug']) {
                    return true;
                }
                if (
                    $field === 'term_id'
                    && $ocean['term_id']
                    && (int) $term === $ocean['term_id']
                ) {
                    return true;
                }
                if (
                    $field === 'term_taxonomy_id'
                    && $ocean['term_taxonomy_id']
                    && (int) $term === $ocean['term_taxonomy_id']
                ) {
                    return true;
                }
            }
        }

        if (($clause['taxonomy'] ?? '') === 'location') {
            $field = $clause['field'] ?? 'term_id';
            foreach ((array) ($clause['terms'] ?? array()) as $term) {
                if (mongabay_location_term_is_ocean_related($term, $field)) {
                    return true;
                }
            }
        }

        if (mongabay_tax_query_has_ocean_term($clause, $ocean)) {
            return true;
        }
    }

    return false;
}

function mongabay_location_term_is_ocean_related($term, $field)
{
    $values = array();
    if (in_array($field, array('term_id', 'term_taxonomy_id'), true) && is_numeric($term)) {
        $location_term = $field === 'term_taxonomy_id'
            ? get_term_by('term_taxonomy_id', (int) $term, 'location')
            : get_term((int) $term, 'location');
        if (!$location_term || is_wp_error($location_term)) {
            return false;
        }
        $values = array($location_term->slug, $location_term->name);
    } elseif (is_scalar($term)) {
        $values = array((string) $term);
    }

    $tokens = array('ocean', 'oceans', 'sea', 'seas');
    foreach (array('ocean', 'oceans', 'sea', 'seas') as $label) {
        $translated = sanitize_title(__($label, 'mongabay'));
        if ($translated !== '') {
            $tokens = array_merge($tokens, explode('-', $translated));
        }
    }
    $tokens = array_unique($tokens);

    foreach ($values as $value) {
        $value_tokens = preg_split('/[-_\s]+/', sanitize_title($value), -1, PREG_SPLIT_NO_EMPTY);
        if (array_intersect($tokens, $value_tokens)) {
            return true;
        }
    }

    return false;
}

function mongabay_is_ocean_listing()
{
    global $wp_query;
    $is_taxonomy_listing = ($wp_query instanceof WP_Query)
        && (get_query_var('section') === 'list' || is_tax(array('topic', 'location')))
        && isset($wp_query->tax_query->queries);
    if (!$is_taxonomy_listing) {
        return false;
    }

    return mongabay_tax_query_has_ocean_term(
        $wp_query->tax_query->queries,
        mongabay_ocean_topic_details()
    );
}

function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    if (mongabay_is_ocean_listing()) {
        $classes[] = 'has-ocean-primary-color';
    }

    return $classes;
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links
function mongabay_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function mongabay_index($length) // Create 20 Word Callback for Index page Excerpts, call using mongabay_excerpt('mongabay_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using mongabay_excerpt('mongabay_custom_post');
function mongabay_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts
function mongabay_excerpt($length = 30)
{
    global $post;
    if (empty($post->post_excerpt)) {
        $output_1 = strip_shortcodes($post->post_content);
        $output_2 = wp_strip_all_tags($output_1);
        $output = wp_trim_words($output_2, $length);
    } else {
        $output = $post->post_excerpt;
    }
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    echo $output;
}

// Custom View Article link to Post
function mongabay_blank_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'mongabay') . '</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function mongabay_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions($html)
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Register custom query vars
function mongabay_query_var($vars)
{

    $vars[] = 'section';
    $vars[] = 'nc1';
    $vars[] = 'nc2';
    $vars[] = 'nc3';
    return $vars;
}


// Customize RSS feed
remove_all_actions('do_feed_rss2'); // Remove default RSS feed

function mongabay_feed_rss2()
{

    $rss_template = get_template_directory() . '/custom-code/feed-rss2.php';

    if (file_exists($rss_template)) {
        load_template($rss_template);
    }
}

add_action('do_feed_rss2', 'mongabay_feed_rss2', 10, 1); // Custom RSS feed

function custom_rss_feed_init()
{
    add_feed('custom', 'custom_rss_feed_callback');
}

function custom_rss_feed_callback()
{
    $search_term = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $topics_str = isset($_GET['topic']) ? ($_GET['topic']) : '';
    $locations_str = isset($_GET['location']) ? ($_GET['location']) : '';
    $post_type_str = isset($_GET['post_type']) ? ($_GET['post_type']) : '';

    $topics = array();
    $locations = array();

    if (!empty($topics_str)) {
        if (strpos($topics_str, ',') !== false) {
            $topics = explode(',', $topics_str);
        } else {
            $topics[] = $topics_str;
        }
    }

    if (!empty($locations_str)) {
        if (strpos($locations_str, ',') !== false) {
            $locations = explode(',', $locations_str);
        } else {
            $locations[] = $locations_str;
        }
    }

    if (!empty($post_type_str)) {
        switch ($post_type_str) {
            case 'shortArticle':
                $post_type = 'short-article';
                break;
            case 'posts':
                $post_type = 'post';
                break;
            default:
                $post_type = $post_type_str;
                break;
        }
    } else {
        $post_type = 'post';
    }

    $tax_query = array('relation' => 'OR');

    if (!empty($topics)) {
        $tax_query[] = array(
            'taxonomy' => 'topic',
            'field' => 'slug',
            'terms' => $topics,
        );
    }

    if (!empty($locations)) {
        $tax_query[] = array(
            'taxonomy' => 'location',
            'field' => 'slug',
            'terms' => $locations,
        );
    }

    // Custom query arguments
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => 10,
        's' => $search_term,
    );

    if (!empty($topics) || !empty($locations)) {
        $args['tax_query'] = $tax_query;
    }

    $query = new WP_Query($args);

    // Generate RSS feed
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);

    echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?>';
?>
    <rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" <?php do_action('rss2_ns'); ?>>
        <channel>
            <title><?php _e('Results for: ', 'mongabay'); ?><?php echo $search_term; ?></title>
            <link><?php bloginfo_rss('url'); ?></link>
            <description><?php bloginfo_rss('description'); ?></description>
            <language><?php bloginfo_rss('language'); ?></language>
            <pubDate><?php echo date('r'); ?></pubDate>
            <generator>https://wordpress.org/?v=<?php bloginfo_rss('version'); ?></generator>
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <item>
                    <title><?php the_title_rss(); ?></title>
                    <link><?php the_permalink_rss(); ?></link>
                    <pubDate><?php echo get_post_time('r', true); ?></pubDate>
                </item>
            <?php endwhile; ?>
        </channel>
    </rss>
    <?php
    // Restore original post data
    wp_reset_postdata();
}

// Parallax Shortcode
function parallax_img($atts)
{

    extract(shortcode_atts(array('imagepath' => 'Image Needed', 'id' => '1', 'px_title' => 'Slide Title', 'title_color' => '#FFFFFF', 'img_caption' => 'Your image caption'), $atts));
    return "<div class='parallax-section full-vheight' data-parallax='scroll' id='" . $id . "' data-image-src='" . $imagepath . "' style='background-size: cover'><div class='featured-article-meta'><span class='subtitle' style='color:" . $title_color . "'>" . $img_caption . "</span></div></div>";
}

function parallax_open()
{

    return "<div class='container pv--40 ph--40'><div class='inner'>";
}



function parallax_close()
{

    return "</div></div>";
}


// Parallax Slide Shortcode Button in a text editor
function px_shortcode_button()
{

    if (wp_script_is("quicktags")) {
    ?>
        <script type="text/javascript">
            function getSel() {
                var txtarea = document.getElementById("content");
                var start = txtarea.selectionStart;
                var finish = txtarea.selectionEnd;
                return txtarea.value.substring(start, finish);
            }

            function callback() {
                var selected_text = getSel();
                QTags.insertContent("[parallax-img imagepath='image_url' id='1' px_title='First Title' title_color='#333333' img_caption='Your image caption']");
            }

            document.addEventListener('DOMContentLoaded', function() {
                QTags.addButton(
                    "parallax_shortcode",
                    "Parallax",
                    callback
                );
            });
        </script>
    <?php
    }
}

// Parallax Content Shortcode Button in a text editor
function open_close_px_content()
{
    if (wp_script_is("quicktags")) {
    ?>
        <script type="text/javascript">
            function getSel() {
                var txtarea = document.getElementById("content");
                var start = txtarea.selectionStart;
                var finish = txtarea.selectionEnd;
                return txtarea.value.substring(start, finish);
            }

            function callback() {
                var selected_text = getSel();
                QTags.insertContent("[open-parallax-content]" + selected_text + "[close-parallax-content]")
            }

            document.addEventListener('DOMContentLoaded', function() {
                QTags.addButton(
                    "pxcontent_shortcode",
                    "Parallax Content",
                    callback
                );
            });
        </script>
        <?php
    }
}

// Remove meta boxes from post editing screen
function mongabay_remove_custom_fields()
{
    $post_types = get_post_types('', 'names');

    foreach ($post_types as $post_type) {
        remove_meta_box('postcustom', $post_type, 'normal');
    }
}


// Prevent from aading new location tags
function mongabay_prevent_terms($term, $taxonomy)
{
    $current_site_id = get_current_blog_id();
    $exception_user_ids = array(1125, 1163);

    if (in_array($taxonomy, array('post_tag', 'topic', 'location'), true) && (!current_user_can('activate_plugins') || (!in_array(get_current_user_id(), $exception_user_ids) && $current_site_id === 35))) {
        return new WP_Error('term_addition_blocked', __('You cannot add terms to this taxonomy'));
    }

    return $term;
}



// Stats pages dynamic sidebar
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => __('Stats Widget', 'mongabay'),
        'description' => __('Stats page sidebar widgets should be placed here.', 'mongabay'),
        'id' => 'stats-widget',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>'
    ));
}

// Remove query strings from scripts and css
function remove_query_string($src)
{
    $parts = explode('?ver', $src);
    return $parts[0];
}

if (!is_admin()) {
    add_filter('script_loader_src', 'remove_query_string', 15, 1);
    add_filter('style_loader_src', 'remove_query_string', 15, 1);
}

// Custom rewrite rule for wildtech posts
function mongabay_wildtech_post_link($url, $post, $leavename)
{
    $parsed = parse_url($url);
    if ($post->post_type == 'post') {
        $tech = has_term('technology', 'topic');
        if ($tech) $url = get_home_url() . '/wildtech' . $parsed['path'];
    }
    return $url;
}

// Listings proper page title
function mongabay_custom_title()
{
    if (get_query_var('section') == 'list') {
        $term_slugs = array(
            get_query_var('nc1'),
            get_query_var('nc2'),
            get_query_var('nc3'),
        );
        $titles = array();
        foreach ($term_slugs as $slug) {
            $term = mongabay_get_list_term($slug);
            if ($term) {
                $titles[] = $term->name;
            }
        }

        if (!empty($titles)) {
            _e('Conservation news on', 'mongabay');
            echo ' ' . implode(' and ', $titles);
        }
    } else {
        wp_title('');
    }
}

// Add the filter parameter for API
function rest_api_filter_add_filters()
{
    foreach (get_post_types(array('show_in_rest' => true), 'objects') as $post_type) {
        add_filter('rest_' . $post_type->name . '_query', 'rest_api_filter_add_filter_param', 10, 2);
    }
}

function rest_api_filter_add_filter_param($args, $request)
{

    if (empty($request['filter']) || !is_array($request['filter'])) {
        return $args;
    }
    $filter = $request['filter'];
    if (isset($filter['posts_per_page']) && ((int) $filter['posts_per_page'] >= 1 && (int) $filter['posts_per_page'] <= 100)) {
        $args['posts_per_page'] = $filter['posts_per_page'];
    }
    global $wp;
    $vars = apply_filters('query_vars', $wp->public_query_vars);
    foreach ($vars as $var) {
        if (isset($filter[$var])) {
            $args[$var] = $filter[$var];
        }
    }
    return $args;
}

function mongabay_rss_pre_get_posts($query)
{
    if ($query->is_feed && $query->is_main_query()) {
        if (isset($query->query_vars['grant']) && !empty($query->query_vars['grant'])) {
            // if you only want to allow 'alpha-numerics':
            $grant =  preg_replace("/[^a-zA-Z0-9]/", "", $query->query_vars['grant']);
            $query->set('meta_key', 'grant');
            $query->set('meta_value', $grant);
        }
    }
}

// Remove p tags from images, scripts, and iframes.
function mongabay_remove_iframe_ptags($content)
{
    $content = preg_replace('/<p>.*?\s*(<iframe.*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content);
    return $content;
}

// Detect Android Mobile phone
function is_android_mobile()
{ // detect only Android phones
    $is_android   = (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'Android');
    $is_android_m = (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile');

    return $is_android && $is_android_m;
}

// Onesignal notification filter
function onesignal_send_notification_filter($fields, $new_status, $old_status, $post)
{
    $fields_dup = $fields;
    $fields_dup['isAndroid'] = true;
    $fields_dup['isIos'] = true;
    $fields_dup['isAnyWeb'] = true;
    $fields_dup['isWP'] = false;
    $fields_dup['isAdm'] = false;
    $fields_dup['isChrome'] = false;
    $fields_dup['data'] = array(
        "notifyurl" => $fields['url']
    );
    $fields_dup['web_url'] = $fields_dup['url'];
    $fields_dup['included_segments'] = array('mongabay_push');
    unset($fields_dup['url']);
    $ch = curl_init();
    $onesignal_post_url = "https://onesignal.com/api/v1/notifications";
    $onesignal_wp_settings = OneSignal::get_onesignal_settings();
    $onesignal_auth_key = $onesignal_wp_settings['app_rest_api_key'];
    curl_setopt($ch, CURLOPT_URL, $onesignal_post_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Basic ' . $onesignal_auth_key
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields_dup));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $fields;
}

// Sanitize json output for content. Consumed by APP.
function mongabay_sanitize_json($data, $post, $context)
{
    $data->data['content'] = preg_replace('/<noscript\b[>]*>(.*?)<\/noscript>/s', '', $data->data['content']);
    $data->data['content'] = preg_replace('/<div class=\'container\'>\\n<div class=\'row justify-content-center\'>\\n<div id=\'main\' class=\'col-lg-8 single\'>\\n/s', '', $data->data['content']);
    $data->data['content'] = preg_replace('/<div class=\'clearfix\'><\/div>\\n/s', '', $data->data['content']);
    $data->data['content'] = preg_replace('/<\/div>\\n<\/div>\\n<\/div>\\n/s', '', $data->data['content']);
    $data->data['content'] = preg_replace('/\\n<div>\\n<div>\\n<ul class/s', '<ul class', $data->data['content']);
    $data->data['content'] = preg_replace('/\/>\\n<div>\\n<div>.*\w*<\/div>\\n<\/div>\\n<\/li>/', '/></li>', $data->data['content']);
    $data->data['content'] = preg_replace('/<!--.*\w*-->/', '', $data->data['content']);
    //$data->data['content'] = preg_replace('/<p>\\n<p>/s', '<p>', $data->data['content']);
    $data->data['content'] = preg_replace('/<a href=\\"https:\/\/news[.]mongabay[.]com\/\d\d\d\d\/\d\d\//s', '<a href="mongabay://article/', $data->data['content']);
    $data->data['content'] = preg_replace('/<a href=\\"https:\/\/cn[.]mongabay[.]com\/\d\d\d\d\/\d\d\//s', '<a href="mongabay_cn://article/', $data->data['content']);
    $data->data['content'] = preg_replace('/<a href=\\"https:\/\/de[.]mongabay[.]com\/\d\d\d\d\/\d\d\//s', '<a href="mongabay_de://article/', $data->data['content']);
    $data->data['content'] = preg_replace('/<a href=\\"https:\/\/es[.]mongabay[.]com\/\d\d\d\d\/\d\d\//s', '<a href="mongabay_es://article/', $data->data['content']);
    $data->data['content'] = preg_replace('/<a href=\\"https:\/\/fr[.]mongabay[.]com\/\d\d\d\d\/\d\d\//s', '<a href="mongabay_fr://article/', $data->data['content']);
    $data->data['content'] = preg_replace('/<a href=\\"https:\/\/it[.]mongabay[.]com\/\d\d\d\d\/\d\d\//s', '<a href="mongabay_it://article/', $data->data['content']);
    $data->data['content'] = preg_replace('/<a href=\\"https:\/\/jp[.]mongabay[.]com\/\d\d\d\d\/\d\d\//s', '<a href="mongabay_jp://article/', $data->data['content']);
    $data->data['content'] = preg_replace('/<a href=\\"https:\/\/pt[.]mongabay[.]com\/\d\d\d\d\/\d\d\//s', '<a href="mongabay_pt://article/', $data->data['content']);
    $data->data['content'] = preg_replace('/<a href=\\"https:\/\/india[.]mongabay[.]com\/\d\d\d\d\/\d\d\//s', '<a href="mongabay_in://article/', $data->data['content']);
    $data->data['content'] = preg_replace('/\\n/s', '', $data->data['content']);
    $data->data['content'] = preg_replace('/<\/li><\/ul><\/div><p><\/div>/', '</li></ul>', $data->data['content']);

    return $data;
}

function mongabay_sanitize_page_json($data, $post, $context)
{
    $data->data['content'] = preg_replace('/\\n/s', '', $data->data['content']);
    return $data;
}

//Make custom taxonomy registered by PODs available in GraphQL
function add_pods_graphql_support($options)
{

    $options['show_in_graphql'] = true;
    $options['graphql_single_name'] = $options['labels']['name'];
    $options['graphql_plural_name'] = $options['labels']['singular_name'];

    return $options;
}

// Require featured image before publishing an article
if ($GLOBALS['pagenow'] == 'post-new.php' || $pagenow == 'post.php') :
    add_filter('wp_insert_post_data', function ($data, $postarr) {
        $post_id = $postarr['ID'];
        $post_status = $data['post_status'];

        if ($post_id && 'publish' === $post_status && isset($data['post_type']) && $data['post_type'] !== 'page') {
            if (post_type_supports($data['post_type'], 'thumbnail') && !has_post_thumbnail($post_id)) {
                $data['post_status'] = 'draft';
            }
        }
        return $data;
    }, 10, 2);
endif;

if ($GLOBALS['pagenow'] == 'post-new.php' || $pagenow == 'post.php') :
    add_action('admin_notices', function () {
        $post = get_post();
        if ('publish' !== get_post_status($post->ID) && !has_post_thumbnail($post->ID) && get_post_type($post->ID) !== 'page') { ?>
            <div id="message" class="error">
                <p><strong><?php _e('Please set Featured Image. Article cannot be published without one.'); ?></strong></p>
            </div>
            <?php
        }
    });
endif;

//Apple news byline rewrite
function mongabay_byline($byline, $postID)
{
    $byline = wp_get_post_terms($postID, 'byline');
    $date = get_the_date();
    $date_format = 'M j, Y';
    if (!empty($byline)) {
        $byline_formatted = sprintf('by %1$s | %2$s', $byline[0]->name, date($date_format, strtotime($date)));
        return $byline_formatted;
    }
}
add_filter('apple_news_exporter_byline', 'mongabay_byline', 10, 2);

//Prevent storing badly formatted HTML in articles
add_filter('wp_insert_post_data', 'my_post_data_validator', '99');
function my_post_data_validator($data)
{
    $error_1 = strpos($data['post_content'], '<br');
    $error_2 = strpos($data['post_content'], '<span');
    $error_3 = strpos($data['post_content'], '<div');
    if ($data['post_type'] === 'post') {
        if ($error_1 || $error_2 || $error_3) {
            $data['post_status'] = 'pending';
            add_filter('redirect_post_location', 'my_post_redirect_filter', '99');
        }
    }
    return $data;
}

function my_post_redirect_filter($location)
{
    remove_filter('redirect_post_location', 'my_post_redirect_filter', '99');
    return add_query_arg('mongabay_error', 1, $location);
}

add_action('admin_notices', 'my_post_admin_notices');

function my_post_admin_notices()
{
    if (!isset($_GET['mongabay_error'])) return;
    switch (absint($_GET['mongabay_error'])) {
        case 1:
            $message = 'Invalid post data. Make sure post HTML content does not contain elements like span, br and div! This is most likely because of copy/paste content from elsewhere.';
            break;
        default:
            $message = 'Something went wrong';
    }
    echo '<div id="notice" class="error"><p>' . $message . '</p></div>';
}

/* Disable Gutenberg for normal posts */
function mongabay_disable_gutenberg($can_edit, $post)
{
    $excluded_pages = array('about', 'contact', 'terms', 'donate');

    if ($post->post_type === 'custom-story') {
        return true;
    }

    if (in_array($post->post_name, $excluded_pages)) {
        return true;
    }

    return false;
}

/* Register post tags for new post types */
function mongabay_register_tags_cpts()
{
    register_taxonomy_for_object_type('post_tag', 'videos');
    register_taxonomy_for_object_type('post_tag', 'podcasts');
    register_taxonomy_for_object_type('post_tag', 'short-article');
    register_taxonomy_for_object_type('post_tag', 'custom-story');
    register_taxonomy_for_object_type('post_tag', 'specials');
}
add_action('init', 'mongabay_register_tags_cpts');

function banner_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'link' => '',
        'title' => '',
        'copy' => '',
        'button_copy' => '',
        'extra_class' => '',
        'headline_class' => '',
        'button_class' => ''
    ), $atts);

    ob_start();
    banner(
        $atts['link'],
        $atts['title'],
        $atts['copy'],
        $atts['button_copy'],
        $atts['extra_class'],
        $atts['headline_class'],
        $atts['button_class']
    );
    return ob_get_clean();
}

// Ajaxed Pagination
function mongabay_ajaxed_pagination()
{
    if (is_page(__('shorts', 'mongabay')) || is_singular(__('specials', 'mongabay'))) {
        wp_enqueue_script('ajax-pagination', get_template_directory_uri() . '/js/lib/ajaxed-pagination.js', array('jquery'), null, true);
        wp_localize_script('ajax-pagination', 'ajaxpagination', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'noposts' => __('No older posts', 'mongabay'),
            'i18n' => array(
                'loading'  => __('Loading...', 'mongabay'),
                'loadMore' => __('Load more', 'mongabay'),
                'noPosts'  => __('No older posts', 'mongabay'),
            ),
        ));
    }
}

function load_more_posts()
{
    $counter = 0;
    $paged = $_POST['page'] ?? 1;
    $post_type = $_POST['post_type'] ?? 'post';
    $taxonomy = $_POST['taxonomy'] ?? null;
    $terms = $_POST['terms'] ?? null;
    $posts_per_page = $_POST['posts_per_page'] ?? 9;

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'paged' => $paged
    );

    if ($taxonomy && $terms) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => $terms,
            )
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        if ($post_type === 'short-article') {
            // additional spacing for short only
            echo '<div class="container full-width pv--20"></div>';
        }
        while ($query->have_posts()) : $query->the_post();
            $counter++;
            if ($post_type === 'post') {
                $isFeatured = get_post_meta(get_the_ID(), 'featured_as', true) === 'featured';
            ?>
                <div class="article--container">
                    <a href="<?php the_permalink(); ?>">
                        <?php if (has_post_thumbnail()) { ?>
                            <div class="featured-image">
                                <?php echo get_icon(get_the_ID()); ?>
                                <?php the_post_thumbnail('medium') ?>
                            </div>
                        <?php }; ?>
                        <?php if ($isFeatured) { ?>
                            <div class="featured-label"><?php _e('Feature story', 'mongabay'); ?></div>
                        <?php }; ?>
                        <div class="title headline">
                            <h3><?php the_title(); ?></h3>
                        </div>
                        <div class="post-meta pv--8">
                            <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                            <span class="date"><?php the_time('j M Y'); ?></span>
                        </div>
                    </a>
                </div>
            <?php }
            if ($post_type === 'short-article') {
                shorts_grid($counter, null);
            }
            ?>

        <?php endwhile;
    endif;
    wp_reset_postdata();

    die();
}

function load_more_short_videos()
{
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 5;

    $args = array(
        'post_type' => 'short-article',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'post_status' => 'publish',
        'cache_results' => true,
        'tax_query' => array(
            array(
                'taxonomy' => 'shorts_format',
                'field' => 'slug',
                'terms' => array('video'),
            ),
        ),
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();
            $embed_url = get_post_meta($id, 'video_link', true);
            $article_link = get_post_meta($id, 'article_link', true);
            $link_url_share = home_url('/shorts/#/' . $id);
        ?>
            <div class="article--container shorts-trigger shorts-video-card" data-articlelink="<?php echo esc_url($article_link); ?>" data-shareurl="<?php echo esc_url($link_url_share); ?>" data-media-format="video" data-embed-url="<?php echo esc_url($embed_url); ?>">
                <div class="featured-image shorts-video-featured-image">
                    <?php echo get_icon($id); ?>
                    <?php if (has_post_thumbnail()) { ?>
                        <?php the_post_thumbnail('large'); ?>
                    <?php } ?>
                    <div class="img-overlay"></div>
                    <div class="shorts-video-overlay">
                        <div class="title headline">
                            <h3><?php the_title(); ?></h3>
                        </div>
                        <div class="post-meta">
                            <span class="byline"><?php echo getPostBylines($id); ?></span>
                            <span class="date"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . __('ago', 'mongabay'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="post-excerpt hidden">
                    <?php the_content(); ?>
                </div>
                <div class="shorts-media-embed hidden">
                    <?php mongabay_render_embed_url($embed_url); ?>
                </div>
            </div>
<?php
        }
        $html = ob_get_clean();
        wp_send_json_success(array(
            'html' => $html,
            'has_more' => $query->max_num_pages > $paged
        ));
    } else {
        wp_send_json_success(array(
            'html' => '',
            'has_more' => false
        ));
    }

    wp_reset_postdata();
    die();
}

function validate_short_article_content_length($post_id)
{
    // Avoid running during autosave or for non-short-article post types
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['post_type']) && $_POST['post_type'] !== 'short-article') return;
    if (!current_user_can('edit_post', $post_id)) return;

    $content = wp_strip_all_tags($_POST['post_content']);
    $word_count = str_word_count($content);
    if ($word_count > 525) {
        remove_action('save_post', 'validate_short_article_content_length'); // prevent infinite loop
        wp_update_post(array(
            'ID' => $post_id,
            'post_status' => 'draft'
        ));
        add_action('save_post', 'validate_short_article_content_length');

        wp_die('Content exceeds 500 words. Please shorten your content.');
    }
}

function custom_post_type_link_rewrite($post_link, $post)
{
    if (
        (!is_admin() || !is_user_logged_in()) &&
        is_object($post) &&
        in_array($post->post_type, array('videos', 'podcasts', 'custom-story', 'short-article', 'specials'))
    ) {
        $post_type = '';

        switch ($post->post_type) {
            case 'videos':
                $post_type = 'video';
                break;
            case 'podcasts':
                $post_type = 'podcast';
                break;
            default:
                $post_type = $post->post_type;
                break;
        }

        if (str_contains($post_link, '&p=')) {
            return $post_link;
        }

        $post_link = home_url(user_trailingslashit(sprintf(
            '%s/%s/%s/%s',
            $post_type,
            get_the_date('Y', $post),
            get_the_date('m', $post),
            $post->post_name
        )));
    }
    return $post_link;
}

add_action('graphql_register_types', function () {
    register_graphql_field('RootQueryToContentNodeConnectionWhereArgs', 'author', [
        'type'        => 'Integer',
        'description' => 'Filter content nodes by author id'
    ]);
});

function custom_author_post_types_query($query)
{
    if (!is_admin() && $query->is_author() && $query->is_main_query()) {
        $query->set('post_type', array('post', 'videos', 'short-article', 'podcasts', 'custom-story', 'specials'));
    }
}

// Add the new image size to the list of available sizes in Gutenberg
function mongabay_custom_image_sizes($sizes)
{
    return array_merge($sizes, array(
        'wide' => __('Wide No Crop'),
    ));
}
add_filter('image_size_names_choose', 'mongabay_custom_image_sizes');


// Disable comment REST API endpoints
add_filter('rest_endpoints', function ($endpoints) {
    if (isset($endpoints['/wp/v2/comments'])) {
        unset($endpoints['/wp/v2/comments']);
    }
    if (isset($endpoints['/wp/v2/comments/(?P<id>[\d]+)'])) {
        unset($endpoints['/wp/v2/comments/(?P<id>[\d]+)']);
    }
    return $endpoints;
});

// Remove users from REST API
add_filter('rest_endpoints', function ($endpoints) {
    if (isset($endpoints['/wp/v2/users'])) {
        unset($endpoints['/wp/v2/users']);
    }
    if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
        unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    }
    return $endpoints;
});

/**
 * Remove fetchpriority="high" from images.
 */
function remove_fetchpriority_from_images($content)
{
    $content = str_replace('fetchpriority="high"', '', $content);
    return $content;
}

/**
 * Redirect custom post types without date in URL.
 */
function redirect_cpt_without_date()
{
    if (is_embed()) {
        return; // Don't redirect if the content is being embedded
    }

    if (is_singular() && !is_singular(['post', 'page']) && !is_preview() && !is_admin()) {
        global $wp_query, $post;

        // List of our custom post types and their URL formats
        $dated_cpts = [
            'podcasts' => 'podcast',
            'videos' => 'video',
            'custom-story' => 'custom-story',
            'short-article' => 'short-article',
            'specials' => 'specials'
        ];

        // Only proceed for our specified CPTs
        if (isset($dated_cpts[$post->post_type]) && empty($wp_query->query_vars['year'])) {
            // Get current URL path
            $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            // Check if we're already on a date-based URL (prevent infinite redirects)
            $pattern = '#^/' . $dated_cpts[$post->post_type] . '/[0-9]{4}/[0-9]{2}/#';
            if (!preg_match($pattern, $request_uri)) {
                // Only redirect published posts
                if ($post->post_status === 'publish') {
                    $date = get_the_date('Y/m', $post);
                    $correct_url = home_url("/{$dated_cpts[$post->post_type]}/{$date}/" . $post->post_name . '/');

                    wp_redirect($correct_url, 301); // Permanent redirect
                    exit;
                }
            }
        }
    }
}
add_action('template_redirect', 'redirect_cpt_without_date');

/**
 * Tell Yoast SEO to treat all custom post types as Article schema,
 * so the author node is included in yoast_head / yoast_head_json.
 */
add_filter('wpseo_schema_article_post_types', function (array $post_types): array {
    return array_merge($post_types, array('videos', 'podcasts', 'short-article', 'custom-story', 'specials'));
});

// Load alerts map scripts
function mongabay_get_mapbox_public_token()
{
    $token = defined('MAPBOX_PUBLIC_TOKEN')
        ? MAPBOX_PUBLIC_TOKEN
        : ($GLOBALS['MAPBOX_PUBLIC_TOKEN'] ?? (defined('MAPBOX_TOKEN') ? MAPBOX_TOKEN : ($GLOBALS['MAPBOX_TOKEN'] ?? '')));

    $token = trim((string) $token);
    return strpos($token, 'pk.') === 0 ? $token : '';
}

function mongabay_alerts_scripts()
{
    if (is_page_template('alerts-page.php')) {
        wp_enqueue_style('mapbox-gl-css', 'https://api.mapbox.com/mapbox-gl-js/v3.4.0/mapbox-gl.css', array(), '3.4.0');
        wp_enqueue_script('mapbox-gl-js', 'https://api.mapbox.com/mapbox-gl-js/v3.4.0/mapbox-gl.js', array(), '3.4.0', true);
        $alerts_map_script = get_template_directory() . '/js/lib/alerts-map.js';
        wp_register_script('alerts-map', get_template_directory_uri() . '/js/lib/alerts-map.js', array('mapbox-gl-js'), file_exists($alerts_map_script) ? filemtime($alerts_map_script) : '1.0.0', true);
        wp_enqueue_script('alerts-map');
        wp_localize_script('alerts-map', 'alertsMapConfig', array(
            'graphqlEndpoint' => esc_url(home_url('/graphql')),
            'mapboxToken' => mongabay_get_mapbox_public_token(),
            'allAlertsLabel' => __('All Alerts', 'mongabay'),
            'allNewsLabel' => __('All News', 'mongabay'),
            /* translators: %s is the selected location or topic. */
            'filteredNewsTitle' => __('%s News', 'mongabay'),
            /* translators: 1: selected location, 2: selected topic. */
            'locationTopicNewsTitle' => __('%1$s and %2$s News', 'mongabay'),
            'allLocationsLabel' => __('All locations', 'mongabay'),
            'allTopicsLabel' => __('All topics', 'mongabay'),
            'allFormatsLabel' => __('All formats', 'mongabay'),
            'formatOptions' => array(
                array('value' => 'post', 'label' => __('Articles', 'mongabay')),
                array('value' => 'custom_story', 'label' => __('Custom Story', 'mongabay')),
                array('value' => 'short_audio', 'label' => __('Shorts: Audio', 'mongabay')),
                array('value' => 'short_video', 'label' => __('Shorts: Video', 'mongabay')),
                array('value' => 'short_content', 'label' => __('Shorts: Content', 'mongabay')),
                array('value' => 'videos', 'label' => __('Video', 'mongabay')),
                array('value' => 'podcasts', 'label' => __('Podcast', 'mongabay')),
                array('value' => 'specials', 'label' => __('Specials', 'mongabay')),
            ),
            'alertsLabel' => __('alerts', 'mongabay'),
            'alertsLoadedLabel' => __('alerts loaded', 'mongabay'),
            'loadingMapLabel' => __('Loading map and filters...', 'mongabay'),
            'mapIndexUnavailable' => __('Could not load all map markers and filters.', 'mongabay'),
            'noLoadedAlertsLabel' => __('No alerts in this batch. Load more to continue.', 'mongabay'),
            'loadMoreLabel' => __('Load more alerts', 'mongabay'),
            'retryAlertsLabel' => __('Retry loading alerts', 'mongabay'),
            'noAlertsLabel' => __('No alerts found for these filters.', 'mongabay'),
            'alertsUnavailable' => __('Alerts could not be loaded. Please try again later.', 'mongabay'),
            'alertsTruncatedLabel' => __('Additional alerts were omitted.', 'mongabay'),
            'mapUnavailable' => __('The map is unavailable.', 'mongabay'),
            'readFullArticle' => __('Read full article', 'mongabay'),
        ));
    }
}

/*------------------------------------*\
    Actions + Filters
\*------------------------------------*/

// Add shortcodes
add_shortcode('parallax-img', 'parallax_img');
add_shortcode('open-parallax-content', 'parallax_open');
add_shortcode('close-parallax-content', 'parallax_close');
add_shortcode('banner', 'banner_shortcode');

// Add Actions
add_action('init', 'mongabay_header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_enqueue_scripts', 'mongabay_conditional_scripts'); // Add Conditional Page Scripts
add_action('wp_enqueue_scripts', 'mongabay_alerts_scripts'); // Add Alerts Map Scripts
add_action('admin_enqueue_scripts', 'enqueue_word_count_script'); // Enqueue word count script
add_action('wp_enqueue_scripts', 'mongabay_styles'); // Add Theme Stylesheet
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'mongabay_pagination'); // Add our Pagination
add_action('rest_api_init', 'rest_api_filter_add_filters'); // Add the filter parameter for API
add_action('pre_get_posts', 'mongabay_rss_pre_get_posts'); // Add 'grant' to meta query
add_action('pre_insert_term', 'mongabay_prevent_terms', 1, 2); // Prevent new terms to be added
add_action('admin_menu', 'mongabay_remove_custom_fields'); // Remove custom fields from post editing screen
add_action('admin_print_footer_scripts', 'px_shortcode_button'); // Add parallax button
add_action('admin_print_footer_scripts', 'open_close_px_content'); // Add parallax button
add_action('template_redirect', 'mongabay_featured'); // Redirect template if content is Featured
add_action('init', 'custom_rss_feed_init'); // Add custom RSS feed
add_action('wp_enqueue_scripts', 'mongabay_ajaxed_pagination');
add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');
add_action('wp_ajax_load_more_short_videos', 'load_more_short_videos');
add_action('wp_ajax_nopriv_load_more_short_videos', 'load_more_short_videos');
add_action('save_post', 'validate_short_article_content_length'); // Short articles validation
add_action('pre_get_posts', 'custom_author_post_types_query');
add_action('template_redirect', 'redirect_cpt_without_date'); // Redirect custom post types without date in URL

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'print_emoji_detection_script', 7); //remove emoji script
remove_action('admin_print_scripts', 'print_emoji_detection_script'); //remove emoji script
remove_action('wp_print_styles', 'print_emoji_styles'); //remove emoji style
remove_action('admin_print_styles', 'print_emoji_styles'); //remove emoji style

// Add Filters
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('style_loader_tag', 'mongabay_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('the_content', 'mongabay_remove_iframe_ptags', 13); // Remove paragraphs from iframe
add_filter('post_type_link', 'custom_post_type_link_rewrite', 1, 2); // Rewrite custom post type links
//add_filter( 'post_link', 'mongabay_wildtech_post_link', 10, 3 ); // Fix post links for wildtech posts
add_filter('query_vars', 'mongabay_query_var'); // Register custom query vars
add_filter('term_link', 'mongabay_byline_link', 10, 3); // Fix byline taxonomy link
add_filter('pre_get_posts', 'mongabay_mega_query'); // Main query modifier
add_filter('term_link', 'mongabay_location_link', 10, 3); // Fix location taxonomy link
add_filter('term_link', 'mongabay_topic_link', 10, 3); // Fix topic taxonomy link
//add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images
add_filter('onesignal_send_notification', 'onesignal_send_notification_filter', 10, 4); // Add Onesignal notifications filter
add_filter('rest_prepare_post', 'mongabay_sanitize_json', 100, 3); // Get content ready for App
add_filter('rest_prepare_page', 'mongabay_sanitize_page_json', 100, 3); //Get content ready for App
add_filter('pods_register_taxonomy_byline', 'add_pods_graphql_support'); //Byline available in GraphQL

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether
remove_filter('the_content', 'wpautop');
add_filter('the_content', 'wpautop', 12); //Remove <p> and <br> from shortcodes
add_filter('use_block_editor_for_post', 'mongabay_disable_gutenberg', 10, 2); //Enable Gutenberg editor for certain pages and custom stories
// add_filter('wp_lazy_loading_enabled', '__return_false'); //Disable lazy load
// add_filter('the_permalink', 'mirror_site_permalink'); // Fix mirror site links
// add_filter('term_link', 'mirror_site_permalink'); // Fix mirror site byline links
?>