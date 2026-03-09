<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * REST API Taxonomy Handler for Course Categories and Tags
 */
class Lenxel_REST_API_Taxonomy
{
    public function __construct()
    {
        // Modify the default taxonomy args to use standard WordPress capabilities
        add_filter('register_taxonomy_args', array($this, 'modify_taxonomy_args'), 10, 2);
        
        // Map meta capabilities - intercept WordPress capability checks
        add_filter('map_meta_cap', array($this, 'map_taxonomy_capabilities'), 10, 4);
        
        // Register AJAX endpoints for creating categories/tags
        add_action('init', array($this, 'register_ajax_endpoints'));
    }

    /**
     * Modify taxonomy registration arguments
     * Note: WordPress handles REST API authentication natively.
     * Use proper permission_callback in REST routes instead of bypassing auth.
     */
    public function modify_taxonomy_args($args, $taxonomy)
    {
        if ($taxonomy === 'course-category' || $taxonomy === 'course-tag') {
            // Ensure show_in_rest is enabled
            $args['show_in_rest'] = true;
            
            // Use edit_posts capability that most users have
            $args['capabilities'] = array(
                'manage_terms' => 'edit_posts',
                'edit_terms'   => 'edit_posts',
                'delete_terms' => 'edit_posts',
                'assign_terms' => 'edit_posts',
            );
        }
        
        return $args;
    }

    /**
     * Map meta capabilities for taxonomy operations
     * Maps course taxonomy capabilities to standard WordPress capabilities
     * that users already have based on their roles
     */
    public function map_taxonomy_capabilities($caps, $cap, $user_id, $args)
    {
        // Map course taxonomy term capabilities to standard post editing capabilities
        $taxonomy_caps_map = array(
            'edit_term'   => 'edit_posts',
            'delete_term' => 'edit_posts',
            'assign_term' => 'edit_posts',
        );
        
        // Check if this is a course taxonomy capability
        foreach ($taxonomy_caps_map as $tax_cap => $mapped_cap) {
            if (strpos($cap, $tax_cap) === 0) {
                // Get the term ID if provided
                $term_id = isset($args[0]) ? $args[0] : 0;
                
                if ($term_id) {
                    $term = get_term($term_id);
                    if ($term && !is_wp_error($term)) {
                        if ($term->taxonomy === 'course-category' || $term->taxonomy === 'course-tag') {
                            // Map to standard WordPress capability instead of granting access
                            return array($mapped_cap);
                        }
                    }
                } else {
                    // Creating new term - check context
                    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 15);
                    foreach ($backtrace as $trace) {
                        if (isset($trace['function']) && $trace['function'] === 'wp_insert_term') {
                            if (isset($trace['args'][1]) && 
                                ($trace['args'][1] === 'course-category' || $trace['args'][1] === 'course-tag')) {
                                // Map to standard WordPress capability
                                return array($mapped_cap);
                            }
                            break;
                        }
                    }
                }
            }
        }
        
        // Return original caps for everything else
        return $caps;
    }

    /**
     * Register AJAX endpoints for taxonomy creation
     */
    public function register_ajax_endpoints()
    {
        // For logged in users
        add_action('wp_ajax_lenxel_create_course_category', array($this, 'ajax_create_course_category'));
        add_action('wp_ajax_lenxel_create_course_tag', array($this, 'ajax_create_course_tag'));
    }

    /**
     * AJAX handler for creating course categories
     */
    public function ajax_create_course_category()
    {
        // Verify nonce
        check_ajax_referer('lenxel_create_taxonomy', 'nonce');
        
        // Check if user is logged in
        if (!is_user_logged_in()) {
            wp_send_json_error('You must be logged in to create categories');
            return;
        }
        
        // Check user capabilities
        if (!current_user_can('edit_posts') && !current_user_can('manage_options')) {
            wp_send_json_error('You do not have permission to create categories');
            return;
        }

        // Validate and sanitize input
        $name = sanitize_text_field($_POST['name'] ?? '');
        if (empty($name)) {
            wp_send_json_error('Category name is required');
            return;
        }

        $description = sanitize_textarea_field($_POST['description'] ?? '');
        $parent = intval($_POST['parent'] ?? 0);

        // Create the term
        $term = wp_insert_term($name, 'course-category', array(
            'description' => $description,
            'parent' => $parent
        ));

        if (is_wp_error($term)) {
            wp_send_json_error($term->get_error_message());
            return;
        }

        // Get the full term object
        $term_obj = get_term($term['term_id'], 'course-category');
        
        $response = array(
            'id' => $term_obj->term_id,
            'name' => $term_obj->name,
            'description' => $term_obj->description,
            'count' => $term_obj->count,
            'parent' => $term_obj->parent,
            'slug' => $term_obj->slug,
            'taxonomy' => 'course-category'
        );
        
        wp_send_json_success($response);
    }

    /**
     * AJAX handler for creating course tags
     */
    public function ajax_create_course_tag()
    {
        // Verify nonce
        check_ajax_referer('lenxel_create_taxonomy', 'nonce');
        
        // Check if user is logged in
        if (!is_user_logged_in()) {
            wp_send_json_error('You must be logged in to create tags');
            return;
        }
        
        // Check user capabilities
        if (!current_user_can('edit_posts') && !current_user_can('manage_options')) {
            wp_send_json_error('You do not have permission to create tags');
            return;
        }

        // Validate and sanitize input
        $name = sanitize_text_field($_POST['name'] ?? '');
        if (empty($name)) {
            wp_send_json_error('Tag name is required');
            return;
        }

        $description = sanitize_textarea_field($_POST['description'] ?? '');

        // Create the term
        $term = wp_insert_term($name, 'course-tag', array(
            'description' => $description
        ));

        if (is_wp_error($term)) {
            wp_send_json_error($term->get_error_message());
            return;
        }

        // Get the full term object
        $term_obj = get_term($term['term_id'], 'course-tag');
        
        $response = array(
            'id' => $term_obj->term_id,
            'name' => $term_obj->name,
            'description' => $term_obj->description,
            'count' => $term_obj->count,
            'slug' => $term_obj->slug,
            'taxonomy' => 'course-tag'
        );
        
        wp_send_json_success($response);
    }
}
