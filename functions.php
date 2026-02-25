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

// Register menu locations
add_action('after_setup_theme', function () {
  register_nav_menus([
    'jmdc-footer-social' => __('Footer Social Links', 'jmdc'),
  ]);
});

/**
 * Create a default footer social menu and assign it to the location
 * if nothing is assigned yet. Runs once.
 */
add_action('after_switch_theme', function () {
  $location_key = 'jmdc-footer-social';
  $locations    = get_theme_mod('nav_menu_locations', []);

  // If already assigned, stop.
  if (!empty($locations[$location_key])) {
    return;
  }

  // Create menu
  $menu_name = 'Footer Social';
  $menu_id   = wp_create_nav_menu($menu_name);

  if (is_wp_error($menu_id)) {
    return;
  }

  // Default items
  $defaults = [
    [
      'title' => 'Instagram',
      'url'   => 'https://www.instagram.com/jonmichaeldesign/',
    ],
    [
      'title' => 'LinkedIn',
      'url'   => 'https://www.linkedin.com/company/jon-michael-design',
    ],
  ];

  foreach ($defaults as $item) {
    wp_update_nav_menu_item($menu_id, 0, [
      'menu-item-title'  => $item['title'],
      'menu-item-url'    => $item['url'],
      'menu-item-status' => 'publish',
    ]);
  }

  // Assign it to the theme location
  $locations[$location_key] = (int) $menu_id;
  set_theme_mod('nav_menu_locations', $locations);
});

/**
 * Footer Customizer settings
 */
add_action('customize_register', function ($wp_customize) {

    // Section
    $wp_customize->add_section('jmdc_footer_section', [
      'title'       => __('Footer', 'jmdc'),
      'priority'    => 160,
      'description' => __('Footer contact + branding options.', 'jmdc'),
    ]);
  
    /**
     * Footer Email
     */
    $wp_customize->add_setting('jmdc_footer_email', [
      'default'           => 'contact@jmdcreative.com',
      'sanitize_callback' => 'sanitize_email',
      'transport'         => 'refresh',
    ]);
  
    $wp_customize->add_control('jmdc_footer_email', [
      'label'       => __('Footer contact email', 'jmdc'),
      'section'     => 'jmdc_footer_section',
      'type'        => 'email',
      'description' => __('Shown in the “Get in touch” area.', 'jmdc'),
    ]);
  
    /**
     * Footer Brand Lockup Image (JMD | A TAYLOR COMPANY)
     */
    $wp_customize->add_setting('jmdc_footer_brand_lockup_image', [
      'default'           => '',
      'sanitize_callback' => 'absint', // attachment ID
      'transport'         => 'refresh',
    ]);
  
    $wp_customize->add_control(new WP_Customize_Media_Control(
      $wp_customize,
      'jmdc_footer_brand_lockup_image',
      [
        'label'       => __('Footer brand lockup image', 'jmdc'),
        'section'     => 'jmdc_footer_section',
        'mime_type'   => 'image',
        'description' => __('Upload/select the combined “JMD | A TAYLOR COMPANY” image used in the footer.', 'jmdc'),
      ]
    ));
  
  });


add_action('after_setup_theme', function () {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails'); // enables featured images globally
  add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
});


require_once get_template_directory() . '/inc/case-studies.php';
require_once get_template_directory() . '/inc/acf-case-studies.php';
require_once get_template_directory() . '/inc/admin-tweaks.php';