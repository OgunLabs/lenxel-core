<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Normalize legacy `course_id` => `courses_id` but only when on the course builder admin page
function wprp_normalize_course_id() {
    if ( is_admin() && isset( $_GET['page'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) === 'create-course' ) {
        if ( isset( $_GET['course_id'] ) && ! isset( $_GET['courses_id'] ) ) {
            // Sanitize and validate course_id (must be numeric)
            $course_id = absint( $_GET['course_id'] );
            if ( $course_id > 0 ) {
                // Redirect to use courses_id
                $url = remove_query_arg('course_id', sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ));
                $url = add_query_arg('courses_id', rawurlencode( (string) $course_id ), $url);
                wp_safe_redirect( esc_url_raw( $url ) );
                exit;
            }
        }
    }
}
add_action('admin_init', 'wprp_normalize_course_id');

require_once LENXEL_PLUGIN_DIR . 'includes/class-wprp.php';

function wprp_init_plugin() {
    Lenxel_WPRP::instance();
}
add_action( 'init', 'wprp_init_plugin' );