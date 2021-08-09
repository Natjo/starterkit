<?php
    $cookiesFields = get_fields('options');
?>

<div id="rgpd-manage" data-nonce="<?= wp_create_nonce("securite_nonce_rgpd"); ?>" data-action="rgpd">
	<div class="box">
        <button type="button" class="btn-close"></button>

		<header>
            <?php if(!empty($cookiesFields['params_cookies_title'])) : ?>
            <h1><?= $cookiesFields['params_cookies_title'] ?></h1>
            <?php endif;
            if(!empty($cookiesFields['params_cookies_intro'])) : ?>
            <p><?= $cookiesFields['params_cookies_intro'] ?></p>
            <?php endif; ?>
		</header>

        <?php
        if( !empty($cookiesFields['params_cookies_groups'])) {
            foreach ($cookiesFields['params_cookies_groups'] as $cookieGroup) {
                ?>
		<section>

			<div class="content">
                <h2><?php echo $cookieGroup['title']; ?></h2>
                <p><?php echo $cookieGroup['text']; ?></p>
            </div>
            <?php
                $dataCookies = '';
                if( !empty($cookieGroup['cookies'])) :
                    $isFirst = true;
                    $count = count($cookieGroup['cookies']);
                    foreach ($cookieGroup['cookies'] as $index => $cookie) :
                        $dataCookies .=  $cookie['name'];
                        if($index !== $count - 1 ) $dataCookies .= ',';
                        if (!empty($cookie['description'])) {
                            $isFirst = true;
                        } else {
                            $isFirst = false;
                        }
                    endforeach;
                endif; 
            ?>
				<div class="details">
                <?php
                    $dataCookies = '';
                    if( !empty($cookieGroup['cookies'])) :?>

				<button type="button" class="btn-detail"><?php echo __('Afficher les détails', 'lsd_lang'); ?></button>
				<div class="panel">
                    <?php
                        $isFirst = true;
                        $count = count($cookieGroup['cookies']);

                        foreach ($cookieGroup['cookies'] as $index => $cookie) :
                            $dataCookies .=  $cookie['name'];
                            if($index !== $count - 1 ) $dataCookies .= ',';
                            if ($isFirst) {
                                echo '<p>';   
                            } 

                            ?>
                            <strong><?php echo $cookie['name']; ?></strong>
                            <?php if($cookie['domain']) : ?>
                            <small>(<?php echo $cookie['domain']; ?>)</small>
                            <?php endif; ?>
                            <?php
                            if (!empty($cookie['description'])) {
                                echo $cookie['description'];
                                echo '</p>';
                                $isFirst = true;
                            } else {
                                $isFirst = false;
                            }
                            ?>
                            <?php
                        endforeach;
                    ?>
                </div>
                <?php endif; ?>
			</div>
            <?php
                if($cookieGroup['activation'] === true){
            ?>
            <input type="checkbox" id="rgpd_<?= $cookieGroup['id']; ?>" name="rgpd_<?= $cookieGroup['id']; ?>" value="<?= $cookieGroup['id']; ?>" data-cookies="<?= $dataCookies; ?>">
            <?php
                }
            ?>
    
            
	

		</section>
        <?php
            }
        }
        ?>

		<footer>
            <button type="button" class="btn-save btn-primary"><?= __('Sauvegarder','lsd_lang'); ?></button>
		</footer>
       
	</div>
</div>

<div id="rgpd-modal">

    <div class="text">
        <?php if(!empty($cookiesFields['params_cookies_title'])) : ?>
        <h2><?= $cookiesFields['params_cookies_title'] ?></h2>
        <?php endif;
        if(!empty($cookiesFields['params_cookies_intro'])) : ?>
        <p><?= $cookiesFields['params_cookies_intro'] ?></p>
        <?php endif; ?>
        <button type="button" class="btn-manage"><?= __('Paramétrer mes cookies', 'lsd_lang'); ?></button>
    </div>

    <div class="action">
        <button type="reset" class="btn-refuse"><?= __('Refuser', 'lsd_lang'); ?></button>
        <button type="button" class="btn-accept"><?= __('Accepter', 'lsd_lang'); ?></button>
    </div>

</div>

