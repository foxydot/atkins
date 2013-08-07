<?php
add_theme_support( 'genesis-footer-widgets', 1 );

add_action('after_setup_theme','msd_child_add_homepage_hero3_sidebars');
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

add_filter('genesis_breadcrumb_args', 'custom_breadcrumb_args');
function custom_breadcrumb_args($args) {
	$args['labels']['prefix'] = ''; //marks the spot
	$args['sep'] = ' > ';
	return $args;
}

remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
add_action('genesis_before_content_sidebar_wrap', 'genesis_do_breadcrumbs');

remove_action( 'genesis_before_post_content', 'genesis_post_info' );
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
//add_action('genesis_footer','msdsocial_do_footer');
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
add_action('genesis_before', 'msd_new_custom_layout_logic');
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
/** Add new image sizes */
add_image_size( 'post-image', 540, 150, TRUE ); //image to float at the top of the post. Reversed Out does these a lot.

/* Manipulate the featured image */
add_action( 'genesis_before_post', 'msd_post_image', 8 );
function msd_post_image() {
	global $post;
   	//setup thumbnail image args to be used with genesis_get_image();
	$size = 'post-image'; // Change this to whatever add_image_size you want
	$default_attr = array(
			'class' => "alignright attachment-$size $size",
			'alt'   => $post->post_title,
			'title' => $post->post_title,
	);

	// This is the most important part!  Checks to see if the post has a Post Thumbnail assigned to it. You can delete the if conditional if you want and assume that there will always be a thumbnail
	if ( has_post_thumbnail() && is_page() ) {
		printf( '<a title="%s" href="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), genesis_get_image( array( 'size' => $size, 'attr' => $default_attr ) ) );
	}

}