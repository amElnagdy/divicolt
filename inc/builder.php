<?php
/**
 * Enable Divi builder for all custom post types
 *
 * @package DiviColt
 * @since 1.1
 */
add_filter('et_builder_post_types', 'divicolt_post_types');
add_filter('et_fb_post_types','divicolt_post_types' ); // Enable Divi Visual Builder on the custom post types
function divicolt_post_types($post_types)
{

    foreach (get_post_types() as $post_type) {
        if (!in_array($post_type, $post_types) and post_type_supports($post_type, 'editor')) {
            $post_types[] = $post_type;
        }
    }
    return $post_types;
}