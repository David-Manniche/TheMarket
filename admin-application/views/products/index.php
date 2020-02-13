<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
    $frmSearch->setFormTagAttribute('class', 'web_form last_td_nowrap');
    $frmSearch->setFormTagAttribute('onsubmit', 'searchProducts(this); return(false);');
    $frmSearch->developerTags['colClassPrefix'] = 'col-md-';
    $frmSearch->developerTags['fld_default_col'] = 4;
?>
<div class='page'>
    <div class='container container-fluid'>
        <div class="row">
            <div class="col-lg-12 col-md-12 space">
                <div class="page__title">
                    <div class="row">
                        <div class="col--first col-lg-6">
                            <span class="page__icon"><i class="ion-android-star"></i></span>
                            <h5><?php echo Labels::getLabel('LBL_Manage_Catalog_Products', $adminLangId); ?> </h5>
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
                        <h4><?php echo Labels::getLabel('LBL_Catalog_Products', $adminLangId); ?> </h4>                               
                        <?php
                            if ($canEdit) {
                                $div = new HtmlElement("div", array("class"=>"section__toolbar"));
                                $div->appendElement('a', array('href'=>'javascript:void(0)','class'=>'btn-clean btn-sm btn-icon btn-secondary toolbar-btn-js d-none','title'=>Labels::getLabel('LBL_Publish', $adminLangId),"onclick"=>"toggleBulkStatues(1)"), '<i class="fas fa-plus"></i>', true);
                                
                                $div->appendElement('a', array('href'=>'javascript:void(0)','class'=>'btn-clean btn-sm btn-icon btn-secondary toolbar-btn-js d-none','title'=>Labels::getLabel('LBL_Unpublish', $adminLangId),"onclick"=>"toggleBulkStatues(0)"), '<i class="fas fa-plus"></i>', true);
                                
                                $div->appendElement('a', array('href'=>'javascript:void(0)','class'=>'btn-clean btn-sm btn-icon btn-secondary toolbar-btn-js d-none','title'=>Labels::getLabel('LBL_Delete', $adminLangId),"onclick"=>"deleteSelected()"), '<i class="fas fa-trash"></i>', true);
                                
                                $div->appendElement('a', array('href'=> commonHelper::generateUrl('Products', 'form'),'class'=>'btn-clean btn-sm btn-icon btn-secondary','title'=>Labels::getLabel('LBL_Add_New_Product', $adminLangId),), '<i class="fas fa-plus"></i>', true);
                                
                                echo $div->getHtml(); 
                            }

                           /* $ul = new HtmlElement("ul", array("class"=>"actions actions--centered"));
                            $li = $ul->appendElement("li", array('class'=>'droplink'));

                            $li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Labels::getLabel('LBL_Edit', $adminLangId)), '<i class="ion-android-more-horizontal icon"></i>', true);
                            $innerDiv=$li->appendElement('div', array('class'=>'dropwrap'));
                            $innerUl=$innerDiv->appendElement('ul', array('class'=>'linksvertical'));

                            $productType=Importexport::TYPE_PRODUCTS;
                        if ($canEdit) {
                            $innerLi=$innerUl->appendElement('li');
                            $innerLi->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Labels::getLabel('LBL_Publish', $adminLangId),"onclick"=>"toggleBulkStatues(1)"), Labels::getLabel('LBL_Publish', $adminLangId), true);

                            $innerLi=$innerUl->appendElement('li');
                            $innerLi->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Labels::getLabel('LBL_Unpublish', $adminLangId),"onclick"=>"toggleBulkStatues(0)"), Labels::getLabel('LBL_Unpublish', $adminLangId), true);

                            $innerLi=$innerUl->appendElement('li');
                            $innerLi->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Labels::getLabel('LBL_Delete', $adminLangId),"onclick"=>"deleteSelected()"), Labels::getLabel('LBL_Delete', $adminLangId), true);

                            $innerLi=$innerUl->appendElement('li');
                            $innerLi->appendElement('a', array('href'=> commonHelper::generateUrl('Products', 'form'),'class'=>'button small green redirect--js','title'=>Labels::getLabel('LBL_Add_New_Product', $adminLangId),), Labels::getLabel('LBL_Add_New_Product', $adminLangId), true);
                        }
                        echo $ul->getHtml(); */
                        ?>
                    </div>
                    <div class="sectionbody">
                        <div class="tablewrap" >
                            <div id="listing"> <?php echo Labels::getLabel('LBL_Processing...', $adminLangId); ?></div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<script>
    $("document").ready(function(){
        var PRODUCT_TYPE_PHYSICAL = <?php echo Product::PRODUCT_TYPE_PHYSICAL; ?>;
        var PRODUCT_TYPE_DIGITAL = <?php echo Product::PRODUCT_TYPE_DIGITAL; ?>;
    });
</script>
