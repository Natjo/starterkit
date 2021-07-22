<li class="item <?= $args["category_class"] ?>" data-view="card-article">
	<a href="<?= $args["link"] ?>" title="<?= $args["title"] ?>">
		<?php
		picture(array(
			"images" => $args['images'],
			"width" => 734,
			"height" => 434,
			"lazy" => true
		));
		?>
		<div class="content">
			<?php if (isset($args["category_name"])) : ?>
				<div class="cat"><?= $args["category_name"] ?></div>
			<?php endif ?>

			<?php if ('' !== $args["title"]) : ?>
				<h3><?= $args["title"] ?></h3>
			<?php endif ?>

			<?php if (isset($args["text"]) && '' !== $args["text"]) : ?>
				<p><?= $args["text"] ?></p>
			<?php endif ?>
		</div>
	</a>
</li>