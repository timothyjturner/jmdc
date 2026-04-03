<?php
/**
 * Case Study Slider Block template.
 *
 * @param array $block The block settings and attributes.
 */

$slides = get_field('jmdc_case_study_slides');

$class_name = '';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}

if ($slides) :
?>
<section class="jmdc-case-study-slider-section <?php echo esc_attr($class_name); ?>">
    <div class="jmdc-case-study-slider-wrapper">
        <div class="jmdc-case-study-slider swiper">
            <div class="jmdc-case-study-slider__track swiper-wrapper">
                <?php foreach ($slides as $slide) :
                    $post = $slide['case_study'] ?? null;

                    if (!$post instanceof WP_Post) {
                        continue;
                    }

                    setup_postdata($post);

                    $custom_title   = get_field('jmdc_case_study_title', $post->ID);
                    $subtitle       = get_field('jmdc_case_study_subtitle', $post->ID);
                    $title          = $custom_title ?: get_the_title($post->ID);
                    $override_image = $slide['override_image'] ?? null;
                    $video          = get_field('jmdc_case_study_video', $post->ID);

                    if (!empty($override_image['ID'])) {
                        $image_id     = $override_image['ID'];
                        $image_url    = $override_image['sizes']['large'] ?? $override_image['url'];
                        $image_alt    = $override_image['alt'] ?? '';
                        $image_width  = $override_image['sizes']['large-width'] ?? $override_image['width'] ?? '';
                        $image_height = $override_image['sizes']['large-height'] ?? $override_image['height'] ?? '';
                    } else {
                        $image_id     = get_post_thumbnail_id($post->ID);
                        $image_data   = $image_id ? wp_get_attachment_image_src($image_id, 'large') : false;
                        $image_url    = $image_data[0] ?? get_the_post_thumbnail_url($post->ID, 'large');
                        $image_alt    = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
                        $image_width  = $image_data[1] ?? '';
                        $image_height = $image_data[2] ?? '';
                    }
                ?>

                <div class="jmdc-case-study-slider__slide swiper-slide">
                    <a aria-label="View case study <?php echo esc_attr($title); ?>" href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="jmdc-case-study-slider__link">

                        <div class="jmdc-case-study-slider__media">
                            <?php if (!empty($video)) : ?>
                                <video
                                    class="jmdc-case-study-slider__video"
                                    autoplay
                                    muted
                                    loop
                                    playsinline
                                    poster="<?php echo esc_url($image_url); ?>"
                                >
                                    <source src="<?php echo esc_url($video['url']); ?>" type="video/mp4">
                                </video>
                            <?php elseif ($image_url) : ?>
                                <img
                                    class="jmdc-case-study-slider__image"
                                    src="<?php echo esc_url($image_url); ?>"
                                    alt="<?php echo esc_attr($image_alt ?: $title); ?>"
                                    <?php if ($image_width) : ?>
                                        width="<?php echo esc_attr($image_width); ?>"
                                    <?php endif; ?>
                                    <?php if ($image_height) : ?>
                                        height="<?php echo esc_attr($image_height); ?>"
                                    <?php endif; ?>
                                >
                            <?php endif; ?>
                        </div>

                    </a>
                </div>

                <?php endforeach; wp_reset_postdata(); ?>
            </div>
            <div class="jmdc-case-study-slider__pagination swiper-pagination" aria-hidden="true"></div>
        </div>
    </div>
</section>
<?php endif; ?>
