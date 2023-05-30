<?php

add_action( 'wp_ajax_nopriv_auto_suggestion', 'auto_suggest' );
add_action( 'wp_ajax_auto_suggestion', 'auto_suggest' );


function array_search_partial($arr, $keyword) {

    $searched_arr = [];
    $keyword = mb_strtolower($keyword);

    $converted_keyword = str_replace(" ","-",$keyword);
    
    foreach($arr as $index => $string) {
        
        // $splitted_search = explode(" ",$string->post_title);
        
        $converted_title = str_replace(" ","-",$string->post_title);
        $converted_title = mb_strtolower($converted_title);

        // print_r($converted_keyword);
        // print_r($converted_title);

        // if(sizeof($splitted_search) > 1){
			
        //     $i=0;
        //     foreach($splitted_search as $split){

        //         if ( (strpos(mb_strtolower($split), $keyword) === 0) || (strpos(mb_strtolower($split), $keyword) === 0) )
        //             $searched_arr[$string->post_title] = $index;
               
        //         $i++;
        //     }
        // }
        
        if ( (strpos(mb_strtolower($converted_title), $converted_keyword) === 0) || (strpos(mb_strtolower($converted_title), $converted_keyword) !== FALSE ) )
            $searched_arr[$string->post_title] = $index;
    
    }

    // if(sizeof($splitted_keyword) > 1){
    //     if(empty($searched_arr)){

            

    //     }
    // }


    return $searched_arr;
}


function array_search_partial_category($arr, $keyword) {

    $searched_arr = [];
    $keyword = mb_strtolower($keyword);

    foreach($arr as $index => $string) {
        if ( (strpos(mb_strtolower($string->name), $keyword) === 0) || (strpos(mb_strtolower($string->slug), $keyword) === 0) )
            $searched_arr[$string->name] = $index;
    }
    
    return $searched_arr;
}



function auto_suggest() {

    $keyword = $_POST['keyword'];
    
    if(strlen($keyword) > 1){
        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'post',
        );
        $the_query = new WP_Query( $args );
        $all_posts = $the_query->posts;
    
        $searched_arr = array_search_partial($all_posts, $keyword);
    
    
        $all_cats = get_terms([
            'taxonomy' => 'post_location',
            'hide_empty' => true,
        ]);
    
        $searched_cats = array_search_partial_category($all_cats, $keyword);
    
        foreach($searched_cats as $arr){
        ?>
            <a href="<?php echo home_url('/sok/?nokkelord='.$all_cats[$arr]->name.'') ?>" data-item="Item" class="dropdown_item">
                <img src="<?php echo get_template_directory_uri().'/src/icons/location_icon.svg' ?>" alt="Pin icon">
                <?php echo $all_cats[$arr]->name ?>
            </a>
        <?php
        }    
    
        foreach($searched_arr as $arr){
        ?>
            <a href="<?php echo get_the_permalink($all_posts[$arr]->ID) ?>" data-item="Item" class="dropdown_item">
                <img src="<?php echo get_template_directory_uri().'/src/icons/home.svg' ?>" alt="Company icon">
                <?php echo $all_posts[$arr]->post_title ?>
            </a>
        <?php
        }
    }

    exit;
}






add_action( 'wp_ajax_nopriv_load_paid_companies', 'paid_companies' );
add_action( 'wp_ajax_load_paid_companies', 'paid_companies' );


