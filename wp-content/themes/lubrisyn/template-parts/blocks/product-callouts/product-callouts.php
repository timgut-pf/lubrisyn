<?php
$headline = get_field('headline');
$intro    = get_field('introduction');
$image    = get_field('side_image');
?>

<section class="product-callouts">
    <div class="lubrisyn-container callout-main-grid">
        
        <div class="callout-content-left">
            <div class="header-area">
                <h2 style="" class="font-subheading"><?php echo esc_html($headline); ?></h2>
                <div class="intro-text font-body">
                    <?php echo wp_kses_post($intro); ?>
                </div>
            </div>

            <?php if (have_rows('callouts')): ?>
                <div class="callout-container-border">
                    <?php while (have_rows('callouts')): the_row(); 
                        $title = get_sub_field('title');
                        $desc  = get_sub_field('description');
                        $icon  = get_sub_field('icon');
                    ?>
                        <div class="callout-item">
                            <?php if ($icon): ?>
                                <div class="callout-icon">
                                    <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($title); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="callout-text">
                                <h4 class="font-heading"><?php echo esc_html($title); ?></h4>
                                <p class="font-body"><?php echo esc_html($desc); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="callout-image-right">
            <?php if ($image): ?>
                <?php echo wp_get_attachment_image($image['ID'], 'large', false, ['class' => 'side-hero-img']); ?>
            <?php endif; ?>
        </div>

    </div>
</section>