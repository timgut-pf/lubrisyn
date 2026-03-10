<?php
$headline = get_field('headline');
$image    = get_field('image');
$buttons  = get_field('buttons'); // Get the repeater array
$button_count = $buttons ? count($buttons) : 0;

// Determine the layout class based on count
$button_class = ($button_count >= 3) ? 'cta-buttons-stacked' : 'cta-buttons-row';
?>

<section class="cta-block">
    <div class="lubrisyn-container cta-grid">
        
        <div class="cta-col-left">
            <?php if ($headline) : ?>
                <h2 class="font-subheading cta-heading"><?php echo esc_html($headline); ?></h2>
            <?php endif; ?>

            <?php if (have_rows('buttons')) : ?>
                <div class="cta-button-group <?php echo $button_class; ?>">
                    <?php 
                    $i = 0; // Initialize counter
                    while (have_rows('buttons')) : the_row(); 
                        $link = get_sub_field('link');
                        if ($link) : 
                            // Check if it's the first button AND the class is 'cta-buttons-row'
                            $style = ($button_class === 'cta-buttons-row' && $i === 0) ? 'style="background-color: #DCAD26;"' : '';
                            ?>
                            <a href="<?php echo esc_url($link['url']); ?>" 
                               target="<?php echo esc_attr($link['target'] ?: '_self'); ?>" 
                               class="btn-primary" 
                               <?php echo $style; ?>>
                                <?php echo esc_html($link['title']); ?>
                            </a>
                        <?php 
                        endif; 
                        $i++; // Increment counter
                    endwhile; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="cta-col-right">
            <?php if ($image) : ?>
                <?php echo wp_get_attachment_image($image['ID'], 'large', false, ['class' => 'cta-image']); ?>
            <?php endif; ?>
        </div>

    </div>
</section>