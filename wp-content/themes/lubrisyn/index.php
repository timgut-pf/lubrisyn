<?php
if ( ! defined('ABSPATH') ) exit;
get_header();

if ( have_posts() ) :
  echo '<div style="max-width:1440px;margin:0 auto;padding:40px 20px;">';
  while ( have_posts() ) : the_post(); ?>
    <article style="margin-bottom:30px;">
      <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <?php the_excerpt(); ?>
    </article>
  <?php endwhile;
  echo '</div>';
endif;

get_footer();
