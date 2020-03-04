<?php

// Register Custom Taxonomy
function graphics_categories() {

    $labels = array(
        'name' => 'Plus Categories',
        'singular_name' => 'graphics',
        'menu_name' => 'Plus Categories',
        'all_items' => 'All Plus Categories',
        'parent_item' => 'Parent Plus Category',
        'parent_item_colon' => 'Parent Plus Category:',
        'new_item_name' => 'New Item Plus Category',
        'add_new_item' => 'Add New Plus Category',
        'edit_item' => 'Edit Plus Category',
        'update_item' => 'Update Plus Category',
        'separate_items_with_commas' => 'Separate items with commas',
        'search_items' => 'Search Plus Categories',
        'add_or_remove_items' => 'Add or remove Plus Categories',
        'choose_from_most_used' => 'Choose from the most used Plus Categories',
        'not_found' => 'Not Found',
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    );
    register_taxonomy('graphics-category', array('graphics'), $args);
}

// Hook into the 'init' action
add_action('init', 'graphics_categories', 0);
