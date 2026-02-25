<?php
add_action('admin_menu', function () {
  // Hide "Posts" menu
  remove_menu_page('edit.php');
}, 999);

add_action('admin_bar_menu', function ($wp_admin_bar) {
  // Hide "New Post" from the +New menu
  $wp_admin_bar->remove_node('new-post');
}, 999);