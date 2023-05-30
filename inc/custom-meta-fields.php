<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'carbon_fields_register_fields', 'custom_meta_fields' );

function custom_meta_fields() {

    Container::make( 'post_meta', 'Sample field' )
        ->where( 'post_type', '=', 'post' )
        ->set_context('advanced')
        ->set_priority('low')
        ->add_fields( array(
        
            Field::make( 'text', 'sample_text', __( 'Sample text' ) ),
            
        ));

}

?>