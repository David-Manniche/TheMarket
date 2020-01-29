<section class="section">
    <div class="sectionhead">
        <h4><?php echo Labels::getLabel('LBL_TAX_STRUCTURE_SETUP', $adminLangId); ?></h4>
    </div>
    <div class="sectionbody space">
        <div class="tabs_nav_container responsive flat">
            <ul class="tabs_nav">
                <li><a href="javascript:void(0);" onclick="structureForm(<?php echo $taxStrId ?>);"><?php echo Labels::getLabel('LBL_General', $adminLangId); ?></a></li>
                <?php
                if ($taxStrId > 0) {
                    foreach ($languages as $langId => $langName) {?>
                        <li><a href="javascript:void(0);" onclick="addLangForm(<?php echo $taxStrId ?>, <?php echo $langId;?>);"><?php echo $langName;?></a></li>
                    <?php } ?>
                    <li><a class="active" href="javascript:void(0);" onclick="options(<?php echo $taxStrId ?>);"><?php echo Labels::getLabel('LBL_Tax_Options', $adminLangId); ?></a></li>
                    <?php
                }
                ?>
            </ul>
            <div class="tabs_panel_wrap">
                <div class="tabs_panel" id="optionForm">
                    <section class="section">
                        <div class="sectionhead">
                            <h4><?php echo Labels::getLabel('LBL_Tax_Options', $adminLangId); ?></h4>
                            <?php
                                $ul = new HtmlElement("ul", array("class"=>"actions actions--centered"));
                                $li = $ul->appendElement("li", array('class'=>'droplink'));
                                $li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Labels::getLabel('LBL_Edit', $adminLangId)), '<i class="ion-android-more-horizontal icon"></i>', true);
                                $innerDiv=$li->appendElement('div', array('class'=>'dropwrap'));
                                $innerUl=$innerDiv->appendElement('ul', array('class'=>'linksvertical'));
                                $innerLiAdd=$innerUl->appendElement('li');
                                $innerLiAdd->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Labels::getLabel('LBL_ADD_NEW', $adminLangId),"onclick"=>"addOptionForm(".$taxStrId.",0)"), Labels::getLabel('LBL_ADD_NEW', $adminLangId), true);
                                echo $ul->getHtml();
                            ?>
                        </div>
                        <div id="optionListing"><?php echo Labels::getLabel('LBL_Processing', $adminLangId); ?></div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</section>
