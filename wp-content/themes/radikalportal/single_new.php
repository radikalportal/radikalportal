<?php
  $post = $wp_query->post;
  
  
  <!--/.IF IN ROM PROJECT CATEGORY-->
  if (in_category('487')) {
      include(TEMPLATEPATH.'/single-romvideo.php');
  }
   
    <!--/.IF IN ROM PROJECT CATEGORY-->
  elseif (in_category('488')) {
  	  include(TEMPLATEPAH.'/singel-romtekst.php');
  } 
  
    <!--/.IF IN ROM PROJECT CATEGORY-->
  elseif (in_category('489')) {
  	  include(TEMPLATEPAH.'/singel-romtekst.php');
  } 
  
  <!--/.IF WHATEVER CATEGORY-->
  else {
      include(TEMPLATEPATH.'/single-default.php');
  }
?>