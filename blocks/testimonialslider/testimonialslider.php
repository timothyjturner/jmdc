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

if ($testimonial_slider) :
?>
<section class="jmdc-testimonial-slider <?php echo esc_attr($class_name); ?>">
    <div class="jmdc-testimonial-slider__container swiper">
        <div class="swiper-wrapper">
            <?php foreach ($testimonial_slider as $testimonial) : ?>
                <div class="jmdc-testimonial-slider__item swiper-slide">
                    <div class="jmdc-testimonial-slider__content">
                        <?php if (!empty($testimonial['jmdc_testimonial'])) : ?>
                            <p class="jmdc-testimonial-slider__text">"<?php echo esc_html($testimonial['jmdc_testimonial']); ?>"</p>
                        <?php endif; ?>
                        <div class="jmdc-testimonial-slider__author-info">
                            <?php if (!empty($testimonial['jmdc_author_name'])) : ?>
                                <span class="jmdc-testimonial-slider__author">- <?php echo esc_html($testimonial['jmdc_author_name']); ?>,</span>
                            <?php endif; ?>
                            <?php if (!empty($testimonial['jmdc_designation'])) : ?>
                                <span class="jmdc-testimonial-slider__author-title"><?php echo esc_html($testimonial['jmdc_designation']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="jmdc-testimonial-slider__pagination swiper-pagination" aria-hidden="true"></div>
    </div>
</section>
<?php endif; ?>