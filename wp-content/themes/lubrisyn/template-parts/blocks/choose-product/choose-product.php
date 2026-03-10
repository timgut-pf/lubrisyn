<?php
$products_header = get_field('products_header');
$products_introduction = get_field('products_introduction');
?>

<section class="choose-product-block">
    <div class="product-title-container">
        <h2 class="product-section-main-title font-subheading"><?php echo esc_html($products_header ); ?></h2>
        <p class="product-section-sub-title"><?php echo esc_html($products_introduction); ?></p>
    </div>
    <div class="lubrisyn-container">
        
        <?php if (have_rows('products')) : ?>
            <div class="product-selection-grid">
                <?php while (have_rows('products')) : the_row(); 
                    $image = get_sub_field('product_image');
                    $title = get_sub_field('product_title');
                    $desc  = get_sub_field('product_description');
                    $link  = get_sub_field('product_button');
                    $bg_color = get_sub_field('background_color') ?: '#ffffff'; // Default to white
                    
                    // Determine text color based on background (Dark BG = White Text)
                    $text_class = (strpos($bg_color, '#00') !== false || $bg_color == 'var(--navy-blue)') ? 'light-text' : 'dark-text';
                ?>
                    <div class="product-card <?php echo $text_class; ?>" style="background-color: <?php echo esc_attr($bg_color); ?>;">
                        <div class="product-image-wrap">
                            <?php if ($image) : ?>
                                <?php echo wp_get_attachment_image($image['ID'], 'large', false, ['class' => 'breakout-image']); ?>
                            <?php endif; ?>
                        </div>

                        <div class="product-info">
                            <h3 class="font-heading product-title"><?php echo esc_html($title); ?></h3>
                            <p class="font-body"><?php echo esc_html($desc); ?></p>
                            
                            <?php if ($link) : ?>
                                <a href="<?php echo esc_url($link['url']); ?>" class="btn-primary btn-full-width">
                                    <?php echo esc_html($link['title']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>