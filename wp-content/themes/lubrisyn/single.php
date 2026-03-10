<?php
if ( ! defined('ABSPATH') ) exit;
get_header();

if ( have_posts() ) :
  while ( have_posts() ) : the_post(); ?>

    <?php if ( has_post_thumbnail() ) : ?>
      <div style="width:100%;height:500px;overflow:hidden;">
        <?php the_post_thumbnail('full', [
          'style' => 'width:100%;height:100%;object-fit:cover;display:block;'
        ]); ?>
      </div>
    <?php endif; ?>

    <div style="max-width:1440px;margin:0 auto;padding:40px 20px;">
      <article style="margin-bottom:30px;">
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
      </article>
    </div>

  <?php endwhile;
endif;

get_footer();
