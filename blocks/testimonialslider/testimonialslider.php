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
        $quote_content = $testimonial['jmdc_testimonial'] ?? '';

        if (!empty(trim(wp_strip_all_tags($quote_content)))) {
            $valid_testimonials[] = array(
                'content'       => $quote_content,
                'font_desktop'  => !empty($testimonial['jmdc_testimonial_font_size_desktop']) ? (int) $testimonial['jmdc_testimonial_font_size_desktop'] : '',
                'font_mobile'   => !empty($testimonial['jmdc_testimonial_font_size_mobile']) ? (int) $testimonial['jmdc_testimonial_font_size_mobile'] : '',
                'author_name'   => $testimonial['jmdc_author_name'] ?? '',
                'designation'   => $testimonial['jmdc_designation'] ?? '',
            );
        }
    }
}

if (!empty($valid_testimonials)) :
?>
<section class="jmdc-testimonial-slider <?php echo esc_attr($class_name); ?>">
    <div class="jmdc-testimonial-slider__container swiper">
        <div class="swiper-wrapper">
            <?php foreach ($valid_testimonials as $testimonial) : ?>
                <?php
                $style = '';

                if (!empty($testimonial['font_desktop'])) {
                    $style .= '--quote-font-size-desktop:' . (int) $testimonial['font_desktop'] . 'px;';
                }

                if (!empty($testimonial['font_mobile'])) {
                    $style .= '--quote-font-size-mobile:' . (int) $testimonial['font_mobile'] . 'px;';
                }

                $author_name = $testimonial['author_name'];
                $designation = $testimonial['designation'];
                ?>
                <div class="jmdc-testimonial-slider__item swiper-slide">
                    <div class="jmdc-testimonial-slider__content">
                        <div class="jmdc-testimonial-slider__text-wrap" <?php echo $style ? 'style="' . esc_attr($style) . '"' : ''; ?>>
                            <div class="jmdc-testimonial-slider__text">
                                <?php echo wp_kses_post($testimonial['content']); ?>
                            </div>
                        </div>

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