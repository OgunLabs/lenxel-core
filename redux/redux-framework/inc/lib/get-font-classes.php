<?php
/**
 * Get Font Awesome font classes.
 *
 * Used with Gulp compiler to pull Font Awesome font class names
 * and compile them into a function to be used with icon_Select field.
 *
 * @package Redux
 * @author  Kevin Provance <kevin.provance@gmail.com>
 *
 * @version 4.4.2
 */

$redux_output  = '<?php' . "\r";
$redux_output .= '/**' . "\r";
$redux_output .= ' * Redux Icon Select Font Awesome 6 Free icon array.' . "\r";
$redux_output .= ' *' . "\r";
$redux_output .= ' * @package Redux' . "\r";
$redux_output .= ' * @author  Kevin Provance <kevin.provance@gmail.com>' . "\r";
$redux_output .= ' */' . "\r\r";
$redux_output .= "defined( 'ABSPATH' ) || exit;\r\r";
$redux_output .= '';
$redux_output .= "if ( ! function_exists( 'redux_icon_select_fa_6_free' ) ) {\r\r";
$redux_output .= "\t" . '/**' . "\r";
$redux_output .= "\t" . ' * Array of free Font Awesome 6 icons.' . "\r";
$redux_output .= "\t" . ' *' . "\r";
$redux_output .= "\t" . ' * @return array' . "\r";
$redux_output .= "\t" . ' */' . "\r";
$redux_output .= "\t" . 'function redux_icon_select_fa_6_free(): array {' . "\r";
$redux_output .= "\t\t" . 'return array( ' . redux_fa_icons() . ' );' . "\r";
$redux_output .= "\t" . '}' . "\r";
$redux_output .= '}' . "\r";

// WordPress.org Compliance: Never write to plugin directory as it gets deleted on updates.
// Only write to WordPress uploads directory if WordPress functions are available.
//
// IMPORTANT FOR DEVELOPERS:
// - This file should be pre-generated during your build/release process
// - The pre-generated font-awesome-6-free.php must be included in inc/lib/ directory
// - Runtime generation only happens in wp-content/uploads/lenxel-core/redux/ directory
// - See class-redux-extension-icon-select.php for the loading mechanism
//
// To generate the file during development/build (outside WordPress):
// Run this script directly and manually copy the output to inc/lib/font-awesome-6-free.php
// before packaging the plugin for distribution.

if ( function_exists( 'wp_upload_dir' ) ) {
	// Use WordPress uploads directory (WordPress.org compliant location).
	$upload_dir = wp_upload_dir();
	$redux_upload_dir = $upload_dir['basedir'] . '/lenxel-core/redux';
	
	// Create directory if it doesn't exist.
	if ( ! file_exists( $redux_upload_dir ) ) {
		wp_mkdir_p( $redux_upload_dir );
	}
	
	$output_file = $redux_upload_dir . '/font-awesome-6-free.php';
	
	// Only write if file doesn't exist or we're in build mode.
	if ( ! file_exists( $output_file ) || ( defined( 'REDUX_BUILD_MODE' ) && REDUX_BUILD_MODE ) ) {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		file_put_contents( $output_file, $redux_output );
	}
}
// Note: No fallback to plugin directory - the file inc/lib/font-awesome-6-free.php 
// must be pre-generated and included in the plugin distribution.

/**
 * Get Font Awesome metadata.
 *
 * @return false|string
 */
function redux_fa_icons() {
	// Use local bundled Font Awesome icons file instead of remote call
	$local_icons_file = dirname( dirname( dirname( __FILE__ ) ) ) . '/assets/font-awesome-icons.json';
	$content = file_exists( $local_icons_file ) ? file_get_contents( $local_icons_file ) : '';
	$json    = json_decode( $content );
	$icons   = '';

	foreach ( $json as $icon => $value ) {
		foreach ( $value->styles as $style ) {
			$icon = 'fa' . substr( $style, 0, 1 ) . ' fa-' . $icon;

			$icons .= "'" . $icon . "', ";
		}
	}

	return substr( $icons, 0, -2 );
}
