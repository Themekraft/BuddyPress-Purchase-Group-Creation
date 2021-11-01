<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * Plugin Name: BuddyPress Purchase Group Creation
 * Plugin URI: https://themekraft.com/
 * Description: Ask your members to pay a fee to create new groups.
 * Version: 0.1
 * Author: ThemeKraft
 * Author URI: https://themekraft.com/
 * License: GPLv2 or later
 * Network: false
 * Text Domain: buddypress-pgc
 * Domain Path: /languages
 * Svn: buddyforms-pgc
 *
 *
 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */


if ( ! class_exists( 'tk_buddypress_pgc' ) ) {


	class tk_buddypress_pgc {

		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Initialize the plugin.
		 */
		public function __construct() {
			define( 'TK_BP_PGC_CSS_PATH', plugin_dir_url( __FILE__ ) . 'assets/css/' );
			define( 'TK_BP_PGC_JS_PATH', plugin_dir_url( __FILE__ ) . 'assets/js/' );
			define( 'TK_BP_PGC_VIEW_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR );
			define( 'TK_BP_PGC_CLASSES_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR );

			$this->load_plugin_textdomain();

      // TODO: require all needed resources/ files
      //require_once WC4BP_GROUP_CLASSES_PATH . 'filename.php';


      // Pre defined to check all depandancies.
      //require_once TK_BP_PGC_CLASSES_PATH . 'resources' . DIRECTORY_SEPARATOR . 'class-tgm-plugin-activation.php';
			//require_once TK_BP_PGC_CLASSES_PATH . 'tk_buddypress_pgc_required.php';

      // TODO: Create a Function based on the tgm depandanciesmanager to check for all reqirements. 1. BuddyPress 2. Groups Component enabled, ...
    }
		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'tk_buddypress_pgc', false, basename( dirname( __FILE__ ) ) . '/languages' );
		}
	}

	add_action( 'plugins_loaded', array( 'tk_buddypress_pgc', 'get_instance' ) );
}
