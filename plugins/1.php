<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Listeo_Core_Search class.
 */
class Listeo_Core_Search {


	public $found_posts = 0;
	/**
	 * Constructor
	 */
	public function __construct() {


		add_action( 'pre_get_posts', array( $this, 'pre_get_posts_listings' ), 0 );
		add_action( 'pre_get_posts', array( $this, 'remove_products_from_search' ), 0 );
		// add_filter( 'posts_orderby', array( $this, 'featured_filter' ), 10, 2 );
		// add_filter( 'posts_request', array( $this, 'featured_filter' ), 10, 2 );


		add_filter( 'posts_results', array( $this,'open_now_results_filter' ));
		add_filter( 'found_posts', array( $this,'open_now_results_filter_pagination'), 1, 2 );

		//add_action( 'parse_tax_query', array( $this, 'parse_tax_query_listings' ), 1 );
		add_shortcode( 'listeo_search_form', array($this, 'output_search_form'));

		add_filter( 'query_vars', array( $this, 'add_query_vars' ) );

		add_action( 'parse_query', [ $this, 'admin_search_by_category' ] );
		add_action('restrict_manage_posts',  [ $this, 'admin_filter_search_by_category']);

		if(get_option('listeo_search_name_autocomplete')) {
			add_action( 'wp_print_footer_scripts', array( __CLASS__, 'wp_print_footer_scripts' ), 11 );
	        add_action( 'wp_ajax_listeo_core_incremental_listing_suggest', array( __CLASS__, 'wp_ajax_listeo_core_incremental_listing_suggest' ) );
	        add_action( 'wp_ajax_nopriv_listeo_core_incremental_listing_suggest', array( __CLASS__, 'wp_ajax_listeo_core_incremental_listing_suggest' ) );
	    }

	    add_action( 'wp_ajax_nopriv_listeo_get_listings', array( $this, 'ajax_get_listings' ) );
		add_action( 'wp_ajax_listeo_get_listings', array( $this, 'ajax_get_listings' ) );

		add_action( 'wp_ajax_nopriv_listeo_get_features_from_category', array( $this, 'ajax_get_features_from_category' ) );
		add_action( 'wp_ajax_listeo_get_features_from_category', array( $this, 'ajax_get_features_from_category' ) );

		add_action( 'wp_ajax_nopriv_listeo_get_features_ids_from_category', array( $this, 'ajax_get_features_ids_from_category' ) );
		add_action( 'wp_ajax_listeo_get_features_ids_from_category', array( $this, 'ajax_get_features_ids_from_category' ) );

   		add_action( 'wp_ajax_nopriv_listeo_get_listing_types_from_categories', array( $this, 'ajax_get_listing_types_from_categories' ) );
		add_action( 'wp_ajax_listeo_get_listing_types_from_categories', array( $this, 'ajax_get_listing_types_from_categories' ) );

 		add_filter( 'posts_where', array( $this,'listeo_date_range_filter') );

	}

function admin_filter_search_by_category() {
	global $typenow;
	$post_type = 'listing'; // change to your post type
	$taxonomy  = 'listing_category'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => sprintf( __( 'Show all %s', 'listeo_core' ), $info_taxonomy->label ),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	};
}

function admin_search_by_category($query) {
	global $pagenow;
	$post_type = 'listing'; // change to your post type
	$taxonomy  = 'listing_category'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}


