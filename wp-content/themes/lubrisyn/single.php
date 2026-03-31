<style>
  #field_2_1 > label {
    display:none;
  }
  </style>
<?php
   if ( ! defined('ABSPATH') ) exit;
   get_header();
   
   if ( have_posts() ) :
     while ( have_posts() ) : the_post(); ?>
<div style="margin:0 auto;">
   <section class="simple-blog-hero">
      <div class="lubrisyn-container">
         <h1 style="max-width:900px;margin: 0 auto" class="font-subheading"><?php the_title(); ?></h1>
         <div class="post-meta" style="max-width:900px; margin: 10px auto 0; font-size: 0.9rem; color: #666;">
            <span class="post-date">Posted on <?php echo get_the_date(); ?></span> 
            <span class="post-author">By <?php the_author(); ?></span>
         </div>
      </div>
   </section>
   <div class="blog-layout-wrapper" style="display: flex; max-width: 1200px; margin: 0 auto; gap: 40px; padding: 0 20px;align-items: start;">
      <article style="flex: 3; min-width: 0;">
         <?php the_content(); ?>
      </article>
      <aside style="flex: 1; min-width: 300px;">
         <div class="sidebar-section">
            <h3>Recent Posts</h3>
            <?php
               $recent_posts = new WP_Query( array(
                   'posts_per_page' => 2,
                   'post__not_in'   => array( get_the_ID() ), // Exclude current post
               ) );
               
               if ( $recent_posts->have_posts() ) :
                   while ( $recent_posts->have_posts() ) : $recent_posts->the_post(); ?>
            <div class="recent-post-item" style="margin-bottom: 20px;">
               <span class="post-category" style="text-transform: uppercase; font-size: 12px; color: #888;">
               <?php the_category(', '); ?>
               </span>
               <h4 style="margin: 5px 0;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            </div>
            <?php endwhile;
               wp_reset_postdata();
               endif;
               ?>
         </div>
         <div class="sidebar-section">
            <p><strong>Share on:</strong></p>
            <div class="social-icons" style="display: flex; gap: 15px; font-size: 24px;">
   <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" aria-label="Share on Facebook">
      <img src="https://lubrisynstg.wpenginepowered.com/wp-content/uploads/2026/03/Facebook-1.svg" alt="Facebook">
   </a>

   <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" aria-label="Share on LinkedIn">
      <img src="https://lubrisynstg.wpenginepowered.com/wp-content/uploads/2026/03/LinkedIn-1.svg" alt="LinkedIn">
   </a>
</div>
         </div>
         <div class="sidebar-section">
            <p><strong>Join email list:</strong></p>
            <?php 
               // Replace '1' with your actual Gravity Form ID
               echo do_shortcode('[gravityform id="2" title="false" description="false" ajax="true"]'); 
               ?>
         </div>
      </aside>
   </div>
</div>
<?php endwhile;
   endif;  ?>
<div class="post-navigation" style="display: flex; justify-content: center; gap:20px; align-items: center; margin-top: 50px; margin-bottom: 50px; padding-top: 30px; border-top: 1px solid #eee;">
   <a href="<?php echo home_url('/resources-and-insights/'); ?>" class="art-nav-btn" style="">
   Back to Resources & Insights
   <?php
      // 1. Try to get the next (newer) post
      $next_post = get_next_post();
      
      if ( ! empty( $next_post ) ) : ?>
   <a href="<?php echo get_permalink( $next_post->ID ); ?>" class="art-nav-btn" style="background-color:#DCAD26;">
   <span style="">Next up: <?php echo get_the_title( $next_post->ID ); ?> </span>
   </a>
   <?php else : 
      // 2. Fallback to Oldest post if on the newest
      $oldest_post = get_posts( array(
          'numberposts' => 1,
          'order'       => 'ASC',
          'post_type'   => 'post'
      ) );
      
      if ( ! empty( $oldest_post ) && $oldest_post[0]->ID !== get_the_ID() ) : ?>
   <a href="<?php echo get_permalink( $oldest_post[0]->ID ); ?>" class="art-nav-btn" style="">
   <span style="">Next up: <?php echo get_the_title( $oldest_post[0]->ID ); ?></span>
   </a>
   <?php endif; 
      endif; ?>
</div>
<section class="shop-for-block">
   <div class="lubrisyn-container shop-for-grid">
      <div class="shop-for-col-left">
         <div>
            <h2 class="font-subheading cta-heading">I want to shop LubriSyn for:</h2>
            <select class="shop-select" onchange="if (this.value) window.location.href = this.value;">

            <!-- update links in site global settings -->
               <option value="">Select</option>
               
               <?php if( have_rows('shop_categories', 'option') ): ?>
                  <?php while( have_rows('shop_categories', 'option') ): the_row(); 
                     $label = get_sub_field('label');
                     $link  = get_sub_field('link');
                  ?>
                     <option value="<?php echo esc_url($link); ?>">
                        <?php echo esc_html($label); ?>
                     </option>
                  <?php endwhile; ?>
               <?php endif; ?>
               
            </select>
            <div class="">
            </div>
         </div>
      </div>
      <div class="shop-for-col-right">
         <img src="https://lubrisynstg.wpenginepowered.com/wp-content/uploads/2026/02/cowboy.jpg" 
            alt="man with horse sketch" 
            class="cta-image" 
            width="800" 
            height="600">
      </div>
   </div>
</section>
<?php
get_footer();
