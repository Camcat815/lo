<?php

/*
 * Iconbox for Visual Composer
 *
 */
add_action( 'vc_before_init', 'pp_iconbox_integrateWithVC' );
function pp_iconbox_integrateWithVC() {
  vc_map( array(
    "name" => esc_html__("Iconbox","listeo"),
    "base" => "iconbox",
    'icon' => 'listeo_icon',
    'description' => esc_html__( 'Small content box with icon', 'listeo' ),
    "category" => esc_html__('Listeo',"listeo"),
    "params" => array(
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Title', 'listeo' ),
          'param_name' => 'title',
          'description' => esc_html__( 'Enter text which will be used as title', 'listeo' ),
          'save_always' => true,
          ),      
        array(
          'type' => 'textarea_html',
          'heading' => esc_html__( 'Content', 'listeo' ),
          'param_name' => 'content',
          'description' => esc_html__( 'Enter iconbox content.', 'listeo' ),
          'save_always' => true,
        ),
        array(
          'type' => 'vc_link',
          'heading' => esc_html__( 'URL', 'listeo' ),
          'param_name' => 'url',
          'description' => esc_html__( 'Iconbox 1st link', 'listeo' ),
          'save_always' => true,
        ),            
       
        array(
          'type' => 'iconpicker',
          'heading' => esc_html__( 'Icon', 'listeo' ),
          'param_name' => 'icon',
            'settings' => array(
              'type' => 'iconsmind',
              'emptyIcon' => false,
              'iconsPerPage' => 50
              ),
          'description' => esc_html__( 'Icon', 'listeo' ),

        ),
        array(
          'type' => 'dropdown',
          'heading' => esc_html__( 'Type', 'listeo' ),
          'param_name' => 'type',
          'description' => esc_html__( 'Choose size', 'listeo' ),
          'value' => array(
            'box-1'         => 'box-1', 
            'box-1 alternative' => 'box-1 alternative', 
          
            ),
          'std' => 'box-1',
          'save_always' => true,
        ),
        array(
          'type' => 'dropdown',
          'heading' => esc_html__( 'Icon with line', 'listeo' ),
          'param_name' => 'with_line',
          'description' => esc_html__( 'For last icon in row set to "no"', 'listeo' ),
          'value' => array(
            'With line' => 'yes', 
            'Without line'  => 'no', 
          
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