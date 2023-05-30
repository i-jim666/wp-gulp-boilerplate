<?php

function search_by_name($arr, $keyword) {

    $searched_arr = [];

    $keyword = mb_strtolower($keyword);

    foreach($arr as $index => $string) {
        if (strpos(mb_strtolower($string->post_title), $keyword) === 0)
			array_push($searched_arr, $index);
    }

    return $searched_arr;
}


function search_by_name_not_exact($arr, $keyword) {

    $searched_arr = [];

    $keyword = mb_strtolower($keyword);

    foreach($arr as $index => $string) {
        if (strpos(mb_strtolower($string->post_title), $keyword) !== FALSE)
			array_push($searched_arr, $index);
    }

    return $searched_arr;
}

function search_by_category($arr, $keyword) {

    $searched_arr = [];
    $keyword = mb_strtolower($keyword);

    foreach($arr as $index => $string) {
        if ( (mb_strtolower($string->name) == $keyword) || (mb_strtolower($string->slug) == $keyword) )
            $searched_arr[$string->name] = $index;
    }

    return $searched_arr;
}


function search_by_location($arr, $keyword) {

    $searched_arr = [];
    $keyword = mb_strtolower($keyword);


    foreach($arr as $index => $string) {
        if ( (mb_strtolower($string->name) == $keyword) || (mb_strtolower($string->slug) == $keyword) ){
            $searched_arr[$string->name] = $index;
		}
    }
    
    return $searched_arr;
}


function search_by_keywords($arr, $keyword) {

    $searched_arr = [];
    $keyword = mb_strtolower($keyword);

    foreach($arr as $index => $string) {
        if ( (mb_strtolower($string->name) == $keyword) || (mb_strtolower($string->slug) == $keyword) )
            $searched_arr[$string->name] = $index;
    }
    
    return $searched_arr;
}


function fix_names($arr, $keyword) {
    $keyword = mb_strtolower($keyword);

    foreach($arr as $index => $string) {
        if (strpos(mb_strtolower($string->post_title), $keyword) !== FALSE)
			$string->post_title = str_replace($keyword, '',$string->post_title);
    }

    return $arr;
}


?>