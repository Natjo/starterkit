<header data-view="hero-homepage">
	<div class="container">
		<h1><?= $args['title'] ?></h1>

		<p class="chapo"><?= $args['chapo'] ?></p>
	</div>

	<?php picture($args["images"], "bg", true); ?>
	
</header>