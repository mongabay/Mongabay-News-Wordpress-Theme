<?php get_header(); ?>
<?php
$post_id = get_the_ID();
$tagline = get_post_meta($post_id, "mog_tagline", true);
$translator = get_post_meta($post_id, "translated_by", true);
$adaptor = get_post_meta($post_id, "adapted_by", true);
$translated_adapted = get_post_meta($post_id, "translated_adapted", true);
$commentary = get_post_meta($post_id, "commentary", true);
$analysis = get_post_meta($post_id, "analysis_by", true);
$topics = wp_get_post_terms($post_id, 'topic');
$serial = wp_get_post_terms($post_id, 'serial');
?>
<?php if (has_post_thumbnail()) : ?>
    <div class="container full-width parallax-section full-vheight article-cover" data-parallax="scroll" data-image-src="<?php echo get_the_post_thumbnail_url($post_id, 'large') ?>">
        <div class="container in-column ph--40 pv--40 full-height container-featured">
            <?php article_headline(); ?>
        </div>
    </div>
<?php endif; ?>
<main role="main">
    <div class="container ph--40 pv--40 in-column">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="inner">
                        <?php article_bulletpoints($post_id); ?>
                    </div>
                    <?php mongabay_sanitized_content($post_id); ?>
                    <div id="single-article-footer">
                        <?php article_credits($post_id); ?>
                        <div id="single-article-tags">
                            <?php article_terms($post_id); ?>
                        </div>
                    </div>
                    <span class="article-comments"><a href=""></a></span>
                    <?php mongabay_comments(); ?>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <article>
                <h1><?php _e('Sorry, nothing to display.', 'mongabay'); ?></h1>
            </article>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>