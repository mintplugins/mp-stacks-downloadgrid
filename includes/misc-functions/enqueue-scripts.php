<?php
/**
 * This file contains the enqueue scripts function for the download_grid plugin
 *
 * @since 1.0.0
 *
 * @package    MP Stacks Features
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2014, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
 
/**
 * Enqueue JS and CSS for download_grid 
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */

/**
 * Enqueue css and js
 *
 * Filter: mp_stacks_download_grid_css_location
 */
function mp_stacks_download_grid_enqueue_scripts(){
			
	//Enqueue download_grid CSS
	wp_enqueue_style( 'mp_stacks_download_grid_css', plugins_url( 'css/download-grid.css', dirname( __FILE__ ) ) );
	
	//Enqueue velocity JS
	wp_enqueue_script( 'velocity_js', plugins_url( 'js/jquery.velocity.min.js', dirname( __FILE__ ) ), array( 'jquery' ) );
	
	//UI plugin for velocity JS
	wp_enqueue_script( 'velocity_ui_js', plugins_url( 'js/velocity.ui.js', dirname( __FILE__ ) ), array( 'jquery', 'velocity_js' ) );
	
	//Enqueue download_grid CSS
	wp_enqueue_script( 'mp_stacks_download_grid_js', plugins_url( 'js/download-grid.js', dirname( __FILE__ ) ), array( 'jquery', 'velocity_js', 'velocity_ui_js' ) );

}
 
/**
 * Enqueue css face for download_grid
 */
add_action( 'wp_enqueue_scripts', 'mp_stacks_download_grid_enqueue_scripts' );

/**
 * Enqueue css and js
 *
 * Filter: mp_stacks_download_grid_css_location
 */
function mp_stacks_download_grid_admin_enqueue_scripts(){
	
	//Enqueue Admin Features CSS
	wp_enqueue_style( 'mp_stacks_download_grid_css', plugins_url( 'css/admin-download-grid.css', dirname( __FILE__ ) ) );

}
 
/**
 * Enqueue css face for download_grid
 */
add_action( 'admin_enqueue_scripts', 'mp_stacks_download_grid_admin_enqueue_scripts' );