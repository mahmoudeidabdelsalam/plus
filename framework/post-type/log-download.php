<?php
// Register Careers custom Post Type
function log_download_post_type() {
    $labels = array(
      'name' => __('Log Download', 'Post Type General Name', 'post-type'),
      'singular_name' => _x('Log Download', 'Post Type Singular Name', 'post-type'),
      'menu_name' => __('Log Download', 'post-type'),
      'parent_item_colon' => __('Parent Log Download:', 'post-type'),
      'all_items' => __('All', 'post-type'),
      'view_item' => __('View Log Download', 'post-type'),
      'add_new_item' => __('Add New Log Download', 'post-type'),
      'add_new' => __('Add New', 'post-type'),
      'edit_item' => __('Edit Log Download', 'post-type'),
      'update_item' => __('Update Log Download', 'post-type'),
      'search_items' => __('Search Log Download', 'post-type'),
      'not_found' => __('Not found', 'post-type'),
      'not_found_in_trash' => __('Not found in Trash', 'post-type'),
    );
    $args = array(
      'labels' => $labels,
      'supports' => array('title','revisions','editor','thumbnail',),
      'hierarchical' => false,
      'public' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'show_in_nav_menus' => true,
      'show_in_admin_bar' => true,
      'menu_position' => 5,
      'menu_icon' => 'dashicons-welcome-write-blog',
      'can_export' => true,
      'has_archive' => true,
      'exclude_from_search' => false,
      'publicly_queryable' => true,
      'capability_type' => 'page',
      'show_in_rest' => true,
    );
    register_post_type('log_download', $args);
}

// Hook into the 'init' action
add_action('init', 'log_download_post_type', 0);

