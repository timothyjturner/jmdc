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
<section class="jmdc-about-info<?php echo esc_attr($class_name); ?>">
    <div class="jmdc-about-info__container">
        <div class="jmdc-about-info">
            <?php if ($image) : ?>
                <div class="jmdc-about-info__image">
                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                </div>
            <?php endif; ?>
            <?php if ($name) : ?>
                    <h2 class="jmdc-about-info__name"><?php echo esc_html($name); ?></h2>
            <?php endif; ?>
            <?php if ($designation) : ?>
                <span class="jmdc-about-info__designation"><?php echo esc_html($designation); ?></span>
            <?php endif; ?>
        </div>
         <?php if ($about_text) : ?>
        <div class="jmdc-about info__content">
            <p class="jmdc-about-info__text">"<?php echo esc_html($about_text); ?>"</p>
        </div>
        <?php endif; ?>
    </div>
</section>