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
            <a class="jmdc-footer__link" href="mailto:contact@jmdcreative.com">contact@jmdcreative.com</a>
          </p>
        </div>

        <div class="jmdc-footer__social">
          <?php
          // Fallback defaults if menu is empty/unassigned for any reason
          $fallback_items = [
            ['title' => 'Instagram', 'url' => 'https://instagram.com/'],
            ['title' => 'LinkedIn',  'url' => 'https://www.linkedin.com/'],
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

    <div class="jmdc-footer__bottom">
      <div class="jmdc-footer__brand">
        <div class="jmdc-footer__logo">
          <?php
          // Use Custom Logo if set, else site name
          if (function_exists('the_custom_logo') && has_custom_logo()) {
            the_custom_logo();
          } else {
            printf('<a class="jmdc-footer__site-name" href="%s">%s</a>',
              esc_url(home_url('/')),
              esc_html(get_bloginfo('name'))
            );
          }
          ?>
        </div>

        <span class="jmdc-footer__divider" aria-hidden="true"></span>

        <div class="jmdc-footer__subbrand">
          A <span class="jmdc-footer__subbrand-accent">TAYLOR</span> COMPANY
        </div>
      </div>
    </div>

  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>