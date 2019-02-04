<?php defined('SYSTEM_INIT') or die('Invalid Usage'); 
	$keywordFld = $headerSrchFrm->getField('keyword');
	$submitFld = $headerSrchFrm->getField('btnSiteSrchSubmit');
	$submitFld->setFieldTagAttribute('class','search--btn submit--js');
	$keywordFld->setFieldTagAttribute('class','search--keyword search--keyword--js');
	$keywordFld->setFieldTagAttribute('placeholder',Labels::getLabel('LBL_What_are_you_looking_for...',$siteLangId));
	/* $keywordFld->setFieldTagAttribute('autofocus','autofocus'); */
	$keywordFld->setFieldTagAttribute('id','header_search_keyword');	
	$keywordFld->setFieldTagAttribute('onkeyup','animation(this)');
	/* CommonHelper::printArray($categoriesArr); die; */
	/* $selectFld = $headerSrchFrm->getField('category');
	$selectFld->setFieldTagAttribute('id','category--js');
	$selectFld->setFieldTagAttribute('onChange','setSelectedCatValue()'); */
?>

<div class="main-search">
	<a href="javascript:void(0)" class="toggle--search toggle--search-js"> <span class="icn"></span></a>
	<div class="form--search form--search-popup">
		<a id="close-search-popup-js" class="close-layer d-xl-none d-lg-none" href="javascript:void(0)"></a>
		<?php echo $headerSrchFrm->getFormTag(); ?>
			<div class="dropdown"> <span id="selected__value-js" class="select__value dropdown__trigger-js"> <?php echo Labels::getLabel('LBL_All',$siteLangId); ?></span>
				<div class="dropdown__target dropdown__target-js">
					<div class="dropdown__target-space"> <span class="expand-heading"><?php echo Labels::getLabel('LBL_Search_Items',$siteLangId); ?></span>
						<ul class="list-vertical list-vertical--tick">
							<?php foreach($categoriesArr as $catkey => $catval) { ?>
								<li><a id="category--js-<?php echo $catkey; ?>" href="javascript:void(0);" onclick="setSelectedCatValue('<?php echo $catkey; ?>')"><?php echo $catval; ?></a></li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
			<?php echo $headerSrchFrm->getFieldHTML('keyword'); ?>
			<?php echo $headerSrchFrm->getFieldHTML('category'); ?>
			<?php echo $headerSrchFrm->getFieldHTML('btnSiteSrchSubmit'); ?>
		</form>
		<?php echo $headerSrchFrm->getExternalJS(); ?>
	</div>
</div>
