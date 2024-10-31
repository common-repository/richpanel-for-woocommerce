<script type="text/javascript">
const setupRichpanelMessengerConfiguration = function (properties) {
	if (properties) {
		window.richpanelSettings = properties['data'];
	}
};

setupRichpanelMessengerConfiguration(<?php echo wp_json_encode($this->getRichpanelUserData()); ?>);

window.richpanel||(window.richpanel=[]),window.richpanel.q=[],mth=["track","debug","atr"],sk=function(e){return function(){a=Array.prototype.slice.call(arguments);a.unshift(e);window.richpanel.q.push(a)}};for(var i=0;mth.length>i;i++){window.richpanel[mth[i]]=sk(mth[i])}window.richpanel.load=function(e){var t=document,n=t.getElementsByTagName("script")[0],r=t.createElement("script");r.type="text/javascript";r.async=true;r.src="https://cdn.richpanel.com/js/tracking.js";n.parentNode.insertBefore(r,n)};
window.richpanel.ensure_rpuid = "";
<?php if (!empty($this->rp_api_key) && $this->accept_tracking) : ?>
const apiKey = "<?php echo esc_html_e($this->rp_api_key); ?>";
window['appClientId']=apiKey;
richpanel.load(apiKey);
<?php endif ?>
</script>
