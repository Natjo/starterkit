<?php
$options_fields = get_fields('options');
?>

<header id="header" role="banner">
    <div class="container header-content">
        <a href="/" class="logo">
            <?= icon('logo', 49, 49) ?>
        </a>

        <button id="btn-nav">Menu</button>

        <nav id="nav" role="navigation">
            <?php
            wp_nav_menu(array(
                'container' => false,
                'theme_location' => 'menu-header',
                'menu_class'  => false,
                'items_wrap' => '<ul>%3$s</ul>',
                'walker' => new Walker_Nav_Menu()
            ));
            ?>
        </nav>

    </div>
</header>