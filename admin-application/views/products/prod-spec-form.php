<?php
    defined('SYSTEM_INIT') or die('Invalid Usage.');
    $prodSpecFrm->setFormTagAttribute('class', 'form web_form');
    $prodSpecFrm->setFormTagAttribute('onsubmit', 'return submitSpecificationForm(this); return(false);');
    $prodSpecFrm->developerTags['fld_default_col'] = 12;

    $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');

if (!empty($translatorSubscriptionKey)) { ?> 
    <div class="row justify-content-end"> 
        <div class="col-auto mb-4">
            <input class="btn btn-primary" 
                type="button" 
                value="<?php echo Labels::getLabel('LBL_AUTOFILL_LANGUAGE_DATA', $adminLangId); ?>" 
                onClick="autofillLangData($(this), $('form#frm_fat_id_frmProductSpec'))"
                data-action="<?php echo CommonHelper::generateUrl('Products', 'getTranslatedSpecData'); ?>">
        </div>
    </div>
<?php }

echo $prodSpecFrm->getFormTag();

foreach ($languages as $langId => $langName) { ?>
    <div class="row align-items-center mb-4">
        <div class="col-md-2">
            <div class="h5 mb-0">
                <?php  echo $langName; ?>
            </div>
        </div>
        <div class="col-md-5">
            <label class="field_label">
            <?php  $fld = $prodSpecFrm->getField('prod_spec_name[' . $langId . ']');
                echo $fld->getCaption(); ?>
                <span class="mandatory">*</span>
            </label>
            <?php if (isset($data['prod_spec_name[' . $langId . ']'])) {
                $fld->value = $data['prod_spec_name[' . $langId . ']'];
            } ?>
            <?php echo $prodSpecFrm->getFieldHtml('prod_spec_name[' . $langId . ']'); ?>
        </div>
        <div class="col-md-5">
            <label class="field_label">
                <?php $fld = $prodSpecFrm->getField('prod_spec_value[' . $langId . ']');
                echo $fld->getCaption(); ?>
                <span class="mandatory">*</span>
            </label>
            <?php   if (isset($data['prod_spec_value[' . $langId . ']'])) {
                $fld->value = $data['prod_spec_value[' . $langId . ']'];
            }
            echo $prodSpecFrm->getFieldHtml('prod_spec_value[' . $langId . ']'); ?>
        </div>
    </div>
<?php } ?>
 
    <div class="row">
        <div class="col-md-12">
            <div class="field-set">
                <div class="caption-wraper">
                    <label class="field_label">
                        <?php  $fld = $prodSpecFrm->getField('btn_submit');
                        echo $fld->getCaption();?>
                    </label>
                </div>
                <div class="field-wraper">
                    <div class="field_cover">
                        <?php echo $prodSpecFrm->getFieldHtml('product_id');?>
                        <?php echo $prodSpecFrm->getFieldHtml('prodspec_id');?>
                        <?php echo $prodSpecFrm->getFieldHtml('btn_submit');?>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
<?php echo $prodSpecFrm->getExternalJs();?>
</form>