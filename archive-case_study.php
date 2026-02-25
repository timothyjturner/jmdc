<?php get_header(); ?>

<section class="jmdc-work-archive">
  <div class="jmdc-work-archive__inner">
    <h1 class="jmdc-work-archive__title"><?php post_type_archive_title(); ?></h1>

    <?php if (have_posts()) : ?>
      <div class="jmdc-work-archive__grid">
        <?php while (have_posts()) : the_post(); ?>
          <article class="jmdc-work-card">
          <?php
            $type = get_field('jmdc_case_study_type') ?: 'internal';
            $external = get_field('jmdc_case_study_external_link');
            $url = ($type === 'external' && $external) ? $external : get_permalink();
            $target = ($type === 'external' && $external) ? ' target="_blank" rel="noopener noreferrer"' : '';
            ?>
            <a class="jmdc-work-card__link" href="<?php echo esc_url($url); ?>"<?php echo $target; ?>>
              <div class="jmdc-work-card__thumb">
                <?php if (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('large'); ?>
                <?php endif; ?>
              </div>

              <h2 class="jmdc-work-card__title"><?php the_title(); ?></h2>

              <?php
              $subtitle = get_field('jmdc_case_study_subtitle');
              if ($subtitle) :
              ?>
                <p class="jmdc-work-card__subtitle"><?php echo esc_html($subtitle); ?></p>
              <?php endif; ?>
            </a>
          </article>
        <?php endwhile; ?>
      </div>

      <div class="jmdc-work-archive__pagination">
        <?php the_posts_pagination(); ?>
      </div>
    <?php else : ?>
      <p>No case studies yet.</p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>