<?php
/**
 * Case Study Grid Block template.
 *
 * @param array $block The block settings and attributes.
 */

$case_studies = get_field('jmdc_select_case_study');
$jmdc_select_style = get_field('jmdc_select_style');
$jmdc_button = get_field('jmdc_button');

$class_name = '';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}

if ($case_studies) :
if ($jmdc_select_style === 'style1') :
?>

<section class="jmdc-case-study-grid<?php echo esc_attr($class_name); ?>">
    <div class="jmdc-work__inner">
        <div class="jmdc-case-study-row two-items">

            <?php foreach ($case_studies as $post) : 
                setup_postdata($post);

                $custom_title = get_field('jmdc_case_study_title', $post->ID);
                $subtitle     = get_field('jmdc_case_study_subtitle', $post->ID);
                $title        = $custom_title ?: get_the_title($post->ID);

                $card_media = jmdc_get_case_study_card_media($post->ID);
                $image_url  = $card_media['image_url'] ?: $card_media['poster_url'];
                $image_alt  = $card_media['image_alt'];
                $video_url  = $card_media['video_url'];
            ?>

            <div class="jmdc-case-study-item jmdc-reveal">
                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">

                    <div class="jmdc-case-study-image">
                        <?php if (!empty($video_url)) : ?>
                            <video autoplay muted loop playsinline poster="<?php echo esc_url($image_url); ?>">
                                <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                            </video>
                        <?php elseif ($image_url) : ?>
                            <img 
                                src="<?php echo esc_url($image_url); ?>" 
                                alt="<?php echo esc_attr($image_alt ?: $title); ?>"
                            >
                        <?php endif; ?>
                    </div>

                    <div class="jmdc-case-study-content">
                        <h3><?php echo esc_html($title); ?></h3>
                        <?php if ($subtitle) : ?>
                            <p><?php echo esc_html($subtitle); ?></p>
                        <?php endif; ?>
                    </div>

                </a>
            </div>

            <?php endforeach; wp_reset_postdata(); ?>

        </div>
    </div>
</section>
<?php endif; 
if ($jmdc_select_style === 'style2') :
?> 
<section class="jmdc-case-study-grid<?php echo esc_attr($class_name); ?>">
<div class="jmdc-work__inner">
<?php
$total = count($case_studies);
$i = 0;
$three_row = true;

while ($i < $total) :

if ($three_row) : ?>
    
    <div class="jmdc-case-study-row three-items">

        <?php for ($j = 0; $j < 3 && $i < $total; $j++, $i++) :

            $post = $case_studies[$i];
            setup_postdata($post);

            $custom_title = get_field('jmdc_case_study_title', $post->ID);
            $subtitle     = get_field('jmdc_case_study_subtitle', $post->ID);
            $title        = $custom_title ?: get_the_title($post->ID);

            $card_media = jmdc_get_case_study_card_media($post->ID);
            $image_url  = $card_media['image_url'] ?: $card_media['poster_url'];
            $image_alt  = $card_media['image_alt'];
            $video_url  = $card_media['video_url'];
        ?>

        <div class="jmdc-case-study-item jmdc-reveal">
            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                
                <div class="jmdc-case-study-image">
                    <?php if (!empty($video_url)) : ?>
                        <video autoplay muted loop playsinline poster="<?php echo esc_url($image_url); ?>">
                            <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                        </video>
                    <?php elseif ($image_url) : ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt ?: $title); ?>">
                    <?php endif; ?>
                </div>

                <div class="jmdc-case-study-content">
                    <h3><?php echo esc_html($title); ?></h3>
                    <?php if ($subtitle) : ?>
                        <p><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                </div>

            </a>
        </div>

        <?php endfor; ?>

    </div>

<?php else : ?>

    <?php
    $post = $case_studies[$i];
    setup_postdata($post);

    $custom_title = get_field('jmdc_case_study_title', $post->ID);
    $subtitle     = get_field('jmdc_case_study_subtitle', $post->ID);
    $title        = $custom_title ?: get_the_title($post->ID);

    $card_media = jmdc_get_case_study_card_media($post->ID);
    $image_url  = $card_media['image_url'] ?: $card_media['poster_url'];
    $image_alt  = $card_media['image_alt'];
    $video_url  = $card_media['video_url'];
    ?>

    <div class="jmdc-case-study-row single-item">

        <div class="jmdc-case-study-item jmdc-reveal">
            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">

                <div class="jmdc-case-study-image">
                    <?php if (!empty($video_url)) : ?>
                        <video autoplay muted loop playsinline poster="<?php echo esc_url($image_url); ?>">
                            <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                        </video>
                    <?php elseif ($image_url) : ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt ?: $title); ?>">
                    <?php endif; ?>
                </div>

                <div class="jmdc-case-study-content">
                    <h3><?php echo esc_html($title); ?></h3>
                    <?php if ($subtitle) : ?>
                        <p><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                </div>

            </a>
        </div>

    </div>

    <?php $i++; ?>

<?php endif;

$three_row = !$three_row;

endwhile;

wp_reset_postdata();
?>
<?php if(!empty($jmdc_button)): ?>
    <div class="jmdc-case-study-button">
        <a href="<?php echo esc_url($jmdc_button['url']); ?>" <?php if(!empty($jmdc_button['target'])): ?>target="<?php echo esc_attr($jmdc_button['target']); ?>"<?php else: ?>target="_self"<?php endif; ?>>
            <?php echo esc_html($jmdc_button['title']); ?>
        </a>
    </div>
<?php endif; ?>
</div>
</section>
<?php endif; endif; ?>
