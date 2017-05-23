<?php
/**
 * Select All Terms (by GOMO)
 * 
 * Adds select / deselect all buttons to taxonomies' metaboxes
 *
 * @package   Select All Terms by GOMO
 * @author    Luis Godinho <luis@gomo.pt>
 * @license   GPL-2.0+
 * @link      http://www.gomo.pt/
 * @copyright 2016 GOMO
 *
 * @wordpress-plugin
 * Plugin Name: Select All Terms
 * Plugin URI:  http://www.gomo.pt/
 * Description: Add select all/deselect all buttons to taxonomies' metaboxes
 * Version:     1.0.2
 * Author:      GOMO
 * Author URI:  http://twitter.com/wearegomo
 * Text Domain: select-all-terms
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 
Select All Terms, by GOMO (SAT)
Copyright (C) 2016, GOMO - hello@gomo.pt

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, see <http://www.gnu.org/licenses/>, or 
write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, 
Boston, MA  02110-1301  USA.

 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( !defined('SAT_URL') )
	define( 'SAT_URL', plugin_dir_url( __FILE__ ) );
if ( !defined('SAT_PATH') )
	define( 'SAT_PATH', plugin_dir_path( __FILE__ ) );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'Select_All_Terms_by_Gomo', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Select_All_Terms_by_Gomo', 'deactivate' ) );

$sat_plugin = new Select_All_Terms_by_Gomo();

/**
 * Plugin class.
 *
 */
class Select_All_Terms_by_Gomo {

	protected $version = '1.0.2';
	protected $plugin_slug = 'select-all-terms';

	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 */
	public function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		//add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		//add_action('admin_init', array( $this, 'plugin_register_settings') );

		// Add script to add the buttons
		add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts_and_styles') );

	}
	
	
	/**
	 * Fired when the plugin is activated.
	 */
	public static function activate( $network_wide ) {
		
	}

	/**
	 * Fired when the plugin is deactivated.
	 */
	public static function deactivate( $network_wide ) {
		
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		//$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		//load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}
	
	/**
	 * Load script for core feature (add buttons)
	 */
	function add_scripts_and_styles( $hook ) {
		if( 'post-new.php' != $hook && 'post.php' != $hook ) {
			return;
		}

		wp_enqueue_script( 'select-all-script', plugin_dir_url( __FILE__ ) . 'js/select-all-terms.js', array('jquery') );
		
		wp_localize_script( 'select-all-script', 'labels', array( 'select' => __('Select All', 'select-all-terms'), 'deselect' => __('Deselect All', 'select-all-terms') ) );

	}
	

	/** Admin page */
	//todo
	
	
}