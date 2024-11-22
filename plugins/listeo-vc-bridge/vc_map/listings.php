<?php
/*
 * Recent project for Visual Composer
 *

 */

add_action( 'vc_before_init', 'listings_integrateWithVC' );
function listings_integrateWithVC() {

  
  $choose_empty = array (__( '-Choose option-', 'listeo' ) => '',);
  vc_map( array(
    "name" => __("Listings", 'listeo'),
    "base" => "listings",
    'icon' => 'listeo_icon',
    'description' => __( 'listings list', 'listeo' ),
    "category" => __('Listeo',"listeo"),
    "params" => array(
        array(
          'type' => 'dropdown',
          'heading' => __( 'Style', 'listeo' ),
          'param_name' => 'style',
          'value' => array(
            __( 'List layout', 'listeo' )     => 'list',
            __( 'Grid layout', 'listeo' )     => 'grid',
            __( 'Grid compact', 'listeo' )     => 'compact',
            
            ),
        ),   
        array(
          'type' => 'checkbox',
          'heading' => __( 'Top bar list elements (if applicable)', 'listeo' ),
          'param_name' => 'list_top_buttons',
          'value' => array(
            __( 'None', 'listeo' ) => 'none',
            __( 'Layout switcher', 'listeo' ) => 'layout',
            __( 'Filters', 'listeo' ) => 'filters',
            __( 'Orderby', 'listeo' ) => 'order',
            __( 'Radius', 'listeo' ) => 'radius',
            ),
        ),
        array(
          'type' => 'dropdown',
          'heading' => __( 'Show selected listing types)', 'listeo' ),
          'param_name' => '_listing_type',
          'value' => array(
            __( 'All', 'listeo' ) => '',
            __( 'Service', 'listeo' ) => 'service',
            __( 'Rental', 'listeo' ) => 'rental',
            __( 'Event', 'listeo' ) => 'event',
            ),
        ),

        //'filters|order|layout|radius',
        array(
          'type' => 'textfield',
          'heading' => __( 'Keyword', 'listeo' ),
          'param_name' => 'keyword',
          'description' => __( 'Search by keyword.', 'listeo' ),

        ),  
        array(
          'type' => 'textfield',
          'heading' => __( 'Location', 'listeo' ),
          'param_name' => 'location',
          'description' => __( 'Search by locaton.', 'listeo' ),

        ),   
        array(
          'type' => 'dropdown',
          'heading' => __( 'Order by', 'listeo' ),
          'param_name' => 'orderby',
          'value' => array(
            __( 'Date', 'listeo' ) => 'date',
            __( 'Random', 'listeo' ) => 'rand',
            __( 'Featured', 'listeo' ) => 'featured',
          
            __( 'Best rated', 'listeo' ) => 'highest',
            __( 'Most views', 'listeo' ) => 'views',
            __( 'Most reviews', 'listeo' ) => 'reviewed',
            __( 'Title', 'listeo' ) => 'title',
          
            ),
        ),
        array(
          'type' => 'dropdown',
          'heading' => __( 'Order', 'listeo' ),
          'param_name' => 'order',
          'value' => array(
            __( 'Descending', 'listeo' ) => 'DESC',
            __( 'Ascending', 'listeo' ) => 'ASC'
            ),
        ),
        array(
          'type' => 'textfield',
          'heading' => __( 'Elements to show', 'listeo' ),
          'param_name' => 'per_page',
          'description' => __( 'Use only number', 'listeo' ),

          'std' => '6',
           'save_always' => true,
        ), 
      
     

/*type*/
      array(
          'type' => 'checkbox',
          'heading' => __( 'Featured listings', 'listeo' ),
          'param_name' => 'featured',
          'description' => __( 'Show only featured listings.', 'listeo' )
        ),  
      array(
          'type' => 'checkbox',
          'heading' => __( 'Verified listings', 'listeo' ),
          'param_name' => '_verified',
           'value' => array( esc_html__( 'Yes', 'listeo' ) => 'on' ),
          'description' => __( 'Show only verified listings.', 'listeo' )
        ),      
      array(
          'type' => 'custom_taxonomy_list_by_slug',
          'heading' => __( 'Listing Feature', 'listeo' ),
          'param_name' => 'tax-listing_feature',
          'taxonomy' => 'listing_feature',
          'description' => __( 'Show listings by feature.', 'listeo' )
        ),
      array(
            'type' => 'custom_taxonomy_list_by_slug',
            'heading' => __( 'Region', 'listeo' ),
            'param_name' => 'tax-region',
            'taxonomy' => 'region',
            'description' => __( 'Show listings from a region.', 'listeo' )
          ),
       array(
            'type' => 'custom_taxonomy_list_by_slug',
            'heading' => __( 'Listing Category', 'listeo' ),
            'param_name' => 'tax-listing_category',
            'taxonomy' => 'listing_category',
            'description' => __( 'Show listings from a selected category.', 'listeo' )
          ),
       array(
            'type' => 'custom_taxonomy_list_by_slug',
            'heading' => __( 'Service Category', 'listeo' ),
            'param_name' => 'tax-service_category',
            'taxonomy' => 'service_category',
            'description' => __( 'Show listings from a selected category.', 'listeo' )
          ),
       array(
            'type' => 'custom_taxonomy_list_by_slug',
            'heading' => __( 'Rental Category', 'listeo' ),
            'param_name' => 'tax-rental_category',
            'taxonomy' => 'rental_category',
            'description' => __( 'Show listings from a selected category.', 'listeo' )
          ), 
       array(
            'type' => 'custom_taxonomy_list_by_slug',
            'heading' => __( 'Event Category', 'listeo' ),
            'param_name' => 'tax-event_category',
            'taxonomy' => 'event_category',
            'description' => __( 'Show listings from a selected category.', 'listeo' )
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