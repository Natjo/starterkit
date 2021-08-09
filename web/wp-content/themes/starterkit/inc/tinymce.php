<?php
// Tiny MCE Custom Formats
add_filter('mce_buttons_2', 'juiz_mce_buttons_2');

if (!function_exists('juiz_mce_buttons_2')) {
    function juiz_mce_buttons_2($buttons)
    {
        array_unshift($buttons, 'styleselect');

        return $buttons;
    }
}
add_filter('tiny_mce_before_init', 'juiz_mce_before_init');
if (!function_exists('juiz_mce_before_init')) {
    function juiz_mce_before_init($styles)
    {
        $style_formats = array(
            array(
                'title' => 'small',
                'inline' => 'small'
            ),
            array(
                'title' => 'intro',
                'selector' => 'p',
                'classes' => 'intro'
            )
            ,
            array(
                'title' => 'image',
                'block' => 'div',
                'classes' => 'img',
                'wrapper' => true,
            )
        );
        $styles['style_formats'] = json_encode($style_formats);

        return $styles;
    }
}
if (!function_exists('juiz_init_editor_styles')) {
    add_action('after_setup_theme', 'juiz_init_editor_styles');
    function juiz_init_editor_styles()
    {
        add_editor_style('assets/styles.css');
    }
}

// add class rte to tiny mce body
function wpse_editor_styles_class($settings)
{
    $settings['body_class'] = 'rte';
    return $settings;
}
add_filter('tiny_mce_before_init', 'wpse_editor_styles_class');

// Set format
function remove_h1_from_heading($args) {
	$args['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5';
	return $args;
}
add_filter('tiny_mce_before_init', 'remove_h1_from_heading' );

// Color set
function my_mce4_options($init) {

    $custom_colours = '
        "00ffff", "Color 1 name",
        "00ffff", "Color 2 name",
        "00ffff", "Color 3 name",
        "00ffff", "Color 4 name",
        "FF0000", "Color 5 name",
        "FF99CC", "Color 6 name",
        "CCFFFF", "Color 7 name"
    ';

    // build colour grid default+custom colors
    $init['textcolor_map'] = '['.$custom_colours.']';

    // change the number of rows in the grid if the number of colors changes
    // 8 swatches per row
    $init['textcolor_rows'] = 1;

    return $init;
}
add_filter('tiny_mce_before_init', 'my_mce4_options');

// Removes buttons from the first row of the tiny mce editor
function jivedig_remove_tiny_mce_buttons_from_editor( $buttons ) {

    $remove_buttons = array(
        'strikethrough',
        'hr', // horizontal line
        'wp_more', // read more link
        'spellchecker',
        'dfw', // distraction free writing mode
        'wp_adv', // kitchen sink toggle (if removed, kitchen sink will always display)
    );
    foreach ( $buttons as $button_key => $button_value ) {
        if ( in_array( $button_value, $remove_buttons ) ) {
            unset( $buttons[ $button_key ] );
        }
    }
    return $buttons;
}
add_filter( 'mce_buttons', 'jivedig_remove_tiny_mce_buttons_from_editor');

// Removes buttons from the second row (kitchen sink) of the tiny mce editor
function jivedig_remove_tiny_mce_buttons_from_kitchen_sink( $buttons ) {

    $remove_buttons = array(
        //'formatselect', // format dropdown menu for <p>, headings, etc
        'underline',
        'alignjustify',
        //'forecolor', // text color
        //'pastetext', // paste as text
        'removeformat', // clear formatting
        'charmap', // special characters
        'outdent',
        'indent',
        //'undo',
        //'redo',
        'wp_help', // keyboard shortcuts
        'hr', // horizontal line
    );
    foreach ( $buttons as $button_key => $button_value ) {
        if ( in_array( $button_value, $remove_buttons ) ) {
            unset( $buttons[ $button_key ] );
        }
    }
    return $buttons;
}
add_filter( 'mce_buttons_2', 'jivedig_remove_tiny_mce_buttons_from_kitchen_sink');

// Add sup and sub
function my_mce_buttons_2($buttons) {	
	/**
	 * Add in a core button that's disabled by default
	 */
	$buttons[] = 'superscript';
	$buttons[] = 'subscript';

	return $buttons;
}
add_filter('mce_buttons_2', 'my_mce_buttons_2');