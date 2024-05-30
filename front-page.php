<?php get_header(); ?>
<?php
$queried_object = get_queried_object();
$section = get_query_var('section');
$firstvar = get_query_var('nc1');
$secondvar = get_query_var('nc2');
if ($section == 'moved') {
    $post = $wp_query->posts[0];
    $id = $post->ID;
    $permalink = get_permalink($id);
    wp_redirect($permalink, 301);
    exit;
}
$line_end = '';
if ($section == 'list' && !empty($firstvar) && empty($secondvar)) {
    $item1 = get_terms(array('topic', 'location'), array('slug' => $firstvar));
    $title = $item1[0]->name;
    $line_end = ' News';
    $type = !empty(get_terms(array('location'), array('slug' => $firstvar))) ? 'location' : 'list';
}

if ($section == 'list' && !empty($firstvar) && !empty($secondvar)) {
    $item1 = get_terms(array('topic', 'location'), array('slug' => $firstvar));
    $item2 = get_terms(array('topic', 'location'), array('slug' => $secondvar));
    $title1 = $item1[0]->name;
    $title2 = $item2[0]->name;
    $title = $title1 . ' and ' . $title2;
    $line_end = ' News';
}
if (empty($section) && mongabay_subdomain_name() !== 'wildtech') {
    $title = 'Environmental headlines';
    $description = "Reader-supported news and inspiration from nature's frontline. Mongabay is a non-profit.";
}
if (mongabay_subdomain_name() == 'wildtech' && empty($section)) {
    $title = 'Conservation Technology News';
    $description = 'Wildtech provides news and information on how the conservation community can better leverage technology. Mongabay is a non-profit.';
}
if (mongabay_subdomain_name() == 'kidsnews' && empty($section)) {
    $title = 'Conservation News for Kids';
    $description = 'Mongabay Kids has a new home: Please check out <b><A href="https://kids.mongabaydev.wpengine.com/">kids.mongabaydev.wpengine.com</a></b>.';
}
if (mongabay_subdomain_name() == 'jp' && empty($section)) {
    $title = 'jp.mongabaydev.wpengine.com';
    $description = '環境保全の独立ニュース局.';
}
if (mongabay_subdomain_name() == 'es' && empty($section)) {
    $title = 'Titulares ambientales';
    $description = 'Mongabay Latam es un medio de comunicación que cubre las historias más importantes de Latinoamérica. Informamos con rigor, claridad e independencia.';
}
if (mongabay_subdomain_name() == 'fr' && empty($section)) {
    $title = 'Actualités environnementales';
    $description = 'Nouvelles indépendantes sur la conservation.';
}
if (mongabay_subdomain_name() == 'brasil' && empty($section)) {
    $title = 'Notícias ambientais';
    $description = 'A Mongabay é uma agência de notícias sobre conservação e ciência ambiental sem fins lucrativos. Nosso objetivo é inspirar, educar e informar.';
}
if (mongabay_subdomain_name() == 'de' && empty($section)) {
    $title = 'Umwelt-News';
    $description = 'Nachrichten und Inspiration aus der Natur.';
}
if (mongabay_subdomain_name() == 'it' && empty($section)) {
    $title = 'it.mongabaydev.wpengine.com';
    $description = 'Mongabay cerca di suscitare interesse e considerazione per terre e animali selvaggi e, allo stesso tempo, di esaminare lʼimpatto delle nuove tendenze del clima, della tecnologia, dellʼeconomia e della finanza sulla conservazione e lo sviluppo.';
}
if (mongabay_subdomain_name() == 'africa' && empty($section)) {
    $title = 'Africa Conservation News';
    $description = 'Conservation news and information from Africa. Mongabay is a non-profit.';
}
if (mongabay_subdomain_name() == 'philippines' && empty($section)) {
    $title = 'Philippines environmental news';
    $description = 'Conservation news and information from the Philippines. Mongabay is a non-profit.';
}
if (mongabay_subdomain_name() == 'madagascar' && empty($section)) {
    $title = 'Madagascar Conservation News';
    $description = 'Conservation news and information from Madagascar. Mongabay is a non-profit.';
}
if (mongabay_subdomain_name() == 'hindi' && empty($section)) {
    $title = 'पर्यावरण से जुड़ी सुर्खियां';
    $description = 'प्रकृति और पर्यावरण से जुड़े मुद्दों की खोज खबर। मोंगाबे एक गैर-लाभकारी संस्था है।';
}
if (mongabay_subdomain_name() == 'cn' && empty($section)) {
    $title = 'cn.mongabaydev.wpengine.com';
    $description = '环境新闻';
}
?>
<main role="main">
    <div class="row">
        <div id="main" class="col-lg-8">
            <div class="tag-line">
                <h1><?php echo _e($title, 'mongabay'); ?><?php _e($line_end, 'mongabay'); ?></h1>
                <?php echo ($section == 'list' && !empty($firstvar) && empty($secondvar)) ? '<a class="series-rss" href="' . esc_url(home_url('/')) . 'feed/?post_type=post&feedtype=bulletpoints&' . $type . '=' . $item1[0]->slug . '"><svg class="icon"><use xlink:href="#rss"></use></svg></a>' : ''; ?>
                <p><?php echo _e($description, 'mongabay'); ?></p>
            </div>
            <!-- section -->
            <section>
                <div id="post-wrapper-news" class="row" data-columns>
                    <?php
                    $std_query = new WP_Query(array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'posts_per_page' => $posts_per_page,
                        'paged' => get_query_var('paged')
                    ));

                    if ($std_query->have_posts()) {
                        while ($std_query->have_posts()) {
                            $std_query->the_post();
                            get_template_part('loop');
                        }
                        wp_reset_postdata();
                    } else {
                        esc_html_e('Sorry, no posts matched your criteria.');
                    }

                    ?>
                </div>

                <div class="counter">
                    <?php mongabay_pagination(); ?>
                </div>

            </section>
        </div>
        <?php
        if (!wp_is_mobile()) {
            get_sidebar();
        }
        ?>
    </div>
    <!-- /row -->
</main>
<!-- /container -->
<?php get_footer(); ?>