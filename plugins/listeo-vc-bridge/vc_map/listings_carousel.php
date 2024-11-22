<?php
/*
 * Recent project for Visual Composer
 *

 */


add_action( 'vc_before_init', 'listings_carousel_integrateWithVC' );
function listings_carousel_integrateWithVC() {
  
  $choose_empty = array (__( '-Choose option-', 'listeo' ) => '',);
  vc_map( array(
    "name" => __("Listings Carousel", 'listeo'),
    "base" => "listings-carousel",
    'icon' => 'listeo_icon',
    'description' => __( 'Carousel with posts ', 'listeo' ),
    "category" => __('Listeo',"listeo"),
    "params" => array(
 
        array(
          'type' => 'dropdown',
          'heading' => __( 'Order by', 'listeo' ),
          'param_name' => 'orderby',
          'value' => array(
            __( 'Date', 'listeo' ) => 'date',
            __( 'ID', 'listeo' ) => 'ID',
            __( 'Author', 'listeo' ) => 'author',
            __( 'Title', 'listeo' ) => 'title',
            __( 'Modified', 'listeo' ) => 'modified',
            __( 'Random', 'listeo' ) => 'rand',
            __( 'Comment count', 'listeo' ) => 'comment_count',
            __( 'Menu order', 'listeo' ) => 'menu_order'
            ),
        ),
         array(
          'type' => 'checkbox',
          'heading' => __( 'Enable if the container row is set to fullwidth', 'listeo' ),
          'param_name' => 'fullwidth',
          'description' => __( 'Users layout designed for full-width row.', 'listeo' )
        ),  
         array(
          'type' => 'dropdown',
          'heading' => __( 'Box style', 'listeo' ),
          'param_name' => 'style',
          'value' => array(
            __( 'Style 1', 'listeo' ) => 'style-1',
            __( 'Style 2', 'listeo' ) => 'style-2'
            ),
          'save_always' => true,
        ),
         array(
          'type' => 'dropdown',
          'heading' => __( 'Autoplay carousel', 'listeo' ),
          'param_name' => 'autoplay',
          'value' => array(
            __( 'Disabled', 'listeo' ) => 'off',
            __( 'Enabled', 'listeo' ) => 'on'
            ),
          'save_always' => true,
        ),
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Autoplay Speed', 'listeo' ),
          'param_name' => 'autoplayspeed',
          'description' => esc_html__( 'In miliseconds', 'listeo' ),
          'std' => '3000'
        ), 
         
        array(
          'type' => 'dropdown',
          'heading' => __( 'Elements to show', 'listeo' ),
          'param_name' => 'limit',
          'value' => array(
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
            '7' => '7',
            '8' => '8',
            '9' => '9',
            '10' => '10',
            '11' => '11',
            '12' => '12',
            ),
          'save_always' => true,
          'std' => '6'
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
          'type' => 'dropdown',
          'heading' => __( 'Relation', 'listeo' ),
          'param_name' => 'relation',
          'value' => array(
            __( 'OR', 'listeo' ) => 'OR',
            __( 'AND', 'listeo' ) => 'AND'
            ),
        ),

      array(
          'type' => 'autocomplete',
          'heading' => __( 'Listings to include', 'listeo' ),
          'param_name' => 'include_posts',
          'settings'    => array(
            'multiple' => true,
            'sortable' => true,
            'no_hide' => true, // In UI after select doesn't hide an select list
            'groups' => true, // In UI show results grouped by groups
            'unique_values' => true, // In UI show results except selected. NB! You should manually check values in backend
            'display_inline' => true, // In UI show results inline view
          ),
          'description' => __( 'Select items, leave empty to use all.', 'listeo' )
        ),
      
      array(
          'type' => 'autocomplete',
          'heading' => __( 'Listings to exclude', 'listeo' ),
          'param_name' => 'exclude_posts',
          'settings'    => array(
            'multiple' => true,
            'sortable' => true,
            'no_hide' => true, // In UI after select doesn't hide an select list
            'groups' => true, // In UI show results grouped by groups
            'unique_values' => true, // In UI show results except selected. NB! You should manually check values in backend
            'display_inline' => true, // In UI show results inline view
          ),
          'description' => __( 'Select items to exclude from list.', 'listeo' )
        ),
/*type*/
      array(
          'type' => 'checkbox',
          'heading' => __( 'Featured listings', 'listeo' ),
          'param_name' => 'featured',
          'description' => __( 'Show only featured listings.', 'listeo' )
        ),   
           array(
          'type' => 'custom_taxonomy_list_by_slug',
          'heading' => __( 'Listings Feature', 'listeo' ),
          'param_name' => 'feature',
          'taxonomy' => 'listing_feature',
          'description' => __( 'Show listings by feature.', 'listeo' )
        ),
      array(
            'type' => 'custom_taxonomy_list_by_slug',
            'heading' => __( 'Region', 'listeo' ),
            'param_name' => 'region',
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

                        // 
add_filter( 'vc_autocomplete_listings-carousel_include_posts_callback',
  'vc_get_listings_search', 10, 1 ); // Get suggestion(find). Must return an array
                                       // 

 add_filter( 'vc_autocomplete_listings-carousel_include_posts_render',
  'vc_get_listings_render', 10, 1 ); // Render exact product. Must return an array (label,value)
                                   
add_filter( 'vc_autocomplete_listings-carousel_exclude_posts_callback',
  'vc_get_listings_search', 10, 1 ); // Get suggestion(find). Must return an array

 add_filter( 'vc_autocomplete_listings-carousel_exclude_posts_render',
  'vc_get_listings_render', 10, 1 ); // Render exact product. Must return an array (label,value)

?>