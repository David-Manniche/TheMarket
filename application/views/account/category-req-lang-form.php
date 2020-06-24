<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$categoryReqLangFrm->setFormTagAttribute('class', 'form form--horizontal layout--'.$formLayout);
$categoryReqLangFrm->developerTags['colClassPrefix'] = 'col-lg-12 col-md-12 col-sm-';
$categoryReqLangFrm->developerTags['fld_default_col'] = 12;
$categoryReqLangFrm->setFormTagAttribute('onsubmit', 'setupCategoryReqLang(this); return(false);');
$categoryFld = $categoryReqLangFrm->getField('scategoryreq_name');
$categoryFld->setFieldTagAttribute('onblur','checkUniqueCategoryName(this,$("input[name=lang_id]").val(),'.$categoryReqId.')');

$langFld = $categoryReqLangFrm->getField('lang_id');
$langFld->setfieldTagAttribute('onChange', "addCategoryReqLangForm(" . $categoryReqId . ", this.value);");

?>
<div class="box__head">
	<h4><?php echo Labels::getLabel('LBL_Request_New_Category',$siteLangId); ?></h4>
</div>

<div class="box__body">		
	<div class="tabs">
		<ul>
			<li><a href="javascript:void(0)" onclick="addCategoryReqForm(<?php echo $categoryReqId ?>);"><?php echo Labels::getLabel('LBL_Basic', $siteLangId);?></a></li>
            <?php $inactive = ($categoryReqId == 0) ? ' fat-inactive' : ''; ?>
            <li class="<?php echo (0 < $categoryReqLangId) ? 'is-active' : ''; echo $inactive; ?>">
                <a href="javascript:void(0);" <?php echo (0 < $categoryReqId) ? "onclick='addCategoryReqLangForm(" . $categoryReqId . "," . FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1) . ");'" : ""; ?>>
                    <?php echo Labels::getLabel('LBL_Language_Data', $siteLangId); ?>
                </a>
            </li>
		</ul>
	</div>
	<div class="tabs tabs--small tabs tabs--scroll clearfix">
    <?php
        $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        if (!empty($translatorSubscriptionKey) && $categoryReqLangId != $siteDefaultLangId) { ?> 
            <div class="row justify-content-end"> 
                <div class="col-auto mb-4">
                    <input class="btn btn-primary" 
                        type="button" 
                        value="<?php echo Labels::getLabel('LBL_AUTOFILL_LANGUAGE_DATA', $siteLangId); ?>" 
                        onClick="addCategoryReqLangForm(<?php echo $categoryReqId; ?>, <?php echo $categoryReqLangId; ?>, 1)">
                </div>
            </div>
        <?php } ?>
		<?php
		echo $categoryReqLangFrm->getFormHtml();
		?>
	</div>
</div>