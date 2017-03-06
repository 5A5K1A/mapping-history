<?php
/**
 * Load admin styles and scripts
 */
add_action( 'admin_enqueue_scripts', function() {
	// css
	wp_enqueue_style( 'od_admin_css', get_stylesheet_directory_uri() . '/dist/css/admin.css', $deps = false, $ver = false, $media = 'all');
	// js
	wp_enqueue_script( 'od_admin_js', get_stylesheet_directory_uri() . '/dist/js/admin.min.js', $deps = false, $ver = false, $in_footer = true);
});

/* Extra filter for the_title breakingpoints
/* ------------------------------------ */
add_filter( 'the_title', function( $title ) {
	return str_replace('||', '&shy;', $title);
});

/* Rename WP standard 'Berichten' to 'Nieuws'
/* ------------------------------------ */
add_action( 'admin_menu', function() {
	global $menu;
	global $submenu;
	$menu[5][0] = 'Nieuws';
	$submenu['edit.php'][5][0] 	= __('Nieuws', 'od');
	$submenu['edit.php'][10][0] = __('Nieuws toevoegen', 'od');
	$submenu['edit.php'][16][0] = __('Nieuws Tags', 'od');

	// remove certain menu items
	remove_menu_page('edit.php'); // Posts/News isn't used in this
	remove_menu_page('edit-comments.php'); // No need to control the comments
	remove_menu_page('occhiodocumentationpage'); // Duplicate / deprecated

});

/* commented out now, since they don't use any news/blogposts now */
// add_action( 'init', function() {
// 	global $wp_post_types;
// 	$labels = &$wp_post_types['post']->labels;
// 	$labels->name 			= __('Nieuws', 'od');
// 	$labels->singular_name 	= __('Nieuws', 'od');
// 	$labels->all_items 		= __('Alle nieuws', 'od');
// 	$labels->add_new 		= __('Nieuws toevoegen', 'od');
// 	$labels->add_new_item 	= __('Nieuws toevoegen', 'od');
// 	$labels->edit_item 		= __('Bewerk nieuws', 'od');
// 	$labels->new_item 		= __('Nieuws', 'od');
// 	$labels->view_item 		= __('Bekijk Nieuws', 'od');
// 	$labels->search_items 	= __('Zoek nieuws', 'od');
// 	$labels->not_found 		= __('Geen nieuws gevonden', 'od');
// 	$labels->not_found_in_trash = __('Geen nieuws gevonden gevonden in de prullenbak', 'od');
// 	$labels->all_items 		= __('Alle nieuws', 'od');
// 	$labels->menu_name 		= __('Nieuws', 'od');
// 	$labels->name_admin_bar = __('Nieuws', 'od');
// });


if ( is_user_logged_in() ) {
    add_filter('body_class','add_role_to_body');
    add_filter('admin_body_class','add_role_to_body');
}
function add_role_to_body( $classes ) {
    $current_user = new WP_User(get_current_user_id());
    $user_role = array_shift($current_user->roles);
    if (is_admin()) {
        $classes .= 'role-'. $user_role;
    } else {
        $classes[] = 'role-'. $user_role;
    }
    return $classes;
}

/* Change the admin footer #sluikreclame
/* ------------------------------------ */
add_filter( 'admin_footer_text', function() {
	echo '<span id="footer-thankyou">'.__('Deze website is ontwikkeld door', 'od').' <a href="https://www.occhio.nl/" target="_blank">Occhio</a> i.s.m. <a href="http://www.mappinghistory.nl/" target="_blank">Mapping History</a></span>';
});

