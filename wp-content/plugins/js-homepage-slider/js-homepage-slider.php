<?php
/*
Plugin Name: Home Page Slider
Plugin URL: hhttps://github.com/jnschrag/wp-slider
Description: Creates a custom menu with a featured image on the main page.
Version: 0.1
Author: Jacque Schrag
Author URI: http://jschrag.com
Text Domain: js_hps
Domain Path: languages
*/

class hps_custom_menu {


	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// load the plugin translation files
		add_action( 'init', array( $this, 'textdomain' ) );

		// load CSS & JavaScript
		add_action( 'wp_enqueue_scripts', array($this, 'js_slider_scripts' ));

		/*----------  Backend Filters  ----------*/

		// Add Link to Slider in Admin Panel
		add_action('admin_menu', array($this, 'add_slider_admin_menu_item'));

		// Register Menu Location
		add_action( 'after_setup_theme', array($this, 'js_hps_register_menu'));
		
		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'js_hps_add_custom_nav_fields' ) );

		// save menu custom fields
		add_action( 'wp_update_nav_menu_item', array( $this, 'js_hps_update_custom_nav_fields'), 10, 3 );
		
		// edit menu walker
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'js_hps_edit_walker'), 10, 2 );

		/*----------  Frontend Filters  ----------*/
		/**
		
			TODO:
			- Create Options Panel
		
		 */
		
		// Display Slider
		add_action('wp_head', array($this, 'js_hps_display_slider'));
		

	} // end constructor
	
	
	/**
	 * Load the plugin's text domain
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function textdomain() {
		load_plugin_textdomain( 'js_hps', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function js_slider_scripts() {
		wp_enqueue_style( 'js-slider-style', plugins_url( 'assets/css/styles.css', __FILE__ ) );
	}

	/**
	 * Register Home Page Slider in Admin Menu bar
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function add_slider_admin_menu_item() {
		$theme_locations = get_nav_menu_locations();
		$menu_obj = get_term( $theme_locations['home-page-slider'], 'nav_menu' );
		$menuID = $menu_obj->term_id;

	  add_menu_page(__('Home Page Slider'), __('Home Page Slider'), 'edit_theme_options', 'nav-menus.php?action=edit&menu='.$menuID, '', 'dashicons-images-alt2', 58);
	}

	/**
	 * Register new navigation menu for slider
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function js_hps_register_menu() {
	    register_nav_menus( array(
			'home-page-slider' => __( 'Home Page Slider', 'textdomain' )
		) );
	}
	
	/**
	 * Add custom fields to $item nav object
	 * in order to be used in custom Walker
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function js_hps_add_custom_nav_fields( $menu_item ) {
	
	    $menu_item->featured_image = get_post_meta( $menu_item->ID, '_menu_item_featured_image', true );
	    return $menu_item;
	    
	}
	
	/**
	 * Save menu custom fields
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function js_hps_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
	
	    // Check if element is properly sent
	    if ( is_array( $_REQUEST['menu-item-featured-image']) ) {
	        $featured_image_value = $_REQUEST['menu-item-featured-image'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_featured_image', $featured_image_value );
	    }
	    
	}
	
	/**
	 * Define new Walker edit
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function js_hps_edit_walker($walker,$menu_id) {
		$locations = get_nav_menu_locations();
		$sliderMenuID = $locations['home-page-slider'];

		if($menu_id == $sliderMenuID) {
	    	return 'Walker_Nav_Menu_Edit_Custom';
	    }
	    else {
	    	return 'Walker_Nav_Menu_Edit';
	    }
	    
	}

	/**
	 * Display the Slider on the front end
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function js_hps_display_slider() {
		$location = 'home-page-slider';

		if ( has_nav_menu( $location ) && is_front_page() ) {
		
			$menu_items = wp_get_nav_menu_items($location);
			$walker = new Slider_Menu_With_Description;

			// Get the feature image, title, description, and url of the first menu item that has an image
			$feat_image = "";
			$feat_title = "";
			$feat_description = "";
			$feat_link = "";
			$feat_id = "";

			// Loop through the menu to get the featured item
			foreach($menu_items as $key => $itemObj) {

				if($itemObj->featured_image) {
					$feat_image = $itemObj->featured_image;
					$feat_title = $itemObj->title;
					$feat_description = $itemObj->description ?: $itemObj->type_label;
					$feat_link = $itemObj->url;
					$feat_id = $itemObj->object_id;
					break;
				}
				else {
					if(get_post_thumbnail_id($itemObj->object_id)) {
						$feat_image = wp_get_attachment_url( get_post_thumbnail_id($itemObj->object_id) );
						$feat_title = $itemObj->title;
						$feat_description = $itemObj->description ?: $itemObj->type_label;
						$feat_link = $itemObj->url;
						$feat_id = $itemObj->object_id;
						break;
					}
				}
			}
			// If no feature image was set, set featured item to the first item in the menu
			if(!$feat_image) {
				$feat_title = $menu_items[0]->title;
				$feat_description = $menu_items[0]->description ?: $menu_items[0]->type_label;
				$feat_link = $menu_items[0]->url;
				$feat_id = $menu_items[0]->object_id;
			}

			echo "<!-- START: WP Slider Plugin -->\n";
			include('templates/slider.php');
			echo "<!-- END: WP Slider Plugin -->\n";
		}
	    
	}

}

// instantiate plugin's class
$GLOBALS['js_hps'] = new hps_custom_menu();


include_once( 'walkers/admin_menus_custom_walker.php' );
include_once( 'walkers/front_slider_walker.php' );
