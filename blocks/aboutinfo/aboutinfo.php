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
$bio_content = get_field('jmdc_bio_content');
$bio_link_label = get_field('jmdc_bio_link_label');

if (empty($bio_link_label)) {
    $bio_link_label = 'VIEW BIO';
}

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
                'content'      => $quote_content,
                'font_desktop' => ! empty($quote['jmdc_quote_font_size_desktop']) ? (int) $quote['jmdc_quote_font_size_desktop'] : '',
                'font_mobile'  => ! empty($quote['jmdc_quote_font_size_mobile']) ? (int) $quote['jmdc_quote_font_size_mobile'] : '',
            );
        }
    }
}

$first_quote = ! empty($valid_quotes) ? $valid_quotes[0] : null;
$has_bio = ! empty(trim(wp_strip_all_tags((string) $bio_content)));
$toggle_id = 'jmdc-about-info-toggle-' . $block['id'];
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

        <?php if ($first_quote || $has_bio) : ?>
            <?php
            $style = '';

            if ($first_quote && ! empty($first_quote['font_desktop'])) {
                $style .= '--quote-font-size-desktop:' . (int) $first_quote['font_desktop'] . 'px;';
            }

            if ($first_quote && ! empty($first_quote['font_mobile'])) {
                $style .= '--quote-font-size-mobile:' . (int) $first_quote['font_mobile'] . 'px;';
            }
            ?>

            <div
                id="<?php echo esc_attr($toggle_id); ?>"
                class="jmdc-about-info__content"
                data-about-info-toggle
                data-view-bio-label="<?php echo esc_attr($bio_link_label); ?>"
                data-view-quote-label="<?php echo esc_attr__('VIEW QUOTE', 'jmdc'); ?>"
            >
                <div class="jmdc-about-info__viewport">
                    <div class="jmdc-about-info__panel-wrap">
                        <?php if ($first_quote) : ?>
                            <div
                                class="jmdc-about-info__panel jmdc-about-info__panel--quote is-active"
                                data-about-info-panel="quote"
                                <?php echo $style ? 'style="' . esc_attr($style) . '"' : ''; ?>
                            >
                                <div class="jmdc-about-info__text jmdc-about-info__text--quote">
                                    <?php echo wp_kses_post($first_quote['content']); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($has_bio) : ?>
                            <div
                                class="jmdc-about-info__panel jmdc-about-info__panel--bio<?php echo $first_quote ? '' : ' is-active'; ?>"
                                data-about-info-panel="bio"
                                <?php echo $first_quote ? 'hidden' : ''; ?>
                            >
                                <div class="jmdc-about-info__text jmdc-about-info__text--bio">
                                    <?php echo wp_kses_post(wpautop($bio_content)); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($first_quote && $has_bio) : ?>
                    <div class="jmdc-about-info__actions">
                        <button
                            type="button"
                            class="jmdc-about-info__toggle-button"
                            data-about-info-button
                            aria-controls="<?php echo esc_attr($toggle_id); ?>"
                            aria-pressed="false"
                        >
                            <?php echo esc_html($bio_link_label); ?>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>