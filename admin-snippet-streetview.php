<?php od_publish_button($this->text); ?>

<?php add_thickbox(); ?>

<div class="od-message">
	<div class="acf-label">
		<br><br>
		<label><?php _e('Google Street View (optioneel)','od'); ?></label>
		<p class="description">Klik eerst op de knop "<?php echo $this->text; ?>" (hierboven) voordat je een Google Street View toe voegt.</p>
	</div>

	<a href="media-upload.php?post_id=<?php the_ID(); ?>&type=streetview&tab=streetview&TB_iframe=true&max-height=400&max-width=640" class="button thickbox" title="Voeg Google Street View toe"><span><?php _e('Voeg Google Street View toe', 'od'); ?></span></a>
	<p class="description" id="succes"></p>
</div>

<?php if( !current_user_can('activate_plugins') ) : ?>
	<style>
		div.acf-field-56c59eae39d16,
		div.acf-field-56c5e3fc04078,
		div#A2A_SHARE_SAVE_meta {
		   display: none;
		}
	</style>
<?php endif; ?>