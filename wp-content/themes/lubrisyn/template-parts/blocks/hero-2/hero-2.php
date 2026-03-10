<?php
$headline = get_field('headline');
$intro    = get_field('introduction');
$image    = get_field('image');
?>

<section class="hero-2">
    <div style="padding-right: 0px; padding-left: 0px;" class="lubrisyn-container hero-2-grid">
        
        <div class="hero-2-content">
            <?php if ($headline) : ?>
                <h1 class="font-subheading hero-2-heading"><?php echo esc_html($headline); ?></h1>
            <?php endif; ?>

            <?php if ($intro) : ?>
                <div class="hero-2-intro font-body">
                    <?php echo wp_kses_post($intro); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="hero-2-image">
            <?php if ($image) : ?>
                <?php echo wp_get_attachment_image($image['ID'], 'large', false, ['class' => 'hero-img-right']); ?>
            <?php endif; ?>
        </div>

    </div>
</section>