<?php
add_action( 'vc_before_init', 'ws_listeo_pricing_table_integrateWithVC' );
function ws_listeo_pricing_table_integrateWithVC() {
  vc_map( array(
    "name" => esc_html__("Pricing table", 'listeo'),
    "base" => "pricing-table",
    'icon' => 'listeo_icon',
    'description' => esc_html__( 'Pricing table', 'listeo' ),
    "category" => esc_html__('Listeo', 'listeo'),
    "params" => array(
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Type of table', 'listeo' ),
            'param_name' => 'type',
            'save_always' => true,
            'value' => array(
              esc_html__('Standard','listeo') => 'color-1',
              esc_html__('Featured','listeo') => 'featured',
              ),
            ),
        array(
          'type' => 'colorpicker',
          'heading' => esc_html__( 'Custom color', 'listeo' ),
          'param_name' => 'color',
          'description' => esc_html__( 'Select custom background color for table.', 'listeo' ),
          //'dependency' => array( 'element' => 'bgcolor', 'value' => array( 'custom' ) )
        ),
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Title', 'listeo' ),
          'param_name' => 'title',
          'description' => esc_html__( 'Enter text which will be used as widget title. Leave blank if no title is needed.', 'listeo' ),
          'save_always' => true,
          ),       
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Subtitle', 'listeo' ),
          'param_name' => 'subtitle',
          'description' => esc_html__( 'Enter text which will be used as a subtitle. Leave blank if not needed.', 'listeo' ),
          'save_always' => true,
          ),
        
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Price', 'listeo' ),
          'param_name' => 'price',
          'value' => '30',
          'save_always' => true,
          ),     
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Discounted Price', 'listeo' ),
          'param_name' => 'discounted',
          'value' => '30',
          'save_always' => true,
          ),
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Per', 'listeo' ),
          'param_name' => 'per',
          'value' => 'per month',
          'save_always' => true,
          ),
        array(
          'type' => 'textarea_html',
          'heading' => esc_html__( 'Content', 'listeo' ),
          'param_name' => 'content',
          'description' => esc_html__( 'Put here simple UL list', 'listeo' )
          ),
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Button URL', 'listeo' ),
          'param_name' => 'buttonlink',
          'value' => ''
          ),
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Button text', 'listeo' ),
          'param_name' => 'buttontext',
          'value' => ''
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


add_action( 'init', 'listeo_pricingwrapper_integrateWithVC' );
  function listeo_pricingwrapper_integrateWithVC() {

    vc_map( array(
      "name" => esc_html__("Pricing Table wrapper", "listeo"),
      "base" => "pricingwrapper",
      "as_parent" => array('only' => 'pricing-table'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
      "content_element" => true,
      "category" => esc_html__('Listeo', 'listeo'),
      "show_settings_on_create" => false,

      "js_view" => 'VcColumnView'
      ));

}

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_Pricingwrapper extends WPBakeryShortCodesContainer {
    }
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_Pricing_Table extends WPBakeryShortCode {
    }
}
?>