<?php

/*
 * Iconbox for Visual Composer
 *
 */
add_action( 'vc_before_init', 'pp_agents_integrateWithVC' );
function pp_agents_integrateWithVC() {
  vc_map( array(
    "name" => esc_html__("Agents","listeo"),
    "base" => "agents",
    'icon' => 'listeo_icon',
    'description' => esc_html__( 'Agents grid', 'listeo' ),
    "category" => esc_html__('Listeo',"listeo"),
    "params" => array(
         array(
          'type' => 'textfield',
          'heading' => __( 'Role', 'sphene' ),
          'param_name' => 'role',
 
        ),
        array(
          'type' => 'dropdown',
          'heading' => __( 'Order by', 'listeo' ),
          'param_name' => 'orderby',
          'value' => array(
            __( 'Order by user display name', 'listeo' )  => 'display_name',
            __( 'Order by user id', 'listeo' )            => 'ID',
            __( 'Order by the included list of user_ids (requires the include parameter)', 'listeo' ) => 'include',
            __( 'Order by user login.', 'listeo' )        => 'login',
            __( 'Order by user nicename.', 'listeo' )     => 'nicename',
            __( 'Order by user email.', 'listeo' )        => 'email',
            __( 'Order by user url.', 'listeo' )          => 'user_url',
            __( 'Order by user registered date', 'listeo' ) => 'registered',
            __( 'Order by user post count', 'listeo' )    => 'post_count',
            ),
        ),
        array(
          'type' => 'from_vs_indicatior',
          'heading' => esc_html__( 'From Visual Composer', 'listeo' ),
          'param_name' => 'from_vs',
          'value' => 'yes',
          'save_always' => true,
        ),
        array(
          'type' => 'autocomplete',
          'heading' => __( 'Users to include', 'listeo' ),
          'param_name' => 'include',
          'description' => __( 'Select items, leave empty to use all.', 'listeo' ),
          'settings'    => array(
            'multiple' => true,
            'sortable' => true,
            'no_hide' => true, // In UI after select doesn't hide an select list
            'groups' => true, // In UI show results grouped by groups
            'unique_values' => true, // In UI show results except selected. NB! You should manually check values in backend
            'display_inline' => true, // In UI show results inline view
          )
        ),        
        array(
          'type' => 'autocomplete',
          'heading' => __( 'Users to exclude', 'listeo' ),
          'param_name' => 'exclude',
          'description' => __( 'Select items, leave empty to use all.', 'listeo' ),
          'settings'    => array(
            'multiple' => true,
            'sortable' => true,
            'no_hide' => true, // In UI after select doesn't hide an select list
            'groups' => true, // In UI show results grouped by groups
            'unique_values' => true, // In UI show results except selected. NB! You should manually check values in backend
            'display_inline' => true, // In UI show results inline view
          )
        ),
    ),
  ));
}

add_filter( 'vc_autocomplete_agents_include_callback',
  'vc_get_agents_search', 10, 1 ); // Get suggestion(find). Must return an array

 add_filter( 'vc_autocomplete_agents_include_render',
  'vc_get_agents_render', 10, 1 ); // Render exact product. Must return an array (label,value)
                                   
add_filter( 'vc_autocomplete_agents_exclude_callback',
  'vc_get_agents_search', 10, 1 ); // Get suggestion(find). Must return an array

 add_filter( 'vc_autocomplete_agents_exclude_render',
  'vc_get_agents_render', 10, 1 ); // Render exact product. Must return an array (label,value)

?>