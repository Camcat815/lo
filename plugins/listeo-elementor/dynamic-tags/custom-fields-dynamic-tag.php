<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor Dynamic Tag - Server Variable
 *
 * Elementor dynamic tag that returns a server variable.
 *
 * @since 1.0.0
 */
class Elementor_Dynamic_Tag_Listeo_Custom_Fields extends \Elementor\Core\DynamicTags\Tag
{

    /**
     * Get dynamic tag name.
     *
     * Retrieve the name of the server variable tag.
     *
     * @since 1.0.0
     * @access public
     * @return string Dynamic tag name.
     */
    public function get_name()
    {
        return 'server-variable';
    }

    /**
     * Get dynamic tag title.
     *
     * Returns the title of the server variable tag.
     *
     * @since 1.0.0
     * @access public
     * @return string Dynamic tag title.
     */
    public function get_title()
    {
        return esc_html__('Listeo Custom Fields', 'listeo_elementor');
    }

    /**
     * Get dynamic tag groups.
     *
     * Retrieve the list of groups the server variable tag belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Dynamic tag groups.
     */
    public function get_group()
    {
        return ['request-variables'];
    }

    /**
     * Get dynamic tag categories.
     *
     * Retrieve the list of categories the server variable tag belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Dynamic tag categories.
     */
    public function get_categories()
    {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::URL_CATEGORY
        ];
    }

    /**
     * Register dynamic tag controls.
     *
     * Add input fields to allow the user to customize the server variable tag settings.
     *
     * @since 1.0.0
     * @access protected
     * @return void
     */
    protected function register_controls()
    {
       
        $fields = array();
        // choose content field
        $service = \Listeo_Core_Meta_Boxes::meta_boxes_service();

        foreach ($service['fields'] as $key => $field) {

            $fields[$field['id']] = $field['name'];
        }
        $location = \Listeo_Core_Meta_Boxes::meta_boxes_location();

        foreach ($location['fields'] as $key => $field) {
            $fields[$field['id']] = $field['name'];
        }
        $event = \Listeo_Core_Meta_Boxes::meta_boxes_event();
        foreach ($event['fields']  as $key => $field) {
            $fields[$field['id']] = $field['name'];
        }
        $prices = \Listeo_Core_Meta_Boxes::meta_boxes_prices();
        foreach ($prices['fields']  as $key => $field) {
            $fields[$field['id']] = $field['name'];
        }
        $contact = \Listeo_Core_Meta_Boxes::meta_boxes_contact();

        foreach ($contact['fields']  as $key => $field) {
            $fields[$field['id']] = $field['name'];
        }
        $rental = \Listeo_Core_Meta_Boxes::meta_boxes_rental();
        foreach ($rental['fields']  as $key => $field) {
            $fields[$field['id']] = $field['name'];
        }
        $custom = \Listeo_Core_Meta_Boxes::meta_boxes_custom();
        foreach ($custom['fields']  as $key => $field) {
            $fields[$field['id']] = $field['name'];
        } 
        $this->add_control(
            'selected_custom_field',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'label' => esc_html__('Custom Field', 'textdomain'),
                'options' => $fields,
            ]
        );
    }

    /**
     * Render tag output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function render()
    {
        $selected_custom_field = $this->get_settings('selected_custom_field');

        if (!$selected_custom_field) {
            return;
        }

        $value = get_post_meta(get_the_ID(), $selected_custom_field, true);
        if(is_array($value)){
            $value = implode(', ', $value);
        }
        echo wp_kses_post($value);
    }
}
