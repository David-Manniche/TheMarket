<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$frmCategoryReq->setFormTagAttribute('class', 'form form--horizontal');
$frmCategoryReq->developerTags['colClassPrefix'] = 'col-lg-12 col-md-12 col-sm-';
$frmCategoryReq->developerTags['fld_default_col'] = 12;
$frmCategoryReq->setFormTagAttribute('onsubmit', 'setupCategoryReq(this); return(false);');
$identifierFld = $frmCategoryReq->getField(CategoryRequest::DB_TBL_PREFIX.'id');
$identifierFld->setFieldTagAttribute('id',CategoryRequest::DB_TBL_PREFIX.'id');
?>

<div class="box__head">
  <h4><?php echo Labels::getLabel('LBL_Request_New_category',$langId); ?></h4>
</div>
<div class="box__body">
  <div class="tabs">
    <ul>
      <li class="is-active" ><a href="javascript:void(0)" onclick="addCategoryReqForm(<?php echo $categoryReqId; ?>);"><?php echo Labels::getLabel('LBL_Basic', $siteLangId);?></a></li>
        <li class="<?php echo (0 == $categoryReqId) ? 'fat-inactive' : ''; ?>">
            <a href="javascript:void(0);" <?php echo (0 < $categoryReqId) ? "onclick='addCategoryReqLangForm(" . $categoryReqId . "," . FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1) . ");'" : ""; ?>>
                <?php echo Labels::getLabel('LBL_Language_Data', $siteLangId); ?>
            </a>
        </li>
    </ul>
  </div>
 
    <?php
		echo $frmCategoryReq->getFormHtml();
	?>
  
</div>
