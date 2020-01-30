<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$imagesFrm->setFormTagAttribute('class', 'web_form mt-5');
$imagesFrm->setFormTagAttribute('id', 'imageFrm');
$imagesFrm->developerTags['colClassPrefix'] = 'col-md-';
$imagesFrm->developerTags['fld_default_col'] = 6;

$imgFld = $imagesFrm->getField('prod_image');
$imgFld->setFieldTagAttribute('onchange', 'submitImageUploadForm(); return false;');
$imgFld->htmlBeforeField='<span class="filename"></span>';
$imgFld->htmlAfterField='<br/><small>'.Labels::getLabel('LBL_Please_keep_image_dimensions_greater_than_500_x_500._You_can_upload_multiple_photos_from_here.', $adminLangId).'</small>';

$optionFld = $imagesFrm->getField('option_id');
$optionFld->addFieldTagAttribute('class', 'option-js');

$langFld = $imagesFrm->getField('lang_id');
$langFld->addFieldTagAttribute('class', 'language-js');

?>
<div class="row justify-content-center">
     <div class="col-md-12">
         <?php echo $imagesFrm->getFormHtml(); ?>
         
         <div id="imageupload_div" class="padd15">
            <?php if (!empty($product_images)) { ?>
            <ul class="grids--onefifth ui-sortable" id="<?php if ($canEdit) { ?>sortable<?php } ?>">
                <?php
                $count=1;
                foreach ($product_images as $afile_id => $row) { ?>
                <li id="<?php echo $row['afile_id']; ?>">
                    <div class="logoWrap">
                        <div class="logothumb"> <img src="<?php echo CommonHelper::generateUrl('image', 'product', array($row['afile_record_id'], "THUMB",$row['afile_id']), CONF_WEBROOT_URL); ?>" title="<?php echo $row['afile_name'];?>"
                                alt="<?php echo $row['afile_name'];?>"> <?php echo ($count == 1) ? '<small><strong>'.Labels::getLabel('LBL_Default_Image', $adminLangId).'</strong></small>' : '&nbsp;'; if ($canEdit) { ?> <a
                                class="deleteLink white" href="javascript:void(0);" title="Delete <?php echo $row['afile_name'];?>" onclick="deleteProductImage(<?php echo $row['afile_record_id']; ?>, <?php echo $row['afile_id']; ?>);"
                                class="delete"><i class="ion-close-round"></i></a>
                            <?php } ?>
                        </div>
                        <?php if (!empty($imgTypesArr[$row['afile_record_subid']])) {
                            echo '<small class=""><strong>'.Labels::getLabel('LBL_Type', $adminLangId).': </strong> '.$imgTypesArr[$row['afile_record_subid']].'</small><br/>';
                                $lang_name = Labels::getLabel('LBL_All', $adminLangId);
                                if ($row['afile_lang_id'] > 0) {
                                    $lang_name = $languages[$row['afile_lang_id']]; ?>
                                            <?php
                                } ?>
                        <small class=""><strong> <?php echo Labels::getLabel('LBL_Language', $adminLangId); ?>:</strong> <?php echo $lang_name; ?></small>
                    </div>
                </li>
                <?php $count++;} ?>
                <?php } ?>
            </ul>
            <?php } ?>
        </div>
                    
     </div>
</div>

<script type="text/javascript">
$(function() {
    $("#sortable").sortable({
        stop: function() {
            var mysortarr = new Array();
            $(this).find('li').each(function() {
                mysortarr.push($(this).attr("id"));
            });
            var product_id = $('#imageFrm input[name=product_id]').val();
            var sort = mysortarr.join('-');
            var lang_id = $('.language-js').val();
            var option_id = $('.option-js').val();
            data = '&product_id=' + product_id + '&ids=' + sort;
            fcom.updateWithAjax(fcom.makeUrl('products', 'setImageOrder'), data, function(t) {
                productImages(product_id, option_id, lang_id);
            });
        }
    }).disableSelection();
});
</script>
   

