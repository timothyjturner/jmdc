<?php
/**
 * Testimonial Slider Block template.
 *
 * @param array $block The block settings and attributes.
 */

$testimonial_slider = get_field('jmdc_testimonial_slider');

$class_name = '';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}

$valid_testimonials = [];

if (!empty($testimonial_slider) && is_array($testimonial_slider)) {
    foreach ($testimonial_slider as $testimonial) {
        $quote = $testimonial['jmdc_testimonial'] ?? '';

        if (!empty($quote)) {
            $valid_testimonials[] = $testimonial;
        }
    }
}

if (!empty($valid_testimonials)) :
?>
<section class="jmdc-testimonial-slider <?php echo esc_attr($class_name); ?>">
    <div class="jmdc-testimonial-slider__container swiper">
        <div class="swiper-wrapper">
            <?php foreach ($valid_testimonials as $testimonial) : ?>
                <div class="jmdc-testimonial-slider__item swiper-slide">
                    <div class="jmdc-testimonial-slider__content">
                        <?php if (!empty($testimonial['jmdc_testimonial'])) : ?>
                            <div class="jmdc-testimonial-slider__text-wrap">
                                <p class="jmdc-testimonial-slider__text" data-fit-text>
                                    “<?php echo wp_kses($testimonial['jmdc_testimonial'], array('br' => array())); ?>”
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php
                        $author_name = $testimonial['jmdc_author_name'] ?? '';
                        $designation = $testimonial['jmdc_designation'] ?? '';
                        ?>

                        <?php if (!empty($author_name) || !empty($designation)) : ?>
                            <div class="jmdc-testimonial-slider__author-info">
                                <?php if (!empty($author_name)) : ?>
                                    <span class="jmdc-testimonial-slider__author">
                                        – <?php echo esc_html($author_name); ?><?php echo !empty($designation) ? ',' : ''; ?>
                                    </span>
                                <?php endif; ?>

                                <?php if (!empty($designation)) : ?>
                                    <span class="jmdc-testimonial-slider__author-title"><?php echo esc_html($designation); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="jmdc-testimonial-slider__pagination swiper-pagination" aria-hidden="true"></div>
    </div>
</section>
<?php endif; ?>