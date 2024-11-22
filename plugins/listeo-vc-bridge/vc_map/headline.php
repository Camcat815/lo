<?php 


/*
 * Headline for Visual Composer
 *
 */
add_action( 'vc_before_init', 'pp_headline_integrateWithVC' );
function pp_headline_integrateWithVC() {

  vc_map( array(
    "name" => esc_html__("Headline","listeo"),
    "base" => "headline",
    'icon' => 'listeo_icon',
    'admin_enqueue_js' => array(get_template_directory_uri().'/vc_templates/js/vc_image_caption_box.js'),
    'admin_enqueue_css' => array(get_template_directory_uri().'/vc_templates/css/listeo_vc_css.css'),
    'description' => esc_html__( 'Header', 'listeo' ),
    "category" => esc_html__('Listeo',"listeo"),
    'js_view' => 'VcHeadlineView',
    "params" => array(
      array(
        'type' => 'textfield',
        'heading' => esc_html__( 'Title', 'listeo' ),
        'param_name' => 'content',
        'description' => esc_html__( 'Enter text which will be used as title', 'listeo' )
        ), 
      array(
        'type' => 'textfield',
        'heading' => esc_html__( 'Subtitle', 'listeo' ),
        'param_name' => 'subtitle',
        'description' => esc_html__( 'Optional  subtitle', 'listeo' )
        ),
      array(
          'type' => 'vc_link',
          'heading' => __( 'URL (Link)', 'listeo' ),
          'param_name' => 'url',
          'description' => __( 'Button link.', 'listeo' )
      ),


      array(
        'type' => 'dropdown',
        'heading' => esc_html__( 'Add extra left and right spacing to the subtitle', 'listeo' ),
        'param_name' => 'extra_space',
        'value' => array(
          'Enable' => 'enable',
          'Disable' => 'disable',
          ),
        'std' => 'disable',
        'save_always' => true,
        
        ), 
      array(
        'type' => 'font_container',
        'param_name' => 'font_container',
        'value' => '',
        'settings'=>array(
             'fields'=>array(
                 'tag'=>'h3',
                 'text_align',
                 'font_size',
                 'line_height',
                 'color',
 
                 'tag_description' => __('Select element tag.','listeo'),
                 'text_align_description' => __('Select text alignment.','listeo'),
                 'font_size_description' => __('Enter font size (add scale like px, %, em etc).','listeo'),
                 'line_height_description' => __('Enter line height (add scale like px, %, em etc).','listeo'),
                 'color_description' => __('Select color for your element.','listeo'),
             ),
         ),
      ),
      array(
        'type' => 'dropdown',
        'heading' => esc_html__( 'Font weight', 'listeo' ),
        'param_name' => 'font_weight',
        'value' => array(
          'normal' => 'normal',
          'bold' => 'bold',
          'bolder' => 'bolder',
          'lighter' => 'lighter',
          '100' => '100',
          '200' => '200',
          '300' => '300',
          '400' => '400',
          '500' => '500',
          '600' => '600',
          '700' => '700',
          '800' => '800',
          '900' => '900',
          ),
        'std' => 'normal',
        'save_always' => true,
        'description' => esc_html__( 'Select font-weight', 'listeo' )
        ), 

      array(
        'type' => 'dropdown',
        'heading' => esc_html__( 'Top margin', 'listeo' ),
        'param_name' => 'margin_top',
        'value' => array(
          '0' => '0',
          '10' => '10',
          '15' => '15',
          '20' => '20',
          '25' => '25',
          '30' => '30',
          '35' => '35',
          '40' => '40',
          '45' => '45',
          '50' => '50',
          '55' => '55',
          '60' => '60',
          '65' => '65',
          '70' => '70',
          ),
        'std' => '15',
        'save_always' => true,
        'description' => esc_html__( 'Choose top margin (in px)', 'listeo' )
        ),
      array(
        'type' => 'dropdown',
        'heading' => esc_html__( 'Bottom margin', 'listeo' ),
        'param_name' => 'margin_bottom',
        'value' => array(
          '0' => '0',
          '10' => '10',
          '15' => '15',
          '20' => '20',
          '25' => '25',
          '30' => '30',
          '35' => '35',
          '40' => '40',
          '45' => '45',
          '50' => '50',
          '55' => '55',
          '60' => '60',
          '65' => '65',
          '70' => '70',
          ),
        'std' => '35',
        'save_always' => true,
        'description' => esc_html__( 'Choose bottom margin (in px)', 'listeo' )
        ),
 
       
      array(
        'type' => 'textfield',
        'heading' => esc_html__( 'Custom Class', 'listeo' ),
        'param_name' => 'custom_class',
        'description' => esc_html__( 'Add Custom Class for headline element', 'listeo' ),
      ),
      array(
        'type' => 'dropdown',
        'heading' => esc_html__( 'Clearfix after?', 'listeo' ),
        'param_name' => 'clearfix',
        'description' => esc_html__( 'Add clearfix after headline, you might want to disable it for some elements, like the recent products carousel.', 'listeo' ),
        'value' => array(
          esc_html__( 'Yes, please', 'listeo' ) => '1',
          esc_html__( 'No, thank you', 'listeo' ) => 'no',
          ),
        'save_always' => true,
        'std' => '1',
        ),
      array(
          'type' => 'from_vs_indicatior',
          'heading' => esc_html__( 'From Visual Composer', 'listeo' ),
          'param_name' => 'from_vs',
          'value' => 'yes',
          'save_always' => true,
          ),    
      ),
  ));
}

?>