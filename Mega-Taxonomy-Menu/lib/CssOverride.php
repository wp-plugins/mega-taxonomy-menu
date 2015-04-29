<?php
	
function mtm_css_override() {
	
	$menu_position = mtm_get_admin_option("mtm_menu_position");
	$primary_color = mtm_get_admin_option("mtm_primary_color");
	$secondary_color = mtm_get_admin_option("mtm_secondary_color");
	$tertiary_color = mtm_get_admin_option("mtm_tertiary_color");
	$max_width = mtm_get_admin_option("mtm_width") . "px";
	$height = mtm_get_admin_option("mtm_height");
	$heightpx = mtm_get_admin_option("mtm_height") . "px";
	$mam_top = round($height * .14);
	$li_top = round($height * .15);
	$li_bottom = round($height *.28);
	$max_logo_width = round($height * 3);
	
	
	$font_size = mtm_get_admin_option('mtm_font_size');	
	$font_size_icon = round($font_size * 1.5);
	$font_size_iconpx = $font_size_icon . "px";
	$font_face = mtm_get_admin_option('mtm_font_family');
	$font_color = mtm_get_admin_option('mtm_font_color');
	
	$article_color = mtm_get_admin_option("mtm_article_color");
	
	$gutter = 20;

	$image_crop_height = mtm_get_admin_option("mtm_article_image_crop_height");
	?>
	<style type="text/css">
		
		.mtm-header .mtm-main-header {
			background-color: <?php echo $primary_color; ?>;
			font-family: <?php echo $font_face; ?>;
			position: <?php echo $menu_position; ?>;
		}
		
		.mtm-header .mtm-main-header .mtm-container {
			max-width: <?php echo $max_width; ?>;
		}		
		
		.mtm-header .mtm-main-header .sub-menu-inner {
			max-width: <?php echo $max_width; ?>;
		}
		
		.mtm-header .mtm-main-header a {
			font-size: <?php echo $font_size; ?>px;
		}
		
		.mtm-header .mtm-main-header i {
			font-size: <?php echo $font_size_iconpx; ?>;
		}
		
		.mtm-header .mtm-main-header a, .mtm-header .mtm-main-header i {
			color: <?php echo $font_color; ?>;
		}
		
		.mtm-header .mtm-main-header .icon-arrow-down {
			font-size: <?php echo $font_size; ?>px;
		}
		
		.mtm-header .mtm-main-header .sub-menu {
			background-color: <?php echo $secondary_color; ?>;
			top: <?php echo $heightpx; ?>;
		}
		
		.mtm-header .mtm-main-header .sub-menu a {
			color: <?php echo $primary_color; ?>px;
		}
		
		.mtm-header .mtm-main-header li:hover .sub-menu-inner .nav-posts a {
			color: <?php echo $article_color; ?>
		}
		
		.mtm-header .mtm-main-header .search .search-box {
			background-color: <?php echo $secondary_color; ?>;
		}
		.mtm-header .mtm-main-header .search .search-box .search-submit {
			background-color: <?php echo $primary_color; ?>;
		}
		
		.mtm-header .mtm-main-header li:hover, 
		.mtm-header .mtm-main-header .search:hover {
			background-color: <?php echo $secondary_color; ?>;
		}
		
		.mtm-header .mtm-main-header li:hover a, 
		.mtm-header .mtm-main-header li:hover i, 
		.mtm-header .mtm-main-header .search:hover a, 
		.mtm-header .mtm-main-header .search:hover i {
			color: <?php echo $primary_color; ?>;		
		}
		.mtm-header .mtm-main-header .sub-navigation {
			background-color: <?php echo $tertiary_color; ?>;
		}
		.mtm-header .mtm-main-header .sub-navigation ul a:hover {
			background-color: <?php echo $secondary_color; ?>;
		}
		
		.mtm-header .mtm-main-header .sub-navigation .menu-item-type-taxonomy:hover:after {
			color: <?php echo $primary_color; ?>;
		}
		
		.mtm-header .mtm-main-header .see-all {
			background: <?php echo $primary_color; ?>;
		}
		
		.mtm-header .mtm-main-header .see-all a {
			background-color: <?php echo $secondary_color; ?>;
		}
		
		.mtm-header .show-menu {
			background-color: <?php echo $secondary_color; ?>;
		}

		.mtm-header	.mtm-main-header .show-menu	{
			height: <?php echo $heightpx; ?>;
			line-height: <?php echo $heightpx; ?>;
		}
		
		.mtm-header .show-menu i {
			color: <?php echo $primary_color; ?>;
		}
		
		.mtm-header .mtm-mobile-menu {
			background-color: <?php echo $primary_color; ?>;
		}
		
		.mtm-header .mtm-mobile-menu .sub-menu {
			background-color: <?php echo $secondary_color; ?>;
		}
		
		.mtm-header .mtm-mobile-menu .sub-menu a {
			color:<?php echo $primary_color; ?>; 
			border-bottom: 1px solid <?php echo $primary_color; ?>; 
		}
		
		.mtm-header .mtm-mobile-menu .sub-menu a:hover {
			background-color: <?php echo $tertiary_color; ?>;
		}
		
		.mtm-header .mtm-mobile-menu ul li a {
			border-bottom: 1px solid <?php echo $secondary_color; ?>;
		}
		
		.mtm-header .mtm-mobile-menu .social-icons a {
			border-bottom: none;
		}

		
		.mtm-header .mtm-mobile-menu ul li a:hover, 
		.mtm-header .mtm-mobile-menu ul li a:active {
			color:<?php echo $primary_color; ?>;
			background-color: <?php echo $secondary_color; ?>;
		}
		
		.mtm-header .mtm-main-header .not-tax.depth-lvl-0 .depth-1 {
			border-right: 1px solid <?php echo $tertiary_color; ?>;
		}
		
		.mtm-header .mtm-main-header .social-icons li {
			height: <?php echo $heightpx; ?>;
			line-height: <?php echo $heightpx; ?>;
		}
		
		.mtm-header .mtm-main-header .search {
			height: <?php echo $heightpx; ?>;
			line-height: <?php echo $heightpx; ?>;
		}
		
		.mtm-header .mtm-main-header .navbar-brand  {
			max-height: <?php echo $heightpx; ?>;
			line-height: <?php echo $height - 2 ?>px;
		}
		
		.mtm-header .mtm-main-header .navbar-brand img  {
			max-width: <?php echo $max_logo_width - 35 ?>px;
		}
		
		.mtm-header .mtm-main-header .primary-nav > li  {
			height: <?php echo $height - $mam_top ?>px;
		}
		
		.mtm-header .mtm-main-header .primary-nav > li > a {
			padding: <?php echo $li_top . "px 17px " . $li_bottom . "px 10px"; ?>;
		}

		
		<?php if(isset($image_crop_height)) { ?>
			.mtm-header .mtm-main-header .sub-menu .articles .thumb {
				height: <?php echo $image_crop_height; ?>px;
			}
			.mtm-header .mtm-main-header .sub-navigation {
				min-height: <?php echo $image_crop_height + 85; ?>px;
		
			}
		<?php } ?>
		<?php for($fi = 1; $fi <= 5; $fi++ ) { ?>
		
		.mtm-header .mtm-main-header .sub-menu .articles .fixed-menu-post-<?php echo $fi; ?> {			
			width: <?php echo floor((($max_width - ($max_width * .16)) /  $fi) - $gutter); ?>px;
			padding: 0 <?php echo ($gutter/2); ?>px;
		}
		<?php } ?>
		
	</style>
	<?php
}	
add_action("wp_head", "mtm_css_override", 168);

?>