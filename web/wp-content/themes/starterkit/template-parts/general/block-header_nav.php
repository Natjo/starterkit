<?php
$options_fields = get_fields('options');
?>

<div id="quick_access">
    <div class="container">
        <a href="#main">Accès au contenu principal</a>
        <a href="#footer">Accès au pied de page</a>
    </div>
</div>

<header id="header" role="banner">
    <div class="container-fluid">
        <a href="/" class="logo" aria-label="Aller à la page d'accueil">
            <?= icon('logo', 40, 40) ?>
        </a>

        <button id="btn-nav" aria-expanded="false" aria-controls="nav-panel">Menu</button>

        <div id="nav-panel" >
            <nav id="nav" role="navigation" aria-label="Access to navigation">
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
    </div>
</header>