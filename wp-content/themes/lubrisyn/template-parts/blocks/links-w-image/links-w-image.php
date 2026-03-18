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
            $link = get_sub_field('link');
            
            // Only proceed if the link exists
            if ($link): 
                $link_url    = $link['url'];
                $link_title  = $link['title'];
                $link_target = $link['target'] ? $link['target'] : '_self';
        ?>
            <div class="callout-item">
                <div class="link-text">
                    <li><a href="<?php echo esc_url($link_url); ?>" 
                       target="<?php echo esc_attr($link_target); ?>" 
                       class="font-body">
                        <?php echo esc_html($link_title); ?>
                    </a></li>
                </div>
            </div>
        <?php 
            endif; // End if $link
        endwhile; 
        ?>
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