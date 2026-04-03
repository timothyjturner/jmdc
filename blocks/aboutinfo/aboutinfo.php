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
                <h3 id="<?php echo esc_attr($modal_id); ?>-title" class="screen-reader-text">
                    <?php esc_html_e('Bio', 'jmdc'); ?>
                </h3>

                <div class="jmdc-about-info__modal-body">
                    <?php echo wp_kses_post(wpautop($bio_content)); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>