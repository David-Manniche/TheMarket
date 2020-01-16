<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$frm->setFormTagAttribute('class', 'web_form');
$frm->setFormTagAttribute( 'onSubmit', 'uploadZip(); return false;' );
?>
<div class="tabs_nav_container">
<?php 
$variables = array('adminLangId'=>$adminLangId,'action'=>$action);
$this->includeTemplate('import-export/_partial/top-navigation.php',$variables,false); ?>
    <div class="tabs_panel_wrap">
        <?php echo $frm->getFormHtml();  ?>
        <div id="listing"></div>
    </div>
</div>
