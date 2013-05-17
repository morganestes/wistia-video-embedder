<?php
/**
 * Embed a Wistia video into any post or page using a configurable shortcode.
 *
 * @package WistiaVideoEmbedder
 * @author  Morgan Estes <morgan.estes@gmail.com>
 * @license GPL-2.0+
 * @link    TODO
 *
 * @wordpress-plugin
 * Plugin Name: Wistia Video Embedder
 * Plugin URI:  TODO
 * Description: Embed a Wistia video into any post or page using a configurable shortcode.
 * Version:     1.0.0
 * Author:      Morgan Estes <morgan.estes@gmail.com>
 * Author URI:  http://morganestes.me
 * Text Domain: wistia-video-embedder
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-wistia-video-embedder.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'WistiaVideoEmbedder', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WistiaVideoEmbedder', 'deactivate' ) );

WistiaVideoEmbedder::get_instance();
