<section class="strate<?= options($args) ?>" data-view="strate-push_articles">
    <?php views('block-header', $args['block-header']); ?>

    <div class="container">
        <ul>
            <?php foreach ($args['items'] as $item) : ?>
                <li>
                    <?php views('card-article', $item); ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?= setlink($args['cta'],"link-1")?>
    </div>
</section>