<?php
/**
 * Plugin Name: CJC Recipe System
 * Plugin URI: https://curtisjcooks.com
 * Description: Custom recipe post type with structured metadata, REST API, Gutenberg block, schema markup, and migration tools for CurtisJCooks.com.
 * Version: 1.0.0
 * Author: Curtis Vaughan
 * Author URI: https://curtisjcooks.com
 * License: GPL-2.0-or-later
 * Text Domain: cjc-recipe-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CJC_RECIPE_VERSION', '1.0.0' );
define( 'CJC_RECIPE_DIR', plugin_dir_path( __FILE__ ) );
define( 'CJC_RECIPE_URL', plugin_dir_url( __FILE__ ) );

// Load class files (guarded to avoid conflicts if theme also loads them).
if ( ! class_exists( 'CJC_Recipe_Post_Type' ) ) {
	require_once CJC_RECIPE_DIR . 'includes/class-cjc-recipe-post-type.php';
}
if ( ! class_exists( 'CJC_Recipe_Meta' ) ) {
	require_once CJC_RECIPE_DIR . 'includes/class-cjc-recipe-meta.php';
}
if ( ! class_exists( 'CJC_Recipe_REST_API' ) ) {
	require_once CJC_RECIPE_DIR . 'includes/class-cjc-recipe-rest-api.php';
}
if ( ! class_exists( 'CJC_Recipe_Schema' ) ) {
	require_once CJC_RECIPE_DIR . 'includes/class-cjc-recipe-schema.php';
}
if ( ! class_exists( 'CJC_Recipe_Block' ) ) {
	require_once CJC_RECIPE_DIR . 'includes/class-cjc-recipe-block.php';
}
if ( ! class_exists( 'CJC_Recipe_Migration' ) ) {
	require_once CJC_RECIPE_DIR . 'includes/class-cjc-recipe-migration.php';
}

/**
 * Initialize all recipe system classes.
 *
 * Uses plugins_loaded so it runs before theme functions.php.
 * The theme can then skip its own init if the plugin is active.
 */
function cjc_recipe_system_init() {
	CJC_Recipe_Post_Type::init();
	CJC_Recipe_Meta::init();
	CJC_Recipe_REST_API::init();
	CJC_Recipe_Schema::init();
	CJC_Recipe_Block::init();
	CJC_Recipe_Migration::init();
}
add_action( 'plugins_loaded', 'cjc_recipe_system_init' );

/**
 * Flush rewrite rules on activation.
 */
function cjc_recipe_system_activate() {
	CJC_Recipe_Post_Type::register_post_type();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'cjc_recipe_system_activate' );
