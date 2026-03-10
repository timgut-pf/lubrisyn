<?php
$title = get_field('section_title');
$subtitle = get_field('section_introduction');
?>

<section class="product-callout-block">
    <div class="lubrisyn-container">
        
        <?php if ($title) : ?>
            <h2 class="section-main-title font-subheading"><?php echo esc_html($title); ?></h2>
            <p class="section-sub-title"><?php echo esc_html($subtitle); ?></p>
        <?php endif; ?>

        <?php if (have_rows('callouts')) : ?>
            <div class="callout-grid">
                <?php while (have_rows('callouts')) : the_row(); 
                    $icon = get_sub_field('icon');
                    $card_title = get_sub_field('title');
                    $desc = get_sub_field('description');
                ?>
                    <div class="callout-card">
                        <?php if ($icon) : ?>
                            <div class="callout-icon">
                                <?php echo wp_get_attachment_image($icon['ID'], 'thumbnail'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="callout-content">
                            <h3 class="callout-heading"><?php echo esc_html($card_title); ?></h3>
                            <p class="font-body"><?php echo esc_html($desc); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

    </div>
</section>