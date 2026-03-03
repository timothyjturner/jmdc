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
?>