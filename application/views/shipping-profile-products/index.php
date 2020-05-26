<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'setupProfileProduct(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 6;

$proFld = $frm->getField("product_name");
$proFld->developerTags['col'] = 8;

$submitFld = $frm->getField("btn_submit");
$submitFld->developerTags['col'] = 4;
$proFld->htmlAfterField = "<span class='form-text text-muted text-danger'>".Labels::getLabel("LBL_Product_will_automatically_remove_from_other_profile", $siteLangId)."</span>";
$proFld->addFieldTagAttribute('placeholder', Labels::getLabel('LBL_Search_Product...', $siteLangId));

?>
<div class="portlet__head">
    <div class="portlet__head-label">
        <h5 class="cards-title"><?php echo Labels::getLabel('LBL_Products', $siteLangId); ?>
        </h5>
    </div>
    <div class="portlet__head-toolbar">
        <div class="portlet__head-actions"></div>
    </div>
</div>
<div class="portlet__body">
    <div class="row">
        <div class="col-md-12">
            <?php echo $frm->getFormHtml(); ?>
            </form>
        </div>
    </div>
    <div id="product-listing--js"></div>
</div>