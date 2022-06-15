<?php $aViews = get_field('views', get_the_ID()); ?>

<?php if (isset($aViews) && !empty($aViews)) : ?>

    <?php
    foreach ($aViews as $aView) :

        if ($aView['acf_fc_layout'] == 'strate-wysiwyg') :
            views('strate-wysiwyg', array(
                'text' => $aView['text'],
            ));
        elseif ($aView['acf_fc_layout'] == 'strate-articles') :
            views('strate-articles', array(
                'items' => $aView['strate-articles-items'],
            ));
        endif;

    endforeach;
    ?>

<?php endif; ?>