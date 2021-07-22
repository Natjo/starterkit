<section data-view="issues">
	<header class="container">
		<?php if ('' !== $args['title']): ?>
			<h2 class="tl2"><?= $args['title'] ?></h2>
		<?php endif ?>
		<div class="slider-control">
			<button class="btn-prev btn-picto light" aria-label="Preview slide"><?= icon("arrow-left", 10, 16); ?></button>
			<button class="btn-next btn-picto light" aria-label="Next slide"><?= icon("arrow-right", 10, 16); ?></button>
		</div>
	</header>

	<div class="slider-container">
		<div class="container">
			<ul class="slider">
				<?php foreach ($args['articles'] as $item):?>
				<?php views('card-article', $item);?>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
</section>