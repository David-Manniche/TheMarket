	<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<div class="tabs__content">		
	<div class="form__content ">
		<div class="row">
			<div class="col-md-12">
			<div class="container container--fluid">
			<div class="tabs--inline tabs--scroll clearfix">
				<ul>
					<li class="is-active"><a onclick="getShopCollectionGeneralForm();" href="javascript:void(0)"><?php echo Labels::getLabel('TXT_Basic', $siteLangId);?></a></li>
					<?php if(isset($scollection_id) && $scollection_id >0){
						$scollection_id=$scollection_id;
					}else{
					$scollection_id='';	
					}

					foreach($language as $lang_id => $langName){?>	
					<li class=""><a href="javascript:void(0)" <?php if($scollection_id>0) { ?> onClick="editShopCollectionLangForm(<?php echo $scollection_id ?>, <?php echo $lang_id;?>)" <?php } ?>>
					<?php echo $langName;?></a></li>
					<?php } ?>
					
					<li class=""><a <?php if($scollection_id>0) { ?> onclick="sellerCollectionProducts(<?php echo $scollection_id; ?>);" <?php } ?> href="javascript:void(0);"><?php echo Labels::getLabel('TXT_LINK', $siteLangId);?></a></li>
				</ul>
			</div>
		</div>
			<div class="form__subcontent">
				<?php 
				$colectionForm->setFormTagAttribute('class', 'form form--horizontal');
				$colectionForm->setFormTagAttribute('onsubmit', 'setupShopCollection(this); return(false);');
				$colectionForm->developerTags['colClassPrefix'] = 'col-lg-8 col-md-8 col-sm-';
				$colectionForm->developerTags['fld_default_col'] = 8; 
				$urlFld = $colectionForm->getField('urlrewrite_custom');
				$urlFld->setFieldTagAttribute('id',"urlrewrite_custom");
				$urlFld->setFieldTagAttribute('onkeyup',"getSlugUrl(this,this.value,'".$baseUrl."','post')");
				$urlFld->htmlAfterField = "<small class='text--small'>" . CommonHelper::generateFullUrl('Shops','Collection',array($shop_id)).'</small>';
				$IDFld = $colectionForm->getField('scollection_id');
				$IDFld->setFieldTagAttribute('id',"scollection_id");
				$identiFierFld = $colectionForm->getField('scollection_identifier');
				$identiFierFld->setFieldTagAttribute('onkeyup',"Slugify(this.value,'urlrewrite_custom','scollection_id')");
				echo $colectionForm->getFormHtml();
				?>
			</div>
		</div>
		</div>
	</div>
</div>