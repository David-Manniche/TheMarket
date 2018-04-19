<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php 
$frm->setFormTagAttribute('class', 'form form--normal');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$frm->setFormTagAttribute('onsubmit', 'confirmOrder(this); return(false);');
?>
<div class="">
	<p><strong><?php echo sprintf(Labels::getLabel('LBL_Pay_using_Payment_Method', $siteLangId),$paymentMethod["pmethod_name"])?>:</strong></p><br/>
	<p><?php echo $paymentMethod["pmethod_description"]?></p><br/>
	<?php 
	if( !isset($error) ){
		echo $frm->getFormHtml(); 
	}
	?>
</div>
<?php if( strtolower($paymentMethod['pmethod_code']) == "cashondelivery" ){ ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<?php } ?>
<script type="text/javascript">
	$("document").ready(function(){
		<?php if( isset($error) ){ ?>
			$.systemMessage(<?php echo $error; ?>);
		<?php } ?>
	});
	function confirmOrder(frm){
		var data = fcom.frmData(frm);
		var action = $(frm).attr('action')
		fcom.updateWithAjax(fcom.makeUrl('Checkout', 'ConfirmOrder'), data, function(ans) {
			$(location).attr("href", action);
		});
	}
</script>