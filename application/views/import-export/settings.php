<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('onsubmit', 'updateSettings(this); return(false);');
$frm->setFormTagAttribute('class','form form--horizontal');

$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12; 	
	
$variables = array('siteLangId'=>$siteLangId,'action'=>$action);
$this->includeTemplate('import-export/_partial/top-navigation.php',$variables,false); ?>
<div class="tabs__content">                                               
	<div class="form__content">		
        <div class="row">
			<div class="col-md-8" id="settingFormBlock">
				<?php echo $frm->getFormHtml(); ?>
			</div>
        </div>
	</div>
</div>