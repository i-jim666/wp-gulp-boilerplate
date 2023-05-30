<?php
/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * 
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/* Custom root paths */

define('ROOTPATH',get_template_directory_uri());
define('ICONS',get_template_directory_uri().'/src/icons');
define('IMG',get_template_directory_uri().'/src/images');

$header_button_1_text = carbon_get_theme_option('header_btn_1_text');
$header_button_1_url = carbon_get_theme_option('header_btn_1_url');

$header_button_2_text = carbon_get_theme_option('header_btn_2_text');
$header_button_2_url = carbon_get_theme_option('header_btn_2_url');

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> style="margin-top: 0 !important;">

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php do_action( 'wp_body_open' ); ?>

    <div class="overlay"></div>

    <header>
    
        <div class="header-desktop">
            <div class="container">

                <div class="logo">
                    <a href="<?php echo home_url() ?>">
                        <?php echo wp_get_attachment_image(carbon_get_theme_option('header_logo'), 'full') ?>
                    </a>
                </div>


                <?php if( !empty($header_button_1_url) || !empty($header_button_2_url) ): ?>
                    <div class="buttons">

                        <?php if(!empty($header_button_1_url)): ?>
                            <a href="<?php echo $header_button_1_url ?>" class="btn ghost-btn"><?php echo $header_button_1_text ?></a>
                        <?php endif ?>

                        <?php if(!empty($header_button_2_url)): ?>
                            <a href="<?php echo $header_button_2_url ?>" class="btn primary-btn"><?php echo $header_button_2_text ?></a>
                        <?php endif ?>

                    </div>
                <?php endif ?>

            </div>
        </div>

    </header>



