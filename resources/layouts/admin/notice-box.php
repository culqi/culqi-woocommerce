<div class="<?php echo $class_box; ?>">
	<div class="<?php echo $class_title; ?>"><?php echo $title; ?></div>
	<p><?php echo $content; ?></p>
	<?php if( ! empty( $link_button ) ) : ?>
		<a href="<?php echo $link_button; ?>" class="<?php echo $class_button; ?>">
			<?php echo $text_button; ?>
		</a>
	<?php endif; ?>
</div>