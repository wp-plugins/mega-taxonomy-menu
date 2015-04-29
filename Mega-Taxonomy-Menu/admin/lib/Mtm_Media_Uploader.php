<?php


class Mtm_Media_Uploader {

	public function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'mtm_optionsmenu_media_scripts' ) );
	}

	static function mtm_optionsmenu_uploader( $_id, $_value, $_desc = '', $name ) {

		$mtm_optionsmenu_settings = get_option( 'mtm_menu_options' );

		

		$output = '';
		$id = '';
		$class = '';
		$int = '';
		$value = '';
		

		$id = strip_tags( strtolower( $_id ) );

		// If a value is passed and we don't have a stored value, use the value that's passed through.
		if ( $_value != '' && $value == '' ) {
			$value = $_value;
		}



		if ( $value ) {
			$class = ' has-file';
		}
		$output .= '<input id="' . $id . '" class="upload' . $class . '" type="text" name="'.$name.'" value="' . $value . '" placeholder="' . __('No file chosen', 'mtm-menu') .'" />' . "\n";
		if ( function_exists( 'wp_enqueue_media' ) ) {
			if ( ( $value == '' ) ) {
				$output .= '<input id="upload-' . $id . '" class="upload-button button" type="button" value="' . __( 'Upload', 'mtm-menu' ) . '" />' . "\n";
			} else {
				$output .= '<input id="remove-' . $id . '" class="remove-file button" type="button" value="' . __( 'Remove', 'mtm-menu' ) . '" />' . "\n";
			}
		} else {
			$output .= '<p><i>' . __( 'Upgrade your version of WordPress for full media support.', 'mtm-menu' ) . '</i></p>';
		}

		if ( $_desc != '' ) {
			$output .= '<span class="of-metabox-desc">' . $_desc . '</span>' . "\n";
		}

		$output .= '<div class="screenshot" id="' . $id . '-image">' . "\n";

		if ( $value != '' ) {
			$remove = '<div><a class="remove-image">Remove</a></div>';
			$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value );
			if ( $image ) {
				$output .= '<img src="' . $value . '" alt="" />' . $remove;
			} else {
				$parts = explode( "/", $value );
				for( $i = 0; $i < sizeof( $parts ); ++$i ) {
					$title = $parts[$i];
				}

				// No output preview if it's not an image.
				$output .= '';

				// Standard generic output if it's not an image.
				$title = __( 'View File', 'mtm-menu' );
				$output .= '<div class="no-image"><span class="file_link"><a href="' . $value . '" target="_blank" rel="external">'.$title.'</a></span></div>';
			}
		}
		$output .= '</div>' . "\n";
		return $output;
	}

	/**
	 * Enqueue scripts for file uploader
	 */
	function mtm_optionsmenu_media_scripts( $hook ) {
		$menu = array();
		$menu['menu_slug'] = "mtm-menu-options";

         if ( substr( $hook, -strlen( $menu['menu_slug'] ) ) !== $menu['menu_slug'] )
	        return;

		if ( function_exists( 'wp_enqueue_media' ) )
			wp_enqueue_media();

		wp_register_script( 'mtm-media-uploader', plugin_dir_url( dirname(__FILE__) ) .'lib/js/media-uploader.js', array( 'jquery' ) );
		wp_enqueue_script( 'mtm-media-uploader' );
		wp_localize_script( 'mtm-media-uploader', 'mtm_optionsmenu_l10n', array(
			'upload' => __( 'Upload', 'mtm-menu' ),
			'remove' => __( 'Remove', 'mtm-menu' )
		) );
	}
}

function mtm_admin_uploader()
{
	$mtm_menu_media_uploader = new Mtm_Media_Uploader;
	$mtm_menu_media_uploader->init();
}	
add_action("init", "mtm_admin_uploader");