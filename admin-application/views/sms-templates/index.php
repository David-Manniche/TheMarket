<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<div class='page'>
    <div class='container container-fluid'>
        <div class="row">
            <div class="col-lg-12 col-md-12 space">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="page__title">
                            <div class="row">
                                <div class="col--first col-lg-6">
                                    <span class="page__icon">
                                        <i class="ion-android-star"></i></span>
                                    <h5><?php echo Labels::getLabel('LBL_MANAGE_SMS_TEMPLATES', $adminLangId); ?>
                                    </h5>
                                    <?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
                                </div>
                            </div>
                        </div>
                        <section class="section searchform_filter">
                            <div class="sectionhead">
                                <h4> <?php echo Labels::getLabel('LBL_Search...', $adminLangId); ?>
                                </h4>
                            </div>
                            <div class="sectionbody space togglewrap" style="display:none;">
                                <?php
                                    $frmSearch->setFormTagAttribute('onsubmit', 'searchStpls(this); return(false);');
                                    $frmSearch->setFormTagAttribute('id', 'frmStplSearch');
                                    $frmSearch->setFormTagAttribute('class', 'web_form');
                                    $frmSearch->developerTags['colClassPrefix'] = 'col-md-';
                                    $frmSearch->developerTags['fld_default_col'] = 6;

                                    $btn = $frmSearch->getField('btn_clear');
                                    $btn->setFieldTagAttribute('onClick', 'clearSearch()');
                                    echo  $frmSearch->getFormHtml();
                                ?>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="row equal-height">
                    <div class="col-md-<?php echo ($canEdit) ? 6 : 12; ?>">
                        <section class="section">
                            <div class="sectionhead">
                                <h4><?php echo Labels::getLabel('LBL_SMS_TEMPLATE_LISTS', $adminLangId); ?></h4>
                                <?php if ($canEdit) {
                                    $ul = new HtmlElement("ul", array("class" => "actions actions--centered"));
                                    $li = $ul->appendElement("li", array('class' => 'droplink'));

                                    $li->appendElement('a', array('href' => 'javascript:void(0)', 'class' => 'button small green', 'title' => Labels::getLabel('LBL_Edit', $adminLangId)), '<i class="ion-android-more-horizontal icon"></i>', true);
                                    $innerDiv = $li->appendElement('div', array('class' => 'dropwrap'));
                                    $innerUl = $innerDiv->appendElement('ul', array('class' => 'linksvertical'));

                                    $innerLi = $innerUl->appendElement('li');
                                    $innerLi->appendElement('a', array('href' => 'javascript:void(0)','class' => 'button small green', 'title' => Labels::getLabel('LBL_Activate', $adminLangId),"onclick" => "toggleBulkStatues(1)"), Labels::getLabel('LBL_Activate', $adminLangId), true);

                                    $innerLi = $innerUl->appendElement('li');
                                    $innerLi->appendElement('a', array('href' => 'javascript:void(0)','class' => 'button small green', 'title' => Labels::getLabel('LBL_Deactivate', $adminLangId), "onclick" => "toggleBulkStatues(0)"), Labels::getLabel('LBL_Deactivate', $adminLangId), true);

                                    echo $ul->getHtml();
                                } ?>
                            </div>
                            <div class="sectionbody">
                                <div class="tablewrap">
                                    <div id="listing"> <?php echo Labels::getLabel('LBL_Processing...', $adminLangId); ?>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <?php if ($canEdit) { ?>
                        <div class="col-md-6">
                            <div id="templateDetail">
                                <section class="section">
                                    <div class="sectionbody space"></div>
                                </section>
                            <div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>