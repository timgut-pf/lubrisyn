<?php
/**
 * Hero Slider Two Block Template.
 */

$id = 'hero-slider-two-' . $block['id'];
$slides = get_field('hero_slides'); // Repeater field
?>

<section id="<?php echo esc_attr($id); ?>" class="hp-hero hero-two">
    <div class="swiper hero-swiper-two">
        <div class="swiper-wrapper">
            <?php if (have_rows('hero_slides')): ?>
                <?php while (have_rows('hero_slides')): the_row(); 
                    // 1. Image Data
                    $img = get_sub_field('image');
                    $img_url = is_array($img) ? $img['url'] : $img;

                    // 2. Text Content
                    $s_title = get_sub_field('hero_slide_title');
                    $s_sub   = get_sub_field('hero_slide_subtitle');
                ?>
                    <div class="swiper-slide">
                        <div class="slide-bg" style="background-image: url('<?php echo esc_url($img_url); ?>');"></div>
                        <div class="hero-overlay"></div>

                        <div class="hero-slide-content">
                            <div class="lubrisyn-container">
                                <div class="hero-text-wrap">
                                    <?php if ($s_title): ?>
                                        <h1 class="hero-title"><?php echo esc_html($s_title); ?></h1>
                                    <?php endif; ?>
                                    
                                    <?php if ($s_sub): ?>
                                        <p class="hero-subtitle"><?php echo esc_html($s_sub); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="hero-static-content">
        <div class="lubrisyn-container">
            <div class="hero-text-wrap">
                <?php if (have_rows('hero_dropdown')): ?>
                    <div class="hero-dropdown-container">
                        <span>Shop Products for:</span>
                        <select class="hero-classic-select" onchange="if (this.value) window.location.href = this.value;">
                            <option value="">Select Category</option>
                            <?php while (have_rows('hero_dropdown')): the_row(); 
                                $label = get_sub_field('menu_text'); 
                                $link_data = get_sub_field('url');
                                $url = is_array($link_data) ? $link_data['url'] : $link_data;
                            ?>
                                <option value="<?php echo esc_url($url); ?>">
                                    <?php echo esc_html($label ?: ($link_data['title'] ?? 'View More')); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
            <div class="swiper-pagination-two hero-dots"></div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Use a unique variable name to avoid conflicts with Hero One
    const heroTwoSwiper = new Swiper('.hero-swiper-two', {
        loop: true,
        speed: 1000,
        effect: 'fade', 
        fadeEffect: {
            crossFade: true
        },
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination-two',
            clickable: true,
        },
    });
});
</script>