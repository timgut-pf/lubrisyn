<?php
if ( ! defined('ABSPATH') ) exit;

$block_id = ! empty($block['anchor']) ? $block['anchor'] : ($block['id'] ?? uniqid('lsh-'));

// Fields
$preheader = get_field('hero_preheader') ?: '';
$title = get_field('hero_title') ?: '';
$content = get_field('hero_content') ?: '';
$bg = get_field('hero_background_image');
$use_texture = (bool) get_field('background_texture_pattern');

$dropdown_label = get_field('dropdown_label') ?: 'Shop Products for:';
$dropdown_placeholder = get_field('dropdown_placeholder') ?: 'Select';
$dropdown_items = get_field('dropdown_items') ?: [];

$bg_url = is_array($bg) ? ($bg['url'] ?? '') : '';
$bg_alt = is_array($bg) ? ($bg['alt'] ?? '') : '';

$classes = [
  'wp-block-acf-lubrisyn-hero',
  'alignfull',
  $use_texture ? 'is-textured' : '',
  ! empty($block['className']) ? $block['className'] : '',
];
?>
<section id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr(trim(implode(' ', array_filter($classes)))); ?>">
  <div class="lsh-hero" <?php if ($bg_url) : ?>style="background-image:url('<?php echo esc_url($bg_url); ?>')"<?php endif; ?> aria-label="<?php echo esc_attr($bg_alt ?: 'Hero'); ?>">
    <div class="lsh-hero__inner">

      <div class="lsh-card">
        <?php if ($preheader) : ?>
          <div class="lsh-eyebrow"><?php echo esc_html($preheader); ?></div>
        <?php endif; ?>

        <?php if ($title) : ?>
          <h1 class="lsh-title"><?php echo esc_html($title); ?></h1>
        <?php endif; ?>

        <?php if ($content) : ?>
          <div class="lsh-content"><?php echo wp_kses_post($content); ?></div>
        <?php endif; ?>

        <div class="lsh-dropdown-row">
          <div class="lsh-dropdown-label"><?php echo esc_html($dropdown_label); ?></div>

          <div class="lsh-dropdown-wrap">
            <select class="lsh-select" data-lsh-select>
              <option value=""><?php echo esc_html($dropdown_placeholder); ?></option>

              <?php foreach ($dropdown_items as $item) :
                $label = $item['label'] ?? '';
                $link  = $item['link'] ?? null;
                $url   = is_array($link) ? ($link['url'] ?? '') : '';
                if ( ! $label ) continue;
              ?>
                <option value="<?php echo esc_url($url); ?>"><?php echo esc_html($label); ?></option>
              <?php endforeach; ?>
            </select>
            <span class="lsh-select-icon" aria-hidden="true">▾</span>
          </div>
        </div>

      </div>

      <div class="lsh-dots" aria-hidden="true">
        <span class="lsh-dot is-active"></span>
        <span class="lsh-dot"></span>
        <span class="lsh-dot"></span>
      </div>

    </div>
  </div>
</section>