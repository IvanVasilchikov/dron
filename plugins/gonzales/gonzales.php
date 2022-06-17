<?php
/**
 * Plugin Name: Webcraftic Assets manager
 * Plugin URI: https://wordpress.org/plugins/gonzales/
 * Description: Increase the speed of the pages by disabling unused scripts (.JS) and styles (.CSS). Make your website REACTIVE!
 * Author: Webcraftic <wordpress.webraftic@gmail.com>
 * Version: 2.1.5
 * Text Domain: gonzales
 * Domain Path: /languages/
 * Author URI: https://webcraftic.com
 * Framework Version: FACTORY_723E1DA7C1403346CDCA8F259C016FC4_456_VERSION
 */

// Exit if accessed directly
if( !defined('ABSPATH') ) {
	exit;
}

/**
 * Developers who contributions in the development plugin:
 *
 * Alexander Kovalev
 * ---------------------------------------------------------------------------------
 * Full plugin development.
 *
 * Email:         alex.kovalevv@gmail.com
 * Personal card: https://alexkovalevv.github.io
 * Personal repo: https://github.com/alexkovalevv
 * ---------------------------------------------------------------------------------
 */

/**
 * -----------------------------------------------------------------------------
 * CHECK REQUIREMENTS
 * Check compatibility with php and wp version of the user's site. As well as checking
 * compatibility with other plugins from Webcraftic.
 * -----------------------------------------------------------------------------
 */

require_once(dirname(__FILE__) . '/libs/factory/core/includes/class-factory-requirements.php');

// @formatter:off
$wgnz_plugin_info = [
	'prefix' => 'wbcr_gnz_',
	'plugin_name' => 'wbcr_gonzales',
	'plugin_title' => __('Webcraftic assets manager', 'gonzales'),

	// PLUGIN SUPPORT
	'support_details' => [
		'url' => 'https://clearfy.pro',
		'pages_map' => [
			'support' => 'support',         // {site}/support
			'docs' => 'docs'               // {site}/docs
		]
	],

	// PLUGIN SUBSCRIBE FORM
	'subscribe_widget' => true,
	'subscribe_settings' => ['group_id' => '105408913'],

	// PLUGIN ADVERTS
	'render_adverts' => true,
	'adverts_settings' => [
		'dashboard_widget' => true, // show dashboard widget (default: false)
		'right_sidebar' => true, // show adverts sidebar (default: false)
		'notice' => true, // show notice message (default: false)
	],

	// FRAMEWORK MODULES
	'load_factory_modules' => [
		['libs/factory/bootstrap', 'factory_bootstrap_6a13d960fb08ebdb339a72cc7fe28071_457', 'admin'],
		['libs/factory/forms', 'factory_forms_0c0d8ea3f46097b6066de1bed10ef3d9_453', 'admin'],
		['libs/factory/pages', 'factory_pages_a5b5ef22d149cd7ce4758112710a341d_455', 'admin'],
		['libs/factory/templates', 'factory_templates_204e5d1397aaf784fcbb05c582936ff1_107', 'all'],
		['libs/factory/adverts', 'factory_adverts_da9c21d3fe63c67e5c6c18d244e4abee_133', 'admin']
	]
];

$wgnz_compatibility = new Wbcr_Factory723e1da7c1403346cdca8f259c016fc4_456_Requirements(__FILE__, array_merge($wgnz_plugin_info, [
	'plugin_already_activate' => defined('WGZ_PLUGIN_ACTIVE'),
	'required_php_version' => '7.0',
	'required_wp_version' => '4.2.0',
	'required_clearfy_check_component' => false
]));

/**
 * If the plugin is compatible, then it will continue its work, otherwise it will be stopped,
 * and the user will throw a warning.
 */
if( !$wgnz_compatibility->check() ) {
	return;
}

/**
 * -----------------------------------------------------------------------------
 * CONSTANTS
 * Install frequently used constants and constants for debugging, which will be
 * removed after compiling the plugin.
 * -----------------------------------------------------------------------------
 */

// This plugin is activated
define('WGZ_PLUGIN_ACTIVE', true);
define('WGZ_PLUGIN_VERSION', $wgnz_compatibility->get_plugin_version());
define('WGZ_PLUGIN_DIR', dirname(__FILE__));
define('WGZ_PLUGIN_BASE', plugin_basename(__FILE__));
define('WGZ_PLUGIN_URL', plugins_url(null, __FILE__));


/**
 * -----------------------------------------------------------------------------
 * PLUGIN INIT
 * -----------------------------------------------------------------------------
 */

require_once(WGZ_PLUGIN_DIR . '/libs/factory/core/boot.php');
require_once(WGZ_PLUGIN_DIR . '/includes/functions.php');
require_once(WGZ_PLUGIN_DIR . '/includes/class-plugin.php');

try {
	new WGZ_Plugin(__FILE__, array_merge($wgnz_plugin_info, [
		'plugin_version' => WGZ_PLUGIN_VERSION,
		'plugin_text_domain' => $wgnz_compatibility->get_text_domain(),
	]));
} catch( Exception $e ) {
	// Plugin wasn't initialized due to an error
	define('WGZ_PLUGIN_THROW_ERROR', true);

	$wgnz_plugin_error_func = function () use ($e) {
		$error = sprintf("The %s plugin has stopped. <b>Error:</b> %s Code: %s", 'Webcraftic Assets Manager', $e->getMessage(), $e->getCode());
		echo '<div class="notice notice-error"><p>' . $error . '</p></div>';
	};

	add_action('admin_notices', $wgnz_plugin_error_func);
	add_action('network_admin_notices', $wgnz_plugin_error_func);
}
// @formatter:on
