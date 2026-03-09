<?php

/**
 * Manage Course Related Logic for Lenxel Core
 *
 * @package LenxelCore
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Course Class for Lenxel Core
 */
class Lenxel_Course
{

    /**
     * Course Post Type
     */
    const COURSE_POST_TYPE = 'courses';

    /**
     * Course Price type
     */
    const PRICE_TYPE_FREE = 'free';
    const PRICE_TYPE_PAID = 'paid';
    const PRICE_TYPE_SUBSCRIPTION = 'subscription';

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('save_post_' . self::COURSE_POST_TYPE, array($this, 'save_course_meta'), 10, 2);
        add_action('wp_ajax_lenxel_save_topic', array($this, 'save_topic'));
        add_action('wp_ajax_lenxel_delete_topic', array($this, 'delete_topic'));
        add_action('wp_ajax_lenxel_create_new_draft_course', array($this, 'ajax_create_new_draft_course'));
        add_action('wp_ajax_lenxel_course_list', array($this, 'ajax_course_list'));
        add_action('wp_ajax_lenxel_create_course', array($this, 'ajax_create_course'));
        add_action('wp_ajax_lenxel_course_details', array($this, 'ajax_course_details'));
        add_action('wp_ajax_lenxel_update_course', array($this, 'ajax_update_course'));
    }

    /**
     * Save course meta data
     */
    public function save_course_meta($post_ID, $post)
    {
        // Validate nonce if available
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'lenxel_course_meta')) {
            return;
        }

        // Save course price type
        $price_type = sanitize_text_field($_POST['course_price_type'] ?? '');
        if ($price_type) {
            update_post_meta($post_ID, '_lenxel_course_price_type', $price_type);
        }

        // Save course level
        $course_level = sanitize_text_field($_POST['_lenxel_course_level'] ?? '');
        if ($course_level) {
            update_post_meta($post_ID, '_lenxel_course_level', $course_level);
        }

        // Save course duration
        $course_duration = isset($_POST['course_duration']) && is_array($_POST['course_duration']) ? array_map('sanitize_text_field', wp_unslash($_POST['course_duration'])) : array();
        if (!empty($course_duration)) {
            $duration = array(
                'hours' => sanitize_text_field($course_duration['hours'] ?? ''),
                'minutes' => sanitize_text_field($course_duration['minutes'] ?? '')
            );
            update_post_meta($post_ID, '_lenxel_course_duration', $duration);
        }

        // Save additional course data
        $this->save_additional_course_data($post_ID);

        do_action('lenxel_course_saved', $post_ID, $post);
    }

    /**
     * Save additional course data
     */
    private function save_additional_course_data($post_ID)
    {
        $fields = array(
            'course_benefits' => '_lenxel_course_benefits',
            'course_requirements' => '_lenxel_course_requirements',
            'course_target_audience' => '_lenxel_course_target_audience',
            'course_material_includes' => '_lenxel_course_material_includes'
        );

        foreach ($fields as $field => $meta_key) {
            $value = wp_kses_post($_POST[$field] ?? '');
            if (!empty($value)) {
                update_post_meta($post_ID, $meta_key, $value);
            }
        }
    }

    /**
     * Create new draft course via AJAX
     */
    public function ajax_create_new_draft_course()
    {
        check_ajax_referer('lenxel_ajax_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(__('Permission denied', 'lenxel-core'));
        }

        $course_id = wp_insert_post(array(
            'post_title' => __('New Course', 'lenxel-core'),
            'post_type' => self::COURSE_POST_TYPE,
            'post_status' => 'draft',
            'post_name' => 'new-course'
        ));

        if (is_wp_error($course_id)) {
            wp_send_json_error($course_id->get_error_message());
        }

        update_post_meta($course_id, '_lenxel_course_price_type', self::PRICE_TYPE_FREE);

        $edit_link = admin_url('post.php?post=' . $course_id . '&action=edit');

        wp_send_json_success(array(
            'message' => __('Draft course created', 'lenxel-core'),
            'course_id' => $course_id,
            'edit_link' => $edit_link
        ));
    }

    /**
     * Get course list via AJAX
     */
    public function ajax_course_list()
    {
        check_ajax_referer('lenxel_ajax_nonce', 'nonce');

        $limit = absint($_POST['limit'] ?? 10);
        $offset = absint($_POST['offset'] ?? 0);
        $search = sanitize_text_field($_POST['search'] ?? '');

        $args = array(
            'post_type' => self::COURSE_POST_TYPE,
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'offset' => $offset,
            's' => $search
        );

        $courses_query = new WP_Query($args);
        $courses = array();

        if ($courses_query->have_posts()) {
            while ($courses_query->have_posts()) {
                $courses_query->the_post();
                $courses[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'status' => get_post_status(),
                    'date' => get_the_date(),
                    'author' => get_the_author()
                );
            }
            wp_reset_postdata();
        }

        wp_send_json_success(array(
            'courses' => $courses,
            'total' => $courses_query->found_posts
        ));
    }

    /**
     * Create course via AJAX
     */
    public function ajax_create_course()
    {
        check_ajax_referer('lenxel_ajax_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(__('Permission denied', 'lenxel-core'));
        }

        $title = sanitize_text_field($_POST['post_title'] ?? '');
        $content = wp_kses_post($_POST['post_content'] ?? '');

        if (empty($title)) {
            wp_send_json_error(__('Course title is required', 'lenxel-core'));
        }

        $course_data = array(
            'post_title' => $title,
            'post_content' => $content,
            'post_type' => self::COURSE_POST_TYPE,
            'post_status' => 'publish',
            'post_author' => get_current_user_id()
        );

        $course_id = wp_insert_post($course_data);

        if (is_wp_error($course_id)) {
            wp_send_json_error($course_id->get_error_message());
        }

        wp_send_json_success(array(
            'message' => __('Course created successfully', 'lenxel-core'),
            'course_id' => $course_id
        ));
    }

    /**
     * Get course details via AJAX
     */
    public function ajax_course_details()
    {
        check_ajax_referer('lenxel_ajax_nonce', 'nonce');

        $course_id = absint($_POST['course_id'] ?? 0);

        if (!$course_id || get_post_type($course_id) !== self::COURSE_POST_TYPE) {
            wp_send_json_error(__('Invalid course ID', 'lenxel-core'));
        }

        $course = get_post($course_id);
        if (!$course) {
            wp_send_json_error(__('Course not found', 'lenxel-core'));
        }

        $course_data = array(
            'ID' => $course->ID,
            'post_title' => $course->post_title,
            'post_content' => $course->post_content,
            'post_status' => $course->post_status,
            'course_level' => get_post_meta($course_id, '_lenxel_course_level', true),
            'course_duration' => get_post_meta($course_id, '_lenxel_course_duration', true),
            'course_benefits' => get_post_meta($course_id, '_lenxel_course_benefits', true),
            'course_requirements' => get_post_meta($course_id, '_lenxel_course_requirements', true),
            'course_target_audience' => get_post_meta($course_id, '_lenxel_course_target_audience', true),
            'course_material_includes' => get_post_meta($course_id, '_lenxel_course_material_includes', true),
            'thumbnail_url' => get_the_post_thumbnail_url($course_id, 'full')
        );

        wp_send_json_success($course_data);
    }

    /**
     * Update course via AJAX
     */
    public function ajax_update_course()
    {
        check_ajax_referer('lenxel_ajax_nonce', 'nonce');

        $course_id = absint($_POST['course_id'] ?? 0);

        if (!$course_id || !current_user_can('edit_post', $course_id)) {
            wp_send_json_error(__('Permission denied', 'lenxel-core'));
        }

        $title = sanitize_text_field($_POST['post_title'] ?? '');
        $content = wp_kses_post($_POST['post_content'] ?? '');

        if (empty($title)) {
            wp_send_json_error(__('Course title is required', 'lenxel-core'));
        }

        $course_data = array(
            'ID' => $course_id,
            'post_title' => $title,
            'post_content' => $content
        );

        $updated = wp_update_post($course_data);

        if (is_wp_error($updated)) {
            wp_send_json_error($updated->get_error_message());
        }

        wp_send_json_success(array(
            'message' => __('Course updated successfully', 'lenxel-core'),
            'course_id' => $course_id
        ));
    }

    /**
     * Save topic via AJAX
     */
    public function save_topic()
    {
        check_ajax_referer('lenxel_ajax_nonce', 'nonce');

        $title = sanitize_text_field($_POST['title'] ?? '');
        $course_id = absint($_POST['course_id'] ?? 0);
        $topic_id = absint($_POST['topic_id'] ?? 0);
        $summary = wp_kses_post($_POST['summary'] ?? '');

        if (empty($title)) {
            wp_send_json_error(__('Topic title is required', 'lenxel-core'));
        }

        if (!$course_id || get_post_type($course_id) !== self::COURSE_POST_TYPE) {
            wp_send_json_error(__('Invalid course ID', 'lenxel-core'));
        }

        $topic_data = array(
            'post_title' => $title,
            'post_content' => $summary,
            'post_type' => 'course_topic',
            'post_status' => 'publish',
            'post_parent' => $course_id,
            'post_author' => get_current_user_id()
        );

        if ($topic_id) {
            $topic_data['ID'] = $topic_id;
            $result = wp_update_post($topic_data);
            $message = __('Topic updated successfully', 'lenxel-core');
        } else {
            $result = wp_insert_post($topic_data);
            $message = __('Topic created successfully', 'lenxel-core');
        }

        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        }

        wp_send_json_success(array(
            'message' => $message,
            'topic_id' => $result
        ));
    }

    /**
     * Delete topic via AJAX
     */
    public function delete_topic()
    {
        check_ajax_referer('lenxel_ajax_nonce', 'nonce');

        $topic_id = absint($_POST['topic_id'] ?? 0);

        if (!$topic_id || !current_user_can('delete_post', $topic_id)) {
            wp_send_json_error(__('Permission denied', 'lenxel-core'));
        }

        $deleted = wp_delete_post($topic_id, true);

        if (!$deleted) {
            wp_send_json_error(__('Failed to delete topic', 'lenxel-core'));
        }

        wp_send_json_success(__('Topic deleted successfully', 'lenxel-core'));
    }
}

// Initialize the class
new Lenxel_Course();
