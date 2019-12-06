<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="cards-content pl-4 pr-4 ">
    <div class="">
        <div class="tabs tabs-sm tabs--scroll clearfix">
            <ul>
                <li><a href="javascript:void(0)" onClick="addForm(<?php echo $splatform_id;?>);"><?php echo Labels::getLabel('LBL_General', $siteLangId); ?></a></li>
                <li class="is-active">
                    <a href="javascript:void(0);">
                        <?php echo Labels::getLabel('LBL_Language_Data', $siteLangId); ?>
                    </a>
                </li>
                <?php
                /* foreach ($languages as $langId => $langName) {?>
                <li class="<?php echo ($splatform_lang_id == $langId)?'is-active':'' ; ?>">
                <a href="javascript:void(0)" <?php if ($splatform_id>0) {?> onClick="addLangForm(<?php echo $splatform_id;?> , <?php echo $langId;?>);" <?php }?>>
                        <?php echo $langName;?></a></li>
                <?php } */ ?>
            </ul>
        </div>
    </div>
    <div class="form__subcontent">
        <?php
        $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        if (!empty($translatorSubscriptionKey) && $splatform_lang_id != $siteDefaultLangId) { ?> 
            <div class="row justify-content-end"> 
                <div class="col-auto mb-4">
                    <input class="btn btn-primary" 
                        type="button" 
                        value="<?php echo Labels::getLabel('LBL_AUTOFILL_LANGUAGE_DATA', $siteLangId); ?>" 
                        onClick="addLangForm(<?php echo $splatform_id; ?>, <?php echo $splatform_lang_id; ?>, 1)">
                </div>
            </div>
        <?php } ?>
        <?php
        $langFrm->setFormTagAttribute('onsubmit', 'setupLang(this); return(false);');
        $langFrm->setFormTagAttribute('class', 'form form--horizontal layout--'.$formLayout);
        $langFrm->developerTags['colClassPrefix'] = 'col-lg-8 col-md-8 col-sm-';
        $langFrm->developerTags['fld_default_col'] = 8;
        $langFld = $langFrm->getField('lang_id');
        $langFld->setfieldTagAttribute('onChange', "addLangForm(" . $splatform_id . ", this.value);");
        echo $langFrm->getFormHtml();
        ?>
    </div>
</div>
