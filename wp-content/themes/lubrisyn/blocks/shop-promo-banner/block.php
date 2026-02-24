<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$block_id = ! empty( $block['anchor'] ) ? $block['anchor'] : ( $block['id'] ?? 'spb-' . uniqid() );
$headline   = get_field( 'headline' );
$intro_text = get_field( 'intro_text' );
$icon_rows  = get_field( 'icon_rows' ) ?: [];
?>
<section id="<?php echo esc_attr( $block_id ); ?>" class="wp-block-acf-shop-promo-banner">
    <?php if ( $headline ) : ?>
        <h2 class="spb-headline"><?php echo esc_html( $headline ); ?></h2>
    <?php endif; ?>
    <?php if ( $intro_text ) : ?>
        <p class="spb-intro"><?php echo esc_html( $intro_text ); ?></p>
    <?php endif; ?>
    <?php if ( ! empty( $icon_rows ) ) : ?>
        <div class="spb-rows">
            <?php foreach ( $icon_rows as $row ) :
                $name  = $row['name'] ?? '';
                $icon  = $row['shop_icon'] ?? null;
                $link  = $row['store_link'] ?? '';
                $url   = is_string( $link ) ? $link : ( is_array( $link ) ? ( $link['url'] ?? '' ) : '' );
                $img   = is_array( $icon ) ? ( $icon['url'] ?? '' ) : '';
            ?>
                <div class="spb-row">
                    <?php if ( $img ) : ?>
                        <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $name ?: 'Icon' ); ?>" class="spb-icon" loading="lazy" />
                    <?php endif; ?>
                    <?php if ( $name ) : ?>
                        <span class="spb-name"><?php echo esc_html( $name ); ?></span>
                    <?php endif; ?>
                    <?php if ( $url ) : ?>
                        <a href="<?php echo esc_url( $url ); ?>" class="spb-link"><?php echo esc_html( $name ?: __( 'Shop', 'lubrisyn' ) ); ?></a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
