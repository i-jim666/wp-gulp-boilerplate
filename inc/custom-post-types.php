<?php

// Our custom post type function
function create_posttype() {
 
    // Add register functions below

    $labels = array(
		'name'                => _x( 'Articles', 'Articles', 'cf_boiler_plate' ),
		'singular_name'       => _x( 'Articles', 'Articles', 'cf_boiler_plate' ),
		'menu_name'           => esc_html__( 'Articles', 'cf_boiler_plate' ),
		'parent_item_colon'   => esc_html__( 'Parent Article', 'cf_boiler_plate' ),
		'all_items'           => esc_html__( 'All Articles', 'cf_boiler_plate' ),
		'view_item'           => esc_html__( 'View Article', 'cf_boiler_plate' ),
		'add_new_item'        => esc_html__( 'Add new Article', 'cf_boiler_plate' ),
		'add_new'             => esc_html__( 'Add new', 'cf_boiler_plate' ),
		'edit_item'           => esc_html__( 'Edit Article', 'cf_boiler_plate' ),
		'update_item'         => esc_html__( 'Update Article', 'cf_boiler_plate' ),
		'search_items'        => esc_html__( 'Search Article', 'cf_boiler_plate' ),
		'not_found'           => esc_html__( 'Not Found', 'cf_boiler_plate' ),
		'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'cf_boiler_plate' ),
	);	

    register_post_type( 'articles',
    
    // CPT Options
        array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'artikler'),
            'show_in_rest' => true,	
            'menu_icon' => 'dashicons-heart', // get dashicons from here https://developer.wordpress.org/resource/dashicons/#editor-paste-word
			'supports' => array('title', 'editor', 'thumbnail')
        )
    );

}

// Hooking up our function to theme setup
// add_action( 'init', 'create_posttype' );

?>