<?php
/**
 * The file contains a base class for update items of plugins.
 *
 * @author        Alex Kovalev <alex.kovalevv@gmail.com>, repo: https://github.com/alexkovalevv
 * @author        Webcraftic <wordpress.webraftic@gmail.com>, site: https://webcraftic.com
 *
 * @package       factory-core
 * @since         1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Plugin Activator
 *
 * @since 1.0.0
 */
abstract class Wbcr_Factory723e1da7c1403346cdca8f259c016fc4_456_Update {

	/**
	 * Current plugin
	 *
	 * @var Wbcr_Factory723e1da7c1403346cdca8f259c016fc4_456_Plugin
	 */
	var $plugin;

	public function __construct( Wbcr_Factory723e1da7c1403346cdca8f259c016fc4_456_Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	abstract function install();

	//abstract function rollback();
}
