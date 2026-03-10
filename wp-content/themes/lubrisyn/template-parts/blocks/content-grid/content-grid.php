<?php
$headline     = get_field('headline');
$intro        = get_field('introduction');
$grid_items   = get_field('grid_items');
?>

<section class="content-grid-block">
    <div class="lubrisyn-container">
        
        <div class="grid-header">
            <?php if ($headline): ?>
                <h2><?php echo esc_html($headline); ?></h2>
            <?php endif; ?>
            <?php if ($intro): ?>
                <div class="intro-text"><?php echo wp_kses_post($intro); ?></div>
            <?php endif; ?>
        </div>

        <?php if (have_rows('grid_items')): ?>
            <div class="grid-container">
                <?php while (have_rows('grid_items')): the_row(); 
                    $image = get_sub_field('image');
                    $title = get_sub_field('title');
                    $link  = get_sub_field('link'); // Our new field
                    
                    // Setup link attributes
                    $link_url    = $link ? $link['url'] : '';
                    $link_target = $link ? $link['target'] : '_self';
                ?>
                    
                    <?php if ($link_url): ?>
                        <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>" class="grid-item">
                    <?php else: ?>
                        <div class="grid-item">
                    <?php endif; ?>

                        <?php if ($image): ?>
                            <div class="item-image">
                                <?php echo wp_get_attachment_image($image['ID'], 'medium'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($title): ?>
                            <h3 class="font-heading "><?php echo esc_html($title); ?></h3>
                        <?php endif; ?>

                    <?php if ($link_url): ?>
                        </a>
                    <?php else: ?>
                        </div>
                    <?php endif; ?>

                <?php endwhile; ?>
            </div>
        <?php endif; ?>

    </div>
</section>