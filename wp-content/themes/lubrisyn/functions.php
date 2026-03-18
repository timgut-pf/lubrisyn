<?php
/**
 * LubriSyn Theme functions
 */


if ( ! defined('ABSPATH') ) exit;

add_action('after_setup_theme', function () {
  add_theme_support('custom-logo');
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
  add_theme_support('editor-styles');
  add_theme_support('wp-block-styles');
  add_theme_support('responsive-embeds');
  add_theme_support('align-wide');
  add_editor_style([
    'css/main.css',
    'css/editor.css',
  ]);

  // Menus
  register_nav_menus([
    'primary' => __('Primary Menu', 'lubrisyn'),
    'footer_company' => __('Footer Company', 'lubrisyn'),
    'footer_products' => __('Footer Products', 'lubrisyn'),
  ]);
});

function lubrisyn_asset_version( $relative_path ) {
  $absolute_path = get_template_directory() . $relative_path;
  return file_exists( $absolute_path ) ? filemtime( $absolute_path ) : wp_get_theme()->get('Version');
}

add_action('wp_enqueue_scripts', function () {
  // Theme Metadata (Empty except for comment)
  wp_enqueue_style('lubrisyn-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));

  wp_enqueue_style(
    'lubrisyn-main', 
    get_template_directory_uri() . '/css/main.css', 
    ['lubrisyn-style'], 
    lubrisyn_asset_version('/css/main.css')
  );

  //Component Styles
  wp_enqueue_style(
    'lubrisyn-header', 
    get_template_directory_uri() . '/css/header.css', 
    ['lubrisyn-style'], // Depends on main style (variables)
    lubrisyn_asset_version('/css/header.css')
  );

  wp_enqueue_style(
    'lubrisyn-footer', 
    get_template_directory_uri() . '/css/footer.css', 
    ['lubrisyn-style'], 
    lubrisyn_asset_version('/css/footer.css')
  );

  // Dashicons
  wp_enqueue_style('dashicons');


  // Swiper Assets (CDN)
  wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], '11.0.0');
  wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], '11.0.0', true);

  wp_enqueue_style(
    'lubrisyn-swiper-custom', 
    get_template_directory_uri() . '/css/swiper.css', 
    ['swiper-css', 'lubrisyn-style'],
    lubrisyn_asset_version('/css/swiper.css')
  );

  //  Main Navigation Script
  wp_enqueue_script(
      'lubrisyn-navigation', 
      get_template_directory_uri() . '/navigation.js', 
      [], 
      file_exists(get_template_directory() . '/navigation.js') ? filemtime(get_template_directory() . '/navigation.js') : '1.0', 
      true 
  );

  // 5. Hero Slider Initialization
  // We list 'swiper-js' and 'jquery' as dependencies so they load FIRST
  wp_enqueue_script(
      'hero-slider-init', 
      get_template_directory_uri() . '/assets/js/hero-slider.js', 
      ['swiper-js', 'jquery'], 
      lubrisyn_asset_version('/assets/js/hero-slider.js'),
      true
  );
});

add_action('enqueue_block_assets', function () {
  if ( ! is_admin() ) {
    return;
  }

  // Load Adobe Fonts / Typekit in the editor so fonts match the frontend.
  wp_enqueue_style(
    'lubrisyn-typekit',
    'https://use.typekit.net/iid8ijd.css',
    [],
    null
  );

  wp_enqueue_style(
    'swiper-css',
    'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
    [],
    '11.0.0'
  );

  wp_enqueue_style(
    'lubrisyn-swiper-custom',
    get_template_directory_uri() . '/css/swiper.css',
    ['swiper-css'],
    lubrisyn_asset_version('/css/swiper.css')
  );

  wp_enqueue_script(
    'swiper-js',
    'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
    [],
    '11.0.0',
    true
  );

  wp_enqueue_script(
    'hero-slider-init',
    get_template_directory_uri() . '/assets/js/hero-slider.js',
    ['swiper-js', 'jquery'],
    lubrisyn_asset_version('/assets/js/hero-slider.js'),
    true
  );
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

/**
 * Create ACF Options Page
 */
if( function_exists('acf_add_options_page') ) {
    
  acf_add_options_page(array(
      'page_title'    => 'Site Settings',
      'menu_title'    => 'Site Settings',
      'menu_slug'     => 'site-settings',
      'capability'    => 'edit_posts',
      'redirect'      => false,
      'icon_url'      => 'dashicons-admin-generic',
  ));
  
}


add_action('acf/init', function() {
  // Path to your blocks directory
  $blocks_path = get_template_directory() . '/template-parts/blocks';

  // Scan the directory for folders
  $blocks = scandir($blocks_path);

  foreach ($blocks as $block_folder) {
      // Skip hidden files/folders
      if ($block_folder === '.' || $block_folder === '..') continue;

      $json_file = "$blocks_path/$block_folder/block.json";

      // Check if the block.json file exists
      if (file_exists($json_file)) {
          register_block_type($json_file);
      }
  }
});

/**
 * Populate ACF Select field with Gravity Forms
 */
function acf_load_gravity_forms_choices( $field ) {
    
  // Clear any existing choices
  $field['choices'] = array();
  
  // Check if Gravity Forms is active
  if ( class_exists( 'GFAPI' ) ) {
      $forms = GFAPI::get_forms();
      
      foreach ( $forms as $form ) {
          // value => Label
          $field['choices'][ $form['id'] ] = $form['title'];
      }
  }
  
  return $field;
}

// Target the specific field name (change 'form_id to your actual field name)
add_filter('acf/load_field/name=sform_id', 'acf_load_gravity_forms_choices');


/**
 * Register Custom Post Type: Team
 */
function register_team_cpt() {
  $labels = array(
      'name'               => 'Team',
      'singular_name'      => 'Team Member',
      'menu_name'          => 'Team',
      'add_new'            => 'Add New Member',
      'add_new_item'       => 'Add New Team Member',
      'edit_item'          => 'Edit Team Member',
      'all_items'          => 'All Team Members',
  );

  $args = array(
      'labels'             => $labels,
      'public'             => true,
      'has_archive'        => false,
      'menu_icon'          => 'dashicons-groups', // Icon of people
      'supports'           => array( 'title', 'thumbnail' ), // Name and Photo
      'rewrite'            => array('slug' => 'team'),
      'show_in_rest'       => true, // Enables Gutenberg
  );

  register_post_type( 'team', $args );
}
add_action( 'init', 'register_team_cpt' );







