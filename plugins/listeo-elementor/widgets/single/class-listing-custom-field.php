<?php

/**
 * Awesomesauce class.
 *
 * @category   Class
 * @package    ElementorAwesomesauce
 * @subpackage WordPress
 * @author     Ben Marshall <me@benmarshall.me>
 * @copyright  2020 Ben Marshall
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link       link(https://www.benmarshall.me/build-custom-elementor-widgets/,
 *             Build Custom Elementor Widgets)
 * @since      1.0.0
 * php version 7.3.9
 */

namespace ElementorListeo\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if (!defined('ABSPATH')) {
    // Exit if accessed directly.
    exit;
}

/**
 * Awesomesauce widget class.
 *
 * @since 1.0.0
 */
class ListingCustomField extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'listeo-listing-custom-field';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Listing Custom Field', 'listeo_elementor');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-text-field';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return array('listeo-single');
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function register_controls()
    {
        // 'title' 		=> 'Service Title',
        // 	    'url' 			=> '',
        // 	    'url_title' 	=> '',

        // 	   	'icon'          => 'im im-icon-Office',
        // 	    'type'			=> 'box-1', // 'box-1, box-1 rounded, box-2, box-3, box-4'
        // 	    'with_line' 	=> 'yes',


        $this->start_controls_section(
            'section_content',
            array(
                'label' => __('Content', 'listeo_elementor'),
            )
        );

       $fields = $this->get_fields();

       $dropdown = [];
       foreach ($fields as $key => $field) {
        $dropdown[$field['id']] = $field['name'];
       }
        // add elementor select control
        $this->add_control(
            'field',
            [
                'label' => __('Field', 'listeo_elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => $dropdown,
                'default' => 'listeo_listing_type',
            ]
        );

        // add elementor text control
        $this->add_control(
            'before',
            [
                'label' => __('Before', 'listeo_elementor'),
                'type' => Controls_Manager::TEXT,
                
            ]
        );

        // add elementor text control
        $this->add_control(
            'after',
            [
                'label' => __('After', 'listeo_elementor'),
                'type' => Controls_Manager::TEXT,
                
            ]
        );
        // add elementor text control
        $this->add_control(
            'separator',
            [
                'label' => __('Separator for multiple values', 'listeo_elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => ', ',
            ]
        );
        $this->add_control(
            'show_as_image',
            [
                'label' => __('For file upload type display as image if possible', 'listeo_elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => ', ',
            ]
        );
        $this->add_control(
            'show_as_list',
            [
                'label' => __('For multiple selection type fields show as list', 'listeo_elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => ', ',
            ]
        );
          

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        
        $selected_custom_field = $settings['field'];
        $field = $this->get_fields()[$selected_custom_field];
        $type = $field['type'];
        $value = get_post_meta(get_the_ID(), $selected_custom_field, true);
        if ($value == '') {
            return;
        }
        
        switch ($type) {
            case 'datetime':
                $meta_value_date = explode(' ', $value, 2);
      
                $date_format = get_option('date_format');

                $meta_value_ = \DateTime::createFromFormat(listeo_date_time_wp_format_php(), $meta_value_date[0]);

                if ($meta_value_ && !is_string($meta_value_)) {
                    $meta_value_stamp = $meta_value_->getTimestamp();
                    $meta_value = date_i18n(get_option('date_format'), $meta_value_stamp);
                } else {
                    $meta_value = $meta_value_date[0];
                }

                if (isset($meta_value_date[1])) {
                    $time = str_replace('-', '', $meta_value_date[1]);
                    $meta_value .= esc_html__(' at ', 'listeo_elementor');
                    $meta_value .= date_i18n(get_option('time_format'), strtotime($time));
                }
                $cfoutput =  $meta_value;
                break;

            case 'textarea':
                $cfoutput =  wpautop(wp_kses_post($value));
                break;
            case 'checkbox':
                if ($value) {
                    $cfoutput =  '<i class="fas fa-check"></i>';
                } else {
                    $cfoutput =  '<i class="fal fa-times-circle"></i>';
                }
                break;
            case 'repeatable':
                $options = $this->get_fields()[$selected_custom_field]['options'];
                $value = get_post_meta(get_the_ID(), $selected_custom_field, true);
                $output = '';
                foreach ($options as $key => $saved_value) {

                    $output.= '<dl><dt>'.$saved_value.'</dt>';
                    
                 
                   foreach ($value as $_key => $_value) {
                        $output .= '<dd>'.$_value[$key].' </dd>';
                   }
                   $output .= '</dl>';
                    $cfoutput =  $output;
                }
                break;
            case 'multicheck_split':
            case 'select_multiple':
            case 'select':
                $options = $this->get_fields()[$selected_custom_field]['options'];
                $i = 0;
                if($type == 'select_multiple' || $type == 'multicheck_split'){
                    $value = get_post_meta(get_the_ID(), $selected_custom_field, false);
                }
                $output = '';

                if(is_array($value)){
                    if ($settings['show_as_list'] == 'yes') {
                        $output .= '<ul class="listeo-custom-field-elementor" id="list-'.$selected_custom_field.'">';
                    }  
                    $last = count($value);
                    foreach ($value as $key => $saved_value) {
                        $i++;
                        if (isset($options[$saved_value]))
                            if ($settings['show_as_list'] == 'yes') {
                            $output .= '<li>'.$options[$saved_value].'</li>';
                            } else {
                            $output .= $options[$saved_value];
                            if ($i >= 1 && $i < $last) : $output .= $settings['separator'];
                            endif;
                            }
                            
                        
                    }
                     
                   
                    if ($settings['show_as_list'] == 'yes') {
                    $output .= '</ul>';
                    }
                    $cfoutput =  $output;
                } else {
                    if (isset($options[$value])) {
                        $cfoutput =  $options[$value];
                    }
                }
               
            
                break;

                case 'file':
                    if($settings['show_as_image'] == 'yes'){
                        $cfoutput =  '<img src="' . $value . '" />';
                    } else {
                        $cfoutput =  '<a href="' . $value . '" /> ' . esc_html__('Download', 'listeo_elementor') . ' ' . wp_basename($value) . ' </a>';
                    }
                
                break;
            default:
                if (filter_var($value, FILTER_VALIDATE_URL) !== false) {

                    $cfoutput = '<a href="' . esc_url($value) . '" target="_blank">' . esc_url($value) . '</a>';
                } else {
                    $cfoutput =  $value;
                }
                
                break;
        }

        if(isset($settings['before']) && $settings['before'] != ''){
            $cfoutput = $settings['before'] . $cfoutput;
        }
        if(isset($settings['after']) && $settings['after'] != ''){
            $cfoutput = $cfoutput . $settings['after'];
        }
        echo $cfoutput;
       
       
       
    }

    function get_fields(){
        $fields = array();
        // choose content field
        $service = \Listeo_Core_Meta_Boxes::meta_boxes_service();

        foreach ($service['fields'] as $key => $field) {

            $fields[$field['id']] = $field;
        }
        $location = \Listeo_Core_Meta_Boxes::meta_boxes_location();

        foreach ($location['fields'] as $key => $field) {
            $fields[$field['id']] = $field;
        }
        $event = \Listeo_Core_Meta_Boxes::meta_boxes_event();
        foreach ($event['fields']  as $key => $field) {
            $fields[$field['id']] = $field;
        }
        $prices = \Listeo_Core_Meta_Boxes::meta_boxes_prices();
        foreach ($prices['fields']  as $key => $field) {
            $fields[$field['id']] = $field;
        }
        $contact = \Listeo_Core_Meta_Boxes::meta_boxes_contact();

        foreach ($contact['fields']  as $key => $field) {
            $fields[$field['id']] = $field;
        }
        $rental = \Listeo_Core_Meta_Boxes::meta_boxes_rental();
        foreach ($rental['fields']  as $key => $field) {
            $fields[$field['id']] = $field;
        }
        $custom = \Listeo_Core_Meta_Boxes::meta_boxes_custom();
        foreach ($custom['fields']  as $key => $field) {
            $fields[$field['id']] = $field;
        } 
        return $fields;
    }
}
