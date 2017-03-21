<?php
/**
 * Display Divi Settings for all custom post types
 *
 * @package DiviColt
 * @since 1.1
 */

add_action('add_meta_boxes', 'divicolt_add_meta_box');
function divicolt_add_meta_box()
{
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'editor') and function_exists('et_single_settings_meta_box')) {
            $obj= get_post_type_object( $post_type );
            add_meta_box('et_settings_meta_box', sprintf(__('Divi %s Settings', 'Divi'), $obj->labels->singular_name), 'et_single_settings_meta_box', $post_type, 'side', 'high');
        }
    }
}

add_action('admin_head', 'divicolt_admin_js');
function divicolt_admin_js()
{
    $s = get_current_screen();
    if (!empty($s->post_type) and $s->post_type != 'page' and $s->post_type != 'post') {
        ?>
        <script>
            jQuery(function ($) {
                $('#et_pb_layout').insertAfter($('#et_pb_main_editor_wrap'));
            });
        </script>
        <?php
    }
}