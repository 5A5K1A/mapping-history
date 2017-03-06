

function inArray(target, array) {
	for(var i = 0; i < array.length; i++) {
		if(array[i] === target) { return true; }
	}
	return false;
}

function getCurrentPosttype() {
	var input = document.querySelector('input#post_type');

	// early return if not posttype edit area
	if( !input ) { return null; }

	return input.value;
}