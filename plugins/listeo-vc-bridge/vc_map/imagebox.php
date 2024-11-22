<?php

/*
 * Iconbox for Visual Composer
 *
 */
add_action( 'vc_before_init', 'pp_imagebox_integrateWithVC' );
function pp_imagebox_integrateWithVC() {


  $categories =  get_terms( 'region', array(
      'hide_empty' => false,
  ) );  
  $options = array();
  if ( ! empty( $categories ) && ! is_wp_error( $categories ) ){
    $options['Select region'] = '';
    foreach ($categories as $cat) {
      $options[$cat->name] = $cat->term_id;
    }
  }

  $listing_feature =  get_terms( 'listing_feature', array(
      'hide_empty' => false,
  ) );	
  $listing_feature_options = array();
  if ( ! empty( $listing_feature ) && ! is_wp_error( $listing_feature ) ){
    $listing_feature_options['or select feature'] = '';
    foreach ($listing_feature as $feature) {
    	$listing_feature_options[$feature->name] = $feature->term_id;
    }
  }

  $listing_category =  get_terms( 'listing_category', array(
      'hide_empty' => false,
  ) );  
  $listing_category_options = array();
  if ( ! empty( $listing_category ) && ! is_wp_error( $listing_category ) ){
    $listing_category_options['or select Listing Category'] = '';
    foreach ($listing_category as $feature) {
      $listing_category_options[$feature->name] = $feature->term_id;
    }
  }

  $event_category =  get_terms( 'event_category', array(
      'hide_empty' => false,
  ) );  
  $event_category_options = array();
  if ( ! empty( $event_category ) && ! is_wp_error( $event_category ) ){
    $event_category_options['or select Event Category'] = '';
    foreach ($event_category as $feature) {
      $event_category_options[$feature->name] = $feature->term_id;
    }
  }

  $service_category =  get_terms( 'service_category', array(
      'hide_empty' => false,
  ) );  
  $service_category_options = array();
  if ( ! empty( $service_category ) && ! is_wp_error( $service_category ) ){
    $service_category_options['or select Event Category'] = '';
    foreach ($service_category as $feature) {
      $service_category_options[$feature->name] = $feature->term_id;
    }
  }

  $rental_category =  get_terms( 'rental_category', array(
      'hide_empty' => false,
  ) );  
  $rental_category_options = array();
  if ( ! empty( $rental_category ) && ! is_wp_error( $rental_category ) ){
    $rental_category_options['or select Event Category'] = '';
    foreach ($rental_category as $feature) {
      $rental_category_options[$feature->name] = $feature->term_id;
    }
  }


  vc_map( array(
    "name" => esc_html__("Imagebox","listeo"),
    "base" => "imagebox",
    'icon' => 'listeo_icon',
    'description' => esc_html__( 'Box displaying custom taxonomy', 'listeo' ),
    "category" => esc_html__('Listeo',"listeo"),
    "params" => array(
     
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Region', 'listeo' ),
            'param_name' => 'category',
            'description' => esc_html__( 'Choose region', 'listeo' ),
            'value' => $options,
            'std' => '',
            'save_always' => true,
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Or Listing Feature', 'listeo' ),
            'param_name' => 'listing_feature',
            'description' => esc_html__( 'Or Choose Feature', 'listeo' ),
            'value' => $listing_feature_options,
            'std' => '',
            'save_always' => true,
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Or Listing Category', 'listeo' ),
            'param_name' => 'listing_category',
            'description' => esc_html__( 'Or Choose Listing Category', 'listeo' ),
            'value' => $listing_category_options,
            'std' => '',
            'save_always' => true,
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Or Event Category', 'listeo' ),
            'param_name' => 'event_category',
            'description' => esc_html__( 'Or Choose Event Category', 'listeo' ),
            'value' => $event_category_options,
            'std' => '',
            'save_always' => true,
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Or Service Category', 'listeo' ),
            'param_name' => 'service_category',
            'description' => esc_html__( 'Or Choose Service Category', 'listeo' ),
            'value' => $service_category_options,
            'std' => '',
            'save_always' => true,
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Or Rental Category', 'listeo' ),
            'param_name' => 'rental_category',
            'description' => esc_html__( 'Or Choose Rental Category', 'listeo' ),
            'value' => $rental_category_options,
            'std' => '',
            'save_always' => true,
        ),



        array(
    		    'type' => 'attach_image',
    		    'heading' => esc_html__( 'Background image', 'sphene' ),
    		    'param_name' => 'background',
    		    'value' => '',
    		    'description' => esc_html__( 'Select image from media library.', 'sphene' )
    		),
        array(
          'type' => 'checkbox',
          'heading' => esc_html__( 'Add Featured badge?', 'listeo' ),
          'param_name' => 'featured',
          'save_always' => true,
        ),      
          
        array(
          'type' => 'checkbox',
          'heading' => esc_html__( 'Show counter?', 'listeo' ),
          'param_name' => 'show_counter',
          'save_always' => true,
        ),      
array(
          'type' => 'dropdown',
          'heading' => esc_html__( 'Imagebox style', 'listeo' ),
          'param_name' => 'style',
          'description' => esc_html__( 'Choose imagebox style"', 'listeo' ),
          'value' => array(
            'Default' => 'default', 
            'Alternative'  => 'alternative-imagebox', 
          
            ),
          'std' => 'yes',
          'save_always' => true,
        ),
        array(
          'type' => 'from_vs_indicatior',
          'heading' => esc_html__( 'From Visual Composer', 'listeo' ),
          'param_name' => 'from_vs',
          'value' => 'yes',
          'save_always' => true,
        )
    ),
  ));
}
?>