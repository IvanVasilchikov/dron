<?php
/**
 * Template Name: stocks
 */


$context          = Timber::context();
$context['data']=get_fields();
$templates        = array( 'stocks.twig' );

Timber::render( $templates, $context );
