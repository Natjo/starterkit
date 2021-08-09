<?php
    $options_fields = get_fields('options');
?>

<header id="header" role="banner">
    <div class="container">
        <a href="/" class="logo">
            <img src="<?= THEME_URL ?>assets/img/logo.svg" alt="<?= get_bloginfo() ?>" width="114" height="52">
        </a>
       
        <div id="nav">
            <nav rol="navigation">
                <?php
                    $link = get_field('params-header-report_link', 'options');
                ?>
                <a href="<?= $link['url'] ?>" class="btn-1 cta" target="<?= $link['target'] ?>"><?= icon('file', 17, 24); ?><?= $link['title'] ?></a>
            </nav>
            
         
        </div>
        <button id="btn-nav" aria-expanded="false" aria-controls="nav">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>