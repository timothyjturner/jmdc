<?php wp_footer(); ?>

<footer class="jmdc-footer" role="contentinfo">
  <div class="jmdc-footer__inner">

    <div class="jmdc-footer__top">
      <h2 class="jmdc-footer__title">GET IN TOUCH</h2>

      <div class="jmdc-footer__grid">
        <div class="jmdc-footer__contact">
          <p class="jmdc-footer__contact-lines">
            525 7<sup>th</sup> Avenue<br>
            Suite 1406<br>
            New York, New York
          </p>

          <p class="jmdc-footer__contact-lines">
            <?php
                $footer_email = get_theme_mod('jmdc_footer_email', 'contact@jmdcreative.com');
                $footer_email = sanitize_email($footer_email);
                ?>
                <?php if (!empty($footer_email)) : ?>
                <a class="jmdc-footer__link" href="<?php echo esc_url('mailto:' . $footer_email); ?>">
                    <?php echo esc_html($footer_email); ?>
                </a>
            <?php endif; ?>
          </p>
        </div>

        <div class="jmdc-footer__social">
          <?php
          // Fallback defaults if menu is empty/unassigned for any reason
          $fallback_items = [
            ['title' => 'Instagram', 'url' => 'https://www.instagram.com/jonmichaeldesign/'],
            ['title' => 'LinkedIn',  'url' => 'https://www.linkedin.com/company/jon-michael-design/'],
          ];

          $has_menu = has_nav_menu('jmdc-footer-social');

          if ($has_menu) {
            wp_nav_menu([
              'theme_location' => 'jmdc-footer-social',
              'container'      => 'nav',
              'container_class'=> 'jmdc-footer__social-nav',
              'menu_class'     => 'jmdc-footer__social-list',
              'fallback_cb'    => false,
              'depth'          => 1,
              'link_before'    => '<span class="jmdc-footer__social-text">',
              'link_after'     => '</span>',
            ]);
          } else {
            echo '<nav class="jmdc-footer__social-nav" aria-label="Footer social links">';
            echo '<ul class="jmdc-footer__social-list">';
            foreach ($fallback_items as $it) {
              printf(
                '<li class="jmdc-footer__social-item"><a class="jmdc-footer__social-link" href="%s" target="_blank" rel="noopener noreferrer"><span class="jmdc-footer__social-text">%s</span></a></li>',
                esc_url($it['url']),
                esc_html(strtoupper($it['title']))
              );
            }
            echo '</ul></nav>';
          }
          ?>
        </div>
      </div>
    </div>

    <?php
        $lockup_id  = (int) get_theme_mod('jmdc_footer_brand_lockup_image', 0);
        $lockup_src = $lockup_id ? wp_get_attachment_image_src($lockup_id, 'full') : false;
        $lockup_alt = $lockup_id ? get_post_meta($lockup_id, '_wp_attachment_image_alt', true) : '';
        $lockup_alt = $lockup_alt ? $lockup_alt : get_bloginfo('name');
        ?>

    <div class="jmdc-footer__bottom">
        <div class="jmdc-footer__brand-lockup">
            <?php if (!empty($lockup_src[0])) : ?>
            <img
                class="jmdc-footer__brand-lockup-img"
                src="<?php echo esc_url($lockup_src[0]); ?>"
                alt="<?php echo esc_attr($lockup_alt); ?>"
                loading="lazy"
            />
            <?php else : ?>
            <!-- Fallback if the lockup image hasn’t been set yet -->
            <a class="jmdc-footer__site-name" href="<?php echo esc_url(home_url('/')); ?>">
                <?php echo esc_html(get_bloginfo('name')); ?>
            </a>
            <?php endif; ?>
        </div>
    </div>

  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>