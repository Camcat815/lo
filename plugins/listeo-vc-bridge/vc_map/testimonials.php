<?php 

/*
 * Testimonials for Visual Composer
 *
 */
add_action( 'vc_before_init', 'listeo_testimonials_wide_integrateWithVC' );
function listeo_testimonials_wide_integrateWithVC() {
  vc_map( array(
    "name" => __("Testimonials", 'listeo'),
    "base" => "testimonials",
    'icon' => 'listeo_icon',
    'description' => __( 'Testimonials carousel', 'listeo' ),
    "category" => __('Listeo',"listeo"),
    "params" => array(
         array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Title', 'listeo' ),
          'param_name' => 'title',
          'description' => esc_html__( 'Enter text which will be used as title', 'listeo' ),
          'save_always' => true,
          ),   
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
        'save_always' => true,
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
        'heading' => __( 'Elements to show', 'listeo' ),
        'param_name' => 'per_page',
        'value' => array(
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
        'std' => '6'
        ),
      array(
        'type' => 'dropdown',
        'heading' => __( 'Text color', 'listeo' ),
        'param_name' => 'textcolor',
        'value' => array(
          __( 'Light', 'listeo' ) => 'light',
          __( 'Dark', 'listeo' ) => 'dark'
          ),
        'save_always' => true,
        ),
      

      array(
        'type' => 'custom_posts_list',
        'heading' => __( 'Testimonials items to include', 'listeo' ),
        'param_name' => 'include_posts',
        'settings' => array(
          'post_type' => 'testimonial',
          ),
        'description' => __( 'Select items, leave empty to use all.', 'listeo' )
        ),
      array(
        'type' => 'custom_posts_list',
        'heading' => __( 'Testimonials to exclude', 'listeo' ),
        'param_name' => 'exclude_posts',
        'settings' => array(
          'post_type' => 'testimonial',
          ),
        'description' => __( 'Select items to exclude from list.', 'listeo' )
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