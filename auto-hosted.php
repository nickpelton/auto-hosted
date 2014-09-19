<?php
/**
 * Plugin Name: Auto Hosted
 * Plugin URI: http://autohosted.com
 * Description: Automatic Plugin and Theme Updater Repository.
 * Version: 0.1.0
 * Author: David Chandra Purnama
 * Author URI: http://shellcreeper.com/
 *
 * Auto Hosted plugin was created to provide solution for plugin and theme developer to host and
 * manage automatic update for their product in easy and afforable way. 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms  
 * of the GNU General Public License version 2, as published by the Free Software Foundation.
 * You may NOT assume that you can use any other version of the GPL.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package AutoHosted
 * @version 0.1.0
 * @since 0.1.0
 * @author David Chandra Purnama <david@shellcreeper.com>
 * @copyright Copyright (c) 2013, David Chandra Purnama
 * @link http://autohosted.com
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Language */
load_plugin_textdomain( 'auto-hosted', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


/* Constants
------------------------------------------ */

/* Set plugin version constant. */
define( 'AUTOHOSTED_VERSION', '0.1.0' );

/* Set constant path to the plugin directory. */
define( 'AUTOHOSTED_PATH', trailingslashit( plugin_dir_path(__FILE__) ) );

/* Set the constant path to the plugin directory URI. */
define( 'AUTOHOSTED_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );


/* Load Functions
------------------------------------------ */

/* initiate metabox class */
add_action( 'after_setup_theme', 'auto_hosted_meta_boxes_init',99 );

/**
 * Load Metabox Class
 * @since 0.1.0
 */
function auto_hosted_meta_boxes_init() {
	require_once( AUTOHOSTED_PATH . 'mb/meta-box.php' );
}


/* Load register post types function */
require_once( AUTOHOSTED_PATH . 'includes/post-types.php' );

/* Load register taxonomies function */
require_once( AUTOHOSTED_PATH . 'includes/taxonomies.php' );

/* Load functions */
require_once( AUTOHOSTED_PATH . 'includes/functions.php' );

/* Load meta boxes and metadata functions */
require_once( AUTOHOSTED_PATH . 'includes/meta-boxes.php' );

/* Load edit custom column functions */
require_once( AUTOHOSTED_PATH . 'includes/manage-column.php' );


/* Updater
------------------------------------------ */

/* Hook updater to init */
add_action( 'init', 'cpt_docs_updater_init' );

/**
 * Load and Activate Plugin Updater Class.
 * @since 0.1.0
 */
function cpt_docs_updater_init() {

	/* Load Plugin Updater */
	require_once( AUTOHOSTED_PATH . 'includes/updater.php' );

	/* Updater Config */
	$config = array(
		'base'		=> plugin_basename( __FILE__ ), //required
		'repo_uri'	=> 'http://repo.shellcreeper.com/',
		'repo_slug'	=> 'auto-hosted',
	);

	/* Load Updater Class */
	new AH_AUTO_Hosted_Updater_Class( $config );
}


/* Activation and Uninstall
------------------------------------------ */

/* Register activation hook. */
register_activation_hook( __FILE__, 'auto_hosted_activation' );


/**
 * Runs only when the plugin is activated.
 * @since 0.1.0
 */
function auto_hosted_activation() {

	/* Get the administrator role. */
	$role =& get_role( 'administrator' );

	/* If the administrator role exists, add required capabilities for the plugin. */
	if ( !empty( $role ) ) {
		$role->add_cap( 'manage_plugin_repo' );
		$role->add_cap( 'create_plugin_repos' );
		$role->add_cap( 'edit_plugin_repos' );
		$role->add_cap( 'manage_theme_repo' );
		$role->add_cap( 'create_theme_repos' );
		$role->add_cap( 'edit_theme_repos' );
	}

	/* uninstall plugin */
	register_uninstall_hook( __FILE__, 'auto_hosted_uninstall' );
}


/**
 * Uninstall plugin
 * @since 0.1.0
 */
function auto_hosted_uninstall(){

	/* Get the administrator role. */
	$role =& get_role( 'administrator' );

	/* If the administrator role exists, remove added capabilities for the plugin. */
	if ( !empty( $role ) ) {
		$role->remove_cap( 'manage_plugin_repo' );
		$role->remove_cap( 'create_plugin_repos' );
		$role->remove_cap( 'edit_plugin_repos' );
		$role->remove_cap( 'manage_theme_repo' );
		$role->remove_cap( 'create_theme_repos' );
		$role->remove_cap( 'edit_theme_repos' );
	}
}