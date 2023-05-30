<?php
/**
 * Template Name: Search Page
 *
 * Template for displaying a search page.
 *
 * 
 */

defined( 'ABSPATH' ) || exit;

get_header('generic');

$args = array(
	'post_type' => 'post',
	'posts_per_page' => -1,
);


if(!empty($_GET['sort'])){
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


$keyword = $_GET['nokkelord'];
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


	if(!empty($_GET['sort'])){
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


	if(!empty($_GET['sort'])){
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


	if(!empty($_GET['sort'])){
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


	if(!empty($_GET['sort'])){
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

	if(!empty($_GET['sort'])){
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

		if(!empty($_GET['sort'])){
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


		if(!empty($_GET['sort'])){
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

	if(!empty($_GET['sort'])){
		$args['orderby'] = 'title';
		$args['order'] = 'ASC';
	}
}

else{
	$args = [];
}


$search_results = new WP_Query( $args );


if(!empty($search_results->posts)){
	
	$args_paid_top = $args;
	$args_paid_medium = $args;
	$args_paid_low = $args;

	// $args['posts_per_page'] = 10;
	// $args['paged'] = 1;

	if(empty($_GET['sort'])){
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

}


if( empty($search_results->posts) ){
	$splitted_search = explode(" ",$keyword);

	if(sizeof($splitted_search) > 1){
		
		$name_search = search_by_name($all_posts, $splitted_search[0]);
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
	
			if(!empty($_GET['sort'])){
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
	
			if(!empty($_GET['sort'])){
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
	
			if(!empty($_GET['sort'])){
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
	
			if(!empty($_GET['sort'])){
				$args['orderby'] = 'title';
				$args['order'] = 'ASC';
			}
		}
	
	} 

	$search_results = new WP_Query( $args );




	if(!empty($search_results->posts)){
	
		$args_paid_top = $args;
		$args_paid_medium = $args;
		$args_paid_low = $args;
	
		// $args['posts_per_page'] = 10;
		// $args['paged'] = 1;
	
		if(empty($_GET['sort'])){
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
	
	}



}



?>

<div class="wrapper page-wrapper search-page">

	<div id="primary">

		<main class="site-main" id="main" role="main">

			<div class="container generic-page-container">
			
				<div class="breadcrumb">

					<a href="<?php echo home_url('/') ?>" class="item">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5 12H3L12 3L21 12H19" stroke="#777777" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 12V19C5 19.5304 5.21071 20.0391 5.58579 20.4142C5.96086 20.7893 6.46957 21 7 21H17C17.5304 21 18.0391 20.7893 18.4142 20.4142C18.7893 20.0391 19 19.5304 19 19V12" stroke="#777777" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M14 12H10V16H14V12Z" stroke="#777777" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</a>
					<div class="separator">
						<img src="<?php echo get_template_directory_uri().'/src/icons/breadcrumb-chevron.svg' ?>" alt="Chevron right">
					</div>
					<div class="item">Søkeresultater</div>
					<div class="separator">
						<img src="<?php echo get_template_directory_uri().'/src/icons/breadcrumb-chevron.svg' ?>" alt="Chevron right">
					</div>		
					<div class="current"><?php echo $keyword ?></div>
					
				</div>


				<div class="flex_container">

					<div class="left_col">

							<div class="sort_switcher">
								<div class="title">Sortere:</div>
								<div class="buttons">
									<a href="<?php echo home_url('/sok/?nokkelord='.$_GET['nokkelord'].'') ?>" data-target="relevent" class="button relevent <?php echo (empty($_GET['sort']))? 'active' : '' ?>">Relevans</a>
									<a href="<?php echo home_url('/sok/?nokkelord='.$_GET['nokkelord'].'&sort=aa') ?>" data-target="distance" class="button alphabetic <?php echo (!empty($_GET['sort']))? 'active' : '' ?>">A-Å</a>
								</div>
							</div>

							
							<div id="company_holder">
								
								<?php if( !empty($paid_companies_1->posts) || !empty($paid_companies_2->posts) || !empty($paid_companies_3->posts) || !empty($search_results->posts) ): ?>

									<div id="paid_companies">

										<?php

											if ( !empty($paid_companies_1->posts)  ):
												while ( $paid_companies_1->have_posts() ):
													$paid_companies_1->the_post();
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


														// Full location for map direction

														$company_full_location = carbon_get_post_meta(get_the_ID(), 'company_address_1');

														if(!empty(carbon_get_post_meta(get_the_ID(), 'company_address_2'))){
															$company_full_location = $company_full_location.', '.carbon_get_post_meta(get_the_ID(), 'company_address_2');
														}
														if(!empty(carbon_get_post_meta(get_the_ID(), 'company_postcode'))){
															$company_full_location = $company_full_location.', '.carbon_get_post_meta(get_the_ID(), 'company_postcode');
														}
														if(!empty(carbon_get_post_meta(get_the_ID(), 'company_city'))){
															$company_full_location = $company_full_location.' '.carbon_get_post_meta(get_the_ID(), 'company_city');
														}

													?>

													<div class="location"><?php echo $company_location ?></div>
													<input type="hidden" name="full_address" class="full_address" value="<?php echo $company_full_location ?>">


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


												<div class="flex_container">

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

													
													<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $company_full_location ?>" id="map_direction_btn" class="phone hide-on-desktop">
														<img src="<?php echo get_template_directory_uri().'/src/icons/compass.svg' ?>" alt="Route icon">
														Veibeskrivelse
													</a>


												</div>


												<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $company_full_location ?>" class="abs-compass hide-on-mobile">
													<img src="<?php echo get_template_directory_uri().'/src/icons/compass.svg' ?>" alt="Route icon">
												</a>
												

											</div>
										</div>

										<?php
											endwhile;
										endif;
										?>



										<?php
											if ( !empty($paid_companies_2->posts)  ):
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




														// Full location for map direction

														$company_full_location = carbon_get_post_meta(get_the_ID(), 'company_address_1');

														if(!empty(carbon_get_post_meta(get_the_ID(), 'company_address_2'))){
															$company_full_location = $company_full_location.', '.carbon_get_post_meta(get_the_ID(), 'company_address_2');
														}
														if(!empty(carbon_get_post_meta(get_the_ID(), 'company_postcode'))){
															$company_full_location = $company_full_location.', '.carbon_get_post_meta(get_the_ID(), 'company_postcode');
														}
														if(!empty(carbon_get_post_meta(get_the_ID(), 'company_city'))){
															$company_full_location = $company_full_location.' '.carbon_get_post_meta(get_the_ID(), 'company_city');
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

												<div class="flex_container">

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


													<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $company_full_location ?>" id="map_direction_btn" class="phone hide-on-desktop">
														<img src="<?php echo get_template_directory_uri().'/src/icons/compass.svg' ?>" alt="Route icon">
														Veibeskrivelse
													</a>

												</div>

												<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $company_full_location ?>" class="abs-compass hide-on-mobile">
													<img src="<?php echo get_template_directory_uri().'/src/icons/compass.svg' ?>" alt="Route icon">
												</a>

											</div>
										</div>

										<?php
											endwhile;
										endif;
										?>


										<?php
											if ( !empty($paid_companies_3->posts)  ):
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




														// Full location for map direction

														$company_full_location = carbon_get_post_meta(get_the_ID(), 'company_address_1');

														if(!empty(carbon_get_post_meta(get_the_ID(), 'company_address_2'))){
															$company_full_location = $company_full_location.', '.carbon_get_post_meta(get_the_ID(), 'company_address_2');
														}
														if(!empty(carbon_get_post_meta(get_the_ID(), 'company_postcode'))){
															$company_full_location = $company_full_location.', '.carbon_get_post_meta(get_the_ID(), 'company_postcode');
														}
														if(!empty(carbon_get_post_meta(get_the_ID(), 'company_city'))){
															$company_full_location = $company_full_location.' '.carbon_get_post_meta(get_the_ID(), 'company_city');
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

												<div class="flex_container">

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

													
													<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $company_full_location ?>" id="map_direction_btn" class="phone hide-on-desktop">
														<img src="<?php echo get_template_directory_uri().'/src/icons/compass.svg' ?>" alt="Route icon">
														Veibeskrivelse
													</a>


												</div>

												<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $company_full_location ?>" class="abs-compass hide-on-mobile">
													<img src="<?php echo get_template_directory_uri().'/src/icons/compass.svg' ?>" alt="Route icon">
												</a>

											</div>
										</div>

										<?php
											endwhile;
										endif;
										?>


									</div>


									<div id="free_companies">

										<?php
											if ( $search_results->have_posts()  ):
												while ( $search_results->have_posts() ):
													$search_results->the_post();
										?>
									
										<div class="company-card">
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

									</div>

									<?php 
										$total_companies = $total_free_companies + $total_paid_companies;
										if($total_companies > 15):
									?>

									<div class="btn_container">
										<div id="load_more_btn" data-paid-count="<?php echo $total_paid_companies ?>" data-free-count="<?php echo $total_free_companies ?>">
											<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M0.833313 2.33331V7.33332M0.833313 7.33332H5.83331M0.833313 7.33332L4.69998 3.69998C5.5956 2.80391 6.70362 2.14932 7.92065 1.79729C9.13768 1.44527 10.4241 1.40727 11.6597 1.68686C12.8954 1.96645 14.0401 2.55451 14.9871 3.39616C15.934 4.23782 16.6523 5.30564 17.075 6.49998M19.1666 15.6666V10.6666M19.1666 10.6666H14.1666M19.1666 10.6666L15.3 14.3C14.4044 15.1961 13.2963 15.8506 12.0793 16.2027C10.8623 16.5547 9.5759 16.5927 8.34022 16.3131C7.10453 16.0335 5.95981 15.4455 5.01287 14.6038C4.06592 13.7621 3.34762 12.6943 2.92498 11.5" stroke="#444444" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
											Vis mer
										</div>
									</div>

								<?php endif ?>

							<?php else: ?> 

								<div class="no_results">
									<img src="<?php echo get_template_directory_uri().'/src/icons/no_results.svg' ?>" alt="No results icon">
									<h3>Beklager. Ingen resultater.</h3>
									<p>Ditt søk etter <strong><?php echo $keyword ?></strong> ga ingen resultater. Sjekk stavemåten eller prøv å søke annerledes.</p>
								</div>

							<?php endif ?>

						</div>

					</div>

					<div class="right_col">
						<div class="ad_holder">
							<!-- <img src="<php echo IMG.'/ad-image.jpg' ?>" alt="Ad"> -->
							<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9549148492346396"
							crossorigin="anonymous"></script>
						<!-- Sokresultat -->
						<ins class="adsbygoogle"
							style="display:block"
							data-ad-client="ca-pub-9549148492346396"
							data-ad-slot="2144148073"
							data-ad-format="auto"
							data-full-width-responsive="true"></ins>
						<script>
							(adsbygoogle = window.adsbygoogle || []).push({});
						</script>
						</div>
					</div>

				</div>

			</div>
				
        </main><!-- #main -->

	</div><!-- #primary -->

</div><!-- #page-wrapper -->

<?php get_footer();