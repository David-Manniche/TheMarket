<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$frm->setFormTagAttribute('class', 'web_form');
$frm->setFormTagAttribute( 'onSubmit', 'uploadZip(); return false;' );
$frm->developerTags['colClassPrefix'] = 'col-md-';
$fldBulkImages  = $frm->getField('bulk_images');
$fldBulkImages->developerTags['col'] = 9;
$fldBulkImages->setRequiredStarPosition(Form::FORM_REQUIRED_STAR_POSITION_NONE);
?>
<div class="tabs_nav_container">
<?php 
$variables = array('adminLangId'=>$adminLangId,'action'=>$action);
$this->includeTemplate('import-export/_partial/top-navigation.php',$variables,false); ?>
    <div class="tabs_panel_wrap p-0">
    <div class="row justify-content-center">
        <div class="col-md-6">
    <div class="p-4"><?php echo $frm->getFormHtml();  ?></div></div>
    </div>
      
        <div class="divider"></div>
        <div id="listing"></div>
    </div>
</div>
