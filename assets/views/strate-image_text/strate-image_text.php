<section class="strate<?= options($args) ?>" data-view="strate-image_text">

	<?php views('block-header', $args['block-header']); ?>

	<div class="container strate-content">
		<?php views('block-image', $args['block-image']); ?>

		<?php views('block-text', $args['block-text']); ?>
	</div>
</section>