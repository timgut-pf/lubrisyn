<?php
$block_id = 'fw-accordion-' . $block['id'];
// Removed the top-level get_field calls since they are now inside the loop
?>

<section id="<?php echo esc_attr($block_id); ?>" class="fw-accordion-block">
    <div class="lubrisyn-container">

        <div class="fw-accordion-list">
            <?php if (have_rows('accordions')) : ?>
                <?php while (have_rows('accordions')) : the_row(); 
                    $row_title   = get_sub_field('section_title');
                    $row_intro   = get_sub_field('section_introduction');
                    $statement   = get_sub_field('statementquestion'); // Fixed: Added underscore
                    $explanation = get_sub_field('explanation');
                ?>
                    <div class="fw-accordion-row-group">
                        
                        <?php if ($row_title || $row_intro) : ?>
                            <div class="row-header-wrap">
                                <?php if ($row_title) : ?>
                                    <h2 class="row-section-title font-heading"><?php echo esc_html($row_title); ?></h2>
                                <?php endif; ?>
                                <?php if ($row_intro) : ?>
                                    <p class="row-section-intro font-body"><?php echo esc_html($row_intro); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="fw-accordion-item">
                            <button class="fw-accordion-trigger" aria-expanded="false">
                                <span class="statement-text font-gotham"><?php echo esc_html($statement); ?></span>
                                <span class="arrow"></span>
                            </button>
                            
                            <div class="fw-accordion-panel">
                                <div class="panel-inner font-body">
                                    <div class="explanation-content">
                                        <?php echo $explanation; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
        
    </div>
</section>

<script>
(function() {
    // Select the specific block instance
    const block = document.getElementById('<?php echo $block_id; ?>');
    if (!block) return;
    
    block.querySelectorAll('.fw-accordion-trigger').forEach(trigger => {
    trigger.addEventListener('click', function() {
        // Find the specific accordion item this trigger belongs to
        const item = this.closest('.fw-accordion-item');
        const isOpen = item.classList.contains('active');
        
        // Close others in this block
        block.querySelectorAll('.fw-accordion-item').forEach(i => i.classList.remove('active'));
        
        // Toggle the current one
        if (!isOpen) {
            item.classList.add('active');
        }
    });
});
})();
</script>