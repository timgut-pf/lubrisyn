<?php
/**
 * Template Name: Blog Landing Page Template
 */
if ( ! defined('ABSPATH') ) exit;

get_header();

if ( have_posts() ) :
  while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('lubrisyn-page lubrisyn-page--blog'); ?>>
      <div class="lubrisyn-container">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile;
endif;

get_footer();