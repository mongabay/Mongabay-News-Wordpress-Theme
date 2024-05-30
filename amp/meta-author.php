<?php $bylines = wp_get_post_terms($this->ID, 'byline'); ?>
<?php if (!empty($bylines)) : ?>
	<div class="amp-wp-meta amp-wp-byline">
		<?php if (is_array($bylines)) : ?>
			<?php $counter = 1; ?>
			<?php foreach ($bylines as $byline) : ?>
				<span class="amp-wp-author author vcard">
					<?php echo esc_html($byline->name); ?><?php echo $counter == count($bylines) || count($bylines) == 1 ? "" : ", "; ?>
				</span>
				<?php $counter++; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
<?php endif; ?>