<?php
/**
 * Logo Grid Block template.
 *
 * @param array $block The block settings and attributes.
 */

$logo_grid = get_field('jmdc_logo_grid');

$class_name = '';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}
if($logo_grid) :
?>
<section class="jmdc-logo-grid jmdc-reveal<?php echo esc_attr($class_name); ?>">
    <div class="jmdc-logo-grid__container">
        <div class="jmdc-logo-grid__grid">
            <?php foreach ($logo_grid as $logo) : ?>
                <div class="jmdc-logo-grid__item">
                    <?php if (!empty($logo['jmdc_logo'])) : ?>
                        <img src="<?php echo esc_url($logo['jmdc_logo']['url']); ?>" alt="<?php echo esc_attr($logo['jmdc_logo']['alt']); ?>" width="<?php echo esc_attr($logo['jmdc_logo']['width']); ?>" height="<?php echo esc_attr($logo['jmdc_logo']['height']); ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>