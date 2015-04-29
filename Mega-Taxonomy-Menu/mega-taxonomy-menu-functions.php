<?php

function mtm_get_template_part( $slug, $name = '' ) {
	
	$template = '';
	
	if ( $name )
		$template = locate_template( array ( "{$slug}-{$name}.php" ) );

	
	if ( !$template && $name && file_exists( MTM()->get_dir() . "/templates/{$slug}-{$name}.php" ) )
		$template = MTM()->get_dir() . "/templates/{$slug}-{$name}.php";	
	
	if ( !$template && $slug && file_exists( MTM()->get_dir() . "/templates/{$slug}.php" ) )
		$template = MTM()->get_dir() . "/templates/{$slug}.php";

	
	if ( !$template )
		$template = locate_template( array ( "{$slug}.php" ) );


		
	if ( $template )
		load_template( $template, false );
}


function mtm_get_admin_option($key)
{
	$def = MTM()->option_defaults();
	$options = get_option('mtm_menu_options');
	
	if(empty($options[$key])) {
		if(empty($def[$key])) {
			return '';
		} else {
			return $def[$key];
		}
	} else {
		
		return $options[$key];
	}
}
?>
