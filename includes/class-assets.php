<?php

/**
 * Manage Assets for Lenxel Core
 *
 * @package LenxelCore
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Assets class for Lenxel Core
 */
class Lenxel_Assets
{

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'load_meta_data'));
        add_action('wp_enqueue_scripts', array($this, 'load_meta_data'));
        // add_filter('body_class', array($this, 'add_identifier_class_to_body'));
        // add_filter('admin_body_class', array($this, 'add_identifier_class_to_body'));
    }

    /**
     * Enqueue admin scripts
     */
    public function admin_scripts()
    {
        // wp_enqueue_style('lenxel-admin', LENXEL_PLUGIN_URL . 'assets/css/admin.css', array(), LENXEL_CORE_VERSION);
        // wp_enqueue_script('lenxel-admin', LENXEL_PLUGIN_URL . 'assets/js/admin.js', array('jquery', 'wp-i18n'), LENXEL_CORE_VERSION, true);

        // Course builder specific scripts
        $current_screen = get_current_screen();
        if ($current_screen && ($current_screen->post_type === 'courses' || (isset($_GET['page']) && in_array(sanitize_text_field(wp_unslash($_GET['page'])), array('lenxel-course-builder', 'create-course'), true)))) {
            wp_enqueue_script('lenxel-course-builder', LENXEL_PLUGIN_URL . 'assets/js/course-builder.js', array('jquery', 'wp-element', 'wp-i18n'), LENXEL_CORE_VERSION, true);
            wp_enqueue_style('lenxel-course-builder', LENXEL_PLUGIN_URL . 'assets/css/course-builder.css', array(), LENXEL_CORE_VERSION);
        }
    }

    /**
     * Enqueue frontend scripts
     */
    public function frontend_scripts()
    {
        wp_enqueue_style('lenxel-frontend', LENXEL_PLUGIN_URL . 'assets/css/frontend.css', array(), LENXEL_CORE_VERSION);
        wp_enqueue_script('lenxel-frontend', LENXEL_PLUGIN_URL . 'assets/js/frontend.js', array('jquery', 'wp-i18n'), LENXEL_CORE_VERSION, true);

        // Course specific scripts
        if (is_singular('courses')) {
            wp_enqueue_script('lenxel-course', LENXEL_PLUGIN_URL . 'assets/js/course.js', array('jquery'), LENXEL_CORE_VERSION, true);
        }
    }

    /**
     * Load meta data for scripts
     */
    public function load_meta_data()
    {
        $localize_data = $this->get_localized_data();

        wp_localize_script('lenxel-admin', 'lenxelData', $localize_data);
        wp_localize_script('lenxel-frontend', 'lenxelData', $localize_data);
        wp_localize_script('lenxel-course-builder', 'lenxelData', $localize_data);
        wp_localize_script('lenxel-wprp-course-builder-script', 'lenxelData', $localize_data);
    }

    /**
     * Get localized data for scripts
     */
    public function get_localized_data()
    {

        // Get course ID from query string
        $course_id = isset($_GET['page']) && isset($_GET['courses_id']) ? absint(wp_unslash($_GET['courses_id'])) : 0;

        // Determine post type from that ID
        $course_post_type = '';
        $course_slug = '';

        if ($course_id) {
            $course_post_type = get_post_type($course_id);
            $course_slug = get_permalink($course_id);
        }

        /**
         * Get the users / students / course levels
         *
         * @since 1.0.0
         *
         * @param mixed $level level.
         *
         * @return mixed
         */
        function course_levels($level = null)
        {
            $levels = apply_filters(
                'lenxel_course_level',
                array(
                    'all_levels'   => __('All Levels', 'lenxel-core'),
                    'beginner'     => __('Beginner', 'lenxel-core'),
                    'intermediate' => __('Intermediate', 'lenxel-core'),
                    'expert'       => __('Expert', 'lenxel-core'),
                )
            );

            // ✅ Return specific label if $level is provided
            if ($level) {
                return isset($levels[$level]) ? $levels[$level] : '';
            }

            // ✅ Transform to array of objects with label/value pairs
            $formatted = array_map(
                function ($label, $value) {
                    return array(
                        'label' => $label,
                        'value' => $value,
                    );
                },
                $levels,
                array_keys($levels)
            );

            return array_values($formatted);
        }

        // Transform supported_video_sources to proper format
        $tutor_options = get_option('tutor_option', array());
        $supported_video_sources_raw = isset($tutor_options['supported_video_sources']) ? $tutor_options['supported_video_sources'] : array();

        // Create proper label/value format for video sources
        $supported_video_sources = array();
        foreach ($supported_video_sources_raw as $source) {
            $supported_video_sources[] = array(
                'label' => $source,
                'value' => $source,
            );
        }

        global $wpdb;

        // Check if BuddyPress is active and the groups table exists before querying
        $buddyPress = array();
        if (function_exists('bp_is_active') && bp_is_active('groups')) {
            // Check if the table exists before querying
            $table_name = $wpdb->prefix . 'bp_groups';
            $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
            
            if ($table_exists) {
                $buddyPress = $wpdb->get_results("SELECT * FROM {$table_name}");
            }
        }

        /**
         * Get list of global timezones
         *
         * @return array
         */
        function lenxel_global_timezone_lists() {
            return array(
                'Pacific/Midway'                 => '(GMT-11:00) Midway Island, Samoa ',
                'Pacific/Pago_Pago'              => '(GMT-11:00) Pago Pago ',
                'Pacific/Honolulu'               => '(GMT-10:00) Hawaii ',
                'America/Anchorage'              => '(GMT-8:00) Alaska ',
                'America/Vancouver'              => '(GMT-7:00) Vancouver ',
                'America/Los_Angeles'            => '(GMT-7:00) Pacific Time (US and Canada) ',
                'America/Tijuana'                => '(GMT-7:00) Tijuana ',
                'America/Phoenix'                => '(GMT-7:00) Arizona ',
                'America/Edmonton'               => '(GMT-6:00) Edmonton ',
                'America/Denver'                 => '(GMT-6:00) Mountain Time (US and Canada) ',
                'America/Mazatlan'               => '(GMT-6:00) Mazatlan ',
                'America/Regina'                 => '(GMT-6:00) Saskatchewan ',
                'America/Guatemala'              => '(GMT-6:00) Guatemala ',
                'America/El_Salvador'            => '(GMT-6:00) El Salvador ',
                'America/Managua'                => '(GMT-6:00) Managua ',
                'America/Costa_Rica'             => '(GMT-6:00) Costa Rica ',
                'America/Tegucigalpa'            => '(GMT-6:00) Tegucigalpa ',
                'America/Winnipeg'               => '(GMT-5:00) Winnipeg ',
                'America/Chicago'                => '(GMT-5:00) Central Time (US and Canada) ',
                'America/Mexico_City'            => '(GMT-5:00) Mexico City ',
                'America/Panama'                 => '(GMT-5:00) Panama ',
                'America/Bogota'                 => '(GMT-5:00) Bogota ',
                'America/Lima'                   => '(GMT-5:00) Lima ',
                'America/Caracas'                => '(GMT-4:30) Caracas ',
                'America/Montreal'               => '(GMT-4:00) Montreal ',
                'America/New_York'               => '(GMT-4:00) Eastern Time (US and Canada) ',
                'America/Indianapolis'           => '(GMT-4:00) Indiana (East) ',
                'America/Puerto_Rico'            => '(GMT-4:00) Puerto Rico ',
                'America/Santiago'               => '(GMT-4:00) Santiago ',
                'America/Halifax'                => '(GMT-3:00) Halifax ',
                'America/Montevideo'             => '(GMT-3:00) Montevideo ',
                'America/Araguaina'              => '(GMT-3:00) Brasilia ',
                'America/Argentina/Buenos_Aires' => '(GMT-3:00) Buenos Aires, Georgetown ',
                'America/Sao_Paulo'              => '(GMT-3:00) Sao Paulo ',
                'Canada/Atlantic'                => '(GMT-3:00) Atlantic Time (Canada) ',
                'America/St_Johns'               => '(GMT-2:30) Newfoundland and Labrador ',
                'America/Godthab'                => '(GMT-2:00) Greenland ',
                'Atlantic/Cape_Verde'            => '(GMT-1:00) Cape Verde Islands ',
                'Atlantic/Azores'                => '(GMT+0:00) Azores ',
                'UTC'                            => '(GMT+0:00) Universal Time UTC ',
                'Etc/Greenwich'                  => '(GMT+0:00) Greenwich Mean Time ',
                'Atlantic/Reykjavik'             => '(GMT+0:00) Reykjavik ',
                'Africa/Nouakchott'              => '(GMT+0:00) Nouakchott ',
                'Europe/Dublin'                  => '(GMT+1:00) Dublin ',
                'Europe/London'                  => '(GMT+1:00) London ',
                'Europe/Lisbon'                  => '(GMT+1:00) Lisbon ',
                'Africa/Casablanca'              => '(GMT+1:00) Casablanca ',
                'Africa/Bangui'                  => '(GMT+1:00) West Central Africa ',
                'Africa/Algiers'                 => '(GMT+1:00) Algiers ',
                'Africa/Tunis'                   => '(GMT+1:00) Tunis ',
                'Europe/Belgrade'                => '(GMT+2:00) Belgrade, Bratislava, Ljubljana ',
                'CET'                            => '(GMT+2:00) Sarajevo, Skopje, Zagreb ',
                'Europe/Oslo'                    => '(GMT+2:00) Oslo ',
                'Europe/Copenhagen'              => '(GMT+2:00) Copenhagen ',
                'Europe/Brussels'                => '(GMT+2:00) Brussels ',
                'Europe/Berlin'                  => '(GMT+2:00) Amsterdam, Berlin, Rome, Stockholm, Vienna ',
                'Europe/Amsterdam'               => '(GMT+2:00) Amsterdam ',
                'Europe/Rome'                    => '(GMT+2:00) Rome ',
                'Europe/Stockholm'               => '(GMT+2:00) Stockholm ',
                'Europe/Vienna'                  => '(GMT+2:00) Vienna ',
                'Europe/Luxembourg'              => '(GMT+2:00) Luxembourg ',
                'Europe/Paris'                   => '(GMT+2:00) Paris ',
                'Europe/Zurich'                  => '(GMT+2:00) Zurich ',
                'Europe/Madrid'                  => '(GMT+2:00) Madrid ',
                'Africa/Harare'                  => '(GMT+2:00) Harare, Pretoria ',
                'Europe/Warsaw'                  => '(GMT+2:00) Warsaw ',
                'Europe/Prague'                  => '(GMT+2:00) Prague Bratislava ',
                'Europe/Budapest'                => '(GMT+2:00) Budapest ',
                'Africa/Tripoli'                 => '(GMT+2:00) Tripoli ',
                'Africa/Cairo'                   => '(GMT+2:00) Cairo ',
                'Africa/Johannesburg'            => '(GMT+2:00) Johannesburg ',
                'Europe/Helsinki'                => '(GMT+3:00) Helsinki ',
                'Africa/Nairobi'                 => '(GMT+3:00) Nairobi ',
                'Europe/Sofia'                   => '(GMT+3:00) Sofia ',
                'Europe/Istanbul'                => '(GMT+3:00) Istanbul ',
                'Europe/Athens'                  => '(GMT+3:00) Athens ',
                'Europe/Bucharest'               => '(GMT+3:00) Bucharest ',
                'Asia/Nicosia'                   => '(GMT+3:00) Nicosia ',
                'Asia/Beirut'                    => '(GMT+3:00) Beirut ',
                'Asia/Damascus'                  => '(GMT+3:00) Damascus ',
                'Asia/Jerusalem'                 => '(GMT+3:00) Jerusalem ',
                'Asia/Amman'                     => '(GMT+3:00) Amman ',
                'Europe/Moscow'                  => '(GMT+3:00) Moscow ',
                'Asia/Baghdad'                   => '(GMT+3:00) Baghdad ',
                'Asia/Kuwait'                    => '(GMT+3:00) Kuwait ',
                'Asia/Riyadh'                    => '(GMT+3:00) Riyadh ',
                'Asia/Bahrain'                   => '(GMT+3:00) Bahrain ',
                'Asia/Qatar'                     => '(GMT+3:00) Qatar ',
                'Asia/Aden'                      => '(GMT+3:00) Aden ',
                'Africa/Khartoum'                => '(GMT+3:00) Khartoum ',
                'Africa/Djibouti'                => '(GMT+3:00) Djibouti ',
                'Africa/Mogadishu'               => '(GMT+3:00) Mogadishu ',
                'Europe/Kiev'                    => '(GMT+3:00) Kiev ',
                'Asia/Dubai'                     => '(GMT+4:00) Dubai ',
                'Asia/Muscat'                    => '(GMT+4:00) Muscat ',
                'Asia/Tehran'                    => '(GMT+4:30) Tehran ',
                'Asia/Kabul'                     => '(GMT+4:30) Kabul ',
                'Asia/Baku'                      => '(GMT+5:00) Baku, Tbilisi, Yerevan ',
                'Asia/Yekaterinburg'             => '(GMT+5:00) Yekaterinburg ',
                'Asia/Tashkent'                  => '(GMT+5:00) Tashkent ',
                'Asia/Karachi'                   => '(GMT+5:00) Islamabad, Karachi ',
                'Asia/Calcutta'                  => '(GMT+5:30) India ',
                'Asia/Kolkata'                   => '(GMT+5:30) Mumbai, Kolkata, New Delhi ',
                'Asia/Kathmandu'                 => '(GMT+5:45) Kathmandu ',
                'Asia/Novosibirsk'               => '(GMT+6:00) Novosibirsk ',
                'Asia/Almaty'                    => '(GMT+6:00) Almaty ',
                'Asia/Dacca'                     => '(GMT+6:00) Dacca ',
                'Asia/Dhaka'                     => '(GMT+6:00) Astana, Dhaka ',
                'Asia/Krasnoyarsk'               => '(GMT+7:00) Krasnoyarsk ',
                'Asia/Bangkok'                   => '(GMT+7:00) Bangkok ',
                'Asia/Saigon'                    => '(GMT+7:00) Vietnam ',
                'Asia/Jakarta'                   => '(GMT+7:00) Jakarta ',
                'Asia/Irkutsk'                   => '(GMT+8:00) Irkutsk, Ulaanbaatar ',
                'Asia/Shanghai'                  => '(GMT+8:00) Beijing, Shanghai ',
                'Asia/Hong_Kong'                 => '(GMT+8:00) Hong Kong ',
                'Asia/Taipei'                    => '(GMT+8:00) Taipei ',
                'Asia/Kuala_Lumpur'              => '(GMT+8:00) Kuala Lumpur ',
                'Asia/Singapore'                 => '(GMT+8:00) Singapore ',
                'Australia/Perth'                => '(GMT+8:00) Perth ',
                'Asia/Yakutsk'                   => '(GMT+9:00) Yakutsk ',
                'Asia/Seoul'                     => '(GMT+9:00) Seoul ',
                'Asia/Tokyo'                     => '(GMT+9:00) Osaka, Sapporo, Tokyo ',
                'Australia/Darwin'               => '(GMT+9:30) Darwin ',
                'Australia/Adelaide'             => '(GMT+9:30) Adelaide ',
                'Asia/Vladivostok'               => '(GMT+10:00) Vladivostok ',
                'Pacific/Port_Moresby'           => '(GMT+10:00) Guam, Port Moresby ',
                'Australia/Brisbane'             => '(GMT+10:00) Brisbane ',
                'Australia/Sydney'               => '(GMT+10:00) Canberra, Melbourne, Sydney ',
                'Australia/Hobart'               => '(GMT+10:00) Hobart ',
                'Asia/Magadan'                   => '(GMT+10:00) Magadan ',
                'SST'                            => '(GMT+11:00) Solomon Islands ',
                'Pacific/Noumea'                 => '(GMT+11:00) New Caledonia ',
                'Asia/Kamchatka'                 => '(GMT+12:00) Kamchatka ',
                'Pacific/Fiji'                   => '(GMT+12:00) Fiji Islands, Marshall Islands ',
                'Pacific/Auckland'               => '(GMT+12:00) Auckland, Wellington',
            );
        }


        // Get WordPress theme custom logo
        $custom_logo_id = get_theme_mod('custom_logo');
        $custom_logo_url = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : '';

        return array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'admin_url' => admin_url(),
            'nonce' => wp_create_nonce('lenxel_ajax_nonce'),
            'taxonomy_nonce' => wp_create_nonce('lenxel_create_taxonomy'),
            'home_url' => home_url(),
            'site_title' => get_bloginfo('title'),
            'plugin_url' => LENXEL_PLUGIN_URL,
            'course_post_type' => $course_post_type,
            'course_slug' => $course_slug,
            'current_user' => wp_get_current_user(),
            'is_admin' => is_admin(),
            'is_admin_bar_showing' => is_admin_bar_showing(),
            'difficulty_levels' => course_levels(),
            'backend_course_list_url' => admin_url('admin.php?page=tutor'),
            'frontend_course_list_url' => get_the_permalink(get_option('tutor_dashboard_page_id')),
            'settings' => array_merge(
                get_option('tutor_option', array()),
                array('supported_video_sources' => $supported_video_sources)
            ),
            'secret_token' => get_option('lenxel_activation_key'),
            'buddypress_groups' => $buddyPress,
            'timezones' => lenxel_global_timezone_lists(),
            'theme_logo_url' => $custom_logo_url,
        );
    }

    /**
     * Add identifier class to body
     */
    public function add_identifier_class_to_body($classes)
    {
        $to_add = array('lenxel-core');

        // Add course builder class
        if (isset($_GET['page']) && sanitize_text_field(wp_unslash($_GET['page'])) === 'lenxel-course-builder') {
            $to_add[] = 'lenxel-course-builder-page';
        }

        // Add frontend course class
        if (is_singular('courses')) {
            $to_add[] = 'lenxel-course-single';
        }

        if (is_array($classes)) {
            $classes = array_merge($classes, $to_add);
        } else {
            $classes .= ' ' . implode(' ', $to_add);
        }

        return $classes;
    }
}

// Initialize the class
new Lenxel_Assets();
