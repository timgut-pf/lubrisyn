<?php
/**
 * Template Name: Home Page Template
 */
if ( ! defined('ABSPATH') ) exit;

get_header();

if ( have_posts() ) :
  while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('lubrisyn-page lubrisyn-page--home'); ?>>
      <?php the_content(); ?>
    </article>
  <?php endwhile;
endif;

get_footer();