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

$class_name = '';
if (! empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}

$display_quote = null;

if (! empty($quotes) && is_array($quotes)) {
    foreach ($quotes as $quote) {
        $quote_content = $quote['jmdc_quote_content'] ?? '';

        if (! empty(trim(wp_strip_all_tags($quote_content)))) {
            $display_quote = array(
                'content'       => $quote_content,
                'font_desktop'  => ! empty($quote['jmdc_quote_font_size_desktop']) ? (int) $quote['jmdc_quote_font_size_desktop'] : '',
                'font_mobile'   => ! empty($quote['jmdc_quote_font_size_mobile']) ? (int) $quote['jmdc_quote_font_size_mobile'] : '',
            );
            break;
        }
    }
}

$has_bio = ! empty(trim(wp_strip_all_tags((string) $bio_content)));
$bio_link_label = ! empty($bio_link_label) ? $bio_link_label : 'VIEW BIO';
$modal_id = 'jmdc-about-info-modal-' . (! empty($block['id']) ? sanitize_html_class($block['id']) : wp_unique_id());

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

        <?php if (! empty($display_quote)) : ?>
            <?php
            $style = '';

            if (! empty($display_quote['font_desktop'])) {
                $style .= '--quote-font-size-desktop:' . (int) $display_quote['font_desktop'] . 'px;';
            }

            if (! empty($display_quote['font_mobile'])) {
                $style .= '--quote-font-size-mobile:' . (int) $display_quote['font_mobile'] . 'px;';
            }
            ?>
            <div class="jmdc-about-info__content">
                <div class="jmdc-about-info__text-wrap" <?php echo $style ? 'style="' . esc_attr($style) . '"' : ''; ?>>
                    <div class="jmdc-about-info__text">
                        <?php echo wp_kses_post($display_quote['content']); ?>
                    </div>
                </div>

                <?php if ($has_bio) : ?>
                    <div class="jmdc-about-info__actions">
                        <button
                            type="button"
                            class="jmdc-about-info__bio-trigger"
                            data-about-info-open="<?php echo esc_attr($modal_id); ?>"
                            aria-controls="<?php echo esc_attr($modal_id); ?>"
                            aria-haspopup="dialog"
                        >
                            <?php echo esc_html($bio_link_label); ?>
                        </button>
                    </div>

                    <div
                        id="<?php echo esc_attr($modal_id); ?>"
                        class="jmdc-about-info__modal"
                        data-about-info-modal
                        hidden
                    >
                        <div class="jmdc-about-info__modal-backdrop" data-about-info-close></div>

                        <div
                            class="jmdc-about-info__modal-dialog"
                            role="dialog"
                            aria-modal="true"
                            aria-labelledby="<?php echo esc_attr($modal_id); ?>-title"
                        >
                            <button
                                type="button"
                                class="jmdc-about-info__modal-close"
                                data-about-info-close
                                aria-label="<?php echo esc_attr__('Close bio', 'jmdc'); ?>"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>

                            <div class="jmdc-about-info__modal-content">
                                <?php if ($name) : ?>
                                    <h3 id="<?php echo esc_attr($modal_id); ?>-title" class="jmdc-about-info__modal-title">
                                        <?php echo esc_html($name); ?>
                                    </h3>
                                <?php else : ?>
                                    <h3 id="<?php echo esc_attr($modal_id); ?>-title" class="screen-reader-text">
                                        <?php esc_html_e('Bio', 'jmdc'); ?>
                                    </h3>
                                <?php endif; ?>

                                <?php if ($designation) : ?>
                                    <div class="jmdc-about-info__modal-designation"><?php echo esc_html($designation); ?></div>
                                <?php endif; ?>

                                <div class="jmdc-about-info__modal-body">
                                    <?php echo wp_kses_post(wpautop($bio_content)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
