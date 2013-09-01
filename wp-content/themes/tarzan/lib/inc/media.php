<?php
/**
 * Add new image sizes
 */
add_image_size('post-thumb', 225, 160, TRUE);
add_image_size('post-image', 315, 235, TRUE);

add_image_size('child_full', 710, 236, TRUE);
add_image_size('child_thumbnail', 341, 114, TRUE);


/**
 * Add new image sizes to the media panel
 */
if(!function_exists('msd_insert_custom_image_sizes')){
function msd_insert_custom_image_sizes( $sizes ) {
	global $_wp_additional_image_sizes;
	if ( empty($_wp_additional_image_sizes) )
		return $sizes;

	foreach ( $_wp_additional_image_sizes as $id => $data ) {
		if ( !isset($sizes[$id]) )
			$sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
	}

	return $sizes;
}
}
add_filter( 'image_size_names_choose', 'msd_insert_custom_image_sizes' );


/* Manipulate the featured image */
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
remove_action( 'genesis_post_content', 'genesis_do_post_image' );
add_action( 'genesis_entry_content', 'msd_post_image', 5 );
add_action( 'genesis_post_content', 'msd_post_image', 5 );
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
    if(has_post_thumbnail()){
        $thumb = genesis_get_image( array( 'size' => $size, 'attr' => $default_attr ) ) ;
    } else {
        $thumb = wp_get_attachment_image( get_option('fallback_image'), $size, false, $default_attr );
    }
    printf( '<a title="%s" href="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), $thumb);
}