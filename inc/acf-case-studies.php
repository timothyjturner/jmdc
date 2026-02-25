<?php

add_filter('acf/settings/save_json', function ($path) {
  return get_template_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function ($paths) {
  $paths[] = get_template_directory() . '/acf-json';
  return $paths;
});

add_action('admin_enqueue_scripts', function ($hook) {
    // Only on post editor screens
    if (!in_array($hook, ['post.php', 'post-new.php'], true)) return;
  
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->post_type !== 'case_study') return;
  
    wp_enqueue_script(
      'jmdc-case-study-editor-toggle',
      get_template_directory_uri() . '/assets/js/case-study-editor-toggle.js',
      ['jquery'],
      '1.0.0',
      true
    );
  });