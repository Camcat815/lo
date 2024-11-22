<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Listeo_Fields_Editor
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


        add_filter('listeo_event_fields', array($this, 'add_listeo_event_fields_from_editor'));
        add_filter('listeo_service_fields', array($this, 'add_listeo_service_fields_from_editor'));
        add_filter('listeo_rental_fields', array($this, 'add_listeo_rental_fields_from_editor'));
        add_filter('listeo_classifieds_fields', array($this, 'add_listeo_classifieds_fields_from_editor'));
        add_filter('listeo_contact_fields', array($this, 'add_listeo_contact_fields_from_editor'));
        add_filter('listeo_location_fields', array($this, 'add_listeo_location_fields_from_editor'));
        add_filter('listeo_custom_fields', array($this, 'add_listeo_custom_fields_from_editor'));
    }


    function add_listeo_contact_fields_from_editor($fields)
    {
        $new_fields =  get_option('listeo_contact_tab_fields');
        if (is_array($new_fields)) {
            $new_fields = array_map(array($this, 'listeo_fields_for_cmb2'), $new_fields);
        }
        if (!empty($new_fields)) {
            $fields['fields'] = $new_fields;
        }
        return $fields;
    }

    function add_listeo_event_fields_from_editor($fields)
    {
        $new_fields =  get_option('listeo_events_tab_fields');
        if (is_array($new_fields)) {
            $new_fields = array_map(array($this, 'listeo_fields_for_cmb2'), $new_fields);
        }
        if (!empty($new_fields)) {
            $fields['fields'] = $new_fields;
        }
        return $fields;
    }
    function add_listeo_custom_fields_from_editor($fields)
    {
        $new_fields =  get_option('listeo_custom_tab_fields');
        if (is_array($new_fields)) {
            $new_fields = array_map(array($this, 'listeo_fields_for_cmb2'), $new_fields);
        }
        if (!empty($new_fields)) {
            $fields['fields'] = $new_fields;
        }
        return $fields;
    }

    function add_listeo_service_fields_from_editor($fields)
    {
        $new_fields =  get_option('listeo_service_tab_fields');
        if (is_array($new_fields)) {
            $new_fields = array_map(array($this, 'listeo_fields_for_cmb2'), $new_fields);
        }
        if (!empty($new_fields)) {
            $fields['fields'] = $new_fields;
        }
        return $fields;
    }
    function add_listeo_classifieds_fields_from_editor($fields)
    {
        $new_fields =  get_option('listeo_classifieds_tab_fields');
        if (is_array($new_fields)) {
            $new_fields = array_map(array($this, 'listeo_fields_for_cmb2'), $new_fields);
        }
        if (!empty($new_fields)) {
            $fields['fields'] = $new_fields;
        }
        return $fields;
    }
    function add_listeo_rental_fields_from_editor($fields)
    {
        $new_fields =  get_option('listeo_rental_tab_fields');
        if (is_array($new_fields)) {
            $new_fields = array_map(array($this, 'listeo_fields_for_cmb2'), $new_fields);
        }
        if (!empty($new_fields)) {
            $fields['fields'] = $new_fields;
        }

        return $fields;
    }

    function add_listeo_location_fields_from_editor($fields)
    {
        $new_fields =  get_option('listeo_locations_tab_fields');

        if (!empty($new_fields)) {
            $fields['fields'] = $new_fields;
        }

        return $fields;
    }

    function listeo_fields_for_cmb2($value)
    {

        if ($value['type'] == 'select') {
            $value['show_option_none'] = true;
        }

        return $value;
    }
    /**
     * Add menu options page
     * @since 0.1.0
     */
    public function add_options_page()
    {
        add_submenu_page('listeo-fields-and-form', 'Listing Fields', 'Listing Fields', 'manage_options', 'listeo-fields-builder', array($this, 'output'));
    }
    public function output()
    {

        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'contact_tab';

        $tabs = array(
            'contact_tab'  => __('Contact Fields', 'listeo-fafe'),
            'locations_tab' => __('Locations Fields', 'listeo-fafe'),
            'events_tab'   => __('Events Fields', 'listeo-fafe'),
            'service_tab'  => __('Service Fields', 'listeo-fafe'),
            'rental_tab'   => __('Rental Fields', 'listeo-fafe'),
            'classifieds_tab'   => __('Classifieds Fields', 'listeo-fafe'),
            // 'prices_tab'   => __( 'Prices fields', 'listeo-fafe' ),
            'custom_tab'   => __('Custom Fields', 'listeo-fafe'),
        );

        if (!empty($_GET['reset-fields']) && !empty($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'reset')) {
            delete_option("listeo_{$tab}_fields");
            echo '<div class="updated"><p>' . __('The fields were successfully reset.', 'listeo') . '</p></div>';
        }

        if (!empty($_POST)) { /* add nonce tu*/

            echo $this->form_editor_save($tab);
        }


        $field_types = apply_filters(
            'listeo_form_field_types',
            array(
                'text'              => __('Text', 'listeo-editor'),
                'datetime'          => __('Date time', 'listeo-editor'),
                'textarea'          => __('Textarea', 'listeo-editor'),
                'repeatable'            => __('Repeatable', 'listeo-editor'),
                'select'            => __('Select', 'listeo-editor'),
                'select_multiple'   => __('Multi Select', 'listeo-editor'),
                'checkbox'          => __('Checkbox', 'listeo-editor'),
                'multicheck_split'  => __('Multi Checkbox', 'listeo-editor'),
                'file'              => __('File upload', 'listeo-editor'),
            )
        );

        // $predefined_options = apply_filters( 'listeo_predefined_options', array(
        //     'listeo_get_property_types'     => __( 'Property Types list', 'listeo-editor' ),
        //     'listeo_get_offer_types_flat'        => __( 'Offer Types list', 'listeo-editor' ),
        //     'listeo_get_rental_period'         => __( 'Rental Period list', 'listeo-editor' ),
        // ) );
        switch ($tab) {
            case 'events_tab':
                $default_fields = Listeo_Core_Meta_Boxes::meta_boxes_event(); //filter listeo_event_fields
                break;
            case 'contact_tab':
                $default_fields = Listeo_Core_Meta_Boxes::meta_boxes_contact();
                break;
            case 'service_tab':
                $default_fields = Listeo_Core_Meta_Boxes::meta_boxes_service();
                break;
            case 'rental_tab':
                $default_fields = Listeo_Core_Meta_Boxes::meta_boxes_rental();
                break;
            case 'classifieds_tab':
                $default_fields = Listeo_Core_Meta_Boxes::meta_boxes_classifieds();
                break;

            case 'locations_tab':
                $default_fields = Listeo_Core_Meta_Boxes::meta_boxes_location();
                break;

            case 'custom_tab':
                $default_fields = Listeo_Core_Meta_Boxes::meta_boxes_custom();
                break;

            default:
                $default_fields = Listeo_Core_Meta_Boxes::meta_boxes_event();
                break;
        }

        $options = get_option("listeo_{$tab}_fields");

        $fields = (!empty($options)) ? get_option("listeo_{$tab}_fields") : $default_fields;
        if (isset($fields['fields'])) {
            $fields = $fields['fields'];
        }

?>

        <h2>Listeo Fields Editor</h2>
        <div class="updated listeo-admin-notice">
            <p style="font-weight: 600; font-size:16px;">This fields are created to extend the custom fields that are default in theme. <br> Only fields added in Events, Service and Rental Tab are automatically displayed in the listing template. <a href=" https://docs.purethemes.net/listeo/knowledge-base/adding-custom-fields-and-displaying-them-on-listing-page/" target="_blank">Learn more ⤴</a> </p>
        </div>
        <div class="listeo-editor-wrap">
            <div class="nav-tab-container">
                <h2 class="nav-tab-wrapper  form-builder">
                    <?php
                    foreach ($tabs as $key => $value) {
                        $active = ($key == $tab) ? 'nav-tab-active' : '';
                        echo '<a class="nav-tab ' . $active . '" href="' . admin_url('admin.php?page=listeo-fields-builder&tab=' . esc_attr($key)) . '">' . esc_html($value) . '</a>';
                    }
                    ?>
                </h2>
            </div>
            <div class="wrap listeo-form-editor listeo-forms-builder listeo-fields-builder">
                <form method="post" id="mainform" action="admin.php?page=listeo-fields-builder&amp;tab=<?php echo esc_attr($tab); ?>">
                    <h3 class="listeo-editor-form-header">
                        <?php
                        foreach ($tabs as $key => $value) {
                            if ($active = ($key == $tab)) {
                                echo esc_html__($value);
                            }
                        } ?>
                        <input name="Submit" type="submit" class="button-primary" value="Save Settings">
                    </h3>
                    <div class="listeo-forms-builder-top">
                        <div class="form-editor-container" id="listeo-fafe-fields-editor" data-clone="<?php
                                                                                                        ob_start();
                                                                                                        $index = -2;
                                                                                                        $field_key = 'clone';
                                                                                                        $field = array(
                                                                                                            'name' => 'clone',
                                                                                                            'id' => '_clone',
                                                                                                            'type' => 'text',
                                                                                                            'invert' => '',
                                                                                                            'desc' => '',
                                                                                                            'options_source' => '',
                                                                                                            'options_cb' => '',
                                                                                                            'options' => array()
                                                                                                        ); ?>
                <div class=" form_item" data-priority="<?php echo  $index; ?>">
                            <span class="handle dashicons dashicons-editor-justify"></span>
                            <div class="element_title"><?php echo esc_attr($field['name']);  ?> <span>(<?php echo $field['type']; ?>)</span> </div>
                            <?php include(plugin_dir_path(__DIR__) . 'views/form-field-edit.php'); ?>
                            <div class="remove_item"> <span class="dashicons dashicons-remove"></span> </div>
                        </div>
                        <?php echo esc_attr(ob_get_clean()); ?>">

                        <?php
                        $index = 0;

                        foreach ($fields as $field_key => $field) {
                            $index++;

                            if (is_array($field)) { ?>
                                <div class="form_item">
                                    <span class="handle dashicons dashicons-editor-justify"></span>
                                    <div class="element_title"><?php echo esc_attr($field['name']);  ?>
                                        <div class="element_title_edit"><span class="dashicons dashicons-edit"></span> Edit</div>
                                    </div>
                                    <?php include(plugin_dir_path(__DIR__) . 'views/form-field-edit.php'); ?>
                                    <div class="remove_item"> <span class="dashicons dashicons-remove"></span> </div>
                                </div>
                        <?php }
                        }  ?>
                        <div class="droppable-helper"></div>
                    </div>
                    <a class="add_new_item button-primary add-field" href="#"><?php _e('Add field', 'listeo'); ?></a>
            </div>

            <?php wp_nonce_field('save-' . $tab); ?>

            <div class="listeo-forms-builder-bottom">

                <input type="submit" class="save-fields button-primary" value="<?php _e('Save Changes', 'listeo'); ?>" />
                <a href="<?php echo wp_nonce_url(add_query_arg('reset-fields', 1), 'reset'); ?>" class="reset button-secondary"><?php _e('Reset to defaults', 'listeo'); ?></a>
            </div>
            </form>
        </div>
        </div>

        <?php wp_nonce_field('save-fields'); ?>
<?php
    }



    private function form_editor_save($tab)
    {

        $field_name             = !empty($_POST['name']) ? array_map('sanitize_textarea_field', $_POST['name'])                     : array();
        $field_id               = !empty($_POST['id']) ? array_map('sanitize_text_field', $_POST['id'])                         : array();
        $field_icon               = !empty($_POST['icon']) ? array_map('sanitize_text_field', $_POST['icon'])                         : array();
        $field_type             = !empty($_POST['type']) ? array_map('sanitize_text_field', $_POST['type'])                     : array();
        $field_invert             = !empty($_POST['invert']) ? array_map('sanitize_text_field', $_POST['invert'])                     : array();
        $field_desc             = !empty($_POST['desc']) ? array_map('sanitize_text_field', $_POST['desc'])                    : array();
        $field_options_cb       = !empty($_POST['options_cb']) ? array_map('sanitize_text_field', $_POST['options_cb'])        : array();
        $field_options_source   = !empty($_POST['options_source']) ? array_map('sanitize_text_field', $_POST['options_source']) : array();
        $field_options          = !empty($_POST['options']) ? $this->sanitize_array($_POST['options'])                : array();
        $new_fields             = array();
        $index                  = 0;

        foreach ($field_name as $key => $field) {

            if (empty($field_name[$key])) {
                continue;
            }
            $name            = sanitize_title($field_id[$key]);
            $options        = array();
            if (!empty($field_options[$key])) {
                foreach ($field_options[$key] as $op_key => $op_value) {
                    $options[stripslashes($op_value['name'])] = stripslashes($op_value['value']);
                }
            }

            $new_field                      = array();
            $new_field['name']              = stripslashes($field_name[$key]);
            $new_field['id']                = $field_id[$key];
            $new_field['icon']              = $field_icon[$key];
            $new_field['type']              = $field_type[$key];
            $new_field['invert']            = isset($field_invert[$key]) ? $field_invert[$key] : false;
            $new_field['desc']              = $field_desc[$key];
            // $new_field['options_source']    = $field_options_source[ $key ];
            // $new_field['options_cb']        = $field_options_cb[ $key ];
            if (!empty($field_options_cb[$key])) {
                $new_field['options']           = array();
            } else {
                $new_field['options']           = $options;
            }

            $new_fields[$name]       = $new_field;
        }

        $result = update_option("listeo_{$tab}_fields", $new_fields);

        if (true === $result) {
            echo '<div class="updated"><p>' . __('The fields were successfully saved.', 'listeo-editor') . '</p></div>';
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
}
