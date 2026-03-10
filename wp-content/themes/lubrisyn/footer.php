<footer class="site-footer">
    <div class="footer-container">
        
        <div class="footer-col footer-logo">
            <?php 
            if (has_custom_logo()) {
                the_custom_logo();
            } else {
                echo '<a href="' . esc_url(home_url('/')) . '" class="footer-site-title">' . get_bloginfo('name') . '</a>';
            }
            ?>
        </div>

        <div class="footer-col">
            <h4 class="footer-heading"><?php _e('Company', 'lubrisyn'); ?></h4>
            <?php
            wp_nav_menu([
                'theme_location' => 'footer_company',
                'container'      => false,
                'menu_class'     => 'footer-menu-list',
                'fallback_cb'    => false,
            ]);
            ?>
        </div>

        <div class="footer-col">
            <h4 class="footer-heading"><?php _e('Products', 'lubrisyn'); ?></h4>
            <?php
            wp_nav_menu([
                'theme_location' => 'footer_products',
                'container'      => false,
                'menu_class'     => 'footer-menu-list',
                'fallback_cb'    => false,
            ]);
            ?>
        </div>

        <div class="footer-col">
            <h4 class="footer-heading"><?php _e('Stay Informed', 'lubrisyn'); ?></h4>
            
            <div class="footer-socials">
                <?php if (have_rows('social_links', 'option')) : ?>
                    <?php while (have_rows('social_links', 'option')) : the_row(); 
                        // This gets the 'value' from your select field (e.g., 'Facebook')
                        $platform = get_sub_field('platform'); 
                        $url      = get_sub_field('url');
                        
                        if ($url) : ?>
                            <a href="<?php echo esc_url($url); ?>" class="social-icon" target="_blank" rel="noopener">
                                <img src="/wp-content/uploads/2026/03/<?php echo esc_attr($platform); ?>.svg" alt="<?php echo esc_attr($platform); ?>">
                            </a>
                        <?php endif; ?>
                    <?php endwhile; ?>
                <?php endif; ?>
</div>

            <div class="newsletter-placeholder">

                <div class="gravity-form-mock">
                <?php
echo do_shortcode('[gravityform id="2" title="false" description="false" ajax="true"]');
?>

                </div>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?> All Rights Reserved | <a href="/privacy-policy/">Privacy Policy</a></p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>