<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Listeo_Forms_Editor
{

    /**
     * Stores static instance of class.
     *
     * @access protected
     * @var Listeo_Submit The single instance of the class
     */
    protected static $_instance = null;

    /**
     * Returns static instance of class.
     *
     * @return self
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct($version = '1.0.0')
    {

        add_action('admin_menu', array($this, 'add_options_page')); //create tab pages
        add_filter('listeo_core_search_fields', array($this, 'add_listeo_core_search_fields_form_editor'));
        add_filter('listeo_core_search_fields_half', array($this, 'add_listeo_core_search_fields_half_form_editor'));
        add_filter('listeo_core_search_fields_home', array($this, 'add_listeo_core_search_fields_home_form_editor'));
        add_filter('listeo_core_search_fields_homebox', array($this, 'add_listeo_core_search_fields_homebox_form_editor'));
        add_filter('listeo_core_search_fields_header', array($this, 'add_listeo_core_search_fields_header_form_editor'));
        // add_action( 'admin_action_foo_modal_box',  array( $this,'foo_render_action_page') ); 
        add_action('wp_ajax_listeo_form_builder_addnewform', array($this, 'listeo_form_builder_addnewform'));
    }



    function add_listeo_core_search_fields_form_editor($r)
    {
        $fields =  get_option('listeo_sidebar_search_form_fields');
        if (!empty($fields)) {
            $r = $fields;
        }
        return $r;
    }



    function add_listeo_core_search_fields_half_form_editor($r)
    {
        $fields = get_option('listeo_search_on_half_map_form_fields');
        if (!empty($fields)) {
            $r = $fields;
        }
        return $r;
    }

    function add_listeo_core_search_fields_home_form_editor($r)
    {
        $fields = get_option('listeo_search_on_home_page_form_fields');
        if (!empty($fields)) {
            $r = $fields;
        }
        return $r;
    }

    function add_listeo_core_search_fields_homebox_form_editor($r)
    {
        $fields = get_option('listeo_search_on_homebox_page_form_fields');
        if (!empty($fields)) {
            $r = $fields;
        }
        return $r;
    }
    function add_listeo_core_search_fields_header_form_editor($r)
    {
        $fields = get_option('listeo_header_form_fields');
        if (!empty($fields)) {
            $r = $fields;
        }
        return $r;
    }



    /**
     * Add menu options page
     * @since 0.1.0
     */
    public function add_options_page()
    {
        add_submenu_page('listeo-fields-and-form', 'Search Forms', 'Search Forms', 'manage_options', 'listeo-forms-builder', array($this, 'output'));
    }


    public function output()
    {

        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'search_on_home_page';

        $tabs = array(
            'search_on_home_page'       => __('Home Search Form Default', 'listeo-fafe'),
            'search_on_homebox_page'       => __('Home Search Form Boxed', 'listeo-fafe'),
            'sidebar_search'            => __('Sidebar Search', 'listeo-fafe'),
            'search_on_half_map'        => __('Search on Half Map Layout', 'listeo-fafe'),
            'header'        => __('Search in Header', 'listeo-fafe'),

        );
        $forms = get_option('listeo_search_forms', array());
        $predefined_options = apply_filters('listeo_predefined_options', array(
            'listeo_get_listing_types'     => __('Listing Types list', 'wp-job-manager-applications'),

        ));

        if (!empty($_GET['reset-fields']) && !empty($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'reset')) {
            delete_option("listeo_{$tab}_form_fields");
            echo '<div class="updated"><p>' . __('The fields were successfully reset.', 'listeo') . '</p></div>';
        }

        if (!empty($_GET['delete-form']) && !empty($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'reset')) {
            delete_option("listeo_{$tab}_form_fields");
            unset($forms[$tab]);
            update_option('listeo_search_forms', $forms);
            $tab = 'search_on_home_page';
            echo '<div class="updated"><p>' . __('The form was successfully deleted.', 'listeo') . '</p></div>';
        }



        if (!empty($_POST)) { /* add nonce tu*/
            echo $this->form_editor_save($tab);
        }
        // fullwidth
        // sidebar
        // split

        switch ($tab) {
            case 'sidebar_search':
                $default_fields = Listeo_Core_Search::get_search_fields();
                $form_type = 'sidebar';
                break;
            case 'search_on_half_map':
                $default_fields =  Listeo_Core_Search::get_search_fields_half();
                $form_type = 'split';
                break;
            case 'search_on_home_page':
                $default_fields = Listeo_Core_Search::get_search_fields_home();
                $form_type = 'fullwidth';
                break;
            case 'search_on_homebox_page':
                $default_fields = Listeo_Core_Search::get_search_fields_home_box();
                $form_type = 'boxed';
                break;
            case 'header':
                $default_fields = Listeo_Core_Search::get_search_fields_header();

                $form_type = 'fullwidth';
                break;
            default:
                //  $default_fields = Listeo_Core_Search::get_search_fields();
                $default_fields = array();
                break;
        }
        $options = get_option("listeo_{$tab}_form_fields");

        $default_forms = listeo_get_default_search_forms();
        $search_fields = $options ? get_option("listeo_{$tab}_form_fields") :  $default_fields;

        if (array_key_exists($tab, $default_forms)) {
            $default_form = true;
        } else {
            $form_type = $forms[$tab]['type'];
            $default_form = false;
        }


?>
        <!-- The modal / dialog box, hidden somewhere near the footer -->
        <div id="listeo-new-search-form-dialog" class="hidden" style="max-width:800px">

            <p style="max-width:300px;">Search Forms can be used in Listeo Elementor widget for home pages, or in Listeo Search Widget in sidebar</p>
            <div class="form-wrap">

                <form action="" id="listeo-new-search-form">
                    <div class=" form-field">
                        <label for="listeo-new-search-form-name">Search form name</label>
                        <input type=" text" id="listeo-new-search-form-name" name="listeo-new-search-form-name">
                    </div>
                    <div class="form-field">
                        <label for="listeo-new-search-form-type">Search form type</label>
                        <select id="listeo-new-search-form-type" name="listeo-new-search-form-type">
                            <option value="fullwidth">Full width form</option>
                            <option value="boxed">Boxed home form</option>
                            <option value="sidebar">Sidebar</option>
                            <option value="split">Split page</option>
                        </select>
                    </div>
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="Create">
                    <div class="spinner "></div>

                </form>
            </div>
        </div>

        <h2>Listeo Search Forms Editor</h2>
        <div class="listeo-editor-wrap">
            <div class="nav-tab-container">
                <h2 class=" nav-tab-wrapper form-builder">

                    <?php

                    foreach ($tabs as $key => $value) {
                        $active = ($key == $tab) ? 'nav-tab-active' : '';
                        echo '<a class="nav-tab ' . $active . '" href="' . admin_url('admin.php?page=listeo-forms-builder&tab=' . esc_attr($key)) . '">' . esc_html($value) . '</a>';
                    }
                    $listeo_search_forms = get_option('listeo_search_forms');
                    if (is_array($listeo_search_forms) && !empty($listeo_search_forms)) {
                        foreach ($listeo_search_forms as $key => $value) {
                            $active = ($value['id'] == $tab) ? 'nav-tab-active' : '';
                            echo '<a class="nav-tab ' . $active . '" href="' . admin_url('admin.php?page=listeo-forms-builder&tab=' . esc_attr($value['id'])) . '">' . esc_html($value['title']) . '</a>';
                        }
                    }
                    ?>
                    <a id="add-new-listeo-search-form" class="nav-tab">+ Add new</a>

                </h2>
            </div>

            <div class="wrap listeo-forms-builder clearfix">

                <form method="post" id="mainform" action="admin.php?page=listeo-forms-builder&amp;tab=<?php echo esc_attr($tab); ?>">
                    <h3 class="listeo-editor-form-header">
                        <?php
                        foreach ($tabs as $key => $value) {
                            if ($active = ($key == $tab)) {
                                echo esc_html__($value);
                            }
                        } ?>
                        <input name="Submit" type="submit" class="button-primary" value="Save Settings">
                    </h3>
                    <div class="listeo-forms-builder-left">
                        <h3>Active Fields</h3>
                        <div class="form-editor-container main" id="listeo-fafe-forms-editor">
                            <?php
                            $index = 0;
                            foreach ($search_fields as $field_key => $field) {
                                if ($tab != 'search_on_home_page' && !in_array($field['place'], array('adv', 'panel'))) {
                                    $index++;
                                    if (is_array($field)) { ?>
                                        <div class="form_item form_item_<?php echo $field_key; ?>" data-priority="<?php echo  $index; ?>">
                                            <span class="handle dashicons dashicons-editor-justify"></span>
                                            <div class="element_title"><?php echo  esc_attr($field['placeholder']);  ?>
                                                <div class="element_title_edit"><span class="dashicons dashicons-edit"></span> Edit</div>
                                            </div>
                                            <?php include(plugin_dir_path(__DIR__) .  'views/forms-editor/form-edit.php'); ?>

                                            <?php if (isset($field['name']) && $field['name'] != 'listeo_order') { ?>
                                                <div class="remove_item"> <span class="dashicons dashicons-remove"></span> </div>
                                            <?php } ?>

                                        </div>
                                    <?php }
                                } else if ($tab == 'search_on_home_page') {

                                    $index++;
                                    if (is_array($field)) { ?>
                                        <div class="form_item form_item_<?php echo $field_key; ?>" data-priority="<?php echo  $index; ?>">
                                            <span class="handle dashicons dashicons-editor-justify"></span>
                                            <div class="element_title"><?php echo  esc_attr($field['placeholder']);  ?>
                                                <div class="element_title_edit"><span class="dashicons dashicons-edit"></span> Edit</div>
                                            </div>
                                            <?php include(plugin_dir_path(__DIR__) .  'views/forms-editor/form-edit.php'); ?>

                                            <?php if (isset($field['name']) && $field['name'] != 'listeo_order') { ?>
                                                <div class="remove_item"> <span class="dashicons dashicons-remove"></span> </div>
                                            <?php } ?>

                                        </div>
                            <?php }
                                }
                            }  ?>
                            <div class="droppable-helper"></div>
                        </div>


                        <?php if (!in_array($form_type, array('fullwidth', 'boxed'))) : ?>
                            <?php if ($form_type == 'split') { ?>
                                <h3 style="margin-top: 30px; margin-bottom: 20px;">Openable Panels</h3>
                            <?php } else { ?>
                                <h3 style="margin-top: 30px; margin-bottom: 20px;">Foldable elements</h3>
                            <?php } ?>
                            <div class="form-editor-container adv <?php if ($form_type == 'split') {
                                                                        echo "panel";
                                                                    } ?>" id="listeo-fafe-forms-editor-adv">
                                <?php

                                foreach ($search_fields as $field_key => $field) {
                                    if (in_array($field['place'], array('adv', 'panel'))) {
                                        $index++;
                                        if (is_array($field)) { ?>
                                            <div class="form_item form_item_<?php echo $field_key; ?>" data-priority="<?php echo  $index; ?>">
                                                <span class="handle dashicons dashicons-editor-justify"></span>
                                                <div class="element_title"><?php echo  esc_attr($field['placeholder']);  ?>
                                                    <div class="element_title_edit"><span class="dashicons dashicons-edit"></span> Edit</div>
                                                </div>
                                                <?php include(plugin_dir_path(__DIR__) .  'views/forms-editor/form-edit.php'); ?>

                                                <?php if (isset($field['name']) && $field['name'] != 'listeo_order') { ?>
                                                    <div class="remove_item"> <span class="dashicons dashicons-remove"></span> </div>
                                                <?php } ?>

                                            </div>
                                <?php }
                                    }
                                } ?>
                            </div>
                        <?php endif; ?>

                        <input type="submit" class="save-fields button-primary" value="<?php _e('Save Changes', 'listeo'); ?>" />

                        <a href="<?php echo wp_nonce_url(add_query_arg('reset-fields', 1), 'reset'); ?>" class="reset button-secondary"><?php _e('Reset to defaults', 'listeo'); ?></a>
                        <?php if (!$default_form) { ?>
                            <div class="updated listeo-admin-notice" style="margin-top:30px;">
                                <p>This form is set to type:
                                    <?php

                                    switch ($form_type) {
                                        case 'split':
                                            echo "Split page form, it can be used only on half map layout.";
                                            break;
                                        case 'fullwidth':
                                            echo "Full width form, it can be used only on home page search elements or full width search form.";
                                            break;
                                        case 'boxed':
                                            echo "Full width form, it can be used only on home page boxed search elements.";
                                            break;
                                        case 'sidebar':
                                            echo "Sidebar form, it can be used only in sidebar widget";
                                            break;
                                    }
                                    ?>
                                    <br><a href="<?php echo wp_nonce_url(add_query_arg('delete-form', 1), 'reset'); ?>" class="delete   button-link-delete"><?php _e(' Delete this form', 'listeo'); ?></a>
                                </p>

                            </div>
                        <?php } ?>

                    </div>
                    <?php wp_nonce_field('save-fields'); ?>
                    <?php wp_nonce_field('save'); ?>
                </form>
                <?php
                $currency_abbr = get_option('listeo_currency');
                $currency = Listeo_Core_Listing::get_currency_symbol($currency_abbr);
                ?>
                <div class="listeo-forms-builder-right">

                    <h3>Available Elements</h3>

                    <div class="form-editor-available-elements-container">
                        <h4>Standard Elements</h4>
                        <?php
                        $visual_fields = array(
                            'submit' => array(
                                'class'         => '',
                                'name'          => 'submit',
                                'id'          => 'submit',
                                'place'         => 'main',
                                'type'          => 'submit',
                                'placeholder'       => __('Search button', 'listeo'),
                            ),
                            'keyword_search' => array(
                                'class'         => '',
                                'id'            => 'keyword_search',
                                'placeholder'   => __('Keyword search', 'listeo_core'),
                                'name'   => __('Keyword search', 'listeo_core'),
                                'key'           => 'keyword_search',

                                'default'      => '',
                                'priority'      => 1,
                                'place'         => 'main',
                                'type'          => 'text',
                            ),
                            '_instant_booking' => array(
                                'class'         => '',
                                'id'            => '_instant_booking',
                                'placeholder'   => __('Instant Booking ', 'listeo_core'),
                                'name'   => __('Instant Booking', 'listeo_core'),
                                'key'           => '_instant_booking',
                                'default'      => '',
                                'priority'      => 1,
                                'place'         => 'main',
                                'type'          => 'checkbox',
                            ),
                            'date_range' => array(
                                'placeholder'       => __('Check-In - Check-Out', 'listeo_core'),
                                'key'               => '_date_range',
                                'name'              => 'date_range',
                                'id'                => 'date_range',
                                'type'              => 'date-range',
                                'place'             => 'main',
                                'class'             => '',
                                'date_range_type'         => 'rental',
                            ),
                            'location_search' => array(
                                'class'         => '',
                                'placeholder'   => __('Location', 'listeo_core'),
                                'key'           => 'location_search',
                                'name'          => 'location_search',
                                'id'            => 'location_search',
                                'default'      => '',
                                'priority'      => 2,
                                'place'         => 'main',
                                'type'          => 'location',
                            ),

                            'radius' => array(
                                'placeholder'   => __('Radius search', 'listeo_core'),
                                'name'   => __('Radius search', 'listeo_core'),
                                'key'           => 'search_radius',
                                'class'         => '',
                                'css_class'     => 'margin-top-30',
                                'id'            => 'search_radius',
                                'priority'      => 5,
                                'place'         => 'main',
                                'type'          => 'radius',
                                'max'           => '100',
                                'min'           => '1',
                            ),
                            '_rating' => array(
                                'placeholder'   => __('Rating Filter', 'listeo_core'),
                                'name'          => __('Rating Filter', 'listeo_core'),
                                'key'           => '_rating',
                                'class'         => '',
                                'css_class'     => '',
                                'id'            => '_rating',
                                'priority'      => 5,
                                'place'         => 'main',
                                'type'          => 'rating',
                                'max'           => '100',
                                'min'           => '1',
                            ),

                            '_listing_type' => array(
                                'placeholder'   => __('Listing Type', 'listeo'),
                                'id'           => '_listing_type',
                                'key'           => '_listing_type',
                                'name'           => __('Listing Type', 'listeo'),
                                'class'         => '',
                                'priority'      => 1,
                                'place'         => 'main',
                                'default'      => '',
                                'type'          => 'select',
                                'options_source' => 'custom',
                                'options'       => array(
                                    'event' => 'Event',
                                    'service' => 'Service',
                                    'rental' => 'Rental',
                                    'classifieds' => __('Classifieds', 'listeo'),
                                )
                            ),
                            'price_range' => array(
                                'placeholder'   => __('Price Filter', 'realteo'),
                                'name'   => __('Price Filter', 'realteo'),
                                'key'           => '_price',
                                'class'         => '',
                                'css_class'     => '',
                                'id'           => '_price',
                                'priority'      => 9,
                                'place'         => 'main',
                                'type'          => 'slider',
                                'max'           => 'auto',
                                'min'           => 'auto',
                                'unit'          => $currency,
                                'state'          => '',
                            ),

                            '_max_guests' => array(
                                'class'         => '',
                                'placeholder'   => __('Maximum number of guests', 'listeo_core'),
                                'key'           => '_max_guests',
                                'name'          => '_max_guests',
                                'id'            => '_max_guests',
                                'default'      => '',
                                'priority'      => 2,
                                'place'         => 'main',
                                'type'          => 'text',
                            ),
                            '_min_guests' => array(
                                'class'         => '',
                                'placeholder'   => __('Minimum number of guests', 'listeo_core'),
                                'key'           => '_min_guests',
                                'name'          => '_min_guests',
                                'id'            => '_min_guests',
                                'default'      => '',
                                'priority'      => 2,
                                'place'         => 'main',
                                'type'          => 'text',
                            ),
                        );
                        $meta_fields = array(
                            Listeo_Core_Meta_Boxes::meta_boxes_prices(),
                            Listeo_Core_Meta_Boxes::meta_boxes_location(),
                            Listeo_Core_Meta_Boxes::meta_boxes_contact(),
                            Listeo_Core_Meta_Boxes::meta_boxes_event(),
                            Listeo_Core_Meta_Boxes::meta_boxes_service(),
                            Listeo_Core_Meta_Boxes::meta_boxes_rental(),
                            Listeo_Core_Meta_Boxes::meta_boxes_classifieds(),
                            Listeo_Core_Meta_Boxes::meta_boxes_video(),
                            Listeo_Core_Meta_Boxes::meta_boxes_custom(),
                        );

                        foreach ($meta_fields as $key) {
                            foreach ($key['fields'] as $key => $field) {

                                if (in_array($field['type'], array('select', 'repeatable', 'select_multiple', 'multicheck_split'))) {
                                    $visual_fields[$field['id']] = array(


                                        'placeholder'   => $field['name'],
                                        'name'          => $field['name'],
                                        'key'           => $field['id'],
                                        'class'         => '',
                                        'css_class'     => '',
                                        'id'            => $field['id'],
                                        'priority'      => 9,
                                        'place'         => 'main',
                                        'type'          => $field['type'],
                                        'options'       => $field['options'],
                                    );
                                } else {
                                    $visual_fields[$field['id']] = array(
                                        'placeholder'   => $field['name'],
                                        'name'          => $field['name'],
                                        'key'           => $field['id'],
                                        'class'         => '',
                                        'css_class'     => '',
                                        'id'            => $field['id'],
                                        'priority'      => 9,
                                        'place'         => 'main',
                                        'type'          => $field['type'],

                                    );
                                }
                            }
                        }
                        foreach ($visual_fields as $key => $field) {
                            $index++;
                        ?>
                            <div class="form_item" data-priority="0">
                                <span class="handle dashicons dashicons-editor-justify"></span>
                                <div class="element_title"><?php echo  $field['placeholder'];  ?> <div class="element_title_edit"><span class="dashicons dashicons-edit"></span> Edit</div>
                                </div>
                                <?php include(plugin_dir_path(__DIR__) .  'views/forms-editor/form-edit-ready-field.php'); ?>
                                <div class="remove_item"> <span class="dashicons dashicons-remove"></span> </div>
                            </div>
                        <?php }
                        ?>

                        <h4>Taxonomies</h4>
                        <?php
                        $taxonomy_objects = get_object_taxonomies('listing', 'objects');
                        foreach ($taxonomy_objects as $tax) {
                            $index++;
                        ?>
                            <div class="form_item" data-priority="0">
                                <span class="handle dashicons dashicons-editor-justify"></span>
                                <div class="element_title"><?php echo  esc_attr($tax->label);  ?> <div class="element_title_edit"><span class="dashicons dashicons-edit"></span> Edit</div>
                                </div>
                                <?php include(plugin_dir_path(__DIR__) .  'views/forms-editor/form-edit-ready-tax.php'); ?>
                                <div class="remove_item"> <span class="dashicons dashicons-remove"></span> </div>
                            </div>
                        <?php }
                        ?>


                    </div>

                    <button id="listeo-show-names" class="button">Show fields names (adv users only)</button>
                </div>


            </div>
        </div>
<?php

    }


    /**
     * Save the form fields
     */
    private function form_editor_save($tab)
    {

        $field_type             = !empty($_POST['type']) ? array_map('sanitize_text_field', $_POST['type'])                    : array();
        $field_name             = !empty($_POST['name']) ? array_map('sanitize_text_field', $_POST['name'])                    : array();
        //$field_label            = ! empty( $_POST['label'] ) ? array_map( 'sanitize_text_field', $_POST['label'] )                  : array();
        $field_placeholder      = !empty($_POST['placeholder']) ? array_map('wp_kses_post', $_POST['placeholder'])             : array();
        $field_class            = !empty($_POST['class']) ? array_map('sanitize_text_field', $_POST['class'])                  : array();
        $field_css_class        = !empty($_POST['css_class']) ? array_map('sanitize_text_field', $_POST['css_class'])          : array();
        $field_default          = !empty($_POST['default']) ? array_map('sanitize_text_field', $_POST['default'])          : array();
        $field_multi            = !empty($_POST['multi']) ? array_map('sanitize_text_field', $_POST['multi'])                  : array();

        $field_priority         = !empty($_POST['priority']) ? array_map('sanitize_text_field', $_POST['priority'])            : array();
        $field_place            = !empty($_POST['place']) ? array_map('sanitize_text_field', $_POST['place'])                  : array();
        $field_taxonomy         = !empty($_POST['field_taxonomy']) ? array_map('sanitize_text_field', $_POST['field_taxonomy']) : array();
        $field_max              = !empty($_POST['max']) ? array_map('sanitize_text_field', $_POST['max'])                      : array();
        $field_min              = !empty($_POST['min']) ? array_map('sanitize_text_field', $_POST['min'])                      : array();
        $field_unit             = !empty($_POST['unit']) ? array_map('sanitize_text_field', $_POST['unit'])                    : array();
        $field_state            = !empty($_POST['state']) ? array_map('sanitize_text_field', $_POST['state'])                    : array();
        $field_options_cb       = !empty($_POST['options_cb']) ? array_map('sanitize_text_field', $_POST['options_cb'])        : array();
        $field_options_source   = !empty($_POST['options_source']) ? array_map('sanitize_text_field', $_POST['options_source']) : array();
        $field_options          = !empty($_POST['options']) ? $this->sanitize_array($_POST['options'])            : array();
        $field_date_range_type  = !empty($_POST['date_range_type']) ? $this->sanitize_array($_POST['date_range_type'])                         : array();
        $new_fields             = array();
        $index                  = 0;

        foreach ($field_name as $key => $field) {


            $name                = sanitize_title($field_name[$key]);

            $options             = array();
            if (!empty($field_options[$key])) {
                foreach ($field_options[$key] as $op_key => $op_value) {
                    $options[stripslashes($op_value['name'])] = stripslashes($op_value['value']);
                }
            }
            $new_field                       = array();
            $new_field['type']               = isset($field_type[$key]) ? $field_type[$key] : 'text';
            $new_field['name']               = stripslashes($field_name[$key]);
            //$new_field['label']              = $field_label[ $key ];
            $new_field['placeholder']        = stripslashes($field_placeholder[$key]);
            if ($tab != 'search_on_home_page') :
                $new_field['class']              = isset($field_class[$key]) ? $field_class[$key] : '';
            endif;

            $new_field['css_class']          = $field_css_class[$key];
            $new_field['default']            = isset($field_default[$key]) ? $field_default[$key] : false;
            $new_field['multi']              = isset($field_multi[$key]) ? $field_multi[$key] : false;
            $new_field['priority']           = $field_priority[$key];
            $new_field['place']              = isset($field_place[$key]) ? $field_place[$key] : 'main';
            $new_field['taxonomy']           = $field_taxonomy[$key];
            $new_field['max']                = $field_max[$key];
            $new_field['min']                = $field_min[$key];
            $new_field['date_range_type']    = isset($field_date_range_type[$key]) ? $field_date_range_type[$key] : false;

            if (!empty($field_state[$key])) {
                $new_field['state']                = $field_state[$key];
            }


            $new_field['options_source']     = $field_options_source[$key];
            $new_field['options_cb']         = $field_options_cb[$key];
            if (!empty($field_options_cb[$key])) {
                $new_field['options']           = array();
            } else {
                $new_field['options']           = $options;
            }
            $new_field['priority']           = $index++;

            $new_fields[$name]            = $new_field;
        }

        $result = update_option("listeo_{$tab}_form_fields", $new_fields);


        if (true === $result) {
            echo '<div class="updated"><p>' . __('The fields were successfully saved.', 'wp-job-manager-applications') . '</p></div>';
        }
    }

    /**
     * Sanitize a 2d array
     * @param  array $array
     * @return array
     */
    private function sanitize_array($input)
    {
        if (is_array($input)) {
            foreach ($input as $k => $v) {
                $input[$k] = $this->sanitize_array($v);
            }
            return $input;
        } else {
            return sanitize_text_field($input);
        }
    }

    function listeo_form_builder_addnewform()
    {
        $new_form = $_REQUEST['name'];
        $new_form_type = $_REQUEST['type'];
        $id = sanitize_title($new_form);
        //type home/sidebar/split
        // check if form exists
        $default_forms = listeo_get_search_forms();


        if (array_key_exists($id, $default_forms)) {

            wp_send_json_error();
        } else {


            if ($new_form) {
                $forms = get_option('listeo_search_forms', array());

                $forms[$id] = array(
                    'id' => $id,
                    'type' => $new_form_type,
                    'title' => $new_form
                );

                update_option('listeo_search_forms', $forms);
                update_option("listeo_{$id}_form_fields", array());
                wp_send_json_success();
            } else {
                wp_send_json_error();
            }
        }
    }
}
