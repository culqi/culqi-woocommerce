<div class="<?php echo esc_html($class_box); ?>">
	<div class="<?php echo esc_html($class_title); ?>"><?php echo esc_html($title); ?></div>
	<p><?php echo esc_html($content); ?></p>
	<?php if( ! empty( $link_button ) ) : ?>
		<a href="<?php echo esc_html($link_button); ?>" class="<?php echo esc_html($class_button); ?>">
			<?php echo esc_html($text_button); ?>
		</a>
	<?php endif; ?>
</div>
