<?php
function v2_stott_oss() {
?>
<div class="stott_oss">
	<table><tr><td><span>Liker du det du leser?</span><br/><br/>VIPPS noen kroner til <b>46924315</b> <br/>eller betal direkte til konto <b>1254.05.88617</b></div>
	</td>
	<td>
		<span>Støtt oss med fast bidrag hver måned</span>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top"><input name="cmd" type="hidden" value="_s-xclick" />
			<div style="width:50%;display:inline;border:0px;">
				<input name="hosted_button_id" type="hidden" value="KN6ANUZSKXFNW" />
				<input name="on0" type="hidden" value="" />
				<select name="os0">
				<option value="Alternativ 1">Alternativ 1 : 100,00 NOK / måned</option>
				<option value="Alternativ 2">Alternativ 2 : 50,00 NOK / måned</option>
				<option value="Alternativ 3">Alternativ 3 : 200,00 NOK / måned</option>
				<option value="Alternativ 4">Alternativ 4 : 300,00 NOK / måned</option>
				<option value="Alternativ 5">Alternativ 5 : 500,00 NOK / måned</option>
				</select>
				<input name="currency_code" type="hidden" value="NOK" />
			</div>
			<div style="width:50%;display:inline;border:0px;">
				<input alt="PayPal – den trygge og enkle metoden for å betale på nettet." name="submit" src="https://www.paypalobjects.com/no_NO/NO/i/btn/btn_subscribeCC_LG.gif" type="image" style="border:0px;"/>
			</div>
		</form>
	</td></tr></table>
</div>
<?php
}
add_action('mh_post_content_bottom', 'v2_stott_oss');
?>