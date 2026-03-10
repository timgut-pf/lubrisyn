<?php
$title = get_field('title');
$button = get_field('see_all_button');
$bg_texture = get_field('background_texture');
$bg_style = $bg_texture ? 'style="border-radius: 35px 35px 0px 0px;background-repeat: no-repeat;background-image: url(' . esc_url($bg_texture['url']) . ');"' : '';
?>

<section class="faq-block"  <?php echo $bg_style; ?>>
    <div class="lubrisyn-container faq-grid">
        
        <div class="faq-intro-col">
            <h2 class="font-heading"><?php echo esc_html($title); ?></h2>
            <?php if ($button) : ?>
                <a href="<?php echo esc_url($button['url']); ?>" class="see-all-btn">
                    <?php echo esc_html($button['title']); ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="faq-list-col">
            <?php if (have_rows('questions')) : ?>
                <?php while (have_rows('questions')) : the_row(); 
                    $question = get_sub_field('question');
                    $answer = get_sub_field('answer');
                ?>
                    <div class="faq-item">
                        <h4 class="faq-question"><?php echo esc_html($question); ?></h4>
                        <div class="faq-answer font-body">
                            <?php echo wp_kses_post($answer); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

    </div>
</section>