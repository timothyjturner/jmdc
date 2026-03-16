<?php
/**
 * Case Study Grid Block template.
 *
 * @param array $block The block settings and attributes.
 */

$case_studies = get_field('jmdc_select_case_study_grid');

$class_name = '';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}

if ($case_studies) :
?>

<section class="jmdc-case-study-grid<?php echo esc_attr($class_name); ?>">
    <div class="jmdc-work__inner">
        <?php
        $total = count($case_studies);
        $i = 0;

        while ($i < $total) :
        ?>
            <div class="jmdc-case-study-row single-item">
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

                <div class="jmdc-case-study-item jmdc-reveal">
                    <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                        <div class="jmdc-case-study-image">
                            <?php if (!empty($video_url)) : ?>
                                <video 
                                    autoplay 
                                    muted 
                                    loop 
                                    playsinline
                                    poster="<?php echo esc_url($image_url); ?>"
                                >
                                    <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            <?php elseif ($image_url) : ?>
                                <img 
                                    src="<?php echo esc_url($image_url); ?>" 
                                    alt="<?php echo esc_attr($image_alt ? $image_alt : $title); ?>"
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
            </div>

        <?php
            $i++;
            if ($i < $total) :
        ?>
                <div class="jmdc-case-study-row two-items">

                    <?php for ($j = 0; $j < 2 && $i < $total; $j++, $i++) :

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
                                        <video 
                                            autoplay 
                                            muted 
                                            loop 
                                            playsinline
                                            poster="<?php echo esc_url($image_url); ?>"
                                        >
                                            <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    <?php elseif ($image_url) : ?>
                                        <img 
                                            src="<?php echo esc_url($image_url); ?>" 
                                            alt="<?php echo esc_attr($image_alt ? $image_alt : $title); ?>"
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
                    <?php endfor; ?>

                </div>
        <?php endif; ?>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
</section>
<?php endif; ?>
