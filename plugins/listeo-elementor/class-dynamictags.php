<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
* Register Request Variables Dynamic Tag Group.
*
* Register new dynamic tag group for Request Variables.
*
* @since 1.0.0
* @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
* @return void
*/
function register_custom_fields_dynamic_tag_group( $dynamic_tags_manager ) {

$dynamic_tags_manager->register_group(
'request-variables',
[
'title' => esc_html__( 'Listeo Custom Fields', 'textdomain' )
]
);

}
add_action( 'elementor/dynamic_tags/register', 'register_custom_fields_dynamic_tag_group' );

/**
* Register Server Variable Dynamic Tag.
*
* Include dynamic tag file and register tag class.
*
* @since 1.0.0
* @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
* @return void
*/
function register_custom_fields_dynamic_tag( $dynamic_tags_manager ) {

require_once( __DIR__ . '/dynamic-tags/custom-fields-dynamic-tag.php' );

$dynamic_tags_manager->register( new \Elementor_Dynamic_Tag_Listeo_Custom_Fields );

}
add_action( 'elementor/dynamic_tags/register', 'register_custom_fields_dynamic_tag' );

?>