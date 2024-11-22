<?php 


/*
 * Headline for Visual Composer
 *
 */
add_action( 'vc_before_init', 'listeo_alertbox_integrateWithVC' );
function listeo_alertbox_integrateWithVC() {

 vc_map( array(
  "name" => esc_html__("Notification box", 'listeo'),
  "base" => "alertbox",
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
      "heading" => esc_html__("Box type", 'listeo'),
      "param_name" => "type",
      'save_always' => true,
      "value" => array(
        'Error' => 'error',
        'Success' => 'success',
        'Warning' => 'warning',
        'Notice' => 'notice',
        ),
      "description" => "",
      'save_always' => true,
    ),
    array(
        'type' => 'checkbox',
        'heading' => esc_html__( 'Closeable?', 'listeo' ),
        'param_name' => 'closeable',
        'description' => esc_html__( 'If checked the box will have close button.', 'listeo' ),
        'value' => array( esc_html__( 'Yes', 'listeo' ) => 'yes' )
      ), 

    ),

));
}

?>