<?php
/**
 * About Info Block template.
 *
 * @param array $block The block settings and attributes.
 */

$image = get_field('jmdc_image');
$name = get_field('jmdc_name');
$designation = get_field('jmdc_designation');
$about_text = get_field('jmdc_about_text');

$class_name = '';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}
?>
<section class="jmdc-about-info jmdc-reveal<?php echo esc_attr($class_name); ?>">
    <div class="jmdc-about-info__container">
        <div class="jmdc-about-info">
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
            <div class="jmdc-about-info__wrapper">
            <?php if ($name) : ?>
                    <h2 class="jmdc-about-info__name"><?php echo esc_html($name); ?></h2>
            <?php endif; ?>
                <?php if ($designation) : ?>
                    <span class="jmdc-about-info__designation"><?php echo esc_html($designation); ?></span>
                <?php endif; ?>
            </div>
        </div>
         <?php if ($about_text) : ?>
        <div class="jmdc-about info__content">
            <p class="jmdc-about-info__text">"<?php echo esc_html($about_text); ?>"</p>
        </div>
        <?php endif; ?>
    </div>
</section>