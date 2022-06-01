<?php $aViews = get_field('views', get_the_ID()); ?>
<?php if (isset($aViews) && !empty($aViews)) : ?>

    <?php
    foreach ($aViews as $aView) :
   
        if ($aView['acf_fc_layout'] == 'strate-wysiwyg') :
            views('strate-wysiwyg', array(
                'text' => $aView['text'],
            ));
        endif;
    endforeach;
    ?>

<?php endif; ?>