<?php get_header(); ?>

<body <?php body_class(); ?>>
    <div class="jmdc-main-page-content">
        <div class="jmdc-wrapper">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>
            <?php endwhile; endif; ?>
        </div>
    </div>
</body>
<?php get_footer(); ?>