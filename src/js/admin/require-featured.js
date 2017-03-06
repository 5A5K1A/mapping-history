/*
 * Require featured image for certain posttypes
 * (now) Required for: Marker, Theme
 *
 * @version 0.1 [February 2017]
 * @author Saskia Bouten (inspired by https://github.com/pressupinc/require-featured-image)
 */

(function() {

	// settings
	// @TODO: make editable from admin
	var imageMinWidth    = 500;
	var imageMinHeight   = 300;
	var posttypeSelect   = ['marker', 'theme'];

	// other variables
	var currentPosttype  = getCurrentPosttype();

	// early return if not in specified
	if( !inArray(currentPosttype, posttypeSelect) ) { return null; }

	// proceed if requirements are met
	if( currentPosttype ) {

		// init
		var elForm       = document.querySelector('form#post');
		var imageDiv     = elForm.querySelector('#postimagediv');
		var aPublishBtn  = elForm.querySelectorAll('input#publish');

		var currentState = 'hasImage';
		var fillText     = 'deze pagina';
		var currentImg;
		var messageDiv;

		// if no image is provided
		if( !imageDiv.querySelector('img') ) { setState('noImage'); }
		// let's expect this image is allright (for now)
		else { setState(currentState); }

		// better keep checking if there (still) is an image
		// @TODO: rewrite to eventlistener
		setInterval(checkForImage, 10000);

		function setState( state ) {
			console.log('setState... '+state);
			//init
			var warningMsg;

			// no changes
			if( currentState == state ) { return; }
			// something is wrong
			else if( state == 'noImage' || state == 'smallImage' ) {
				warningMsg = getWarningText( state );
				addWarningDisablePublish( warningMsg );
			}
			// we've got ourselves a good one
			else {
				warningMsg = null;
				if( currentState != 'hasImage' ) { clearWarningEnablePublish(); }
			}

			// set variable
			currentState = state;
			return;
		}

		function getWarningText( type ) {
			console.log('getWarningText...');
			// set specific texts
			if( currentPosttype == 'marker' ) { fillText = 'deze marker'; }
			else if( currentPosttype == 'theme' ) { fillText = 'dit thema'; }

			// there's no image provided
			if( type == 'noImage' ) {
				console.log('noImage');
				return '<strong>Let op : Er is nog geen foto voor '+fillText+' geselecteerd.</strong>';
			}
			// this one is too small
			else {
				console.log('tooSmall');
				return '<strong>De ingestelde foto is te klein. Gebruik een foto van minimaal '+imageMinWidth+' bij '+imageMinHeight+' pixels.</strong>';
			}
		}

		function addWarningDisablePublish( message ) {
			console.log('addWarningDisablePublish...');
			// create container (if not already there)
			createMessageDiv();

			// fill the warning div with the message
			messageDiv.classList.add('od-featured-error');
			messageDiv.innerHTML = '<p>'+message+'</p>';

			// disable the submit/publish button
			aPublishBtn.forEach( function(elBtn) {
				elBtn.setAttribute('disabled', 'disabled');
			});
		}

		function createMessageDiv() {
			// if none is around, make one
			if( !getMessageDiv() ) {
			console.log('createMessageDiv...');
				messageDiv = document.createElement('div');
				messageDiv.setAttribute('id', 'od-featured-message');

				// insert before/above the form
				elForm.parentNode.insertBefore(messageDiv, elForm);
			}
		}

		function clearWarningEnablePublish() {
			console.log('clearWarningEnablePublish...');

			currentState = 'hasImage';
			if( getMessageDiv() ) {
				// remove the warning div
				messageDiv.outerHTML = '';
				// enable the submit/publish button
				aPublishBtn.forEach( function(elBtn) {
					elBtn.removeAttribute('disabled');
				});
			}
		}

		function getMessageDiv() {
			messageDiv   = document.querySelector('div#wpbody #od-featured-message');
			return messageDiv;
		}

		function checkForImage() {
			console.log('checkForImage...');
			var image    = imageDiv.querySelector('img');

			// early exit on no image
			if( !image ) {
				if( currentState != 'noImage' ) { setState('noImage'); }
				return;
			}
			// early exit on still the same image
			else if( image.src == currentImg ) { return; }

			// set state to having an image
			setState('hasImage');
			currentImg   = image.src.replace(/-\d+[Xx]\d+\./g, ".");
			console.log('currentImg: '+currentImg);

			// check if image is big enough
			checkImageData(currentImg);
		}

		function checkImageData( path ) {
			console.log('checkImageData...');
			// init
			var img      = new Image();

			// load image and it's data
			img.onload   = function(){
				// temporarily clear the message div
				if( currentState != 'hasImage' ) { clearWarningEnablePublish(); }

				// compare image size to settings
				checkSize( this.width, this.height );
			};
			img.src      = path;
			currentImg   = img.src;
		}

		function checkSize( width, height ) {
			console.log('checkSize... '+width+' x '+height);

			// somethings not right, try again?
			// @TODO prevent an infinite loop
			if( width == 0 || height == 0 ) { checkImageData(currentImg); return; }

			// sorry this one is too small, display the warning
			if( width < imageMinWidth || height < imageMinHeight ) {
				addWarningDisablePublish( getWarningText('tooSmall') );
				return;
			}

			// everything is fine
			if( currentState != 'hasImage' ) { clearWarningEnablePublish(); }
			return;
		}
	}
})();