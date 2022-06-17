<?php

namespace TimberEditor;

class ThemeEditor
{
    /**
     * ThemeEditor constructor.
     */
    public function __construct()
    {
        add_filter('wp_theme_editor_filetypes', [$this, 'editableExtensions']);
        add_filter('editable_extensions', [$this, 'editableExtensions']);
        add_filter('wp_code_editor_settings', [$this, 'codeEditorSettings'], 10, 2);
        add_action('wp_enqueue_code_editor', [$this, 'enqueueCodeEditor']);
    }

    /**
     * wp_theme_editor_filetypes and editable_extensions filter callback
     * Adds twig extension support
     *
     * @param $types
     *
     * @return array
     */
    public function editableExtensions($types)
    {
        $types[] = 'twig';

        return $types;
    }

    /**
     * wp_code_editor_settings filter callback
     * Adds twig support and sets theme
     *
     * @param $settings
     * @param $args
     *
     * @return array
     */
    public function codeEditorSettings($settings, $args) {
        if (isset($args['file']) && strpos($args['file'], '.twig') !== false) {
            $settings['codemirror']['mode'] = ['name' => 'twig', 'base' => 'text/html'];
        }

        $settings['codemirror']['theme'] = Settings::getCodeMirrorTheme();

        return $settings;
    }

    /**
     * wp_enqueue_code_editor action callback
     * Adds twig and custom mode support
     * Adds selected theme css
     *
     * @param $settings
     */
    public function enqueueCodeEditor($settings) {
        if (isset($settings['codemirror']['mode']['name']) && $settings['codemirror']['mode']['name'] == 'twig') {
            wp_add_inline_script( # fix as described here: https://make.wordpress.org/core/2017/10/22/code-editing-improvements-in-wordpress-4-9/
                'wp-codemirror',
                'window.CodeMirror = wp.CodeMirror;'
            );
            wp_enqueue_script('codemirror-mode-twig', timberEditorAssetsUrl('codemirror/mode/twig/twig.js'), ['wp-codemirror']);
        }

        $theme = $settings['codemirror']['theme'];
        if ($theme != 'default') {
            wp_enqueue_style('codemirror-theme', timberEditorAssetsUrl("codemirror/theme/$theme.css"), ['wp-codemirror']);
        }
    }
}
