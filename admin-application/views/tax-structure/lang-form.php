<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$langFrm->setFormTagAttribute('class', 'web_form form_horizontal layout--' . $formLayout);
$langFrm->setFormTagAttribute('onsubmit', 'setUpLang(this); return(false);');
$langFrm->developerTags['colClassPrefix'] = 'col-md-';
$langFrm->developerTags['fld_default_col'] = 12;
?>

<section class="section">
	<div class="sectionhead">
		<h4><?php echo Labels::getLabel('LBL_TAX_STRUCTURE_SETUP', $adminLangId); ?></h4>
	</div>
	<div class="sectionbody space">      
		<div class="tabs_nav_container responsive flat">
			<ul class="tabs_nav">
				<li><a href="javascript:void(0);" onclick="structureForm(<?php echo $taxStrId ?>);"><?php echo Labels::getLabel('LBL_General',$adminLangId); ?></a></li>
				<?php 
				if ($taxStrId > 0) {
					foreach($languages as $langId => $langName){?>
						<li><a class="<?php echo ($taxStrLangId == $langId)?'active':''?>" href="javascript:void(0);" onclick="addLangForm(<?php echo $taxStrId ?>, <?php echo $langId;?>);"><?php echo $langName;?></a></li>
					<?php } ?>
                    <?php if($type != TaxStructure::TYPE_SINGLE) {?>
                    <li><a href="javascript:void(0);" onclick="options(<?php echo $taxStrId ?>);"><?php echo Labels::getLabel('LBL_Tax_Options',$adminLangId); ?></a></li>
                    <?php }
                    }
				?>
			</ul>
			<div class="tabs_panel_wrap">
				<div class="tabs_panel">
					<?php echo $langFrm->getFormHtml(); ?>
				</div>
			</div>						
		</div>
	</div>						
</section>