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

<script>var paramsData = <?php paramsData(); ?>;paramsData.views = <?= views_observe()?>;document.addEventListener('DOMContentLoaded', (event) => {const script = document.createElement('script');script.type = 'module';script.src = `<?= THEME_URL; ?>assets/app.js`;script.setAttribute('defer', '');document.body.appendChild(script);});</script>

</body>
</html>