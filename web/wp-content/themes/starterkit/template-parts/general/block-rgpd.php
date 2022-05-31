<?php
$cookiesFields = get_fields('options');
?>

<div id="rgpd-manage" role="dialog" aria-modal="true" aria-labelledby="rgpd-title" aria-describedby="rgpd-desc" data-nonce="<?= wp_create_nonce("securite_nonce_rgpd"); ?>" data-action="rgpd">
    <div class="box">
        <button type="button" class="btn-close btn-picto" aria-label="<?= __('Fermer la fenêtre de paramètres des cookies', 'lsd_lang'); ?>"><?= icon("close", 15, 15); ?></button>
        <?php if (!empty($cookiesFields['params-cookies-title'])) : ?>
            <h1 id="rgpd-title"><?= $cookiesFields['params-cookies-title'] ?></h1>
        <?php endif; ?>
        <?php if (!empty($cookiesFields['params-cookies-intro'])) : ?>
            <div id="rgpd-desc" class="rte">
                <p><?= $cookiesFields['params-cookies-intro'] ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($cookiesFields['params-cookies-groups'])) : ?>
            <?php foreach ($cookiesFields['params-cookies-groups'] as $cookieGroup) : ?>
                <?php
                $dataCookies = array();
                if (!empty($cookieGroup['cookies'])) {
                    foreach ($cookieGroup['cookies'] as $index => $cookie) {
                        array_push($dataCookies, $cookie['name']);
                    }
                }
                ?>
                <section>
                    <h2><?= $cookieGroup['title']; ?></h2>
                    <p><?= $cookieGroup['text']; ?></p>
                    <?php if (!empty($cookieGroup['cookies'])) : ?>
                        <details>
                            <summary>
                                <?= icon("arrow-down", 19, 19); ?>
                                <h3>Afficher les details</h3>
                                <?php if ($cookieGroup['activation'] === true) : ?>
                                    <div class="checkbox-container checked">
                                        <span>ACCEPTER</span>
                                        <input type="checkbox" id="pref-<?= $cookieGroup['id']; ?>" name="pref-<?= $cookieGroup['id']; ?>" value="<?= $cookieGroup['id']; ?>" aria-label="<?= __('Gestion des cookies', 'lsd_lang'); ?> <?= $cookieGroup['title']; ?>" data-cookies="<?= implode(",", $dataCookies); ?>" data-accept="<?= __('Activé', 'lsd_lang'); ?>" data-denied="<?= __('Désactivé', 'lsd_lang'); ?>">
                                        <span>REFUSER</span>
                                    </div>
                                <?php endif; ?>
                            </summary>
                            <ul>
                                <?php foreach ($cookieGroup['cookies'] as $index => $cookie) : ?>
                                    <li>
                                        <strong><?= $cookie['name']; ?></strong>
                                        <?php if ($cookie['domain']) : ?>
                                            <small>(<?= $cookie['domain']; ?>)</small>
                                        <?php endif; ?>
                                        <?php if (!empty($cookie['description'])) : ?>
                                            <br>
                                            <?= $cookie['description']; ?>
                                        <?php endif; ?>
                                    <li>
                                <?php endforeach; ?>
                            </ul>
                        </details>
                    <?php endif; ?>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>

        <button type="button" class="btn-save btn-1">
            <div class="picto-btn-1 picto-save"><?= icon("arrow-down", 19, 19); ?></div>
            <?= __('Accepter', 'lsd_lang'); ?>
        </button>
    </div>
</div>

<div id="rgpd-modal" aria-hidden="true">
    <?php if (!empty($cookiesFields['params-cookies-title'])) : ?>
    <h2><?= $cookiesFields['params-cookies-title'] ?></h2>
    <?php endif;?>
    <?php if (!empty($cookiesFields['params-cookies-intro'])) : ?>
    <p><?= $cookiesFields['params-cookies-intro'] ?></p>
    <?php endif; ?>
    <button type="button" class="rgpd-manage-link btn-1">
        <div class="picto-btn-1"><?= icon("arrow-down", 19, 19); ?></div>
        <?= __('Paramétrer mes cookies', 'lsd_lang'); ?>
    </button>
    <div class="button-right-container">
        <button type="button" class="btn-accept btn-picto"><?= icon("check", 18, 14); ?></button>
        <button type="reset" class="btn-refuse"><?= icon("close", 14, 14); ?></button>
    </div>
</div>