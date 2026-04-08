<?php
if (!defined('ABSPATH')) {
	exit;
}

add_action('acf/init', function () {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key' => 'group_jmd_front_page_intro',
		'title' => 'Front Page Intro Animation',
		'fields' => [
			[
				'key' => 'field_jmd_intro_enable',
				'label' => 'Enable Intro Animation',
				'name' => 'jmd_intro_enable',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 0,
				'message' => 'Enable intro animation on front page',
			],
			[
				'key' => 'field_jmd_intro_video',
				'label' => 'Intro Video',
				'name' => 'jmd_intro_video',
				'type' => 'file',
				'return_format' => 'array',
				'library' => 'all',
				'mime_types' => 'mp4,webm',
				'conditional_logic' => [
					[
						[
							'field' => 'field_jmd_intro_enable',
							'operator' => '==',
							'value' => '1',
						],
					],
				],
			],
			[
				'key' => 'field_jmd_intro_autoplay_muted',
				'label' => 'Muted Autoplay',
				'name' => 'jmd_intro_autoplay_muted',
				'type' => 'true_false',
				'ui' => 1,
				'default_value' => 1,
				'message' => 'Recommended for autoplay',
				'conditional_logic' => [
					[
						[
							'field' => 'field_jmd_intro_enable',
							'operator' => '==',
							'value' => '1',
						],
					],
				],
			],
		],
		'location' => [
			[
				[
					'param' => 'page_type',
					'operator' => '==',
					'value' => 'front_page',
				],
			],
		],
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'active' => true,
	]);
});