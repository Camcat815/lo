<?php
/*
 * Plugin Name: Listeo - Forms&Fields Editor
 * Version: 1.8.02
 * Plugin URI: http://www.purethemes.net/
 * Description: Editor for Listeo - Directory Plugin from Purethemes.net
 * Author: Purethemes.net
 * Author URI: http://www.purethemes.net/
 * Requires at least: 4.7
 * Tested up to: 4.8.2
 *
 * Text Domain: listeo-fafe
 * Domain Path: /languages/
 *
 * @package WordPress
 * @author Lukasz Girek
 * @since 1.0.0
 */


class Listeo_Forms_And_Fields_Editor
{


    /**
     * The main plugin file.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $file;

    /**
     * The main plugin directory.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $dir;

    /**
     * The plugin assets directory.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $assets_dir;

    /**
     * The plugin assets URL.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $assets_url;


    public $fields;
    public $submit;
    //$this->booking  = Listeo_BookingForm_Editor::instance();
    public $forms;
    public $reviews_criteria;
    public $users;
    public $booking_fields;
    //$this->import_export  = Listeo_Forms_Import_Export::instance();

    public $registration;
    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_version;

    /**
     * Initiate our hooks
     * @since 0.1.0
     */
    public function __construct($file = '', $version = '1.8.0')
    {
        $this->_version = $version;
        add_action('admin_menu', array($this, 'add_options_page')); //create tab pages
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts_and_styles'));

        // Load plugin environment variables
        $this->file = __FILE__;
        $this->dir = dirname($this->file);
        $this->assets_dir = trailingslashit($this->dir) . 'assets';
        $this->assets_url = esc_url(trailingslashit(plugins_url('/assets/', $this->file)));

        include('includes/class-listeo-forms-builder.php');
        include('includes/class-listeo-fields-builder.php');
        include('includes/class-listeo-user-fields-builder.php');

        include('includes/class-listeo-reviews-criteria.php');
        include('includes/class-listeo-submit-builder.php');
        include('includes/class-listeo-booking-fields-builder.php');
        //include( 'includes/class-listeo-bookingform-builder.php' );
        include('includes/class-listeo-registration-form-builder.php');
        //include( 'includes/class-listeo-import-export.php' );

        $this->fields  = Listeo_Fields_Editor::instance();
        $this->submit  = Listeo_Submit_Editor::instance();
        //$this->booking  = Listeo_BookingForm_Editor::instance();
        $this->forms  = Listeo_Forms_Editor::instance();
        $this->reviews_criteria  = Listeo_Reviews_Criteria::instance();
        $this->users  = Listeo_User_Fields_Editor::instance();
        $this->booking_fields  = Listeo_Booking_Fields_Editor::instance();
        //$this->import_export  = Listeo_Forms_Import_Export::instance();

        $this->registration  = Listeo_Registration_Form_Editor::instance();

        add_action('admin_init', array($this, 'listeo_process_settings_export'));
        add_action('admin_init', array($this, 'listeo_process_settings_import'));
        add_action('admin_init', array($this, 'listeo_process_featured_fix'));
        add_action('admin_init', array($this, 'listeo_process_events_fix'));
        add_action('admin_init', array($this, 'listeo_fix_author_dropdown'));

        add_filter('admin_body_class', array($this, 'listeo_editor_admin_classes'));
    }
    function listeo_editor_admin_classes($classes)
    {
        global $current_screen;


        if (in_array($current_screen->base, array(
            'listeo-editor_page_listeo-submit-builder',
            'listeo-editor_page_listeo-forms-builder',
            //'listeo-editor_page_listeo-bookingform-builder',
            'listeo-editor_page_listeo-fields-builder',
            'listeo-editor_page_listeo-reviews-criteria',
            'listeo-editor_page_listeo-user-fields-builder',
            'listeo-editor_page_listeo-booking-fields-builder',
            'listeo-editor_page_listeo-user-registration-builder',
            'listeo-editor_page_listeo-user-fields-registration'

        ), true)) {
            $classes .= ' listeo-editor';
        }

        return $classes;
    }



