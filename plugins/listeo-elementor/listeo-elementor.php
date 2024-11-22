<?php
/*
 * Plugin Name: Listeo Elementor
 * Version: 1.6.12
 * Plugin URI: http://www.purethemes.net/
 * Description: Listeo widgets for Elementor
 * Author: Purethemes.net
 * Author URI: http://www.purethemes.net/
 *
 * Text Domain: listeo_elementor
 * Domain Path: /languages/
 *
 * @package WordPress
 * @author Lukasz Girek
 * @since 1.0.0
 */


define( 'ELEMENTOR_LISTEO', __FILE__ );


/**
 * Include the Elementor_Listeo class.
 */
require plugin_dir_path( ELEMENTOR_LISTEO ) . 'class-elementor-listeo.php';