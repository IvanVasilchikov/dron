<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	$timber = new Timber\Timber();
}
if (function_exists('acf_add_options_page')) {

    acf_add_options_page();

}
/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

	add_action(
		'admin_notices',
		function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function( $template ) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
        add_filter('xmlrpc_enabled', '__return_false');
        remove_action('wp_head','feed_links_extra', 3); // убирает ссылки на RSS категорий
        remove_action('wp_head','feed_links', 2); // минус ссылки на основной RSS и комментарии
        remove_action('wp_head','rsd_link');  // удаляет RSD ссылку для удаленной публикации
        remove_action('wp_head','wlwmanifest_link'); // удаляет ссылку Windows для Live Writer
        remove_action('wp_head','wp_generator');  // удаляет версию WordPress
        remove_action('wp_head','start_post_rel_link',10,0);
        remove_action('wp_head','index_rel_link');
        remove_action('wp_head','adjacent_posts_rel_link_wp_head', 10, 0 ); // удаляет ссылки на предыдущую и следующую статьи
        remove_action('wp_head','wp_shortlink_wp_head', 10, 0 ); // удаляет короткую ссылку

// Отключаем type="application/json+oembed"
        remove_action( 'wp_head', 'rest_output_link_wp_head');
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links');
        remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
//для того, чтобы кавычки отображались такими, какие ввели
        remove_filter('the_content', 'wptexturize');
        remove_filter('the_title', 'wptexturize');

		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
        add_action('wp_enqueue_scripts', array($this, 'loadScripts'));
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
        add_action( 'wp_enqueue_scripts', array($this,'smartwp_remove_wp_block_library_css') );
        add_action( 'wp_footer',array($this, 'my_deregister_scripts' ));
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        add_filter( 'show_admin_bar',array($this,'wpse_99333_hide_admin_bar_from_front_end' ) );
        add_action( 'wp_enqueue_scripts', array($this,'rjs_lwp_contactform_css_js'));
		parent::__construct();
	}
    function rjs_lwp_contactform_css_js() {
        global $post;
        if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'contact-form-7') ) {
            wp_enqueue_script('contact-form-7');
            wp_enqueue_style('contact-form-7');

        }else{
            wp_dequeue_script( 'contact-form-7' );
            wp_dequeue_style( 'contact-form-7' );
        }
    }
    function wpse_99333_hide_admin_bar_from_front_end(){
        if ( is_blog_admin() ) {
            return true;
        }
        remove_action( 'wp_head', '_admin_bar_bump_cb' );
        return false;
    }
    function my_deregister_scripts(){
        wp_dequeue_script( 'wp-embed' );

    }
    function smartwp_remove_wp_block_library_css(){
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'global-styles' );
        wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
        if ( !is_admin() ){
            wp_dequeue_script( 'jquery');
            wp_deregister_script( 'jquery');
        }
    }
	/** This is where you can register custom post types. */
    public function register_post_types() {
      /*  $labels = array(
            'name' => 'doctors',
            'menu_name' => 'Врачи',
            'edit_item' => 'Edit',
            'add_new'  => 'Добавить ',
        );
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'supports' => array('title', 'thumbnail'), // title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'
            'public' => true,
            'map_meta_cap' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-admin-post',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'publicly_queryable' => true, // Добовляют ссылку к посту
            'exclude_from_search' => false,
            'has_archive' => true,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => array("slug" => "doctors"), // URL
            'capability_type' => 'post'
        );

        register_post_type('doctors', $args);*/
    }
    /** This is where you can register custom taxonomies. */
    public function register_taxonomies() {
       /* $labels = array(
            'name' => 'specialty',
            'menu_name' => 'Специальность',
            'edit_item' => 'Редактировать',
        );
        register_taxonomy('tax_doctors', array('doctors'), array(
            'labels' => $labels,
            'hierarchical' => true,
            'show_ui' => true,
            'query_var' => true,
            'has_archive' => true,
            'rewrite' => array("slug" => "specialty-doctors"), // не поворять название поста
            'show_admin_column' => true,
            'show_in_nav_menus' => true
        ));*/
    }

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$context['foo']   = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::context();';
		$context['menu']  = new Timber\Menu();
		$context['site']  = $this;
        $context['options'] = get_fields('option');
		return $context;
	}

	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );
	}

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig\Extension\StringLoaderExtension() );
		$twig->addFilter( new Twig\TwigFilter( 'myfoo', array( $this, 'myfoo' ) ) );
        /*$twig->addFunction( new Timber\Twig_Function( 'percent', array( $this, 'percent' ) ) );*/
		return $twig;
	}

    public function loadScripts()
    {


        $version = '1';




        wp_enqueue_script('js-main', get_template_directory_uri() . '/static/js/main.js', array(), $version, true);
        wp_enqueue_style('css-style', get_template_directory_uri() . '/static/styles/main.min.css', null, $version);
        if(is_front_page()){
            wp_enqueue_script('js-vendor', get_template_directory_uri() . '/static/js/index.js', array(),$version, true);
            wp_enqueue_style('css-page', get_template_directory_uri() . '/static/styles/index.min.css', null, $version);
        }
        if(is_page(278)){
            wp_enqueue_script('js-vendor', get_template_directory_uri() . '/static/js/stocks.js', array(), '1.0.2', true);
            wp_enqueue_style('css-page', get_template_directory_uri() . '/static/styles/stocks.min.css', null, $version);
        }
      /*  if(is_singular('locations')){
            wp_enqueue_script('js-vendor', get_template_directory_uri() . '/static/js/page/location-detail.js', array(), '1.0.2', true);
        }
        if(is_singular('services')){
            wp_enqueue_script('js-vendor', get_template_directory_uri() . '/static/js/page/services-detail.js', array(), '1.0.2', true);
        }

        if(is_page(243)){
            wp_enqueue_script('js-vendor', get_template_directory_uri() . '/static/js/page/vacancy.js', array(), '1.0.2', true);
        }
        if(is_page(259)){
            wp_enqueue_script('js-vendor', get_template_directory_uri() . '/static/js/lib/fancybox.esm.js', array(), '1.0.2', true);
        }*/

    }

}

new StarterSite();
