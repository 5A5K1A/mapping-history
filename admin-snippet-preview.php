<?php
	$sItem        = ( $this->posttype == 'marker' ) ? __('deze marker', 'od') : __('dit thema', 'od');
	$oPostType    = get_post_type_object( $this->posttype );


	$sExtraStyles = ( current_user_can('activate_plugins') ) ? NULL : <<<EOHTML
			div.acf-field-56c59eae39d16,
			div.acf-field-56c5e3fc04078,
			div.acf-field-56bb49ebec61b,
			div.acf-field-56cee3950763e,
			div#A2A_SHARE_SAVE_meta {
			   display: none;
			}

EOHTML;

?>
		<style>
			div.acf-label label {
				font-size: 18px !important;
			}
			<?php echo $sExtraStyles; ?>
		</style>
<?php

	if( has_post_thumbnail() ) :

		$sFeaturedLabel = $oPostType->labels->featured_image;

		$iThumbId   = get_post_thumbnail_id();
		$sThumbUrl  = wp_get_attachment_image_src($iThumbId,'header_image', true);
		$aThumbs    = get_posts(array('p' => $iThumbId, 'post_type' => 'attachment'));

		// get metadata
		$sYear 	     = get_field( 'jaar', $iThumbId );
		$sDate 	     = get_field( 'datum', $iThumbId );
		$sCredit     = get_field( 'fotograaf', $iThumbId );
		$sArtist     = get_field( 'maker', $iThumbId );
		$sCollection = get_field( 'collectie', $iThumbId );

		// building the caption
		$sCaption = '';
		if( $sYear != '' )       { $sCaption .= '<strong>'.$sYear.'</strong> - '.$aThumbs[0]->post_excerpt; }
		if( $sDate != '' )       { $sCaption .= ', '.$sDate.' '.$sYear; }
		if( $sCaption != '' )    { $sCaption .= '<br>'; }
		if( $sCredit != '' )     { $sCaption .= '<span class="credits">Fotograaf: '.$sCredit.'</span><br>'; }
		if( $sArtist != '' )     { $sCaption .= '<span class="credits">Maker: '.$sArtist.'</span><br>'; }
		if( $sCollection != '' ) { $sCaption .= '<span class="credits">Collectie: '.$sCollection.'</span>'; }

?>
			<div id="previewimagediv" class="postbox closed">
				<button type="button" class="handlediv button-link" aria-expanded="false"><span class="screen-reader-text">Preview <?php echo $sFeaturedLabel; ?> verbergen</span><span class="toggle-indicator" aria-hidden="true"></span></button><h2 class="hndle ui-sortable-handle"><span><strong>Preview <?php echo $sFeaturedLabel; ?> (incl. onderschrift)</strong></span></h2>
				<div class="inside">
					<div class="hide-if-no-js">
						<div class="od-preview">
							<div class="image" style="background-image: url(<?php echo $sThumbUrl[0]; ?>);"></div>
							<div class="od-caption">
								<p><?php echo $sCaption; ?></p>
							</div>
						</div>
						<div class="explain" style="">
							<p class="description">Er zijn twee manieren om het onderschrift bij deze foto aan te passen:</p>
							<ol>
								<li>je kunt op de <?php echo $sFeaturedLabel; ?> (rechts in de zijbalk) klikken en daar de betreffende velden aanpassen.<br> Vervolgens dien je weer op "<?php echo $sFeaturedLabel; ?> instellen" te klikken en op "Bijwerken", zo worden deze aanpassingen ook opgeslagen bij <?php echo $sItem; ?>.</li>
								<li>je kunt de afbeelding in de <a target="_blank" href="post.php?post=<?php echo $iThumbId; ?>&action=edit">Media Bibliotheek</a> opzoeken en aldaar bewerken.</li>
							</ol>
						</div>
					</div>
				</div>
			</div>

<?php endif; ?>
