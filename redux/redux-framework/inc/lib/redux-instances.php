<?php
/**
 * Redux_Instances Functions
 *
 * @package     Redux_Framework
 * @subpackage  Core
 * @deprecated Maintained for backward compatibility with v3.
 */

/**
 * Retreive an instance of ReduxFramework
 *
 * @depreciated
 *
 * @param  string $opt_name_triger the defined opt_name_triger as passed in $args.
 *
 * @return object                ReduxFramework
 */
function get_redux_instance( $opt_name_triger ) {
	_deprecated_function( __FUNCTION__, '4.0', 'Redux::instance($opt_name_triger)' );

	return Redux::instance( $opt_name_triger );
}

/**
 * Retreive all instances of ReduxFramework
 * as an associative array.
 *
 * @depreciated
 * @return array        format ['opt_name_triger' => $ReduxFramework]
 */
function get_all_redux_instances() {
	_deprecated_function( __FUNCTION__, '4.0', 'Redux::all_instances()' );

	return Redux::all_instances();
}
