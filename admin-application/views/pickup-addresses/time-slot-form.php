<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'web_form form_horizontal');
$frm->setFormTagAttribute('onsubmit', 'setUpTimeSlot(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 4;

for($i = 0; $i< $timeSlotsCount; $i++){
    $fromFLd = $frm->getField('tslot_from_time['.$i.']');
    $fromFLd->developerTags['col'] = 3;

    $toFLd = $frm->getField('tslot_to_time['.$i.']');
    $toFLd->setWrapperAttribute('class', 'js-to-time');
    $toFLd->developerTags['col'] = 3;
}
$addRowFld = $frm->getField('btn_add_row');
$addRowFld->setFieldTagAttribute('onClick', 'addTimeSlotRow();');
$addRowFld->setWrapperAttribute('class', 'text-right');
$addRowFld->developerTags['col'] = 8;
?>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Labels::getLabel('LBL_Time_Slot_Setup', $adminLangId); ?></h4>
    </div>
    <div class="sectionbody space">
        <div class="row">
            <div class="col-sm-12">
                <div class="tabs_nav_container responsive flat">
                    <div class="tabs_panel_wrap">
                        <div class="tabs_panel">
                            <?php echo $frm->getFormHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
<?php if($timeSlotsCount > 1) { ?>
$(document).ready(function(){  
     $( "#frm_fat_id_frmTimeSlot .js-to-time" ).not('.js-to-time:first').after('<div class="col-md-2"><button class="js-remove-slot">x</button></div>');
})
<?php } ?>
</script>