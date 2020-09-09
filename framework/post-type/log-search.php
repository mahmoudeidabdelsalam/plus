<?php
// Register Careers custom Post Type
function log_search_post_type() {
    $labels = array(
      'name' => __('Log search', 'Post Type General Name', 'post-type'),
      'singular_name' => _x('Log search', 'Post Type Singular Name', 'post-type'),
      'menu_name' => __('Log search', 'post-type'),
      'parent_item_colon' => __('Parent Log search:', 'post-type'),
      'all_items' => __('All', 'post-type'),
      'view_item' => __('View Log search', 'post-type'),
      'add_new_item' => __('Add New Log search', 'post-type'),
      'add_new' => __('Add New', 'post-type'),
      'edit_item' => __('Edit Log search', 'post-type'),
      'update_item' => __('Update Log search', 'post-type'),
      'search_items' => __('Search Log search', 'post-type'),
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
    register_post_type('log_search', $args);
}

// Hook into the 'init' action
add_action('init', 'log_search_post_type', 0);

