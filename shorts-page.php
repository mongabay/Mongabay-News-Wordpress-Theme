<?php

/**
 * Template Name: Shorts Page
 *
 * @package WordPress
 * @subpackage Mongabay_v2
 * @since Mongabay 1.0
 */
get_header(); ?>

<?php
$args = array(
  'post_type' => 'post',
  'posts_per_page' => 8,
  'offset' => 0,
  'post_status' => 'publish',
);

$query = new WP_Query($args);
$post_counter = 0;


function article_card($id, $is_large = false, $idx)
{
  $backgrounds = array(
    1 => ' bg-theme-secondary',
    2 => ' bg-theme-gray',
    3 => ' bg-theme-accent',
    4 => ' bg-brand-color',
    5 => ' bg-theme-gray',
    6 => '',
    7 => ' bg-theme-secondary',
    8 => ' bg-theme-accent',
  );
?>
  <div class="article--container">
    <a class="shorts-trigger">
      <div class="title headline rounded-top <?php echo $is_large ? 'ph--80 pv--80' : 'ph--20 pv--20';
                                              echo $backgrounds[$idx]; ?>">
        <?php echo $is_large ? '<h2>' : '<h3>' ?>
        <?php the_title(); ?>
        <?php echo $is_large ? '</h2>' : '</h3>' ?>
      </div>
      <div class="post-excerpt hidden">
        <?php mongabay_excerpt(100); ?>
      </div>
      <?php the_post_thumbnail($id, 'medium', array('class' => 'rounded-bottom')); ?>
    </a>
  </div>
<? }

$banner = '
<div class="banner gap--20 ph--20 pv--20 outlined">
<div class="inner">
  <div class="title">
    <h1 class="lh--tight">Subscribe</h1>
  </div>
  <div class="copy">
    Stay informed with news and inspiration from nature’s frontline.
  </div>
  <a href="" class="theme--button primary full-width">
    Newsletter<span class="icon icon-right"></span>
  </a>
</div>
</div>';

?>
<div class="container ph--40 pv--40 in-column">

  <?php if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      $post_counter++;

      if ($post_counter === 1) {
        echo '<div class="container in-row gap--40">'; //first grid start
        echo '<div class="column--60">'; // first grid first column
        article_card(get_the_ID(), true, $post_counter);
        echo '</div>'; // close first grid first column
      }
      if ($post_counter === 2) {
        echo '<div class="column--40 in-column gap--40">';
        article_card(get_the_ID(), false, $post_counter);
      }

      if ($post_counter === 3) {
        article_card(get_the_ID(), false, $post_counter);
        echo '</div>'; // close first grid second column
        echo '</div>'; // close first grid
      }

      if ($post_counter === 4) {
        echo '<div class="container in-row gap--40 pv--40">'; //second grid start
        echo '<div class="column--40 in-column gap--40">'; //second grid first column
        article_card(get_the_ID(), false, $post_counter);
      }

      if ($post_counter === 5) {
        article_card(get_the_ID(), false, $post_counter);
        echo '</div>'; // close second grid first column
      }

      if ($post_counter === 6) {
        echo '<div class="column--60">'; //second grid second column
        article_card(get_the_ID(), true,  $post_counter);
        echo '</div>'; // close second grid second column
        echo '</div>'; // close second grid
      }

      if ($post_counter === 7) {
        echo '<div class="container in-row gap--40">'; //third grid start
        echo '<div class="column--40 in-column gap--40">'; //third grid first column
        article_card(get_the_ID(), false, $post_counter);
        echo $banner;
        echo '</div>'; // close third grid first column
      }

      if ($post_counter === 8) {
        echo '<div class="column--60">'; //third grid second column
        article_card(get_the_ID(), true, $post_counter);
        echo '</div>'; // close third grid second column
        echo '</div>'; // close third grid
      }
    } ?>
    <dialog id="shorts-dialog">
      <div class="dialog-content">
        <div class="dialog-header">
          <button class="close-button">
            <span class="icon icon-close"></span>
          </button>
        </div>
        <div class="dialog-body">
          <div class="container in-column gap--40">
            <div class="title headline">
              <h2></h2>
            </div>
            <div class="post-content">
              <p></p>
            </div>
          </div>
        </div>
      </div>
    </dialog>
    <div class="container centered pv--40">
      <a href="<?php home_url(); ?>/?s=&formats=short-articles" class="theme--button primary"><?php _e('All shorts', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>

    <script>
      const shortsTriggers = document.querySelectorAll('.shorts-trigger');
      const shortsDialog = document.querySelector('#shorts-dialog');
      const closeDialogButton = document.querySelector('.close-button');

      shortsTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
          const title = e.target.closest('.shorts-trigger').querySelector('.title').textContent;
          const excerpt = e.target.closest('.shorts-trigger').querySelector('.post-excerpt').textContent;

          const dialogTitle = shortsDialog.querySelector('.dialog-body .title h2');
          const dialogContent = shortsDialog.querySelector('.dialog-body .post-content p');

          dialogTitle.textContent = title;
          dialogContent.textContent = excerpt;

          shortsDialog.showModal();
        });
      });

      closeDialogButton.addEventListener('click', () => {
        shortsDialog.close();
      });
    </script>
  <?php } else {
    _e('No short articles found', 'mongabay');
  }
  ?>
</div>

<?php inspiration_banner(); ?>

<?php get_footer(); ?>