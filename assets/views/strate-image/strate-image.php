<?php
$image = $args['image'];
?>
<section data-view="strate-image">
    <picture>
        <?php if (!empty($image['mobile'])) : ?>
            <source srcset="<?= $image['mobile'] ?>.webp" media="(max-width: 575px)" type="image/webp">
            <source srcset="<?= $image['mobile'] ?>" media="(max-width: 575px)" type="image/jpeg">
        <?php endif; ?>
        <source srcset="<?= $image['desktop'] ?>.webp" media="(min-width: 1200px)" type="image/webp">
        <source srcset="<?= $image['desktop'] ?>" media="(min-width: 1200px)" type="image/jpeg">
        <?php if (!empty($image['tablet'])) : ?>
            <source srcset="<?= $image['tablet'] ?>.webp" media="(min-width: 576px)" type="image/webp">
            <source srcset="<?= $image['tablet'] ?>" media="(min-width: 576px)" type="image/jpeg">
        <?php endif; ?>
        <img src="<?= $image['desktop'] ?>" alt="" loading="lazy" width="<?= $image['width'] ?>" height="<?= $image['height'] ?>">
    </picture>
</section>