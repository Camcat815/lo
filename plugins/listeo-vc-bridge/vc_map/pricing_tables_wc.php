<?php
add_action( 'vc_before_init', 'ws_listeo_pricing_tables_wc_integrateWithVC' );
function ws_listeo_pricing_tables_wc_integrateWithVC() {
  vc_map( array(
    "name" => esc_html__("Pricing table (WC)", 'listeo'),
    "base" => "pricing-tables-wc",
    'icon' => 'listeo_icon',
    'description' => esc_html__( 'WooCommerce Pricing table', 'listeo' ),
    "category" => esc_html__('Listeo', 'listeo'),
    "params" => array(
         array(
          'type' => 'dropdown',
          'heading' => __( 'Order by', 'sphene' ),
          'param_name' => 'orderby',
          'value' => array(
            __( 'Price', 'sphene' ) => 'price',
            __( 'Price desc', 'sphene' ) => 'price-desc',
            __( 'Rating', 'sphene' ) => 'rating',
            __( 'Title', 'sphene' ) => 'title',
            __( 'Popularity', 'sphene' ) => 'popularity',
            __( 'Random', 'sphene' ) => 'random',
            ),
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