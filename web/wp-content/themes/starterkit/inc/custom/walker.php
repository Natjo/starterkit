<?php

class menu_header_Walker extends Walker_Nav_Menu
{

    // @see Walker::start_el()
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $sPicto = get_field('menu-header-pictos', $item->ID);
        $picto =  icon("icon-" . $sPicto, 13, 13);
        $caret = $args->walker->has_children ? icon("caret", 12, 7) : "";

        $title = $item->title;
        $permalink = $item->url;
        $target = !empty($item->target) ? ' rel="noreferrer"  target="' . $item->target . '"' : '';
        $output .= '<li>';
        $output .= '<a href="' . $permalink . '" ' . $target . '>';
        $output .= $sPicto != "none" ? $picto : "";
        $output .= $title;
        $output .= '</a>' . $caret;
    }
}
