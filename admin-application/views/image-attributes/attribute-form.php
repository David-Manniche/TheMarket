<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
$frm->setFormTagAttribute('class', 'web_form');
$frm->setFormTagAttribute('id', 'frmImgAttribute');
$frm->setFormTagAttribute('onsubmit', 'setup(this); return(false);');

$langFld = $frm->getField('lang_id');
$langFld->addFieldTagAttribute('class', 'language-js');

$btn = $frm->getField('btn_submit');
$btn->setFieldTagAttribute('class', "btn-clean btn-sm btn-icon btn-secondary");

$btn = $frm->getField('btn_discard');
$btn->addFieldTagAttribute('onClick', "discardForm()");
$btn->setFieldTagAttribute('class', "btn-clean btn-sm btn-icon btn-secondary");
?>
<?php echo $frm->getFormTag(); ?>
<div class="sectionhead">
    <h4><?php echo $title; ?><br/>
	<?php echo $frm->getFieldHtml('lang_id'); ?></h4>
    <div class="section__toolbar">
    <?php echo $frm->getFieldHtml('btn_discard'); ?>
    <?php echo $frm->getFieldHtml('btn_submit'); ?>
    </div>
</div>
<div class="sectionbody space">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="row justify-content-center">
				<?php if(!empty($images)) { ?>
					<?php foreach($images as $afileId => $afileData) {
						$frm->getField('image_title'.$afileId)->value = $afileData['afile_attribute_title'];
						$frm->getField('image_alt'.$afileId)->value = $afileData['afile_attribute_alt'];
						switch ($moduleType) {
							case AttachedFile::FILETYPE_PRODUCT_IMAGE:
								$imageUrl = CommonHelper::generateFullUrl('Image','Product', array($recordId, "THUMB", 0, $afileId, $langId), CONF_WEBROOT_FRONT_URL);
								break;
							case AttachedFile::FILETYPE_BRAND_LOGO:
								$imageUrl = CommonHelper::generateFullUrl('Image','brand', array($recordId, $langId, "THUMB", $afileId), CONF_WEBROOT_FRONT_URL);
								break;
							case AttachedFile::FILETYPE_BRAND_IMAGE:
								$imageUrl = CommonHelper::generateFullUrl('Image','brandImage', array($recordId, $langId, "THUMB", $afileId), CONF_WEBROOT_FRONT_URL);
								break;
							case AttachedFile::FILETYPE_BLOG_POST_IMAGE:
								$imageUrl = CommonHelper::generateFullUrl('Image','blogPost', array($recordId, $langId, "THUMB", 0, $afileId, false), CONF_WEBROOT_FRONT_URL);
								break;
							case AttachedFile::FILETYPE_CATEGORY_IMAGE:
								$imageUrl = CommonHelper::generateFullUrl('Category','image', array($recordId, $langId, "THUMB", 0, $afileId), CONF_WEBROOT_FRONT_URL);
								break;
							default:
								$imageUrl = CommonHelper::generateFullUrl('Category','banner', array($recordId, $langId, "THUMB", 0, $afileId), CONF_WEBROOT_FRONT_URL);
								break;
						} ?>
					<div class="row">
						<div class="col-md-4">
							<img src="<?php echo $imageUrl; ?>">
						</div>
						<div class="col-md-4">
							<div class="field-set">
								<div class="caption-wraper">
									<label class="field_label">
									<?php
										$fld = $frm->getField('image_title'.$afileId);
										echo $fld->getCaption();
									?></label>
								</div>
								<div class="field-wraper">
									<div class="field_cover">
									<?php echo $frm->getFieldHtml('image_title'.$afileId); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="field-set">
								<div class="caption-wraper">
									<label class="field_label">
									<?php
										$fld = $frm->getField('image_alt'.$afileId);
										echo $fld->getCaption();
									?></label>
								</div>
								<div class="field-wraper">
									<div class="field_cover">
									<?php echo $frm->getFieldHtml('image_alt'.$afileId); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }?>
				<?php } else {
					echo Labels::getLabel('LBL_No_Records_Found', $adminLangId);
				}?>
            </div>
        </div>
    </div>
</div>
<?php
echo $frm->getFieldHtml('module_type');
echo $frm->getFieldHtml('record_id');
?>
</form>
<?php echo $frm->getExternalJS(); ?>
