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


function check_category($word){

	global $wpdb;	
	$cat_Args="SELECT * FROM $wpdb->terms WHERE name LIKE '".$word."'";
	$cats = $wpdb->get_results($cat_Args, OBJECT);

	return $cats;
}

function check_category_full($word){

	global $wpdb;	
	$cat_Args="SELECT * FROM $wpdb->terms WHERE name LIKE '".$word."'";
	$cats = $wpdb->get_results($cat_Args, OBJECT);

	return $cats;
}

function search_exact_name($word){

	global $wpdb;	
	$cat_Args="SELECT * FROM wp_posts WHERE post_title LIKE '".$word."%'";
	$cats = $wpdb->get_results($cat_Args, OBJECT);

	return $cats;
}

function search_exact_term($word){

	global $wpdb;	
	$cat_Args="SELECT * FROM $wpdb->terms WHERE name LIKE '".$word."'";
	$cats = $wpdb->get_results($cat_Args, OBJECT);

	return $cats;
}


$priority_order = [];
$found_in_first_place = false;
$both_cat_and_term = 0;

if ( isset( $_GET['nokkelord'] ) && $_GET['nokkelord'] != '') {
	$search_query = sanitize_text_field( $_GET['nokkelord'] );
	$search_query_without_filter = $_GET['nokkelord'];


	$exact_name = search_exact_name($search_query_without_filter);
	$exact_term = search_exact_term($search_query_without_filter);


	if( !empty($exact_term[0]->term_id) ){
		$args = array(
			'post_type' => 'post',
			'orderby' => 'relevance',
			'posts_per_page' => -1,
			
			'tax_query' => array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'category',
					'field' => 'term_id',
					'terms' => array($exact_term[0]->term_id),
				),
				array(
					'taxonomy' => 'post_location', // Replace with your custom taxonomy
					'field' => 'term_id',
					'terms' => array($exact_term[0]->term_id),
				),
				array(
					'taxonomy' => 'post_tag',
					'field' => 'term_id',
					'terms' => array($exact_term[0]->term_id),
				),
			),
		);

		$search_results = new WP_Query( $args );
	}

	else if( !empty($exact_name) ){

		$post_ids = [];

		foreach($exact_name as $name):
			array_push($post_ids, $name->ID);
		endforeach;

		$args = array(
			'post_type' => 'post',
			'posts_per_page' => -1,
			'post__in' => $post_ids
		);
		$search_results = new WP_Query( $args );
	}


	else{

		$term_ids=array(); 

		if ($search_query == trim($search_query) && strpos($search_query, ' ') !== false) {
			$words = array_filter(explode(" ",$search_query));

			foreach($words as $word){
				$cats = check_category($word);

				if(!empty(check_category($words[0]))){
					$found_in_first_place = 1;
				}
				else{
					$found_in_first_place = 0;
				}

				if(!empty($cats)){
					$both_cat_and_term++;

					foreach($cats as $cat):
						array_push($term_ids, $cat->term_id);    
					endforeach;
				}
			}
		}
		else{

			$words = array_filter(explode(" ",$search_query));

			foreach($words as $word){
				$cats = check_category_full($word);

				if(!empty(check_category_full($words[0]))){
					$found_in_first_place = 1;
				}
				else{
					$found_in_first_place = 0;
				}

				if(!empty($cats)){
					foreach($cats as $cat):
						array_push($term_ids, $cat->term_id);    
					endforeach;
				}
			}
			
		}
		

		// Set up query args


		if(!$found_in_first_place){

			$args = array(
				'post_type' => 'post',
				'posts_per_page' => -1,
				'orderby' => 'relevance',
				's' => $search_query,
				
				'tax_query' => array(
					'relation' => 'OR',
					array(
						'taxonomy' => 'category',
						'field' => 'term_id',
						'terms' => $term_ids,
					),
					array(
						'taxonomy' => 'post_tag',
						'field' => 'term_id',
						'terms' => $term_ids,
					),
				),
			);
		}
		else{

			if($both_cat_and_term == 2){
				$args = array(
					'post_type' => 'post',
					'orderby' => 'relevance',
					'posts_per_page' => -1,
					
					'tax_query' => array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'category',
							'field' => 'term_id',
							'terms' => $term_ids,
						),
						array(
							'taxonomy' => 'post_location', // Replace with your custom taxonomy
							'field' => 'term_id',
							'terms' => $term_ids,
						),
						// array(
						// 	'taxonomy' => 'post_tag',
						// 	'field' => 'term_id',
						// 	'terms' => $term_ids,
						// ),

					),
				);
			}
			else{
				$args = array(
					'post_type' => 'post',
					'orderby' => 'relevance',
					'posts_per_page' => -1,
					
					'tax_query' => array(
						'relation' => 'OR',
						array(
							'taxonomy' => 'category',
							'field' => 'term_id',
							'terms' => $term_ids,
						),
						array(
							'taxonomy' => 'post_location', // Replace with your custom taxonomy
							'field' => 'term_id',
							'terms' => $term_ids,
						),
						array(
							'taxonomy' => 'post_tag',
							'field' => 'term_id',
							'terms' => $term_ids,
						),
					),
				);
			}

			
		}

		
		// Run the query
		$search_results = new WP_Query( $args );

		if ( !$search_results->have_posts() ){

			$words = array_filter(explode(" ",$search_query));

			$args = array(
				'post_type' => 'post',
				'orderby' => 'relevance',
				'posts_per_page' => -1,
				's' => $words[0],	
			);
			
			// Run the query
			$search_results = new WP_Query( $args );
		}
	}

}
else{
	$args = array(
		'post_type' => 'post',
		'posts_per_page' => -1,
	);
	$search_results = new WP_Query( $args );
}


