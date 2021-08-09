<?php
    $pageTitle = (is_front_page() ? get_bloginfo('description') : wp_title('', false) ) . ' | ' . get_bloginfo('name') ;
    $siteDescription = 'description';
?>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php echo $pageTitle; ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <meta name="description" content="<?php echo $siteDescription; ?>">
    
    <!-- <link rel="apple-touch-icon" sizes="180x180" href="<?= THEME_URL; ?>/assets/favicon/apple-touch-icon.png"> -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= THEME_URL; ?>/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= THEME_URL; ?>/assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?= THEME_URL; ?>/assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="<?= THEME_URL; ?>/assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <?php wp_head(); ?>
    <?php foreach ($args as $value) echo '<link href="'.THEME_URL.$value.'" rel="stylesheet" media="screen">';?>
</head>