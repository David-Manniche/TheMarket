<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<div class="row justify-content-between align-items-center">
    <div class="col-auto"><?php echo Labels::getLabel('LBL_New_Sub_User', $siteLangId); ?></div>
    <div class="col-auto">
        <div class="btn-group">
            <a class="btn btn-outline-primary btn--sm" title="<?php echo Labels::getLabel('LBL_Back', $siteLangId); ?>" onclick="searchUsers()" href="javascript:void(0)"><?php echo Labels::getLabel('LBL_Back', $siteLangId); ?></a>
        </div>
    </div>
</div>
<div class="cards-content">
    <div class="form__subcontent">
        <?php
        $frm->setFormTagAttribute('onsubmit', 'setup(this); return(false);');
        $frm->setFormTagAttribute('class', 'form form--horizontal');
        $frm->developerTags['colClassPrefix'] = 'col-lg-4 col-md-4 col-sm-';
        $frm->developerTags['fld_default_col'] = 4;
        echo $frm->getFormHtml();
        ?>
    </div>
</div>
