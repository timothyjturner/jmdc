<?php
/**
 * Simple List Content Block template.
 *
 * @param array $block The block settings and attributes.
 */

$simple_list_content = get_field('jmdc_simple_list_content');


$class_name = '';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}
if (!empty($simple_list_content)) :
?>
<section class="jmdc-simple-list-content<?php echo esc_attr($class_name); ?>">
    <div class="jmdc-work__inner">
        <?php foreach ($simple_list_content as $item) : ?>
                <?php if (!empty($item['jmdc_heading'])) : ?>
                    <div class="jmdc-simple-list-content__item">
                        <span>
                            <?php echo $item['jmdc_heading']; ?>
                        </span>
                    </div>
                <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?> 