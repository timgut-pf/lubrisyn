<?php
$title = get_field('hero_title') ?: get_the_title(); // Fallback to page title if empty
?>

<section class="simple-hero">
    <div class="lubrisyn-container">
        <h1 class="font-subheading"><?php echo esc_html($title); ?></h1>
    </div>
</section>