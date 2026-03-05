<?php
/**
 * Counter Block template.
 *
 * @param array $block The block settings and attributes.
 */

$heading = get_field('jmdc_heading');
$counter = get_field('jmdc_counter');

$class_name = '';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}
if (!empty($counter)) :
?>
<section class="jmdc-counter<?php echo esc_attr($class_name); ?>">
    <div class="jmdc-work__inner">
        <?php if (!empty($heading)) : ?>
            <h2 class="jmdc-counter__heading"><?php echo esc_html($heading); ?></h2>
        <?php endif; ?>
        <div class="jmdc-counter__row">
       <?php foreach ($counter as $item) : 
            $number = $item['jmdc_counter_number'];
            $label = $item['jmdc_counter_heading'];
        ?>
            <div class="jmdc-counter__item">
                <?php if (!empty($number)) : ?>
                    <span class="jmdc-counter__number"><?php echo esc_html($number); ?> +</span>
                <?php endif; ?>
                <?php if (!empty($label)) : ?>
                    <span class="jmdc-counter__label"><?php echo esc_html($label); ?></span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>