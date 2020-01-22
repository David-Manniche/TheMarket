<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$submitBtnFld = $frmSellerProduct->getField('btn_submit');
$submitBtnFld->setFieldTagAttribute('class', 'btn btn--primary');

$cancelBtnFld = $frmSellerProduct->getField('btn_cancel');
$cancelBtnFld->setFieldTagAttribute('class', 'btn btn--primary-border');
$submitBtnFld->developerTags['col'] = 12;
?>
<div class="tabs tabs--small tabs--scroll clearfix">
    <?php require_once('sellerCatalogProductTop.php');?>
</div>
<div class="cards">
    <div class="cards-content pt-3 pl-4 pr-4 ">
        <div class="tabs__content form">
            <div class="row">
                <div class="col-md-12">
                    <div class="">
                        <div class="tabs tabs-sm tabs--scroll clearfix">
                            <ul>
                                <li class="is-active"><a href="javascript:void(0)"
                                    <?php if ($selprod_id > 0) { ?>
                                        onClick="sellerProductForm(<?php echo $product_id, ',', $selprod_id ?>)"
                                    <?php }?>>
                                <?php echo Labels::getLabel('LBL_Basic', $siteLangId); ?></a></li>
                                <li class="<?php echo (0 == $selprod_id) ? 'fat-inactive' : ''; ?>">
                                    <a href="javascript:void(0);" <?php echo (0 < $selprod_id) ? "onclick='sellerProductLangForm(" . FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1) . ", " . $selprod_id . ");'" : ""; ?>>
                                        <?php echo Labels::getLabel('LBL_Language_Data', $siteLangId); ?>
                                    </a>
                                </li>
                                <?php $inactive = ($selprod_id==0)?'fat-inactive':'';?>
                                <li class="<?php echo $inactive ; ?>"><a href="javascript:void(0)"
                                    <?php if ($selprod_id>0) { ?>
                                        onClick="linkPoliciesForm(<?php echo $product_id, ',', $selprod_id, ',', PolicyPoint::PPOINT_TYPE_WARRANTY ; ?>)"
                                    <?php }?>><?php echo Labels::getLabel('LBL_Link_Warranty_Policies', $siteLangId); ?></a></li>
                                <li class="<?php echo $inactive ; ?>"><a href="javascript:void(0)"
                                    <?php if ($selprod_id>0) {?>
                                        onClick="linkPoliciesForm(<?php echo $product_id, ',', $selprod_id, ',', PolicyPoint::PPOINT_TYPE_RETURN ; ?>)"
                                    <?php }?>><?php echo Labels::getLabel('LBL_Link_Return_Policies', $siteLangId); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="form__subcontent">
                        <?php
                        $frmSellerProduct->setFormTagAttribute('onsubmit', 'setUpSellerProduct(this); return(false);');
                        $frmSellerProduct->setFormTagAttribute('class', 'form form--horizontal');
                        /*$frmSellerProduct->developerTags['colClassPrefix'] = 'col-lg-6 col-md-';
                        $frmSellerProduct->developerTags['fld_default_col'] = 6;*/

                        $returnAgeFld = $frmSellerProduct->getField('selprod_return_age');
                        $cancellationAgeFld = $frmSellerProduct->getField('selprod_cancellation_age');
                        $returnAge = FatUtility::int($returnAgeFld->value);
                        $hidden = '';
                        if ('' === $returnAgeFld->value || '' === $cancellationAgeFld->value) {
                            $hidden = 'hidden';
                        }
                        $returnAgeFld->setWrapperAttribute('class', 'use-shop-policy ' . $hidden);
                        $cancellationAgeFld->setWrapperAttribute('class', 'use-shop-policy ' . $hidden);

                        $selprod_threshold_stock_levelFld = $frmSellerProduct->getField('selprod_threshold_stock_level');
                        $selprod_threshold_stock_levelFld->htmlAfterField = '<small class="text--small">'.Labels::getLabel('LBL_Alert_stock_level_hint_info', $siteLangId). '</small>';
                        $selprod_threshold_stock_levelFld->setWrapperAttribute('class', 'selprod_threshold_stock_level_fld');
                        $urlFld = $frmSellerProduct->getField('selprod_url_keyword');
                        $urlFld->setFieldTagAttribute('id', "urlrewrite_custom");
                        $urlFld->setFieldTagAttribute('onkeyup', "getSlugUrl(this,this.value, $selprod_id, 'post')");
                        $urlFld->htmlAfterField = "<small class='text--small'>" . CommonHelper::generateFullUrl('Products', 'View', array($selprod_id), '/').'</small>';
                        $selprodCodEnabledFld = $frmSellerProduct->getField('selprod_cod_enabled');
                        $selprodCodEnabledFld->setWrapperAttribute('class', 'selprod_cod_enabled_fld');

                        $fld = $frmSellerProduct->getField('selprod_subtract_stock');
                        $fld->setOptionListTagAttribute('class', 'listing--vertical listing--vertical-chcek');
                        $fld->developerTags['rdLabelAttributes'] = array('class'=>'radio');
                        $fld->developerTags['rdHtmlAfterRadio'] = '<i class="input-helper"></i>';

                        $fld = $frmSellerProduct->getField('selprod_track_inventory');
                        $fld->setOptionListTagAttribute('class', 'listing--vertical listing--vertical-chcek');
                        $fld->developerTags['rdLabelAttributes'] = array('class'=>'radio');
                        $fld->developerTags['rdHtmlAfterRadio'] = '<i class="input-helper"></i>';
                        ?>

                        <?php echo $frmSellerProduct->getFormTag(); ?>
                         <div class="row">
                             <div class="col-md-6">
                                 <div class="field-set">
                                     <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_title'.FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1))->getCaption(); ?><span class="spn_must_field">*</span></label></div>
                                     <div class="field-wraper">
                                         <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_title'.FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1)); ?></div>
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
                                     <div class="caption-wraper">
                                         <div class="field_label"><?php echo $frmSellerProduct->getField('selprod_subtract_stock')->getCaption(); ?></div>
                                     </div>
                                     <div class="field-wraper">
                                         <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_subtract_stock'); ?></div>
                                     </div>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="field-set d-flex align-items-center">
                                     <div class="caption-wraper">
                                         <div class="field_label"><?php echo $frmSellerProduct->getField('selprod_track_inventory')->getCaption(); ?></div>
                                     </div>
                                     <div class="field-wraper">
                                         <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_track_inventory'); ?></div>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <div class="row">
                             <div class="selprod_threshold_stock_level_fld col-md-6">
                                 <div class="field-set">
                                     <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_threshold_stock_level')->getCaption(); ?></label></div>
                                     <div class="field-wraper">
                                         <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_threshold_stock_level'); ?></div>
                                     </div>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="field-set">
                                     <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_min_order_qty')->getCaption(); ?></label></div>
                                     <div class="field-wraper">
                                         <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_min_order_qty'); ?><span class="spn_must_field">*</span></div>
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
                             <?php if ($product_type == Product::PRODUCT_TYPE_PHYSICAL) { ?>
                             <div class="col-md-6">
                                 <div class="field-set">
                                     <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_condition')->getCaption(); ?><span class="spn_must_field">*</span></label></div>
                                     <div class="field-wraper">
                                         <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_condition'); ?></div>
                                     </div>
                                 </div>
                             </div>
                             <?php } ?>
                             <div class="col-md-6">
                                 <div class="field-set">
                                     <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_available_from')->getCaption(); ?><span class="spn_must_field">*</span></label></div>
                                     <div class="field-wraper">
                                         <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_available_from'); ?></div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="row">
                             <div class="col-md-6">
                                 <div class="field-set">
                                     <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_active')->getCaption(); ?></label></div>
                                     <div class="field-wraper">
                                         <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_active'); ?></div>
                                     </div>
                                 </div>
                             </div>
                             <div class="selprod_cod_enabled_fld col-md-6">
                                 <div class="field-set">
                                     <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_cod_enabled')->getCaption(); ?></label></div>
                                     <div class="field-wraper">
                                         <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_cod_enabled'); ?></div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                        <?php if ($optionCombinations) { ?>
                         <div class="row">
                             <div class="col-md-12">
                                 <table id="shipping" class="table table-bordered mb-4">
                                     <thead>
                                         <tr>
                                             <th width="20%"><?php echo Labels::getLabel('LBL_Variant/Option', $siteLangId); ?></th>
                                             <th width="20%"><?php echo Labels::getLabel('LBL_Cost_Price', $siteLangId); ?></th>
                                             <th width="20%"><?php echo Labels::getLabel('LBL_Selling_Price', $siteLangId); ?></th>
                                             <th width="20%"><?php echo Labels::getLabel('LBL_Quantity', $siteLangId); ?></th>
                                             <th width="20%"><?php echo Labels::getLabel('LBL_SKU', $siteLangId); ?></th>
                                             <th></th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                        <?php foreach ($optionCombinations as $optionKey => $optionValue) { ?>
                                         <tr>
                                             <td><?php echo $optionValue; ?></td>
                                             <td><?php echo $frmSellerProduct->getFieldHtml('selprod_cost'.$optionKey); ?></td>
                                             <td><?php echo $frmSellerProduct->getFieldHtml('selprod_price'.$optionKey); ?></td>
                                             <td><?php echo $frmSellerProduct->getFieldHtml('selprod_stock'.$optionKey); ?></td>
                                             <td><?php echo $frmSellerProduct->getFieldHtml('selprod_sku'.$optionKey); ?></td>
                                         </tr>
                                        <?php } ?>
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                        <?php } ?>
                         <div class="row">
                             <div class="col-md-12">
                                 <div class="field-set">
                                     <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_comments'.FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1))->getCaption(); ?></label></div>
                                     <div class="field-wraper">
                                         <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_comments'.FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1)); ?></div>
                                     </div>
                                 </div>
                                <?php $languages = Language::getAllNames();
                                unset($languages[FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1)]);
                                foreach ($languages as $langId => $langName) { ?>
                                    <div class="accordians_container accordians_container-categories">
                                        <div class="accordian_panel">
                                            <span class="accordian_title accordianhead accordian_title"><?php echo Labels::getLabel('LBL_Inventory_Data_for', $siteLangId) ?> {<?php echo $langName;?>}</span>
                                            <div class="accordian_body accordiancontent p-0" style="display: none;">
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
                         <div class="row">
                             <div class="col-md-12">
                                 <div class="field-set">
                                     <div class="caption-wraper"><label class="field_label"></label></div>
                                     <div class="field-wraper">
                                         <div class="field_cover"><input data-field-caption="" data-fatreq="{&quot;required&quot;:false}" type="submit" name="btn_submit" value="Save Changes"></div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </form>
                     <?php echo $frmSellerProduct->getExternalJS();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo FatUtility::createHiddenFormFromData(array('product_id' => $product_id), array('name' => 'frmSearchSellerProducts'));?>
<script type="text/javascript">
    var PERCENTAGE = <?php echo applicationConstants::PERCENTAGE; ?>;
    var FLAT = <?php echo applicationConstants::FLAT; ?>;

    $("document").ready(function() {
        var INVENTORY_TRACK = <?php echo Product::INVENTORY_TRACK; ?>;
        var INVENTORY_NOT_TRACK = <?php echo Product::INVENTORY_NOT_TRACK; ?>;

        var PRODUCT_TYPE_DIGITAL = <?php echo Product::PRODUCT_TYPE_DIGITAL; ?>;
        var productType = <?php echo $product_type; ?>;
        var shippedBySeller = <?php echo $shippedBySeller; ?>;

        if (productType == PRODUCT_TYPE_DIGITAL || shippedBySeller == 0) {
            $(".selprod_cod_enabled_fld").hide();
        }

        $("input[name='selprod_track_inventory']").change(function() {
            if ($("input[name='selprod_track_inventory']:checked").val() == INVENTORY_TRACK) {
                $("input[name='selprod_threshold_stock_level']").removeAttr("disabled");
            }

            if ($("input[name='selprod_track_inventory']:checked").val() == INVENTORY_NOT_TRACK) {
                $("input[name='selprod_threshold_stock_level']").val(0);
                $("input[name='selprod_threshold_stock_level']").attr("disabled", "disabled");
            }
        });

        $("input[name='selprod_track_inventory']").trigger('change');

        $("#use_shop_policy").change(function() {
            if ($(this).is(":checked")) {
                $('.use-shop-policy').addClass('hidden');
            } else {
                $('.use-shop-policy').removeClass('hidden');
            }
        });
    });
</script>