#===================================================#
# Customn columns for markers & themes in admin 	#
#===================================================#
add_filter('manage_posts_columns', 'od_maphist_columns', 5);
function od_maphist_columns($columns) {
	if ( in_array(get_post_type(), array('marker', 'theme')) ) {
		$columns = array_slice($columns, 0, 1, true) +
		array('thumb' => 'Foto') +
		array_slice($columns, 1, count($columns)-1, true);
		if ( in_array(get_post_type(), array('theme')) ) {
			$columns = array_slice($columns, 0, 3, true) +
				array('marker' => 'Marker(s)') +
				array_slice($columns, 3, count($columns)-1, true);
		} else {
			$columns = array_slice($columns, 0, 3, true) +
				array('theme' => 'Thema') +
				array_slice($columns, 3, count($columns)-1, true);
		}
	}
	return $columns;
}

add_action('manage_pages_custom_column', 'od_maphist_custom_columns', 5, 2);
function od_maphist_custom_columns($column_name, $id) {
	switch ($column_name) {
		case 'thumb' :
			$iImageID 	= get_post_thumbnail_id();
			$sImageUrl 	= wp_get_attachment_image_src($iImageID, 'thumbnail');
			echo '<a href="' . get_edit_post_link() . '"><img src="' . $sImageUrl[0] . '"></a>';
			break;
		case 'theme' :
			$oTheme = get_field('theme');
			if ($oTheme) {
				echo $oTheme->post_title;
				if( !current_user_can('activate_plugins') ) {
					$oTheme->post_title .= '<div class="row-actions"><span class="edit"><a href="' . get_edit_post_link($oTheme->post_title->ID) . '">Bewerk het gekoppelde kaartthema</a></span></div>';
				}
			} else {
				echo '<span aria-hidden="true">—</span>';
			}
			break;
		case 'marker' :
			$connected = get_connected_markers(get_the_ID());
			if ( $connected ) {
				foreach ( $connected as $post ) {
					echo '<li><a href="'.get_edit_post_link($post->ID).'">'.get_the_title($post->ID).'</a></li>';
				}
			}
			break;
	}
}

// add some extra text to "featured image" box in WP-admin
function add_featured_image_instruction( $content ) {
	if ( get_post_type() != 'post') {
		if (has_post_thumbnail()) {
			$iImageID 		= get_post_thumbnail_id(get_the_ID());
			$content 	.= '<p>Bij deze foto zijn de onderstaande gegevens opgeslagen.<br>
				Klik op de foto hierboven om deze aan te passen.<br>
				Of pas de gegevens aan in de <a target="_blank" href="post.php?post='.$iImageID.'&action=edit">Media Bibliotheek</a>.</p>';
			$content 	.= '<dl><style>dt { display: inline-block; float: left; } dd { padding-left: 30px; }</style>';
			$sCredit 		= get_post_meta( $iImageID, 'fotograaf' );
			$sArtist 		= get_post_meta( $iImageID, 'maker' );
			$sCollection 	= get_post_meta( $iImageID, 'collectie' );
			$sYear 			= get_post_meta( $iImageID, 'jaar' );
			$sDate 			= get_post_meta( $iImageID, 'datum' );
			if ($sCredit[0]) {
				$content .= '<dt>Fotograaf</dt><dd>'.$sCredit[0] . '</dd>';
			}
			if ($sArtist[0]) {
				$content .= '<dt>Maker</dt><dd>'.$sArtist[0] . '</dd>';
			}
			if ($sCollection[0]) {
				$content .= '<dt>Collectie</dt><dd>'.$sCollection[0] . '</dd>';
			}
			if ($sYear[0]) {
				$content .= '<dt>Jaar</dt><dd>'.$sYear[0] . '</dd>';
				$aField = acf_get_field('image_year');
				update_field($aField['key'], $sYear[0], get_the_ID());
			}
			if ($sDate[0]) {
				$content .= '<dt>Datum</dt><dd>'.$sDate[0] . '</dd>';
			}
			$content .= '</dl>';
		}
	}
	return $content;
}
add_filter( 'admin_post_thumbnail_html', 'add_featured_image_instruction');


