<?php
$title = get_field('title');
$intro = get_field('introduction');
$button = get_field('see_all_button');
?>

<section class="reviews-block">
    <div class="lubrisyn-container">
        
        <div class="reviews-header">
            <div class="header-left">
                <h2 class="font-subheading"><?php echo esc_html($title); ?></h2>
                <?php if($intro): ?><p class="font-body"><?php echo esc_html($intro); ?></p><?php endif; ?>
            </div>
            <div style="padding-bottom:30px" class="header-right">
                <?php if($button): ?>
                    <a href="<?php echo esc_url($button['url']); ?>" class="see-all-btn">
                        <?php echo esc_html($button['title']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if(have_rows('reviews')): ?>
            <div class="swiper reviews-swiper">
                <div class="swiper-wrapper">
                    <?php while(have_rows('reviews')): the_row(); 
                        $image = get_sub_field('image');
                        $text  = get_sub_field('review_text');
                        $name  = get_sub_field('name');
                        $role  = get_sub_field('title');
                    ?>
                        <div class="swiper-slide review-card">
                            <?php if($image): ?>
                                <div class="review-image-side">
                                    <?php echo wp_get_attachment_image($image['ID'], 'large'); ?>
                                </div>
                            <?php endif; ?>

                            <div class="review-content-side">
                                <div class="quote-area">
                                    <p class="review-quote">"<?php echo esc_html($text); ?>"</p>
                                </div>
                                
                                <div class="review-meta">
                                    <h4 class="review-name"><?php echo esc_html($name); ?></h4>
                                    <p class="review-role"><?php echo esc_html($role); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        <?php endif; ?>

    </div>
</section>