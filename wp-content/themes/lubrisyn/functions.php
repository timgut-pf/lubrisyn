<?php
/**
 * LubriSyn Theme functions
 */
if ( ! defined('ABSPATH') ) exit;

require_once get_template_directory() . '/inc/blocks.php';

add_action('after_setup_theme', function () {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
  add_theme_support('editor-styles');
  add_theme_support('wp-block-styles');
  add_theme_support('responsive-embeds');

  // Menus
  register_nav_menus([
    'primary' => __('Primary Menu', 'lubrisyn'),
    'footer_company' => __('Footer Company', 'lubrisyn'),
    'footer_products' => __('Footer Products', 'lubrisyn'),
  ]);
});

add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style('lubrisyn-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
});

/**
 * Block pattern category + patterns
 */
add_action('init', function () {
  if ( function_exists('register_block_pattern_category') ) {
    register_block_pattern_category('lubrisyn', [
      'label' => __('LubriSyn', 'lubrisyn')
    ]);
  }

  // Auto-register all patterns in /patterns
  $pattern_dir = get_template_directory() . '/patterns';
  if ( is_dir($pattern_dir) ) {
    foreach ( glob($pattern_dir . '/*.php') as $file ) {
      register_block_pattern(
        'lubrisyn/' . basename($file, '.php'),
        require $file
      );
    }
  }
});

add_action('acf/init', function () {
  if ( ! function_exists('acf_register_block_type') ) return;

  // Register block (field group for this block lives in ACF UI / acf-json)
  acf_register_block_type([
    'name'            => 'lubrisyn-hero',
    'title'           => __('LubriSyn Hero', 'lubrisyn'),
    'description'     => __('Homepage hero with background image, overlay card, and product dropdown.', 'lubrisyn'),
    'category'        => 'layout',
    'icon'            => 'cover-image',
    'keywords'        => ['hero', 'lubrisyn', 'banner'],
    'mode'            => 'preview',
    'supports'        => [
      'align' => ['full'],
      'anchor' => true,
    ],
    'render_template' => get_template_directory() . '/acf-blocks/lubrisyn-hero/render.php',
    'enqueue_assets'  => function () {
      wp_enqueue_style(
        'lubrisyn-hero-block',
        get_template_directory_uri() . '/acf-blocks/lubrisyn-hero/hero.css',
        [],
        wp_get_theme()->get('Version')
      );
      wp_enqueue_script(
        'lubrisyn-hero-block',
        get_template_directory_uri() . '/acf-blocks/lubrisyn-hero/hero.js',
        [],
        wp_get_theme()->get('Version'),
        true
      );
    },
  ]);
});