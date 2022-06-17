<?php

/**
 * Activator for the cyrlitera
 *
 * @author        Alex Kovalev <alex.kovalevv@gmail.com>, Github: https://github.com/alexkovalevv
 * @copyright (c) 09.03.2018, Webcraftic
 * @see           Wbcr_Factory723e1da7c1403346cdca8f259c016fc4_456_Activator
 * @version       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WGNZ_Activation extends Wbcr_Factory723e1da7c1403346cdca8f259c016fc4_456_Activator {

	/**
	 * Runs activation actions.
	 */
	public function activate() {
		wbcr_gnz_deploy_mu_plugin();
	}

	/**
	 * Runs deactivation actions.
	 */
	public function deactivate() {
		wbcr_gnz_remove_mu_plugin();
	}
}
