<?php 

 /*
 * Button Block Visual Composer
 *
 */

add_action( 'vc_before_init', 'listeo_button_integrateWithVC' );
function listeo_button_integrateWithVC() {
  
  vc_map( array(
    "name" => __("Button (listeo)", 'listeo'),
    "base" => "button",
    'icon' => 'listeo_icon',
    'description' => __( 'listeo styled button', 'listeo' ),
    "category" => __('Listeo', 'listeo'),
    "params" => array(

        array(
          'type' => 'vc_link',
          'heading' => __( 'URL (Link)', 'listeo' ),
          'param_name' => 'url',
          'description' => __( 'Button link.', 'listeo' )
        ),
      array(
        'type' => 'dropdown',
        'heading' => __( 'Button color', 'listeo' ),
        'param_name' => 'color',
        'save_always' => true,
        'value' => array(
          __( 'Current main color', 'listeo' ) => 'color',
          __( 'Border color', 'listeo' )  => 'border',
          ),
        ),      

      array(
        'type' => 'colorpicker',
        'heading' => __( 'Custom color', 'listeo' ),
        'param_name' => 'customcolor',
        ),
      array(
        'type' => 'dropdown',
        'heading' => __( 'Icon color', 'listeo' ),
        'param_name' => 'color',
        'value' => array(
          __( 'White', 'listeo' ) => 'white',
          __( 'Black', 'listeo' ) => 'black',
          ),
        'save_always' => true,
        ),      

      array(
          'type' => 'iconpicker',
          'heading' => esc_html__( 'Icon', 'listeo' ),
          'param_name' => 'icon',
          'description' => esc_html__( 'Icon', 'listeo' ),
      ),
      array(
        'type' => 'textfield',
        'heading' => __( 'Custom class', 'listeo' ),
        'param_name' => 'customclass',
        'description' =>  __( 'Optional', 'listeo' ),
        ),
      array(
        'type' => 'from_vs_indicatior',
        'heading' => __( 'From Visual Composer', 'listeo' ),
        'param_name' => 'from_vs',
        'value' => 'yes',
        'save_always' => true,
        )
      ),
));
}

?>