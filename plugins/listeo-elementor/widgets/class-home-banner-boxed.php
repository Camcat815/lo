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
class HomeBannerBoxed extends Widget_Base
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
		return 'listeo-homebanner-boxed';
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
		return __('Home Search Banner Boxed', 'listeo_elementor');
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
		$search_forms = listeo_get_search_forms_dropdown('boxed');
		$this->add_control(
			'home_banner_form',
			[
				'label' => __('Form source ', 'listeo_elementor'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $search_forms,
				'default' => 'search_on_homebox_page'


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
				'label_on' => __('On', 'listeo_elementor'),
				'label_off' => __('Off', 'listeo_elementor'),
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


		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => esc_html__('Background', 'plugin-name'),
				'types' => ['classic', 'gradient', 'video'],
				'selector' => '{{WRAPPER}} .main-search-container',
			]
		);
		$this->add_control(
			'background_overlay_type',
			[
				'label' => __('Background Overlay type', 'listeo_elementor'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => __('None', 'listeo_elementor'),
					'container-overlay-solid' => __('Solid color', 'listeo_elementor'),
					'container-overlay-gradient' 	 => __('Gradient', 'listeo_elementor'),

				],


			]
		);
		$this->add_control(
			'overlay_gradient',
			[
				'label' => __('Overlay on homepage search banner', 'plugin-domain'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'alpha' => false,
				'default' => '#00000080',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .alt-search-box.container-overlay-gradient.main-search-container:before' => 'background: linear-gradient(to right, {{VALUE}}F2 20%, {{VALUE}}B3 70%, {{VALUE}}00 95%) !important; display:block;',
				],
				'condition' => [
					'background_overlay_type' => 'container-overlay-gradient',
				],
			]
		);
		$this->add_control(
			'overlay_solid',
			[
				'label' => __('Overlay on homepage search banner', 'plugin-domain'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'alpha' => true,
				'default' => '#00000080',
				'separator' => 'before',
				'selectors' => [

					'{{WRAPPER}} .alt-search-box.container-overlay-solid.main-search-container:before' => 'background:  {{VALUE}} !important; display:block;',
				],
				'condition' => [
					'background_overlay_type' => 'container-overlay-solid',
				],
			]
		);



		// $this->add_control(
		// 	'background',
		// 	[
		// 		'label' => __('Choose Background Image', 'listeo_elementor'),
		// 		'type' => \Elementor\Controls_Manager::MEDIA,

		// 	]
		// );
		// $this->add_control(
		// 	'video',
		// 	[
		// 		'label' => __('Choose Video', 'listeo_elementor'),
		// 		'type' => \Elementor\Controls_Manager::MEDIA,

		// 	]
		// );
		// $this->add_control(
		// 	'video_poster',
		// 	[
		// 		'label' => __('Choose Video Poster Image', 'listeo_elementor'),
		// 		'type' => \Elementor\Controls_Manager::MEDIA,
		// 		'default' => [
		// 			'url' => \Elementor\Utils::get_placeholder_image_src(),
		// 		]
		// 	]
		// );


		// $this->add_control(
		// 	'background_overlay_type',
		// 	[
		// 		'label' => __('Background Overlay type', 'listeo_elementor'),
		// 		'type' => \Elementor\Controls_Manager::SELECT,
		// 		'default' => 'container-overlay-solid',
		// 		'options' => [
		// 			'container-overlay-solid' => __('Solid color', 'listeo_elementor'),
		// 			'container-overlay-gradient' 	 => __('Gradient', 'listeo_elementor'),

		// 		],


		// 	]
		// );
		// $this->add_control(
		// 	'overlay_gradient',
		// 	[
		// 		'label' => __('Overlay on homepage search banner', 'plugin-domain'),
		// 		'type' => \Elementor\Controls_Manager::COLOR,
		// 		'alpha' => false,
		// 		'default' => '#000',
		// 		'separator' => 'before',
		// 		'selectors' => [
		// 			'{{WRAPPER}} .container-overlay-gradient.main-search-container:before' => 'background: linear-gradient(to right, {{VALUE}}F2 20%, {{VALUE}}B3 70%, {{VALUE}}00 95%)',
		// 		],
		// 		'condition' => [
		// 			'background_overlay_type' => 'container-overlay-gradient',
		// 		],
		// 	]
		// );
		// $this->add_control(
		// 	'overlay_solid',
		// 	[
		// 		'label' => __('Overlay on homepage search banner', 'plugin-domain'),
		// 		'type' => \Elementor\Controls_Manager::COLOR,
		// 		'alpha' => true,
		// 		'default' => '#00000080',
		// 		'separator' => 'before',
		// 		'selectors' => [

		// 			'{{WRAPPER}} .container-overlay-solid.main-search-container:before' => 'background:  {{VALUE}}',
		// 		],
		// 		'condition' => [
		// 			'background_overlay_type' => 'container-overlay-solid',
		// 		],
		// 	]
		// );
		// $this->add_control(
		// 	'title_color',
		// 	[
		// 		'label' => __('Overlay on homepage search banner', 'plugin-domain'),
		// 		'type' => \Elementor\Controls_Manager::COLOR,
		// 		'alpha' => false,
		// 		'separator' => 'before',
		// 		'selectors' => [
		// 			'{{WRAPPER}} .main-search-container:before' => 'background: linear-gradient(to right, {{VALUE}}F2 20%, {{VALUE}}B3 70%, {{VALUE}}00 95%)',
		// 		],
		// 	]
		// );	


		// $this->add_control(
		// 	'home_full_screen',
		// 	[
		// 		'label' => __( 'Full screen banner', 'listeo_elementor'  ),
		// 		'type' => \Elementor\Controls_Manager::SWITCHER,
		// 		'label_on' => __( 'Show', 'listeo_elementor' ),
		// 		'label_off' => __( 'Hide', 'listeo_elementor' ),
		// 		'return_value' => 'yes',
		// 		'default' => 'no',
		// 	]
		// );






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
		$classes[] = $settings['background_overlay_type'];
		$classes[] = 'full-height';

?>

		<div class="main-search-container elementor-main-search-container  <?php echo implode(" ", $classes); ?> alt-search-box centered">
			<div class="main-search-inner">

				<div class="container">
					<div class="row">
						<div class="col-md-12">

							<div class="main-search-input">

								<div class="main-search-input-headline">
									<h1><?php echo $settings['title']; ?> <span class="typed-words"></span></h1>
									<?php if (!empty($settings['subtitle'])) { ?><h4><?php echo $settings['subtitle']; ?></h4><?php } ?>

								</div>

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

				</div>
				<?php if ($video) {
					if (isset($settings['video_poster']['url']) && !empty(isset($settings['video_poster']['url']))) {
						$video_poster = $settings['video_poster']['url'];
					}
				?>
					<!-- Video -->
					<div class="video-container">

						<video <?php if (isset($video_poster)) ?> poster="<?php echo $video_poster ?>" loop autoplay muted>
							<source src="<?php echo $video ?>" type="video/mp4">

						</video>
					</div>
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
		}
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
