<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <?php wp_head(); ?>
</head>
<body <?php body_class('jmdc-body'); ?>>
<?php wp_body_open(); ?>
<header class="jmdc-site-header">
<div class="jmdc-site-header__inner">

    <!-- Center Logo -->
    <div class="jmdc-site-header__logo">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="jmdc-site-header__logo-link" aria-label="<?php bloginfo('name'); ?>">
        <?php
        if (function_exists('the_custom_logo') && has_custom_logo()) {
          the_custom_logo();
        } else {
          echo '<span class="jmdc-site-header__logo-text">' . esc_html(get_bloginfo('name')) . '</span>';
        }
        ?>
      </a>
    </div>

    <!-- Right Nav -->
    <nav class="jmdc-site-header__nav" aria-label="<?php esc_attr_e('Primary navigation', 'your-theme-textdomain'); ?>">
      <?php
      if (has_nav_menu('header_menu')) {
        wp_nav_menu([
          'theme_location' => 'header_menu',
          'container'      => false,
          'menu_class'     => 'jmdc-header-menu',
          'fallback_cb'    => false,
          'depth'          => 1,
        ]);
      } else {
        echo '<ul class="jmdc-header-menu">';
        echo '<li><a href="' . esc_url(home_url('/work/')) . '">WORK</a></li>';
        echo '<li><a href="' . esc_url(home_url('/agency/')) . '">THE AGENCY</a></li>';
        echo '</ul>';
      }
      ?>
    </nav>

  </div>
</header>
