<?php

defined( 'ABSPATH' ) || exit;

get_header('company');


$company_title = carbon_get_post_meta(get_the_ID(), 'company_title');
$company_package = carbon_get_post_meta(get_the_ID(), 'company_package');
$current_cat = get_the_terms(get_the_ID(), 'category')[0];
$current_location = get_the_terms(get_the_ID(), 'post_location')[0];
$company_phone = carbon_get_post_meta(get_the_ID(), 'company_phone');

$company_website = carbon_get_post_meta(get_the_ID(), 'company_website');
$company_email = carbon_get_post_meta(get_the_ID(), 'company_email');
$company_facebook = carbon_get_post_meta(get_the_ID(), 'company_facebook');
$company_instagram = carbon_get_post_meta(get_the_ID(), 'company_instagram');

$banner_image = carbon_get_post_meta(get_the_ID(), 'banner_image');

$company_location = carbon_get_post_meta(get_the_ID(), 'company_address_1');

if(!empty(carbon_get_post_meta(get_the_ID(), 'company_address_2'))){
	$company_location = $company_location.', '.carbon_get_post_meta(get_the_ID(), 'company_address_2');
}
if(!empty(carbon_get_post_meta(get_the_ID(), 'company_postcode'))){
	$company_location = $company_location.', '.carbon_get_post_meta(get_the_ID(), 'company_postcode');
}
if(!empty(carbon_get_post_meta(get_the_ID(), 'company_city'))){
	$company_location = $company_location.' '.carbon_get_post_meta(get_the_ID(), 'company_city');
}

$embed_map = carbon_get_post_meta(get_the_ID(), 'embed_map');
$company_gallery = carbon_get_post_meta(get_the_ID(), 'image_gallery');


$company_desc = carbon_get_post_meta(get_the_ID(), 'company_description');

if( ($company_package == 'Select package') || empty($company_desc) ){
	$company_desc = carbon_get_theme_option('default_text');

	$company_desc = str_replace("(Company name)", $company_title, $company_desc);
	$company_desc = str_replace("(City name)", $current_location->name, $company_desc);
	$company_desc = str_replace("(Category)", $current_cat->name, $company_desc);

}

?>

