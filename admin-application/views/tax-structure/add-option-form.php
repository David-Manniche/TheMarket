<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'web_form');
$frm->setFormTagAttribute('onsubmit', 'optionSetup(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col']=6;
?>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Labels::getLabel('LBL_Tax_Option_Setup', $adminLangId); ?></h4>
        <?php
            $ul = new HtmlElement("ul", array("class"=>"actions actions--centered"));
            $li = $ul->appendElement("li", array('class'=>'droplink'));
            $li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Labels::getLabel('LBL_Edit', $adminLangId)), '<i class="ion-android-more-horizontal icon"></i>', true);
            $innerDiv=$li->appendElement('div', array('class'=>'dropwrap'));
            $innerUl=$innerDiv->appendElement('ul', array('class'=>'linksvertical'));
            $innerLiExport=$innerUl->appendElement('li');
            $innerLiExport->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Labels::getLabel('LBL_Back_to_Listing', $adminLangId),"onclick"=>"options(".$taxstrId.")"), Labels::getLabel('LBL_Back_to_Listing', $adminLangId), true);
            echo $ul->getHtml();
        ?>
    </div>
    <div id="optionListing"><?php echo $frm->getFormHtml(); ?></div>
</section>
