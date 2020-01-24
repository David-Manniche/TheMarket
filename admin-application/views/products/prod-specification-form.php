<?php defined('SYSTEM_INIT') or die('Invalid Usage.');  ?>
<div class="p-4 mb-4 bg-gray rounded">
     <div class="row">
        <div class="col-md-5">
             <div class="field-set">
                 <div class="caption-wraper">
                    <label class="field_label"><?php echo Labels::getLabel('LBL_Specification_Label_Text', $adminLangId); ?></label>
                 </div>
                 <div class="field-wraper">
                    <div class="field_cover">
                        <input type="text" name="prodspec_name[<?php echo $langId; ?>]" value="<?php if(!empty($prodSpecData)) { echo $prodSpecData[0]['prodspec_name']; } ?>">                        
                    </div>
                 </div>
             </div>
         </div>
         <div class="col-md-5">
             <div class="field-set">
                 <div class="caption-wraper">
                    <label class="field_label"><?php echo Labels::getLabel('LBL_Specification_Value', $adminLangId); ?></label>
                 </div>
                 <div class="field-wraper">
                    <div class="field_cover">
                        <input type="text" name="prodspec_value[<?php echo $langId; ?>]" value="<?php if(!empty($prodSpecData)) { echo $prodSpecData[0]['prodspec_value']; } ?>">
                    </div>
                </div>
            </div>
         </div>
         <div class="col-md-2">
             <div class="field-set">
                 <div class="caption-wraper"></div>
                 <div class="field-wraper">
                    <div class="field_cover">
                    <button type="button" class="btn btn-primary" onClick="saveSpecification(<?php echo $langId; ?>, <?php if(!empty($prodSpecData)) { echo $prodSpecData[0]['prodspec_id']; } ?>)"><?php echo Labels::getLabel('LBL_Add', $adminLangId) ?></button></div>
                 </div>
             </div>
         </div>
    </div>
</div>