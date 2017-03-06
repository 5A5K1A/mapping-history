<?php


// setup  image sizes
if( function_exists('add_image_size') ) {
	// setup image sizes: $name, $width, $height, $crop
	add_image_size( 'header_image', 500,  300, true  );
	add_image_size( 'fancybox',    1280, 9999, false );
	add_image_size( 'related',      100,  100, true  );
	add_image_size( 'marker_hover',  50,   50, true  );
}

// editor images: https://premium.wpmudev.org/blog/adding-custom-images-sizes-to-the-wordpress-media-library/
add_filter('image_size_names_choose', function( $aSizes ) {
	$aAddSizes = array(
		'header_image' => __( 'Header image', 'od' ),
	);
	$aNewSizes = array_merge( $aSizes, $aAddSizes );
	return $aNewSizes;
});





// add image_year automaticly to associated/parent post (post where this attachment is used as featured image)
add_action( 'edit_attachment', 'save_year_to_post' );
function save_year_to_post( $attachment_id ) {
	if ( isset( $_REQUEST['attachments'][$attachment_id]['jaar'] ) ) {
		$sYear 		= $_REQUEST['attachments'][$attachment_id]['jaar'];

		// get IDs of associated posts (marker/theme)
		$args = array(
			'post_type' => array('marker', 'theme'),
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' 	=> 'connected_image',
					'value' => $attachment_id
				)
			),
		);
		$posts = get_posts($args);

		// get field for this particular meta data field
		$aField 	= acf_get_field('image_year');

		foreach ($posts as $post) {
			//update ACF in parent-post
			update_field( $aField['key'], $sYear, $post->ID );
		}
	}
}

// only filter img caption when not on the frontpage
add_filter('img_caption_shortcode', 'od_img_caption_shortcode_filter', 10, 3);

/**
 * Filter to replace the [caption] shortcode text with HTML5 compliant code
 * @return text HTML content describing embedded figure
 **/
function od_img_caption_shortcode_filter($output, $attr, $content = null) {

	// Set up the default arguments.
	$defaults = array(
		'id'      => '',
		'align'   => 'alignnone',
		'width'   => 380,
		'caption' => ''
	);

	// Merge the defaults with user input.
	$attr = shortcode_atts( $defaults, $attr );

	// @TODO grab images by ID, or just don't add any captionable text in WP admin
	if ( 1 > $attr['width'] || empty($attr['caption']) ) {
		return $content;
	}

	// Set up the attributes for the caption <div>.
	$attributes = ( !empty( $attr['id'] ) ? ' id="' . esc_attr( $attr['id'] ) . '"' : '' );
	$attributes .= ' class="wp-caption ' . esc_attr( $attr['align'] ) . '"';
	$attributes .= ' style="width: ' . esc_attr( $attr['width'] ) . 'px;"';

	// Open the caption <div>.
	$output = '<div' . $attributes .'>';

	// Allow shortcodes for the content the caption was created for.
	$output .= do_shortcode( $content );

	// append data to the caption
	$iImageID 	= filter_var($attr['id'], FILTER_SANITIZE_NUMBER_INT); // extract integer from string

	// Append the caption text.
	$output .= '<p class="wp-caption-text">'.od_rewrite_caption($iImageID, $attr['caption']).'</p>';

	// Close the caption </div>.
	$output .= '</div>';

	// Return the formatted, clean caption.
	return $output;
}

function od_get_the_post_thumbnail_caption() {
	global $post;

	$thumbnail_id    = get_post_thumbnail_id($post->ID);
	$thumbnail_image = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));

	if ($thumbnail_image && isset($thumbnail_image[0]) && !empty($thumbnail_image[0]->post_excerpt)) {
		return '<p class="wp-caption-text">'.od_rewrite_caption( $thumbnail_id, $thumbnail_image[0]->post_excerpt ).'</p>';
	}
}

function od_the_post_thumbnail_caption() {
	echo od_get_the_post_thumbnail_caption();
}

function od_rewrite_caption( $id, $caption ) {
	// get metadata
	$sYear 			= get_field( 'jaar', $id );
	$sDate 			= get_field( 'datum', $id );
	$sCredit 		= get_field( 'fotograaf', $id );
	$sArtist 		= get_field( 'maker', $id );
	$sCollection 	= get_field( 'collectie', $id );

	// building the caption
	$sCaption = '';
	if ( $sYear != '' )       { $sCaption .= '<strong>'.$sYear.'</strong> - '.$caption; }
	if ( $sDate != '' )       { $sCaption .= ', '.$sDate.' '.$sYear; }
	if ( $sCredit != '' )     { $sCaption .= '<span class="credits">Fotograaf: '.$sCredit.'</span>'; }
	if ( $sArtist != '' )     { $sCaption .= '<span class="credits">Maker: '.$sArtist.'</span>'; }
	if ( $sCollection != '' ) { $sCaption .= '<span class="credits">Collectie: '.$sCollection.'</span>'; }

	return $sCaption;
}

//rewrite the_post_thumbnail() output
function od_modify_post_thumbnail_html($html, $post_id, $post_thumbnail_id, $size) {
	if(!is_admin()) {
		$id = get_post_thumbnail_id(); // gets the id of the current post_thumbnail (in the loop)
		$src = wp_get_attachment_image_src($id, $size); // gets the image url specific to the passed in size (aka. custom image size)
		$alt = get_the_title($id); // gets the post thumbnail title
		$url = wp_get_attachment_image_src($id, 'fancybox');

		$sCaption = get_the_post_thumbnail_caption();
		// if ($sCaption) {
		// 	$sCaption = '<br>'.$sCaption;
		// }

		$html = <<<EOHTML
			<div class="photo" id="fancybox-click" type="button">
				<a href="{$url[0]}"><img src="{$src[0]}" alt="{$alt}" class=""></a>
				{$sCaption}
			</div>
EOHTML;
	}
    return $html;
}
add_filter('post_thumbnail_html', 'od_modify_post_thumbnail_html', 99, 5);
