<div class="od-message tooltip">
	<p class="description">

<?php if( has_post_thumbnail() ) :
		$sYear     = get_field('image_year');

		$iThumbId  = get_post_thumbnail_id();
		$aThumbUrl = wp_get_attachment_image_src( $iThumbId, 'marker_hover', true );

		$sText = $this->text.' '.__('De tooltip zal als volgt getoond worden:', 'od');
?>
		<?php echo $sText; ?><br><br>
	</p>
	<div class="infoWindow">
		<img src="<?php echo $aThumbUrl[0]; ?>">
		<span id="tip_text"><strong><?php echo $sYear; ?></strong> </span>
	</div>
	<script type='text/javascript'>
		var title 	= document.getElementById("title").value;
		var span 	= document.getElementById("tip_text");
		span.innerHTML+=title;
	</script>

<?php else : echo $this->text; endif; ?>

	</p>
</div>
