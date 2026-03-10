<?php
$main_title = get_field('main_title');
?>

<section class="triple-feature-block">
    <div class="lubrisyn-container">
        <?php if ($main_title) : ?>
            <h2 class="main-block-title font-heading"><?php echo esc_html($main_title); ?></h2>
        <?php endif; ?>

        <div class="feature-grid">
            <?php if (have_rows('feature_columns')) : ?>
                <?php while (have_rows('feature_columns')) : the_row(); 
                    $title = get_sub_field('title');
                    $author = get_sub_field('author_name');
                    $desc = get_sub_field('description');
                    $link = get_sub_field('link');
                ?>
                    <div class="feature-column">
                        <h3 class="feature-title"><?php echo esc_html($title); ?></h3>
                        
                        <?php if ($author) : ?>
                            <p class="feature-by"> <?php echo esc_html($author); ?></p>
                        <?php endif; ?>

                        <?php if ($desc) : ?>
                            <div class="feature-desc font-body">
                                <?php echo wp_kses_post($desc); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($link) : ?>
                            <a href="<?php echo esc_url($link['url']); ?>" 
                               class="feature-link" 
                               target="<?php echo esc_attr($link['target'] ?: '_self'); ?>">
                                <?php echo esc_html($link['title']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>