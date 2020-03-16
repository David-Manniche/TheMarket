<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
if ($selprod_id > 0 || empty($productOptions)) {
    $frmSellerProduct->setFormTagAttribute('onsubmit', 'setUpSellerProduct(this); return(false);');
} else {
    $frmSellerProduct->setFormTagAttribute('onsubmit', 'setUpMultipleSellerProducts(this); return(false);');
}
$siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
$frmSellerProduct->setFormTagAttribute('class', 'form form--horizontal layout--'.Language::getLayoutDirection($siteDefaultLangId));
$autoUpdateFld = $frmSellerProduct->getField('auto_update_other_langs_data');
$autoUpdateFld->developerTags['cbLabelAttributes'] = array('class' => 'checkbox');
$autoUpdateFld->developerTags['cbHtmlAfterCheckbox'] = '<i class="input-helper"></i>';
$returnAgeFld = $frmSellerProduct->getField('selprod_return_age');
$cancellationAgeFld = $frmSellerProduct->getField('selprod_cancellation_age');
$returnAge = FatUtility::int($returnAgeFld->value);
$hidden = '';
if ('' === $returnAgeFld->value || '' === $cancellationAgeFld->value) {
    $hidden = 'hidden';
}

$urlFld = $frmSellerProduct->getField('selprod_url_keyword');
$urlFld->setFieldTagAttribute('id', "urlrewrite_custom");
$urlFld->setFieldTagAttribute('onkeyup', "getSlugUrl(this,this.value, $selprod_id, 'post')");
$urlFld->htmlAfterField = "<span class='form-text text-muted'>" . CommonHelper::generateFullUrl('Products', 'View', array($selprod_id), '/').'</span>';

$fld = $frmSellerProduct->getField('selprod_subtract_stock');
$fld->developerTags['cbLabelAttributes'] = array('class' => 'checkbox');
$fld->developerTags['cbHtmlAfterCheckbox'] = '<i class="input-helper"></i>';

$fld = $frmSellerProduct->getField('selprod_track_inventory');
$fld->developerTags['cbLabelAttributes'] = array('class' => 'checkbox');
$fld->developerTags['cbHtmlAfterCheckbox'] = '<i class="input-helper"></i>';

$fld = $frmSellerProduct->getField('use_shop_policy');
$fld->developerTags['cbLabelAttributes'] = array('class' => 'checkbox');
$fld->developerTags['cbHtmlAfterCheckbox'] = '<i class="input-helper"></i>';

$submitBtnFld = $frmSellerProduct->getField('btn_submit');
$submitBtnFld->setFieldTagAttribute('class', 'btn btn--primary');
$submitBtnFld->developerTags['col'] = 12;

$cancelBtnFld = $frmSellerProduct->getField('btn_cancel');
$cancelBtnFld->setFieldTagAttribute('class', 'btn btn-outline-primary');