?>

<div class="wrapper page-wrapper search-page">

	<div id="primary">

		<main class="site-main" id="main" role="main">

			<div class="container generic-page-container">
			
				<div class="breadcrumb">

					<div class="item">Hjem</div>
					<div class="separator">
						<img src="<?php echo get_template_directory_uri().'/src/icons/breadcrumb-chevron.svg' ?>" alt="Chevron right">
					</div>
					<div class="item">Søkeresultater</div>
					<div class="separator">
						<img src="<?php echo get_template_directory_uri().'/src/icons/breadcrumb-chevron.svg' ?>" alt="Chevron right">
					</div>		
					<div class="current"><?php echo $_GET['nokkelord'] ?></div>
					
				</div>


				<div class="flex_container">

					<div class="left_col">

						<div class="sort_switcher">
							<div class="title">Sortere:</div>
							<div class="buttons">
								<div data-target="relevent" class="button relevent active">Relevans</div>
								<div data-target="distance" class="button alphabetic">A-Å</div>
							</div>
						</div>


						<div id="company_holder">


							<?php
								
								// Output the results
								if ( $search_results->have_posts() && strlen($_GET['nokkelord']) > 2 ) {
									while ( $search_results->have_posts() ) {
										$search_results->the_post();
									?>

										<?php if(carbon_get_post_meta(get_the_ID(), 'company_package') != 'Select package'): ?>

											<div class="company-card">
												<a class="abs-link" href="<?php echo get_the_permalink(get_the_ID()) ?>"></a>
												<div class="left_col">

													<?php if(carbon_get_post_meta(get_the_ID(), 'company_package') != 'Select package'): ?>

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

															if(!empty(carbon_get_post_meta(get_the_ID(), 'company_address_2'))){
																$company_location = $company_location.', '.carbon_get_post_meta(get_the_ID(), 'company_address_2');
															}
															if(!empty(carbon_get_post_meta(get_the_ID(), 'company_city'))){
																$company_location = $company_location.' '.carbon_get_post_meta(get_the_ID(), 'company_city');
															}
														?>

														<div class="location"><?php echo $company_location ?></div>

														<?php if(carbon_get_post_meta(get_the_ID(), 'company_package') != 'Select package'): ?>
															<div class="desc">
																<?php echo excerpt(carbon_get_post_meta(get_the_ID(), 'company_description')) ?>
															</div>
														<?php endif ?>

													</div>

													<?php if(!empty(carbon_get_post_meta(get_the_ID(), 'company_phone'))): ?>

														<a href="tel:<?php echo carbon_get_post_meta(get_the_ID(), 'company_phone') ?>" class="phone">
															<img src="<?php echo get_template_directory_uri().'/src/icons/phone.svg' ?>" alt="Phone icon">
															<?php echo carbon_get_post_meta(get_the_ID(), 'company_phone') ?>
														</a>

													<?php endif ?>

												</div>
											</div>

										<?php endif ?>
									<?php
									}
								} else {
									echo '<img src="'.IMG.'/no_result.gif" alt="No result image">';
								}

								// Reset the post data
								wp_reset_postdata();
							?>


							<?php
								
								// Output the results
								if ( $search_results->have_posts() && strlen($_GET['nokkelord']) > 2 ) {
									while ( $search_results->have_posts() ) {
										$search_results->the_post();
									?>

										<?php if(carbon_get_post_meta(get_the_ID(), 'company_package') == 'Select package'): ?>

											<div class="company-card">
												<a class="abs-link" href="<?php echo get_the_permalink(get_the_ID()) ?>"></a>
												<div class="left_col">

													<?php if(carbon_get_post_meta(get_the_ID(), 'company_package') != 'Select package'): ?>

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

															if(!empty(carbon_get_post_meta(get_the_ID(), 'company_address_2'))){
																$company_location = $company_location.', '.carbon_get_post_meta(get_the_ID(), 'company_address_2');
															}
															if(!empty(carbon_get_post_meta(get_the_ID(), 'company_city'))){
																$company_location = $company_location.', '.carbon_get_post_meta(get_the_ID(), 'company_city');
															}
														?>

														<div class="location"><?php echo $company_location ?></div>

														<?php if(carbon_get_post_meta(get_the_ID(), 'company_package') != 'Select package'): ?>
															<div class="desc">
																<?php echo excerpt(carbon_get_post_meta(get_the_ID(), 'company_description')) ?>
															</div>
														<?php endif ?>

													</div>

													<a href="tel:<?php echo carbon_get_post_meta(get_the_ID(), 'company_phone') ?>" class="phone">
														<img src="<?php echo get_template_directory_uri().'/src/icons/phone.svg' ?>" alt="Phone icon">
														<?php echo carbon_get_post_meta(get_the_ID(), 'company_phone') ?>
													</a>

												</div>
											</div>
											
										<?php endif ?>
									<?php
									}
								}

								// Reset the post data
								wp_reset_postdata();
							?>
							
						</div>

					</div>

					<div class="right_col">
						<div class="ad_holder">
							<img src="<?php echo IMG.'/ad-image.jpg' ?>" alt="Ad">
						</div>
					</div>

				</div>

			</div>
				
        </main><!-- #main -->

	</div><!-- #primary -->

</div><!-- #page-wrapper -->

<?php get_footer();