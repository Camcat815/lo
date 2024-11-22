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

if (!defined('ABSPATH')) {
	// Exit if accessed directly.
	exit;
}

/**
 * Awesomesauce widget class.
 *
 * @since 1.0.0
 */
class TextTyped extends Widget_Base
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
		return 'listeo-text-typed';
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
		return __('Listeo Text Typed', 'listeo_elementor');
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
		return 'eicon-editor-h2';
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
		
	
		// add typography controls for title
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __('Title typography', 'listeo_elementor'),
				'selector' => '{{WRAPPER}} h1',
			]
		);

		// add color setting for tilte
		$this->add_control(
			'title_color',
			[
				'label' => __('Title Color', 'listeo_elementor'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} h1' => 'color: {{VALUE}}',
				],
			]
		);

		//add alignment control for title
		$this->add_control(
			'title_alignment',
			[
				'label' => __('Title Alignment', 'listeo_elementor'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'listeo_elementor'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'listeo_elementor'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'listeo_elementor'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} h1' => 'text-align: {{VALUE}};',
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

		$this->add_inline_editing_attributes('title', 'basic');
		
?>
		<h1 <?php echo $this->get_render_attribute_string('title'); ?>><?php echo wp_kses($settings['title'], array()); ?> <span class="typed-words"></span></h1>
		
<?php
		if($settings['typed'] == 'yes') {

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

}
