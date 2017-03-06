<?php
#===================================================#
# Hooks associated with ACF Pro 				 	#
#===================================================#

// register Google API key in order to allow the Google API to load correctly
add_action( 'acf/init', function() {
	acf_update_setting('google_api_key', GOOGLE_APIKEY);
});

// add a new save point (folder) for ACF to save to
add_filter('acf/settings/save_json', function( $path ) {
	return get_stylesheet_directory() . '/od/acf-json';
});

// add a new load point (folder) for ACF to look in
add_filter('acf/settings/load_json', function( $paths ) {
	unset($paths[0]); // remove original path (optional)
	$paths[] = get_stylesheet_directory() . '/od/acf-json';
	return $paths;
});

/**
 * Returns an (extra) button for publishing/ updating a post
 * @param      string   $text   The text (default text: Publish)
 * @return     string   Publish/ Update button
 */
function od_get_publish_button( $text = 'Publish' ) {
	return Template::Get('admin-snippet-button', array('text' => $text));
}
function od_publish_button( $text = 'Publish' ) {
	echo od_get_publish_button( $text );
}

add_filter( 'acf/load_field/name=publish_button', function( $field ) {
	$sBtnText = ( get_post_status() != 'publish' ) ? __('Publiceren', 'od') : __('Bijwerken', 'od');
	$field['message'] = od_get_publish_button($sBtnText);
	return $field;
});




// update field with in marker/theme with value of thumbnail_id
add_filter( 'acf/update_value/name=connected_image', function( $value, $post_id, $field  ) {
	// check if post has featured image
	$value = ( has_post_thumbnail($post_id) ) ? get_post_thumbnail_id( $post_id ) : '';
	return $value;
}, 10, 3);

##### Theme Connected Markers Info : field_56c1dadc9a6cf #####
add_filter( 'acf/load_field/key=field_56c1dadc9a6cf', function( $field ) {
	$field['message'] = Template::Get('admin-snippet-connected');
	return $field;
});

##### Marker StreetView (button) : field_56c47f9b8212a #####
add_filter( 'acf/load_field/key=field_56c47f9b8212a', function( $field ) {
	$sBtnText = ( get_post_status() != 'publish' ) ? __('Publiceren', 'od') : __('Bijwerken', 'od');
	$field['message'] = Template::Get('admin-snippet-streetview', array('text' => $sBtnText) );
	return $field;
});

##### Marker StreetView (output image) : field_56c5e42004079 #####
add_filter( 'acf/load_field/key=field_56c5e42004079', function( $field ) {
	$sImage = get_field('image_sv');
	if( $sImage != '' ) {
		$field['message'] = <<<EOHTML
			{$sImage}
			<p class="od-message">* mocht de Google Street View foto niet bevallen, kan je door nogmaals op de knop te drukken de foto aanpassen. Of je kunt op de onderstaande button klikken om de Google Street View in zijn geheel te verwijderen.</p>
			<button class="button" onclick="clear_streetview()">Verwijder Google Street View</button>

EOHTML;
	}
	return $field;
});

##### Thumbnail preview: field_56bb305f7a724 #####
add_filter( 'acf/load_field/key=field_56bb305f7a724', function( $field ) {
	$sPosttype    = get_post_type();
	if( !in_array($sPosttype, array('marker', 'theme')) ) return $field;
	$field['message'] = Template::Get('admin-snippet-preview', array('posttype' => $sPosttype));
	return $field;
});

##### Tooltip preview : field_56dd99dd8bc9d #####
add_filter( 'acf/load_field/key=field_56dd99dd8bc9d', function( $field ) {
	$field['message'] = Template::Get( 'admin-snippet-tooltip', array('text' => $field['message']) );
	return $field;
});




#=#=================================================================#=#
# # Based on Simple Google Street View plugin 						# #
# # https://nl.wordpress.org/plugins/simple-google-street-view/ 	# #
# # API key Street View = AIzaSyBwMp0ciBWYb6cAGzC9tDRgZs_mojaPQe4 	# #
# # @saskia 													 	# #
#=#=================================================================#=#

// Run the filter when a blog is shown
add_filter( 'the_content', 'filter_streetview' );
function filter_streetview( $content ) {
	preg_match_all( "/\[streetview([^\]]*)\](.*?)\[\/streetview\]/", $content, $matches );
	foreach( $matches[0] as $k => $match ) {
		$attributes = $matches[1][$k];
		$string     = $matches[2][$k];
		$content    = str_replace( $match, streetview_div($string, $attributes, $k), $content );
	}
	return $content;
}

// sv_acf_load_value
add_filter('acf/load_value/key=field_56c59eae39d16', function( $value, $post_id, $field ) {
    // run the_content filter on all textarea values
    return filter_streetview( $value );
}, 10, 3);

function img_acf_load_value( $value, $post_id, $field ) {
	// echo 1234;
    // p(get_fields($post_id));
 //    p($field);  image_sv
/*
	<img class="streetview-image" src="https://maps.googleapis.com/maps/api/streetview?size=500x300&amp;location=52.0301257,4.167722200000071&amp;heading=35.92219020172914&amp;pitch=-15.821325648414977&amp;key=AIzaSyBwMp0ciBWYb6cAGzC9tDRgZs_mojaPQe4">
			<p class="od-message">* mocht de Google Street View foto niet bevallen, kan je door nogmaals op de knop te drukken de foto aanpassen. Of je kunt op de onderstaande button klikken om de Google Street View in zijn geheel te verwijderen.</p>

			<button class="button" onclick="clear_streetview()">Verwijder Google Street View</button>

			<script>
			function clear_streetview() {
				$('#acf-field_56c5e3fc04078').text('');
				$('#acf-field_56c59eae39d16').text('');
			}
		</script>


* mocht de Google Street View foto niet bevallen, kan je door nogmaals op de knop te drukken de foto aanpassen. Of je kunt op de onderstaande button klikken om de Google Street View in zijn geheel te verwijderen.


			Verwijder Google Street View
			*/
    return $value;
}
add_filter('acf/load_value/key=field_56c5e42004079', 'img_acf_load_value', 10, 3);

