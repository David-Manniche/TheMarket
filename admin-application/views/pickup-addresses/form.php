<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'web_form  layout--' . $formLayout);
$frm->setFormTagAttribute('onsubmit', 'setup(this); return(false);');
//$frm->developerTags['colClassPrefix'] = 'col-md-';
//$frm->developerTags['fld_default_col'] = 6;

$langFld = $frm->getField('lang_id');
$langFld->setFieldTagAttribute('onChange', "addAddressForm(" . $addressId . ", this.value);");

$addrLabelFld = $frm->getField('addr_title');
$addrLabelFld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_E.g:_My_Office_Address', $langId));
        
$countryFld = $frm->getField('addr_country_id');
$countryFld->setFieldTagAttribute('id', 'addr_country_id');
$countryFld->setFieldTagAttribute('onChange', 'getCountryStates(this.value,'.$stateId.',\'#shop_state\','.$langId.')');

$stateFld = $frm->getField('addr_state_id');
$stateFld->setFieldTagAttribute('id', 'shop_state');

$slotTypeFld = $frm->getField('slot_type');
$slotTypeFld->setOptionListTagAttribute('class','list-inline');
$slotTypeFld->developerTags['cbLabelAttributes'] = array('class' => 'radio');
$slotTypeFld->developerTags['rdHtmlAfterRadio'] = '<i class="input-helper"></i>';
$slotTypeFld->setFieldTagAttribute('onClick', 'displaySlotTimings(this);');

$fromAllFld = $frm->getField('tslot_from_all');
$fromAllFld->setFieldTagAttribute('onChange', 'validateTimeFields()');

$toAllFld = $frm->getField('tslot_to_all');
$toAllFld->setFieldTagAttribute('onChange', 'validateTimeFields()');
?>

