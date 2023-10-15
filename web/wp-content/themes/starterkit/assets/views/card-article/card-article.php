<li class="item" data-view="card-article">
    <?php if (!empty($args['tag'])) : ?>
        <span><?= $args['tag'] ?></span>
    <?php endif ?>

    <h3><?= $args['title'] ?></h3>

    <?php if (!empty($args['date'])) : ?>
        <time datetime="<?= $args['datetime'] ?>"><?= $args['date'] ?></time>
    <?php endif ?>

    <a href="<?= $args['text'] ?>">More</a>

    <?php if (!empty($args['date'])) : ?>
        <p>
            <?= $args['text'] ?>
        </p>
    <?php endif ?>

    <?php if (!empty($args['images'])) : ?>
        <?php picture($args['images']) ?>
    <?php endif ?>
</li>