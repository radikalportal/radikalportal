</div> <!-- /container -->

<!-- Innsamlingskampanje -->
<style>
.modal-body p {
  color: black;
  font-size: 18px;
  margin-bottom: 1.5em;
}
@media screen and (max-width: 768px) {
  .modal-title {
    font-size: 1.5em;
  }
  .modal-body {
    padding: 0.5em 0;
  }
  .modal-body p {
    font-size: 1em;
    margin-bottom: 0.5em;
  }
}
</style>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h1 class="text-center modal-title" id="myModalLabel">Støtt Radikal Portal - få en t-skjorte</h1>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-5 hidden-sm hidden-xs">
              <img width="100%" src="/wp-content/themes/radikalportal/img/innsamlingsposter.jpg">
            </div>
            <div class="col-md-7">
              <p>For å fortsette og forbedre det viktige arbeidet ber vi deg som leser bidra. Setter du deg på fast trekk 100 kroner i måneden får du en eksklusiv Radikal Portal-t-skjorte.</p>
              <p>Vi ønsker å styrke antirasismen, arbeiderkampen, miljøaktivismen, fredsarbeidet og en reell demokratisering. Vi er en motvekt til kommersielle medier og en selvstending kraft som vil løfte fram stemmer som jobber for en bedre verden. Håper du vil hjelpe.</p>
              <p><a href="/stott-radikal-portal/" class="btn btn-primary btn-lg">Donér til Radikal Portal</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="<?php bloginfo('template_directory'); ?>/js/js.cookie.js"></script>
<script>
  jQuery(function() {
    if (jQuery.browser.msie && parseFloat(jQuery.browser.version) <= parseFloat("8.0")) {
      return;
    }

    var cookieName = "innsamling";
    var msToNext = 1000*60*60*24*2;
    var msBeforeModal = 500;

    if (-1 == document.location.href.indexOf("/stott-radikal-portal/")) {
      if (jQuery.cookie(cookieName) == undefined) {
        jQuery.cookie(cookieName, new Date().toISOString());
        setTimeout(function() {
          jQuery('#myModal').modal();
        }, msBeforeModal);
      } else {
        var msSinceLast = (new Date() - Date.parse(jQuery.cookie(cookieName)));
        if (msSinceLast > msToNext) {
          jQuery.cookie(cookieName, new Date().toISOString());
          setTimeout(function() {
            jQuery('#myModal').modal();
          }, msBeforeModal);
        }
      }
    }
  });
</script>
<!-- /Innsamlingskampanje -->

<script src="<?php bloginfo('template_directory'); ?>/js/bootstrap.min.js"></script>

<img height="0" border="0" width="0" alt="hits" src="http://hits.blogsoft.org/?eid=721" />

<script type="text/javascript">
  jQuery(document).ready(function($) {
    $('.carousel').carousel({
      interval: 10000,
    })
  });
</script>

<?php wp_footer(); ?>

</body>
</html>
