<picture>
    <?php
    $img_desktop = isset($args['images']['desktop']) ? $args['images']['desktop'] : NULL;
    $img_tablet = isset($args['images']['tablet']) ? $args['images']['tablet'] : NULL;
    $img_mobile = isset($args['images']['mobile']) ? $args['images']['mobile'] : NULL;
    ?>
    <?php if ($img_mobile) : ?>
        <source srcset="<?= $img_mobile; ?>.webp" media="(max-width: 599px)" type="image/webp">
        <source srcset="<?= $img_mobile; ?>" media="(max-width: 599px)" type="image/jpeg">
    <?php endif; ?>
    <source srcset="<?= $img_desktop; ?>.webp" media="(min-width: 1200px)" type="image/webp">
    <source srcset="<?= $img_desktop; ?>" media="(min-width: 1200px)" type="image/jpeg">
    <?php if ($img_tablet) : ?>
        <source srcset="<?= $img_tablet; ?>.webp" media="(min-width: 600px)" type="image/webp">
        <source srcset="<?= $img_tablet; ?>" media="(min-width: 600px)" type="image/jpeg">
    <?php endif; ?>
    <img src="<?= $img_desktop; ?>" alt="<?php if($args['alt']) echo $args['alt'];?>"<?= $args['lazy'];?><?= $args['width'];?><?= $args['height'];?>>
</picture>