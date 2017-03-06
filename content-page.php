<?php
	// you're looking for snippet-page.php
	if( have_posts() ) {
		while( have_posts() ) {
			the_post();
			$oPage = new Page();
			echo $oPage->GetHtml();
		}
	}
?>
