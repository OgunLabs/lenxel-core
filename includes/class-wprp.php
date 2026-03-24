<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// filepath: /Users/macbookpro/projects/len/wordpress-react-plugin/includes/class-wprp.php
// ...existing code...
class Lenxel_WPRP {
    private static $instance;
    // private $plugin_url;
    private $plugin_path;
    private $assets;

    private function __construct() {
        // LENXEL_PLUGIN_URL  = plugin_dir_url( dirname( __FILE__ ) );
        // LENXEL_PLUGIN_DIR = plugin_dir_path( dirname( __FILE__ ) );
        $this->assets = $this->load_assets_manifest();

        // Enqueue admin assets only on the course builder page and run very late to avoid conflicts
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ], 999 );
        // Front assets should be enqueued on the front-end, not admin pages
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_front_assets' ], 999 );
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_gutenberg_assets' ] );

        // Inject mount container into admin footer when on course builder page
        add_action( 'admin_footer', [ $this, 'print_admin_mount' ], 999 );
    }

    public static function instance() {
        if ( ! self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function load_assets_manifest() {
        $manifest_file = LENXEL_PLUGIN_DIR . 'build/manifest.json';

        // Try to load a build manifest if present
        if ( file_exists( $manifest_file ) ) {
            $decoded = json_decode( file_get_contents( $manifest_file ), true );
            if ( is_array( $decoded ) ) {
                return $decoded;
            }
        }

        // Fallback: if no manifest, build a simple map from known filenames that exist
        $assets = [];
        $build_dir = LENXEL_PLUGIN_DIR . 'build/';
        $candidates = [
            'admin.js' => 'admin.js',
            'admin.css' => 'admin.css',
            'front.js' => 'front.js',
            'front.css' => 'front.css',
            'guten.js' => 'guten.js',
            'guten.css' => 'guten.css',
            // add course-builder candidates
            'course-builder.js' => 'course-builder.js',
            'course-builder.css' => 'course-builder.css',
        ];

        foreach ( $candidates as $key => $filename ) {
             
            if ( file_exists( $build_dir . $filename ) ) {
                $assets[ $key ] = $filename;
            }
        }

        // If build uses hashed filenames (e.g. admin.abcdef.js), attempt to detect them
        if ( empty( $assets ) || ! isset( $assets['admin.js'] ) ) {
            $files = @scandir( $build_dir );
            if ( is_array( $files ) ) {
                foreach ( $files as $f ) {
                    // Skip directories and LICENSE files
                    if ( empty( $f ) || $f === '.' || $f === '..' ) {
                        continue;
                    }

                    // detect admin JS (admin.js or admin.<hash>.js)
                    if ( preg_match( '/^admin(\.[a-f0-9]+)?\.js$/i', $f ) ) {
                        $assets['admin.js'] = $f;
                        continue;
                    }

                    // detect front JS
                    if ( preg_match( '/^front(\.[a-f0-9]+)?\.js$/i', $f ) ) {
                        $assets['front.js'] = $f;
                        continue;
                    }

                    // detect course-builder JS
                    if ( preg_match( '/^course-builder(\.[a-f0-9]+)?\.js$/i', $f ) ) {
                        $assets['course-builder.js'] = $f;
                        continue;
                    }

                    // detect guten JS
                    if ( preg_match( '/^guten(\.[a-f0-9]+)?\.js$/i', $f ) ) {
                        $assets['guten.js'] = $f;
                        continue;
                    }

                    // detect CSS counterparts
                    if ( preg_match( '/^admin(\.[a-f0-9]+)?\.css$/i', $f ) ) {
                        $assets['admin.css'] = $f;
                        continue;
                    }
                    if ( preg_match( '/^front(\.[a-f0-9]+)?\.css$/i', $f ) ) {
                        $assets['front.css'] = $f;
                        continue;
                    }
                    if ( preg_match( '/^guten(\.[a-f0-9]+)?\.css$/i', $f ) ) {
                        $assets['guten.css'] = $f;
                        continue;
                    }
                    if ( preg_match( '/^course-builder(\.[a-f0-9]+)?\.css$/i', $f ) ) {
                        $assets['course-builder.css'] = $f;
                        continue;
                    }
                }
            }
        }

        return $assets;
    }

    private function asset_url( $key ) {
        // Return full URL to an asset key from manifest or fallback mapping
        if ( isset( $this->assets[ $key ] ) ) {
            $file = $this->assets[ $key ];
            return LENXEL_PLUGIN_URL . 'build/' . ltrim( $file, '/' );
        }
        return '';
    }

    public function enqueue_admin_assets() {
        // Only enqueue on the course builder admin page (page=create-course) AND when our `courses_id` param is present
        if ( ! is_admin() ) {
            return;
        }

        // detect page
        $is_course_builder = isset( $_GET['page'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) === 'create-course';
        if ( ! $is_course_builder ) {
            return;
        }

        // Robust courses id detection: accept both 'courses_id' and 'courses-id'
        $courses_id = '';
        if ( isset( $_GET['courses_id'] ) ) {
            $courses_id = sanitize_text_field( wp_unslash( $_GET['courses_id'] ) );
        } elseif ( isset( $_GET['courses-id'] ) ) {
            $courses_id = sanitize_text_field( wp_unslash( $_GET['courses-id'] ) );
        }

        if ( empty( $courses_id ) ) {
            // nothing to do when no course id present (we only mount the builder when id available)
            return;
        }

        // Determine files to load (prefer course-builder bundle if present)
        $script_url = $this->asset_url( 'course-builder.js' ) ?: $this->asset_url( 'admin.js' ) ?: $this->asset_url( 'front.js' );
        $style_url  = $this->asset_url( 'course-builder.css' ) ?: $this->asset_url( 'admin.css' ) ?: $this->asset_url( 'front.css' );

        // Enqueue styles first (if any)
        if ( $style_url ) {
            wp_enqueue_style( 'lenxel-wprp-course-builder-style', $style_url, [], LENXEL_CORE_VERSION );
        }

        // Enqueue script
        if ( $script_url ) {
            // Ensure WP element/i18n are available when using React built with wp packages
            $deps = [];
            // try to include common WP dependencies if present
            if ( wp_script_is( 'wp-element', 'registered' ) ) {
                $deps[] = 'wp-element';
            }
            if ( wp_script_is( 'wp-i18n', 'registered' ) ) {
                $deps[] = 'wp-i18n';
            }
            // jQuery is used by some legacy front bundles
            if ( wp_script_is( 'jquery', 'registered' ) ) {
                $deps[] = 'jquery';
            }

            wp_enqueue_script( 'lenxel-wprp-course-builder-script', $script_url, $deps, LENXEL_CORE_VERSION, true );

            // Set webpack public path BEFORE the main bundle loads (for dynamic imports)
            // This ensures dynamically loaded chunks resolve to the plugin build folder
            $public_path = esc_url( LENXEL_PLUGIN_URL . 'build/' );
            $inline_script = sprintf(
                "/* Lenxel WPRP: set webpack public path so dynamic imports resolve to plugin build folder */\nif (typeof __webpack_public_path__ !== 'undefined') { __webpack_public_path__ = %s; } else { window.__lenxel_wprp_public_path = %s; }",
                wp_json_encode( $public_path ),
                wp_json_encode( $public_path )
            );
            wp_add_inline_script( 'lenxel-wprp-course-builder-script', $inline_script, 'before' );

            // Pass some runtime data to the bundle
            $data = [
                'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
                'pluginUrl'  => LENXEL_PLUGIN_URL,
                'courses_id' => $courses_id,
                'nonce'      => wp_create_nonce( 'lenxel_ajax_nonce' ),
            ];
            wp_localize_script( 'lenxel-wprp-course-builder-script', 'lenxelWprp', $data );
        }
    }

    public function print_admin_mount() {
        // Only print the mount node when on the course builder admin page and with course id
        if ( ! is_admin() ) {
            return;
        }

        if ( ! isset( $_GET['page'] ) || sanitize_text_field( wp_unslash( $_GET['page'] ) ) !== 'create-course' ) {
            return;
        }

        $courses_id = '';
        if ( isset( $_GET['courses_id'] ) ) {
            $courses_id = sanitize_text_field( wp_unslash( $_GET['courses_id'] ) );
        } elseif ( isset( $_GET['courses-id'] ) ) {
            $courses_id = sanitize_text_field( wp_unslash( $_GET['courses-id'] ) );
        }

        if ( empty( $courses_id ) ) {
            return;
        }

        // Print a mount point for the React app. Keep it minimal so the React app can manage the UI.
       echo "\n<!-- Lenxel WPRP: Course Builder mount -->\n<div id=\"lenxel-course-builder\"></div>\n";
       echo "\n<!-- Lenxel WPRP: Course Builder mount -->\n<div id=\"lenxel-course-builder\" data-courses-id=\"" . esc_attr( $courses_id ) . "\"></div>\n";
        
    }

    public function enqueue_front_assets() {
       
        $front_js = $this->asset_url( 'front.js' );
        $front_css = $this->asset_url( 'front.css' );

        $front_file_path = LENXEL_PLUGIN_DIR . 'build/' . ( isset( $this->assets['front.js'] ) ? $this->assets['front.js'] : '' );
        $front_ver = ( $front_file_path && file_exists( $front_file_path ) ) ? filemtime( $front_file_path ) : null;

        $front_css_path = LENXEL_PLUGIN_DIR . 'build/' . ( isset( $this->assets['front.css'] ) ? $this->assets['front.css'] : '' );
        $front_css_ver = ( $front_css_path && file_exists( $front_css_path ) ) ? filemtime( $front_css_path ) : null;

        if ( $front_js ) {
            wp_enqueue_script( 'lenxel-wprp-front', $front_js, [], $front_ver, true );
            wp_localize_script( 'lenxel-wprp-front', 'lenxelWprpFront', [
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'lenxel-wprp-front' ),
            ] );
        }
        if ( $front_css ) {
            wp_enqueue_style( 'lenxel-wprp-front-css', $front_css, [], $front_css_ver );
        }
    }

    public function enqueue_gutenberg_assets() {
        $guten_js = $this->asset_url( 'guten.js' );
        $guten_file_path = LENXEL_PLUGIN_DIR . 'build/' . ( isset( $this->assets['guten.js'] ) ? $this->assets['guten.js'] : '' );
        $guten_ver = ( $guten_file_path && file_exists( $guten_file_path ) ) ? filemtime( $guten_file_path ) : null;

        if ( $guten_js ) {
            wp_enqueue_script( 'lenxel-wprp-guten', $guten_js, [ 'wp-blocks', 'wp-element', 'wp-i18n' ], $guten_ver, true );
        }
    }
}

Lenxel_WPRP::instance();
