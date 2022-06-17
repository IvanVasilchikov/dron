<?php
/**
 * Factory Templates
 *
 * @author        Alex Kovalev <alex.kovalevv@gmail.com>, Github: https://github.com/alexkovalevv
 * @since         1.0.2
 * @package       clearfy
 * @copyright (c) 2018, Webcraftic Ltd
 *
 */

// Exit if accessed directly
if( !defined('ABSPATH') ) {
	exit;
}

if( defined('FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_LOADED') ) {
	return;
}

define('FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_LOADED', true);

define('FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107', '1.0.7');

define('FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_DIR', dirname(__FILE__));
define('FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_URL', plugins_url(null, __FILE__));

load_plugin_textdomain('wbcr_factory_templates_204e5d1397aaf784fcbb05c582936ff1_107', false, dirname(plugin_basename(__FILE__)) . '/langs');

require(FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_DIR . '/includes/ajax-handlers.php');
require(FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_DIR . '/includes/class-helpers.php');
require(FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_DIR . '/includes/class-configurate.php');

// module provides function only for the admin area
if( is_admin() ) {
	/**
	 * Подключаем скрипты для установки компонентов Clearfy
	 * на все страницы админпанели.
	 */
	add_action('admin_enqueue_scripts', function ($hook) {
		wp_enqueue_script('wbcr-factory-templates-204e5d1397aaf784fcbb05c582936ff1_107-global', FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_URL . '/assets/js/clearfy-globals.js', [
			'jquery',
			'wfactory-723e1da7c1403346cdca8f259c016fc4_456-core-general'
		], FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107);

		require_once FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_DIR . '/includes/class-search-options.php';
		$all_options = \WBCR\FactoryTemplates_204e5d1397aaf784fcbb05c582936ff1_107\Search_Options::get_all_options();

		if( empty($all_options) ) {
			return;
		}

		$allow_print_data = false;
		$formated_options = [];

		foreach($all_options as $option) {
			//if( !$allow_print_data && isset($_GET['page']) && $option['page_id'] === $_GET['page'] ) {
			//$allow_print_data = true;
			//}

			$formated_options[] = [
				'value' => $option['title'],
				'data' => [
					//'hint' => isset($option['hint']) ? $option['hint'] : '',
					'page_url' => $option['page_url'],
					'page_id' => $option['page_id']
				]
			];
		}

		//if( !$allow_print_data ) {
		//return;
		//}

		wp_localize_script('wbcr-factory-templates-204e5d1397aaf784fcbb05c582936ff1_107-global', 'wfactory_clearfy_search_options', $formated_options);
	});

	if( defined('FACTORY_PAGES_A5B5EF22D149CD7CE4758112710A341D_455_LOADED') ) {
		require(FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_DIR . '/pages/templates/impressive/class-page-template-impressive.php');
		require(FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_DIR . '/pages/templates/impressive/class-pages.php');
		require(FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_DIR . '/pages/templates/impressive-lite/class-page-template-impressive-lite.php');

		require(FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_DIR . '/pages/class-page-more-features.php');
		require(FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_DIR . '/pages/class-page-license.php');
		require(FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_DIR . '/pages/class-pages-components.php');

		require(FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_DIR . '/pages/setup-parts/class-step.php');
		require(FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_DIR . '/pages/setup-parts/class-step-form.php');
		require(FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_DIR . '/pages/setup-parts/class-step-custom.php');
		require(FACTORY_TEMPLATES_204E5D1397AAF784FCBB05C582936FF1_107_DIR . '/pages/class-page-setup.php');
	}
}