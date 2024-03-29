<head>
    <meta charset="<?php bloginfo('charset'); ?>">

    <?= lsd_seo(); ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
   
    <link rel="icon" href="<?= THEME; ?>assets/favicon/favicon.ico" sizes="any">
    <link rel="icon" href="<?= THEME; ?>assets/favicon/icon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="<?= THEME; ?>assets/favicon/apple-touch-icon.png">
    <link rel="manifest" href="<?= THEME; ?>assets/favicon/site.webmanifest">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <?php wp_head(); ?>
    
    <link rel='stylesheet' href='<?= THEME; ?>assets/styles.css?v=<?= VERSION; ?>'/>

    <?php foreach ($args as $value) echo '<link href="'.THEME.$value.'?v='.VERSION.'" rel="stylesheet" media="screen">';?>
</head>
