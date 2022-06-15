<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />

    <link rel="icon" href="<?= THEME_URL; ?>assets/favicon/favicon.ico" sizes="any">
    <link rel="icon" href="<?= THEME_URL; ?>assets/favicon/icon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="<?= THEME_URL; ?>assets/favicon/apple-touch-icon.png">
    <link rel="manifest" href="<?= THEME_URL; ?>assets/favicon/site.webmanifest">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <?php wp_head(); ?>
    
    <link rel='stylesheet' href='<?= THEME_URL; ?>assets/styles.css'  media='screen' />

    <?php foreach ($args as $value) echo '<link href="'.THEME_URL.$value.'" rel="stylesheet" media="screen">';?>
</head>    