function get_connected_markers($postID) {
	$args = array(
		'post_type' => 'marker',
		'meta_query'             => array(
			array(
				'key'       => 'theme',
				'value'     => $postID,
			),
		),
	);

	// The Query
	$connected 	= new WP_Query($args);
	if ( $connected->have_posts() ) {
		return $connected->posts;
	}
}

// hide admin sections for certain users
add_action('wp_dashboard_setup', function() {
	if( !current_user_can('activate_plugins') ) {
		remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); // Plugins
	}
	if( !current_user_can('publish_pages') ) {
		remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // Right Now
	}

	remove_action('welcome_panel', 'wp_welcome_panel');
	remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal'); // Gravity Forms
	// remove_meta_box('icl_dashboard_widget', 'dashboard', 'normal'); // Multi Language Plugin
	remove_meta_box('dashboard_activity', 'dashboard', 'normal'); // Activity
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // Recent Comments
	remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // Quick Press widget
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); // Recent Drafts
	remove_meta_box('dashboard_primary', 'dashboard', 'side'); // WordPress.com Blog
	remove_meta_box('dashboard_secondary', 'dashboard', 'side'); // Other WordPress News
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); // Incoming Links
});

// nice logo for admin login
add_filter( 'login_headerurl', function() { ?>
	<style>
		body {
			background-color: <?php echo BASE_COLOR; ?>;
		}
		#login h1 a {
			background: none;
		}
		#login form {
			background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/src/img/mh-logo.png');
			background-repeat: no-repeat;
			background-size: 85%;
			background-position: center 30px;
			padding-top: 145px;
		}
		.login #backtoblog,
		.login #nav {
			text-align: center;
		}
		.login #backtoblog a,
		.login #nav a {
			color: white;
			mix-blend-mode: soft-light;
			font-size: 1.2em;
		}
		.login #backtoblog a:hover,
		.login #backtoblog a:focus,
		.login #nav a:hover,
		.login #nav a:focus {
			color: white;
			mix-blend-mode: initial;
		}
	</style>
<?php
});

/**
 * Modify TinyMCE
 *
 * @param array $in
 * @return array $in
 */
function my_tiny_mce_before_init( $in ) {

	# customize the buttons
	$in['toolbar1'] = 'formatselect,charmap,bold,italic,blockquote,link,unlink,pastetext,removeformat,undo,redo,wp_help';
	$in['toolbar2'] = NULL;

	return $in;
}
add_filter( 'tiny_mce_before_init', 'my_tiny_mce_before_init' );

add_action( 'add_meta_boxes', 'add_marker_metabox' );
function add_marker_metabox() {
	// add_meta_box( $id, $title, $callback, $page, $context, $priority, $callback_args );
	add_meta_box('mh_marker', 'Marker velden', 'mh_marker_location', 'marker', 'side', 'high');
}