    public function enqueue_scripts_and_styles($hook)
    {

        if (!in_array(
            $hook,
            array(
                'toplevel_page_listeo-fields-and-form',
                'listeo-editor_page_listeo-submit-builder',
                'listeo-editor_page_listeo-forms-builder',
                //'listeo-editor_page_listeo-bookingform-builder',
                'listeo-editor_page_listeo-fields-builder',
                'listeo-editor_page_listeo-reviews-criteria',
                'listeo-editor_page_listeo-user-fields-builder',
                'listeo-editor_page_listeo-booking-fields-builder',
                'listeo-editor_page_listeo-user-registration-builder',
                'listeo-editor_page_listeo-user-fields-registration'
            )
        )) {
            return;
        }

        wp_enqueue_script('listeo-fafe-script', esc_url($this->assets_url) . 'js/admin.js', array('jquery', 'jquery-ui-droppable', 'jquery-ui-draggable', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-resizable'));

        wp_register_style('listeo-fafe-styles', esc_url($this->assets_url) . 'css/admin.css', array(), $this->_version);
        wp_enqueue_style('listeo-fafe-styles');
        wp_enqueue_style('wp-jquery-ui-dialog');
    }

    /**
     * Add menu options page
     * @since 0.1.0
     */
    public function add_options_page()
    {
        add_menu_page('Listeo Forms and Fields Editor', 'Listeo Editor', 'manage_options', 'listeo-fields-and-form', array($this, 'output'), 'dashicons-forms', 80);

        //add_submenu_page( 'listeo-fields-and-form', 'Property Fields', 'Property Fields', 'manage_options', 'realte-fields-builder', array( $this, 'output' ));
    }

    public function output()
    {
        if (!empty($_GET['import'])) {
            echo '<div class="updated"><p>' . __('The file was imported successfully.', 'listeo') . '</p></div>';
        } ?>
        <div class="metabox-holder listeo-import-export ">
            <div class="postbox">
                <h3><span><?php _e('Export Settings'); ?></span></h3>
                <div class="inside">
                    <p><?php _e('Export fields and forms settings for this site as a .json file. This allows you to easily import the configuration into another site or make a backup.'); ?></p>
                    <form method="post">
                        <p><input type="hidden" name="listeo_action" value="export_settings" /></p>
                        <p>
                            <?php wp_nonce_field('listeo_export_nonce', 'listeo_export_nonce'); ?>
                            <?php submit_button(__('Export'), 'secondary', 'submit', false); ?>
                        </p>
                    </form>
                </div><!-- .inside -->
            </div><!-- .postbox -->

            <div class="postbox">
                <h3><span><?php _e('Import Settings'); ?></span></h3>
                <div class="inside">
                    <p><?php _e('Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.'); ?></p>
                    <form method="post" enctype="multipart/form-data">
                        <p>
                            <input type="file" name="import_file" />
                        </p>
                        <p>
                            <input type="hidden" name="listeo_action" value="import_settings" />
                            <?php wp_nonce_field('listeo_import_nonce', 'listeo_import_nonce'); ?>
                            <?php submit_button(__('Import'), 'secondary', 'submit', false); ?>
                        </p>
                    </form>
                </div><!-- .inside -->
            </div><!-- .postbox -->
            <div class="postbox">
                <h3><span><?php _e('Fix Featured listings '); ?></span></h3>
                <div class="inside">
                    <p><?php _e('We have changed the way featured listings information is storred since version 1.3.3. If you have updated from older version, please run the fix function by clicking button below'); ?></p>
                    <?php $args = array(
                        'post_type' => 'listing',
                        'posts_per_page'   => -1,
                    );
                    $counter = 0;
                    $post_query = new WP_Query($args);
                    $posts_array = get_posts($args);
                    foreach ($posts_array as $post_array) {
                        $featured = get_post_meta($post_array->ID, '_featured', true);

                        if ($featured !== 'on' && $featured !== "0") {
                            $counter++;
                            //update_post_meta($post_array->ID, '_featured', false);
                        }
                    }
                    wp_reset_query();
                    echo "There are " . $counter . " listings to be fixed"; ?>
                    <form method="post" enctype="multipart/form-data">

                        <p>
                            <input type="hidden" name="listeo_action" value="fix_featured" />
                            <?php wp_nonce_field('fix_featured_nonce', 'fix_featured_nonce'); ?>
                            <?php submit_button(__('Fix Featured'), 'secondary', 'submit', false); ?>
                        </p>
                    </form>
                </div><!-- .inside -->
            </div><!-- .postbox -->

            <div class="postbox">
                <h3><span><?php _e('Fix Event Dates '); ?></span></h3>
                <div class="inside">
                    <p><?php _e('We have changed the way search by date works to make it comaptible with Events. If you have updated from older version than 1.4.2, please run the fix function by clicking button below'); ?></p>
                    <?php $args = array(
                        'post_type' => 'listing',
                        'posts_per_page'   => -1,
                        'meta_key' => '_listing_type',
                        'meta_value' => 'event',
                    );
                    $counter = 0;
                    $post_query = new WP_Query($args);
                    $posts_array = get_posts($args);

                    foreach ($posts_array as $post_array) {
                        $_event_date = get_post_meta($post_array->ID, '_event_date_timestamp', true);

                        if (!$_event_date) {
                            $counter++;
                        }
                    }
                    wp_reset_query();
                    echo "There are " . $counter . " listings to be fixed"; ?>
                    <form method="post" enctype="multipart/form-data">

                        <p>
                            <input type="hidden" name="listeo_action" value="fix_events" />
                            <?php wp_nonce_field('fix_events_nonce', 'fix_events_nonce'); ?>
                            <?php submit_button(__('Fix Events'), 'secondary', 'submit', false); ?>
                        </p>

                    </form>
                </div><!-- .inside -->
            </div><!-- .postbox -->


            <div class="postbox">
                <h3><span><?php _e('Fix Users '); ?></span></h3>
                <div class="inside">
                    <?php _e('If you do not see all users available in your Author dropdown, please click the button below'); ?></p>
                    <form method="post" enctype="multipart/form-data">

                        <p>
                            <input type="hidden" name="listeo_action" value="fix_author_dropdown" />
                            <?php wp_nonce_field('fix_author_dropdown_nonce', 'fix_author_dropdown_nonce'); ?>
                            <?php submit_button(__('Fix Author dropdown'), 'secondary', 'submit', false); ?>
                        </p>

                    </form>
                </div>

            </div>
        </div><!-- .metabox-holder -->
<?php
    }


    /**
     * Process a settings export that generates a .json file of the shop settings
     */
    function listeo_process_settings_export()
    {

        if (empty($_POST['listeo_action']) || 'export_settings' != $_POST['listeo_action'])
            return;

        if (!wp_verify_nonce($_POST['listeo_export_nonce'], 'listeo_export_nonce'))
            return;

        if (!current_user_can('manage_options'))
            return;

        $settings = array();
        $settings['property_types']         = get_option('listeo_property_types_fields');
        $settings['property_rental']        = get_option('listeo_rental_periods_fields');
        $settings['property_offer_types']   = get_option('listeo_offer_types_fields');

        $settings['submit']                 = get_option('listeo_submit_form_fields');

        $settings['price_tab']              = get_option('listeo_price_tab_fields');
        $settings['main_details_tab']       = get_option('listeo_main_details_tab_fields');
        $settings['details_tab']            = get_option('listeo_details_tab_fields');
        $settings['location_tab']           = get_option('listeo_locations_tab_fields');

        $settings['sidebar_search']         = get_option('listeo_sidebar_search_form_fields');
        $settings['full_width_search']      = get_option('listeo_full_width_search_form_fields');
        $settings['half_map_search']        = get_option('listeo_search_on_half_map_form_fields');
        $settings['home_page_search']       = get_option('listeo_search_on_home_page_form_fields');
        $settings['home_page_alt_search']   = get_option('listeo_search_on_home_page_alt_form_fields');

        ignore_user_abort(true);

        nocache_headers();
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=listeo-settings-export-' . date('m-d-Y') . '.json');
        header("Expires: 0");

        echo json_encode($settings);
        exit;
    }

    /**
     * Process a settings import from a json file
     */
    function listeo_process_settings_import()
    {

        if (empty($_POST['listeo_action']) || 'import_settings' != $_POST['listeo_action'])
            return;

        if (!wp_verify_nonce($_POST['listeo_import_nonce'], 'listeo_import_nonce'))
            return;

        if (!current_user_can('manage_options'))
            return;

        $extension = end(explode('.', $_FILES['import_file']['name']));

        if ($extension != 'json') {
            wp_die(__('Please upload a valid .json file'));
        }

        $import_file = $_FILES['import_file']['tmp_name'];

        if (empty($import_file)) {
            wp_die(__('Please upload a file to import'));
        }

        // Retrieve the settings from the file and convert the json object to an array.
        $settings = json_decode(file_get_contents($import_file), true);

        update_option('listeo_property_types_fields', $settings['property_types']);
        update_option('listeo_rental_periods_fields', $settings['property_rental']);
        update_option('listeo_offer_types_fields', $settings['property_offer_types']);

        update_option('listeo_submit_form_fields', $settings['submit']);

        update_option('listeo_price_tab_fields', $settings['price_tab']);
        update_option('listeo_main_details_tab_fields', $settings['main_details_tab']);
        update_option('listeo_details_tab_fields', $settings['details_tab']);
        update_option('listeo_locations_tab_fields', $settings['location_tab']);

        update_option('listeo_sidebar_search_form_fields', $settings['sidebar_search']);
        update_option('listeo_full_width_search_form_fields', $settings['full_width_search']);
        update_option('listeo_search_on_half_map_form_fields', $settings['half_map_search']);
        update_option('listeo_search_on_home_page_form_fields', $settings['home_page_search']);
        update_option('listeo_search_on_home_page_alt_form_fields', $settings['home_page_alt_search']);


        wp_safe_redirect(admin_url('admin.php?page=listeo-fields-and-form&import=success'));
        exit;
    }


    function listeo_fix_author_dropdown()
    {
        if (empty($_POST['listeo_action']) || 'fix_author_dropdown' != $_POST['listeo_action'])
            return;

        if (!current_user_can('manage_options'))
            return;

        $ownerusers = get_users(array('role__in' => array('owner', 'seller')));

        foreach ($ownerusers as $user) {
            $user->add_cap('level_1');
        }
    }
    function listeo_process_featured_fix()
    {
        if (empty($_POST['listeo_action']) || 'fix_featured' != $_POST['listeo_action'])
            return;

        if (!wp_verify_nonce($_POST['fix_featured_nonce'], 'fix_featured_nonce'))
            return;

        if (!current_user_can('manage_options'))
            return;

        $args = array(
            'post_type' => 'listing',
            'posts_per_page'   => -1,
        );
        $counter = 0;
        $post_query = new WP_Query($args);
        $posts_array = get_posts($args);
        foreach ($posts_array as $post_array) {
            $featured = get_post_meta($post_array->ID, '_featured', true);

            if ($featured !== 'on' && $featured !== "0") {

                update_post_meta($post_array->ID, '_featured', '0');
            }
        }
    }

    function listeo_process_events_fix()
    {
        if (empty($_POST['listeo_action']) || 'fix_events' != $_POST['listeo_action'])
            return;

        if (!wp_verify_nonce($_POST['fix_events_nonce'], 'fix_events_nonce'))
            return;

        if (!current_user_can('manage_options'))
            return;

        $args = array(
            'post_type' => 'listing',
            'posts_per_page'   => -1,
            'meta_key' => '_listing_type',
            'meta_value' => 'event',
        );

        $counter = 0;

        $post_query = new WP_Query($args);

        $posts_array = get_posts($args);

        foreach ($posts_array as $post_array) {

            $event_date = get_post_meta($post_array->ID, '_event_date', true);

            if ($event_date) {
                $meta_value_date = explode(' ', $event_date, 2);
                if (is_array($meta_value_date)) {

                    $meta_value_stamp = DateTime::createFromFormat(listeo_date_time_wp_format_php(), $meta_value_date[0])->getTimestamp();
                    update_post_meta($post_array->ID, '_event_date_timestamp', $meta_value_stamp);
                }
            }

            $event_date_end = get_post_meta($post_array->ID, '_event_date_end', true);

            if ($event_date_end) {
                $meta_value_date_end = explode(' ', $event_date_end, 2);
                if (is_array($meta_value_date_end)) {
                    $meta_value_stamp_end = DateTime::createFromFormat(listeo_date_time_wp_format_php(), $meta_value_date_end[0])->getTimestamp();
                    update_post_meta($post_array->ID, '_event_date_end_timestamp', $meta_value_stamp_end);
                }
            }
        }
    }
}

$Listeo_Form_Editor = new Listeo_Forms_And_Fields_Editor();
