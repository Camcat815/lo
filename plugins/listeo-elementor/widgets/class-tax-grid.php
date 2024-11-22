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
class TaxonomyGrid extends Widget_Base
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
		return 'listeo-taxonomy-grid';
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
		return __('Taxonomy Grid', 'listeo_elementor');
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
		return 'eicon-gallery-grid';
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
		// 	'taxonomy' => '',
		// 'xd' 	=> '',
		// 'only_top' 	=> 'yes',
		// 'autoplay'      => '',
		//          'autoplayspeed'      => '3000',

		$this->add_control(
			'show_as_carousel',
			[
				'label' => __('Show as carousel', 'listeo_elementor'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Carousel', 'your-plugin'),
				'label_off' => __('Standard', 'your-plugin'),
				'return_value' => 'yes',
				'default' => 'no',

			]
		);
		$this->add_control(
			'autoplay',
			[
				'label' => __('Auto Play', 'listeo_elementor'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'your-plugin'),
				'label_off' => __('Hide', 'your-plugin'),
				'return_value' => 'yes',
				'default' => 'yes',

			]
		);


		$this->add_control(
			'autoplayspeed',
			array(
				'label'   => __('Auto Play Speed', 'listeo_elementor'),
				'type'    => Controls_Manager::NUMBER,
				'default' => __('Subtitle', 'listeo_elementor'),
				'min' => 1000,
				'max' => 10000,
				'step' => 500,
				'default' => 3000,
			)
		);

		// setting for slides to show
		$this->add_control(
			'slides_to_show',
			[
				'label' => __('Slides to Show', 'listeo_elementor'),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'default' => 6,
				'condition' => [
					'show_as_carousel' => 'yes',
				],
			]
		);
		$this->add_control(
			'taxonomy',
			[
				'label' => __('Taxonomy', 'elementor-pro'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => [],
				'options' => $this->get_taxonomies(),

			]
		);

		$taxonomy_names = get_object_taxonomies('listing', 'object');
		foreach ($taxonomy_names as $key => $value) {

			$this->add_control(
				$value->name . '_include',
				[
					'label' => __('Include listing from ' . $value->label, 'listeo_elementor'),
					'type' => Controls_Manager::SELECT2,
					'label_block' => true,
					'multiple' => true,
					'default' => [],
					'options' => $this->get_terms($value->name),
					'condition' => [
						'taxonomy' => $value->name,
					],
				]
			);
			$this->add_control(
				$value->name . '_exclude',
				[
					'label' => __('Exclude listings from ' . $value->label, 'listeo_elementor'),
					'type' => Controls_Manager::SELECT2,
					'label_block' => true,
					'multiple' => true,
					'default' => [],
					'options' => $this->get_terms($value->name),
					'condition' => [
						'taxonomy' => $value->name,
					],
				]
			);
		}
		$this->add_control(
			'number',
			[
				'label' => __('Terms to display', 'listeo_elementor'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 199,
				'step' => 1,
				'default' => 6,
			]
		);
		$this->add_control(
			'only_top',
			[
				'label' => __('Show only top terms', 'listeo_elementor'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'your-plugin'),
				'label_off' => __('Hide', 'your-plugin'),
				'return_value' => 'yes',
				'default' => 'yes',

			]
		);


		$this->add_control(
			'show_counter',
			[
				'label' => __('Show listings counter', 'listeo_elementor'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'your-plugin'),
				'label_off' => __('Hide', 'your-plugin'),
				'return_value' => 'yes',
				'default' => 'yes',

			]
		);

		$this->add_control(
			'style',
			[
				'label' => __('Style ', 'listeo_elementor'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'default' => __('Default', 'listeo_elementor'),
					'alt' => __('Alternative', 'listeo_elementor'),

				],
			]
		);



		// $taxonomy_names = get_object_taxonomies( 'listing','object' );

		// foreach ($taxonomy_names as $key => $value) {
		// 	$shortcode_atts[$value->name.'_include'] = '';
		// 	$shortcode_atts[$value->name.'_exclude'] = '';
		// }


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


		$taxonomy_names = get_object_taxonomies('listing', 'object');

		$taxonomy = $settings['taxonomy'];


		if (empty($taxonomy)) {
			$taxonomy = "listing_category";
		}
		$query_args = array(
			'include' => $settings[$taxonomy . '_include'],
			'exclude' => $settings[$taxonomy . '_exclude'],
			'hide_empty' => false,
			'number' => $settings['number'],
		);
		if ($settings['only_top'] == 'yes') {
			$query_args['parent'] = 0;
		}
		$terms = get_terms($settings['taxonomy'], $query_args);

		if (!empty($terms) && !is_wp_error($terms)) {
?>
			<?php if ($settings['show_as_carousel'] == "on") { ?>
				<div data-slides="<?php echo $settings['slides_to_show'] ?>" class="general-carousel" <?php if ($settings['autoplay'] == 'yes') { ?>data-slick='{"slidetoshow": <?php echo $settings['slides_to_show'] ?> ,"autoplay": true, "autoplaySpeed": <?php echo $settings['autoplayspeed']; ?>}' <?php } ?>>
				<?php } else { ?>

					<div class="categories-boxes-container<?php if ($settings['style'] == 'alt') {
																echo "-alt";
															} ?> margin-top-5 margin-bottom-30">
					<?php } ?>
					<!-- Item -->
					<?php
					foreach ($terms as $term) {
						$t_id = $term->term_id;

						// retrieve the existing value(s) for this meta field. This returns an array
						$icon = get_term_meta($t_id, 'icon', true);
						$_icon_svg = get_term_meta($t_id, '_icon_svg', true);
						$_icon_svg_image = wp_get_attachment_image_src($_icon_svg, 'medium');
						if (empty($icon)) {
							$icon = 'fa fa-globe';
						}

					?>
						<a href="<?php echo get_term_link($term); ?>" class="category-small-box<?php if ($settings['style'] == 'alt') {
																									echo "-alt";
																								} ?>">
							<?php if (!empty($_icon_svg_image)) { ?>
								<i class="listeo-svg-icon-box-grid">
									<?php echo listeo_render_svg_icon($_icon_svg); ?>
								</i>
							<?php } else {
								if ($icon != 'emtpy') {
									$check_if_im = substr($icon, 0, 3);
									if ($check_if_im == 'im ') {
										echo ' <i class="' . esc_attr($icon) . '"></i>';
									} else {
										echo ' <i class="fa ' . esc_attr($icon) . '"></i>';
									}
								}
							} ?>
							<h4><?php echo $term->name; ?></h4>
							<?php if ($settings['show_counter'] == "yes") { ?><span class="category-box-counter<?php if ($settings['style'] == 'alt') {
																													echo "-alt";
																												} ?>"><?php $count = listeo_get_term_post_count($settings['taxonomy'], $term->term_id);
																														echo $count ?></span> <?php } ?>
							<?php if ($settings['style'] == 'alt') {
								$cover_id = get_term_meta($term->term_id, '_cover', true);
								if ($cover_id) {
									$cover = wp_get_attachment_image_src($cover_id, 'listeo-blog-post');  ?>
									<img src="<?php echo $cover[0];  ?>">
							<?php }
							} ?>
						</a>

					<?php } ?>
				
					</div>


		<?php }
	}


	protected function get_taxonomies()
	{
		$taxonomies = get_object_taxonomies('listing', 'objects');

		$options = ['' => ''];

		foreach ($taxonomies as $taxonomy) {
			$options[$taxonomy->name] = $taxonomy->label;
		}

		return $options;
	}

	protected function get_terms($taxonomy)
	{
		$taxonomies = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));

		$options = ['' => ''];

		if (!empty($taxonomies)) :
			foreach ($taxonomies as $taxonomy) {
				$options[$taxonomy->term_id] = $taxonomy->name;
			}
		endif;

		return $options;
	}
}
