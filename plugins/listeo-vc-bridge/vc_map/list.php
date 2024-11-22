<?php 


/*
 * Headline for Visual Composer
 *
 */
add_action( 'vc_before_init', 'listeo_list_integrateWithVC' );
function listeo_list_integrateWithVC() {

 vc_map( array(
  "name" => esc_html__("List with icons", 'listeo'),
  "base" => "list",
  'icon' => 'listeo_icon',
  "category" => esc_html__('Listeo', 'listeo'),
  "params" => array(
    array(
      'type' => 'textarea_html',
      'heading' => esc_html__( 'Content', 'listeo' ),
      'param_name' => 'content',
      'description' => esc_html__( 'Enter message content.', 'listeo' )
      ),

    array(
      "type" => "dropdown",
      "class" => "",
      "heading" => esc_html__("Icon", 'listeo'),
      "param_name" => "icon",
      'save_always' => true,
      "value" => array(
        'Square'    => 'list-1',
        'Arrow'   => 'list-2',
        'Arrow 2' => 'list-3',
        'Circle'    => 'list-4',
        ),
      'save_always' => true,
      "description" => ""
    ),

    array(
        'type' => 'checkbox',
        'heading' => esc_html__( 'Colored?', 'listeo' ),
        'param_name' => 'color',
        'description' => esc_html__( 'If checked the icon will have current theme color.', 'listeo' ),
        'value' => array( esc_html__( 'Yes', 'listeo' ) => 'yes' )
      ),     
    array(
        'type' => 'checkbox',
        'heading' => esc_html__( 'Numbered?', 'listeo' ),
        'param_name' => 'numbered',
        'description' => esc_html__( 'If checked the list  will have numbers instead of icons.', 'listeo' ),
        'value' => array( esc_html__( 'Yes', 'listeo' ) => 'yes' )
      ),     
    array(
        'type' => 'checkbox',
        'heading' => esc_html__( 'Filled?', 'listeo' ),
        'param_name' => 'filled',
        'description' => esc_html__( 'If checked the number icon  will have current theme color.', 'listeo' ),
        'value' => array( esc_html__( 'Yes', 'listeo' ) => 'yes' )
      ), 

    ),

));
}

?>