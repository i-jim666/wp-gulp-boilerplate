<?php

// Add custom taxonomies below

$labels = array( 
    'name' => _x( 'Locations', 'post_location' ),
    'singular_name' => _x( 'Location', 'post_location' ),
    'search_items' => _x( 'Locations', 'post_location' ),
    'popular_items' => _x( 'Popular Locations', 'post_location' ),
    'all_items' => _x( 'All Locations', 'post_location' ),
    'parent_item' => _x( 'Parent Location', 'post_location' ),
    'parent_item_colon' => _x( 'Parent Location:', 'post_location' ),
    'edit_item' => _x( 'Edit Location', 'post_location' ),
    'update_item' => _x( 'Update Location', 'post_location' ),
    'add_new_item' => _x( 'Add new Location', 'post_location' ),
    'new_item_name' => _x( 'New Location', 'post_location' ),
    'separate_items_with_commas' => _x( 'Separate Locations with commas', 'post_location' ),
    'add_or_remove_items' => _x( 'Add or remove Location', 'post_location' ),
    'choose_from_most_used' => _x( 'Choose from the most used Location', 'post_location' ),
    'menu_name' => _x( 'Locations', 'post_location' ),
);

$args = array( 
    'labels' => $labels,
    'public' => true,
    'show_in_nav_menus' => true,
    'show_ui' => true,
    'show_tagcloud' => true,
    'show_admin_column' => false,
    'hierarchical' => true,
    'show_in_rest' => true,
    'rewrite' => true,
    'has_archive' => false,
    'query_var' => true
);

register_taxonomy( 'post_location', array('post'), $args );

?>