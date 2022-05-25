<?php $content = ob_get_clean(); ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<?php get_header(null, $links); ?>

<body <?php body_class(); ?>>
<?php 
get_header('nav');
echo $content;
get_footer();
?>

<script id="appjs" type="module" src="<?= THEME_URL; ?>assets/app.js" defer 
data-params_data='<?= paramsData(); ?>' 
data-views='<?= views_observe()?>'></script>
</body>
</html>