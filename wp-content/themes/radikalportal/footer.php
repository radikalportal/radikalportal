<?php
	/* Wed Feb 26 12:18:40 CET 2014, Erling Westenvik
	 * Added ouput buffers to replace " " with "%20" in malformed urls.
	 */
?>
<div>...</div>
</div> <!-- /container -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js"></script>

<script src="<?php ob_start(); bloginfo('template_directory'); $c = ob_get_contents(); ob_end_clean(); echo str_replace(" ", "%20", $c); ?>/bootstrap/js/bootstrap-transition.js" type="text/javascript"></script>
<script src="<?php ob_start(); bloginfo('template_directory'); $c = ob_get_contents(); ob_end_clean(); echo str_replace(" ", "%20", $c); ?>/bootstrap/js/bootstrap-alert.js" type="text/javascript"></script>
<script src="<?php ob_start(); bloginfo('template_directory'); $c = ob_get_contents(); ob_end_clean(); echo str_replace(" ", "%20", $c); ?>/bootstrap/js/bootstrap-modal.js" type="text/javascript"></script>
<script src="<?php ob_start(); bloginfo('template_directory'); $c = ob_get_contents(); ob_end_clean(); echo str_replace(" ", "%20", $c); ?>/bootstrap/js/bootstrap-dropdown.js" type="text/javascript"></script>
<script src="<?php ob_start(); bloginfo('template_directory'); $c = ob_get_contents(); ob_end_clean(); echo str_replace(" ", "%20", $c); ?>/bootstrap/js/bootstrap-scrollspy.js" type="text/javascript"></script>
<script src="<?php ob_start(); bloginfo('template_directory'); $c = ob_get_contents(); ob_end_clean(); echo str_replace(" ", "%20", $c); ?>/bootstrap/js/bootstrap-tab.js" type="text/javascript"></script>
<script src="<?php ob_start(); bloginfo('template_directory'); $c = ob_get_contents(); ob_end_clean(); echo str_replace(" ", "%20", $c); ?>/bootstrap/js/bootstrap-tooltip.js" type="text/javascript"></script>
<script src="<?php ob_start(); bloginfo('template_directory'); $c = ob_get_contents(); ob_end_clean(); echo str_replace(" ", "%20", $c); ?>/bootstrap/js/bootstrap-popover.js" type="text/javascript"></script>
<script src="<?php ob_start(); bloginfo('template_directory'); $c = ob_get_contents(); ob_end_clean(); echo str_replace(" ", "%20", $c); ?>/bootstrap/js/bootstrap-button.js" type="text/javascript"></script>
<script src="<?php ob_start(); bloginfo('template_directory'); $c = ob_get_contents(); ob_end_clean(); echo str_replace(" ", "%20", $c); ?>/bootstrap/js/bootstrap-collapse.js" type="text/javascript"></script>
<script src="<?php ob_start(); bloginfo('template_directory'); $c = ob_get_contents(); ob_end_clean(); echo str_replace(" ", "%20", $c); ?>/bootstrap/js/bootstrap-carousel.js" type="text/javascript"></script>
<script src="<?php ob_start(); bloginfo('template_directory'); $c = ob_get_contents(); ob_end_clean(); echo str_replace(" ", "%20", $c); ?>/bootstrap/js/bootstrap-typeahead.js" type="text/javascript"></script>

<script type="text/javascript">
jQuery(document).ready(function($) {
    $('.carousel').carousel({
  		interval: 10000	
  	})
});
</script>

</body>
</html>
