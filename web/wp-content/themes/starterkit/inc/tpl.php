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

<script id="appjs" async type="module" src="<?= THEME_URL; ?>assets/app.js?v=<?= VERSION ?>"  data-params_data='<?= paramsData(); ?>' data-views-js='<?= views_observe()?>'></script>
</body>
</html>