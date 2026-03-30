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
        $quote_text = $quote['jmdc_quote_text'] ?? '';

        if (! empty($quote_text)) {
            $valid_quotes[] = $quote_text;
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
                data-count="<?php echo esc_attr($quote_count); ?>"
            >
                <div class="jmdc-about-info__slider-viewport">
                    <div class="jmdc-about-info__slider-track">
                        <?php foreach ($valid_quotes as $index => $quote_text) : ?>
                            <div
                                class="jmdc-about-info__slide<?php echo $index === 0 ? ' is-active' : ''; ?>"
                                data-slide="<?php echo esc_attr($index); ?>"
                                <?php echo $index === 0 ? '' : 'hidden'; ?>
                            >
                                <div class="jmdc-about-info__text-wrap">
                                    <p class="jmdc-about-info__text" data-fit-text>
                                        “<?php echo wp_kses(nl2br($quote_text), array('br' => array())); ?>”
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ($quote_count > 1) : ?>
                    <div class="jmdc-about-info__dots" aria-label="Quote navigation">
                        <?php foreach ($valid_quotes as $index => $quote_text) : ?>
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