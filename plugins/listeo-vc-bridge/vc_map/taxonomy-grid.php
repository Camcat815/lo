<?php

/*
 * Iconbox for Visual Composer
 *
 */
add_action( 'vc_before_init', 'listeo_terms_grid_integrateWithVC' );
	
function listeo_terms_grid_integrateWithVC() {

	$taxonomy_names = get_object_taxonomies( 'listing','object' );
	$tax_dropdown = array();
	foreach ($taxonomy_names as $key => $value) {
		$tax_dropdown[$value->label] = $value->name;
	}
	$params = array(

		  array(
          'type' => 'dropdown',
          'heading' => __( 'Style', 'listeo' ),
          'param_name' => 'style',
          'value' => array(
            __( 'Style 1', 'listeo' ) => 'style-1',
            __( 'Style 2', 'listeo' ) => 'alt'
            ),
          'save_always' => true,
        ),
			array(
			'type' 			=> 'dropdown',
			'heading' 		=> esc_html__( 'Taxonomy', 'listeo' ),
			'param_name' 	=> 'taxonomy',
			'description' 	=> esc_html__( 'Choose Taxonomy', 'listeo' ),
			'value' 		=> $tax_dropdown,
			'std' 			=> '',
			'save_always' 	=> true,
	        ),

	        array(
		        "type" => "dropdown",
		        "class" => "",
		        "heading" => esc_html__("Show only top terms", 'workscout'),
		        "param_name" => "only_top",
		        "value" => array(
		         'Only top' => 'yes',     
		         'All' => 'no',
		          ),
		        'save_always' => true,
		        "description" => "Show only top level terms"
		      ), 

	        array(
		        "type" => "dropdown",
		        "class" => "",
		        "heading" => esc_html__("Show terms counter", 'workscout'),
		        "param_name" => "show_counter",
		        "value" => array(
		         'Enable' => 'yes',     
		         'Disable' => 'no',
		          ),
		        'save_always' => true,
		        "description" => "Show number of jobs assigned to this category"
		      ),  

	        array(
	          'type' 		=> 'from_vs_indicatior',
	          'heading' 	=> esc_html__( 'From Visual Composer', 'listeo' ),
	          'param_name' 	=> 'from_vs',
	          'value' 		=> 'yes',
	          'save_always' => true,
	        )
	    );
	foreach ($tax_dropdown as $key => $value) {
		$params[] = array(
		        'type' => 'custom_taxonomy_list_by_ids',
		        'heading' => $key. ' to include',
		        'param_name' => $value.'_include',
		        'taxonomy' => $value,
		        'description' => __( 'Select categories from which posts items will be taken.', 'sphene' ),
		        'dependency'    => array(
			        'element'   => 'taxonomy',
			        'value'     => $value
			    ),	
        	);
		$params[] = array(
		        'type' => 'custom_taxonomy_list_by_ids',
		        'heading' => $key. ' to exclude',
		        'param_name' => $value.'_exclude',
		        'taxonomy' => $value,
		        'description' => __( 'Select categories from which posts items will be taken.', 'sphene' ),
		        'dependency'    => array(
			        'element'   => 'taxonomy',
			        'value'     => $value
			    ),	
        	);
	}

	vc_map( array(
	    "name" 			=> esc_html__("Taxonomy Grid","listeo"),
	    "base" 			=> "taxonomy-grid",
	    'icon' 			=> 'listeo_icon',
	    'description' 	=> esc_html__( 'Grid for taxonomies', 'listeo' ),
	    "category" 		=> esc_html__('Listeo',"listeo"),
	    "params" 		=> $params
	  )
	);	
}
