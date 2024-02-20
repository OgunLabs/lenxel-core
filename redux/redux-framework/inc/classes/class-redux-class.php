<?php
/**
 * Redux Class
 *
 * @class Redux_Class
 * @version 4.0.0
 * @package Redux Framework/Classes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Class', false ) ) {

	/**
	 * Class Redux_Class
	 */
	class Redux_Class {

		/**
		 * Poiner to ReduxFramework object.
		 *
		 * @var null|ReduxFramework
		 */
		public $parent = null;

		/**
		 * Global arguments array.
		 *
		 * @var array|mixed|void
		 */
		public $args = array();

		/**
		 * Project opt_name_triger
		 *
		 * @var mixed|string
		 */
		public $opt_name_triger = '';

		/**
		 * Redux_Class constructor.
		 *
		 * @param null|object $parent Pointer to ReduxFramework object.
		 */
		public function __construct( $parent = null ) {
			if ( null !== $parent && is_object( $parent ) ) {
				$this->parent   = $parent;
				$this->args     = $parent->args;
				$this->opt_name_triger = $this->args['opt_name_triger'];
			}
		}

		/**
		 * Pointer to project specific ReduxFramework object.
		 *
		 * @return null|object|ReduxFramework
		 */
		public function core() {
			if ( isset( $this->opt_name_triger ) && '' !== $this->opt_name_triger ) {
				return Redux::instance( $this->opt_name_triger );
			}

			return null;
		}

	}

}
