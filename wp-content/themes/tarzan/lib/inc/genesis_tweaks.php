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
//add_action('wp_head', 'collections');

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

add_filter( 'excerpt_length', 'msdlab_excerpt_length', 999 );
function msdlab_excerpt_length( $length ) {
    return 140;
}

add_filter( 'excerpt_more', 'msdlab_excerpt_more' );
function msdlab_excerpt_more( $more ) {
    return '&hellip; <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">Continue Reading <i class="icon-caret-right icon-large"></i></a>';
}

add_filter( 'get_the_author_genesis_author_box_single', '__return_true' );
add_filter('genesis_author_box','msdlab_author_box');
function msdlab_author_box($author_box){
    global $authordata;
    $authordata    = is_object( $authordata ) ? $authordata : get_userdata( get_query_var( 'author' ) );
    $social_keys = array(
        'jabber',
        'aim',
        'yim',
        'twitter',
        'facebook',
        'linkedin',
        'flickr',
        'myspace',
        'friendfeed',
        'delicious',
        'digg',
        'feed',
        'tumblr',
        'youtube',
        'blogger',
        'googleplus',
        'instagram',
        'slideshare',
        'stackoverflow',
        'posterous',
        'pinterest'
    );
    foreach($social_keys AS $sk){
        $link = get_the_author_meta($sk,$authordata->ID);
        if(!empty($link)){
            $social[$sk] = $link;
        }
    }
    ts_data($social);
}

/*Add Social URLs*/
function msdlab_author_contactmethods( $contactmethods ) {
    if ( !isset( $contactmethods['twitter'] ) )
        $contactmethods['twitter'] = 'Twitter';
    if ( !isset( $contactmethods['facebook'] ) )
        $contactmethods['facebook'] = 'Facebook';
    if ( !isset( $contactmethods['linkedin'] ) )
        $contactmethods['linkedin'] = 'LinkedIn';   
    if ( !isset( $contactmethods['flickr'] ) )
        $contactmethods['flickr'] = 'Flickr';   
    if ( !isset( $contactmethods['myspace'] ) )
        $contactmethods['myspace'] = 'MySpace';     
    if ( !isset( $contactmethods['friendfeed'] ) )
        $contactmethods['friendfeed'] = 'Friendfeed';   
    if ( !isset( $contactmethods['delicious'] ) )
        $contactmethods['delicious'] = 'Delicious';     
    if ( !isset( $contactmethods['digg'] ) )
        $contactmethods['digg'] = 'Digg';   
    if ( !isset( $contactmethods['feed'] ) )
        $contactmethods['feed'] = 'XML Feed';   
    if ( !isset( $contactmethods['tumblr'] ) )
        $contactmethods['tumblr'] = 'Tumblr';   
    if ( !isset( $contactmethods['youtube'] ) )
        $contactmethods['youtube'] = 'YouTube'; 
    if ( !isset( $contactmethods['blogger'] ) )
        $contactmethods['blogger'] = 'Blogger'; 
    if ( !isset( $contactmethods['googleplus'] ) )
        $contactmethods['googleplus'] = 'Google+'; 
    if ( !isset( $contactmethods['instagram'] ) )
        $contactmethods['instagram'] = 'Instagram'; 
    if ( !isset( $contactmethods['slideshare'] ) )
        $contactmethods['slideshare'] = 'Slideshare'; 
    if ( !isset( $contactmethods['stackoverflow'] ) )
        $contactmethods['stackoverflow'] = 'Stackoverflow'; 
    if ( !isset( $contactmethods['posterous'] ) )
        $contactmethods['posterous'] = 'Posterous'; 
    if ( !isset( $contactmethods['pinterest'] ) )
        $contactmethods['pinterest'] = 'Pinterest'; 
            
    return $contactmethods;
}

add_filter('user_contactmethods','msdlab_author_contactmethods');
