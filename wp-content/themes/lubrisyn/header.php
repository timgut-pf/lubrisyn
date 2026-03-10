<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <link rel="stylesheet" href="https://use.typekit.net/iid8ijd.css">

</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php 
// Pull the Link Arrays from the Options Page
$contact_link = get_field('header_contact_link', 'option');
$buy_link     = get_field('header_buy_link', 'option');
$cart_link     = get_field('header_cart_link', 'option');
$login_link     = get_field('header_login_link', 'option');
?>

<header class="site-header lubrisyn-header">
    <div class="top-bar">
        <div class="top-bar-container">
            <div class="top-bar-links">
                <?php 
                $c_url    = $contact_link ? esc_url($contact_link['url']) : '/contact';
                $c_target = !empty($contact_link['target']) ? esc_attr($contact_link['target']) : '_self';
                $c_title  = $contact_link ? esc_html($contact_link['title']) : 'Contact';
                ?>
                <a href="<?php echo $c_url; ?>" target="<?php echo $c_target; ?>" class="top-link">
                    <span class="dashicons dashicons-email"></span> <?php echo $c_title; ?>
                </a>

                <?php 
                $b_url    = $buy_link ? esc_url($buy_link['url']) : '/where-to-buy';
                $b_target = !empty($buy_link['target']) ? esc_attr($buy_link['target']) : '_self';
                $b_title  = $buy_link ? esc_html($buy_link['title']) : 'Where to Buy';
                ?>
                <a href="<?php echo $b_url; ?>" target="<?php echo $b_target; ?>" class="top-link">
                    <span class="dashicons dashicons-location"></span> <?php echo $b_title; ?>
                </a>
            </div>
        </div>
    </div>

    <div class="header-container">
        <div class="header-left">
            
            <nav id="site-navigation" class="main-nav">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'nav-list',
                    'fallback_cb'    => false,
                ]);
                ?>
                <div class="mobile-icons-container">
                    <a href="<?php echo esc_url( $login_link['url'] ); ?>" target="<?php echo esc_attr( $login_link['target'] ?: '_self' ); ?>" class="mobile-icon-item">
                    <img class="dashicons" src="/wp-content/uploads/2026/03/Single-Neutral-Circle-Streamline-Ultimate-1.svg">
                        <span class="icon-label">Account</span>
                    </a>
                    
                    <a href="<?php echo esc_url( $cart_link['url'] ); ?>" target="<?php echo esc_attr( $cart_link['target'] ?: '_self' ); ?>" class="mobile-icon-item">
                    <img class="dashicons" src="/wp-content/uploads/2026/03/Shopping-Basket-1-Streamline-Ultimate-2.svg">
                        <span class="icon-label">Cart</span>
                    </a>
                    <a href="<?php echo $c_url; ?>" target="<?php echo $c_target; ?>" class="mobile-icon-item">
                    <img class="dashicons" src="/wp-content/uploads/2026/03/Vector.svg">
                        <span class="icon-label">Contact</span>
                    </a>
                    <a href="<?php echo $b_url; ?>" target="<?php echo $b_target; ?>" class="mobile-icon-item">
                    <img class="dashicons" src="/wp-content/uploads/2026/03/Style-One-Pin-Check-Streamline-Ultimate.svg">
                        <span class="icon-label">In-Store</span>
                    </a>
                   
                   
                </div>
            </nav>
        </div>

        <div class="header-center">
            <div class="site-logo">
                <?php the_custom_logo(); ?>
            </div>
        </div>

        <div class="header-right">
        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <span class="dashicons dashicons-menu"></span>
                <span class="screen-reader-text">Menu</span>
            </button>
            <div class="header-icons">
                <a href="<?php echo esc_url( $login_link ['url'] ); ?>" class="icon-link login" target="<?php echo esc_attr( $login_link ['target'] ?: '_self' ); ?>"><img class="dashicons" src="/wp-content/uploads/2026/03/Single-Neutral-Circle-Streamline-Ultimate.svg"></span></a>
                <a href="<?php echo esc_url( $cart_link['url'] ); ?>" class="icon-link cart" target="<?php echo esc_attr( $cart_link['target'] ?: '_self' ); ?>"><img class="dashicons" src="/wp-content/uploads/2026/03/Shopping-Basket-1-Streamline-Ultimate-1.svg"></a>
            </div>
        </div>
    </div>
</header>