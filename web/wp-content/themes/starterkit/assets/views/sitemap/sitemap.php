<section data-view="sitemap">
	<div class="container">
		<ul>
			<?php foreach ($args as $item) : ?>
				<li>
					<a href="<?= $item['url']; ?>"><?= $item['label']; ?></a>
					<ul>
						<?php foreach ($item["childs"] as $item_child) : ?>
							<li>
								<a href="<?= $item_child['url']; ?>"><?= $item_child['label']; ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>