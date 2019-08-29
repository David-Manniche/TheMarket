<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
    $frmSearch->setFormTagAttribute('class', 'web_form last_td_nowrap');
    $frmSearch->setFormTagAttribute('onsubmit', 'searchVolumeDiscountProducts(this); return(false);');
    $frmSearch->developerTags['colClassPrefix'] = 'col-md-';
    $frmSearch->developerTags['fld_default_col'] = 4;
    $fld_active = $frmSearch->getField('active');

$class = '';
if (0 < $selProd_id) {
    $class = 'hide';
    $keywordFld = $frmSearch->getField('keyword');
    $keywordFld->setFieldTagAttribute('readonly', 'readonly');
}
    $submitBtnFld = $frmSearch->getField('btn_submit');
    $submitBtnFld->setFieldTagAttribute('class', $class);

    $cancelBtnFld = $frmSearch->getField('btn_clear');
    $cancelBtnFld->setFieldTagAttribute('onclick', 'clearSearch('.$selProd_id.');');

    $prodName = $addVolDiscountFrm->getField('product_name');
    $prodName->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Select_Product', $adminLangId));

    $minQty = $addVolDiscountFrm->getField('voldiscount_min_qty');
    $minQty->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Add_Minimum_Quantity', $adminLangId));

    $disPerc = $addVolDiscountFrm->getField('voldiscount_percentage');
    $disPerc->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Add_Discount_Percentage', $adminLangId));

    $addVolDiscountFrm->setFormTagAttribute('class', 'web_form');
    $addVolDiscountFrm->setFormTagAttribute('id', 'frmAddVolumeDiscount');
    $addVolDiscountFrm->setFormTagAttribute('name', 'frmAddVolumeDiscount');
    $addVolDiscountFrm->setFormTagAttribute('onsubmit', 'updateVolumeDiscount(this, '.$selProd_id.'); return(false);');

    $addVolDiscountFrm->addHiddenField('', 'lastRow', 0);
    $addVolDiscountFrm->addHiddenField('', 'addMultiple', 0);
?>
<div class='page'>
    <div class='container container-fluid'>
        <div class="row">
            <div class="col-lg-12 col-md-12 space">
                <div class="page__title">
                    <div class="row">
                        <div class="col--first col-lg-6">
                            <span class="page__icon"><i class="ion-android-star"></i></span>
                            <h5><?php echo Labels::getLabel('LBL_Manage_Volume_Discount', $adminLangId); ?> </h5>
                            <?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
                        </div>
                    </div>
                </div>
                <section class="section searchform_filter">
                    <div class="sectionhead">
                        <h4> <?php echo Labels::getLabel('LBL_Search...', $adminLangId); ?></h4>
                    </div>
                    <div class="sectionbody space togglewrap" style="display:none;">
                        <?php echo $frmSearch->getFormHtml(); ?>
                    </div>
                </section>
                <!--<div class="col-sm-12">-->
                <section class="section">
                    <div class="sectionhead">
                        <h4><?php echo Labels::getLabel('LBL_Seller_Products_List', $adminLangId); ?> </h4>
                        <?php
                        if ($canEdit) {
                            $ul = new HtmlElement("ul", array("class"=>"actions actions--centered"));
                            $li = $ul->appendElement("li", array('class'=>'droplink'));
                            $innerDiv=$li->appendElement('div', array('class'=>'dropwrap'));
                            $li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Labels::getLabel('LBL_Edit', $adminLangId)), '<i class="ion-android-more-horizontal icon"></i>', true);
                            $innerUl=$innerDiv->appendElement('ul', array('class'=>'linksvertical'));

                            $innerLi=$innerUl->appendElement('li');
                            $innerLi->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Labels::getLabel('LBL_Remove_Volume_Discount', $adminLangId),"onclick"=>"deleteVolumeDiscountRows()"), Labels::getLabel('LBL_Remove_Volume_Discount', $adminLangId), true);
                        }
                            echo $ul->getHtml();
                        ?>
                    </div>
                    <div class="sectionbody">
                        <?php
                        $class = !empty($dataToUpdate) && 0 < count($dataToUpdate) ? 'defaultForm hide' : '';
                        $this->includeTemplate('seller-products/add-volume-discount-form.php', array('addVolDiscountFrm'=>$addVolDiscountFrm, 'class' => $class), false);
                        foreach ($dataToUpdate as $key => $value) {
                            $cloneFrm = clone $addVolDiscountFrm;
                            if ($value === end($dataToUpdate) && 1 > $selProd_id) {
                                $value['lastRow'] = 1;
                            }
                            $value['addMultiple'] = 1;

                            $cloneFrm->fill($value);
                            $cloneFrm->setFormTagAttribute('id', 'frmAddVolumeDiscount-'.$key);
                            $cloneFrm->setFormTagAttribute('name', 'frmAddVolumeDiscount-'.$key);
                            $productName = $cloneFrm->getField('product_name');
                            $productName->setFieldTagAttribute('readonly', 'readonly');

                            // CommonHelper::printArray($cloneFrm, true);
                            $this->includeTemplate('seller-products/add-volume-discount-form.php', array('addVolDiscountFrm'=>$cloneFrm, 'class' => ''), false);
                        }
                        ?>
                        <div class="tablewrap" >
                            <div id="listing"> <?php echo Labels::getLabel('LBL_Processing...', $adminLangId); ?></div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
