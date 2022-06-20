<?php

$argsPosts = getSearchCpt([
    "card" => 'article'
]);

?>
<section class="container" data-view="strate-actualites">
    <ul>
        <?php if (!empty($argsPosts['items'])) : ?>
            <?php foreach ($argsPosts['items'] as $item) : ?>
                <?php views('card-article', $item); ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <?php pager($argsPosts['pager'], "/" . get_post_field('post_name') . "/page/"); ?>
</section>