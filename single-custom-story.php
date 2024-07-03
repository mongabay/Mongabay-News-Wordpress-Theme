<?php get_header(); ?>
<main role="main">
    <?php
    $post_id = get_the_ID();

    ?>
    <div class="container in-column ph--40">
        <div class="single">
            <?php article_headline(); ?>
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
    
    <div class="container section--highlight ph--40 pv--40 gap--20 in-column">
      <?php
      $series_latest = (array('oceans', 'amazon-conservation', 'land-rights-and-extractives', 'indigenous-peoples-and-conservation', 'great-apes', 'indonesian-palm-oil', 'indonesian-fisheries', 'global-forest-reporting-network'));
      series_latest($series_latest);
      ?>
      <div class="container centered">
        <a href="" class="theme--button primary"><?php _e('All Specials', 'mongabay'); ?><span class="icon icon-right"></span></a>
      </div>
       <?php tools_slider(); ?>       
    </div>
    <div class="container full-width pv--40">    
        <?php inspiration_banner(); ?>
    </div>
</main>
<?php get_footer(); ?>