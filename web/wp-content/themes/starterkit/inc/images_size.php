<?php

// Remove unused format
function disable_unused_format($sizes) {
    unset($sizes['medium']);
    unset($sizes['large']);
    unset($sizes['2048x2048']);
	unset($sizes['1536x1536']);
	unset($sizes['thumbnail_example']);
    unset($sizes['medium_large']);
	return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'disable_unused_format');


add_image_size('hero-desktop', 2000, 1125, true);
add_image_size('hero-tablet', 1200, 675, true);
add_image_size('hero-mobile', 750, 1154, true);

add_image_size('interview-desktop', 570, 0, true);

add_image_size('card', 734, 434, true);

add_image_size('highlights-desktop', 530, 0, false);

/*
add_image_size('report-desktop', 1140, 1200, true);

add_image_size('hero-desktop', 1440, 810, true);
add_image_size('hero-mobile', 750, 1154, true);

add_image_size('image-desktop', 1546, 0, false);
add_image_size('image-mobile', 768, 0, false);

add_image_size('point-of-view-desktop', 265, 939, true);

add_image_size('image-legended-text-desktop', 1806, 1096, true);
add_image_size('image-legended-text-mobile', 915, 548, true);

add_image_size('related', 248, 176, true);*/