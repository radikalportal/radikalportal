<style>

.sharebar {
	background-color: #f8f8f8;
	border-style: solid;
	border-color: #eee;
	border-width: 2px;
	border-radius: 2px;
	padding: 4px 0 0 4px;
}

</style>

<div class="sharebar">
  <div class="hidden-phone hidden-tablet" style="float:left;padding:0px 0px;">
    <a class="btn btn-small" href="javascript:print();"><i class="icon-print"></i>Skriv ut</a>
  </div>
  <div style="float:left;padding:3px 0px 0px 14px;">
    <div class="fb-like" data-href="<?= get_permalink(); ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
  </div>
  <div style="float:left;padding:3px 0px 0px 14px;">
    <?php dd_twitter_generate('Compact','radikalportal') ?>
  </div>
  <div class="hidden-phone" style="float:left;padding:1px 0px;margin-left:-14px;">
    <?php dd_google1_generate('Compact (24px)') ?>
  </div>
  <div class="clearfix"></div>
</div>
