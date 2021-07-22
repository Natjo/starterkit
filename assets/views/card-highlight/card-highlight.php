<li class="item" data-view="card-highlight">
	<a href="<?= $args['link'] ?>" target="_blank">
		<?php
		picture(array(
			"images" => $args['images'],
			"width" => 314,
			"lazy" => true
		));
		?>

		<?= icon("arrow", 20, 16) ?>

		<?php if ('' !== $args['title']) : ?>
			<h3><?= $args['title'] ?></h3>
		<?php endif ?>

		<?php if ('' !== $args['date']) : ?>
			<time><?= $args['date'] ?></time>
		<?php endif ?>

		<?php if ('' !== $args['text']) : ?>
			<p><?= $args['text'] ?></p>
		<?php endif ?>
	</a>
</li>