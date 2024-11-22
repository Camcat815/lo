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
class ListingTitle extends Widget_Base
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
        return 'listeo-listing-title';
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
        return __('Listing Title', 'listeo_elementor');
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

        $this->add_control(
            'categories',
            [
                'label' => __('Show Categories over title', 'listeo_elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'listeo_elementor'),
                'label_off' => __('Off', 'listeo_elementor'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );
        $this->add_control(
            'listing_type_categories',
            [
                'label' => __('Show Listing Type Categories over title', 'listeo_elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'listeo_elementor'),
                'label_off' => __('Off', 'listeo_elementor'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );

        $this->add_control(
            'price_range',
            [
                'label' => __('Show Price range', 'listeo_elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'listeo_elementor'),
                'label_off' => __('Off', 'listeo_elementor'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );

        $this->add_control(
            'address',
            [
                'label' => __('Show Address', 'listeo_elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'listeo_elementor'),
                'label_off' => __('Off', 'listeo_elementor'),
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );
        $this->add_control(
            'reviews',
            [
                'label' => __('Show Reviews', 'listeo_elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'listeo_elementor'),
                'label_off' => __('Off', 'listeo_elementor'),
                'return_value' => 'yes',
                'default' => 'yes',

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
        global $post;
        $listing_type = get_post_meta(get_the_ID(), '_listing_type', true);
        $listing_logo = get_post_meta(get_the_ID(), '_listing_logo', true);
?>


        <div id="titlebar" class="listing-titlebar">
            <?php
            if ($listing_logo) { ?>
                <div class="listing-logo"> <img src="<?php echo $listing_logo; ?>" alt=""></div>
            <?php } ?>
            <div class="listing-titlebar-title">
                <div class="listing-titlebar-tags">
                    <?php
                    if ($settings['categories'] == 'yes') {

                        $terms = get_the_terms(get_the_ID(), 'listing_category');
                        if ($terms && !is_wp_error($terms)) :
                            $categories = array();
                            foreach ($terms as $term) {

                                $categories[] = sprintf(
                                    '<a href="%1$s">%2$s</a>',
                                    esc_url(get_term_link($term->slug, 'listing_category')),
                                    esc_html($term->name)
                                );
                            }

                            $categories_list = join(", ", $categories);
                    ?>
                            <span class="listing-tag">
                                <?php echo ($categories_list) ?>
                            </span>
                    <?php endif;
                    } ?>
                    <?php
                    if ($settings['listing_type_categories'] == 'yes') {
                        switch ($listing_type) {
                            case 'service':
                                $type_terms = get_the_terms(get_the_ID(), 'service_category');
                                $taxonomy_name = 'service_category';
                                break;
                            case 'rental':
                                $type_terms = get_the_terms(get_the_ID(), 'rental_category');
                                $taxonomy_name = 'rental_category';
                                break;
                            case 'event':
                                $type_terms = get_the_terms(get_the_ID(), 'event_category');
                                $taxonomy_name = 'event_category';
                                break;
                            case 'classifieds':
                                $type_terms = get_the_terms(get_the_ID(), 'classifieds_category');
                                $taxonomy_name = 'classifieds_category';
                                break;
                            case 'region':
                                $type_terms = get_the_terms(get_the_ID(), 'region');
                                $taxonomy_name = 'region';
                                break;

                            default:
                                # code...
                                break;
                        }
                        if (isset($type_terms)) {
                            if ($type_terms && !is_wp_error($type_terms)) :
                                $categories = array();
                                foreach ($type_terms as $term) {
                                    $categories[] = sprintf(
                                        '<a href="%1$s">%2$s</a>',
                                        esc_url(get_term_link($term->slug, $taxonomy_name)),
                                        esc_html($term->name)
                                    );
                                }

                                $categories_list = join(", ", $categories);
                    ?>
                                <span class="listing-tag">
                                    <?php echo ($categories_list) ?>
                                </span>
                    <?php endif;
                        }
                    }
                    ?>
                    <?php
                    if ($settings['price_range'] == 'yes') {
                        if (get_the_listing_price_range()) : ?>
                            <span class="listing-pricing-tag"><i class="fa fa-<?php echo esc_attr(get_option('listeo_price_filter_icon', 'tag')); ?>"></i><?php echo get_the_listing_price_range(); ?></span>
                    <?php endif;
                    }

                    do_action('listeo/single-listing/tags');

                    ?>

                </div>
                <h1><?php the_title(); ?></h1>
                <?php
                if ($settings['address'] == 'yes') {


                    if (get_the_listing_address()) : ?>
                        <span>
                            <a href="#listing-location" class="listing-address">
                                <i class="fa fa-map-marker"></i>
                                <?php the_listing_address(); ?>
                            </a>
                        </span> <br>
                        <?php endif;
                }

                if ($settings['reviews'] == 'yes') {
                    
                    if (!get_option('listeo_disable_reviews')) { 
                        $rating = get_post_meta($post->ID, 'listeo-avg-rating', true);
                        if (!$rating && get_option('listeo_google_reviews_instead')) {
                            $reviews = listeo_get_google_reviews($post);
                            if (!empty($reviews['result']['reviews'])) {
                                $rating = number_format_i18n($reviews['result']['rating'], 1);
                                $rating = str_replace(',', '.', $rating);
                            }
                        }

                        
                        if (isset($rating) && $rating > 0) :
                            $rating_type = get_option('listeo_rating_type', 'star');
                            if ($rating_type == 'numerical') { ?>
                                <div class="numerical-rating" data-rating="<?php $rating_value = esc_attr(round($rating, 1));
                                                                            printf("%0.1f", $rating_value); ?>">
                                <?php } else { ?>
                                    <div class="star-rating" data-rating="<?php echo $rating; ?>">
                                    <?php } ?>
                                    <?php $number = listeo_get_reviews_number($post->ID);
                                    if (!get_post_meta($post->ID, 'listeo-avg-rating', true) && get_option('listeo_google_reviews_instead')) {
                                        $number = $reviews['result']['user_ratings_total'];
                                    }  ?>

                                    <div class="rating-counter"><a href="#listing-reviews"><strong><?php esc_attr(round($rating, 1));
                                                                                                    printf("%0.1f", $rating);  ?></strong> (<?php printf(_n('%s review', '%s reviews', $number, 'listeo_elementor'), number_format_i18n($number));  ?>)</a></div>
                                    </div>
                        <?php endif;
                    }
                } ?>

                                </div>

            </div>

    <?php
    }
}
