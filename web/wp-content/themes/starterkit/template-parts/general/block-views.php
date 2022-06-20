<?php $aViews = get_field('views', get_the_ID()); ?>

<?php if (isset($aViews) && !empty($aViews)) : ?>

    <?php
    foreach ($aViews as $aView) :
        if ($aView['acf_fc_layout'] == 'strate-wysiwyg') :
            views('strate-wysiwyg', array(
                'text' => $aView['text'],
            ));

        elseif ($aView['acf_fc_layout'] == 'strate-image') :
            views('strate-image', array(
                'image' => [
                    'desktop' => lsd_get_thumb($aView['img-desktop'], 'image-desktop'),
                    'mobile' => lsd_get_thumb($aView['img-mobile'], 'image-mobile'),
                    'tablet' => lsd_get_thumb($aView['img-tablet'], 'image-tablet'),
                    'width' => '1200',
                    'height' => '800'
                ]
            ));
        endif;

    endforeach;
    ?>

<?php endif; ?>