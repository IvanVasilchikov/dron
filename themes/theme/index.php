<?php
/**
 * Template Name: main
 */


$context          = Timber::context();
$context['data']=get_fields();
$templates        = array( 'index.twig' );
/*$args = array(
    'post_type' => 'services',
    'posts_per_page' => -1,
    'order'       => 'ASC',
    'meta_key'		=> 'show_on_main',
    'meta_value'	=> 1
);
$context["services"] = Timber::get_posts($args);*/
Timber::render( $templates, $context );
