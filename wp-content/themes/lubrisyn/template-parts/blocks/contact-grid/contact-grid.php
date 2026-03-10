<?php
$main_title = get_field('main_title');
// Get the anchor ID set in the Gutenberg sidebar
$anchor_id = !empty($block['anchor']) ? 'id="' . esc_attr($block['anchor']) . '" ' : '';

// Fetch the toggle value (returns true or false)
$enable_bg = get_field('enable_background_color');
// Create a dynamic class string
$bg_class = $enable_bg ? 'has-bg' : 'no-bg';
?>

<section <?php echo $anchor_id; ?> class="contact-grid-block <?php echo $bg_class; ?>">
    <div class="lubrisyn-container">
        <?php if ($main_title) : ?>
            <h2 class="contact-grid-header font-heading"><?php echo esc_html($main_title); ?></h2>
        <?php endif; ?>

        <div class="contact-grid">
            <?php if (have_rows('contact_columns')) : ?>
                <?php while (have_rows('contact_columns')) : the_row(); 
                    $icon        = get_sub_field('icon');
                    $description = get_sub_field('description');
                    $contact_val = get_sub_field('contact_info'); // Updated field name
                ?>
                    <div class="contact-column">
                        <div class="contact-icon-wrapper">
                            <?php if ($icon) : ?>
                                <?php echo wp_get_attachment_image($icon['ID'], 'thumbnail', true, ['class' => 'contact-icon']); ?>
                            <?php endif; ?>
                        </div>

                        <?php if ($description) : ?>
                            <div class="contact-desc font-body">
                                <?php echo wp_kses_post($description); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($contact_val) : 
                            // Check if it's an email or a phone number
                            if ( is_email( $contact_val ) ) : ?>
                                <a href="mailto:<?php echo antispambot($contact_val); ?>" class="contact-link">
                                    <?php echo esc_html($contact_val); ?>
                                </a>
                            <?php else : 
                                // Clean the string for the tel: link (remove spaces/dashes)
                                $tel_link = preg_replace('/[^0-9+]/', '', $contact_val); ?>
                                <a href="tel:<?php echo esc_attr($tel_link); ?>" class="contact-link">
                                    <?php echo esc_html($contact_val); ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>