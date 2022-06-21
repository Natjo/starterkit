<?php
$options_fields = get_fields('options');
?>

<header id="header" role="banner">

    <a href="/" class="logo">
        <?= icon('logo', 49, 49) ?>
    </a>

    <button id="btn-nav" aria-expanded="false" aria-controls="nav-panel">Menu</button>

    <div id="nav-panel" aria-label="Access to navigation">
        <nav id="nav" role="navigation">
            <?php
            wp_nav_menu(array(
                'container' => false,
                'theme_location' => 'menu-header',
                'menu_class'  => false,
                'items_wrap' => '<ul class="nav-links">%3$s</ul>',
                'walker' => new menu_header_Walker()
            ));
            ?>
        </nav>
    </div>

</header>