function listeo_date_range_filter( $where ) {

	global $wpdb;
	global $wp_query;
	if (!isset($wp_query) || !method_exists($wp_query, 'get'))
	return;

	$date_range = get_query_var( 'date_range' );

	if(!empty($date_range)) :
//TODO replace / with - if first is day - month- year
		$dates = explode(' - ',$date_range);
		//setcookie('listeo_date_range', $date_range, time()+31556926);
		$date_start = $dates[0];
		$date_end = $dates[1];

		$date_start_object = DateTime::createFromFormat('!'.listeo_date_time_wp_format_php(), $date_start);
		$date_end_object = DateTime::createFromFormat('!'.listeo_date_time_wp_format_php(), $date_end);

		if(!$date_start_object || !$date_end_object) {
			return $where;
		}
		$format_date_start 	= esc_sql($date_start_object->format("Y-m-d H:i:s"));
		$format_date_end 	= esc_sql($date_end_object->modify('+23 hours 59 minutes 59 seconds')->format("Y-m-d H:i:s"));


			// $where .= $GLOBALS['wpdb']->prepare(  " AND {$wpdb->prefix}posts.ID ".
			//     'NOT IN ( '.
			//         'SELECT listing_id '.
			//         "FROM {$wpdb->prefix}bookings_calendar ".
			//         'WHERE
			//     	(( %s > date_start AND %s < date_end )
			//     	OR
			//     	( %s > date_start AND %s < date_end )
			//     	OR
			//     	( date_start >= %s AND date_end <= %s ))
			//     	AND type = "reservation" AND NOT status="cancelled" AND NOT status="expired"
			//     	GROUP BY listing_id '.
			//     ' ) ', $format_date_start, $format_date_start, $format_date_end,  $format_date_end, $format_date_start, $format_date_end );
			$where .= $GLOBALS['wpdb']->prepare(
				" AND {$wpdb->prefix}posts.ID NOT IN (
                SELECT DISTINCT listing_id
                FROM {$wpdb->prefix}bookings_calendar
                WHERE
                (
                    (date_start < %s AND date_end > %s)
                    OR (date_start < %s AND date_end > %s)
                    OR (date_start >= %s AND date_end <= %s)
                    OR (date_start = %s AND date_end = %s)
                )
                AND type = 'reservation'
                AND status NOT IN ('cancelled', 'expired')
            )",
				$format_date_end,
				$format_date_start,
				$format_date_start,
				$format_date_start,
				$format_date_start,
				$format_date_end,
				$format_date_start,
				$format_date_end
			);

		endif;

    return $where;
}

	public function remove_products_from_search($query){

	    /* check is front end main loop content */
	    if(is_admin() || !$query->is_main_query()) return;

	    /* check is search result query */
	    if($query->is_search()){
	    	if(isset($_GET['post_type']) && $_GET['post_type'] == 'product'){

	    	} else {
		  			$post_type_to_remove = 'product';
			        /* get all searchable post types */
			        $searchable_post_types = get_post_types(array('exclude_from_search' => false));

			        /* make sure you got the proper results, and that your post type is in the results */
			        if(is_array($searchable_post_types) && in_array($post_type_to_remove, $searchable_post_types)){
			            /* remove the post type from the array */
			            unset( $searchable_post_types[ $post_type_to_remove ] );
			            /* set the query to the remaining searchable post types */
			            $query->set('post_type', $searchable_post_types);
			        }
	    	}

	    }
	}


	public function open_now_results_filter( $posts ) {

		if(isset($_GET['open_now'])) {
			$filtered_posts = array();

			foreach ( $posts as $post ) {
				if( listeo_check_if_open($post) ){
					$filtered_posts[] = $post;
				}

			}
			$this->found_posts = count($filtered_posts);;
			return $filtered_posts ;
		}

		return $posts;

	}

	function open_now_results_filter_pagination( $found_posts, $query ) {
		if(isset($_GET['open_now'])) {
			// Define the homepage offset...
			$found_posts = $this->found_posts;
		}
		return $found_posts;
	}


	static function wp_print_footer_scripts() {
		?>
	    <script type="text/javascript">
	        (function($){
	        $(document).ready(function(){

	            $( '#keyword_search.title-autocomplete' ).autocomplete({

	                source: function(req, response){
	                    $.getJSON('<?php echo admin_url( 'admin-ajax.php' ); ?>'+'?callback=?&action=listeo_core_incremental_listing_suggest', req, response);
	                },
	                select: function(event, ui) {
	                    window.location.href=ui.item.link;
	                },
	                minLength: 3,
	            });
	         });

	        })(this.jQuery);


	    </script><?php
    }

    static function wp_ajax_listeo_core_incremental_listing_suggest() {

        $suggestions = array();
        $posts = get_posts( array(
            's' => $_REQUEST['term'],
            'post_type'     => 'listing',
        ) );
        global $post;
        $results = array();
        foreach ($posts as $post) {
            setup_postdata($post);
            $suggestion = array();
            $suggestion['label'] =  html_entity_decode($post->post_title, ENT_QUOTES, 'UTF-8');
            $suggestion['link'] = get_permalink($post->ID);

            $suggestions[] = $suggestion;
        }
        // JSON encode and echo
            $response = $_GET["callback"] . "(" . json_encode($suggestions) . ")";
            echo $response;
             // Don't forget to exit!
            exit;

    }

	public function add_query_vars($vars) {

		$new_vars = $this->build_available_query_vars();
	    $vars = array_merge( $new_vars, $vars );
		return $vars;

	}

	public static function build_available_query_vars(){
		$query_vars = array();
		$taxonomy_objects = get_object_taxonomies( 'listing', 'objects' );
        foreach ($taxonomy_objects as $tax) {
        	array_push($query_vars, 'tax-'.$tax->name);
        }



        $service = Listeo_Core_Meta_Boxes::meta_boxes_service();
            foreach ($service['fields'] as $key => $field) {
              	array_push($query_vars, $field['id']);

        }
        $location = Listeo_Core_Meta_Boxes::meta_boxes_location();

            foreach ($location['fields'] as $key => $field) {
              	array_push($query_vars, $field['id']);

        }
        $event = Listeo_Core_Meta_Boxes::meta_boxes_event();
            foreach ($event['fields']  as $key => $field) {
              	array_push($query_vars, $field['id']);

        }
        $prices = Listeo_Core_Meta_Boxes::meta_boxes_prices();
            foreach ($prices['fields']  as $key => $field) {
              	array_push($query_vars, $field['id']);

        }
        $contact = Listeo_Core_Meta_Boxes::meta_boxes_contact();

            foreach ($contact['fields']  as $key => $field) {
              	array_push($query_vars, $field['id']);

        }
        $rental = Listeo_Core_Meta_Boxes::meta_boxes_rental();
            foreach ($rental['fields']  as $key => $field) {
              	array_push($query_vars, $field['id']);

        }
        $classifieds = Listeo_Core_Meta_Boxes::meta_boxes_classifieds();
            foreach ($classifieds['fields']  as $key => $field) {
              	array_push($query_vars, $field['id']);

        }
        $custom = Listeo_Core_Meta_Boxes::meta_boxes_custom();
            foreach ($custom['fields']  as $key => $field) {
              	array_push($query_vars, $field['id']);

        }
        array_push($query_vars, '_price_range');
        array_push($query_vars, '_listing_type');
        //array_push($query_vars, '_verified');
        array_push($query_vars, '_price');
        array_push($query_vars, '_max_guests');
        array_push($query_vars, 'rating-filter');
        array_push($query_vars, '_min_guests');
        array_push($query_vars, '_instant_booking');
		return $query_vars;
	}

	public function pre_get_posts_listings( $query ) {

		if ( is_admin() || ! $query->is_main_query() ){
			return $query;


		}
		if ( !is_admin() && $query->is_main_query() && is_post_type_archive( 'listing' ) ) {
			$per_page = get_option('listeo_listings_per_page',10);
		    $query->set( 'posts_per_page', $per_page );
		    $query->set( 'post_type', 'listing' );
		    $query->set( 'post_status', 'publish' );
		}

		if ( is_tax('listing_category') || is_tax('service_category') || is_tax('event_category') || is_tax('rental_category') || is_tax('listing_feature')  || is_tax('region') ) {

			$per_page = get_option('listeo_listings_per_page',10);
		    $query->set( 'posts_per_page', $per_page );
		}

		if ( is_post_type_archive( 'listing' ) || is_author() || is_tax('listing_category') || is_tax('listing_feature') || is_tax('event_category') || is_tax('service_category') || is_tax('rental_category') || is_tax('region')) {

			$ordering_args = Listeo_Core_Listing::get_listings_ordering_args( );

			if(isset($ordering_args['meta_key']) && $ordering_args['meta_key'] != '_featured' ){
				$query->set('meta_key', $ordering_args['meta_key']);
			}

			$query->set('orderby', $ordering_args['orderby']);
        	$query->set('order', $ordering_args['order'] );

			$keyword = get_query_var( 'keyword_search' );

			$date_range =  (isset($_REQUEST['date_range'])) ? sanitize_text_field(  $_REQUEST['date_range']  ) : '';

			$keyword_search = get_option('listeo_keyword_search', 'search_title');
			$search_mode = get_option('listeo_search_mode', 'exact');
			// make wp_query show only listings that have _event_date meta field value in future



			$keywords_post_ids = array();
			$location_post_ids = array();
			if($search_mode == 'relevance') {
				if ( $keyword  ) {

						// Combine title, content, and meta searches
						$search_terms = array_map('trim', explode('+', $keyword));
						$search_string = implode(' ', $search_terms);

						// Set search parameters for wp_query
						$query->set('s', $search_string);

				}
			} //eof relevance

			if ($search_mode != 'relevance') {

				if ($keyword) {
					global $wpdb;
					// Trim and explode keywords
					if ($search_mode == 'exact') {
						$keywords = array_map('trim', explode('+', $keyword));
					} else {
						$keywords = array_map('trim', explode(' ', $keyword));
					}

					// Setup SQL
					$posts_keywords_sql    = array();
					$postmeta_keywords_sql = array();
					// Loop through keywords and create SQL snippets
					foreach ($keywords as $keyword) {

						# code...
						if (strlen($keyword) > 2) {

							// Create post meta SQL
							if ($keyword_search == 'search_title') {

								$postmeta_keywords_sql[] = " meta_value LIKE '%" . esc_sql($keyword) . "%' AND meta_key IN ('listeo_subtitle','listing_title','listing_description','keywords') ";
							} else {
								$postmeta_keywords_sql[] = " meta_value LIKE '%" . esc_sql($keyword) . "%'";
							}

							// Create post title and content SQL
							$posts_keywords_sql[]    = " post_title LIKE '%" . esc_sql($keyword) . "%' OR post_content LIKE '%" . esc_sql($keyword) . "%' ";
						}
					}


					// Construct the final SQL queries using AND between different keywords
					if (!empty($postmeta_keywords_sql)) {
						$post_ids_meta = $wpdb->get_col("
        SELECT DISTINCT post_id FROM {$wpdb->postmeta}
        WHERE " . implode(' AND ', $postmeta_keywords_sql) . "
    ");
					} else {
						$post_ids_meta = array();
					}

					if (!empty($posts_keywords_sql)) {
						$post_ids_posts = $wpdb->get_col("
        SELECT ID FROM {$wpdb->posts}
        WHERE " . implode(' AND ', $posts_keywords_sql) . "
        AND post_type = 'listing'
    ");
					} else {
						$post_ids_posts = array();
					}


					// Merge and filter duplicates
					$keywords_post_ids = array_unique(array_merge($post_ids_meta, $post_ids_posts));
					if (empty($keywords_post_ids)) {
						$keywords_post_ids = array(0);
					}
				}
			}

			$location = get_query_var( 'location_search' );

			if( $location ) {

				$radius = get_query_var('search_radius');
	        	if(empty($radius) && get_option('listeo_radius_state') == 'enabled') {
	        		$radius = get_option('listeo_maps_default_radius');
	        	}
				$radius_type = get_option('listeo_radius_unit','km');
				$geocoding_provider = get_option('listeo_geocoding_provider','google');
				if($geocoding_provider == 'google'){
					$radius_api_key = get_option( 'listeo_maps_api_server' );
				} else {
					$radius_api_key = get_option( 'listeo_geoapify_maps_api_server' );
				}

				if(!empty($location) && !empty($radius) && !empty($radius_api_key)) {

					//search by google

					$latlng = listeo_core_geocode($location);

					$nearbyposts = listeo_core_get_nearby_listings($latlng[0], $latlng[1], $radius, $radius_type );

					listeo_core_array_sort_by_column($nearbyposts,'distance');
					$location_post_ids = array_unique(array_column($nearbyposts, 'post_id'));

					if(empty($location_post_ids)) {
						$location_post_ids = array(0);
					}

				} else {

					//search by text
					global $wpdb;
					// Trim and explode keywords
					$locations = array_map('trim', explode(',', $location));

					// Setup SQL
					$posts_locations_sql = array();
					$postmeta_locations_sql = array();
					// Loop through keywords and create SQL snippets

					if (get_option('listeo_search_only_address', 'off') == 'on') {
						// Directly using the location with sensitivity to special characters
						$postmeta_locations_sql[] = $wpdb->prepare("meta_value LIKE %s AND meta_key = '_address'", '%' . $wpdb->esc_like($locations[0]) . '%');
						$postmeta_locations_sql[] = $wpdb->prepare("meta_value LIKE %s AND meta_key = '_friendly_address'", '%' . $wpdb->esc_like($locations[0]) . '%');
					} else {
						// Create post meta SQL
						$postmeta_locations_sql[] = $wpdb->prepare("meta_value LIKE %s", '%' . $wpdb->esc_like($locations[0]) . '%');
						// Create post title and content SQL
						$posts_locations_sql[] = $wpdb->prepare("post_title LIKE %s OR post_content LIKE %s", '%' . $wpdb->esc_like($locations[0]) . '%', '%' . $wpdb->esc_like($locations[0]) . '%');
					}

					// Get post IDs from post meta search
					$post_ids = $wpdb->get_col("
    SELECT DISTINCT post_id FROM {$wpdb->postmeta}
    WHERE " . implode(' OR ', $postmeta_locations_sql) . "
");

					// Merge with post IDs from post title and content search
					if (get_option('listeo_search_only_address', 'off') == 'on') {
						$location_post_ids = array_merge($post_ids, array(0));
					} else {
						$location_post_ids = array_merge($post_ids, $wpdb->get_col("
        SELECT ID FROM {$wpdb->posts}
        WHERE (" . implode(' OR ', $posts_locations_sql) . ")
        AND post_type = 'listing'
        AND post_status = 'publish'
    "), array(0));
					}
