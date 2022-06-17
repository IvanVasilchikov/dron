<?php

namespace TimberEditor;

class Settings
{
    /**
     * Settings constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'adminMenu']);
        add_action('admin_init', [$this, 'adminInit']);
    }

    /**
     * admin_menu action callback
     */
    public function adminMenu()
    {
        add_submenu_page('options-general.php', 'Timber Editor', 'Timber Editor', 'manage_options', 'timber-editor', [$this, 'addSubmenuPage']);
    }

    /**
     * add_submenu_page callback
     */
    public function addSubmenuPage()
    {
        ?>
        <div class="wrap">
            <h1><?= esc_html(get_admin_page_title()) ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('timber-editor');
                do_settings_sections('timber-editor');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * admin_init action callback
     */
    public function adminInit()
    {
        register_setting('timber-editor', 'timber-editor_general_supported-post-types');
        add_settings_section('timber-editor_general', __('General'), function () {}, 'timber-editor');
        add_settings_field('timber-editor_general_supported-post-types', __('Supported Post Types'), function () {
            $postTypes = get_post_types([], 'objects');
            $supportedPostTypes = self::getGeneralSupportedPostTypes();
            ?>
            <select name="timber-editor_general_supported-post-types[]" id="timber-editor_general_supported-post-types" multiple>
                <?php foreach ($postTypes as $pt): ?>
                    <option value="<?= $pt->name ?>" <?= in_array($pt->name, $supportedPostTypes) ? 'selected' : '' ?>><?= $pt->label ?></option>
                <?php endforeach; ?>
            </select>
            <?php
        }, 'timber-editor', 'timber-editor_general', ['label_for' => 'timber-editor_general_supported-post-types']);

        register_setting('timber-editor', 'timber-editor_codemirror_theme');
        add_settings_section('timber-editor_codemirror', 'CodeMirror', function () {}, 'timber-editor');
        add_settings_field('timber-editor_codemirror_theme', 'Theme', function () {
            $theme = self::getCodeMirrorTheme();
            $themes = include_once 'codemirror-themes.php';
            ?>
            <select name="timber-editor_codemirror_theme" id="timber-editor_codemirror_theme">
                <?php foreach ($themes as $t): ?>
                    <option value="<?= $t ?>" <?php selected($theme, $t) ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>
            <p class="description">
                <a href="https://codemirror.net/demo/theme.html#<?= $theme ?>" target="_blank">
                    <?= __('Preview') ?>
                </a>
            </p>
            <?php
        }, 'timber-editor', 'timber-editor_codemirror', ['label_for' => 'timber-editor_codemirror_theme']);
    }

    /**
     * @return string[]
     */
    public static function getGeneralSupportedPostTypes()
    {
        return get_option('timber-editor_general_supported-post-types', ['page']) ?: [];
    }

    /**
     * @return string
     */
    public static function getCodeMirrorTheme()
    {
        return get_option('timber-editor_codemirror_theme', 'default');
    }
}
