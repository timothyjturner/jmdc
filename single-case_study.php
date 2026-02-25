<?php get_header(); ?>

<article class="jmdc-case-study">
  <div class="jmdc-case-study__inner">
    <header class="jmdc-case-study__header">
      <h1 class="jmdc-case-study__title"><?php the_title(); ?></h1>

      <?php if ($subtitle = get_field('jmdc_case_study_subtitle')) : ?>
        <p class="jmdc-case-study__subtitle"><?php echo esc_html($subtitle); ?></p>
      <?php endif; ?>

      <?php if (has_post_thumbnail()) : ?>
        <div class="jmdc-case-study__featured">
          <?php the_post_thumbnail('full'); ?>
        </div>
      <?php endif; ?>
    </header>

    <div class="jmdc-case-study__content">
      <?php the_content(); ?>
    </div>
  </div>
</article>

<?php get_footer(); ?>