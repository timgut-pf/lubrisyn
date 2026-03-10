<?php
$form_id  = get_field('form_id'); 
$image    = get_field('featured_image');
$headline = get_field('headline');
?>

<section class="form-image-block">
    <div class="lubrisyn-container">
        
        <?php if ($headline) : ?>
            <h2 class="form-block-header font-gotham"><?php echo esc_html($headline); ?></h2>
        <?php endif; ?>

        <div class="form-grid">
            <div class="form-column">
                <?php if ($form_id) : 
                    gravity_form($form_id, false, false, false, '', true); 
                endif; ?>
            </div>

            <div class="image-column">
                <?php if ($image) : 
                    $image_id = is_array($image) ? $image['ID'] : $image;
                    echo wp_get_attachment_image($image_id, 'large', false, ['class' => 'form-side-img']); 
                endif; ?>
            </div>
        </div>

    </div>
</section>