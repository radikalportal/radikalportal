<!DOCTYPE html>
<html <?php language_attributes(); ?> style="margin-top: 0 !important;">
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width">
    <title><?php wp_title(' ', true, 'right'); ?></title>
    <!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js" type="text/javascript"></script>
    <![endif]-->
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <meta name="description" content="<?php if ( is_single() ) {
        single_post_title('', true); 
    } else {
        bloginfo('description');
    }
    ?>" />
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/journal/bootstrap.min.css">
    <link rel="image_src" href="http://radikalportal.no/wp-content/uploads/userphoto/51.jpg" />
    <style type="text/css">
 
.front-page-img img {width: 100%; }
.post p { color: #000; }
.single p { }
.sidebar { font-size: 12px; }
  .nav-tabs > .active > a, .nav-tabs > .active > a:hover {
    background-color: #fcfcfc;
    color: #000000;
  }

@media print {
  .navbar, .disqus, .print-hidden {
    display: none;
  }
  a { color: #000; }
}

.infobox {
  border: 1px solid #ccc;
  background-color: #f8f8f8;
  color: #000;
  padding: 0 0px 10px 0px;
  font-size: 84%;
  font-family: verdana, sans-serif;
  line-height: 150%;
  clear:right;
  float: right;
  width: 300px;
  margin: 0 0 9px 20px;
}
.wideinfo{
  border: 1px solid #ccc;
  background-color: #f8f8f8;
  color: #000;
  padding: 0 0px 10px 0px;
  font-size: 84%;
  font-family: verdana, sans-serif;
  line-height: 150%;
  clear:right;
  float: left;
  width: 100%;
  margin: 0 0 9px 20px;
}
.infobox hr { display: none; }
.infobox p { margin: 10px 0 0 0;}
.infobox img { margin: 10px 0 0 10px; padding: 10px; 0 0 20px; width:80px; height:80px; }
.infobox h3,
.infobox h4 {
  display: block;
  padding: 3px 10px 5px 10px;
  margin: 0px -10px 10px -10px;
  font-size: 100%;
  font-weight: bold;
  line-height: 100%;
  background-color: #ddd;
  color: #000;
}
.infobox h4 {
  margin: 10px -10px 10px -10px;
  background-color: #eee;
  color: #000;
}
.infobox br { display: none }

.navbar .nav > li > a {
	font-size:16px;
}

.sidebar li
{
	font-size: 8pt;
	line-height: 13pt;
}

    </style>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
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

    <meta property="fb:app_id" content="446811972038512">

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
  </head>
  <body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/nb_NO/sdk.js#xfbml=1&appId=446811972038512&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

    <!-- Static navbar -->
    <div class="navbar navbar-default navbar-static-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">Radikal Portal</a>
        </div>
        <div class="navbar-collapse collapse">

<?php
  wp_nav_menu(array('menu' => 'Toppmeny',
                    'container_class' => '',
                    'menu_class' => 'nav navbar-nav'));
?>

          <form action="/" class="navbar-form navbar-right hidden-sm hidden-md" role="search">
            <div class="form-group">
              <input name="s" type="text" class="form-control" placeholder="Søk">
            </div>
            <button type="submit" class="btn btn-default hidden-xs">
              <span class="glyphicon glyphicon-search"></span>
            </button>
          </form>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">
