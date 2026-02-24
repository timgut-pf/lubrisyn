<?php
/**
 * Register blocks from the blocks/ folder.
 *
 * Each subfolder (e.g. blocks/shop-promo-banner/) should contain:
 *   - block.json  — Block metadata; include an "acf" key so ACF treats it as an ACF block.
 *   - block.php   — Render template (path set in block.json "acf.renderTemplate").
 *
 * ACF 6+ loads any block.json that has an "acf" key and shows the linked field group
 * when that block is selected. Field group location must be Block = acf/{folder-name}.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function lubrisyn_register_blocks() {
    if ( ! function_exists( 'register_block_type' ) ) {
        return;
    }

    $blocks_dir = get_template_directory() . '/blocks';
    if ( ! is_dir( $blocks_dir ) ) {
        return;
    }

    $dirs = array_filter( glob( $blocks_dir . '/*', GLOB_ONLYDIR ), 'is_dir' );
    foreach ( $dirs as $dir ) {
        $block_name = basename( $dir );
        $block_path = $dir . '/block.json';
        if ( file_exists( $block_path ) ) {
            register_block_type( $block_path );
        }
    }
}
add_action( 'init', 'lubrisyn_register_blocks' );