<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Labels::getLabel('LBL_Product_Setup', $adminLangId); ?>
        </h4>
    </div>
    <div class="sectionbody space">
        <div class="tabs_nav_container  flat">
            <?php /* require_once 'sellerCatalogProductTop.php'; */?>

            <div class="tabs_panel_wrap">
                <ul class="tabs_nav tabs_nav--internal">
                    <li><a class="active" href="javascript:void(0)"
                            onClick="sellerProductForm(<?php echo $product_id;?>,<?php echo $selprod_id;?>)"><?php echo Labels::getLabel('LBL_Basic', $adminLangId); ?></a>
                    </li>
                    <li class="<?php echo (0 == $selprod_id) ? 'fat-inactive' : ''; ?>">
                        <a href="javascript:void(0);" <?php echo (0 < $selprod_id) ? "onclick='sellerProductLangForm(" . $selprod_id . "," . FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1) . ");'" : ""; ?>>
                            <?php echo Labels::getLabel('LBL_Language_Data', $adminLangId); ?>
                        </a>
                    </li>
                    <?php $inactive = ($selprod_id == 0) ? 'fat-inactive' : ''; ?>
                    <li class="<?php echo $inactive ; ?>"><a
                            href="javascript:void(0)" <?php if ($selprod_id > 0) { ?>
                            onClick="linkPoliciesForm(<?php echo $product_id,',',$selprod_id,',',PolicyPoint::PPOINT_TYPE_WARRANTY ; ?>)"
                            <?php } ?>>
                            <?php echo Labels::getLabel('LBL_Link_Warranty_Policies', $adminLangId); ?></a>
                    </li>
                    <li class="<?php echo $inactive ; ?>"><a
                            href="javascript:void(0)" <?php if ($selprod_id > 0) {
                            ?>
                            onClick="linkPoliciesForm(<?php echo $product_id,',',$selprod_id,',',PolicyPoint::PPOINT_TYPE_RETURN ; ?>)"
                            <?php
                        }?>><?php echo Labels::getLabel('LBL_Link_Return_Policies', $adminLangId); ?></a>
                    </li>
                </ul>
                <div class="tabs_panel_wrap">
                    <?php
                    $frmSellerProduct->setFormTagAttribute('onsubmit', 'setUpSellerProduct(this); return(false);');
                    $frmSellerProduct->setFormTagAttribute('class', 'web_form');
                    $frmSellerProduct->developerTags['colClassPrefix'] = 'col-md-';
                    $frmSellerProduct->developerTags['fld_default_col'] = 12;

                    $returnAgeFld = $frmSellerProduct->getField('selprod_return_age');
                    $cancellationAgeFld = $frmSellerProduct->getField('selprod_cancellation_age');
                    $returnAge = FatUtility::int($returnAgeFld->value);
                    $hidden = '';
                    if ('' === $returnAgeFld->value || '' === $cancellationAgeFld->value) {
                        $hidden = 'hide';
                    }
                    $returnAgeFld->setWrapperAttribute('class', 'use-shop-policy ' . $hidden);
                    $cancellationAgeFld->setWrapperAttribute('class', 'use-shop-policy ' . $hidden);

                    $selprod_threshold_stock_levelFld = $frmSellerProduct->getField('selprod_threshold_stock_level');
                    $selprod_threshold_stock_levelFld->htmlAfterField = '<small class="text--small">'.Labels::getLabel('LBL_Alert_stock_level_hint_info', $adminLangId). '</small>';
                    $selprod_threshold_stock_levelFld->setWrapperAttribute('class', 'selprod_threshold_stock_level_fld');
                    $idFld= $frmSellerProduct->getField('selprod_id');
                    $idFld->setFieldTagAttribute('id', 'selprod_id');
                    $shopUserNameFld= $frmSellerProduct->getField('selprod_user_shop_name');
                    $shopUserNameFld->setfieldTagAttribute('readonly', 'readonly');
                    $urlFld= $frmSellerProduct->getField('selprod_url_keyword');
                    $urlFld->htmlAfterField = "<small class='text--small'>" . CommonHelper::generateFullUrl('Products', 'View', array($selprod_id), CONF_WEBROOT_FRONT_URL).'</small>';
                    $urlFld->setFieldTagAttribute('onkeyup', "getSlugUrl(this,this.value,$selprod_id,'post')");
                    $selprodCodEnabledFld = $frmSellerProduct->getField('selprod_cod_enabled');
                    $selprodCodEnabledFld->setWrapperAttribute('class', 'selprod_cod_enabled_fld');

                    $fld = $frmSellerProduct->getField('selprod_subtract_stock');
                    $fld->developerTags['cbLabelAttributes'] = array('class' => 'checkbox');
                    $fld->developerTags['cbHtmlAfterCheckbox'] = '<i class="input-helper"></i>';

                    $fld = $frmSellerProduct->getField('selprod_track_inventory');
                    $fld->developerTags['cbLabelAttributes'] = array('class' => 'checkbox');
                    $fld->developerTags['cbHtmlAfterCheckbox'] = '<i class="input-helper"></i>';

                    $fld = $frmSellerProduct->getField('use_shop_policy');
                    $fld->developerTags['cbLabelAttributes'] = array('class' => 'checkbox');
                    $fld->developerTags['cbHtmlAfterCheckbox'] = '<i class="input-helper"></i>'; ?>
                        <?php echo $frmSellerProduct->getFormTag(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-set">
                                    <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_title'.FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1))->getCaption(); ?><span
                                                class="spn_must_field">*</span></label></div>
                                    <div class="field-wraper">
                                        <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_title'.FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1)); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-set">
                                    <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_user_shop_name')->getCaption(); ?><span class="spn_must_field">*</span></label></div>
                                    <div class="field-wraper">
                                        <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_user_shop_name'); ?></div>
                                    </div>
                                </div>
                            </div>
                         </div>
                         <div class="row">
                            <div class="col-md-6">
                                 <div class="field-set">
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                            <?php echo $frmSellerProduct->getFieldHtml('selprod_subtract_stock'); ?>
                                        </div>
                                    </div>
                                 </div>
                            </div>
                            <div class="col-md-6">
                                 <div class="field-set">
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-set">
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
                         <div class="row">
                             <div class="col-md-12">
                                 <table id="shipping" class="table table-bordered mb-4">
                                     <thead>
                                         <tr>
                                            <?php if (!empty($optionValues)) { ?>
                                             <th width="20%"><?php echo Labels::getLabel('LBL_Variant/Option', $adminLangId); ?></th>
                                            <?php } ?>
                                             <th width="20%"><?php echo Labels::getLabel('LBL_Cost_Price', $adminLangId); ?></th>
                                             <th width="20%"><?php echo Labels::getLabel('LBL_Selling_Price', $adminLangId); ?> <i class="fa fa-question-circle-o tooltip tooltip--right"><span class="hovertxt"><?php echo Labels::getLabel('LBL_This_price_is_excluding_the_tax_rates.', $adminLangId).' '.Labels::getLabel('LBL_Min_Selling_price', $adminLangId).' '. CommonHelper::displayMoneyFormat($productMinSellingPrice, true, true); ?></span></i></th>
                                             <th width="20%"><?php echo Labels::getLabel('LBL_Quantity', $adminLangId); ?></th>
                                             <th width="20%"><?php echo Labels::getLabel('LBL_SKU', $adminLangId); ?> <i class="fa fa-question-circle-o tooltip tooltip--right"><span class="hovertxt"><?php echo Labels::getLabel('LBL_Stock_Keeping_Unit', $adminLangId) ?></span></i></th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         <tr>
                                            <?php if (!empty($optionValues)) { ?>
                                             <td><?php /*echo str_replace("_", " | ", $optionValue);*/ ?></td>
                                            <?php } ?>
                                             <td><?php echo $frmSellerProduct->getFieldHtml('selprod_cost'); ?></td>
                                             <td><?php echo $frmSellerProduct->getFieldHtml('selprod_price'); ?></td>
                                             <td><?php echo $frmSellerProduct->getFieldHtml('selprod_stock'); ?></td>
                                             <td><?php echo $frmSellerProduct->getFieldHtml('selprod_sku'); ?></td>
                                         </tr>
                                     </tbody>
                                 </table>
                             </div>
                         </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="field-set">
                                    <div class="caption-wraper"><label class="field_label"><?php echo $frmSellerProduct->getField('selprod_comments'.FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1))->getCaption(); ?></label></div>
                                    <div class="field-wraper">
                                        <div class="field_cover"><?php echo $frmSellerProduct->getFieldHtml('selprod_comments'.FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1)); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php $languages = Language::getAllNames();
                                unset($languages[FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1)]);
                                foreach ($languages as $langId => $langName) { ?>
                                <div class="accordians_container accordians_container-categories">
                                    <div class="accordian_panel">
                                        <span class="accordian_title accordianhead accordian_title"><?php echo Labels::getLabel('LBL_Inventory_Data_for', $adminLangId) ?> <?php echo $langName;?></span>
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
                                         <div class="field_cover">
                                            <?php echo $frmSellerProduct->getFieldHtml('btn_submit'); ?>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                        </div>
                        <?php echo $frmSellerProduct->getFieldHtml('selprod_product_id');
                        echo $frmSellerProduct->getFieldHtml('selprod_id');?>
                        </form>
                        <?php echo $frmSellerProduct->getExternalJS();?>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $("document").ready(function() {
        var addedByAdmin = <?php echo $product_added_by_admin; ?> ;
        var
            PRODUCT_TYPE_DIGITAL = <?php echo Product::PRODUCT_TYPE_DIGITAL; ?> ;
        var productType = <?php echo $product_type; ?> ;
        var shippedBySeller = <?php echo $shippedBySeller; ?> ;
        if (productType == PRODUCT_TYPE_DIGITAL || shippedBySeller == 0) {
            $(".selprod_cod_enabled_fld").hide();
        }
        /* if( addedByAdmin == 1 )
        {
            $('input[name=\'selprod_user_shop_name\']').autocomplete({
                'source': function(request, response) {
                    $.ajax({
                        url: fcom.makeUrl('sellerProducts', 'autoCompleteUserShopName'),
                        data: {keyword: request, fIsAjax:1},
                        dataType: 'json',
                        type: 'post',
                        success: function(json) {
                            response($.map(json, function(item) {
                                return { label: item['user_name'] +' - '+item['shop_identifier'],    value: item['user_id']    };
                            }));
                        },
                    });
                },
                'select': function(item) {
                    $("input[name='selprod_user_id']").val( item['value'] );
                    $("input[name='selprod_user_shop_name']").val( item['label'] );
                }
            });
        } */
        var INVENTORY_TRACK = <?php echo Product::INVENTORY_TRACK; ?> ;
        var INVENTORY_NOT_TRACK = <?php echo Product::INVENTORY_NOT_TRACK; ?> ;

        $("input[name='selprod_track_inventory']").change(function() {
            if( $(this).prop("checked") == false ){
                $("input[name='selprod_threshold_stock_level']").val(0);
                $("input[name='selprod_threshold_stock_level']").attr("disabled", "disabled");
            } else {
                $("input[name='selprod_threshold_stock_level']").removeAttr("disabled");
            }
        });

        $("input[name='selprod_track_inventory']").trigger('change');

        $("#use_shop_policy").change(function(){
            if ($(this).is(":checked")) {
                $('.use-shop-policy').addClass('hide');
            } else {
                $('.use-shop-policy').removeClass('hide');
            }
        });
    });
</script>
