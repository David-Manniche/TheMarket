<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'web_form');
$frm->developerTags['colClassPrefix'] = 'col-lg-12 col-md-';
$frm->developerTags['fld_default_col'] = 12;
?>
<div class="tabs_nav_container">
<?php 
$variables = array('adminLangId'=>$adminLangId,'action'=>$action);
$this->includeTemplate('import-export/_partial/top-navigation.php',$variables,false); ?>
    <div class="tabs_panel_wrap">
        <?php echo $frm->getFormHtml();  ?>
    </div>
</div>