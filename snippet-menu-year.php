<?php

	// get active years
	global $post;
	$oTheme = null;
	if(get_post_type() == 'marker') {
		// load this marker's theme model
		$oMarker = new Marker($post);
		$oTheme = $oMarker->GetTheme();
	} else if(get_post_type() == 'theme') {
		$oTheme = new Theme($post);
	}

	if(!is_null($oTheme)) {
		$aActiveYears = $oTheme->GetYears();
	}

	$aYears = get_terms('year', array('hide_empty' => false)); // list of TermObjects
	$sYearMenu = '<ul class="dropdown-menu">';

	$aYears = array_reverse($aYears); // last year on top

	// loop through all the years and add them to the year-menu
	foreach ($aYears as $oYear) {
		if(is_array($aActiveYears) && !in_array($oYear->slug, $aActiveYears)) {
			$class = 'inactive';
		} else {
			$class = 'active';
		}
		$sYearMenu .= '<li class="' . $class . '" id="menu-item-'.$oYear->term_id.'"><a href="./?jaar='.$oYear->name.'">'.$oYear->name.'</a></li>';
	}
	// closing off the year-menu
	$sYearMenu .= '</ul>';

	echo $sYearMenu;
