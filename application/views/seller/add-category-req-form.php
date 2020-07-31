<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'form form--horizontal');
$frm->developerTags['colClassPrefix'] = 'col-lg-12 col-md-12 col-sm-';
$frm->developerTags['fld_default_col'] = 12;
$frm->setFormTagAttribute('onsubmit', 'setupCategoryReq(this); return(false);');
$identifierFld = $frm->getField(ProductCategory::DB_TBL_PREFIX.'id');
$identifierFld->setFieldTagAttribute('id', ProductCategory::DB_TBL_PREFIX.'id');
?>
<div class="box__head">
<h4><?php echo Labels::getLabel('LBL_Request_New_Category', $langId); ?></h4>
</div>
<div class="box__body">
    <div class="tabs tabs--small tabs--scroll">
        <ul>
            <li class="is-active"><a href="javascript:void(0)" onclick="addCategoryReqForm(<?php echo $categoryReqId; ?>);"><?php echo Labels::getLabel('LBL_Basic', $siteLangId);?></a></li>
            <li class="<?php echo (0 == $categoryReqId) ? 'fat-inactive' : ''; ?>">
                <a href="javascript:void(0);" <?php echo (0 < $categoryReqId) ? "onclick='addCategoryReqLangForm(" . $categoryReqId . "," . FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1) . ");'" : ""; ?>>
                    <?php echo Labels::getLabel('LBL_Language_Data', $siteLangId); ?>
                </a>
            </li>
            <?php $inactive=($categoryReqId==0)?'fat-inactive':''; ?>
            <li class="<?php echo $inactive;?>"><a href="javascript:void(0)"
                <?php if ($categoryReqId > 0) { ?>
                    onclick="categoryMediaForm(<?php echo $categoryReqId ?>);"
                <?php } ?>><?php echo Labels::getLabel('LBL_Media', $siteLangId); ?></a>
            </li>
        </ul>
    </div>
    <?php
        echo $frm->getFormHtml();
    ?>
</div>
