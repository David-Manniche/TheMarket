	<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="tabs__content">		
	<div class="form__content ">
		<div class="row">
			<div class="col-md-12">
				<div class="container container--fluid">
					<div class="tabs--inline tabs--scroll clearfix">
						<ul>
							<li ><a onclick="getShopCollectionGeneralForm();" href="javascript:void(0)"><?php echo Labels::getLabel('TXT_Basic', $siteLangId);?></a></li>
							<?php 					
							foreach($language as $lang_id => $langName){?>	
							<li class="<?php echo ($langId == $lang_id)?'is-active':''?>"><a href="javascript:void(0)" <?php if($scollection_id>0) { ?> onClick="editShopCollectionLangForm(<?php echo $scollection_id ?>, <?php echo $lang_id;?>)" <?php } ?>>
							<?php echo $langName;?></a></li>
							<?php } ?>
							<li class=""><a <?php if($scollection_id>0) { ?> onclick="sellerCollectionProducts(<?php echo $scollection_id ?>)" <?php } ?> href="javascript:void(0);"><?php echo Labels::getLabel('TXT_LINK', $siteLangId);?></a></li>
						</ul>
					</div>
				</div>

				<div class="form__subcontent">
					<?php 
					$shopColLangFrm->setFormTagAttribute('class','form form--horizontal layout--'.$formLayout);
					$shopColLangFrm->setFormTagAttribute('onsubmit', 'setupShopCollectionlangForm(this); return(false);');
					$shopColLangFrm->developerTags['colClassPrefix'] = 'col-lg-8 col-md-8 col-sm-';
					$shopColLangFrm->developerTags['fld_default_col'] = 8; 
					echo $shopColLangFrm->getFormHtml();
					?>
				</div>
			</div>
		</div>
	</div>
</div>