function paid_companies(){

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
    );
    
    
    if(!empty($_POST['keyword'])){
        $args['orderby'] = 'title';
        $args['order'] = 'ASC';
    }
    
    $the_query = new WP_Query( $args );
    $all_posts = $the_query->posts;
    
    $all_posts = fix_names($all_posts, "'");
    
    $all_cats = get_categories([
        'hide_empty' => true,
    ]);
    
    $all_locations = get_terms([
        'taxonomy' => 'post_location',
        'hide_empty' => true,
    ]);
    
    $all_keywords = get_tags(array(
        'hide_empty' => true
    ));
    
    
    $keyword = $_POST['keyword'];
    $keyword = str_replace("'", "", $keyword); 
    $keyword = str_replace("\\", "", $keyword);
    
    
    
    $name_search = search_by_name($all_posts, $keyword);
    $not_exact_name_search = search_by_name_not_exact($all_posts, $keyword);
    $category_search = search_by_category($all_cats, $keyword);
    $location_search = search_by_location($all_locations, $keyword);
    $keyword_search = search_by_keywords($all_keywords, $keyword);
    
    
    if(!empty($location_search)){
    
        $prepared_location_arr = [];
        foreach($location_search as $item){
            array_push($prepared_location_arr, $all_locations[$item]->term_id);
        }
        
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'post_location', // Replace with your custom taxonomy
                    'field' => 'term_id',
                    'terms' => $prepared_location_arr,
                )
            )
        );
    
    
        if(!empty($_POST['keyword'])){
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
        }
    
    }
    
    else if(!empty($category_search)){
    
        $prepared_category_arr = [];
        foreach($category_search as $item){
            array_push($prepared_category_arr, $all_cats[$item]->term_id);
        }
    
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1,
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'category', // Replace with your custom taxonomy
                    'field' => 'term_id',
                    'terms' => $prepared_category_arr,
                )
            )
        );
    
    
        if(!empty($_POST['keyword'])){
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
        }
    }
    
    
    else if(!empty($not_exact_name_search)){
    
        $name_search = search_by_name_not_exact($all_posts, $keyword);
        $prepared_name_arr = [];
        foreach($name_search as $item){
            array_push($prepared_name_arr, $all_posts[$item]->ID);
        }
    
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1,
            'post__in' => $prepared_name_arr,
        );
    
    
        if(!empty($_POST['keyword'])){
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
        }
    }
    
    else if(!empty($name_search)){
    
        $prepared_name_arr = [];
        foreach($name_search as $item){
            array_push($prepared_name_arr, $all_posts[$item]->ID);
        }
    
        $prepared_location_arr = [];
        foreach($location_search as $item){
            array_push($prepared_location_arr, $all_locations[$item]->term_id);
        }
    
    
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1,
            'post__in' => $prepared_name_arr,
        );
    
    
        if(!empty($_POST['keyword'])){
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
        }
    
        $loop = new WP_Query( $args );
        $name_search_count = sizeof($loop->posts);
    
    
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1,
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'post_location', // Replace with your custom taxonomy
                    'field' => 'term_id',
                    'terms' => $prepared_location_arr,
                )
            )
        );
    
        if(!empty($_POST['keyword'])){
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
        }
    
        $loop = new WP_Query( $args );
        $location_search_count = sizeof($loop->posts);
    
    
        if($location_search_count > $name_search_count){
    
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => -1,
                'tax_query' => array(
                    'relation' => 'OR',
                    array(
                        'taxonomy' => 'post_location', // Replace with your custom taxonomy
                        'field' => 'term_id',
                        'terms' => $prepared_location_arr,
                    )
                )
            );
    
            if(!empty($_POST['keyword'])){
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
            }
    
        }
        else{
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => -1,
                'post__in' => $prepared_name_arr,
            );
    
    
            if(!empty($_POST['keyword'])){
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
            }
        }
    
    }
    
    
    else if(!empty($keyword_search)){
    
        $prepared_keyword_arr = [];
        foreach($keyword_search as $item){
            array_push($prepared_keyword_arr, $all_keywords[$item]->term_id);
        }
    
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1,
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'post_tag', // Replace with your custom taxonomy
                    'field' => 'term_id',
                    'terms' => $prepared_keyword_arr,
                )
            )
        );
    
        if(!empty($_POST['keyword'])){
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
        }
    }
    
    else{
        $args = [];
    }
    
    
    
    $args_paid_top = $args;
    $args_paid_medium = $args;
    $args_paid_low = $args;
    
    $args['posts_per_page'] = 10;
    $args['paged'] = $_POST['page'];
    
    if(empty($_POST['keyword'])){
        $args_paid_top['orderby'] = 'rand';
        $args_paid_medium['orderby'] = 'rand'; 
        $args_paid_low['orderby'] = 'rand';
    }
    
    $args_paid_top ['meta_query'] = array(
        array(
            'key'     => 'company_package',
            'value'   => 1,
            'compare' => '='
        )
    );
    
    $args_paid_medium ['meta_query'] = array(
        array(
            'key'     => 'company_package',
            'value'   => 2,
            'compare' => '='
        )
    );
    
    $args_paid_low ['meta_query'] = array(
        array(
            'key'     => 'company_package',
            'value'   => 3,
            'compare' => '='
        )
    );
    
    $paid_companies_1 = new WP_Query( $args_paid_top );
    $paid_companies_2 = new WP_Query( $args_paid_medium );
    $paid_companies_3 = new WP_Query( $args_paid_low );
    
    
    
    $args ['meta_query'] = array(
        array(
            'key'     => 'company_package',
            'value'   => array(1, 2, 3),
            'compare' => 'NOT IN'
        )
    );
    $search_results = new WP_Query( $args );
    
    
    
    $args ['posts_per_page'] = -1;
    $total_free_companies = new WP_Query( $args );
    $total_free_companies = sizeof($total_free_companies->posts);
    
    $args ['meta_query'] = array(
        array(
            'key'     => 'company_package',
            'value'   => array(1, 2, 3),
            'compare' => 'IN'
        )
    );
    $total_paid_companies = new WP_Query( $args );
    $total_paid_companies = sizeof($total_paid_companies->posts);
    
    
    
    if( empty($search_results->posts) ){
        $splitted_search = explode(" ",$keyword);
    
        if(sizeof($splitted_search) > 1){
            $name_search = search_by_name_not_exact($all_posts, $splitted_search[0]);
            $location_search = search_by_location($all_locations, $splitted_search[1]);
            $category_search = search_by_category($all_cats, $splitted_search[1]);
            $keyword_search = search_by_keywords($all_keywords, $splitted_search[1]);
        
        
            $prepared_name_arr = [];
            foreach($name_search as $item){
                array_push($prepared_name_arr, $all_posts[$item]->ID);
            }
        
            $prepared_location_arr = [];
            foreach($location_search as $item){
                array_push($prepared_location_arr, $all_locations[$item]->term_id);
            }
        
            $prepared_category_arr = [];
            foreach($category_search as $item){
                array_push($prepared_category_arr, $all_cats[$item]->term_id);
            }
        
            $prepared_keyword_arr = [];
            foreach($keyword_search as $item){
                array_push($prepared_keyword_arr, $all_keywords[$item]->term_id);
            }
        
        
            if(!empty($name_search)){
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => -1,
                    'post__in' => $prepared_name_arr	
                );
        
                if(!empty($_POST['keyword'])){
                    $args['orderby'] = 'title';
                    $args['order'] = 'ASC';
                }
            }
            if(!empty($location_search)){
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'post_location', // Replace with your custom taxonomy
                        'field' => 'term_id',
                        'terms' => $prepared_location_arr,
                    )
                );
        
                if(!empty($_POST['keyword'])){
                    $args['orderby'] = 'title';
                    $args['order'] = 'ASC';
                }
            }
            if(!empty($category_search)){
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'term_id',
                        'terms' => $prepared_category_arr,
                    ),
                );
        
                if(!empty($_POST['keyword'])){
                    $args['orderby'] = 'title';
                    $args['order'] = 'ASC';
                }
            }
            if(!empty($category_search)){
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'post_tag',
                        'field' => 'term_id',
                        'terms' => $prepared_keyword_arr,
                    ),
                );
        
                if(!empty($_POST['keyword'])){
                    $args['orderby'] = 'title';
                    $args['order'] = 'ASC';
                }
            }
        
        } 
    
        $search_results = new WP_Query( $args );
    
    }

        while ( $paid_companies_2->have_posts() ):
            $paid_companies_2->the_post();
        ?>

        <div class="company-card paid-company">
        <a class="abs-link" href="<?php echo get_the_permalink(get_the_ID()) ?>"></a>
        <div class="left_col">

        <?php if( (carbon_get_post_meta(get_the_ID(), 'company_package') != 'Select package') && (carbon_get_post_meta(get_the_ID(), 'company_package') != 0) ): ?>

                <?php if(!empty(get_the_post_thumbnail(get_the_ID(), 'full'))): ?>
                    <div class="logo">
                        <?php echo get_the_post_thumbnail(get_the_ID(), 'full') ?>
                    </div>
                <?php endif ?>

            <?php endif ?>

        </div>
        <div class="right_col">
            <div class="info">
                <div class="title"><?php the_title() ?></div>

                <?php
                    $company_location = carbon_get_post_meta(get_the_ID(), 'company_address_1');
                    $company_location = $company_location.', ';

                    if(!empty(carbon_get_post_meta(get_the_ID(), 'company_address_2'))){
                        $company_location = $company_location.', '.carbon_get_post_meta(get_the_ID(), 'company_address_2');
                    }
                    
                    if(!empty(carbon_get_post_meta(get_the_ID(), 'company_city'))){
                        $company_location = $company_location.' '.carbon_get_post_meta(get_the_ID(), 'company_city');
                    }
                ?>

                <div class="location"><?php echo $company_location ?></div>

                <?php if( (carbon_get_post_meta(get_the_ID(), 'company_package') != 'Select package') && (carbon_get_post_meta(get_the_ID(), 'company_package') != 0) ): ?>
                    <div class="desc">
                        <?php echo excerpt(carbon_get_post_meta(get_the_ID(), 'company_description')) ?>
                    </div>
                <?php endif ?>


                <?php 
                    $current_cat = get_the_terms(get_the_ID(), 'category')[0]; 
                    if(!empty($current_cat)):
                ?>
                    <div class="cat-tag"><?php echo $current_cat->name ?></div>
                <?php
                    endif;
                ?>
            

            </div>

            <?php if(!empty(carbon_get_post_meta(get_the_ID(), 'company_phone'))): ?>

                <a href="tel:<?php echo carbon_get_post_meta(get_the_ID(), 'company_phone') ?>" class="phone">
                    <img src="<?php echo get_template_directory_uri().'/src/icons/phone.svg' ?>" alt="Phone icon">
                    <div class="hide-on-mobile">
                        <?php echo carbon_get_post_meta(get_the_ID(), 'company_phone') ?>
                    </div>
                    <div class="hide-on-desktop">
                        Ring
                    </div>
                </a>

            <?php endif ?>

        </div>
        </div>

        <?php
            endwhile;
        ?>


        <?php
        if ( $paid_companies_3->have_posts()  ):
            while ( $paid_companies_3->have_posts() ):
                $paid_companies_3->the_post();
        ?>

        <div class="company-card paid-company">
        <a class="abs-link" href="<?php echo get_the_permalink(get_the_ID()) ?>"></a>
        <div class="left_col">

        <?php if( (carbon_get_post_meta(get_the_ID(), 'company_package') != 'Select package') && (carbon_get_post_meta(get_the_ID(), 'company_package') != 0) ): ?>

                <?php if(!empty(get_the_post_thumbnail(get_the_ID(), 'full'))): ?>
                    <div class="logo">
                        <?php echo get_the_post_thumbnail(get_the_ID(), 'full') ?>
                    </div>
                <?php endif ?>

            <?php endif ?>

        </div>
        <div class="right_col">
            <div class="info">
                <div class="title"><?php the_title() ?></div>

                <?php
                    $company_location = carbon_get_post_meta(get_the_ID(), 'company_address_1');
                    $company_location = $company_location.', ';

                    if(!empty(carbon_get_post_meta(get_the_ID(), 'company_address_2'))){
                        $company_location = $company_location.', '.carbon_get_post_meta(get_the_ID(), 'company_address_2');
                    }
                    
                    if(!empty(carbon_get_post_meta(get_the_ID(), 'company_city'))){
                        $company_location = $company_location.' '.carbon_get_post_meta(get_the_ID(), 'company_city');
                    }
                ?>

                <div class="location"><?php echo $company_location ?></div>

                <?php if( (carbon_get_post_meta(get_the_ID(), 'company_package') != 'Select package') && (carbon_get_post_meta(get_the_ID(), 'company_package') != 0) ): ?>
                    <div class="desc">
                        <?php echo excerpt(carbon_get_post_meta(get_the_ID(), 'company_description')) ?>
                    </div>
                <?php endif ?>


                <?php 
                    $current_cat = get_the_terms(get_the_ID(), 'category')[0]; 
                    if(!empty($current_cat)):
                ?>
                    <div class="cat-tag"><?php echo $current_cat->name ?></div>
                <?php
                    endif;
                ?>
            

            </div>

            <?php if(!empty(carbon_get_post_meta(get_the_ID(), 'company_phone'))): ?>

                <a href="tel:<?php echo carbon_get_post_meta(get_the_ID(), 'company_phone') ?>" class="phone">
                    <img src="<?php echo get_template_directory_uri().'/src/icons/phone.svg' ?>" alt="Phone icon">
                    <div class="hide-on-mobile">
                        <?php echo carbon_get_post_meta(get_the_ID(), 'company_phone') ?>
                    </div>
                    <div class="hide-on-desktop">
                        Ring
                    </div>
                </a>

            <?php endif ?>

        </div>
        </div>

    <?php
        endwhile;
        endif;
    ?>

<?php
    exit;
}





add_action( 'wp_ajax_nopriv_load_free_companies', 'free_companies' );
add_action( 'wp_ajax_load_free_companies', 'free_companies' );


function free_companies(){

    // Write code here

    exit;
}


?>