?>
<div class="row">
    <div class="col-md-12">
        <div class="form__subcontent">
            <?php echo $frmSellerProduct->getFormTag(); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_title'.$siteDefaultLangId)->getCaption(); ?><span class="spn_must_field">*</span></label></div>
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_title'.$siteDefaultLangId); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_url_keyword')->getCaption(); ?><span class="spn_must_field">*</span></label></div>
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_url_keyword'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="field-set d-flex align-items-center">
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_subtract_stock'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-set d-flex align-items-center">
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_track_inventory'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="selprod_threshold_stock_level_fld col-md-6">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_threshold_stock_level')->getCaption(); ?> <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo Labels::getLabel('LBL_Alert_stock_level_hint_info', $siteLangId); ?>"></i></label></div>
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_threshold_stock_level'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_min_order_qty')->getCaption(); ?><span class="spn_must_field">*</span></label></div>
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_min_order_qty'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($product_type == Product::PRODUCT_TYPE_DIGITAL) { ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_max_download_times')->getCaption(); ?></label></div>
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_max_download_times'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_download_validity_in_days')->getCaption(); ?></label></div>
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_download_validity_in_days'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $frmSellerProduct->getFieldHtml('selprod_condition'); ?>
            <?php } ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_active')->getCaption(); ?></label></div>
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_active'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_available_from')->getCaption(); ?><span class="spn_must_field">*</span></label></div>
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_available_from'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($product_type == Product::PRODUCT_TYPE_PHYSICAL) { ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_condition')->getCaption(); ?><span class="spn_must_field">*</span></label></div>
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_condition'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"></label></div>
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('use_shop_policy'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row use-shop-policy <?php echo $hidden; ?>">
                <div class="col-md-6">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_return_age')->getCaption(); ?></label></div>
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_return_age'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_cancellation_age')->getCaption(); ?></label></div>
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_cancellation_age'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="selprod_cod_enabled_fld col-md-6">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_cod_enabled')->getCaption(); ?></label></div>
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_cod_enabled'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table id="optionsTable-js" class="table">
                        <thead>
                            <tr>
                                <?php if (($selprod_id == 0 && !empty($availableOptions)) || !empty($optionValues)) { ?>
                                <th width="20%"><?php echo Labels::getLabel('LBL_Variant/Option', $siteLangId); ?></th>
                                <?php }?>
                                <th width="20%"><?php echo Labels::getLabel('LBL_Cost_Price', $siteLangId); ?></th>
                                <th width="20%"><?php echo Labels::getLabel('LBL_Selling_Price', $siteLangId); ?> <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo Labels::getLabel('LBL_This_price_is_excluding_the_tax_rates.', $siteLangId).' '.Labels::getLabel('LBL_Min_Selling_price', $siteLangId).' '. CommonHelper::displayMoneyFormat($productMinSellingPrice, true, true); ?>"></i></th>
                                <th width="20%"><?php echo Labels::getLabel('LBL_Quantity', $siteLangId); ?></th>
                                <th width="20%"><?php echo Labels::getLabel('LBL_SKU', $siteLangId); ?> <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo Labels::getLabel('LBL_Stock_Keeping_Unit', $siteLangId) ?>"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($selprod_id == 0 && !empty($availableOptions)) { ?>
                            <?php foreach ($availableOptions as $optionKey => $optionValue) { ?>
                            <tr>
                                <td><?php echo str_replace("_", " | ", $optionValue); ?></td>
                                <td class="optionFld-js"><?php echo $frmSellerProduct->getFieldHtml('selprod_cost'.$optionKey); ?></td>
                                <td class="optionFld-js"><?php echo $frmSellerProduct->getFieldHtml('selprod_price'.$optionKey); ?></td>
                                <td class="optionFld-js"><?php echo $frmSellerProduct->getFieldHtml('selprod_stock'.$optionKey); ?></td>
                                <td class="optionFld-js fldSku"><?php echo $frmSellerProduct->getFieldHtml('selprod_sku'.$optionKey); ?></td>
                                <td><button type="button" class="btn btn-secondary btn-elevate btn-icon"  data-toggle="tooltip" data-placement="right" title="" data-original-title="Copy to clipboard"><i class="fas fa-paste"></i></button>
                                
                                 
                                   </td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <?php if (!empty($optionValues)) { ?>
                                <td><?php echo implode(' | ', $optionValues); ?></td>
                                <?php } ?>
                                <td><?php echo $frmSellerProduct->getFieldHtml('selprod_cost'); ?></td>
                                <td><?php echo $frmSellerProduct->getFieldHtml('selprod_price'); ?></td>
                                <td><?php echo $frmSellerProduct->getFieldHtml('selprod_stock'); ?></td>
                                <td><?php echo $frmSellerProduct->getFieldHtml('selprod_sku'); ?></td>

                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_comments'.$siteDefaultLangId)->getCaption(); ?></label></div>
                        <div class="field-wraper">
                            <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_comments'.$siteDefaultLangId); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $languages = Language::getAllNames();
            unset($languages[$siteDefaultLangId]);
            $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
            if (!empty($translatorSubscriptionKey) && count($languages) > 0) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="field-set mb-0">
                        <div class="caption-wraper"></div>
                        <div class="field-wraper">
                            <div class="field_cover">
                                <?php echo $frmSellerProduct->getFieldHtml('auto_update_other_langs_data'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if(count($languages) > 0) { ?>
            <div class="row">
                <div class="col-md-12">
                    <?php foreach ($languages as $langId => $langName) {
                        $layout = Language::getLayoutDirection($langId); ?>
                    <div class="accordion mt-4" id="specification-accordion">
                        <h6 class="dropdown-toggle" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><span onclick="translateData(this, '<?php echo $siteDefaultLangId; ?>', '<?php echo $langId; ?>')">
                                <?php echo Labels::getLabel('LBL_Inventory_Data_for', $siteLangId) ?> <?php echo $langName;?>
                            </span>
                        </h6>
                        <div id="collapseOne" class="collapse collapse-js-<?php echo $langId; ?>" aria-labelledby="headingOne" data-parent="#specification-accordion">
                            <div class="p-4 mb-4 bg-gray rounded" dir="<?php echo $layout; ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="field-set">
                                            <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_title'.$langId)->getCaption(); ?></label></div>
                                            <div class="field-wraper">
                                                <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_title'.$langId); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="field-set">
                                            <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_comments'.$langId)->getCaption(); ?></label></div>
                                            <div class="field-wraper">
                                                <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_comments'.$langId); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-6">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"></label></div>
                        <div class="field-wraper">
                            <div class="field_cover">
                                <?php echo $frmSellerProduct->getFieldHtml('btn_cancel'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"></label></div>
                        <div class="field-wraper">
                            <div class="field_cover">
                                <?php echo $frmSellerProduct->getFieldHtml('btn_submit'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $frmSellerProduct->getFieldHtml('selprod_product_id');
            echo $frmSellerProduct->getFieldHtml('selprod_urlrewrite_id');
            echo $frmSellerProduct->getFieldHtml('selprod_id');?>
            </form>
            <?php echo $frmSellerProduct->getExternalJS();?>
        </div>
    </div>
</div>
<?php echo FatUtility::createHiddenFormFromData(array('product_id' => $product_id), array('name' => 'frmSearchSellerProducts'));?>
<script type="text/javascript">
    $('[data-toggle="tooltip"]').tooltip();
    var PERCENTAGE = <?php echo applicationConstants::PERCENTAGE; ?>;
    var FLAT = <?php echo applicationConstants::FLAT; ?>;
    var CONF_PRODUCT_SKU_MANDATORY = <?php echo FatApp::getConfig("CONF_PRODUCT_SKU_MANDATORY", FatUtility::VAR_INT, 1); ?>;
    var LBL_MANDATORY_OPTION_FIELDS = '<?php echo Labels::getLabel('LBL_Atleast_one_option_needs_to_be_added_before_creating_inventory_for_this_product', $siteLangId); ?>';
    var PRODUCT_TYPE_DIGITAL = <?php echo Product::PRODUCT_TYPE_DIGITAL; ?>;
    var productType = <?php echo $product_type; ?>;
    $("document").ready(function() {
        var INVENTORY_TRACK = <?php echo Product::INVENTORY_TRACK; ?>;
        var INVENTORY_NOT_TRACK = <?php echo Product::INVENTORY_NOT_TRACK; ?>;
        var shippedBySeller = <?php echo $shippedBySeller; ?>;
        if (productType == PRODUCT_TYPE_DIGITAL || shippedBySeller == 0) {
            $(".selprod_cod_enabled_fld").hide();
        }

        $("input[name='selprod_track_inventory']").change(function() {
            if ($(this).prop("checked") == false) {
                $("input[name='selprod_threshold_stock_level']").val(0);
                $("input[name='selprod_threshold_stock_level']").attr("disabled", "disabled");
            } else {
                $("input[name='selprod_threshold_stock_level']").removeAttr("disabled");
            }
        });

        $("input[name='selprod_track_inventory']").trigger('change');

        $("input[name='use_shop_policy']").change(function() {
            if ($(this).is(":checked")) {
                $('.use-shop-policy').addClass('hidden');
            } else {
                $('.use-shop-policy').removeClass('hidden');
            }
        });

        $(document).on('keyup', ".optionFld-js input", function() {
            var currentObj = $(this);
            if (currentObj.val().length > 0) {
                currentObj.parent().parent().find('input').each(function() {
                    if ($(this).parent().hasClass('fldSku') && CONF_PRODUCT_SKU_MANDATORY != 1) {
                        return;
                    }
                    if ($(this).val().length == 0 || $(this).val() == 0) {
                        $(this).attr('class', 'error');
                    }
                });
                currentObj.removeClass('error');
            } else {
                var allEmpty = true;
                currentObj.parent().parent().find('input').each(function() {
                    if ($(this).val().length > 0) {
                        allEmpty = false;
                    }
                });
                if (allEmpty) {
                    currentObj.parent().parent().find('input').each(function() {
                        $(this).removeClass('error');
                    });
                } else {
                    currentObj.attr('class', 'error');
                }
            }
        });
    });
</script>