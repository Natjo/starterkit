<div class="block-image">
	<picture>
		<?php if (!empty($args['mobile'])) : ?>
			<source srcset="<?= $args['mobile'] ?>.webp" media="(max-width: 575px)" type="image/webp">
			<source srcset="<?= $args['mobile'] ?>" media="(max-width: 575px)" type="image/jpeg">
		<?php endif; ?>
		<source srcset="<?= $args['desktop'] ?>.webp" media="(min-width: 1200px)" type="image/webp">
		<source srcset="<?= $args['desktop'] ?>" media="(min-width: 1200px)" type="image/jpeg">
		<?php if (!empty($args['tablet'])) : ?>
			<source srcset="<?= $args['tablet'] ?>.webp" media="(min-width: 576px)" type="image/webp">
			<source srcset="<?= $args['tablet'] ?>" media="(min-width: 576px)" type="image/jpeg">
		<?php endif; ?>
		<img src="<?= $args['desktop'] ?>" alt="" loading="lazy" width="<?= $args['width'] ?>" height="<?= $args['height'] ?>">
	</picture>
</div>