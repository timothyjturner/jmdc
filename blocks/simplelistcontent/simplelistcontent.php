<?php
/**
 * Simple List Content Block template.
 *
 * @param array $block The block settings and attributes.
 */

$simple_list_content = get_field('jmdc_simple_list_content');
$simple_list_version = get_field('jmdc_simple_list_version');

if (empty($simple_list_version)) {
    $simple_list_version = 'animated';
}

$class_name = '';
if (! empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}

if (! empty($simple_list_content)) :
    $section_classes = 'jmdc-simple-list-content';

    if ($simple_list_version === 'animated') {
        $section_classes .= ' jmdc-simple-list-content--marquee';
    } else {
        $section_classes .= ' jmdc-simple-list-content--grid';
    }

    $section_classes .= $class_name;
?>
<section class="<?php echo esc_attr($section_classes); ?>">
    <h2 class="jmdc-simple-list-content__title">CAPABILITIES</h2>

    <?php if ($simple_list_version === 'animated') : ?>
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
    <?php else : ?>
        <div class="jmdc-simple-list-content__grid">
            <?php foreach ($simple_list_content as $item) : ?>
                <?php if (! empty($item['jmdc_heading'])) : ?>
                    <div class="jmdc-simple-list-content__grid-item">
                        <span><?php echo esc_html($item['jmdc_heading']); ?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php endif; ?>