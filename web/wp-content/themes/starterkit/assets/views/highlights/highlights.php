
<section data-view="highlights">
	<header class="container">
		<?php if ('' !== $args['title']): ?>
			<h2 class="tl2"><?= $args['title'] ?></h2>
		<?php endif ?>
	
		<div class="slider-control">
			<button class="btn-prev btn-picto" aria-label="Preview slide"><?= icon("arrow-left", 10, 16); ?></button>
			<button class="btn-next btn-picto" aria-label="Next slide"><?= icon("arrow-right", 10, 16); ?></button>
		</div>
	</header>

	<div class="slider-container">
		<div class="container">
			<ul class="highlights slider">
				<?php foreach ($args['items'] as $item): ?>
					<?php views('card-highlight', $item);?>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
</section>