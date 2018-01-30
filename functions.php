<?php
/*
	DiviColt Functions
*/
function divicolt_enqueue_styles() {
wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

wp_enqueue_style( 'child-style',
get_stylesheet_directory_uri() . '/style.css',
array( 'parent-style' ),
wp_get_theme()->get('Version')
);
}
add_action( 'wp_enqueue_scripts', 'divicolt_enqueue_styles' );

include('inc/builder.php'); // Enable The Builder for All Custom Post Types
/* Comment the following line if you want to enable only the builder */
include('inc/box.php'); // Enable Divi Settings for All Custom Post Type
/* Add your custom functions after this line*/
