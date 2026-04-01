<?php
/**
 * About Info Block template.
 *
 * @param array $block The block settings and attributes.
 */

$image = get_field('jmdc_image');
$name = get_field('jmdc_name');
$designation = get_field('jmdc_designation');
$quotes = get_field('jmdc_quotes');

$class_name = '';
if (! empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}

$valid_quotes = [];

if (! empty($quotes) && is_array($quotes)) {
    foreach ($quotes as $quote) {
        $quote_content = $quote['jmdc_quote_content'] ?? '';

        if (! empty(trim(wp_strip_all_tags($quote_content)))) {
            $valid_quotes[] = array(
                'content'       => $quote_content,
                'font_desktop'  => ! empty($quote['jmdc_quote_font_size_desktop']) ? (int) $quote['jmdc_quote_font_size_desktop'] : '',
                'font_mobile'   => ! empty($quote['jmdc_quote_font_size_mobile']) ? (int) $quote['jmdc_quote_font_size_mobile'] : '',
            );
        }
    }
}

$quote_count = count($valid_quotes);
?>
<section class="jmdc-about-info<?php echo esc_attr($class_name); ?>">
    <div class="jmdc-about-info__container jmdc-reveal">
        <div class="jmdc-about-info__media">
            <?php if ($image) :
                $image_width  = $image['width'] ?? '';
                $image_height = $image['height'] ?? '';
            ?>
                <div class="jmdc-about-info__image">
                    <img
                        src="<?php echo esc_url($image['url']); ?>"
                        alt="<?php echo esc_attr($image['alt']); ?>"
                        <?php if ($image_width) : ?>
                            width="<?php echo esc_attr($image_width); ?>"
                        <?php endif; ?>
                        <?php if ($image_height) : ?>
                            height="<?php echo esc_attr($image_height); ?>"
                        <?php endif; ?>
                    >
                </div>
            <?php endif; ?>

            <?php if ($name || $designation) : ?>
                <div class="jmdc-about-info__meta">
                    <?php if ($name) : ?>
                        <h2 class="jmdc-about-info__name"><?php echo esc_html($name); ?></h2>
                    <?php endif; ?>

                    <?php if ($designation) : ?>
                        <span class="jmdc-about-info__designation"><?php echo esc_html($designation); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (! empty($valid_quotes)) : ?>
            <div
                class="jmdc-about-info__content"
                data-slider
                data-autoplay="true"
                data-autoplay-speed="5000"
            >
                <div class="jmdc-about-info__slider-viewport">
                    <div class="jmdc-about-info__slider-track">
                        <?php foreach ($valid_quotes as $index => $quote) : ?>
                            <?php
                            $style = '';

                            if (! empty($quote['font_desktop'])) {
                                $style .= '--quote-font-size-desktop:' . (int) $quote['font_desktop'] . 'px;';
                            }

                            if (! empty($quote['font_mobile'])) {
                                $style .= '--quote-font-size-mobile:' . (int) $quote['font_mobile'] . 'px;';
                            }
                            ?>
                            <div
                                class="jmdc-about-info__slide<?php echo $index === 0 ? ' is-active' : ''; ?>"
                                data-slide="<?php echo esc_attr($index); ?>"
                                <?php echo $index === 0 ? '' : 'hidden'; ?>
                            >
                                <div class="jmdc-about-info__text-wrap" <?php echo $style ? 'style="' . esc_attr($style) . '"' : ''; ?>>
                                    <div class="jmdc-about-info__text">
                                        <?php echo wp_kses_post($quote['content']); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ($quote_count > 1) : ?>
                    <div class="jmdc-about-info__dots" aria-label="Quote navigation">
                        <?php foreach ($valid_quotes as $index => $quote) : ?>
                            <button
                                type="button"
                                class="jmdc-about-info__dot<?php echo $index === 0 ? ' is-active' : ''; ?>"
                                data-dot="<?php echo esc_attr($index); ?>"
                                aria-label="<?php echo esc_attr('Go to quote ' . ($index + 1)); ?>"
                                aria-pressed="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                            ></button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>