<?php
	// you're looking for snippet-marker.php
	if( have_posts() ) {
		while( have_posts() ) {
			the_post();
			$oMarker = new Marker();
			echo $oMarker->GetHtml();
		}
	}
?>
