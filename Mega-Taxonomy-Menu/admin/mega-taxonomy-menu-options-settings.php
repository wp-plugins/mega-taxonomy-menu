<?php
require "lib/Mtm_Media_Uploader.php";

class MegaTaxonomyOptions {
	
	protected $slug = 'mtm-menu-options';
	protected $optionName = "mtm_menu_options";
	protected $menuTitle = 'Mega Taxonomy Menu Options';
	protected $options;
	private $section;
	private $inputName;
	private $inputTitle;
	private $inputDesc;
	private $inputDatas;	
	private $validation = array();
	private $types = array();
	private $errorSlug = 'mtm-setting-error';
	function __construct()
	{
		add_action('admin_menu', array($this,'mtm_menu_options_page'));
		add_action('admin_init', array($this,'mtm_register_and_build_fields'));
		add_action( 'admin_notices', array($this,'admin_notices_action' ));
		add_action( 'wp_enqueue_scripts', array($this,'options_typography_google_fonts') );
		add_action( 'admin_enqueue_scripts', array($this,'admin_scripts' ));
		$this->options = get_option($this->optionName);
	}
	
	
	function mtm_menu_options_page() 
	{  
		add_menu_page($this->menuTitle, $this->menuTitle, 'administrator', $this->slug, array($this,'build_options_page'));
	}
	function build_options_page() 
	{ 
	?>
	<div id="theme-options-wrap">
		<div class="icon32" id="icon-tools"> <br /> </div>
		<h2><?php _e($this->menuTitle, 'mega_taxonomy_menu'); ?></h2>
		
		<form method="post" action="options.php" enctype="multipart/form-data">
			<?php settings_fields($this->optionName); ?>
			<?php do_settings_sections($this->slug); ?>
			

			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</p>
		</form>
	</div>
	<?php 
	}
	function setFields()
	{
		$this->section("Settings");
		$this->imageUpload("mtm_logo", "Logo", __('Adds logo the mega taxonomy menu', 'mega_taxonomy_menu'));
		
		$menu_terms = get_terms( 'nav_menu', array( 'hide_empty' => true ));
		$menus = array();
 		if(!empty($menu_terms)) {
			foreach($menu_terms as $menu_term) {
				$menus[$menu_term->term_id] = $menu_term->name;
			}
		}
		$desc = (empty($menus))? __('Please create a menu in Appearance -> Menus.', 'mega_taxonomy_menu') : __('Choose a menu to convert it to a Mega Taxonomy Menu', 'mega_taxonomy_menu');
 		$this->dropdown("mtm_menu", __('Select Nav Menu', 'mega_taxonomy_menu'),
		$desc,
		$menus		
		);
		
		$this->dropdown('mtm_menu_position', __('Menu Position', 'mega_taxonomy_menu'),
		__('Choose a menu position. Fixed position floats menu when scrolling down.', 'mega_taxonomy_menu'),
		array('fixed' => 'Fixed', 'relative' => 'Relative')
		);
		
		$this->colorPicker('mtm_primary_color', 
		__('Primary Color', 'mega_taxonomy_menu'),
		__('Choose a primary color.', 'mega_taxonomy_menu'),
		"#00aeef"
		);		
		
		$this->colorPicker('mtm_secondary_color', 
		__('Secondary Color', 'mega_taxonomy_menu'),
		__('Choose a secondary color.', 'mega_taxonomy_menu'),
		"#DDF0F9"
		);		
		
		$this->colorPicker('mtm_tertiary_color', 
		__('Tertiary Color', 'mega_taxonomy_menu'),
		__('Choose a tertiary color.', 'mega_taxonomy_menu'),
		"#C7E6F5"
		);		
		
		$this->textInput('mtm_width', 
		__('Menu Width', 'mega_taxonomy_menu'),
		__('The mega taxonomy menu maximum width in px.', 'mega_taxonomy_menu'),
		"1300", 'required'
		);		
		
		$this->textInput('mtm_height', 
		__('Menu Height', 'mega_taxonomy_menu'),
		__('The mega taxonomy menu height in px.', 'mega_taxonomy_menu'),
		"70", 'required'
		);		
		$this->dropdown('mtm_font_size', __('Font Size', 'mega_taxonomy_menu'),
		__('select a font size in px.', 'mega_taxonomy_menu'),
		$this->range(9, 71)
		);			
		$typography_mixed_fonts = array_merge( $this->options_typography_get_os_fonts() , $this->options_typography_get_google_fonts() );
		asort($typography_mixed_fonts);
		$this->dropdown('mtm_font_family', __('Font Family', 'mega_taxonomy_menu'),
		__('select a font face or family.', 'mega_taxonomy_menu'),
		$typography_mixed_fonts
		);		
		$this->colorPicker('mtm_font_color', 
		__('Menu Font Color', 'mega_taxonomy_menu'),
		__('Choose a font color.', 'mega_taxonomy_menu'),
		"#000000"
		);		
		$this->colorPicker('mtm_article_color', 
		__('Article Color', 'mega_taxonomy_menu'),
		__('select color of article text.', 'mega_taxonomy_menu'),
		"#000000"
		);
		
		$this->dropdown('mtm_article_width', __('Article Width', 'mega_taxonomy_menu'),
		__('Choose the article width layout.', 'mega_taxonomy_menu'),
		array("fixed" => "Fixed", "fluid" => "Fluid")
		);
		
	$image_sizes = array();
	$registered_images = get_intermediate_image_sizes();
	
	if(!empty($registered_images)) {
		foreach($registered_images as $img_key => $size_name) {
			$image_sizes[$size_name] = $size_name;
		}
	}
	
		$this->dropdown('mtm_article_image_size', __('Article Image Size', 'mega_taxonomy_menu'),
		__('Choose the article image size.', 'mega_taxonomy_menu'),
		$image_sizes
		);
		
		$this->textInput('mtm_article_image_crop_height', 
		__('Article Image Height crop', 'mega_taxonomy_menu'),
		__('Use CSS to crop your article images height in px.', 'mega_taxonomy_menu'),
		"200", 'required'
		);
		
		$this->checkbox('mtm_article_see_all', 
		__('Remove See All Link', 'mega_taxonomy_menu'),
		__('Check to remove "See all" link at the bottom of the articles.', 'mega_taxonomy_menu')
		);
		
		$this->dropdown('mtm_column', __('Columns', 'mega_taxonomy_menu'),
		__('Number of articles to display', 'mega_taxonomy_menu'),
		$this->range(1, 5)
		);
		
		$this->textInput('mtm_localstorage_minutes', 
		__('HTML5 Local Storage Minutes', 'mega_taxonomy_menu'),
		__('Input how many minutes html5 local storage clears. Note that for every post update 
		html5 local storage clears to have an updated articles in the menu. Default to 5 hours. 300min = 5h * 60mi', 'mega_taxonomy_menu'),
		"300", 'required'
		);
		$this->info('Clear Cache (if changes not showing click clear cache)', __("<a id='mtm_clear_link' style='font-size: 34px' href='" . admin_url() . "?page=" . $this->slug . "&clear_jstorage=true&clear_mam=1'>Clear</a> Click to clear cache.", 'mega_taxonomy_menu'));
		$this->section("Social Media Links");
		
		$this->textInput('mtm_facebook_url', 
		__('Facebook URL', 'mega_taxonomy_menu'),
		__('Facebook url link.', 'mega_taxonomy_menu'),''
		);		
		$this->textInput('mtm_twitter_url', 
		__('Twitter URL', 'mega_taxonomy_menu'),
		__('Twitter url link.', 'mega_taxonomy_menu'),''
		);			
		$this->textInput('mtm_pinterest_url', 
		__('Pinterest URL', 'mega_taxonomy_menu'),
		__('Pinterest url link.', 'mega_taxonomy_menu'),''
		);
		$this->textInput('mtm_instagram_url', 
		__('Instagram URL', 'mega_taxonomy_menu'),
		__('Instagram url link.', 'mega_taxonomy_menu'),''
		);
		$this->textInput('mtm_googleplus_url', 
		__('Google Plus URL', 'mega_taxonomy_menu'),
		__('Google Plus url link.', 'mega_taxonomy_menu'),''
		);
		
	}
	function mtm_register_and_build_fields() 
	{
		register_setting($this->optionName, $this->optionName, array($this,'validate_setting'));
		
		$this->setFields();

	}
	function validate_setting($mtm_menu_options) {
		
		$validation = $this->validation;
		
		if(!empty($validation)) {
			foreach($validation as $vk => $validate) {
				if($validate['validate'] == 'required') {
					
					if(empty($mtm_menu_options[$vk])) {
						$mtm_menu_options[$vk] = $this->options[$vk];
						$message = $validate['title'] . " is required";
						$type = "error";
							add_settings_error(
								$this->errorSlug,
								esc_attr( $this->slug ),
								$message,
								$type
							);
					}
				}
					
			}
		}		
		
		$types = $this->types;
		
		if(!empty($types)) {
			foreach($types as $tk => $type) {
				if($type['type'] == 'checkbox') {
					
					if(empty($mtm_menu_options[$tk])) {
						$mtm_menu_options[$tk] = 0;
					}
				}
					
			}
		}
		
		return $mtm_menu_options;
	}
	function addSettings($name, $func)
	{
		add_settings_section($this->section, $name, array($this,$func), $this->slug);
	}
	
	function addSettingsField($args, $func)
	{
		$name = (isset($args["name"]))? $args["name"] : "";
		$title = (isset($args["title"]))? $args["title"] : "";

		add_settings_field($name, $title, array($this,$func), $this->slug, $this->section, $args);
	}
	function section($name)
	{
		$this->section = str_replace(' ', '-', strtolower($name));
		
		//add_settings_section($this->section, $name, array($this,'sectionRun'), $this->slug);
		$this->addSettings($name, 'sectionRun');
	}
	function sectionRun()
	{
	}
	function imageUpload($name, $title, $desc)
	{
		
		
		$args = array("name" => $name, "title" => $title, "desc" => $desc);
		$this->addSettingsField($args, 'imageUploadRun');
		//add_settings_field($this->inputName, $title, array($this,'imageUploadRun'), $this->slug, $this->section);
	}
	
	function imageUploadRun($args)
	{
		$name = (isset($args["name"]))? $args["name"] : "";
		$title = (isset($args["title"]))? $args["title"] : "";		
		$desc = (isset($args["desc"]))? $args["desc"] : "";		
		?>
		<div id="section-<?php echo $name; ?>" class="section section-upload">
		
		<?php
		
		$logo_val = (isset($this->options[$name]))? $this->options[$name] : "";
		$uploader = Mtm_Media_Uploader::mtm_optionsmenu_uploader($name, $logo_val, '', $this->inputName($name));
		echo $uploader;
		?>
		<label><?php echo $desc; ?></label>
		</div>
		<?php		
	}
	
	function dropdown($name, $title, $desc, $datas)
	{
		$args = array("name" => $name, "title" => $title, "desc" => $desc, 'datas' => $datas);
		$this->addSettingsField($args, 'dropdownRun');
		
		
		
	}
	function dropdownRun($args)
	{
		$name = (isset($args["name"]))? $args["name"] : "";
		$title = (isset($args["title"]))? $args["title"] : "";
		$desc = (isset($args["desc"]))? $args["desc"] : "";
		$datas = (isset($args["datas"]))? $args["datas"] : "";
		
		$val = (isset($this->options[$name]))? $this->options[$name] : '';
		?>
		<div class="section">
		<select class="of-input <?php echo $name; ?>" name="<?php echo $this->inputName($name); ?>" id="<?php echo $name; ?>">
		<?php
		if(!empty($datas)) {
			foreach($datas as $dk => $data) {
				$selected = ($dk == $val)? 'selected="selected"' : '';
				echo '<option ' . $selected . ' value="' . $dk . '">' . $data . '</option>';
			}
		}
		?>
		</select>
		
		<label><?php echo $desc; ?></label>
		</div>
		<?php		
	}
	
	function colorPicker($name, $title, $desc, $default = '#ccc')
	{
		$args = array("name" => $name, "title" => $title, "desc" => $desc, 'default' => $default);
		$this->addSettingsField($args, 'colorPickerRun');
	}
	function colorPickerRun($args)
	{
		$name = (isset($args["name"]))? $args["name"] : "";
		$title = (isset($args["title"]))? $args["title"] : "";
		$desc = (isset($args["desc"]))? $args["desc"] : "";	
		$default = (isset($args["default"]))? $args["default"] : "";	
		$val = (isset($this->options[$name]))? $this->options[$name] : $default;		
		?>
		<div class="section">
			<input class="of-color" name="<?php echo $this->inputName($name); ?>" id="<?php echo $name; ?>"  type="text" value="<?php echo $val; ?>" data-default-color="<?php echo $default; ?>"  />
			<label><?php echo $desc; ?></label>
		</div>
		<?php
	}
	
	function textInput($name, $title, $desc, $default, $validation = array())
	{
		if(!empty($validation)) {
			$this->validation[$name] = array('validate' => $validation, 'title' => $title);
		}
		$args = array("name" => $name, "title" => $title, "desc" => $desc, 'default' => $default);
		$this->addSettingsField($args, 'textInputRun');
	}
	function textInputRun($args)
	{
		$name = (isset($args["name"]))? $args["name"] : "";
		$title = (isset($args["title"]))? $args["title"] : "";
		$desc = (isset($args["desc"]))? $args["desc"] : "";	
		$default = (isset($args["default"]))? $args["default"] : "";	
		$val = (isset($this->options[$name]))? $this->options[$name] : $default;			
		?>
		<div class="section">
			<input class="of-input <?php echo $name; ?>" name="<?php echo $this->inputName($name); ?>" id="<?php echo $name; ?>"  type="text" value="<?php echo $val; ?>"  />
			<label><?php echo $desc; ?></label>
		</div>
		<?php		
	}
	function checkbox($name, $title, $desc)
	{
		$this->types[$name] = array('type' => 'checkbox', 'title' => $title);
		$args = array("name" => $name, "title" => $title, "desc" => $desc);
		$this->addSettingsField($args, 'checkboxRun');
	}	
	
	function checkboxRun($args)
	{
		$name = (isset($args["name"]))? $args["name"] : "";
		$title = (isset($args["title"]))? $args["title"] : "";
		$desc = (isset($args["desc"]))? $args["desc"] : "";	
			
		$val = (isset($this->options[$name]) && $this->options[$name] == 1)? 'checked' : '';			
	?>
		<div class="section">
			<input class="of-checkbox <?php echo $name; ?>" <?php echo $val; ?> name="<?php echo $this->inputName($name); ?>" id="<?php echo $name; ?>"  type="checkbox" value='1'  />
			<label><?php echo $desc; ?></label>
		</div>
	<?php		
	}
	function info($title, $desc)
	{
		$args = array("title" => $title, "desc" => $desc);
		$this->addSettingsField($args, 'infoRun');
	}
	
	function infoRun($args)
	{
		
		$desc = (isset($args["desc"]))? $args["desc"] : "";	
		
		echo $desc;
	}
	function inputName($name)
	{
		return $this->optionName . '[' . $name . ']';
	}
	
	function admin_notices_action() {
		settings_errors( $this->errorSlug );
	}

	/**
	 * Returns an array of system fonts
	 * Feel free to edit this, update the font fallbacks, etc.
	 */
	function options_typography_get_os_fonts() {
		// OS Font Defaults
		$os_faces = array(
			'Arial, sans-serif' => 'Arial',
			'"Avant Garde", sans-serif' => 'Avant Garde',
			'Cambria, Georgia, serif' => 'Cambria',
			'Copse, sans-serif' => 'Copse',
			'Garamond, "Hoefler Text", Times New Roman, Times, serif' => 'Garamond',
			'Georgia, serif' => 'Georgia',
			'"Helvetica Neue", Helvetica, sans-serif' => 'Helvetica Neue',
			'Tahoma, Geneva, sans-serif' => 'Tahoma'
		);
		return $os_faces;
	}

	/**
	 * Returns a select list of Google fonts
	 * Feel free to edit this, update the fallbacks, etc.
	 */
	function options_typography_get_google_fonts() {
		// Google Font Defaults
		$google_faces = array(
			'Arvo, serif' => 'Arvo',
			'Copse, sans-serif' => 'Copse',
			'Droid Sans, sans-serif' => 'Droid Sans',
			'Droid Serif, serif' => 'Droid Serif',
			'Lobster, cursive' => 'Lobster',
			'Nobile, sans-serif' => 'Nobile',
			'Open Sans, sans-serif' => 'Open Sans',
			'Oswald, sans-serif' => 'Oswald',
			'Pacifico, cursive' => 'Pacifico',
			'Rokkitt, serif' => 'Rokkit',
			'PT Sans, sans-serif' => 'PT Sans',
			'Quattrocento, serif' => 'Quattrocento',
			'Raleway, cursive' => 'Raleway',
			'Ubuntu, sans-serif' => 'Ubuntu',
			'Yanone Kaffeesatz, sans-serif' => 'Yanone Kaffeesatz'
		);
		return $google_faces;
	}

	/**
	 * Checks font options to see if a Google font is selected.
	 * If so, options_typography_enqueue_google_font is called to enqueue the font.
	 * Ensures that each Google font is only enqueued once.
	 */
	
	function options_typography_google_fonts() {
		$all_google_fonts = array_keys( $this->options_typography_get_google_fonts() );


		// Get the font face for each option and put it in an array
		$selected_fonts = array($this->options['mtm_font_family']);
		// Remove any duplicates in the list
		$selected_fonts = array_unique($selected_fonts);
		// Check each of the unique fonts against the defined Google fonts
		// If it is a Google font, go ahead and call the function to enqueue it
		foreach ( $selected_fonts as $font ) {
			if ( in_array( $font, $all_google_fonts ) ) {
				$this->options_typography_enqueue_google_font($font);
			}
		}
	}
	function options_typography_enqueue_google_font($font) {
		$font = explode(',', $font);
		$font = $font[0];
		// Certain Google fonts need slight tweaks in order to load properly
		// Like our friend "Raleway"
		if ( $font == 'Raleway' )
			$font = 'Raleway:100';
		$font = str_replace(" ", "+", $font);
		wp_enqueue_style( "options_typography_$font", "http://fonts.googleapis.com/css?family=$font", false, null, 'all' );
	}
	
	function admin_scripts()
	{
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'mtm-options-custom', plugin_dir_url( dirname(__FILE__) ) . 'admin/lib/js/options-custom.js', array( 'jquery','wp-color-picker' ) );
		wp_enqueue_style( 'mtm-options-style', plugin_dir_url( dirname(__FILE__) ) . 'admin/lib/css/admin.css' );
	}
	
	function range($start = 9, $end = 70)
	{
		$arr = array();
		for($i = $start; $i <= $end; $i++) {
			$arr[$i] = $i;
		}
		return $arr;
	}
}

new MegaTaxonomyOptions;






?>