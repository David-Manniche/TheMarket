<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<div class="cards-content p-4">
	<div class="tabs tabs-sm tabs--scroll clearfix">
		<ul>
			<li class="is-active"><a href="javascript:void(0)" onClick="socialPlatformForm(<?php echo $splatform_id;?>);"><?php echo Labels::getLabel('LBL_General', $siteLangId); ?></a></li>
            <li class="<?php echo (0 == $splatform_id) ? 'fat-inactive' : ''; ?>">
                <a href="javascript:void(0);" <?php echo (0 < $splatform_id) ? "onclick='addLangForm(" . $splatform_id . "," . FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1) . ");'" : ""; ?>>
                    <?php echo Labels::getLabel('LBL_Language_Data', $siteLangId); ?>
                </a>
            </li>
		</ul>
	</div>
	<div class="form__subcontent">
		<?php
        $frm->setFormTagAttribute('onsubmit', 'setup(this); return(false);');
        $frm->setFormTagAttribute('class', 'form form--horizontal');
        $frm->developerTags['colClassPrefix'] = 'col-lg-8 col-md-8 col-sm-';
        $frm->developerTags['fld_default_col'] = 8;
        $urlFld = $frm->getField('splatform_url');
        $urlFld->htmlAfterField = '<span class="text--small">'.Labels::getLabel('LBL_Example_Url', $siteLangId).'</span>';
        echo $frm->getFormHtml();
        ?>
	</div>
</div>
