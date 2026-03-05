<?php
/**
 * Hero Text Block template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$heading           = get_field( 'jmdc_hero_heading' );
$description       = get_field( 'jmdc_hero_description' );

// Create class attribute allowing for custom "className"
$class_name = '';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}
?>

<section class="jmdc-herotext jmdc-herotext--animate-on-load<?php echo esc_attr( $class_name ); ?>">
    <header class="jmdc-herotext__header">
      <h1 class="jmdc-work__title"><?php echo esc_html( $heading ); ?></h1>
    </header>
  <div class="jmdc-herotext__description"><?php echo $description; ?></div>
</section>
