
<p class="description">
<?php
	// check for connected markers
	$connected = get_connected_markers( get_the_ID() );
	$oMarker   = get_post_type_object( 'marker' );

	// tell where to connect the markers
	if( empty($connected) ) :
		$sPluralLink  = '<a href="edit.php?post_type=marker" target="_blank">'.strtolower($oMarker->labels->name).'</a>';
		printf(__('Er zijn geen %s gekoppeld aan dit thema.', 'od'), $sPluralLink);
	?>
</p>

<?php
	// list the markers (with an edit-link)
	else :
		$sSingleName  = strtolower($oMarker->labels->singular_name);
		printf(__('Een %s is te koppelen aan (of te ontkoppelen van) een thema via de desbetreffende %s.', 'od'), $sSingleName, $sSingleName);
	?>
</p>
<ul>

<?php
		// list the connected markers (with edit links)
		foreach ( $connected as $post ) {
			echo '<li>* <a href="'.get_edit_post_link($post->ID).'" target="_blank">'.get_the_title($post->ID).'</a></li>';
		}
	?>
</ul>
<?php endif; ?>