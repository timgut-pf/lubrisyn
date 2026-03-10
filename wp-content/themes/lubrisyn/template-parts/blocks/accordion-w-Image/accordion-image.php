<?php
// Create a unique ID for this specific block instance
$block_id = 'accordion-block-' . $block['id'];
$anchor_id = !empty($block['anchor']) ? 'id="' . esc_attr($block['anchor']) . '" ' : 'id="' . $block_id . '"';
$title     = get_field('section_title');
$image     = get_field('side_image');
?>

<section <?php echo $anchor_id; ?> class="accordion-image-block" data-block-id="<?php echo esc_attr($block_id); ?>">
    <div class="lubrisyn-container accordion-grid">
        
        <div class="accordion-content-col">
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

        <div class="accordion-image-col">
            <?php if ($image) : ?>
                <?php echo wp_get_attachment_image($image['ID'], 'large', false, ['class' => 'portrait-img']); ?>
            <?php endif; ?>
        </div>

    </div>
</section>

<script>
(function() {
    // Scope the selection to THIS specific block instance only
    const currentBlock = document.querySelector('[data-block-id="<?php echo $block_id; ?>"]');
    if (!currentBlock) return;

    const triggers = currentBlock.querySelectorAll('.accordion-trigger');

    triggers.forEach(button => {
        button.addEventListener('click', () => {
            const accordionItem = button.parentElement;
            const isOpen = accordionItem.classList.contains('active');
            
            // Close other items ONLY within THIS specific block
            currentBlock.querySelectorAll('.accordion-item').forEach(item => {
                item.classList.remove('active');
            });

            if (!isOpen) {
                accordionItem.classList.add('active');
            }
        });
    });
})();
</script>