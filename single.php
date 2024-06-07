<?php get_header(); ?>
<main role="main">
    <?php
        $post_id = get_the_ID();
        $translator = get_post_meta($post_id,"translated_by",true);
        $adaptor = get_post_meta($post_id,"adapted_by",true);
        $translated_adapted = get_post_meta($post_id,"translated_adapted",true);
        $commentary = get_post_meta($post_id,"commentary",true);
        $analysis = get_post_meta($post_id,"analysis_by",true);
        $topics = wp_get_post_terms($post_id, 'topic');
        $serial = wp_get_post_terms($post_id, 'serial');
        $legacy = get_post_meta($post_id, 'mongabay_post_legacy_status',true);
        $img_url = wp_get_attachment_url( get_post_thumbnail_id() );
        $byline_terms = wp_get_post_terms($post_id,'byline');
        $avatar = get_term_meta($byline_terms[0]->term_id,'cover_image_url',true);
  
    ?>
    <div class="container in-column ph--40">
        <div id="headline">
            <div class="article-headline">
                <h1><?php the_title(); ?></h1>
            </div>
            <div class="single-article-meta">
                <div class="about-author">
                  <div class="author-avatar">
                      <?php if($avatar){ echo '<img src="' . $avatar . '" alt="cover image" style="max-width:48px;height:auto;">';
                        } else { echo '<span class="meta-author-circle"></span>';
                        } ?>
                  </div>
                  <div class="author-info">
                    <?php echo get_the_term_list($post_id, 'byline', '', ', ', ''); ?><?php the_time('j F Y'); ?>
                  </div>
                </div>
                <?php $comments_number = get_comments_number();
                  if ( $comments_number > 1 ){
                    printf( esc_html__('%s Comments', 'mongabay'), get_comments_number_text( '0', '1', '%' ) );
                  }
                  else {
                    esc_html_e( '1 Comment', 'mongabay' );
                  } ?>
                <div class="social">
                   <?php get_template_part('partials/section', 'social'); ?>
                </div>

            </div>
        </div>
        <?php if ( !empty( $img_url ) )  : ?>
            <div class="row article-cover-image no-gutters">
                <?php
                    if(wp_is_mobile()) {
                        $coversize = 'medium';
                    } elseif(strlen(get_the_post_thumbnail_url($post_id, 'cover-image-retina')) > 0) {
                      $coversize = 'cover-image-retina'; }
                    else {
                      $coversize = 'large';
                    }
                    $thumb_url = get_the_post_thumbnail_url($post_id, $coversize);
                ?>
                <div class="col-lg-12" style="background: url('<?php echo esc_url($thumb_url); ?>'); background-size: cover; background-position: center; width: 100%;"></div>
                
            </div>
        <?php endif; ?>

        <div class="row">
            <div id="main" class="col-lg-8 single">
                <?php if (have_posts()): while (have_posts()) : the_post(); ?>
                    <!-- article -->
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php
                            $mog_count = 0;
                            for ($n=0; $n < 4; $n++) {
                                $single_bullet = get_post_meta($post_id, "mog_bullet_" . $n . "_mog_bulletpoint", true);
                                if (!empty($single_bullet)) {
                                    $mog_count = $mog_count + 1;                
                                }
                            }
                            if ((int)$mog_count > 0 && get_post_meta($post_id, "mog_bullet_0_mog_bulletpoint", true)) {
                                echo '<div class="bulletpoints" data-tie-toggle=".bulletpoints ul"><ul>';
                                for ($i=0; $i < $mog_count; $i++) {
                                    if ($i >= 2) {
                                        echo "<li style='display:none;'><em>".get_post_meta($post_id, "mog_bullet_".$i."_mog_bulletpoint", true)."</em></li>"; 
                                    } else {
                                        echo "<li><em>".get_post_meta($post_id, "mog_bullet_".$i."_mog_bulletpoint", true)."</em></li>";                   
                                    }
                                }
                                echo '</ul>';
                                if ($mog_count > 2) {
                                    echo '<button class="toggle-bulletpoints"><span>' . __('See All Key Ideas', 'mongabay' ) . '</span></button>';
                                }
                                echo '</div>'; 
                            } 
                        ?>
                        <script>
                            (function($) {
                                $(document).ready(function() {
                                    $('.bulletpoints .toggle-bulletpoints').click(function() {
                                        $(this).prev('ul').find('li:nth-child(n+3)').toggle();
                                        if ($(this).prev('ul').find('li:nth-child(n+3)').is(':visible')) {
                                            $(this).addClass('bullets-visible');
                                        } else {
                                            $(this).removeClass('bullets-visible');
                                        }
                                    });
                                });
                            })(jQuery);
                        </script>

                        <?php mongabay_sanitized_content($post_id);?>
                        <div id="single-article-footer">
                            <div id="single-article-meta">
                                <div class="about-editor-translator">
                                    <div class="section-title gap--16">
                                        <h4><?php _e( 'Credits ', 'mongabay' ); ?> </h4>
                                        <div class="divider"></div>
                                    </div>                                   
                                  <div class="author-avatar">
                                    <?php $author_avatar = get_avatar( get_the_author_meta( 'ID' ), 32 ); ?>
                                      <?php if($author_avatar){ echo $author_avatar; 
                                        } else { echo '<span class="meta-author-circle"></span>';
                                        } ?>
                                  </div>
                                  <div class="author-info">
                                    <span>
                                        <?php the_author_posts_link(); ?> 
                                        <?php _e( 'Contributor ', 'mongabay' ); ?>  
                                    </span>
                                  </div>
                                
                            <?php
                                if ($translated_adapted == 'adapted' || $translated_adapted == 'translated' ) {

                                        if ($translated_adapted == 'adapted' && !empty($adaptor)) {
                                            $string_title = 'Adaptor';
                                            $translator_adaptor = $adaptor;
                                        }
                                        elseif ($translated_adapted == 'translated' && !empty($translator)) {
                                            $string_title = 'Translator';
                                            $translator_adaptor = $translator;
                                        }
                                        $translator_adaptor_slug = $translator_adaptor['slug'];
                                        $translator_adaptor_name = $translator_adaptor['name'];
                                        $translator_adaptor_id = $translator_adaptor['term_id'];

                                        $adaptor_avatar = get_term_meta($translator_adaptor_id,'cover_image_url',true);

                                        echo '<div class="author-avatar">';
                                        if (!empty($adaptor_avatar)) {
                                            echo '<img src="' . esc_url($adaptor_avatar) . '" alt="Cover Image">';
                                        } else {
                                            echo '<span class="meta-author-circle"></span>';
                                        }
                                        echo '</div>';
                                        echo '<div class="author-info">';
                                        echo '<a href="'.home_url( '/' ).'by/'. $translator_adaptor_slug .'">'. $translator_adaptor_name .'</a>';
                                        _e( $string_title ,'mongabay');
                                        echo '</div>';
                                }
                            ?>
                                </div><!-- /about-editor-translator -->
                                <div id="single-article-tags">
                                    <?php echo get_the_term_list( $post_id, 'topic', '', ', ' ); ?><br><BR>             
                                    <?php echo get_the_term_list( $post_id, 'location', '', ', ' ); ?><br><BR>
                                    <?php echo get_the_term_list( $post_id, 'entity', '', ', ' ); ?> 
                                </div>
                                
                                
                            </div><!-- / single-article-meta -->      
                        </div><!-- / single-article-footer -->
                        <span class="article-comments"><a href=""></a></span>
                        <?php mongabay_comments(); ?>
                        
                    </article>
                    <!-- /article -->
                <?php endwhile; ?>
            <?php else: ?>
                <!-- article -->
                <article>
                    <h1><?php _e( 'Sorry, nothing to display.', 'mongabay' ); ?></h1>
                </article>
                <!-- /article -->
            <?php endif; ?>
            </div> <!-- /main -->
        </div> <!-- /row -->
    </div> <!-- /container in-column ph--40 -->
    <div class="container ph--40 pv--40 in-column">
        <?php // series_articles_listing(); ?>
        <div class="container full-width pv--40">
            <?php banner('', 'Free and open access to credible information', '', 'Learn more', 'accent full-width pv--56', 'extra-large'); ?>
        </div>
        <div id="section-post-latest" class="container pv--40 gap--20 in-column">
            <div class="container in-row full-width section--headline" style="align-items: center; justify-content: space-between;">
              <h1><?php _e('Latest articles', 'mongabay'); ?></h1>
              <a href="<?php home_url(); ?>/?s=&format=post" class="theme--button primary"><?php _e('All articles', 'mongabay'); ?><span class="icon icon-right"></span></a>
            </div>
            <div class="grid--4 gap--20">
              <?php articles_listing('post', 8, 0, true, 'medium', false, false, null); ?>
            </div>
            <div class="container centered pv--40">
                <a href="<?php home_url(); ?>/?s=&format=post" class="theme--button primary"><?php _e('All articles', 'mongabay'); ?><span class="icon icon-right"></span></a>
            </div>
        </div>
        <div class="container pv--40 full-width">
            <?php banner('', 'Stay updated', 'Delivering news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
        </div>
    </div>
</main>
<?php get_footer(); ?>
