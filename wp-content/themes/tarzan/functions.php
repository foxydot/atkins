<?php
/** Start the engine */
require_once( get_template_directory() . '/lib/init.php' );

/*
 * Pull in some stuff from other files
*/
if(!function_exists('requireDir')){
	function requireDir($dir){
		$dh = @opendir($dir);

		if (!$dh) {
			throw new Exception("Cannot open directory $dir");
		} else {
			while($file = readdir($dh)){
				$files[] = $file;
			}
			closedir($dh);
			sort($files); //ensure alpha order
			foreach($files AS $file){
				if ($file != '.' && $file != '..') {
					$requiredFile = $dir . DIRECTORY_SEPARATOR . $file;
					if ('.php' === substr($file, strlen($file) - 4)) {
						require_once $requiredFile;
					} elseif (is_dir($requiredFile)) {
						requireDir($requiredFile);
					}
				}
			}
		}
		unset($dh, $dir, $file, $requiredFile);
	}
}
requireDir(get_stylesheet_directory() . '/lib/inc');

/*
 * Add styles and scripts
*/
add_action('wp_print_styles', 'msd_add_styles');

function msd_add_styles() {
	global $is_IE;
	if(!is_admin()){
		wp_enqueue_style('msd-style',get_stylesheet_directory_uri().'/lib/css/style.css');
		if($is_IE){
			wp_enqueue_script('ie-style',get_stylesheet_directory_uri().'/lib/css/ie.css');
		}
		if(is_front_page()){
			wp_enqueue_style('msd-homepage-style',get_stylesheet_directory_uri().'/lib/css/homepage.css');
		}
	}
}
add_action('wp_print_scripts', 'msd_add_scripts');

function msd_add_scripts() {
	global $is_IE;
	if(!is_admin()){
		wp_enqueue_script('msd-jquery',get_stylesheet_directory_uri().'/lib/js/theme-jquery.js');
		wp_enqueue_script('equalHeights',get_stylesheet_directory_uri().'/lib/js/jquery.equalheights.js');
		if($is_IE){
			wp_enqueue_script('columnizr',get_stylesheet_directory_uri().'/lib/js/jquery.columnizer.js');
			wp_enqueue_script('ie-fixes',get_stylesheet_directory_uri().'/lib/js/ie-jquery.js');
		}
		if(is_front_page()){
			wp_enqueue_script('msd-homepage-jquery',get_stylesheet_directory_uri().'/lib/js/homepage-jquery.js');
		}
	}
}