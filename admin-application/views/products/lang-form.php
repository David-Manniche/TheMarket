<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$productLangFrm->setFormTagAttribute('class', 'web_form layout--' . $formLayout);
$productLangFrm->setFormTagAttribute('onsubmit', 'setupProductLang(this); return(false);');
    
$productLangFrm->developerTags['colClassPrefix'] = 'col-md-';
$productLangFrm->developerTags['fld_default_col'] = 12;
/* $product_short_description_fld = $productLangFrm->getField('product_short_description');
$product_short_description_fld->htmlAfterField = 'Enter Data Separated By New Line. Shown on Products Listing Page.'; */

$langFld = $productLangFrm->getField('lang_ids');
$langFld->setfieldTagAttribute('onChange', "productLangForm(" . $product_id . ", this.value);");

?>
<section class="section">
    <div class="sectionhead">
        <h4>
            <?php echo Labels::getLabel('LBL_Product_Setup', $adminLangId); ?>
        </h4>
    </div>
    <div class="sectionbody space">
        <div class="row">
            <div class="col-sm-12">
                <div class="tabs_nav_container responsive flat">
                    <ul class="tabs_nav">
                        <li><a href="javascript:void(0);" onclick="productForm(<?php echo $product_id ?>, 0);">
                                <?php echo Labels::getLabel('LBL_General', $adminLangId); ?></a>
                        </li>
                        <li class="<?php echo (!$product_id) ? 'fat-inactive' : ''; ?>">
                            <a class="active" href="javascript:void(0);" <?php echo ($product_id) ? "onclick='productLangForm(" . $product_id . "," . $adminLangId . ");'" : ""; ?>>
                                <?php echo Labels::getLabel('LBL_Language_Data', $adminLangId); ?>
                            </a>
                        </li>
                        <?php /* foreach ($languages as $langId => $langName) { ?>
                                <li class="<?php echo (!$product_id) ? 'fat-inactive' : ''; ?>">
                                    <a class="<?php echo ($product_lang_id == $langId) ? ' active' : ''; ?>" href="javascript:void(0);" <?php echo ($product_id) ? "onclick='productLangForm( " . $product_id . "," . $langId . " );'" : ""; ?>>
                                    <?php echo Labels::getLabel('LBL_' . $langName, $adminLangId); ?></a>
                                </li>
                        <?php } */ ?>
                    </ul>
                    <div class="tabs_panel_wrap">
                        <?php if ($product_lang_id != $adminLangId) { ?> 
                            <input class="btn btn-primary" 
                                    type="button" 
                                    value="<?php echo Labels::getLabel('LBL_AUTOFILL_LANGUAGE_DATA', $adminLangId); ?>" 
                                    onClick="productLangForm(<?php echo $product_id; ?>, <?php echo $product_lang_id; ?>, 1)">
                        <?php } ?> 
                        <div class="tabs_panel">
                            <?php
                                echo $productLangFrm->getFormTag();
                                echo $productLangFrm->getFormHtml(false);
                                echo '</form>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>