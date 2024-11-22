<?php

/*
 * Counters for Visual Composer
 *
 */

add_action( 'vc_before_init', 'listeo_counterbox_integrateWithVC' );
  function listeo_counterbox_integrateWithVC() {

    vc_map( array(
      "name" => esc_html__("Counters wrapper", "listeo"),
      "base" => "counters",
      "as_parent" => array('only' => 'counter'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
      "content_element" => true,
      "category" => esc_html__('Listeo', 'listeo'),
      'icon' => 'listeo_icon',
      "show_settings_on_create" => false,
      "params" => array(
          // add params same as with any other content element
        array(
          "type" => "textfield",
          "heading" => esc_html__("Title", "listeo"),
          "param_name" => "title",
          "description" => esc_html__("Title of the box", "listeo")
          ),
        array(
          'type' => 'attach_image',
          'heading' => esc_html__( 'Background image', 'listeo' ),
          'param_name' => 'background',
          'value' => '',
          'description' => esc_html__( 'Select image from media library.', 'listeo' )
        ),
        array(
          'type' => 'from_vs_indicatior',
          'heading' => esc_html__( 'From Visual Composer', 'listeo' ),
          'param_name' => 'from_vs',
          'value' => 'yes',
          'save_always' => true,
          )
        ),
      "js_view" => 'VcColumnView'
      ));


    vc_map( array(
      "name" => esc_html__("Counter box", 'listeo'),
      "base" => "counter",
      'icon' => 'listeo_icon',
      'description' => esc_html__( 'Animated number counter', 'listeo' ),
      "category" => esc_html__('Listeo', 'listeo'),
      "params" => array(
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Title', 'listeo' ),
          'param_name' => 'title',
          'description' => esc_html__( 'Enter text which will be used as title.', 'listeo' )
          ),
       
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Value', 'listeo' ),
          'param_name' => 'number',
          'description' => esc_html__( 'Only number (for example 2,147).', 'listeo' )
          ),      

        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Scale', 'listeo' ),
          'param_name' => 'value',
          'description' => esc_html__( 'Optional. For example %, degrees, k, etc.', 'listeo' )
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
          'type' => 'checkbox',
          'heading' => esc_html__( 'Featured?', 'listeo' ),
          'param_name' => 'colored',
          'description' => esc_html__( 'If checked the box will have current main color background.', 'listeo' ),
          'value' => array( esc_html__( 'Yes', 'listeo' ) => 'yes' ),
          'default' => 'yes',
          'save_always' => true,
        ), 
         array(
          'type' => 'checkbox',
          'heading' => esc_html__( 'Used in Counter wrapper?', 'listeo' ),
          'param_name' => 'in_full_width',
          'description' => esc_html__( 'Please check this box if counter is inside the "Counter wrapper" element', 'listeo' ),
          'value' => array( esc_html__( 'Yes', 'listeo' ) => 'yes' ),
          'default' => 'yes',
          'save_always' => true,
        ), 
        array(
          'type' => 'dropdown',
          'heading' => esc_html__( 'Width of the box', 'listeo' ),
          'param_name' => 'width',
          'description' => esc_html__( 'Applicable if the element is inside the "Counter wrapper" element', 'listeo' ),
          'value' => array(
            esc_html__('Two','listeo') => '2',
            esc_html__('Three','listeo') => '3',
            esc_html__('Four','listeo') => '4',
            ),
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

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_Counters extends WPBakeryShortCodesContainer {
    }
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_Counter extends WPBakeryShortCode {
    }
}
?>