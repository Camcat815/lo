<?php 

add_action( 'vc_before_init', 'logo_slider_integrateWithVC' );
function logo_slider_integrateWithVC() {

  vc_map( array(
    "name" => esc_html__("Logo Slider", 'listeo'),
    "base" => "logo-slider",
    'icon' => 'listeo_icon',
    'description' => esc_html__( 'Logo images slider', 'listeo' ),
    "category" => esc_html__('Listeo', 'listeo'),
    "params" => array(
      array(
        'type' => 'attach_images',
        'heading' => esc_html__( 'Images', 'listeo' ),
        'param_name' => 'images',
        'value' => '',
        'description' => esc_html__( 'Select images from media library.', 'listeo' )
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