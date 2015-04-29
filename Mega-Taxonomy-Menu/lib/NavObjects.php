<?php

class MegaNavObjects {
	
	private $menu_id;
	private $menu_arr = array();
	private $column = 5;
	private $hours;
	function __construct($menu_id) {
		$this->hours = 3600 * 5; // remove transient every 5 hours
	
		$this->menu_id = mtm_get_admin_option("mtm_menu");
		$this->column = mtm_get_admin_option("mtm_column");
		if(isset($_GET['clear_mam'])) {			
			delete_transient( 'mam_menu_objects' );
		}
	}
	
	function get_nav_items() {
		
		$menu_id = $this->menu_id;
		


			$menu_items = wp_get_nav_menu_items($menu_id);

			if(!empty($menu_items)) {
				foreach($menu_items as $item) {
					if($item->type == "taxonomy") {
						
						$obj_id = $item->object_id;
						$tax = $item->object;
						$menu_posts = $this->get_fields($tax, $obj_id);
						
						$this->menu_arr[$obj_id] = $menu_posts;
					}
				}
			}
			

	}
	
	function get_fields($tax, $tag_id) {
		$r_items = array();
		$args['posts_per_page'] = $this->column;
		$args['post_status'] = "publish";
		$args['post_type'] = "any";
		;
		$tax_ar = array();
		$tax_ar['taxonomy'] = $tax;
		$tax_ar['field'] = 'term_id';
		$tax_ar['terms'] = $tag_id;
		$tax_query = array($tax_ar);
		$args['tax_query'] = $tax_query;
		
		$args["fields"] = "ids";
		$items = get_posts($args);	
		//dumpit($args);
		if(!empty($items)) {
			foreach($items as $item) {
				$post_id = $item;
				$r_items[$post_id]['url'] = get_permalink($post_id);
				$r_items[$post_id]['title'] = get_the_title($post_id);
				$image_size = mtm_get_admin_option("mtm_article_image_size");
				$post_thumbnail_id = get_post_thumbnail_id( $post_id );
				
				$image_attributes = wp_get_attachment_image_src( $post_thumbnail_id, $image_size );	
				if(!empty($image_attributes)) {
					$r_items[$post_id]['thumb'] = $image_attributes[0];
				}
			}
		}
		
		
		if(!empty($r_items)) {
			return $r_items;
		} else {
			return false;
		}
	}
	
	function get_objects() {
		if(!get_transient("mam_menu_objects")) {
			$this->get_nav_items();
			$menu_arr = $this->menu_arr;
			
			$mam_objects = json_encode($menu_arr);		
			$hours = $this->hours;
			set_transient("mam_menu_objects", $mam_objects, $hours );
			return $mam_objects;
		} else {
			$mam_objects = get_transient("mam_menu_objects");
/* 			$mam_objects = json_decode($mam_objects, true);
			
			$mam_objects[0][0]['title'] = "jere";
			
			$mam_objects = json_encode($mam_objects); */
			

			return $mam_objects;
		}
	}
	
	function the_objects() {
	
		echo $this->get_objects();
		exit;
	}
	
}

$meganav_objects = new MegaNavObjects(0);


?>