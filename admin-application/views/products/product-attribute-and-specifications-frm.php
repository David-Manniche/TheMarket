<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$productFrm->setFormTagAttribute('class', 'web_form mt-5');
//$productFrm->setFormTagAttribute('onsubmit', 'setUpProduct(this); return(false);');

$fldSeller = $productFrm->getField('selprod_user_shop_name');
$productFrm->htmlAfterField = '<br/><small>'.Labels::getLabel('LBL_Please_leave_empty_if_you_want_to_add_product_in_system_catalog', $adminLangId).' </small>';
if ($productData['product_added_by_admin_id'] == 1 && $totalProducts >0) {
    $fldSeller->setfieldTagAttribute('readonly', 'readonly');
}
?>
<div class="row justify-content-center">
     <div class="col-md-9">
        <?php echo $productFrm->getFormTag(); ?>
        <div class="row">
             <div class="col-md-6">
                 <div class="field-set">
                     <div class="caption-wraper">
                        <label class="field_label">
                            <?php $fld = $productFrm->getField('selprod_user_shop_name');
                              echo $fld->getCaption();
                            ?>
                        </label>
                     </div>
                     <div class="field-wraper">
                         <div class="field_cover">
                         <?php echo $productFrm->getFieldHtml('selprod_user_shop_name'); ?>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col-md-6">
                 <div class="field-set">
                     <div class="caption-wraper">
                        <label class="field_label">
                        <?php $fld = $productFrm->getField('product_model');
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
             <?php if($productData['product_type'] == Product::PRODUCT_TYPE_PHYSICAL) { ?>
             <div class="col-md-4">
                 <div class="field-set">
                     <div class="caption-wraper"></div>
                     <div class="field-wraper">
                         <div class="field_cover">
                         <?php echo $productFrm->getFieldHtml('ps_free');  ?>
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
         
         <div class="row">
             <div class="col-md-12">
                 <div class="field-set">
                     <div class="caption-wraper">
                        <label class="field_label">
                        <?php $fld = $productFrm->getField('product_collection');
                              echo $fld->getCaption();
                        ?>
                        </label>
                    </div>
                     <div class="field-wraper">
                         <div class="field_cover">
                          <?php echo $productFrm->getFieldHtml('product_collection'); ?>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
         
         <div class="p-4 mb-4 bg-gray rounded">
             <div class="row">
                <div class="col-md-5">
                     <div class="field-set">
                         <div class="caption-wraper">
                            <label class="field_label">
                                <?php $fld = $productFrm->getField('prodspec_name['.$siteDefaultLangId.']');
                                      echo $fld->getCaption();
                                ?>
                            </label>
                         </div>
                         <div class="field-wraper">
                            <div class="field_cover">
                               <?php echo $productFrm->getFieldHtml('prodspec_name['.$siteDefaultLangId.']'); ?>                           
                            </div>
                         </div>
                     </div>
                 </div>
                 <div class="col-md-5">
                     <div class="field-set">
                         <div class="caption-wraper">
                            <label class="field_label">
                                <?php $fld = $productFrm->getField('prodspec_value['.$siteDefaultLangId.']');
                                      echo $fld->getCaption();
                                ?>
                            </label>
                         </div>
                         <div class="field-wraper">
                            <div class="field_cover">
                               <?php echo $productFrm->getFieldHtml('prodspec_value['.$siteDefaultLangId.']'); ?></div>
                         </div>
                     </div>
                 </div>
                 <div class="col-md-2">
                     <div class="field-set">
                         <div class="caption-wraper"></div>
                         <div class="field-wraper">
                            <div class="field_cover">
                            <button type="button" class="btn btn-primary" onClick="addSpecification(<?php echo $siteDefaultLangId; ?>)"><?php echo Labels::getLabel('LBL_Add', $adminLangId) ?></button></div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
         
         <div class="specifications-<?php echo $siteDefaultLangId; ?>">
             <?php if(!empty($productSpecifications)){ ?>
             <div class="row">
                 <div class="col-md-12">
                     <div class="tablewrap">
                         <table width="100%" class="table table-bordered">
                             <thead>
                                 <tr>
                                     <th width="80%"><?php echo Labels::getLabel('LBL_Specification', $adminLangId) ?></th>
                                     <th><?php echo Labels::getLabel('LBL_Action', $adminLangId) ?></th>
                                 </tr>
                             </thead>
                             <tbody>
                                 <?php foreach($productSpecifications as $specifications){?>
                                 <tr>
                                     <td><?php echo $specifications[$siteDefaultLangId]['prodspec_name'];?>: <?php echo $specifications[$siteDefaultLangId]['prodspec_value'];?></td>
                                     <td>
                                        <a title="Edit details" class="btn btn-sm btn-clean btn-icon btn-icon-md"> <i class="fa fa-edit"></i> </a>
                                         <a title="Delete" class="btn btn-sm btn-clean btn-icon btn-icon-md"> <i class="fa fa-trash"></i> </a>
                                     </td>
                                 </tr>
                                <?php } ?>
                             </tbody>
                         </table>

                     </div>
                 </div>
             </div>
             <?php } ?>
         </div>
         
         <?php 
         if(!empty($otherLanguages)){ 
            foreach($otherLanguages as $langId=>$data) { 
         ?>
         <div class="accordians_container accordians_container-categories mt-5">
             <div class="accordian_panel">
                 <span class="accordian_title accordianhead" id="collapse_<?php echo $langId; ?>">
                 <?php echo $data." "; echo Labels::getLabel('LBL_Language_Specification', $adminLangId); ?>
                 </span>
                 <div class="accordian_body accordiancontent" style="display: none;">
                     <div class="row">
                        <div class="col-md-5">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                    <?php  $fld = $productFrm->getField('prodspec_name['.$langId.']');
                                        echo $fld->getCaption(); ?>
                                    </label>
                                </div>
                                <div class="field-wraper">
                                    <div class="field_cover">
                                    <?php echo $productFrm->getFieldHtml('prodspec_name['.$langId.']'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-5">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label">
                                    <?php  $fld = $productFrm->getField('prodspec_value['.$langId.']');
                                        echo $fld->getCaption(); ?>
                                    </label>
                                </div>
                                <div class="field-wraper">
                                    <div class="field_cover">
                                    <?php echo $productFrm->getFieldHtml('prodspec_value['.$langId.']'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="field-set">
                                <div class="caption-wraper"></div>
                                <div class="field-wraper">
                                <div class="field_cover">
                                    <button type="button" class="btn btn-primary" onClick="addSpecification(<?php echo $langId; ?>)"><?php echo Labels::getLabel('LBL_Add', $adminLangId) ?></button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="specifications-<?php echo $langId; ?>">
                        <?php if(!empty($productSpecifications)){ ?>
                         <div class="row">
                             <div class="col-md-12">
                                 <div class="tablewrap">
                                     <table width="100%" class="table table-bordered">
                                         <thead>
                                             <tr>
                                                 <th width="80%"><?php echo Labels::getLabel('LBL_Specification', $adminLangId) ?></th>
                                                 <th><?php echo Labels::getLabel('LBL_Action', $adminLangId) ?></th>
                                             </tr>
                                         </thead>
                                         <tbody>
                                             <?php foreach($productSpecifications as $specifications){
                                               ?>
                                             <tr>
                                                 <td><?php echo $specifications[$langId]['prodspec_name'];?>: <?php echo $specifications[$langId]['prodspec_value'];?></td>
                                                 <td>
                                                    <a title="Edit details" class="btn btn-sm btn-clean btn-icon btn-icon-md"> <i class="fa fa-edit"></i> </a>
                                                     <a title="Delete" class="btn btn-sm btn-clean btn-icon btn-icon-md"> <i class="fa fa-trash"></i> </a>
                                                 </td>
                                             </tr>
                                            <?php } ?>
                                         </tbody>
                                     </table>

                                 </div>
                             </div>
                         </div>
                      <?php } ?>
                    </div>
                 </div>
             </div>
         </div>
         <?php } 
         }
         ?>

         <div class="row">
             <div class="col-md-6">
                 <div class="field-set">
                     <div class="caption-wraper"><label class="field_label"></label></div>
                     <div class="field-wraper">
                         <div class="field_cover">
                         <?php 
                         echo $productFrm->getFieldHtml('product_seller_id'); 
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
    var product_added_by_admin = <?php echo $productData['product_added_by_admin_id']; ?> ;
    var totalProducts = <?php echo $totalProducts; ?> ;
    
    if (product_added_by_admin == 1 && totalProducts == 0) {
        $('input[name=\'selprod_user_shop_name\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: fcom.makeUrl('sellerProducts', 'autoCompleteUserShopName'),
                    data: {
                        keyword: request,
                        fIsAjax: 1
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['user_name'] + ' - ' + item['shop_identifier'],
                                value: item['user_id']
                            };
                        }));
                    },
                });
            },
            'select': function(item) {
                $("input[name='product_seller_id']").val(item['value']);
                $("input[name='selprod_user_shop_name']").val(item['label']);
            }
        });
    } else {
        $('input[name=\'selprod_user_shop_name\']').addClass('readonly-field');
        $('input[name=\'selprod_user_shop_name\']').attr('readonly', true);
    }

    $('input[name=\'selprod_user_shop_name\']').change(function() {
        if ($(this).val() == '') {
            $("input[name='product_seller_id']").val(0);
        }
    });
        
</script>