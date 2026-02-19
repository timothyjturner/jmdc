<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <?php wp_head(); ?>
</head>
<body <?php body_class('jmdc-body'); ?>>
<?php wp_body_open(); ?>
<header class="jmdc-site-header">
  <div class="jmdc-site-header__inner">

    <!-- Center Logo -->
    <div class="jmdc-site-header__logo">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="jmdc-site-header__logo-link" aria-label="<?php bloginfo('name'); ?>">
        <?php
        if (function_exists('the_custom_logo') && has_custom_logo()) {
          the_custom_logo();
        } else {
          echo '<span class="jmdc-site-header__logo-text">' . esc_html(get_bloginfo('name')) . '</span>';
        }
        ?>
      </a>
    </div>

    <!-- Hamburger (shows on tablet/mobile) -->
    <button
      class="jmdc-nav-toggle"
      type="button"
      aria-controls="jmdc-mobile-nav"
      aria-expanded="false"
    >
      <span class="jmdc-nav-toggle__icon" aria-hidden="true"></span>
    </button>

    <!-- Nav -->
    <nav class="jmdc-site-header__nav" id="jmdc-mobile-nav" aria-label="<?php esc_attr_e('Primary navigation', 'your-theme-textdomain'); ?>">
      <?php
      if (has_nav_menu('header_menu')) {
        wp_nav_menu([
          'theme_location' => 'header_menu',
          'container'      => false,
          'menu_class'     => 'jmdc-header-menu',
          'fallback_cb'    => false,
          'depth'          => 1,
        ]);
      } else {
        echo '<ul class="jmdc-header-menu">';
        echo '<li><a href="' . esc_url(home_url('/work/')) . '">WORK</a></li>';
        echo '<li><a href="' . esc_url(home_url('/agency/')) . '">THE AGENCY</a></li>';
        echo '</ul>';
      }
      ?>
    </nav>

  </div>

  <script>
  (function () {
    var btn = document.querySelector('.jmdc-nav-toggle');
    var nav = document.getElementById('jmdc-mobile-nav');
    if (!btn || !nav) return;

    function closeMenu() {
      btn.setAttribute('aria-expanded', 'false');
      btn.classList.remove('is-open');
      nav.classList.remove('is-open');
      document.body.classList.remove('jmdc-nav-open');
    }

    btn.addEventListener('click', function (e) {
      e.stopPropagation(); // prevent immediate close
      var isOpen = btn.getAttribute('aria-expanded') === 'true';

      btn.setAttribute('aria-expanded', String(!isOpen));
      btn.classList.toggle('is-open', !isOpen);
      nav.classList.toggle('is-open', !isOpen);
      document.body.classList.toggle('jmdc-nav-open', !isOpen);
    });

    // 🔥 Close when clicking outside
    document.addEventListener('click', function (e) {
      var isClickInsideNav = nav.contains(e.target);
      var isClickOnButton = btn.contains(e.target);

      if (!isClickInsideNav && !isClickOnButton) {
        closeMenu();
      }
    });

    // Close on ESC
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        closeMenu();
      }
    });

    nav.addEventListener('click', function (e) {
    if (e.target.tagName === 'A') {
        closeMenu();
    }
    });

  })();


</script>

</header>
