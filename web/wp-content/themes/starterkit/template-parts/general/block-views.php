<?php $aViews = get_field('views', get_the_ID()); ?>
<?php if (isset($aViews) && !empty($aViews)): ?>

    <?php
    foreach ($aViews as $aView) :
        if( $aView['acf_fc_layout'] == 'hero-home' ):
            views('hero-home',array(
                'images' => array(
                    'desktop' => $aView['hero-home-bg-desktop']['sizes']['hero-desktop'],
                    'tablet' => $aView['hero-home-bg-desktop']['sizes']['hero-tablet'],
                    'mobile' => $aView['hero-home-bg-mobile']['sizes']['hero-mobile'],
                )
            ));
        elseif ($aView['acf_fc_layout'] == 'interview'):
            views('interview', array(
                'quote' => $aView['interview-quote'],
                'author' => $aView['interview-author'],
                'function' => $aView['interview-function'],  
                'title' => $aView['interview-title'],
                'link' => $aView['interview-link'],
                 'images' => array(
                    'desktop' => $aView['interview-img-desktop']['sizes']['interview-desktop']
                )
            ));
        elseif ($aView['acf_fc_layout'] == 'home-issues'):
            $items = array();
            foreach ($aView['home-issues-articles'] as $item){
                $img_id = get_post_thumbnail_id($item);
                $category = get_the_category($item);
                array_push($items, array(
                    'title' =>  get_the_title($item),
                    'link' => get_the_permalink($item),
                    'text' => get_the_excerpt($item),
                    'category_name' => $category[0]->name,
                    'category_class' => 'tpl-' . $category[0]->slug,
                    'images' => array(
                        'desktop' => wp_get_attachment_image_src($img_id, 'card')[0]
                    )
                ));
            }
            views('issues', array( 
                'title' => $aView['home-issues-title'],
                'articles' => $items
            ));
        elseif ($aView['acf_fc_layout'] == 'highlights'):
            $items = array();
            foreach ($aView['highlights-items'] as $item){
                array_push($items, array(
                    'title' =>  $item['title'],
                    'date' =>  $item['date'],
                    'text' =>  $item['text'],
                    'images' => array(
                        'desktop' =>  $item['image-desktop']['sizes']['highlights-desktop'],
                    )
                ));
            }
            views('highlights', array( 
                'title' => $aView['highlights-title'],
                'items' =>  $items  
            ));
        elseif ($aView['acf_fc_layout'] == 'wysiwyg'):
            views('wysiwyg', array( 
                'text' => $aView['text'],
            ));
        endif;
    endforeach; 
    ?>

<?php endif; ?>