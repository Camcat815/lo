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
class HomeBannerSlider extends Widget_Base
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
		return 'listeo-homebanner-slider';
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
		return __('Home Banner Slider', 'listeo_elementor');
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
		return 'eicon-site-search';
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
			'title',
			array(
				'label'   => __('Title', 'listeo_elementor'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('Find Nearby Attractions', 'listeo_elementor'),
			)
		);
		$this->add_control(
			'subtitle',
			array(
				'label'   => __('Subtitle', 'listeo_elementor'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('Explore top-rated attractions, activities and more!', 'listeo_elementor'),
			)
		);

		$this->add_control(
			'typed',
			[
				'label' => __('Enable Type words effect', 'listeo_elementor'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'your-plugin'),
				'label_off' => __('Hide', 'your-plugin'),
				'return_value' => 'yes',
				'default' => 'yes',

			]
		);
		$this->add_control(
			'typed_text',
			array(
				'label'   => __('Text to displayed in "typed" section, separate by coma', 'listeo_elementor'),
				'label_block' => true,
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __('Attractions, Restaurants, Hotels', 'listeo_elementor'),
			)
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
					'listing' => __('Listing', 'listeo_elementor'),
					'page' => __('Page', 'listeo_elementor'),
					'custom' => __('Custom', 'listeo_elementor'),
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
		$this->add_control(
			'headers_color',
			[
				'label' => esc_html__('Title Color', 'plugin-name'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} h1' => 'color: {{VALUE}}',
					'{{WRAPPER}} h2' => 'color: {{VALUE}}',
					'{{WRAPPER}} h4' => 'color: {{VALUE}}',
					'{{WRAPPER}} h5' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'shapes',
			[
				'label' => __('Enable Shapes animation  effect', 'listeo_elementor'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'your-plugin'),
				'label_off' => __('Hide', 'your-plugin'),
				'return_value' => 'yes',
				'default' => 'yes',

			]
		);

		$this->add_control(
			'home_banner_text_align',
			[
				'label' => __('Text alignment ', 'listeo_elementor'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'center' => __('Center', 'listeo_elementor'),
					'left' 	 => __('Left', 'listeo_elementor'),

				],
				'selectors' => [
					'{{WRAPPER}} .main-search-inner' => 'text-align: {{VALUE}}'
				],


			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => esc_html__('Background', 'plugin-name'),
				'types' => ['classic', 'gradient', 'video'],
				'selector' => '{{WRAPPER}} .main-search-container.plain-color',
			]
		);
		// $this->add_control(
		// 	'background',
		// 	[
		// 		'label' => __( 'Choose Background Image', 'listeo_elementor' ),
		// 		'type' => \Elementor\Controls_Manager::MEDIA,
		// 		'selectors' => [

		// 			'{{WRAPPER}} .main-search-container.plain-color' => 'background-image:  url({{VALUE}})',
		// 		],
		// 	]
		// );





		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'slide_title_1st',
			[
				'label' => __('Title first line', 'plugin-domain'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __('List Title', 'plugin-domain'),
				'label_block' => true,
			]
		);


		$repeater->add_control(
			'list_background',
			[
				'label' => __('Content', 'plugin-domain'),
				'type' => \Elementor\Controls_Manager::MEDIA,

				'show_label' => false,
			]
		);
		$this->add_control(
			'list',
			[
				'label' => __('Slides', 'plugin-domain'),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'prevent_empty' => false,
				'title_field' => '{{{ slide_title_1st }}}',
			]
		);

		$this->add_control(
			'featured_categories_status',
			[
				'label' => __('Show Featured Categories', 'listeo_elementor'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'listeo_elementor'),
				'label_off' => __('Hide', 'listeo_elementor'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);


		$this->add_control(
			'tax-listing_category',
			[
				'label' => __('Show in Featured Categories this terms', 'listeo_elementor'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'default' => [],
				'options' => $this->get_terms('listing_category'),
				'condition' => [
					'featured_categories_status' => 'yes',
				],
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

		$this->add_inline_editing_attributes('title', 'none');
		$this->add_inline_editing_attributes('subtitle', 'none');

		if (!empty($settings['background']['url'])) {

			$background_image = $settings['background']['url'];
		} else {
			$background_image = get_option('listeo_search_bg');
		}

		$video = false;

		if (isset($settings['video']['url']) && !empty($settings['video']['url'])) {
			$video = $settings['video']['url'];
		}

		$classes = array();

		// if($settings['solid_background'] == 'solid'){
		// 	$classes[] = 'solid-bg-home-banner';
		// }
		// if( $settings['search_form_style'] == 'boxed') { 
		// 	$classes[] = 'alt-search-box centered';
		// }

		if ($video) {
			$classes[] = 'dark-overlay';
		}

?>


		<div class="main-search-container  elementor-main-search-container  plain-color main-search-container-with-slider ">
			<div class="main-search-inner">

				<div class="container">
					<div class="row">
						<div class="col-md-12">

							<h1><?php echo $settings['title']; ?> <span class="typed-words"></span></h1>
							<?php if (!empty($settings['subtitle'])) { ?><h4><?php echo $settings['subtitle']; ?></h4><?php } ?>

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

							echo do_shortcode('[listeo_search_form action=' . $home_banner_form_action . ' source="' . $settings['home_banner_form'] . '"  custom_class="main-search-form"]') ?>

						</div>
					</div>

					<?php
					if ($settings['featured_categories_status'] == 'yes') :

						if (isset($settings['tax-listing_category'])) :
							$category = is_array($settings['tax-listing_category']) ? $settings['tax-listing_category'] : array_filter(array_map('trim', explode(',', $settings['tax-listing_category'])));


							if (!empty($category)) : ?>
								<div class="row">
									<div class="col-md-12">
										<h5 class="highlighted-categories-headline"><?php esc_html_e('Or browse featured categories:', 'listeo') ?></h5>


										<div class="highlighted-categories">

											<?php

											foreach ($category as $value) {

												$term = get_term($value, 'listing_category');

												if ($term && !is_wp_error($term)) {
													$icon = get_term_meta($value, 'icon', true);
													$_icon_svg = get_term_meta($value, '_icon_svg', true);
											?>
													<!-- Box -->
													<a href="<?php echo get_term_link($term->slug, 'listing_category'); ?>" class="highlighted-category">
														<?php if (!empty($_icon_svg)) { ?>
															<i>
																<?php echo listeo_render_svg_icon($_icon_svg); ?>
															</i>
														<?php } else if ($icon && $icon != 'empty') { ?><i class="<?php echo esc_attr($icon); ?>"></i><?php }; ?>
														<h4><?php echo esc_html($term->name) ?></h4>
													</a>

											<?php }
											} ?>

										</div>

									</div>
								</div>
					<?php
							endif;
						endif;
					endif; ?>


				</div>

			</div>
			<!-- Main Search Photo Slider -->
			<div class="container msps-container">


				<?php if ($settings['list']) { ?>
					<div class="main-search-photo-slider">
						<div class="msps-slider-container">
							<div class="msps-slider">
								<?php
								foreach ($settings['list'] as $item) { ?>
									<div class="item"><img src="<?php echo $item['list_background']['url']; ?>" class="item" title="<?php echo $item['slide_title_1st']; ?>" /></div>
								<?php }

								?>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if ($settings['shapes'] == 'yes') { ?>
					<div class="msps-shapes" id="scene">

						<div class="layer" data-depth="0.2">
							<svg height="40" width="40" class="shape-a">
								<circle cx="20" cy="20" r="17" stroke-width="4" fill="transparent" stroke="#C400FF" />
							</svg>
						</div>

						<div class="layer" data-depth="0.5">
							<svg width="90" height="90" viewBox="0 0 500 800" class="shape-b">
								<g transform="translate(281,319)">
									<path fill="transparent" style="transform:rotate(25deg)" stroke-width="35" stroke="#F56C83" fill d="M260.162831,132.205081
					A18,18 0 0,1 262.574374,141.205081
					A18,18 0 0,1 244.574374,159.205081H-244.574374
					A18,18 0 0,1 -262.574374,141.205081
					A18,18 0 0,1 -260.162831,132.205081L-15.588457,-291.410162
					A18,18 0 0,1 0,-300.410162
					A18,18 0 0,1 15.588457,-291.410162Z" />
								</g>
							</svg>
						</div>

						<div class="layer" data-depth="0.2" data-invert-x="false" data-invert-y="false" style="z-index: -10">
							<svg height="200" width="200" viewbox="0 0 250 250" class="shape-c">
								<path d="
					    M 0, 30
					    C 0, 23.400000000000002 23.400000000000002, 0 30, 0
					    S 60, 23.400000000000002 60, 30
					        36.599999999999994, 60 30, 60
					        0, 36.599999999999994 0, 30
					" fill="#FADB5F" transform="rotate(
					    -25,
					    100,
					    100
					) translate(
					    0
					    0
					) scale(3.5)"></path>
							</svg>
						</div>


						<div class="layer" data-depth="0.6" style="z-index: -10">
							<svg height="120" width="120" class="shape-d">
								<circle cx="60" cy="60" r="60" fill="#222" />
							</svg>
						</div>


						<div class="layer" data-depth="0.2">
							<svg height="70" width="70" viewBox="0 0 200 200" class="shape-e">
								<path fill="#FF0066" d="M68.5,-24.5C75.5,-0.8,58.7,28.5,33.5,46.9C8.4,65.4,-25.2,73.1,-42.2,60.2C-59.2,47.4,-59.6,13.9,-49.8,-13.7C-40,-41.3,-20,-63.1,5.4,-64.8C30.7,-66.6,61.5,-48.3,68.5,-24.5Z" transform="translate(100 100)" />
							</svg>
						</div>

					</div>
				<?php } else { ?>
					<div class="msps-shapes" id="scene"></div>
				<?php } ?>
			</div>


		</div>
		<?php
		if ($settings['typed'] == 'yes') {

			$typed = $settings['typed_text'];
			$typed_array = explode(',', $typed);
		?>
			<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.9"></script>
			<script>
				var typed = new Typed('.typed-words', {
					strings: <?php echo json_encode($typed_array); ?>,
					typeSpeed: 70,
					backSpeed: 80,
					backDelay: 4000,
					startDelay: 1000,
					loop: true,
					showCursor: true
				});
			</script>

		<?php
		} ?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/parallax/2.1.3/parallax.min.js"></script>

		<script>
			/* ----------------- Start Document ----------------- */
			(function($) {
				"use strict";

				$(document).ready(function() {

					<?php if ($settings['shapes'] == 'yes') { ?>
						$(window).on('load', function() {
							$('.msps-shapes').addClass('shapes-animation')

						});
						const parent = document.getElementById('scene');
						const parallax = new Parallax(parent, {
							limitX: 50,
							limitY: 50,
						});
					<?php } ?>


					$('.msps-slider').slick({
						infinite: true,
						slidesToShow: 1,
						slidesToScroll: 1,
						dots: true,
						arrows: false,
						autoplay: true,
						autoplaySpeed: 5000,
						speed: 1000,
						fade: true,
						cssEase: 'linear'
					});

				});

			})(this.jQuery);
		</script>
<?php

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
