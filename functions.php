<?php
/*
	DiviColt Functions
*/
function divicolt_enqueue_styles() {
	$parent_style = 'divi-style';
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'divicolt_enqueue_styles' );

include('inc/builder.php'); // Enable The Builder for All Custom Post Types
/* Comment the following line if you want to enable only the builder */
include('inc/box.php'); // Enable Divi Settings for All Custom Post Type
/* Add your custom functions after this line*/