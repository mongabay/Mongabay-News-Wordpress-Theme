<?php
/**
 * Single view template.
 * @package AMP
 */

/**
 * Context.
 *
 * @var AMP_Post_Template $this
 */

$this->load_parts( [ 'html-start' ] );
?>

<?php $this->load_parts( [ 'header' ] ); ?>

<article class="amp-wp-article">
	<header class="amp-wp-article-header">
		<h1 class="amp-wp-title"><?php echo $this->get( 'post_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
		<?php
		/**
		 * Filters the template parts loaded in the header area of the AMP legacy post template.
		 *
		 * @since 0.4
		 * @param string[] Templates to load.
		 */
		$this->load_parts( apply_filters( 'amp_post_article_header_meta', [ 'meta-author', 'meta-time' ] ) );
		?>
	</header>

	<?php $this->load_parts( [ 'featured-image' ] ); ?>
        
	<div class="amp-wp-article-content">
    <?php 
      $post_id = get_the_ID();
        $mog_count = 0;
        for ($n=0;$n < 4;$n++){
            $single_bullet=get_post_meta($post_id,"mog_bullet_".$n."_mog_bulletpoint",true);
            if(!empty($single_bullet)) {
                $mog_count=$mog_count+1;                
            }
        }
        if((int)$mog_count > 0 && get_post_meta($post_id,"mog_bullet_0_mog_bulletpoint",true)){
            echo '<div class="bulletpoints"><ul>';
            for($i=0;$i<$mog_count;$i++){

                echo "<li><em>".get_post_meta($post_id,"mog_bullet_".$i."_mog_bulletpoint",true)."</em></li>";                   
            }
            echo "</ul></div>"; 
        } 
      ?>
		<?php echo $this->get( 'post_amp_content' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>

	<footer class="amp-wp-article-footer">
		<?php
		/**
		 * Filters the template parts to load in the footer area of the AMP legacy post template.
		 *
		 * @since 0.4
		 * @param string[] Templates to load.
		 */
		$this->load_parts( apply_filters( 'amp_post_article_footer_meta', [ 'meta-taxonomy', 'meta-comments-link' ] ) );
		?>
	</footer>
</article>

<?php $this->load_parts( [ 'footer' ] ); ?>

<?php
$this->load_parts( [ 'html-end' ] );
