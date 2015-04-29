<?php
/*
	Plugin Name: Mega Taxonomy Menu
	Description: Very fast articles mega menu.
	Version: 1.1.1
	Author: Precious Dale Ramirez
	Author URI: http://codecanyon.net/user/dalewpdevph
	License: You should have purchased a license from http://codecanyon.net/
	Copyright 2013-2014  Precious Dale Ramirez, Demo http://softanalyzer.com/dale/mega-taxonomy-menu/
*/

require("mega-taxonomy-menu-functions.php");

require("admin/mega-taxonomy-menu-options-settings.php");
require("lib/NavWalker.php");
require("lib/NavObjects.php");
require("lib/CssOverride.php");

class MegaTaxonomyMenu {

	protected static $_instance = null;
	private $adminSlug = 'mtm-menu-options';
	function __construct() {
		add_action( 'wp_enqueue_scripts', array($this,'front_scripts') );
		add_action( 'admin_enqueue_scripts', array($this,'admin_scripts') );
		add_filter( 'wp_nav_menu_objects', array($this,'add_tax_count') );
		add_action( 'save_post', array($this, "delete_transient") );
		add_action( "admin_footer", array($this, "admin_footer") );
	}
	
	function get_dir() {
		return plugin_dir_path(__FILE__);
	}
	
	function get_url() {
		return plugin_dir_url(__FILE__);
	}
	
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	function front_scripts() {	
		wp_enqueue_style( 'mtm-style', $this->get_url() . "stylesheets/style.css" );
		
		wp_enqueue_script( 'mtm-jstorage',  $this->get_url() . '/js/jstorage.js', array("jquery"), '1.0.0');
		
		$def = $this->option_defaults();
		
		$column = mtm_get_admin_option("mtm_column");
		$lwd = mtm_get_admin_option("mtm_article_width");
		$max_width = mtm_get_admin_option("mtm_width");
		$position = mtm_get_admin_option("mtm_menu_position");
		$minutes = mtm_get_admin_option("mtm_localstorage_minutes");
		
		wp_enqueue_script( 'mtm-front-script',  $this->get_url() . 'js/mtm.js', array("mtm-jstorage"), '1.0.0');
		wp_localize_script('mtm-front-script', 'mtm_column', 
		array("num" => $column, "width" => $lwd, "max_width" => $max_width, 
		"position" => $position, "minutes" => $minutes));
		wp_localize_script( 'mtm-front-script', 'mtm_data', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	}	
	
	function admin_scripts() {	
		$screen = get_current_screen();
		if($screen->base == "post" || (isset($_GET['page']) && $_GET['page'] == $this->adminSlug) || $screen->base == "edit") {
			wp_enqueue_script( 'mtm-jstorage',  $this->get_url() . '/js/jstorage.js', array("jquery"), '1.0.0');		
		}
		
	
	}

	function add_tax_count($items) {
		
		$length = 1;
		 
		foreach ( $items as $item ) {
			if ( $item->type == "taxonomy" && $item->menu_item_parent == 0 ) {
				
				$item->classes[] = 'menu-tax-count-' . $length;
				$length++;
			}
			
		}
		
		return $items;    
	}
	function delete_transient() {
		if(get_transient( 'mam_menu_objects' )) {
			delete_transient( 'mam_menu_objects' );
		}
	}
	
	function admin_footer() {

		?>
		<?php if(isset($_GET['page']) && $_GET['page'] == $this->adminSlug) { ?>
			<script type="text/javascript">
				var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
				jQuery("#mtm_clear_link").click(function(e) {
					e.preventDefault();
					
					jQuery.jStorage.deleteKey("meganav_objects");
					var data = {
						'action': 'mtm_clear_objects',
					};
					jQuery.post(ajaxurl, data, function(response) {
						if(response.result == "success") {
							alert("Menu Cache Cleared");
						} else if(response.result == "notrans") {
							alert("Menu Cache has already been cleared.");
						} else {
							alert("Menu Cache Clearing FAILED " + response.result);
						}
					}, "json");
				})
			</script>
		<?php }
		
		$screen = get_current_screen();
		
		
		if($screen->base == "edit" || $screen->base == "post") {
		?>
		<script type="text/javascript">
			jQuery(".submit .save, #publish").click(function() {
				
				jQuery.jStorage.deleteKey("meganav_objects");
			})
		</script>
		<?php
		}
	} 
	
	function option_defaults() {
		$options = array();
		$options['mtm_logo'] = $this->get_url() . "images/logo.png";
		$options['mtm_menu'] = false;
		$options['mtm_menu_position'] = "fixed";
		$options['mtm_primary_color'] = "#00aeef";
		$options['mtm_secondary_color'] = "#DDF0F9";
		$options['mtm_tertiary_color'] = "#C7E6F5";
		$options['mtm_width'] = "1300";
		$options['mtm_height'] = "70";
		$options['mtm_typography'] = array( 'size' => '14px', 'face' => 'Arial, serif', 'color' => '#cccccc');
		$options['mtm_article_color'] = "#000000";
		$options['mtm_article_width'] = "fixed";
		$options['mtm_article_image_size'] = "thumbnail";
		$options['mtm_column'] = 5;
		$options['mtm_localstorage_minutes'] = 300;
		$options['mtm_article_image_crop_height'] = false;
		$options['mtm_article_see_all'] = false;
		$options['mtm_custom_css'] = "";
		$options['mtm_facebook_url'] = "";
		$options['mtm_twitter_url'] = "";
		$options['mtm_pinterest_url'] = "";
		$options['mtm_instagram_url'] = "";
		$options['mtm_googleplus_url'] = "";
		
		return $options;
	}
}

function mega_taxonomy_menu() {
	mtm_get_template_part("mtm", "menu");
}

function MTM() {
	return MegaTaxonomyMenu::instance();
}

MTM();

function mtm_nav_objects() {
	global $meganav_objects;

	$meganav_objects->the_objects();
	exit;
}
add_action( 'wp_ajax_mtm_nav_objects', 'mtm_nav_objects' );
add_action( 'wp_ajax_nopriv_mtm_nav_objects', 'mtm_nav_objects' );

function mtm_clear_objects() {
	$arr = array();
	if(get_transient( 'mam_menu_objects' )) {
		
		$res = delete_transient( 'mam_menu_objects' );
		if($res) {
			$arr['result'] = "success";
		} else {
			$arr['result'] = "fail";
		}
	} else {
		$arr['result'] = "notrans";
	}

	echo json_encode($arr);
	exit;
}
add_action( 'wp_ajax_mtm_clear_objects', 'mtm_clear_objects' );
add_action( 'wp_ajax_nopriv_mtm_clear_objects', 'mtm_clear_objects' );

// Debugging
if(!function_exists("dumpit")) {
	function dumpit($arr, $return = false) {
		$out = "<pre>" . print_r($arr, true) . "</pre>";
		if(!$return) {
			echo $out;
		} else {
			return $out;
		}
	}
}
?>