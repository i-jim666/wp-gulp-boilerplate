<?php

function add_theme_scripts() {

    // CDN's

    // JQuery

    wp_register_script( 'Jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js', null, null, true );
	wp_enqueue_script('Jquery');

    // Vanilla js datepicker

    wp_enqueue_style( 'datepicker_style', 'https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.2.0/dist/css/datepicker.min.css' );

    wp_register_script( 'Datepicker', 'https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.2.0/dist/js/datepicker-full.min.js', null, null, true );
	wp_enqueue_script('Datepicker');


    // Splide carousel
    wp_enqueue_style( 'splide_style', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css' );
    
    wp_register_script( 'Splide', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js' );
	wp_enqueue_script('Splide');

    wp_register_script( 'splide_autoscroll', 'https://cdn.jsdelivr.net/npm/@splidejs/splide-extension-auto-scroll@0.5.3/dist/js/splide-extension-auto-scroll.min.js' );
	wp_enqueue_script('splide_autoscroll');

    // Tagger

    // wp_register_script( 'Tagger', get_template_directory_uri(). '/src/js/library/tagger.js', array ( 'jquery' ), null, true );
	// wp_enqueue_script('Tagger');
    

    // add base style.css file
    wp_enqueue_style( 'style', get_stylesheet_uri() );

    // add theme style
  
    // Get the theme data.
    $the_theme     = wp_get_theme();
    $theme_version = $the_theme->get( 'Version' );

    $css_version = $theme_version . '.' . filemtime( get_template_directory() . '/dist/css/theme.min.css' );
    wp_enqueue_style( 'scss_styles', get_template_directory_uri() . '/dist/css/theme.min.css', array(), $css_version );


    // AOS style and script
    // wp_enqueue_style( 'aos_style', 'https://unpkg.com/aos@2.3.1/dist/aos.css');
            
    // wp_register_script( 'AOS', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array ( 'jquery' ), null, true );
	// wp_enqueue_script('AOS');

    // gsap script

    // wp_register_script( 'ii-gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.6.0/gsap.min.js', array(), null, true );
    // wp_enqueue_script('ii-gsap');

    // scroll trigger script

    wp_register_script( 'ii-ScrollTrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.6.0/ScrollTrigger.min.js',  array(), null, true );
    wp_enqueue_script('ii-ScrollTrigger');


    // add theme script

    wp_register_script( 'libraries_js', get_template_directory_uri() . '/src/js/library/all_libraries.js', null, null, true );
	wp_enqueue_script('libraries_js');


    // Pagination 
    // wp_register_script( 'pagination_js', get_template_directory_uri() . '/src/js/library/pagination.js', null, null, true );
	// wp_enqueue_script('pagination_js');

    wp_register_script( 'theme_js', get_template_directory_uri() . '/dist/js/theme.min.js', null, null, true );
	wp_enqueue_script('theme_js');

    // wp_register_script( 'unminified_js', get_template_directory_uri() . '/src/js/unminified/slider.js', null, null, true );
	// wp_enqueue_script('unminified_js');


    wp_localize_script( 'theme_js', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));   
}

add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );

?>