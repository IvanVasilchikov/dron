<?php

namespace TimberEditor;

class MetaBox
{
    /**
     * Metabox constructor.
     */
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'addMetaBoxes']);

        foreach (Settings::getGeneralSupportedPostTypes() as $postType) {
            add_action("save_post_{$postType}", [$this, 'savePost']);
        }
    }

    /**
     * add_meta_boxes action callback
     *
     * @param $postType
     */
    public function addMetaBoxes($postType)
    {
        if (! in_array($postType, Settings::getGeneralSupportedPostTypes())) {
            return;
        }

        add_meta_box('timber-editor', 'Timber Editor', [$this, 'metaBoxTimberEditor'], '', 'advanced', 'high');
    }

    /**
     * save_post action callback
     * Writes the content to file
     *
     * @param $postId
     */
    public function savePost($postId)
    {
        if (
            (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
            (! isset($_POST['post_ID']) || $_POST['post_ID'] != $postId) ||
            ! check_admin_referer('metaBoxTimberEditor', 'metaBoxTimberEditor') ||
            ! isset($_POST['timber-editor_content'])
        ) {
            return;
        }

        $content = wp_kses($_POST['timber-editor_content'], wp_kses_allowed_html('post'));

        file_put_contents(TimberEditor::getTemplateFilePath($postId), $content);
        if (empty($_POST['timber-editor_content'])) {
            wp_delete_file(TimberEditor::getTemplateFilePath($postId));
        }
    }

    /**
     * add_meta_box callback
     */
    public function metaBoxTimberEditor()
    {
        $file = TimberEditor::getTemplateFilePath();
        if (file_exists($file)) {
            $f = fopen($file, 'r');
            $content = fread($f, filesize($file));
            fclose($f);
        }

        wp_nonce_field('metaBoxTimberEditor', 'metaBoxTimberEditor');
        ?>
        <textarea name="timber-editor_content" id="timber-editor_content"><?= esc_textarea($content ?? '') ?></textarea>
        <?php

        $settings = wp_enqueue_code_editor([
            'file' => $file,
            'codemirror' => [
                'theme' => Settings::getCodeMirrorTheme(),
            ],
        ]);
        wp_add_inline_script('code-editor', sprintf('jQuery( function() { wp.codeEditor.initialize( "timber-editor_content", %s ); } );', wp_json_encode($settings)));
    }
}
