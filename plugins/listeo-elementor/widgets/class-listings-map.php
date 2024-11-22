<?php

/**
 * listeo class.
 *
 * @category   Class
 * @package    Elementorlisteo
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

if (! defined('ABSPATH')) {
    // Exit if accessed directly.
    exit;
}

/**
 * listeo widget class.
 *
 * @since 1.0.0
 */
class ListingsMap extends Widget_Base
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
        return 'listeo-listings-map';
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
        return __('Listings Map', 'listeo_elementor');
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
        return 'eicon-editor-h1';
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
        return array('listeo');
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
        $this->start_controls_section(
            'section_content',
            array(
                'label' => __('Content', 'listeo_elementor'),
            )
        );

        $this->add_control(
            'with_search_form',
            [
                'label' => __('Show search form', 'listeo_elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'listeo_elementor'),
                'label_off' => __('Off', 'listeo_elementor'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );


        $search_forms = listeo_get_search_forms_dropdown('fullwidth');
        $this->add_control(
            'home_banner_form',
            [
                'label' => __('Form source ', 'listeo_elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,

                'options' => $search_forms,
                'default' => 'search_on_home_page'


            ]
        );

        $this->add_control(
            'home_banner_form_action',
            [
                'label' => __('Form action ', 'listeo_elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'listing' => __('Listings results', 'listeo_elementor'),
                    'page' => __('Page', 'listeo_elementor'),
                    'custom' => __('Custom link', 'listeo_elementor'),
                ],
                'default' => 'listing'
            ]
        );
        $this->add_control(
            'home_banner_form_action_custom',
            [
                'label' => __('Custom action ', 'listeo_elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'home_banner_form_action' => 'custom',
                ],

            ]
        );
        $this->add_control(
            'home_banner_form_action_page',
            [
                'label' => __('Page ', 'listeo_elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->listeo_get_pages_dropdown(),
                'default' => '',
                'condition' => [
                    'home_banner_form_action' => 'page',
                ],
            ]
        );
        // $this->add_control(
        //     'title',
        //     array(
        //         'label'   => __('Title', 'listeo_elementor'),
        //         'type'    => Controls_Manager::TEXT,
        //         'default' => __('Title', 'listeo_elementor'),
        //     )
        // );
        // $this->add_control(
        //     'subtitle',
        //     array(
        //         'label'   => __('Subtitle', 'listeo_elementor'),
        //         'type'    => Controls_Manager::TEXT,
        //         'default' => '',
        //     )
        // );

        // $this->end_controls_section();

        // $this->start_controls_section(
        //     'style_section',
        //     [
        //         'label' => __('Style Section', 'listeo_elementor'),
        //         'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        //     ]
        // );

        // $this->add_control(
        //     'type',
        //     [
        //         'label' => __('Element tag ', 'listeo_elementor'),
        //         'type' => \Elementor\Controls_Manager::SELECT,
        //         'default' => 'h3',
        //         'options' => [
        //             'h1' => __('H1', 'listeo_elementor'),
        //             'h2' => __('H2', 'listeo_elementor'),
        //             'h3' => __('H3', 'listeo_elementor'),
        //             'h4' => __('H4', 'listeo_elementor'),
        //             'h5' => __('H5', 'listeo_elementor'),
        //         ],
        //     ]
        // );


        // $this->add_control(
        //     'text_align',
        //     [
        //         'label' => __('Text align', 'listeo_elementor'),
        //         'type' => \Elementor\Controls_Manager::CHOOSE,
        //         'options' => [
        //             'left' => [
        //                 'title' => __('Left', 'listeo_elementor'),
        //                 'icon' => 'fa fa-align-left',
        //             ],
        //             'center' => [
        //                 'title' => __('Center', 'listeo_elementor'),
        //                 'icon' => 'fa fa-align-center',
        //             ],
        //             'right' => [
        //                 'title' => __('Right', 'listeo_elementor'),
        //                 'icon' => 'fa fa-align-right',
        //             ],
        //         ],
        //         'default' => 'center',
        //         'toggle' => true,
        //     ]
        // );

        // $this->add_control(
        //     'with_border',
        //     [
        //         'label' => __('With Border', 'listeo_elementor'),
        //         'type' => \Elementor\Controls_Manager::SWITCHER,
        //         'label_on' => __('Show', 'listeo_elementor'),
        //         'label_off' => __('Hide', 'listeo_elementor'),
        //         'return_value' => 'yes',
        //         'default' => 'yes',
        //     ]
        // );

        /* Add the options you'd like to show in this tab here */

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

?>
        <!-- Map
================================================== -->
        <div id="map-container" class="fullwidth-home-map">

            <!-- <div id="map" data-map-zoom="9">
        
    </div> -->

            <?php
            $maps = new \ListeoMaps;
            $maps->show_map();
            ?>

            <div class="main-search-inner">

                <?php if ($settings['with_search_form'] == 'yes') : ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                $home_banner_form_action_page = $settings['home_banner_form_action_page'];
                                $home_banner_form_action_custom = $settings['home_banner_form_action_custom'];
                                $home_banner_form_action = $settings['home_banner_form_action'];
                                if ($home_banner_form_action == 'page' && !empty($home_banner_form_action_page)) {
                                    $home_banner_form_action = get_permalink($home_banner_form_action_page);
                                } else if ($home_banner_form_action == 'custom' && !empty($home_banner_form_action_custom)) {
                                    $home_banner_form_action = $home_banner_form_action_custom;
                                } else {
                                    $home_banner_form_action = get_post_type_archive_link('listing');
                                }

                                ?>
                                <?php

                                echo do_shortcode('[listeo_search_form action=' . $home_banner_form_action . ' source="' . $settings['home_banner_form'] . '" custom_class="main-search-form"]') ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <a href="#" id="show-map-button" class="show-map-button" data-enabled="<?php esc_attr_e('Show Map ', 'listeo'); ?>" data-disabled="<?php esc_attr_e('Hide Map ', 'listeo'); ?>"><?php esc_html_e('Show Map ', 'listeo') ?></a>

            <!-- Scroll Enabling Button -->
            <a href="#" id="scrollEnabling" title="<?php esc_attr_e('Enable or disable scrolling on map', 'listeo') ?>"><?php esc_html_e('Enable Scrolling', 'listeo') ?></a>

        </div>

<?php
    }

    /**
     * Render the widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    function listeo_get_pages_dropdown()
    {
        $pages = get_pages();
        $options = ['' => ''];
        if (!empty($pages)) :
            foreach ($pages as $page) {
                $options[$page->ID] = $page->post_title;
            }
        endif;
        return $options;
    }
}
