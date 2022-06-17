<footer id="footer" role="contentinfo" class="container-fluid">

    <small>© copyright lonsdale 2022</small>

    <nav id="nav-footer">
        <?php
        wp_nav_menu(array(
            'container' => false,
            'theme_location' => 'menu-footer',
            'menu_class'  => false,
            'items_wrap' => '<ul>%3$s</ul>',
            'walker' => new Walker_Nav_Menu()
        ));
        ?>
    </nav>

</footer>