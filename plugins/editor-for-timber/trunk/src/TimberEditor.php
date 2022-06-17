<?php

namespace TimberEditor;

use Timber\Loader;
use Timber\Post;
use Timber\Timber;

class TimberEditor
{
    /**
     * TimberEditor constructor.
     */
    public function __construct()
    {
        if (! class_exists(Timber::class)) {
            add_action('admin_notices', [$this, 'adminNoticeTimberLibraryMissing']);
            return;
        }
        if (! class_exists(\Classic_Editor::class)) {
            add_action('admin_notices', [$this, 'adminNoticeClassicEditor']);
        }

        $this->run();
    }

    /**
     * admin_notices action callback for missing Timber Library
     */
    public function adminNoticeTimberLibraryMissing()
    {
        ?>
        <div class="notice notice-error">
            <p>
                <a href="https://wordpress.org/plugins/timber-library/" target="_blank">Timber</a>
                (<a href="<?= admin_url('plugin-install.php?s=timber&tab=search&type=term') ?>">install</a>)
                needs to be installed and active.
            </p>
        </div>
        <?php
    }

    /**
     * admin_notices action callback for missing Classic Editor
     */
    public function adminNoticeClassicEditor()
    {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <a href="https://wordpress.org/plugins/classic-editor/" target="_blank">Classic Editor</a>
                (<a href="<?= admin_url('plugin-install.php?s=classic+editor&tab=search&type=term') ?>">install</a>)
                should be installed and active, because the <b>Gutenberg Editor</b> doesn't play well with <b>CodeMirror</b> <i>currently</i>.
            </p>
        </div>
        <?php
    }

    /**
     * Run the plugin
     */
    public function run()
    {
        if (is_null(Timber::$locations)) {
            Timber::$locations = self::getTemplatesLocation();
        }

        new Settings();
        new ThemeEditor();
        new MetaBox();
    }

    /**
     * Get the plugins' templates location
     * try to use user provided location via Timber::$locations
     * or fall back to uploads folder
     *
     * @return string
     */
    public static function getTemplatesLocation()
    {
        $location = Timber::$locations;
        if (is_array($location)) {
            $location = $location[array_key_first($location)];
        } else if (is_null($location)) {
            $location = wp_upload_dir()['basedir'] . '/timber-editor';
        }

        return apply_filters('TimberEditor/getTemplatesLocation', $location);
    }

    /**
     * Get the current posts' template filename
     *
     * @param int|null $postId
     *
     * @return string
     */
    public static function getTemplateFilename($postId = null)
    {
        return apply_filters('TimberEditor/getTemplateFilename', ($postId ?? get_the_ID()) . '.twig', $postId);
    }

    /**
     * Get the current posts' template filepath
     *
     * @param int|null $postId
     *
     * @return string
     */
    public static function getTemplateFilePath($postId = null)
    {
        return self::getTemplatesLocation() . '/' . self::getTemplateFilename($postId);
    }

    /**
     * @param array|string $filenames
     * @param array        $context
     * @param bool         $expires
     * @param string       $cacheMode
     *
     * @return bool|string
     */
    public static function render($filenames = [], $context = [], $expires = false, $cacheMode = Loader::CACHE_USE_DEFAULT)
    {
        $filenames = (array)$filenames;
        array_unshift($filenames, self::getTemplateFilename());

        return Timber::render($filenames, $context, $expires, $cacheMode);
    }

    /**
     * @param array|string $filenames
     * @param array        $context
     * @param bool         $expires
     * @param string       $cacheMode
     *
     * @return bool|string
     */
    public static function renderPost($filenames = [], $context = [], $expires = false, $cacheMode = Loader::CACHE_USE_DEFAULT)
    {
        if (! isset($context['post'])) {
            $context['post'] = new Post();
        }

        return self::render($filenames, $context, $expires, $cacheMode);
    }
}
