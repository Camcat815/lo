<?php 

add_action( 'vc_before_init', 'address_box_integrateWithVC' );
function address_box_integrateWithVC() {

  vc_map( array(
    "name" => esc_html__("Map address box", 'listeo'),
    "base" => "address-box",
    'admin_enqueue_css' => array(get_template_directory_uri().'/vc_templates/css/listeo_vc_css.css'),
    
    'icon' => 'listeo_icon',
    'description' => esc_html__( 'Full width map with address', 'listeo' ),
    "category" => esc_html__('Listeo', 'listeo'),
    "params" => array(

      array(
        'type' => 'attach_image',
        'heading' => esc_html__( 'Background image for address box', 'listeo' ),
        'param_name' => 'background',
        'value' => '',
        'description' => esc_html__( 'Select image from media library.', 'listeo' )
      ),
      array(
        'type' => 'textfield',
        'heading' => esc_html__( 'Latitude', 'listeo' ),
        'param_name' => 'latitude',
        'value' => '', // default value
        'description' => '',
      ),      
      array(
        'type' => 'textfield',
        'heading' => esc_html__( 'Longitude', 'listeo' ),
        'param_name' => 'longitude',
        'value' => '', // default value
        'description' => '',
      ), 
      array(
          'type' => 'textarea_html',
          'heading' => esc_html__( 'Content', 'listeo' ),
          'param_name' => 'content',
          'description' => esc_html__( 'Enter content.', 'listeo' )
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