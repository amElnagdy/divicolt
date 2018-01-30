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
        if (et_pb_is_allowed( 'page_options' )) {
	        if (post_type_supports($post_type, 'editor') and function_exists('et_single_settings_meta_box')) {
		        $obj= get_post_type_object( $post_type );
		        add_meta_box('et_settings_meta_box', sprintf(__('Divi %s Settings', 'Divi'), $obj->labels->singular_name), 'et_single_settings_meta_box', $post_type, 'side', 'high');
	        }
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

/*
 * Modified function to copy the Setting Box from Posts to other post types
 * @since 1.5
*/
function et_single_settings_meta_box( $post ) {
	$post_id = get_the_ID();

	wp_nonce_field( basename( __FILE__ ), 'et_settings_nonce' );

	$page_layout = get_post_meta( $post_id, '_et_pb_page_layout', true );

	$side_nav = get_post_meta( $post_id, '_et_pb_side_nav', true );

	$project_nav = get_post_meta( $post_id, '_et_pb_project_nav', true );

	$post_hide_nav = get_post_meta( $post_id, '_et_pb_post_hide_nav', true );
	$post_hide_nav = $post_hide_nav && 'off' === $post_hide_nav ? 'default' : $post_hide_nav;

	$show_title = get_post_meta( $post_id, '_et_pb_show_title', true );

	if ( is_rtl() ) {
		$page_layouts = array(
			'et_left_sidebar'    => esc_html__( 'Left Sidebar', 'Divi' ),
			'et_right_sidebar'   => esc_html__( 'Right Sidebar', 'Divi' ),
			'et_full_width_page' => esc_html__( 'Fullwidth', 'Divi' ),
		);
	} else {
		$page_layouts = array(
			'et_right_sidebar'   => esc_html__( 'Right Sidebar', 'Divi' ),
			'et_left_sidebar'    => esc_html__( 'Left Sidebar', 'Divi' ),
			'et_full_width_page' => esc_html__( 'Fullwidth', 'Divi' ),
		);
	}

	$layouts = array(
		'light' => esc_html__( 'Light', 'Divi' ),
		'dark'  => esc_html__( 'Dark', 'Divi' ),
	);
	$post_bg_color  = ( $bg_color = get_post_meta( $post_id, '_et_post_bg_color', true ) ) && '' !== $bg_color
		? $bg_color
		: '#ffffff';
	$post_use_bg_color = get_post_meta( $post_id, '_et_post_use_bg_color', true )
		? true
		: false;
	$post_bg_layout = ( $layout = get_post_meta( $post_id, '_et_post_bg_layout', true ) ) && '' !== $layout
		? $layout
		: 'light'; ?>

    <p class="et_pb_page_settings et_pb_page_layout_settings">
        <label for="et_pb_page_layout" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Page Layout', 'Divi' ); ?>: </label>

        <select id="et_pb_page_layout" name="et_pb_page_layout">
			<?php
			foreach ( $page_layouts as $layout_value => $layout_name ) {
				printf( '<option value="%2$s"%3$s>%1$s</option>',
					esc_html( $layout_name ),
					esc_attr( $layout_value ),
					selected( $layout_value, $page_layout, false )
				);
			} ?>
        </select>
    </p>
    <p class="et_pb_page_settings et_pb_side_nav_settings" style="display: none;">
        <label for="et_pb_side_nav" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Dot Navigation', 'Divi' ); ?>: </label>

        <select id="et_pb_side_nav" name="et_pb_side_nav">
            <option value="off" <?php selected( 'off', $side_nav ); ?>><?php esc_html_e( 'Off', 'Divi' ); ?></option>
            <option value="on" <?php selected( 'on', $side_nav ); ?>><?php esc_html_e( 'On', 'Divi' ); ?></option>
        </select>
    </p>
    <p class="et_pb_page_settings">
        <label for="et_pb_post_hide_nav" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Hide Nav Before Scroll', 'Divi' ); ?>: </label>

        <select id="et_pb_post_hide_nav" name="et_pb_post_hide_nav">
            <option value="default" <?php selected( 'default', $post_hide_nav ); ?>><?php esc_html_e( 'Default', 'Divi' ); ?></option>
            <option value="no" <?php selected( 'no', $post_hide_nav ); ?>><?php esc_html_e( 'Off', 'Divi' ); ?></option>
            <option value="on" <?php selected( 'on', $post_hide_nav ); ?>><?php esc_html_e( 'On', 'Divi' ); ?></option>
        </select>
    </p>

	<?php foreach (get_post_types() as $post_type) {
    if ( $post->post_type == $post_type ) : ?>
        <p class="et_pb_page_settings et_pb_single_title" style="display: none;">
            <label for="et_single_title" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Post Title', 'Divi' ); ?>: </label>

            <select id="et_single_title" name="et_single_title">
                <option value="on" <?php selected( 'on', $show_title ); ?>><?php esc_html_e( 'Show', 'Divi' ); ?></option>
                <option value="off" <?php selected( 'off', $show_title ); ?>><?php esc_html_e( 'Hide', 'Divi' ); ?></option>
            </select>
        </p>

        <p class="et_divi_quote_settings et_divi_audio_settings et_divi_link_settings et_divi_format_setting et_pb_page_settings">
            <label for="et_post_use_bg_color" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Use Background Color', 'Divi' ); ?></label>
            <input name="et_post_use_bg_color" type="checkbox" id="et_post_use_bg_color" <?php checked( $post_use_bg_color ); ?> />
        </p>

        <p class="et_post_bg_color_setting et_divi_format_setting et_pb_page_settings">
            <input id="et_post_bg_color" name="et_post_bg_color" class="color-picker-hex" type="text" maxlength="7" placeholder="<?php esc_attr_e( 'Hex Value', 'Divi' ); ?>" value="<?php echo esc_attr( $post_bg_color ); ?>" data-default-color="#ffffff" />
        </p>

        <p class="et_divi_quote_settings et_divi_audio_settings et_divi_link_settings et_divi_format_setting">
            <label for="et_post_bg_layout" style="font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Text Color', 'Divi' ); ?>: </label>
            <select id="et_post_bg_layout" name="et_post_bg_layout">
				<?php
				foreach ( $layouts as $layout_name => $layout_title )
					printf( '<option value="%s"%s>%s</option>',
						esc_attr( $layout_name ),
						selected( $layout_name, $post_bg_layout, false ),
						esc_html( $layout_title )
					);
				?>
            </select>
        </p>
	<?php endif; }

	if ( 'project' === $post->post_type ) : ?>
        <p class="et_pb_page_settings et_pb_project_nav" style="display: none;">
            <label for="et_project_nav" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Project Navigation', 'Divi' ); ?>: </label>

            <select id="et_project_nav" name="et_project_nav">
                <option value="off" <?php selected( 'off', $project_nav ); ?>><?php esc_html_e( 'Hide', 'Divi' ); ?></option>
                <option value="on" <?php selected( 'on', $project_nav ); ?>><?php esc_html_e( 'Show', 'Divi' ); ?></option>
            </select>
        </p>
	<?php endif;
}

function divicolt_post_settings_save_details( $post_id, $post ){
	global $pagenow;

	if ( 'post.php' !== $pagenow || ! $post || ! is_object( $post ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	$post_type = get_post_type_object( $post->post_type );
	if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['et_settings_nonce'] ) || ! wp_verify_nonce( $_POST['et_settings_nonce'], basename( __FILE__ ) ) ) {
		return;
	}

	if ( isset( $_POST['et_post_use_bg_color'] ) )
		update_post_meta( $post_id, '_et_post_use_bg_color', true );
	else
		delete_post_meta( $post_id, '_et_post_use_bg_color' );

	if ( isset( $_POST['et_post_bg_color'] ) )
		update_post_meta( $post_id, '_et_post_bg_color', sanitize_text_field( $_POST['et_post_bg_color'] ) );
	else
		delete_post_meta( $post_id, '_et_post_bg_color' );

	if ( isset( $_POST['et_post_bg_layout'] ) )
		update_post_meta( $post_id, '_et_post_bg_layout', sanitize_text_field( $_POST['et_post_bg_layout'] ) );
	else
		delete_post_meta( $post_id, '_et_post_bg_layout' );

	if ( isset( $_POST['et_single_title'] ) )
		update_post_meta( $post_id, '_et_pb_show_title', sanitize_text_field( $_POST['et_single_title'] ) );
	else
		delete_post_meta( $post_id, '_et_pb_show_title' );

	if ( isset( $_POST['et_pb_post_hide_nav'] ) )
		update_post_meta( $post_id, '_et_pb_post_hide_nav', sanitize_text_field( $_POST['et_pb_post_hide_nav'] ) );
	else
		delete_post_meta( $post_id, '_et_pb_post_hide_nav' );

	if ( isset( $_POST['et_project_nav'] ) )
		update_post_meta( $post_id, '_et_pb_project_nav', sanitize_text_field( $_POST['et_project_nav'] ) );
	else
		delete_post_meta( $post_id, '_et_pb_project_nav' );

	if ( isset( $_POST['et_pb_page_layout'] ) ) {
		update_post_meta( $post_id, '_et_pb_page_layout', sanitize_text_field( $_POST['et_pb_page_layout'] ) );
	} else {
		delete_post_meta( $post_id, '_et_pb_page_layout' );
	}

	if ( isset( $_POST['et_pb_side_nav'] ) ) {
		update_post_meta( $post_id, '_et_pb_side_nav', sanitize_text_field( $_POST['et_pb_side_nav'] ) );
	} else {
		delete_post_meta( $post_id, '_et_pb_side_nav' );
	}
}
add_action( 'save_post', 'divicolt_post_settings_save_details', 10, 2 );