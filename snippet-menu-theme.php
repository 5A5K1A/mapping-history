<?php
	
	$sThemeMenu = '<ul id="menu-primary-navigation" class="nav navbar-nav">';
	// Add "Alle markers" pageID = 158
	$sThemeMenu .= '<li id="menu-item-'.ALLMARKERS.'" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-'.ALLMARKERS.'"><a href="'.get_permalink(ALLMARKERS).'">'.get_the_title(ALLMARKERS).'</a></li>';
	// Add "Nieuw op de kaart" pageID = 160
	$sThemeMenu .= '<li id="menu-item-'.NEWONMAP.'" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-'.NEWONMAP.'"><a href="'.get_permalink(NEWONMAP).'">'.get_the_title(NEWONMAP).'</a></li>';

	$args = array(
		'orderby' 		=> 'title',
		'order' 		=> 'ASC',
		'post_type' 	=> 'theme',
		'post_status' 	=> 'publish',
		'suppress_filters' => true,
		'posts_per_page' => -1,
	);
	$aThemes = get_posts($args);
	// loop through all the themes and add them to the theme-menu
	foreach ($aThemes as $oTheme) {
		$sThemeMenu .= '<li id="menu-item-'.$oTheme->ID.'" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-'.$oTheme->ID.'"><a href="'.get_permalink($oTheme->ID).'">'.get_the_title($oTheme->ID).'</a></li>';

		// of object attributen gebruiken?
		// $sThemeMenu .= '<li id="menu-item-'.$oTheme->ID.'" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-'.$oTheme->ID.'"><a href="'.get_permalink($oTheme->ID).'">'.$oTheme->post_title.'</a></li>';
	}
	// closing off the theme-menu
	$sThemeMenu .= '</ul>';

	echo $sThemeMenu;