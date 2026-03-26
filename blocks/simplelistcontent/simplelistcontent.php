<?php
/**
 * Simple List Content Block template.
 *
 * @param array $block The block settings and attributes.
 */

$simple_list_content = get_field('jmdc_simple_list_content');

$class_name = '';
if (! empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}

if (! empty($simple_list_content)) :
?>
<section class="jmdc-simple-list-content jmdc-simple-list-content--marquee<?php echo esc_attr($class_name); ?>">
    <h2 class="jmdc-simple-list-content__title">CAPABILITIES</h2>

    <div class="jmdc-simple-list-content__marquee">
        <div class="jmdc-simple-list-content__track">
            <div class="jmdc-simple-list-content__group">
                <?php foreach ($simple_list_content as $item) : ?>
                    <?php if (! empty($item['jmdc_heading'])) : ?>
                        <span class="jmdc-simple-list-content__item">
                            <?php echo esc_html($item['jmdc_heading']); ?>
                        </span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="jmdc-simple-list-content__group" aria-hidden="true">
                <?php foreach ($simple_list_content as $item) : ?>
                    <?php if (! empty($item['jmdc_heading'])) : ?>
                        <span class="jmdc-simple-list-content__item">
                            <?php echo esc_html($item['jmdc_heading']); ?>
                        </span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>