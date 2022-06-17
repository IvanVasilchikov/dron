<?php
/**
 * Plugin Name: Editor for Timber
 * Description: Admin Editor for Timber
 * Plugin URI: https://gitlab.com/DRogueRonin/wp-plugin-timber-editor
 * Author: Daniel Weipert
 * Version: 1.0.2
 * Author URI: https://dweipert.de
*/

require 'vendor/autoload.php';

add_action('plugins_loaded', 'timberEditor');
function timberEditor() {
    new \TimberEditor\TimberEditor();
}

function timberEditorAssetsUrl($path) {
    return plugins_url("assets/$path", __FILE__);
}
