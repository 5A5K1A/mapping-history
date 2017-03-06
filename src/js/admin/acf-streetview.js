/*
 * Set & Remove streetview data in ACF Pro fields
 * Streetview for: Marker
 *
 * @version 0.1 [February 2017]
 * @author Saskia Bouten
 */

(function(){
	// settings
	var posttypeSelect   = ['marker'];

	// other variables
	var currentPosttype  = getCurrentPosttype();

	// early return if not in specified
	if( !inArray(currentPosttype, posttypeSelect) ) { return null; }


	console.dir(currentPosttype);

	var elForm       = document.querySelector('form#post');
	var elACFLoc     = elForm.querySelector('#acf-group_marker_location');
	var elACFStv     = elForm.querySelector('#acf-group_marker_streetview');

	var inputStvCode = elACFStv.querySelector('[data-name="output_sv"] textarea');
	var inputStvText = elACFStv.querySelector('[data-name="image_sv"] textarea');


})();

function clear_streetview() {
	document.querySelector('[data-name="image_sv"] textarea').value = '';
	document.querySelector('[data-name="output_sv"] textarea').value = '';
}

function show_image(image) {
	document.querySelector('[data-name="image_sv"] textarea').value = image;
}

function show_streetview(embedcode) {
	document.querySelector('[data-name="output_sv"] textarea').value = embedcode;
	tb_remove();
}

function show_succes() {
	 document.querySelector('#acf-group_marker_streetview p#succes').innerHTML = 'Er is een Google Street View toegevoegd. Klik nogmaals op de knop "Bijwerken" om de foto te tonen.';
}