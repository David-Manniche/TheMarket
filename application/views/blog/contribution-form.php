<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
/* $this->includeTemplate('_partial/blogTopFeaturedCategories.php'); */
$frm->setFormTagAttribute('class', 'form');
/* $frm->setFormTagAttribute('onsubmit','setupContribution(this);return false;'); */
$frm->setFormTagAttribute('action', CommonHelper::generateUrl('Blog', 'setupContribution'));
$frm->developerTags['colClassPrefix'] = 'col-lg-12 col-md-12 col-sm-';
$frm->developerTags['fld_default_col'] = 12;
$fileFld = $frm->getField('file');
$fileFld->htmlBeforeField='<div class="filefield"><span class="filename"></span>';
$preferredDimensionsStr = '<label class="filelabel">'.Labels::getLabel('LBL_Browse_File', $siteLangId).'</label></div><small class="text--small">'.Labels::getLabel('MSG_Allowed_Extensions', $siteLangId).'</small>';
$fileFld->htmlAfterField = $preferredDimensionsStr;
if (FatApp::getConfig('CONF_RECAPTCHA_SITEKEY', FatUtility::VAR_STRING, '')!= '' && FatApp::getConfig('CONF_RECAPTCHA_SECRETKEY', FatUtility::VAR_STRING, '')!= '') {
    $captchaFld = $frm->getField('htmlNote');
    $captchaFld->htmlBeforeField = '<div class="field-set">
           <div class="caption-wraper"><label class="field_label"></label></div>
           <div class="field-wraper">
               <div class="field_cover">';
    $captchaFld->htmlAfterField = '</div></div></div>';
}
$isUserLogged = UserAuthentication::isUserLogged();
if ($isUserLogged) {
    $nameFld = $frm->getField(BlogContribution::DB_TBL_PREFIX.'author_first_name');
    $nameFld->setFieldTagAttribute('readonly', 'readonly');
}
?>
<div id="body" class="body">
   
   <div class="section section--pagebar">
      <div class="container container--fixed">
        <div class="row align-items-center justify-content-between">
          <div class="col-md-8 col-sm-8">
                <h1><?php echo Labels::getLabel('Lbl_Blog_Contribution', $siteLangId); ?></h1>
                
                
          </div>
          <div class="col-md-auto col-sm-auto"><a href="<?php echo $backPageUrl; ?>" class="btn btn--secondary"><?php echo Labels::getLabel('Lbl_Back', $siteLangId); ?></a></div>
        </div>
      </div>
    </div>
   
   
   
    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-8">
                    <div class="box box--gray box--radius box--border p-5">
                    <?php echo $frm->getFormHtml(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src='https://www.google.com/recaptcha/api.js'></script>
