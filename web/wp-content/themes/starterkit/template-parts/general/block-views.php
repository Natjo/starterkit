<?php $aViews = get_field('views', get_the_ID()); ?>

<?php
if (isset($aViews) && !empty($aViews)) {
    foreach ($aViews as $aView) {
        switch ($aView['acf_fc_layout']) {
            case 'strate-wysiwyg':
                views('strate-wysiwyg', Strates_Helper::strate_wysiwyg($aView));
                break;
            case 'strate-image':
                views('strate-image', Strates_Helper::strate_image($aView));
                break;
            case 'strate-image_text':
                views('strate-image_text', Strates_Helper::strate_image_text($aView));
                break;
        }
    }
}
?>