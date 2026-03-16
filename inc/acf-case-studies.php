<?php

add_filter('acf/settings/save_json', function ($path) {
  return get_template_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function ($paths) {
  $paths[] = get_template_directory() . '/acf-json';
  return $paths;
});

/**
 * Resolve which media the case study cards should use.
 *
 * On the front page only, use this priority:
 * 1. Front Page Video Override
 * 2. Front Page Image Override
 * 3. Regular Video
 * 4. Featured Image
 */
function jmdc_get_case_study_card_media($post_id) {
  $front_page_video = get_field('jmdc_case_study_front_page_video', $post_id);
  $front_page_image = get_field('jmdc_case_study_front_page_image', $post_id);
  $default_video    = get_field('jmdc_case_study_video', $post_id);

  $featured_id  = get_post_thumbnail_id($post_id);
  $featured_url = $featured_id ? get_the_post_thumbnail_url($post_id, 'large') : '';
  $featured_alt = $featured_id ? get_post_meta($featured_id, '_wp_attachment_image_alt', true) : '';

  $front_page_image_url = '';
  $front_page_image_alt = '';

  if (!empty($front_page_image)) {
    $front_page_image_url = $front_page_image['sizes']['large'] ?? $front_page_image['url'] ?? '';
    $front_page_image_alt = $front_page_image['alt'] ?? '';
  }

  $media = [
    'type'      => '',
    'video_url' => '',
    'image_url' => '',
    'image_alt' => '',
    'poster_url'=> '',
  ];

  if (is_front_page()) {
    if (!empty($front_page_video['url'])) {
      $media['type']       = 'video';
      $media['video_url']  = $front_page_video['url'];
      $media['poster_url'] = $front_page_image_url ?: $featured_url;
      $media['image_alt']  = $front_page_image_alt ?: $featured_alt;
      return $media;
    }

    if (!empty($front_page_image_url)) {
      $media['type']      = 'image';
      $media['image_url'] = $front_page_image_url;
      $media['image_alt'] = $front_page_image_alt;
      return $media;
    }
  }

  if (!empty($default_video['url'])) {
    $media['type']       = 'video';
    $media['video_url']  = $default_video['url'];
    $media['poster_url'] = $featured_url;
    $media['image_alt']  = $featured_alt;
    return $media;
  }

  if (!empty($featured_url)) {
    $media['type']      = 'image';
    $media['image_url'] = $featured_url;
    $media['image_alt'] = $featured_alt;
  }

  return $media;
}
