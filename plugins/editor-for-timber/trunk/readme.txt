=== Editor for Timber ===
Contributors: drogueronin
Tags: timber, templates, twig
Requires at least: 5.5
Tested up to: 5.5.1
Stable tag: 1.0.2
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Page, Theme & Plugin Editor Extension for Timber http://www.upstatement.com/timber/

== Description ==

### Features

- adds a metabox to configurable Post-Types to create and edit Twig templates
- adds Twig support to the WP Theme & Plugin editors
  - you can even customize the CodeMirror theme

### Plugin Dependencies

- https://wordpress.org/plugins/timber-library/
- https://wordpress.org/plugins/classic-editor/

### How to use the MetaBox Feature

The plugin uses the path defined in `Timber::$locations` as the templates path or falls back to the uploads folder if none is provided.

Either adjust your `Timber::render('page.twig', $context)`-functions like this:
`
$filenames = [TimberEditor::getTemplateFilename(), 'page.twig'];
Timber::render($filenames, $context);
`

like this
`
TimberEditor::render('page.twig', $context);
# 'page.twig' serves as a fallback if the current posts's template doesn't exist
`

or like this
`
TimberEditor::renderPost('page.twig', $context);
# renderPost() automatically adds ['post' => new Timber\Post()] to the $context
`
