<?php
if ( ! defined('ABSPATH') ) exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <?php echo '<div style="padding:20px;background:yellow;color:black;position:relative;z-index:9999;">HEADER TEST</div>'; ?>
<?php wp_body_open(); ?>

<header class="site-header">
  <?php get_template_part('template-parts/header/utility-bar'); ?>
  <?php get_template_part('template-parts/header/main-nav'); ?>
</header>

<main class="site-main">