<div class="wrapper">

	<div class="single-page">

		<main class="site-main" role="main">

			<div class="container">

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
					<a href="<?php echo home_url('/sok/?nokkelord='.$current_cat->name.'') ?>" class="item"><?php echo $current_cat->name ?></a>
					<div class="separator">
						<img src="<?php echo get_template_directory_uri().'/src/icons/breadcrumb-chevron.svg' ?>" alt="Chevron right">
					</div>		
					<a href="<?php echo home_url('/sok/?nokkelord='.$current_location->name.'') ?>" class="item"><?php echo $current_location->name ?></a>
					<div class="separator">
						<img src="<?php echo get_template_directory_uri().'/src/icons/breadcrumb-chevron.svg' ?>" alt="Chevron right">
					</div>		
					<div class="current"><?php echo get_the_title(get_the_ID()) ?></div>
				</div>

				<div class="banner_image">
					<?php echo wp_get_attachment_image($banner_image, 'full') ?>
				</div>


				<div class="flex_container">

					<div class="left_col">

						<!-- <php if($company_package != 'Select package'): ?> -->

							<div class="logo_holder">
								<?php if(!empty(get_the_post_thumbnail(get_the_ID(), 'full'))): ?>
									<?php echo get_the_post_thumbnail(get_the_ID(), 'full') ?>
								<?php else: ?>
									<img class="placeholder_img" src="<?php echo IMG.'/logo_placeholder.png' ?>" alt="Logo placeholder">
								<?php endif ?>
							</div>

						<!-- <php endif ?> -->

							<div class="phone_holder">
								<div class="title"><?php echo $company_title ?></div>

								<div class="flex-container">

									<a data-number="<?php echo $company_phone ?>" href="<?php echo (!empty($company_phone))? 'tel:'.$company_phone : 'javascript:void(0)' ?>" class="number <?php echo empty($company_phone)? 'disable-click' : '' ?>">
										<img src="<?php echo get_template_directory_uri().'/src/icons/phone.svg' ?>" alt="Phone icon">
										<?php if(!empty($company_phone)): ?>
											<?php echo $company_phone ?>
										<?php else: ?>
											Nummeret mangler
										<?php endif ?>
									</a>

									<?php if(!empty($company_phone)): ?>
										<div style="position: absolute; opacity: 0; pointer-events: none;" id="#example1"><?php echo $company_phone ?></div>
									<?php endif ?>

									<?php if(!empty($company_phone)): ?>
										<div id="mytext" class="copy_btn active" data-phone="<?php echo $company_phone ?>" onClick="copyTextFromElement('mytext')">
											<img src="<?php echo get_template_directory_uri().'/src/icons/duplicate.png' ?>" alt="Copy to clickboard icon">

											<div class="popup">Kopiert!</div>
										</div>
									<?php endif ?>

								</div>
								
						</div>


						<?php if( ($company_package != 'Select package') && ($company_package != 0) ): ?>
							
							<div class="links_holder">

								<div class="title">Kontaktinformasjon</div>

								<?php if(!empty($company_website)): ?>
									<a target="_blank" href="<?php echo $company_website ?>" class="website">
										<img src="<?php echo get_template_directory_uri().'/src/icons/newtab.svg' ?>" alt="New tab icon">
										<?php echo 'Nettsted' ?>
									</a>
								<?php endif ?>

								<?php if(!empty($company_email)): ?>
									<a href="mailto:<?php echo $company_email ?>" class="email">
										<img src="<?php echo get_template_directory_uri().'/src/icons/email.svg' ?>" alt="Email icon">
										<?php echo 'E-post' ?>
									</a>
								<?php endif ?>
								
								<?php if(!empty($company_facebook)): ?>
									<a target="_blank" href="<?php echo $company_facebook ?>" class="facebook">
										<img src="<?php echo get_template_directory_uri().'/src/icons/facebook.svg' ?>" alt="Facebook icon">
										Facebook
									</a>
								<?php endif ?>

								<?php if(!empty($company_instagram)): ?>
									<a target="_blank" href="<?php echo $company_instagram ?>" class="instagram">
										<img src="<?php echo get_template_directory_uri().'/src/icons/instagram.png' ?>" alt="Instagram icon">
										Instagram
									</a>
								<?php endif ?>


							</div>

						<?php endif ?>


						<?php if(carbon_get_post_meta(get_the_ID(), 'enable_hours')): ?>

							<div class="business_hours_holder">
								<div class="title">Åpningstider</div>

								<?php 
									// Set the timezone to GMT+2
									$timezone = new DateTimeZone('GMT+2');

									// Create a DateTime object with the current time
									$datetime = new DateTime('now', $timezone);

									// Format the date and time as a string
									$time_str = $datetime->format('l');
								?>

								<div class="hour <?php echo ($time_str == 'Monday')? 'active' : '' ?>">
									<div class="day">Mandag</div>
									<div class="time"><?php echo carbon_get_post_meta(get_the_ID(), 'mandag') ?></div>
								</div>

								<div class="hour <?php echo ($time_str == 'Tuesday')? 'active' : '' ?>">
									<div class="day">Tirsdag</div>
									<div class="time"><?php echo carbon_get_post_meta(get_the_ID(), 'tirsdag') ?></div>
								</div>

								<div class="hour <?php echo ($time_str == 'Wednesday')? 'active' : '' ?>">
									<div class="day">Onsdag</div>
									<div class="time"><?php echo carbon_get_post_meta(get_the_ID(), 'onsdag') ?></div>
								</div>

								<div class="hour <?php echo ($time_str == 'Thursday')? 'active' : '' ?>">
									<div class="day">Torsdag</div>
									<div class="time"><?php echo carbon_get_post_meta(get_the_ID(), 'torsdag') ?></div>
								</div>
								
								<div class="hour <?php echo ($time_str == 'Friday')? 'active' : '' ?>">
									<div class="day">Fredag</div>
									<div class="time"><?php echo carbon_get_post_meta(get_the_ID(), 'fredag') ?></div>
								</div>

								<div class="hour <?php echo ($time_str == 'Saturday')? 'active' : '' ?>">
									<div class="day">Lørdag</div>
									<div class="time"><?php echo carbon_get_post_meta(get_the_ID(), 'lordag') ?></div>
								</div>
								
								<div class="hour <?php echo ($time_str == 'Sunday')? 'active' : '' ?>">
									<div class="day">Søndag</div>
									<div class="time"><?php echo carbon_get_post_meta(get_the_ID(), 'sondag') ?></div>
								</div>

							</div>

						<?php endif ?>
						
					</div>


					<div class="right_col <?php echo ($company_package == 'Select package')? 'full-width' : '' ?>">

						<div class="company_info_holder holder">

							<div class="title">
								<img src="<?php echo get_template_directory_uri().'/src/icons/home.svg' ?>" alt="Home icon">
								Om <?php echo $company_title ?>
							</div>

							<div class="desc">
								<?php echo wpautop($company_desc) ?>
							</div>

						</div>


						<div class="company_address holder">

							<div class="title">
								<img src="<?php echo get_template_directory_uri().'/src/icons/pin.svg' ?>" alt="Pin icon">
								Adresse og kart
							</div>

							<div class="desc">
								<?php echo $company_location ?>
								<input type="hidden" name="hidden_address" id="hidden_address" value="<?php echo $company_location ?>">
							</div>

							<div class="map_holder">
								<!-- <iframe
									width="450"
									height="250"
									frameborder="0" style="border:0"
									referrerpolicy="no-referrer-when-downgrade"
									src="https://www.google.com/maps/embed/v1/place?key=AIzaSyC9n1DEj6wCyBWwMBB4IOW83KQCIeysxTg&q=<?php echo $company_location ?>&zoom=12"
									allowfullscreen>
								</iframe> -->

								<div id="map" style="height: 250px;"></div>
							</div>


							<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC9n1DEj6wCyBWwMBB4IOW83KQCIeysxTg"></script>


							<script>

							 function initMap() {
								var address = document.querySelector('#hidden_address').value;

								var geocoder = new google.maps.Geocoder();
								geocoder.geocode( { 'address': address }, function(results, status) {
									if (status == 'OK') {
										var map = new google.maps.Map(document.getElementById('map'), {
											zoom: 14,
											center: results[0].geometry.location,
											styles: [
												{
													"featureType": "administrative.land_parcel",
													"elementType": "labels",
													"stylers": [
													{
														"visibility": "off"
													}
													]
												},
												{
													"featureType": "poi",
													"elementType": "labels.text",
													"stylers": [
													{
														"visibility": "off"
													}
													]
												},
												{
													"featureType": "poi.business",
													"stylers": [
													{
														"visibility": "off"
													}
													]
												},
												{
													"featureType": "road",
													"elementType": "labels.icon",
													"stylers": [
													{
														"visibility": "off"
													}
													]
												},
												{
													"featureType": "road.arterial",
													"elementType": "labels",
													"stylers": [
													{
														"visibility": "off"
													}
													]
												},
												{
													"featureType": "road.highway",
													"elementType": "labels",
													"stylers": [
													{
														"visibility": "off"
													}
													]
												},
												{
													"featureType": "road.local",
													"stylers": [
													{
														"visibility": "off"
													}
													]
												},
												{
													"featureType": "road.local",
													"elementType": "labels",
													"stylers": [
													{
														"visibility": "off"
													}
													]
												},
												{
													"featureType": "transit",
													"stylers": [
													{
														"visibility": "off"
													}
													]
												}
											]
										});
										var marker = new google.maps.Marker({
											map: map,
											position: results[0].geometry.location
										});
									} else {
										alert('Geocode was not successful for the following reason: ' + status);
									}
								});
							}	

							initMap();

							</script>

							
							<?php if( ($company_package != 'Select package') && ($company_package != 0) ): ?>
								<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $company_location ?>" id="map_direction_btn" class="btn secondary-btn">
									<img src="<?php echo get_template_directory_uri().'/src/icons/compass.svg' ?>" alt="Route icon">
									Veibeskrivelse
								</a>
							<?php endif ?>

						</div>


						<div class="gallery_holder holder">
							<div class="title">
								<img src="<?php echo get_template_directory_uri().'/src/icons/image.svg' ?>" alt="Image icon">
								Bilder
							</div>


							<div class="image_holder">

								<div class="col-1">

									<?php if(!empty($company_gallery)): ?>

										<div class="splide">
											<div class="splide__track">
												<div class="splide__list">
													<?php foreach($company_gallery as $img): ?>
														<div class="splide__slide">
															<?php echo wp_get_attachment_image($img, 'full') ?>
														</div>
													<?php endforeach ?>
												</div>
											</div>
										</div>

									<?php else: ?>

										<p><?php echo $company_title ?> har ennå ikke lastet opp noen bilder.</p>

									<?php endif ?>

								</div>

							</div>

						</div>
						


						<div class="reviews_holder">

							<?php ic_reviews(); ?>

						</div>
				

						<?php if(!empty(get_the_category(get_the_ID()))): ?>

							<div class="tags_holder">
								<div class="title">
									<img src="<?php echo get_template_directory_uri().'/src/icons/tag.svg' ?>" alt="Tag icon">
									Bransjer
								</div>

								<div class="tags">
									<?php 
										foreach(get_the_category(get_the_ID()) as $tag):
									?>
										<a href="<?php echo home_url('/sok/?nokkelord='.$tag->name.'') ?>" class="tag"><?php echo $tag->name ?></a>
									<?php
										endforeach;
									?>
								</div>		
							</div>

						<?php endif ?>


					</div>

				</div>

			</div>

		</main><!-- #main -->

	</div><!-- #primary -->

</div><!-- #page-wrapper -->

<?php get_footer();

