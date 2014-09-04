<!doctype html>
<html <?php language_attributes(); ?> style="margin-top: 0 !important;">
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="description" content="<?php if ( is_single() ) {
        single_post_title('', true); 
    } else {
        bloginfo('name'); echo " - "; bloginfo('description');
    }
    ?>" />

<title><?php wp_title(' ', true, 'right'); ?></title>




<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!--<link rel="stylesheet/less" href="<?php bloginfo('template_directory'); ?>/less/bootswatch.less">-->
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/less/bootswatch.css">
<link rel="image_src" href="http://radikalportal.no/wp-content/uploads/userphoto/51.jpg" />


<style type="text/css">
  .post img,
  .front-page-img img { width: 100%; }

  .post p {
    color: #000;
  }

  .single p {
    margin: 0 0 22px;
    line-height: 22px;
    font-size: 15px;
  }
  .sidebar {
    font-size: 12px;
  }
  .nav-tabs > .active > a, .nav-tabs > .active > a:hover {
    background-color: #fcfcfc;
    color: #000000;
  }

@media print {
  .navbar, .disqus, .print-hidden {
    display: none;
  }

  a {
    color: #000;
  }
}

</style>

<!--<script>var less = {}; less.env = 'development';</script>-->
<!--<script src="https://raw.github.com/cloudhead/less.js/master/dist/less-1.3.1.js"></script>-->
<script src="<?php bloginfo('template_directory'); ?>/js/less-1.3.1.js"></script>

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<?php wp_head(); ?>

<link rel="alternate" type="application/rss+xml" title="Artikler (RSS)" href="<?php bloginfo('rss2_url'); ?>" />

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37487054-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>


<div class="navbar navbar-static-top">
  <div class="navbar-inner visible-desktop">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <div class="row">
        <div class="span2">

<a class="brand" href="/">HJEM</a> 
        </div>

        <div class="span20">
<?php wp_nav_menu( array('menu' => 'Toppmeny',
                   'container_class' => 'nav-collapse collapse',
                   'menu_class' => 'nav' ));
?>
        </div>
        
	<div class="span2">
         

	<form action="/" class="navbar-search">
            <input name="s" type="text" class="search-query" placeholder="Søk" style="width: 100%;">
        	</form>    


	</div>
      </div>
    </div>
</div>
  <div class="navbar-inner hidden-desktop">
    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </a>
 <!--/.   <a class="brand" href="/"><?php bloginfo('name'); ?></a>-->
<?php wp_nav_menu( array('menu' => 'Toppmeny',
                   'container_class' => 'nav-collapse collapse',
                   'menu_class' => 'nav' ));
?>

<!--/. <div class="span17">
<?php wp_nav_menu( array('menu' => 'Headermeny'));?>
        </div>-->
  </div>

</div>
<div class="container">

<div class="visible-desktop" style="padding-top: 0px;"></div>
<div class="row">
	<div class="span24" style="padding: 20px 0 15px 0;">
	<a href="<?php echo home_url(); ?>/rom/"><img src="http://radikalportal.no/wp-content/uploads/2013/07/radikal_portal-kopi.png" width="945px"></a>

	</div>
</div>