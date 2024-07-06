<?php
function shorts_article_card($id, $is_large = false, $title_class = '', $idx = 0)
{
  $backgrounds = array(
    1 => ' bg-theme-secondary',
    2 => ' bg-theme-gray',
    3 => ' bg-theme-accent',
    4 => ' bg-brand-color',
    5 => ' bg-theme-gray',
    6 => ' bg-theme-gray',
    7 => ' bg-theme-secondary',
    8 => ' bg-theme-accent',
    9 => ' bg-theme-gray',
  );

  $link_url = get_post_meta($id, 'article_link', true)
  // $backgrounds = array(
  //   1 => ' bg-theme-secondary',
  //   2 => ' bg-theme-gray',
  //   3 => ' bg-theme-gray',
  //   4 => ' bg-theme-accent',
  //   5 => ' bg-brand-color',
  //   6 => ' bg-theme-accent',
  //   7 => ' bg-theme-gray',
  //   8 => ' bg-theme-secondary',
  //   9 => ' bg-theme-gray',
  // );
?>
  <div class="article--container">
    <a class="shorts-trigger" data-url="<?php echo $link_url; ?>">
      <div class="title headline rounded-top <?php echo $is_large ? 'ph--80 pv--80' : 'ph--40 pv--40';
                                              echo $backgrounds[$idx]; ?>">
        <?php echo $is_large ? '<h2 class="' . $title_class . '">' : '<h3 class="' . $title_class . '">' ?>
        <?php the_title(); ?>
        <?php echo $is_large ? '</h2>' : '</h3>' ?>
      </div>
      <div class="post-meta hidden">
        <span class="byline"><?php echo getPostBylines($id); ?></span>
        <span class="date"><?php the_time('j M Y'); ?></span>
      </div>
      <div class="post-excerpt hidden">
        <?php the_content(); ?>
      </div>
      <?php the_post_thumbnail($id, 'medium', array('class' => 'rounded-bottom')); ?>
    </a>
  </div>
<? }
?>