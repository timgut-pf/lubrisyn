<?php
$headline = get_field('headline');
$content  = get_field('content_text');
$image    = get_field('featured_image');
?>

<section class="research-block">
    <div class="lubrisyn-container research-grid">
        
        <div class="research-image-col">
            <?php if ($image) : ?>
                <?php echo wp_get_attachment_image($image['ID'], 'large', false, ['class' => 'research-img']); ?>
            <?php endif; ?>
        </div>

        <div class="research-content-col">
            <div>
                <?php if ($headline) : ?>
                    <h2 style="font-size: 50px;" class="font-subheading"><?php echo esc_html($headline); ?></h2>
                <?php endif; ?>

                <?php if ($content) : ?>
                    <div class="research-text font-body">
                        <?php echo wp_kses_post($content); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (have_rows('study_links')) : ?>
                <div class="study-links-container">
                    <div class="links-list">
                        <?php while (have_rows('study_links')) : the_row(); 
                            $link = get_sub_field('link');
                            if ($link) : ?>
                                <a href="<?php echo esc_url($link['url']); ?>" 
                                   target="_blank" 
                                   class="study-link-item">
                                    <?php echo esc_html($link['title']); ?>
                                </a>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>