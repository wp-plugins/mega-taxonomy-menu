<header id="mtm-main-header" class="banner navbar mtm-header">
	<div id="mobile-menu" class="mtm-mobile-menu">
		<h3>Menu</h3>	
	</div>


	<div class="mtm-main-header">
		<div class="mtm-container">
		
			<div class="mtm-row">
				<div class="col-sm-12">
					<div class="navbar-header">
					   
					<a href="#"class="show-menu"><i class="icon-menu"></i></a>
										
					</div><!-- /.navbar-header -->
					<div class="navbrand-nav-social">
						<?php
						 $logo = mtm_get_admin_option("mtm_logo");
						 if(empty($logo)) {
							$logo = MTM()->get_url() . "images/logo.png";
						 }
						?>
						<a class="navbar-brand" href="<?php echo home_url('/'); ?>"><img src="<?php echo $logo; ?>" alt="<?php echo get_bloginfo("name"); ?>" /></a>
						<nav class="main-navigation" role="navigation">
							<?php 
							$mtm_menu = mtm_get_admin_option("mtm_menu");
							$menu_terms = get_terms( 'nav_menu', array( 'hide_empty' => true ));
							if ( !empty($menu_terms) ) :
								  wp_nav_menu(array(
									'menu' => $mtm_menu,
									'container_class' => 'mtm-menu-container',
									'menu_class' => 'primary-nav',
									'walker'         => new MTM\Navigation\NavWalker(),
									'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul><div class="mtm-clear"></div>',
								 )); 

							endif; 
							
							?>						
						</nav><!-- /.main-navigation -->
						
						<ul class="social-icons hidden-xs hidden-sm">
							<?php
							$facebook  = mtm_get_admin_option("mtm_facebook_url");
							$twitter = mtm_get_admin_option("mtm_twitter_url");
							$pinterest = mtm_get_admin_option("mtm_pinterest_url"); 
							$instagram = mtm_get_admin_option("mtm_instagram_url");
							$googleplus = mtm_get_admin_option("mtm_googleplus_url");
							?>
							<?php if(!empty($facebook)) { ?>
							<li>
								<a href="<?php echo $facebook; ?>">
									<i class="social-icon icon-facebook"></i>
								</a>
							</li>
							<?php } ?>
							<?php if(!empty($twitter)) { ?>
							<li>
								<a href="<?php echo $twitter; ?>">
									<i class="social-icon icon-twitter"></i>
								</a>
							</li>
							<?php } ?>
							<?php if(!empty($pinterest)) { ?>
							<li>
								<a href="<?php echo $pinterest; ?>">
									<i class="social-icon icon-pinterest"></i>
								</a>						
							</li>
							<?php } ?>
							<?php if(!empty($googleplus)) { ?>
							<li>
								<a href="<?php echo $googleplus; ?>">
									<i class="social-icon icon-googleplus"></i>
								</a>						
							</li>
							<?php } ?>
							<?php if(!empty($instagram)) { ?>
							<li>
								<a href="<?php echo $instagram; ?>">
									<i class="social-icon icon-instagram"></i>
								</a>						
							</li>
							<?php } ?>
						</ul><!-- /.social-icons -->
		
					</div>
					<div class="search popover" rel="#js-search-box">
						<i class="icon-search"></i>
						<div id="js-search-box" class="search-box" style="display: none;">
							<?php mtm_get_template_part("mtm", "search"); ?>
						</div>
					</div><!-- /.search -->
				</div><!-- /.col-sm-12 -->
			</div><!-- /.row -->
		</div><!-- /.container -->
	</div>
</header><!-- /.main-header -->
