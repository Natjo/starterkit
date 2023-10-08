<?php $content = ob_get_clean(); ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<?php get_header(null, $links); ?>

<body <?php body_class(); ?>>
    <?php
    get_header();
    echo $content;
    get_footer();
    ?>

    <script id="appjs" async type="module" src="<?= THEME; ?>assets/app.js?v=<?= VERSION ?>" data-ajax_url="<?= AJAX_URL ?>" data-theme_url="<?= THEME_URL ?>" data-gtag_key="<?= GTAG_KEY ?>" data-version="<?= VERSION ?>" data-views-js='<?= views_observe() ?>'></script>
</body>

</html>