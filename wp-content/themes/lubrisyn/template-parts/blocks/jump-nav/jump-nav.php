<nav class="jump-nav">
    <div class="lubrisyn-container nav-flex">
        <div><span class="jump-to font-gotham">Jump to:</span></div>
        <div>
            <?php while(have_rows('jump_links')): the_row(); ?>
                <a href="#<?php the_sub_field('section_id'); ?>" class="jump-btn">
                    <?php the_sub_field('button_text'); ?>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
</nav>