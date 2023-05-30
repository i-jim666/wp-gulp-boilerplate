<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;


// Load Carbon fields through composer
add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
    require_once get_template_directory() . '/vendor/autoload.php';
    \Carbon_Fields\Carbon_Fields::boot();
}

// Get and set data for gutenberg blocks
function getData (){
    return get_query_var( 'component_data', [] );
}

function setData ($data){
    return set_query_var( 'component_data', $data );
}




// Add menu support 

add_theme_support( 'menus' );
add_action( 'init', 'register_my_menus' );

function register_my_menus() {
    register_nav_menus(
        array(
            'primary-menu' => __( 'Primary Menu' ),
        )
    );
}


// Add support for post thumbnail
add_theme_support( 'post-thumbnails' );



function title_tag_support() {

    // Adds <title> tag support
    add_theme_support( 'title-tag' );  

}
add_action( 'after_setup_theme', 'title_tag_support');


?>