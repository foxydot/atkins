<?php
/**
 * Add a hero space with the site description
 */
function msd_child_hero(){
	if(is_active_sidebar('homepage-top')){
		print '<div id="hp-top">';
		print '<div class="wrap">';
		dynamic_sidebar('homepage-top');
		print '</div>';
		print '<div class="wrap2">';
		do_action( 'genesis_site_description' );
		print '</div>';
		print '</div>';
	}
}

/**
 * Add a hero space with the site description
 */
function msd_callout(){
	if(is_active_sidebar('homepage-callout')){
		print '<div id="hp-callout">';
		print '<div class="wrap">';
		dynamic_sidebar('homepage-callout');
		print '</div>';
		print '</div>';
	}
}

/**
 * Add three widget areas
 */
function msd_child_homepage_widgets(){
	print '<div id="hp-bot">';
	print '<div class="wrap">';
	print '<div id="widgets-one" class="widget-area"><div class="wrap">';
	dynamic_sidebar('homepage-one');
	print '</div></div>';
	print '<div id="widgets-two" class="widget-area"><div class="wrap">';
	dynamic_sidebar('homepage-two');
	print '</div></div>';
	print '<div id="widgets-three" class="widget-area"><div class="wrap">';
	dynamic_sidebar('homepage-three');
	print '</div></div>';
	print '</div>';
	print '</div>';
}

/**
 * Create a long scrollie page with child pages of homepage.
 * Uses featured image for background of each wrap section.
 */
function msd_scrollie_page(){
	global $post;
	$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
	$background = $thumbnail?' style="background-image:url('.$thumbnail[0].');"':'';
	print '<div id="intro" class="scrollie parent div-intro div0">
				<div class="background-wrapper"'.$background.'>
						<div class="wrap">
							<div class="page-content">
								<div class="entry-content">'.apply_filters('the_content', $post->post_content).'</div>
							</div>
						</div>
					</div>
				</div>';
	print '<div id="callout"><p>'.get_option('blogdescription').'</p></div>';
	$my_wp_query = new WP_Query();
	$pages = $my_wp_query->query(array('post_type' => 'page','order' => 'ASC','orderby' => 'menu_order'));
	$children = get_page_children($post->ID, $pages);
	$i = 1;
	foreach($children AS $child){
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($child->ID), 'full' );
		$background = $thumbnail?' style="background-image:url('.$thumbnail[0].');"':'';
		$form = $child->post_name=='contact-us'?do_shortcode('[gravityform id="1" name="Untitled Form" title="false" ajax="true"]'):'';
		print '<div id="'.$child->post_name.'" class="scrollie child div-'.$child->post_name.' div'.$i.'">
				<div class="background-wrapper"'.$background.'>
						<div class="wrap">'.$form.'
							<div class="page-content">
								<h2 class="entry-title">'.$child->post_title.'</h2>
								<div class="entry-content">'.apply_filters('the_content', $child->post_content).'</div>
							</div>
						</div>
					</div>
				</div>';
		$i++;
	}
}