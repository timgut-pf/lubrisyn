<?php
/**
 * Template Name: Where to Buy Page Template
 */
if ( ! defined('ABSPATH') ) exit;

get_header();

if ( have_posts() ) :
  while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('lubrisyn-page lubrisyn-page--where-to-buy'); ?>>
      <?php the_content(); ?>
    </article>
  <?php endwhile;
endif;

get_footer();