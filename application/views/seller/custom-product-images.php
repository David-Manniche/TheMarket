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
  </div>
</div>