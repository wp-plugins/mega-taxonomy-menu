<?php

namespace MTM\Navigation;

class NavWalker extends \Walker_Nav_Menu {

    private $posts_output = '';
	private $mtm_tax = false;
	private $is_tax = false;
    private $see_all = '';
	private $count = 1;
	private $length = 8;
	
    function check_current($classes) {
        return preg_match('/(current[-_])|active|dropdown/', $classes);
    }

    function start_lvl(&$output, $depth = 0, $args = array()) {
		if($depth == 0) {
			$output.= "\n<div class=\"sub-menu\">\n";
		}
		if($depth == 0) {	
			$output.= "<div class=\"sub-menu-inner\">\n";
			$output.= $this->check_set_menu();
		}
		if($depth == 0 && $this->mtm_tax) {
			$output.='<i class="icon-spinner"></i>';
				
			$output.= $this->_start_meganav_wrap($depth);
		} elseif($this->mtm_tax && $depth > 0) { 
			$output.= "<ul>";
		} else {
			$output.= "<ul class='not-tax depth-lvl-" . $depth . "'>";
		}
    }

    function end_lvl( &$output, $depth = 0, $args = array() ){
        $indent = str_repeat("\t", $depth);
		$meganav_end = "";
		if($this->mtm_tax && $depth == 0) {
			$meganav_end = $this->_end_meganav_wrap($depth);		
			$output.= "$indent $meganav_end ";
		} elseif($this->mtm_tax && $depth > 0) { 
			$output.= "</ul>";
		} else {
			$output.="</ul>";
		}
		
		if($depth == 0) {		
			$output.= "</div> ";
		}
		if($depth == 0) {
			$output.= $this->see_all;
		}
		if($depth == 0) {
			$output.=" </div>\n";
		}
    }



    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {


        parent::start_el($item_html, $item, $depth, $args);
		if($depth == 0) {
			if( $item->type == "taxonomy" ) {
				$this->mtm_tax = true;
			} else {
				$this->mtm_tax = false;
			}
		}
		
		
		$this->is_tax = ($item->type == "taxonomy") ? true : false;
		if($this->mtm_tax) {
			$this->count++;
		}
		
		if ($item->type == "taxonomy"){

			$item_html = str_replace('<a', '<a data-tag="' . $item->object_id . '" ', $item_html);
			

		}
		if ($item->is_dropdown && ($depth === 0)) {
		   $item_html = str_replace('<a', ' <a class="parent"', $item_html);
		  $item_html = str_replace('</a>', ' <i class="has-child icon-arrow-down"></i></a>', $item_html);
		}
		
		$this->see_all = '<div class="see-all clearfix">';
		if ($this->mtm_tax){
			$see_all_option = mtm_get_admin_option("mtm_article_see_all");
			if(!$see_all_option) {
				$this->see_all .= $this->_add_see_all_link($item);
			}
			
		}
		$this->see_all .= '</div>';
		$item_html = apply_filters('mtm_wp_nav_menu_item', $item_html);

        $output .= $item_html . "\n";
    }

    function end_el( &$output, $item, $depth = 0, $args = array() ) {

		if ($depth === 0){
            $this->posts_output = '';
			$this->see_all = '';
			
        }
		
		if ($item->type == "taxonomy" && $depth > 0){
		//	$output .= "<i class='icon-arrow-right'></i>";
		}
		
        $output .= "</li>\n";
    }

    private function _start_meganav_wrap($depth){

        return '<nav class="sub-navigation depth-lvl-'.$depth.'">' . "\n" . '<ul>' . "\n";

    }

    private function _end_meganav_wrap($depth){
		$nav_posts = '';
		
		
		
		$nav_posts = '<section class="nav-posts articles"><i class="fa fa-spinner fa-spin mega-spinner"></i></section>';
	
        return '</ul></nav>' . $this->posts_output. ' ' . $nav_posts;

    }

    private function _add_meganav_posts($item){

	return '<section id="js-section-'.$item->object_id.'" data-tax="'.$item->object.'" data-id="'.$item->object_id.'" class="js-mega-posts articles text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></section>';

    }

    private function _add_see_all_link($item){

	$title = !empty($item->post_title) ? $item->post_title : $item->title;
	return '<a class="pull-right" href="">' . apply_filters("mtm_see_all","See all") . ' <span></span></a>';
    }
	
	function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
		$element->is_dropdown = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));
		
		if ($element->is_dropdown) {
		  $element->classes[] = 'dropdown';
		}
		$element->classes[] = 'depth-' . $depth;
		parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	}
	
	function check_set_menu() {
		if(!mtm_get_admin_option("mtm_menu")) {
			return "<h2>Please select a menu in Admin -> Mega Taxonomy Menu -> \"Select a menu\" and then save options.</h2>";
		} else {
			return "";
		}
	}

}

class MobileNavWalker extends \Walker_Nav_Menu
{
	 function start_el(&$output, $item, $depth = 0, $args = Array(), $id = 0)
    {

        parent::start_el($item_html, $item, $depth, $args);
		
		$class_names = join( ' ', $classes);
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		
       	if ($item->is_dropdown && ($depth === 0)) {
		  
		  $item_html = str_replace('<a', ' <a class="parent"', $item_html);
		  $item_html = str_replace('</a>', ' <i class="has-child icon-arrow-down"></i></a>', $item_html);
		}
		 $output .= $item_html;
    }
	
    function display_element ($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
    {
        // check, whether there are children for the given ID and append it to the element with a (new) ID
        $element->is_dropdown = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));

        return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }


}