<?php
/**
 * Clean Rewrite: H5P AJAX Handlers for Lenxel Core
 */

namespace LENXEL_CORE;

if (!defined('ABSPATH')) exit;

class H5PAjaxHandlers {

    public function __construct() {
        $this->register_ajax_handlers();
    }

    /**
     * Register all AJAX endpoints
     */
    private function register_ajax_handlers() {

        // Lesson contents
        add_action('wp_ajax_lenxel_tutor_h5p_list_lesson_contents', [$this, 'lesson_contents']);
        add_action('wp_ajax_nopriv_lenxel_tutor_h5p_list_lesson_contents', [$this, 'lesson_contents']);

        // Quiz contents
        add_action('wp_ajax_lenxel_h5p_list_quiz_contents', [$this, 'quiz_contents']);
        add_action('wp_ajax_nopriv_lenxel_h5p_list_quiz_contents', [$this, 'quiz_contents']);
    }

    /**
     * AJAX: Get lesson H5P contents
     */
    public function lesson_contents() {
        $search = $this->get_search('search_filter');

        $this->respond(function() use ($search) {
            return [
                'output' => $this->get_h5p_contents('lesson', $search)
            ];
        });
    }

    /**
     * AJAX: Get quiz H5P contents
     */
    public function quiz_contents() {
        // Don't break frontend: accept both `search` and `search_filter`
        $search = $this->get_search(['search', 'search_filter']);

        $this->respond(function() use ($search) {
            return [
                'output' => $this->get_h5p_contents('quiz', $search)
            ];
        });
    }

    /**
     * Helper: Get search value safely
     */
    private function get_search($keys) {

        if (is_string($keys)) {
            $keys = [$keys];
        }

        foreach ($keys as $key) {
            if (!empty($_POST[$key])) {
                return sanitize_text_field($_POST[$key]);
            }
        }

        return '';
    }

    /**
     * Main database reader
     */
    private function get_h5p_contents($type = 'quiz', $search = '') {
        global $wpdb;

        $table = $wpdb->prefix . 'h5p_contents';

        // Fetch
        $results = $wpdb->get_results("SELECT * FROM {$table}");

        // No real data → return demo data
        if (empty($results)) {
            return $this->get_sample_data($type, $search);
        }

        $output = [];
        foreach ($results as $row) {

            // Title
            $title = !empty($row->title) ? $row->title : 'Untitled H5P Content';

            $library = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}h5p_libraries WHERE id = %d",
                    $row->library_id
                )
            );

            // Filter search
            if (!empty($search) && stripos($title, $search) === false) {
                continue;
            }

            $output[] = [
                'id'          => (int) $row->id,
                'title'       => $title,
                'content_type'=> $this->format_content_type($row->content_type, $library->title),
                'updated_at'  => $row->updated_at ?: $row->created_at,
                'user_id'     => (int) ($row->user_id ?: 1),
                'user_name'   => $row->user_name ?: 'Unknown User',
                'tags'        => null
            ];
        }

        return array_values($output);
    }

    /**
     * Unified safe response wrapper
     * Prevents 400/500 from leaking.
     */
    private function respond(callable $callback) {
        try {
            $data = $callback();

            wp_send_json_success($data);
        }
        catch (\Throwable $e) {

            error_log('[Lenxel H5P AJAX] ' . $e->getMessage());

            wp_send_json_error([
                'message' => 'Internal error while fetching H5P content.',
                'error'   => $e->getMessage()
            ]);
        }
    }

    /**
     * Format display-friendly content type label
     */
    private function format_content_type($title, $name) {

        if (!empty($title)) return $title;

        if (!empty($name)) {
            $formatted = preg_replace('/(?<!^)([A-Z])/', ' $1', $name);
            return ucwords(strtolower(str_replace('.', ' ', $formatted)));
        }

        return 'Interactive Content';
    }

    /**
     * Fallback demo data for testing
     */
    private function get_sample_data($type, $search) {
        $items = [
            [ 'id'=>1, 'title'=>'solar system',   'content_type'=>'Multiple Choice', 'updated_at'=>'2025-11-12 20:39:55', 'user_id'=>1, 'user_name'=>'Admin' ],
            [ 'id'=>2, 'title'=>'solar single',   'content_type'=>'Single Choice Set','updated_at'=>'2025-11-16 13:23:24','user_id'=>1,'user_name'=>'Admin' ],
            [ 'id'=>3, 'title'=>'solar multiple', 'content_type'=>'Multiple Choice', 'updated_at'=>'2025-11-16 13:29:35','user_id'=>1,'user_name'=>'Admin' ]
        ];

        if ($search) {
            $items = array_filter($items, function($item) use ($search) {
                return stripos($item['title'], $search) !== false;
            });
        }

        return array_values($items);
    }
}
