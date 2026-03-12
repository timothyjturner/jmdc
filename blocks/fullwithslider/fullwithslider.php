<?php
/**
 * Full Width Slider Block template.
 *
 * @param array $block The block settings and attributes.
 */

$full_with_slider = get_field('jmdc_full_with_slider');
$class_name       = '';

if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}

if ($full_with_slider) :
	?>
<section class="jmdc-full-with-slider <?php echo esc_attr($class_name); ?>">
	<div class="jmdc-full-with-slider__container swiper">
		<div class="swiper-wrapper">
			<?php foreach ($full_with_slider as $slide) : ?>
				<div class="jmdc-full-with-slider__item swiper-slide">
					<?php
					if (isset($slide['jmdc_media_type']) && 'image' === $slide['jmdc_media_type'] && ! empty($slide['jmdc_image'])) :
						$image_url    = $slide['jmdc_image']['url'] ?? '';
						$image_alt    = $slide['jmdc_image']['alt'] ?? '';
						$image_width  = $slide['jmdc_image']['width'] ?? '';
						$image_height = $slide['jmdc_image']['height'] ?? '';
						?>
						<?php if ($image_url) : ?>
							<img
								class="jmdc-full-with-slider__image"
								src="<?php echo esc_url($image_url); ?>"
								alt="<?php echo esc_attr($image_alt); ?>"
								<?php if ($image_width) : ?>
									width="<?php echo esc_attr($image_width); ?>"
								<?php endif; ?>
								<?php if ($image_height) : ?>
									height="<?php echo esc_attr($image_height); ?>"
								<?php endif; ?>
							>
						<?php endif; ?>
					<?php
					elseif (isset($slide['jmdc_media_type']) && 'video' === $slide['jmdc_media_type'] && ! empty($slide['jmdc_video'])) :
						$video_url  = $slide['jmdc_video']['url'] ?? '';
						$poster_url = $slide['jmdc_image']['url'] ?? '';
						?>
						<?php if ($video_url) : ?>
							<video
								class="jmdc-full-with-slider__video"
								<?php if ($poster_url) : ?>
									poster="<?php echo esc_url($poster_url); ?>"
								<?php endif; ?>
								autoplay
								muted
								loop
								playsinline
								src="<?php echo esc_url($video_url); ?>"
							></video>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="jmdc-full-with-slider__pagination swiper-pagination" aria-hidden="true"></div>
</section>
<?php endif; ?>