function mh_marker_location() {
	global $post;

	if (get_the_title() != '') { $aRequir['Titel'] = 'checked'; } else { $aRequir['Titel'] = ''; }
	if (has_post_thumbnail()) { $aRequir['Uitgelichte afbeelding'] = 'checked'; } else { $aRequir['Uitgelichte afbeelding'] = ''; }
	if (get_field('theme') != '') { $aRequir['Kaartthema'] = 'checked'; } else { $aRequir['Kaartthema'] = ''; }
	if (get_field('marker_location') != '') { $aRequir['Lokatie'] = 'checked'; } else { $aRequir['Lokatie'] = ''; }
	if (count(wp_get_post_terms(get_the_ID(), 'year')) > 0) { $aOptional['Jaartallen'] = 'checked'; } else { $aOptional['Jaartallen'] = ''; }
	if (get_field('image_sv') != '') { $aOptional['Streetview'] = 'checked'; } else { $aOptional['Streetview'] = ''; }
	if (count(get_field('marker_related')) > 0) { $aOptional['Gerelateerde Markers'] = 'checked'; } else { $aOptional['Gerelateerde Markers'] = ''; }

	$sList = <<<EOHTML
		<style>
			ul.requirments {
				font-weight: 600;
				color: red;
			}
			ul.requirments, ul.optionals {
				margin-top: 5px;
			}
			ul.requirments li, ul.optionals li {
				padding-left: 10px;
			}
			ul.requirments li.checked, ul.optionals li.checked {
				color: green;
			}
			ul.requirments li input[type=checkbox]:before, ul.optionals li input[type=checkbox]:before {
				opacity: 1 !important;
			}
			ul.requirments li input[type=checkbox]:disabled, ul.optionals li input[type=checkbox]:disabled {
				border: 0px;
			}
		</style>
		<strong>Verplicht</strong>
		<ul class="requirments">
EOHTML;

	foreach($aRequir as $key => $val) {
		$sList .= '<li class="'.$val.'">';
		$sList .= '<input type="checkbox" disabled readonly '.$val.'>';
		$sList .= $key.'</li>';
	}

	$sList .= <<<EOHTML

		</ul>
		<strong>Optioneel</strong>
		<ul class="optionals">
EOHTML;

	foreach($aOptional as $key => $val) {
		$sList .= '<li class="'.$val.'">';
		$sList .= '<input type="checkbox" disabled readonly '.$val.'>';
		$sList .= $key.'</li>';
	}

	$sList .= <<<EOHTML

		</ul>
EOHTML;

	echo $sList;
}



/* Add Custom Post Type to WP-ADMIN Right Now Widget
/* ------------------------------------ */
add_action( 'dashboard_glance_items', function() { ?>
	<style>
	#dashboard_right_now a.marker-count:before { content: "\f230"; }
	#dashboard_right_now a.theme-count:before { content: "\f323"; }
	li.comment-count { display: none; }
	</style>
<?php
	$args = array(
		'public' 	=> true ,
		'_builtin' 	=> false
	);
	$aPostTypes = get_post_types( $args , 'object' , 'and' );

	foreach( $aPostTypes as $oPostType ) {
		if( $oPostType->name == 'pronamic_pay_form' ) { continue; }
		$iTotal 		= number_format_i18n( wp_count_posts( $oPostType->name )->publish );
		$sOutput 		= _n( $oPostType->labels->name, $oPostType->labels->name, intval( wp_count_posts( $oPostType->name )->publish ) );
		$sPostTypeName 	= $oPostType->name;

		if( current_user_can('edit_posts') ) {
			echo '<li class="'.$sPostTypeName.'-count"><a href="edit.php?post_type='.$sPostTypeName.'" class="'.$sPostTypeName.'-count">'.$iTotal.' '.$sOutput.'</a></li>';
		}
	}
});

/* Convert absolute URLs in content to site relative ones
/* Inspired by http://thisismyurl.com/6166/replace-wordpress-static-urls-dynamic-urls/
/* ------------------------------------ */
add_filter( 'content_save_pre', function( $content ) {

	$sSiteURL = get_bloginfo('url');
	$sNewContent = str_replace(' src=\"'.$sSiteURL, ' src=\"', $content );
	$sFilteredContent = str_replace(' href=\"'.$sSiteURL, ' href=\"', $sNewContent );

	return $sFilteredContent;
},'99');

/* remove the WP-button on the left top in admin
/* ------------------------------------ */
add_action( 'wp_before_admin_bar_render', function() {
	global $wp_admin_bar; //p($wp_admin_bar);

	$wp_admin_bar->remove_menu( 'wp-logo' );
	$wp_admin_bar->remove_menu( 'comments' );
	$wp_admin_bar->remove_node( 'new-post' );
});

add_filter( 'login_errors', function(){
	return __('Er is iets mis!', 'od');
});

add_filter( 'enter_title_here', function( $title ) {
	if( get_post_type() == 'page' ) {
		$title = 'Vul een paginatitel in';
	} elseif( get_post_type() == 'theme' ) {
		$title = 'Vul een korte titel in';
	}
	return $title;
});

