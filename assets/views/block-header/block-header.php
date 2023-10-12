<header class="block-header container">
	<?php if (!empty($args['title'])) : ?>
		<h2 class="tl2"><?= $args['title'] ?></h2>
	<?php endif; ?>
	<?php if (!empty($args['intro'])) : ?>
		<div class="intro"><?= $args['intro'] ?></div>
	<?php endif; ?>
</header>