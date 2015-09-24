<?php
/*
Plugin Name: MP Stacks + DownloadGrid
Plugin URI: http://mintplugins.com
Description: Displays posts from Easy Digital Downloads categories in a grid in a Brick
Version: 1.0.1.4
Author: Mint Plugins
Author URI: http://mintplugins.com
Text Domain: mp_stacks_downloadgrid
Domain Path: languages
License: GPL2
*/

/*  Copyright 2015  Phil Johnston  (email : phil@mintplugins.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Mint Plugins Core.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
|--------------------------------------------------------------------------
| CONSTANTS
|--------------------------------------------------------------------------
*/
// Plugin version
if( !defined( 'MP_STACKS_DOWNLOADGRID_VERSION' ) )
	define( 'MP_STACKS_DOWNLOADGRID_VERSION', '1.0.1.4' );

// Plugin Folder URL
if( !defined( 'MP_STACKS_DOWNLOADGRID_PLUGIN_URL' ) )
	define( 'MP_STACKS_DOWNLOADGRID_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Plugin Folder Path
if( !defined( 'MP_STACKS_DOWNLOADGRID_PLUGIN_DIR' ) )
	define( 'MP_STACKS_DOWNLOADGRID_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Plugin Root File
if( !defined( 'MP_STACKS_DOWNLOADGRID_PLUGIN_FILE' ) )
	define( 'MP_STACKS_DOWNLOADGRID_PLUGIN_FILE', __FILE__ );

/*
|--------------------------------------------------------------------------
| GLOBALS
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| INTERNATIONALIZATION
|--------------------------------------------------------------------------
*/

function mp_stacks_downloadgrid_textdomain() {

	// Set filter for plugin's languages directory
	$mp_stacks_downloadgrid_lang_dir = dirname( plugin_basename( MP_STACKS_DOWNLOADGRID_PLUGIN_FILE ) ) . '/languages/';
	$mp_stacks_downloadgrid_lang_dir = apply_filters( 'mp_stacks_downloadgrid_languages_directory', $mp_stacks_downloadgrid_lang_dir );


	// Traditional WordPress plugin locale filter
	$locale        = apply_filters( 'plugin_locale',  get_locale(), 'mp-stacks-downloadgrid' );
	$mofile        = sprintf( '%1$s-%2$s.mo', 'mp-stacks-downloadgrid', $locale );

	// Setup paths to current locale file
	$mofile_local  = $mp_stacks_downloadgrid_lang_dir . $mofile;
	$mofile_global = WP_LANG_DIR . '/mp-stacks-downloadgrid/' . $mofile;

	if ( file_exists( $mofile_global ) ) {
		// Look in global /wp-content/languages/mp-stacks-downloadgrid folder
		load_textdomain( 'mp_stacks_downloadgrid', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) {
		// Look in local /wp-content/plugins/mp-stacks-downloadgrid/languages/ folder
		load_textdomain( 'mp_stacks_downloadgrid', $mofile_local );
	} else {
		// Load the default language files
		load_plugin_textdomain( 'mp_stacks_downloadgrid', false, $mp_stacks_downloadgrid_lang_dir );
	}

}
add_action( 'init', 'mp_stacks_downloadgrid_textdomain', 1 );

/**
 * Activation Hook Function - Sets up Rewrite Rules etc
 */
require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . 'includes/misc-functions/install.php' );

/*
|--------------------------------------------------------------------------
| INCLUDES
|--------------------------------------------------------------------------
*/
function mp_stacks_downloadgrid_include_files(){
	/**
	 * If mp_core or mp_stacks aren't active, stop and install it now
	 */
	if (!function_exists('mp_core_textdomain') || !function_exists('mp_stacks_textdomain')){
		
		/**
		 * Include Plugin Checker
		 */
		require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . '/includes/plugin-checker/class-plugin-checker.php' );
		
		/**
		 * Include Plugin Installer
		 */
		require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . '/includes/plugin-checker/class-plugin-installer.php' );
		
		/**
		 * Check if mp_core in installed
		 */
		require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . 'includes/plugin-checker/included-plugins/mp-core-check.php' );
		
		/**
		 * Check if mp_stacks is installed
		 */
		include_once( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . 'includes/plugin-checker/included-plugins/mp-stacks.php' );
		
	}
	/**
	 * Otherwise, if mp_core and mp_stacks are active, carry out the plugin's functions
	 */
	else{
		
		/**
		 * Update script - keeps this plugin up to date
		 */
		require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . 'includes/updater/mp-stacks-downloadgrid-update.php' );
		
		/**
		 * enqueue scripts
		 */
		require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . 'includes/misc-functions/admin-enqueue-scripts.php' );
		
		/**
		 * HTML Content Filters for downloadgrid
		 */
		require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . 'includes/misc-functions/content-filters-html.php' );
		
		/**
		 * CSS Content Filters for downloadgrid
		 */
		require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . 'includes/misc-functions/content-filters-css.php' );
		
		/**
		 * Metabox for downloadgrid
		 */
		require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . 'includes/metaboxes/mp-stacks-downloadgrid-meta/mp-stacks-downloadgrid-meta.php' );
		
		/**
		 * Add this add on to the list of Active MP Stacks Add Ons
		 */
		if ( function_exists('mp_stacks_developer_textdomain') ){
			function mp_stacks_downloadgrid_add_active( $required_add_ons ){
				$required_add_ons['mp_stacks_downloadgrid'] = 'MP Stacks + DownloadGrid';
				return $required_add_ons;
			}
			add_filter( 'mp_stacks_active_add_ons', 'mp_stacks_downloadgrid_add_active' );
		}
		
		/**
		 * Metabox which adds downloadgrid as a content type
		 */
		require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . 'includes/metaboxes/mp-stacks-content/mp-stacks-content.php' );
		
		/**
		 * Misc Functions 
		 */
		require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . 'includes/misc-functions/misc-functions.php' );
		
		/**
		 * Include all Grid Title Functions
		 */
		require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . 'includes/misc-functions/grid-titles-setup.php' );
		
		/**
		 * Include all Grid Excerpt Functions
		 */
		require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . 'includes/misc-functions/grid-excerpts-setup.php' );
		
		/**
		 * Include all Grid Price Functions
		 */
		require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . 'includes/misc-functions/grid-prices-setup.php' );
		
		/**
		 * Include all "Load More" Functions
		 */
		require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . 'includes/misc-functions/load-more-setup.php' );
		
		/**
		 * Isotope Setup
		 */
		require( MP_STACKS_DOWNLOADGRID_PLUGIN_DIR . 'includes/misc-functions/isotope-setup.php' );
				
	}
}
add_action('plugins_loaded', 'mp_stacks_downloadgrid_include_files', 9);