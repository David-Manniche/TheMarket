<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
    $frmSearch->setFormTagAttribute( 'onsubmit', 'searchOptions(this); return(false);' );
    
    $frmSearch->setFormTagAttribute('class', 'form');
    $frmSearch->developerTags['colClassPrefix'] = 'col-md-';
    $frmSearch->developerTags['fld_default_col'] = 12;

    $keyFld = $frmSearch->getField('keyword');
    $keyFld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Keyword', $siteLangId));
    $keyFld->setWrapperAttribute('class','col-sm-6');
    $keyFld->developerTags['col'] = 8;

    $submitBtnFld = $frmSearch->getField('btn_submit');
    $submitBtnFld->setFieldTagAttribute('class','btn--block');
    $submitBtnFld->setWrapperAttribute('class','col-sm-3');
    $submitBtnFld->developerTags['col'] = 2;

    $cancelBtnFld = $frmSearch->getField('btn_clear');
    $cancelBtnFld->setFieldTagAttribute('class','btn--block');
    $cancelBtnFld->setWrapperAttribute('class','col-sm-3');
    $cancelBtnFld->developerTags['col'] = 2;
?>

<?php $this->includeTemplate('_partial/seller/sellerDashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
 <div class="content-wrapper content-space">
	<div class="content-header  row justify-content-between mb-3">
		<div class="content-header-left col-md-auto">
			<?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
			<h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Seller_Options',$siteLangId); ?></h2>
		</div>
	</div>
    <div class="content-body">
		<div class="cards">
            <div class="cards-header p-3">
                <h5 class="cards-title "><?php echo Labels::getLabel('LBL_Manage_Seller_Options',$siteLangId); ?></h5>
                <div class="action"><a href="#modal-popup" class="modaal-inline-content link" onclick="optionForm(0)"><?php echo Labels::getLabel('LBL_Add_Option',$siteLangId);?></a></div>
            </div>
            <div class="cards-content p-3">
             <div class="bg-gray-light p-3 pb-0">
              <?php echo $frmSearch->getFormHtml(); ?>
             </div>
             <span class="gap"></span>
             <?php echo $frmSearch->getExternalJS();?>
             <div id="optionListing"></div>
            </div>
		</div>
	</div>
  </div>
</main>
