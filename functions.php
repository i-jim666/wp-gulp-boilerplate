<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$includes = array(
	'/theme-setup.php',     // Initialize theme default settings.
    '/theme-options.php',   // Theme options
	'/custom-post-types.php', // Register custom post types
	'/custom-taxonomy.php', // Register custom taxonomies
	'/enqueue.php', // Enqueue elements
	'/custom-gutenberg-blocks.php', // Register gutenberg blocks
	'/custom-meta-fields.php', // Register meta fields
	'/ajax-functions.php', // Ajax functions
	'/search_functions.php'
);

foreach ( $includes as $file ) {
	require_once get_template_directory() . '/inc' . $file;
}

add_filter( 'wpcf7_mail_components', 'remove_blank_lines' );

function remove_blank_lines( $mail ) {
	if ( is_array( $mail ) && ! empty( $mail['body'] ) )
		$mail['body'] = preg_replace( '|\n\s*\n|', "\n\n", $mail['body'] );
	return $mail;
}

add_filter('wpcf7_autop_or_not', '__return_false');


function get_post_read_time($post = null) {
    $post = get_post($post);
    $words_per_minute = 200; // average reading speed
    $words = str_word_count(strip_tags($post->post_content));
    $minutes = ceil($words / $words_per_minute);
    return $minutes;
}

function thousandsCurrencyFormat($num) {

	if($num>1000) {
  
		  $x = round($num);
		  $x_number_format = number_format($x);
		  $x_array = explode(',', $x_number_format);
		  $x_parts = array('k', 'm', 'b', 't');
		  $x_count_parts = count($x_array) - 1;
		  $x_display = $x;
		  $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
		  $x_display .= $x_parts[$x_count_parts - 1];
  
		  return $x_display;
  
	}
  
	return $num;
}


function ce4_excerpt_length($length) {
    return 40;
}
add_filter('excerpt_length', 'ce4_excerpt_length');

function ce4_excerpt_more($more) {
    global $post;
    return ' ...';
}
add_filter('excerpt_more', 'ce4_excerpt_more');



function excerpt($title) {
	$new = substr($title, 0, 110);

	if (strlen($title) > 110) {
		return $new.'...';
	} else {
		return $title;
	}
}
?>