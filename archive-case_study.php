<?php get_header(); ?>

<section class="jmdc-work">
  <div class="jmdc-work__inner">

    <header class="jmdc-work__header">
      <h1 class="jmdc-work__title">WORK</h1>
    </header>

    <?php if (have_posts()) : ?>
      <div class="jmdc-work__grid">
        <?php while (have_posts()) : the_post(); ?>

          <?php
          // ACF fields
          $display_title = get_field('jmdc_case_study_title');
          $subtitle      = get_field('jmdc_case_study_subtitle');
          $type          = get_field('jmdc_case_study_type') ?: 'internal';

          $gallery       = get_field('jmdc_case_study_hover_gallery'); // array of images (return_format=array)
          $external      = get_field('jmdc_case_study_external_link');

          // Choose title
          $title = $display_title ? $display_title : get_the_title();

          // Featured image (fallback / default)
          $featured_id = get_post_thumbnail_id(get_the_ID());
          $featured_src = $featured_id ? wp_get_attachment_image_src($featured_id, 'large') : false;

          // Link rules:
          // - gallery: no link
          // - external: link out if url exists
          // - internal: link to single
          $is_gallery = ($type === 'gallery');
          $href = '';
          $target_attr = '';

          if (!$is_gallery) {
            if ($type === 'external' && !empty($external)) {
              $href = esc_url($external);
              $target_attr = ' target="_blank" rel="noopener noreferrer"';
            } else {
              $href = esc_url(get_permalink());
            }
          }

          // Build slideshow images for hover gallery:
          // We'll include featured image first (if set), then gallery images.
          $slide_images = [];

          if ($featured_id) {
            $slide_images[] = [
              'id'  => $featured_id,
              'src' => wp_get_attachment_image_url($featured_id, 'large'),
              'alt' => get_post_meta($featured_id, '_wp_attachment_image_alt', true),
            ];
          }

          if (is_array($gallery) && !empty($gallery)) {
            foreach ($gallery as $img) {
              // Avoid duplicates if featured is also in gallery
              if (!empty($img['ID']) && $img['ID'] === $featured_id) continue;

              $slide_images[] = [
                'id'  => $img['ID'] ?? 0,
                'src' => $img['sizes']['large'] ?? ($img['url'] ?? ''),
                'alt' => $img['alt'] ?? '',
              ];
            }
          }

          // If no images at all, we'll just render an empty thumbnail box.
          $has_slides = !empty($slide_images);
          ?>

          <article class="jmdc-work-card jmdc-reveal <?php echo $is_gallery ? 'jmdc-work-card--gallery' : ''; ?>">
            <?php if (!$is_gallery) : ?>
              <a class="jmdc-work-card__hit" href="<?php echo $href; ?>"<?php echo $target_attr; ?>>
            <?php else : ?>
              <div class="jmdc-work-card__hit" role="group" aria-label="<?php echo esc_attr($title); ?>">
            <?php endif; ?>

                <div
                  class="jmdc-work-card__thumb <?php echo $is_gallery ? 'jmdc-hover-gallery' : ''; ?>"
                  <?php if ($is_gallery && $has_slides) : ?>
                    data-hover-gallery="1"
                    data-interval="1800"
                  <?php endif; ?>
                >
                  <?php if ($has_slides) : ?>
                    <?php
                    $i = 0;
                    foreach ($slide_images as $slide) :
                      $src = $slide['src'];
                      if (!$src) continue;
                      $alt = $slide['alt'] ? $slide['alt'] : $title;
                      $is_active = ($i === 0);
                    ?>
                      <img
                        class="jmdc-hover-gallery__img <?php echo $is_active ? 'is-active' : ''; ?>"
                        src="<?php echo esc_url($src); ?>"
                        alt="<?php echo esc_attr($alt); ?>"
                        loading="lazy"
                        decoding="async"
                      />
                    <?php
                      $i++;
                    endforeach;
                    ?>
                  <?php else : ?>
                    <div class="jmdc-work-card__thumb-empty" aria-hidden="true"></div>
                  <?php endif; ?>
                </div>

                <div class="jmdc-work-card__meta">
                  <h2 class="jmdc-work-card__title"><?php echo esc_html($title); ?></h2>

                  <?php if (!empty($subtitle)) : ?>
                    <p class="jmdc-work-card__subtitle"><?php echo esc_html($subtitle); ?></p>
                  <?php endif; ?>
                </div>

            <?php if (!$is_gallery) : ?>
              </a>
            <?php else : ?>
              </div>
            <?php endif; ?>
          </article>

        <?php endwhile; ?>
      </div>

    <?php else : ?>
      <p class="jmdc-work__empty">No case studies yet.</p>
    <?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>