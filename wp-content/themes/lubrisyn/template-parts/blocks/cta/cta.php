<?php
$headline   = get_field('headline');
$image      = get_field('image');
$dropdown   = get_field('cta_dropdown'); // Changed from 'buttons' to 'cta_dropdown'
$placeholder = get_field('dropdown_placeholder') ?: 'Select';
?>

<section class="cta-block">
    <div class="lubrisyn-container cta-grid">
        
        <div class="cta-col-left">
            <?php if ($headline) : ?>
                <h2 class="font-subheading cta-heading"><?php echo esc_html($headline); ?></h2>
            <?php endif; ?>

            <?php if (have_rows('cta_dropdown')) : ?>
                <div class="hero-dropdown-container cta-dropdown-wrap">
                    <select class="hero-classic-select" onchange="if (this.value) window.location.href = this.value;">
                        <option value=""><?php echo esc_html($placeholder); ?></option>
                        <?php while (have_rows('cta_dropdown')) : the_row(); 
                            $menu_text = get_sub_field('menu_text');
                            $link      = get_sub_field('url');
                            if ($link) : 
                                $label = $menu_text ?: $link['title']; ?>
                                <option value="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($label); ?></option>
                            <?php endif; 
                        endwhile; ?>
                    </select>
                </div>

            <?php else : ?>
                <div class="cta-button-group cta-buttons-row" style="margin-top: 20px; display: flex; gap: 15px;">
                    <a href="https://lubrisyn-dev.myshopify.com/" class="btn-primary" style="background-color: #DCAD26;">Shop Now</a>
                    <a href="/how-it-works/" class="btn-primary">How it Works</a>
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