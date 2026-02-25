<?php
add_action('init', function () {

  $labels = [
    'name'               => __('Case Studies', 'jmdc'),
    'singular_name'      => __('Case Study', 'jmdc'),
    'add_new'            => __('Add New', 'jmdc'),
    'add_new_item'       => __('Add New Case Study', 'jmdc'),
    'edit_item'          => __('Edit Case Study', 'jmdc'),
    'new_item'           => __('New Case Study', 'jmdc'),
    'view_item'          => __('View Case Study', 'jmdc'),
    'search_items'       => __('Search Case Studies', 'jmdc'),
    'not_found'          => __('No case studies found', 'jmdc'),
    'not_found_in_trash' => __('No case studies found in Trash', 'jmdc'),
    'all_items'          => __('All Case Studies', 'jmdc'),
    'menu_name'          => __('Case Studies', 'jmdc'),
  ];

  register_post_type('case_study', [
    'labels'             => $labels,
    'public'             => true,
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => 20,
    'menu_icon'          => 'dashicons-portfolio',
    'show_in_rest'       => true, // Gutenberg + REST
    'supports'           => ['title', 'editor', 'thumbnail', 'revisions', 'excerpt'],
    'rewrite'            => [
      'slug'       => 'work',   // archive: /work
      'with_front' => false,
    ],
  ]);
});