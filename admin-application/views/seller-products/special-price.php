<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
    $frmSearch->setFormTagAttribute('class', 'web_form last_td_nowrap');
    $frmSearch->setFormTagAttribute('onsubmit', 'searchSpecialPriceProducts(this); return(false);');
    $frmSearch->developerTags['colClassPrefix'] = 'col-md-';
    $frmSearch->developerTags['fld_default_col'] = 4;
    $fld_active = $frmSearch->getField('active');

    $class = (0 < $selProd_id) ? 'hidden' : '';
    $submitBtnFld = $frmSearch->getField('btn_submit');
    $submitBtnFld->setFieldTagAttribute('class', $class);

    $cancelBtnFld = $frmSearch->getField('btn_clear');
    $cancelBtnFld->setFieldTagAttribute('class', $class);
    $cancelBtnFld->setFieldTagAttribute('onclick', 'clearSearch('.$selProd_id.');');

    $prodName = $addSpecialPriceFrm->getField('product_name');
    $prodName->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Select_Product', $adminLangId));

    $startDate = $addSpecialPriceFrm->getField('splprice_start_date');
    $startDate->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Price_Start_Date', $adminLangId));

    $endDate = $addSpecialPriceFrm->getField('splprice_end_date');
    $endDate->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Price_End_Date', $adminLangId));

    $splPrice = $addSpecialPriceFrm->getField('splprice_price');
    $splPrice->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Special_Price', $adminLangId));

    $addSpecialPriceFrm->setFormTagAttribute('class', 'web_form');
    $addSpecialPriceFrm->setFormTagAttribute('id', 'frmAddSpecialPrice');
    $addSpecialPriceFrm->setFormTagAttribute('name', 'frmAddSpecialPrice');
    $addSpecialPriceFrm->setFormTagAttribute('onsubmit', 'updateSpecialPrice(this, '.$selProd_id.'); return(false);');

    $addSpecialPriceFrm->addHiddenField('', 'lastRow', 0);
    $addSpecialPriceFrm->addHiddenField('', 'addMultiple', 0);
?>
<div class='page'>
    <div class='container container-fluid'>
        <div class="row">
            <div class="col-lg-12 col-md-12 space">
                <div class="page__title">
                    <div class="row">
                        <div class="col--first col-lg-6">
                            <span class="page__icon"><i class="ion-android-star"></i></span>
                            <h5><?php echo Labels::getLabel('LBL_Manage_Special_Price', $adminLangId); ?> </h5>
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
                            $innerLi->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Labels::getLabel('LBL_Delete_Special_Price', $adminLangId),"onclick"=>"deleteSpecialPriceRows()"), Labels::getLabel('LBL_Delete_Special_Price', $adminLangId), true);
                            echo $ul->getHtml();
                        }
                        ?>
                    </div>
                    <div class="sectionbody">
                        <?php
                        $class = !empty($dataToUpdate) && 0 < count($dataToUpdate) ? 'defaultForm hidden' : '';
                        $this->includeTemplate('seller-products/add-special-price-form.php', array('addSpecialPriceFrm'=>$addSpecialPriceFrm, 'class' => $class), false);
                        foreach ($dataToUpdate as $key => $value) {
                            $cloneFrm = clone $addSpecialPriceFrm;
                            if ($value === end($dataToUpdate) && 1 > $selProd_id) {
                                $value['lastRow'] = 1;
                            }
                            $value['addMultiple'] = 1;

                            $cloneFrm->fill($value);
                            $cloneFrm->setFormTagAttribute('id', 'frmAddSpecialPrice-'.$key);
                            $cloneFrm->setFormTagAttribute('name', 'frmAddSpecialPrice-'.$key);
                            $productName = $cloneFrm->getField('product_name');
                            $productName->setFieldTagAttribute('readonly', 'readonly');

                            // CommonHelper::printArray($cloneFrm, true);
                            $this->includeTemplate('seller-products/add-special-price-form.php', array('addSpecialPriceFrm'=>$cloneFrm, 'class' => ''), false);
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
<!--</div></div></div>-->
