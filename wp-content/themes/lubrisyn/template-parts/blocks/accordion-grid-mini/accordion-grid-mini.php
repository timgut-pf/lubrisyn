<?php
$block_id = 'accordion-grid-' . $block['id'];
$anchor_id = !empty($block['anchor']) ? 'id="' . esc_attr($block['anchor']) . '" ' : 'id="' . $block_id . '"';
$title     = get_field('section_title');
?>

<section <?php echo $anchor_id; ?> class="accordion-mini-grid-block" data-block-id="<?php echo esc_attr($block_id); ?>">
    <div class="lubrisyn-container grid-main-layout">
        
        <div class="accordion-col">
            <?php if ($title) : ?>
                <h2 class="section-title font-heading"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>

            <div class="accordion-list">
                <?php if (have_rows('faqs')) : ?>
                    <?php while (have_rows('faqs')) : the_row(); ?>
                        <div class="accordion-item">
                            <button class="accordion-trigger">
                                <span><?php the_sub_field('question'); ?></span>
                                <span class="arrow"></span>
                            </button>
                            <div class="accordion-panel">
                                <div class="panel-inner font-body">
                                    <?php the_sub_field('answer'); ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div>
        <p class="shop-for">Shop lubrisyn for:</p>
        <div class="grid-2x2">
            
            <?php if ( have_rows('mini_grid_items') ) :
                while ( have_rows('mini_grid_items') ) : the_row(); 
                    $img        = get_sub_field('image');
                    $item_title = get_sub_field('title');
                    $link_data  = get_sub_field('link_url'); // This is the array causing the crash

                    // Initialize variables
                    $url    = '';
                    $target = '_self';

                    // Logic to handle Array vs String return formats
                    if ( is_array($link_data) && isset($link_data['url']) ) {
                        $url    = $link_data['url'];
                        $target = !empty($link_data['target']) ? $link_data['target'] : '_self';
                    } elseif ( is_string($link_data) ) {
                        $url    = $link_data;
                    }

                    $tag  = !empty($url) ? 'a' : 'div';
                    $href = !empty($url) ? 'href="' . esc_url($url) . '"' : '';
                    $attr = ($tag === 'a') ? $href . ' target="' . esc_attr($target) . '"' : '';
                    ?>
                    
                    <<?php echo $tag; ?> <?php echo $attr; ?> class="grid-item">
                        
                        <?php if ( $img && is_array($img) && isset($img['ID']) ) : ?>
                            <div class="item-image-wrapper">
                                <?php echo wp_get_attachment_image($img['ID'], 'medium', false, ['class' => 'grid-img']); ?>
                            </div>
                        <?php elseif ( is_numeric($img) ) : ?>
                            <div class="item-image-wrapper">
                                <?php echo wp_get_attachment_image($img, 'medium', false, ['class' => 'grid-img']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( $item_title ) : ?>
                            <h4 class="item-title font-heading"><?php echo esc_html($item_title); ?></h4>
                        <?php endif; ?>

                    </<?php echo $tag; ?>>

                <?php endwhile;
            endif; ?>
        </div>
        </div>

    </div>
</section>

<script>
(function() {
    const block = document.querySelector('[data-block-id="<?php echo $block_id; ?>"]');
    if (!block) return;
    block.querySelectorAll('.accordion-trigger').forEach(button => {
        button.addEventListener('click', () => {
            const item = button.parentElement;
            const isOpen = item.classList.contains('active');
            block.querySelectorAll('.accordion-item').forEach(i => i.classList.remove('active'));
            if (!isOpen) item.classList.add('active');
        });
    });
})();
</script>