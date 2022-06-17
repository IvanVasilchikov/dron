<?php
/**
 * Factory Bootstrap
 *
 * @author        Alex Kovalev <alex.kovalevv@gmail.com>
 * @since         1.0.0
 * @package       factory-bootstrap
 * @copyright (c) 2018, Webcraftic Ltd
 *
 */

// Exit if accessed directly
if( !defined('ABSPATH') ) {
	exit;
}

// module provides function only for the admin area
if( !is_admin() ) {
	return;
}

if( defined('FACTORY_BOOTSTRAP_6A13D960FB08EBDB339A72CC7FE28071_457_LOADED') ) {
	return;
}

define('FACTORY_BOOTSTRAP_6A13D960FB08EBDB339A72CC7FE28071_457_VERSION', '4.5.7');
define('FACTORY_BOOTSTRAP_6A13D960FB08EBDB339A72CC7FE28071_457_LOADED', true);

if( !defined('FACTORY_FLAT_ADMIN') ) {
	define('FACTORY_FLAT_ADMIN', true);
}

define('FACTORY_BOOTSTRAP_6A13D960FB08EBDB339A72CC7FE28071_457_DIR', dirname(__FILE__));
define('FACTORY_BOOTSTRAP_6A13D960FB08EBDB339A72CC7FE28071_457_URL', plugins_url(null, __FILE__));

require_once(FACTORY_BOOTSTRAP_6A13D960FB08EBDB339A72CC7FE28071_457_DIR . '/includes/functions.php');

/**
 * @param Wbcr_Factory723e1da7c1403346cdca8f259c016fc4_456_Plugin $plugin
 */
add_action('wbcr_factory_bootstrap_6a13d960fb08ebdb339a72cc7fe28071_457_plugin_created', function ($plugin) {
	$manager = new Wbcr_FactoryBootstrap6a13d960fb08ebdb339a72cc7fe28071_457_Manager($plugin);
	$plugin->setBootstap($manager);
});


