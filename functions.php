<?php
function jmdc_enqueue_styles() {
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_style(
        'theme-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        [],
        filemtime(get_stylesheet_directory() . '/assets/css/main.css')
      );
    
      // Overrides CSS (loads AFTER main.css)
      wp_enqueue_style(
        'theme-overrides',
        get_stylesheet_directory_uri() . '/assets/css/overrides.css',
        ['theme-main'], // dependency ensures correct order
        filemtime(get_stylesheet_directory() . '/assets/css/overrides.css')
      );
}
add_action('wp_enqueue_scripts', 'jmdc_enqueue_styles');

function jmdc_fonts() {
    wp_enqueue_style(
      'google-fonts',
      'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap',
      [],
      null
    );
  }
  add_action('wp_enqueue_scripts', 'jmdc_fonts');
  
// Register menu location for header nav
add_action('after_setup_theme', function () {
    register_nav_menus([
      'header_menu' => __('Header Menu', 'jmdc'),
    ]);
  });
  
//custom logo support
  add_theme_support('custom-logo', [
    'height'      => 100,
    'width'       => 300,
    'flex-height' => true,
    'flex-width'  => true,
  ]);