function streetview_div( $string, $attr_string, $k = 0 ) {

	if ($attributes = streetview_attr2arr($attr_string)) {
		$javascript = "<script type='text/javascript'>
		  	var myLatlng = new google.maps.LatLng(".$attributes['lat'].",".$attributes['lng'].");
			var panoramaOptions = {
				position: myLatlng,
				addressControl: false,
				pov: {
					heading: ".$attributes['heading'].",
					pitch: ".$attributes['pitch'].",
					zoom: ".$attributes['zoom']."
				}
			};
			var panorama_$k = new google.maps.StreetViewPanorama(document.getElementById('streetview_canvas_$k'), panoramaOptions);
		</script>";
		unset($attributes['lat']);
		unset($attributes['lng']);
		unset($attributes['heading']);
		unset($attributes['pitch']);
		unset($attributes['zoom']);
		$style = streetview_style($attributes);

	}
	$div = "<div id='streetview_canvas_$k' style='$style'></div>";

	return $div.$javascript;
}

function streetview_attr2arr($attr) {
	// match the attributes
	if (preg_match_all('/(\S*)="([^"]*)"/', $attr, $matches)) {
		$attributes = $matches[1];
		$values		= $matches[2];
		// return the attributes in a attr=>value array
		return array_combine($attributes, $values);
	} else { return false; }
}

function streetview_style($arr) {
	$style = '';
	foreach ($arr as $key => $value) { $style.= $key.': '.$value.'; '; }
	return $style;
}

// Adding action for the iframe
add_action( 'media_upload_streetview', function() {
	wp_iframe('streetview_inner_custom_box');
});


// Prints the inner fields for the custom post/page section
function streetview_inner_custom_box() {

	$postID 	= getValue('post_id');
	// get already set location data to center the map
	$aLocation 	= get_field('marker_location', $postID);
	if( !isset($aLocation) ) { return; }
	$sLatLng 	= $aLocation['lat'].', '.$aLocation['lng'];

	// adding the js directly in the thickbox because thickbox headers conflict with main WP header
	// yes, not the prettiest solution, but it works...
	?>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_APIKEY; ?>"
  type="text/javascript"></script>
	<div style="padding: 0 30px;">
		<p class="howto"><strong>Hoe werkt het?</strong><br>Sleep het gele mannetje/icoontje naar een blauwe lijn op de kaart. Wanneer de Street View modus verschijnt, kun je schuiven en zoomen totdat je het beeld hebt wat je wilt. Om dit beeld te bewaren klik je onderaan op &quot;Voeg deze view toe&quot;.</p>
		<input id="streetview_address" type="hidden" name="address" value="<?php echo $sLatLng; ?>" />
		<div id="streetview_canvas" style="width: 96%; height: 400px; margin: 20px auto 0;"></div>
		<br />
		<input class="button button-primary button-large" style="font-weight: bold;" value="Voeg deze view toe" type="button" onclick="streetview_getthepov();">
	</div>
	<script type="text/javascript">
		var map;
		var geocoder;

		function streetview_initialize() {
			var center = new google.maps.LatLng(52.0, 4.2);
			var mapOptions = {
				center: center,
				zoom: 12,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				streetViewControl: true
			};
			geocoder = new google.maps.Geocoder();
			map = new google.maps.Map(document.getElementById("streetview_canvas"), mapOptions);

			// let's zoom in on the given coordinates already
			streetview_findaddress();
		}

		function streetview_getthepov() {
			var pano = map.getStreetView();
			var pov = pano.getPov();

			if (pos = pano.getPosition()) {

				// embedcode wordt na opslaan van de post nog gefilterd (filter_streetview)
				var embedcode = "[streetview width=\"100%\" height=\"100%\" lat=\""+pos.lat()+"\" lng=\""+pos.lng()+"\" heading=\""+pov.heading+"\" pitch=\""+pov.pitch+"\" zoom=\""+pov.zoom+"\"][/streetview]";

				// return img (of voor Gijs: return streetarray?) ## streetview image uses a different API ##
				var image = "<img class=\"streetview-image\" src=\"https://maps.googleapis.com/maps/api/streetview?size=500x300&amp;location="+pos.lat()+","+pos.lng()+"&amp;heading="+pov.heading+"&amp;pitch="+pov.pitch+"&amp;key=<?php echo GOOGLE_APIKEY; ?>\" data-zoom="+pov.zoom+">"; //add some zoom for Gijs

				var streetarray = {
					size: "500x300",
					location: pos.lat()+","+pos.lng(),
					heading: pov.heading,
					pitch: pov.pitch,
					key: "<?php echo GOOGLE_APIKEY; ?>"
				};

				// return image & embedcode to ACF Enhanced Message script
				top.show_image(image);
				top.show_succes();
				top.show_streetview(embedcode);

			} else {
				alert('Dit is nog geen Street View.\nSleep eerst het gele mannetje de kaart in.');
			}
		}

		// based on google's geocode example code
		function streetview_findaddress() {
			var address = document.getElementById("streetview_address").value;
			geocoder.geocode( { 'address': address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					map.setCenter(results[0].geometry.location);
					map.setZoom(18);
				} else {
					alert("Druk eerst op \"Bijwerken\" voordat je een Street View toevoegd.\n\nGeocode was not successful for the following reason: " + status);
				}
			});
		}

		streetview_initialize();

	</script>
	<?php
}
