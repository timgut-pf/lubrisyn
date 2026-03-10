<?php
/**
 * Hero Slider Block Template.
 */

$id = 'hero-slider-' . $block['id'];
$title = get_field('hero_title');
$subtitle = get_field('hero_subtitle');
$button = get_field('hero_button');
$slides = get_field('hero_slides'); // Repeater field

// Create the data array for JS
$slide_data = [];
if ($slides) {
    foreach ($slides as $slide) {
        $slide_data[] = [
            'title'    => $slide['hero_slide_title'],
            'subtitle' => $slide['hero_slide_subtitle']
        ];
    }
}



if( $button ): 
    $button_url = $button['url'];
    $button_title = $button['title'];
    $button_target = $button['target'] ? $button['target'] : '_self';
    ?>
    
    <a class="btn-primary" 
       href="<?php echo esc_url( $button_url ); ?>" 
       target="<?php echo esc_attr( $button_target ); ?>"
       <?php echo $button_target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
        <?php echo esc_html( $button_title ); ?>
    </a>

<?php endif; ?>




<section id="<?php echo esc_attr($id); ?>" class="hp-hero">
    <div class="mobile-push"></div>
    
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            <?php if ($slides): foreach ($slides as $slide): 
                $img_url = is_array($slide['image']) ? $slide['image']['url'] : $slide['image'];
                // We pass the unique text to the JS via data attributes
                $s_title = esc_attr($slide['hero_slide_title']);
                $s_sub   = esc_attr($slide['hero_slide_subtitle']);
            ?>
                <div class="swiper-slide" data-title="<?php echo $s_title; ?>" data-subtitle="<?php echo $s_sub; ?>">
                    <div class="slide-bg" style="background-image: url('<?php echo esc_url($img_url); ?>');"></div>
                    <div class="hero-overlay"></div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <div class="hero-static-content">
        <div class="lubrisyn-container">
            <div class="hero-text-wrap">
            <?php 
    // Define the fallbacks
    $first_title = !empty($slides) ? $slides[0]['hero_slide_title'] : '';
    $first_sub   = !empty($slides) ? $slides[0]['hero_slide_subtitle'] : '';
    ?>

    <h1 class="hero-title js-dynamic-title"><?php echo esc_html($first_title); ?></h1>
    <p class="hero-subtitle js-dynamic-subtitle"><?php echo esc_html($first_sub); ?></p>
                
                <?php if ($button): ?>
                    <a href="<?php echo esc_url($button['url']); ?>" class="btn-primary">
                        <?php echo esc_html($button['title']); ?>
                    </a>
                <?php endif; ?>

                <?php if( have_rows('hero_dropdown') ): ?>
                    <div class="hero-dropdown-container">
                        <span>Shop Products for</span>
                        <select class="hero-classic-select" onchange="if (this.value) window.location.href = this.value;">
                            <option value=""><?php echo esc_html(get_field('dropdown_placeholder') ?: 'Select'); ?></option>
                            
                            <?php while( have_rows('hero_dropdown') ): the_row(); 
                                $menu_label = get_sub_field('menu_text'); 
                                $link_array = get_sub_field('url'); 
                                
                                if( is_array($link_array) ) :
                                    $destination_url = $link_array['url'];
                                    $display_text    = $menu_label ?: $link_array['title'];
                                ?>
                                    <option value="<?php echo esc_url($destination_url); ?>">
                                        <?php echo esc_html($display_text); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
            <div class="swiper-pagination hero-dots"></div>
        </div>
    </div>
</section>