<div class="sectionbody space">
    <div class="row">
        <div class="col-sm-12">
            <div class="tabs_nav_container responsive flat">
                <div class="tabs_panel_wrap">
                    <div class="tabs_panel">
                        <?php echo $frm->getFormTag(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                           <?php $fld = $frm->getField('lang_id');
                                             echo $fld->getCaption();
                                           ?>
                                       </label>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('lang_id'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                       <?php $fld = $frm->getField('addr_title');
                                             echo $fld->getCaption();
                                       ?>
                                       </label>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('addr_title'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                           <?php $fld = $frm->getField('addr_name');
                                             echo $fld->getCaption();
                                           ?>
                                       </label>
                                       <span class="spn_must_field">*</span>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('addr_name'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                       <?php $fld = $frm->getField('addr_address1');
                                             echo $fld->getCaption();
                                       ?>
                                       </label>
                                       <span class="spn_must_field">*</span>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('addr_address1'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                           <?php $fld = $frm->getField('addr_address2');
                                             echo $fld->getCaption();
                                           ?>
                                       </label>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('addr_address2'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                       <?php $fld = $frm->getField('addr_country_id');
                                             echo $fld->getCaption();
                                       ?>
                                       </label>
                                       <span class="spn_must_field">*</span>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('addr_country_id'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                           <?php $fld = $frm->getField('addr_state_id');
                                             echo $fld->getCaption();
                                           ?>
                                       </label>
                                       <span class="spn_must_field">*</span>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('addr_state_id'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                       <?php $fld = $frm->getField('addr_city');
                                             echo $fld->getCaption();
                                       ?>
                                       </label>
                                        <span class="spn_must_field">*</span>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('addr_city'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                           <?php $fld = $frm->getField('addr_zip');
                                             echo $fld->getCaption();
                                           ?>
                                       </label>
                                       <span class="spn_must_field">*</span>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('addr_zip'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                       <?php $fld = $frm->getField('addr_phone');
                                             echo $fld->getCaption();
                                       ?>
                                       </label>
                                        <span class="spn_must_field">*</span>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('addr_phone'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                           <?php $fld = $frm->getField('slot_type');
                                             echo $fld->getCaption();
                                           ?>
                                       </label>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('slot_type'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="js-slot-individual">
                        <?php 
                        $daysArr = TimeSlot::getDaysArr($langId);
                        for($i = 0; $i< count($daysArr); $i++){
                            $dayFld = $frm->getField('tslot_day['.$i.']');
                            $dayFld->developerTags['cbLabelAttributes'] = array('class' => 'checkbox');
                            $dayFld->developerTags['cbHtmlAfterCheckbox'] = '<i class="input-helper"></i>';
                            $dayFld->setFieldTagAttribute('onChange', 'displayFields('.$i.', this)');

                            $addRowFld = $frm->getField('btn_add_row['.$i.']');
                            $addRowFld->setFieldTagAttribute('onClick', 'addTimeSlotRow('.$i.')');
                            $addRowFld->setFieldTagAttribute('class', 'js-slot-add-'.$i);
       
                            if(!empty($slotData) && isset($slotData['tslot_day'][$i])){   
                                $dayFld->setFieldTagAttribute('checked', 'true');
                                foreach($slotData['tslot_from_time'][$i] as $key=>$time){   
                                    $fromTime = date('H:i', strtotime($time));
                                    $toTime = date('H:i', strtotime($slotData['tslot_to_time'][$i][$key]));
                                    
                                    $fromFld = $frm->getField('tslot_from_time['.$i.'][]');
                                    $fromFld->setFieldTagAttribute('class', 'js-slot-from-'.$i.' fromTime-js');
                                    $fromFld->setFieldTagAttribute('onChange', 'displayAddRowField('.$i.')');
                                    $fromFld->value = $fromTime;

                                    $toFld = $frm->getField('tslot_to_time['.$i.'][]');
                                    $toFld->setFieldTagAttribute('class', 'js-slot-to-'.$i);
                                    $toFld->setFieldTagAttribute('onChange', 'displayAddRowField('.$i.')');
                                    $toFld->value = $toTime;     
                        ?>
                        <div class="row <?php echo ($key > 0) ? 'js-added-rows-'.$i : ''?>">
                            <div class="col-md-2">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                       </label>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php 
                                        if($key == 0){
                                            echo $frm->getFieldHtml('tslot_day['.$i.']'); 
                                        }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 js-from_time_<?php echo $i;?>">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                        <?php $fld = $frm->getField('tslot_from_time['.$i.'][]');
                                          echo $fld->getCaption();
                                        ?>
                                       </label>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('tslot_from_time['.$i.'][]'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 js-to_time_<?php echo $i;?>">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                        <?php $fld = $frm->getField('tslot_to_time['.$i.'][]');
                                          echo $fld->getCaption();
                                        ?>
                                       </label>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('tslot_to_time['.$i.'][]'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <?php if($key == 0){ ?>
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                       </label>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php  echo $frm->getFieldHtml('btn_add_row['.$i.']');  ?>
                                        </div>
                                    </div>
                                </div>
                                <?php }else{ ?>
                                <input class='mt-4' type='button' name='btn_remove_row' value='x'>
                                <?php } ?>
                            </div>
                        </div>
                        <?php  }
                            }else{
                                $addRowFld->setFieldTagAttribute('class', 'd-none js-slot-add-'.$i);
                                
                                $fromFld = $frm->getField('tslot_from_time['.$i.'][]');
                                $fromFld->setFieldTagAttribute('disabled', 'true');
                                $fromFld->setFieldTagAttribute('class', 'js-slot-from-'.$i.' fromTime-js');
                                $fromFld->setFieldTagAttribute('onChange', 'displayAddRowField('.$i.')');

                                $toFld = $frm->getField('tslot_to_time['.$i.'][]');
                                $toFld->setFieldTagAttribute('disabled', 'true');
                                $toFld->setFieldTagAttribute('class', 'js-slot-to-'.$i);
                                $toFld->setFieldTagAttribute('onChange', 'displayAddRowField('.$i.')');
                        ?>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                       </label>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('tslot_day['.$i.']'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 js-from_time_<?php echo $i;?>">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                        <?php $fld = $frm->getField('tslot_from_time['.$i.'][]');
                                          echo $fld->getCaption();
                                        ?>
                                       </label>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('tslot_from_time['.$i.'][]'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 js-to_time_<?php echo $i;?>">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                        <?php $fld = $frm->getField('tslot_to_time['.$i.'][]');
                                          echo $fld->getCaption();
                                        ?>
                                       </label>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('tslot_to_time['.$i.'][]'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                       </label>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('btn_add_row['.$i.']'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php   
                            }
                        }
                        ?>
                        </div>
                        <div class="row d-none js-slot-all">
                            <div class="col-md-6">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                           <?php $fld = $frm->getField('tslot_from_all');
                                             echo $fld->getCaption();
                                           ?>
                                       </label>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('tslot_from_all'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                       <?php $fld = $frm->getField('tslot_to_all');
                                             echo $fld->getCaption();
                                       ?>
                                       </label>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php echo $frm->getFieldHtml('tslot_to_all'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="field-set">
                                    <div class="caption-wraper">
                                       <label class="field_label">
                                       </label>
                                    </div>
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                        <?php 
                                        echo $frm->getFieldHtml('addr_id'); 
                                        echo $frm->getFieldHtml('btn_submit'); 
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                        <?php echo $frm->getExternalJS(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script language="javascript">
    <?php if($addressId > 0) {?>
        $(document).ready(function() {
            getCountryStates($("#addr_country_id").val(), <?php echo ($stateId) ? $stateId : 0 ;?>, '#shop_state', <?php echo $langId; ?>);
        });
    <?php } ?>
</script>