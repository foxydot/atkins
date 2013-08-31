<?php
add_theme_support( 'genesis-footer-widgets', 1 );

//add_action('after_setup_theme','msd_child_add_homepage_hero3_sidebars');
function msd_child_add_homepage_hero3_sidebars(){
	genesis_register_sidebar(array(
	'name' => 'Homepage Hero',
	'description' => 'Homepage hero space',
	'id' => 'homepage-top'
			));
	genesis_register_sidebar(array(
	'name' => 'Homepage Widget Area',
	'description' => 'Homepage central widget areas',
	'id' => 'homepage-widgets'
			)); 
}
//add_action('after_setup_theme','msd_child_add_homepage_callout_sidebars');
function msd_child_add_homepage_callout_sidebars(){
	genesis_register_sidebar(array(
	'name' => 'Homepage Callout',
	'description' => 'Homepage call to action',
	'id' => 'homepage-callout'
			));
}
add_action('wp_head', 'collections');

/** Customize search form input box text */
add_filter( 'genesis_search_text', 'custom_search_text' );
function custom_search_text($text) {
	return esc_attr( 'Begin your search here...' );
}

//add_filter('genesis_breadcrumb_args', 'custom_breadcrumb_args');
function custom_breadcrumb_args($args) {
	$args['labels']['prefix'] = ''; //marks the spot
	$args['sep'] = ' > ';
	return $args;
}

remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
add_action('genesis_before_content_sidebar_wrap', 'genesis_do_breadcrumbs');

remove_action( 'genesis_before_post_content', 'genesis_post_info' );
add_action( 'genesis_before_post_title', 'genesis_post_info' );
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
/**
 * Add extra menu locations
 */
register_nav_menus( array(
'footer_menu' => 'Footer Menu'
) );
/**
 * Replace footer
 */
remove_action('genesis_footer','genesis_do_footer');
add_action('genesis_footer','msdsocial_do_footer');
function msdsocial_do_footer(){
	global $msd_social;
	if(has_nav_menu('footer_library_link')){$copyright .= wp_nav_menu( array( 'theme_location' => 'footer_library_link','container_class' => 'ftr-menu','echo' => FALSE ) ).'<br />';}
	if($msd_social){
		$copyright .= '&copy;Copyright '.date('Y').' '.$msd_social->get_bizname().' &middot; All Rights Reserved';
	} else {
		$copyright .= '&copy;Copyright '.date('Y').' '.get_bloginfo('name').' &middot; All Rights Reserved ';
	}
	if(has_nav_menu('footer_menu')){$copyright .= wp_nav_menu( array( 'theme_location' => 'footer_menu','container_class' => 'ftr-menu ftr-links','echo' => FALSE ) );}
	print '<div id="copyright" class="copyright gototop">'.$copyright.'</div><div id="social" class="social creds">';
	if($msd_social){do_shortcode('[msd-social]');}
	print '</div>';
}

/**
 * Reversed out style SCS
 * This ensures that the primary sidebar is always to the left.
 */
//add_action('genesis_before', 'msd_new_custom_layout_logic');
function msd_new_custom_layout_logic() {
	$site_layout = genesis_site_layout();	 
	if ( $site_layout == 'sidebar-content-sidebar' ) {
		// Remove default genesis sidebars
		remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
		remove_action( 'genesis_after_content_sidebar_wrap', 'genesis_get_sidebar_alt');
		// Add layout specific sidebars
		add_action( 'genesis_before_content_sidebar_wrap', 'genesis_get_sidebar' );
		add_action( 'genesis_after_content', 'genesis_get_sidebar_alt');
	}
}


add_action ('genesis_after_header','section_featured_image', 5);
function section_featured_image() {
    $image = get_stylesheet_directory_uri().'/lib/img/ties.jpg';
    print '
    <div id="section-header" style="background-image:url('.$image.');">
        <div class="wrap"></div>
    </div>';
}

add_filter( 'genesis_post_info', 'msdlab_post_info_filter' );
function msdlab_post_info_filter($post_info) {
if ( !is_page() ) {
    $post_info = '[post_date]';
    return $post_info;
}}
