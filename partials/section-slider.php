<?php
global $wpdb;
if (is_mirror_site(get_current_blog_id())) {
    $wpdb->blogid = 20;
    $wpdb->set_prefix($wpdb->base_prefix);
}

$postnumber = 4;
$extra_filter = [];
if (wp_is_mobile()) $postnumber = 1;

switch (mongabay_subdomain_name()) {
    case 'wildtech':
        $extra_filter = array(
            array(
                'taxonomy' => 'topic',
                'field' => 'slug',
                'terms' => 'technology'
            )
        );
        break;
    case 'srilanka':
        $extra_filter = array(
            array(
                'taxonomy' => 'location',
                'field' => 'slug',
                'terms' => 'sri-lanka'
            )
        );
        break;
    case 'africa':
        $extra_filter = array(
            array(
                'taxonomy' => 'location',
                'field' => 'slug',
                'terms' => 'africa'
            )
        );
        break;
    case 'madagascar':
        $extra_filter = array(
            array(
                'taxonomy' => 'location',
                'field' => 'slug',
                'terms' => 'madagascar'
            )
        );
        break;
    case 'philippines':
        $extra_filter = array(
            array(
                'taxonomy' => 'location',
                'field' => 'slug',
                'terms' => 'philippines'
            )
        );
        break;
}

$args = array(
    'posts_per_page' => $postnumber,
    'meta_query' => array(
        array(
            'key' => 'featured_as',
            'value' => 'featured',
            'compare' => '='
        )
    ),
    'tax_query' => $extra_filter
);

$query = new WP_Query($args);

if ($query->have_posts()) {
    $i = 0;

    while ($query->have_posts()) : $query->the_post();
        $i = $i + 1; ?>

        <?php
        switch ($i) {
            case 1:
                $slideclass = 'col-lg-8';
                break;
            case 2:
                $slideclass = 'col-lg-12 half-height';
                break;
            case 3:
                $slideclass = 'col-lg-6 half-height';
                break;
            case 4:
                $slideclass = 'col-lg-6 half-height';
                break;
        }
        ?>
        <?php if ($i == 1) : ?>
            <div class="<?php echo $slideclass; ?>" style="background: url(<?php the_post_thumbnail_url('medium'); ?>);background-size: cover; background-position: 50% 50%;">
                <a href="<?php the_permalink(); ?>">
                    <div class="slider-headline responsive-title"><?php the_title(); ?></div>
                </a>
            </div>
        <?php endif; ?>
        <?php if ($i == 2) : ?>
            <div class="clearfix"></div>
            <div class="col hidden-md-down">
                <div class="<?php echo $slideclass; ?>" style="background: url(<?php the_post_thumbnail_url('medium'); ?>);background-size: cover;border-left: 5px solid #fff;border-bottom: 5px solid #fff;background-position: 50% 50%;">
                    <a href="<?php the_permalink(); ?>">
                        <div class="slider-headline responsive-title"><?php the_title(); ?></div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if ($i == 3) : ?>
                <div class="<?php echo $slideclass; ?>" style="background: url(<?php the_post_thumbnail_url('medium'); ?>);background-size: cover;border-left: 5px solid white;position: absolute;left: 0;top: 50%;background-position: 50% 50%;">
                    <a href="<?php the_permalink(); ?>">
                        <div class="slider-headline responsive-title"><?php the_title(); ?></div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if ($i == 4) : ?>
                <div class="<?php echo $slideclass; ?>" style="background: url(<?php the_post_thumbnail_url('medium'); ?>);background-size: cover;position: absolute;right: 0;top: 50%;border-left: 5px solid #fff;background-position: 50% 50%;">
                    <a href="<?php the_permalink(); ?>">
                        <div class="slider-headline responsive-title"><?php the_title(); ?></div>
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
        <?php endif; ?>
<?php endwhile;
}

wp_reset_postdata();

?>