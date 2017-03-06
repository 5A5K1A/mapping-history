<?php
	// you're looking for snippet-theme.php
	if( have_posts() ) {
		while( have_posts() ) {
			the_post();
			$oTheme = new Theme();
			echo $oTheme->GetHtml();
		}
	}
?>
