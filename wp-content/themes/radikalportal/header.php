<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width">
    <title><?php wp_title(' ', true, 'right'); ?></title>
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
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/style.css?6">
    <link rel="image_src" href="http://radikalportal.no/wp-content/uploads/userphoto/51.jpg" />

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
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
  <body <?php body_class(); ?>>

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

<div class="menu-toppmeny-container">
<ul id="menu-toppmeny" class="nav navbar-nav">

<?php
  wp_nav_menu(array('menu' => 'Toppmeny',
                    'container' => false,
                    'menu_class' => 'nav navbar-nav',
                    'items_wrap' => '%3$s',
  ));
?>

</ul>
</div>

          <form action="/" class="navbar-form navbar-right hidden-sm" role="search">
            <div class="form-group">
              <input name="s" type="text" class="form-control" placeholder="Søk" value="<?= get_search_query(); ?>">
            </div>
            <button type="submit" class="btn btn-default hidden-xs">
              <span class="glyphicon glyphicon-search"></span>
            </button>
          </form>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">

<!-- Innsamlingskampanje -->
<div class="hidden-print" style="margin-bottom: 22px;">
  <a href="/stott-radikal-portal/">
    <img width="100%" src="/wp-content/themes/radikalportal/img/innsamlingsbanner.jpg">
  </a>
</div>
<!-- /Innsamlingskampanje -->
