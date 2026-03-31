<?php
$intro  = get_field('introduction');
$header = get_field('header');
?>

<section class="masonry-reviews">
    <div class="lubrisyn-container">
        
        <div class="reviews-intro">
            <span class="sub-heading"><?php echo esc_html($intro ?: 'TESTIMONIALS'); ?></span>
            <h2 class="font-heading"><?php echo esc_html($header); ?></h2>
        </div>

        <div class="masonry-grid">
            <?php while(have_rows('reviews')): the_row(); 
                $name   = get_sub_field('name');
                $title   = get_sub_field('title');
                $stars  = get_sub_field('stars');
                $pfp    = get_sub_field('profile_picture');
                $full   = get_sub_field('full_width_image');
                $review = get_sub_field('review_text');
            ?>
                <div class="masonry-card">
                    <div class="card-header">
                        <?php if($pfp): echo wp_get_attachment_image($pfp['ID'], 'thumbnail'); endif; ?>
                        <div class="user-meta">
                            <h4><?php echo esc_html($name); ?></h4>
                            <span style="font-size: 14px;color:#333"><?php echo esc_html($title); ?></span>
                            <div class="rating-badge">
                                <span class="star-icon">★</span>
                                <span class="rating-number"><?php echo esc_html($stars); ?>/5</span>
                            </div>
                        </div>
                    </div>
    <?php if($full): echo wp_get_attachment_image($full['ID'], 'large', false, ['class' => 'full-img']); endif; ?>
    
    <div class="review-body">
        <p><?php echo esc_html($review); ?></p>
    </div>
</div>
            <?php endwhile; ?>
        </div>
    </div>
</section>