
<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;


add_action( 'carbon_fields_register_fields', 'custom_theme_options' );
function custom_theme_options() {
    
    $basic_options_container = Container::make( 'theme_options', __( 'Theme Options' ) )
    ->set_icon('dashicons-admin-tools')
    ->set_page_menu_position( 3 )
    ->add_tab( __( 'General' ), array(
        Field::make( 'header_scripts', 'crb_header_script', __( 'Header Script' ) ),
        Field::make( 'footer_scripts', 'crb_footer_script', __( 'Footer Script' ) ),
    ) )
    ->add_tab( __( 'Header' ), array(
        Field::make( 'image', 'header_logo', __( 'Logo' ) ),
        Field::make( 'separator', 'separator', __( 'Buttons' ) ),
        Field::make( 'text', 'header_btn_1_text', __( 'Button 1 text' ) )
            ->set_width(50),
        Field::make( 'text', 'header_btn_2_text', __( 'Button 2 text' ) )
            ->set_width(50),
        
        Field::make( 'text', 'header_btn_1_url', __( 'Button 1 url' ) )
            ->set_width(50),
        Field::make( 'text', 'header_btn_2_url', __( 'Button 2 url' ) )
            ->set_width(50),
    ) )
    ->add_tab( __( 'Footer' ), array(
        
        Field::make( 'image', 'footer_logo', __( 'Logo' ) ),
        Field::make( 'text', 'footer_phone', __( 'Phone number' ) ),
        Field::make( 'text', 'footer_address', __( 'Address' ) ),
        Field::make( 'text', 'copyright', __( 'Copyright' ) ),
            
    ) );

}


?>