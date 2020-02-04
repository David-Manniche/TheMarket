<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$productFrm->setFormTagAttribute('class', 'form form--horizontal');
$productFrm->setFormTagAttribute('onsubmit', 'setUpCatalogProductAttributes(this); return(false);');
?>
<div class="row justify-content-center">
    <div class="col-md-12">
<?php echo $productFrm->getFormTag(); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php
                            $fld = $productFrm->getField('product_model');
                            echo $fld->getCaption();
                            ?>
                        </label>
                        <?php if (FatApp::getConfig("CONF_PRODUCT_MODEL_MANDATORY", FatUtility::VAR_INT, 1)) { ?>
                            <span class="spn_must_field">*</span>
<?php } ?>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
<?php echo $productFrm->getFieldHtml('product_model'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label">
                            <?php
                            $fld = $productFrm->getField('product_warranty');
                            echo $fld->getCaption();
                            ?>
                        </label>
                        <span class="spn_must_field">*</span>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
<?php echo $productFrm->getFieldHtml('product_warranty'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="field-set">
                    <div class="caption-wraper"></div>
                    <div class="field-wraper">
                        <div class="field_cover">
<?php echo $productFrm->getFieldHtml('product_featured'); ?>
                        </div>
                    </div>
                </div>
            </div>
<?php if ($productType == Product::PRODUCT_TYPE_PHYSICAL) { ?>
                <div class="col-md-4">
                    <div class="field-set">
                        <div class="caption-wraper"></div>
                        <div class="field-wraper">
                            <div class="field_cover">
    <?php echo $productFrm->getFieldHtml('ps_free'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="field-set">
                        <div class="caption-wraper"></div>
                        <div class="field-wraper">
                            <div class="field_cover">
    <?php echo $productFrm->getFieldHtml('product_cod_enabled'); ?>
                            </div>
                        </div>
                    </div>
                </div>
<?php } ?>
        </div>
        <div class="specifications-form-<?php echo $siteDefaultLangId; ?>"></div>
        <div class="specifications-list-<?php echo $siteDefaultLangId; ?>"></div>

        <?php
        if (!empty($otherLanguages)) {
            foreach ($otherLanguages as $langId => $data) {
                ?>
                <div class="accordion" id="specification-accordion">
                    <ul class="list-group list-group-sm list-group-flush-y list-group-flush-x">
                        <li class="list-group-item">
                            <h6 data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <span onClick="displayOtherLangProdSpec(this,<?php echo $langId; ?>)">
        <?php echo $data . " ";
        echo Labels::getLabel('LBL_Language_Specification', $siteLangId); ?>
                                </span>
                            </h6>
                            <div id="collapseOne" class="collapse collapse-js-<?php echo $langId; ?>" aria-labelledby="headingOne" data-parent="#specification-accordion">
                                <div class="specifications-form-<?php echo $langId; ?>"></div>
                                <div class="specifications-list-<?php echo $langId; ?>"></div>
                            </div>
                        </li>
                    </ul>

                </div>
    <?php
    }
}
?>

        <div class="row">
            <div class="col-md-6">
                <div class="field-set">
                    <div class="caption-wraper"><label class="field_label"></label></div>
                    <div class="field-wraper">
                        <div class="field_cover">
<?php
echo $productFrm->getFieldHtml('preq_id');
echo $productFrm->getFieldHtml('btn_submit');
?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
<?php echo $productFrm->getExternalJS(); ?>
    </div>
</div>

<script type="text/javascript">
   prodSpecificationSection(<?php echo $siteDefaultLangId; ?>)
   prodSpecificationsByLangId(<?php echo $siteDefaultLangId; ?>)   
</script>