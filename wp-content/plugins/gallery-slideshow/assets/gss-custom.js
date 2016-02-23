/*
Use Cycle2 bootstrap methods/syntax to change default options:
http://jquery.malsup.com/cycle2/api/advanced.php
See Cycle2 docs for list of supported options:
http://jquery.malsup.com/cycle2/api/#options
*/

jQuery( document ).on( 'cycle-bootstrap', function( e, opts, API ) {
	opts.timeout = 4000;
	// opts.speed = 500;
});