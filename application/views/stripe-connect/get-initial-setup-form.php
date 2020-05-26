<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$this->includeTemplate('_partial/seller/sellerDashboardNavigation.php');

$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'initialSetup(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 6;

$btnFld = $frm->getField('btn_submit');
$btnFld->addFieldTagAttribute('class', 'btn btn--primary');

$btnFld = $frm->getField('btn_clear');
if (null != $btnFld) {
    $btnFld->addFieldTagAttribute('class', 'btn btn-outline-primary');
    $btnFld->addFieldTagAttribute('onClick', 'clearForm();');
} ?>
<main id="main-area" class="main" role="main">
	<div class="content-wrapper content-space">
        <div class="content-header row">
			<div class="col"> 
				<?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title">
                	<?php echo $pageTitle; ?>	
                </h2>
            </div>
		</div>
		<div class="content-body">
			<div class="panel panel--centered clearfix">
				<div class="clearfix">
					<div class="section__body">
						<?php $this->includeTemplate('stripe-connect/fieldsErrors.php', ['errors' => $errors]); ?>
						<div class="box box--white">
							<?php echo $frm->getFormHtml(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<script language="javascript">
$(document).ready(function(){
	getStatesByCountryCode($("#country").val(),'<?php echo $stateCode ;?>','#state', 'state_code');
});	
</script>