<?php get_header(); ?>
<main role="main">
    <?php
    $post_id = get_the_ID();
    $img_url = wp_get_attachment_url(get_post_thumbnail_id());

    ?>
    <div class="container in-column ph--40">
        <div class="single">
            <?php article_headline(); ?>
            <?php if (!empty($img_url)) : ?>
                <div class="article-cover-image">
                    <?php
                    if (wp_is_mobile()) {
                        $coversize = 'medium';
                    } elseif (strlen(get_the_post_thumbnail_url($post_id, 'cover-image-retina')) > 0) {
                        $coversize = 'cover-image-retina';
                    } else {
                        $coversize = 'large';
                    }
                    $thumb_url = get_the_post_thumbnail_url($post_id, $coversize);
                    ?>
                    <div style="background: url('<?php echo esc_url($thumb_url); ?>'); background-size: cover; background-position: center; width: 100%;"></div>

                </div>
            <?php endif; ?>
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="inner">
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <?php article_bulletpoints($post_id); ?>

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
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="inner">
                    <article>
                        <h1><?php _e('Sorry, nothing to display.', 'mongabay'); ?></h1>
                    </article>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="container ph--40 pv--40 in-column">
        <?php series_articles_slider(false, 'post'); ?>
        <div class="container full-width pv--40">
            <?php banner('', 'Free and open access to credible information', '', 'Learn more', 'accent full-width pv--56', 'extra-large'); ?>
        </div>
        <div id="section-post-latest" class="container pv--40 gap--20 in-column">
            <div class="container in-row full-width section--headline" style="align-items: center; justify-content: space-between;">
                <h1><?php _e('Latest articles', 'mongabay'); ?></h1>
                <a href="<?php echo home_url(); ?>/articles" class="theme--button primary"><?php _e('All articles', 'mongabay'); ?><span class="icon icon-right"></span></a>
            </div>
            <div class="grid--4 gap--20">
                <?php articles_listing('post', 8, 0, true, 'medium', false, false, null); ?>
            </div>
            <div class="container centered pv--40">
                <a href="<?php echo home_url(); ?>/articles" class="theme--button primary"><?php _e('All articles', 'mongabay'); ?><span class="icon icon-right"></span></a>
            </div>
        </div>
        <div class="container pv--40 full-width">
            <?php banner(getSubscribeLink(), 'Subscribe', 'Stay informed with news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
        </div>
    </div>
</main>
<?php get_footer(); ?>