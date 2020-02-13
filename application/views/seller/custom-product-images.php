<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$imagesFrm->setFormTagAttribute('id', 'frmCustomProductImage');
$imagesFrm->setFormTagAttribute('class', 'form form-horizontal');
$imagesFrm->developerTags['colClassPrefix'] = 'col-md-';
$imagesFrm->developerTags['fld_default_col'] = 6;
    
$optionFld = $imagesFrm->getField('option_id');	
$optionFld->addFieldTagAttribute('class','option-js');

$langFld = $imagesFrm->getField('lang_id');	
$langFld->addFieldTagAttribute('class','language-js');

$img_fld = $imagesFrm->getField('prod_image');
$img_fld->setFieldTagAttribute( 'onchange','setupCustomProductImages(); return false;');
?>

<div class="row justify-content-center">
     <div class="col-md-12">     
        <?php echo $imagesFrm->getFormHtml(); ?>
        <div id="imageupload_div"></div>
        <div class="row web_form">
            <div class="col-md-6">
                <div class="field-set">
                    <div class="caption-wraper"><label class="field_label"></label></div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <input onclick="<?php if($productType == Product::PRODUCT_TYPE_PHYSICAL) { ?>productShipping(<?php echo $product_id; ?>); <?php }else{ ?> productOptionsAndTag(<?php echo $product_id; ?>); <?php }?>" class="btn btn-outline-primary" type="button" name="btn_back" value="<?php echo Labels::getLabel('LBL_Back', $siteLangId); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <div class="field-set">
                    <div class="caption-wraper"><label class="field_label"></label></div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <input onclick="goToCatalog();" type="button" class="btn btn--primary" name="btn_Finish" value="<?php echo Labels::getLabel('LBL_Finish', $siteLangId); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
  </div>
</div>