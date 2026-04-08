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


add_action('wp_enqueue_scripts', function () {

  $version = time(); 
    // Swiper CSS
    wp_enqueue_style(
      'swiper-css',
      'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
      [],
      $version
    );

    // Swiper JS
    wp_enqueue_script(
      'swiper-js',
      'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
      [],
      $version,
      true
    );
    
  if (is_post_type_archive('case_study')) {
    wp_enqueue_script(
      'jmdc-work-hover-gallery',
      get_template_directory_uri() . '/assets/js/jmdc-work-hover-gallery.js',
      [],
      '1.0.0',
      true
    );
  }
});

add_action('pre_get_posts', function ($query) {
  if (is_admin() || !$query->is_main_query()) return;

  if ($query->is_post_type_archive('case_study')) {
    // Oldest first
    $query->set('order', 'ASC');
    $query->set('orderby', 'date');

    // No pagination: load all
    $query->set('posts_per_page', -1);
    $query->set('nopaging', true);
  }
});

add_action('wp_enqueue_scripts', function () {
  if (is_post_type_archive('case_study')) {
    wp_enqueue_script(
      'jmdc-reveal-on-scroll',
      get_template_directory_uri() . '/assets/js/jmdc-reveal-on-scroll.js',
      [],
      '1.0.0',
      true
    );
  }
});

function custom_register_acf_blocks() {
  $block_json_files = glob(__DIR__ . '/blocks/*/block.json');

  if (empty($block_json_files)) {
    return;
  }

  foreach ($block_json_files as $block_json_file) {
    register_block_type(dirname($block_json_file));
  }
}
add_action('init', 'custom_register_acf_blocks');
add_action('wp_enqueue_scripts', function () {

    if (is_admin()) {
        return;
    }

    global $post;

    if (empty($post) || empty($post->post_content)) {
        return;
    }

    $blocks_dir = get_template_directory() . '/blocks/';
    $blocks_url = get_template_directory_uri() . '/blocks/';

    $block_folders = glob($blocks_dir . '*', GLOB_ONLYDIR);

    if (!$block_folders) {
        return;
    }

    foreach ($block_folders as $block_path) {

        $block_name = basename($block_path);

        // Check if the ACF block exists in the post
        if (!has_block('acf/' . $block_name, $post)) {
            continue;
        }

        /* ---------- CSS ---------- */
        $css_files = glob($block_path . '/*.css');
        if (!empty($css_files)) {
            foreach ($css_files as $css_file) {

                $handle = 'block-' . $block_name . '-style-' . basename($css_file, '.css');

                wp_enqueue_style(
                    $handle,
                    $blocks_url . $block_name . '/' . basename($css_file),
                    [],
                    filemtime($css_file)
                );
            }
        }

        /* ---------- JS ---------- */
        $js_files = glob($block_path . '/*.js');
        if (!empty($js_files)) {
            foreach ($js_files as $js_file) {

                $handle = 'block-' . $block_name . '-script-' . basename($js_file, '.js');

                wp_enqueue_script(
                    $handle,
                    $blocks_url . $block_name . '/' . basename($js_file),
                    [],
                    filemtime($js_file),
                    true // Load in footer
                );
            }
        }
    }
});


add_filter( 'render_block', 'jmdc_preserve_spacer_custom_classes', 10, 2 );

function jmdc_preserve_spacer_custom_classes( $block_content, $block ) {
	if ( empty( $block['blockName'] ) || 'core/spacer' !== $block['blockName'] ) {
		return $block_content;
	}

	if ( empty( $block['attrs']['className'] ) ) {
		return $block_content;
	}

	$custom_classes = trim( $block['attrs']['className'] );

	if ( '' === $custom_classes ) {
		return $block_content;
	}

	if ( false !== strpos( $block_content, $custom_classes ) ) {
		return $block_content;
	}

	$block_content = preg_replace(
		'/class="([^"]*)"/',
		'class="$1 ' . esc_attr( $custom_classes ) . '"',
		$block_content,
		1
	);

	return $block_content;
}

//start front page intro
require_once get_stylesheet_directory() . '/inc/acf-front-page-intro.php';

add_action('wp_enqueue_scripts', function () {
	if (!is_front_page()) {
		return;
	}

	$enabled = function_exists('get_field') ? get_field('jmd_intro_enable') : false;
	$video   = function_exists('get_field') ? get_field('jmd_intro_video') : null;

	if (!$enabled || empty($video['url'])) {
		return;
	}

	$theme_version = wp_get_theme()->get('Version');

	wp_enqueue_style(
		'jmd-front-intro',
		get_stylesheet_directory_uri() . '/assets/css/jmd-front-intro.css',
		[],
		$theme_version
	);

	wp_enqueue_script(
		'jmd-front-intro',
		get_stylesheet_directory_uri() . '/assets/js/jmd-front-intro.js',
		[],
		$theme_version,
		true
	);

	$data = [
		'videoUrl' => esc_url($video['url']),
		'muted' => (bool) get_field('jmd_intro_autoplay_muted'),
		'topLogo' => esc_url(get_stylesheet_directory_uri() . '/assets/img/jmd-top.png'),
		'bottomLogo' => esc_url(get_stylesheet_directory_uri() . '/assets/img/jmd-bottom.png'),
		'desktop' => [
			'logoWidth' => 188.5,
			'logoHeight' => 53,
			'topOffset' => 34.5,
		],
		'mobile' => [
			'logoWidth' => 113.797,
			'logoHeight' => 32,
			'topOffset' => 45,
		],
		'mobileBreakpoint' => 576,
		'closedGap' => 4.36,
		'endThresholdSeconds' => 0.75,
	];

	wp_localize_script('jmd-front-intro', 'jmdFrontIntro', $data);
});
//end front page intro