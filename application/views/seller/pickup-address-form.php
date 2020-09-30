<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'pickupAddressFrm');
$frm->setFormTagAttribute('class', 'form form--horizontal');
$frm->setFormTagAttribute('onsubmit', 'setPickupAddress(this); return(false);');

$addrLabelFld = $frm->getField('addr_title');
$addrLabelFld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_E.g:_My_Office_Address', $siteLangId));

$countryFld = $frm->getField('addr_country_id');
$countryFld->setFieldTagAttribute('id', 'addr_country_id');
$countryFld->setFieldTagAttribute('onChange', 'getCountryStates(this.value,'.$stateId.',\'#addr_state_id\')');

$stateFld = $frm->getField('addr_state_id');
$stateFld->setFieldTagAttribute('id', 'addr_state_id');

$slotTypeFld = $frm->getField('slot_type');
$slotTypeFld->setOptionListTagAttribute('class','list-inline');
$slotTypeFld->developerTags['rdLabelAttributes'] = array('class' => 'radio');
$slotTypeFld->developerTags['rdHtmlAfterRadio'] = '<i class="input-helper"></i>';
$slotTypeFld->setFieldTagAttribute('onClick', 'displaySlotTimings(this);');

$fromAllFld = $frm->getField('tslot_from_all');
$fromAllFld->setFieldTagAttribute('onChange', 'validateTimeFields()');

$toAllFld = $frm->getField('tslot_to_all');
$toAllFld->setFieldTagAttribute('onChange', 'validateTimeFields()');

$cancelFld = $frm->getField('btn_cancel');
$cancelFld->setFieldTagAttribute('class', 'btn btn-outline-primary');
$cancelFld->developerTags['col'] = 2;
$cancelFld->developerTags['noCaptionTag'] = true;

$btnSubmit = $frm->getField('btn_submit');
$btnSubmit->setFieldTagAttribute('class', "btn btn-primary");
$btnSubmit->developerTags['col'] = 2;
$btnSubmit->developerTags['noCaptionTag'] = true;

$variables= array('language'=>$language,'siteLangId'=>$siteLangId,'shop_id'=>$shop_id,'action'=>$action);
$this->includeTemplate('seller/_partial/shop-navigation.php', $variables, false); ?>
<div class="cards">
     <div class="cards-header">
        <h5 class="cards-title"><?php echo Labels::getLabel('LBL_Shop_Pickup_Addresses', $siteLangId); ?></h5>
        <div class="btn-group">
            <a href="javascript:void(0)" onClick="pickupAddress()" class="btn btn-outline-primary btn-sm  btn-sm"><?php echo Labels::getLabel('LBL_Back', $siteLangId);?></a>
        </div>
    </div>
    
    <div class="cards-content">    
        <div class="row">
            <div class="col-md-12">
                <?php echo $frm->getFormTag(); ?>
                    <div class="row">
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                    </div>
                    <div class="row">
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                    $daysArr = TimeSlot::getDaysArr($siteLangId);
                    for($i = 0; $i< count($daysArr); $i++){
                        $dayFld = $frm->getField('tslot_day['.$i.']');
                        $dayFld->developerTags['cbLabelAttributes'] = array('class' => 'checkbox');
                        $dayFld->developerTags['cbHtmlAfterCheckbox'] = '<i class="input-helper"></i>';
                        $dayFld->setFieldTagAttribute('onChange', 'displayFields('.$i.', this)');

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
												<?php //echo $frm->getFieldHtml('btn_add_row['.$i.']');  ?>
													<button type="button" name="btn_add_row[<?php echo $i; ?>]" onClick= "addTimeSlotRow(<?php echo $i; ?>)" class="btn btn-primary js-slot-add-<?php echo $i; ?>"><i class="fas fa-plus"></i></button>
												</div>
											</div>
										</div>
										<?php }else{ ?>
										<div class="field-set">
											<div class="caption-wraper">
											   <label class="field_label"></label>
											</div>
											<div class="field-wraper">
												<div class="field_cover">
												<button class="btn btn-outline-primary" type="button" name="btn_remove_row"><i class="fas fa-minus"></i></button>
												</div>
											</div>
										</div>
										<?php } ?>
									</div>
								</div>
                    <?php  }
                        }else{
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
											<?php //echo $frm->getFieldHtml('btn_add_row['.$i.']'); ?>
											<button type="button" name="btn_add_row[<?php echo $i; ?>]" onClick= "addTimeSlotRow(<?php echo $i; ?>)" class="d-none btn btn-primary js-slot-add-<?php echo $i; ?>"><i class="fas fa-plus"></i></button>
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

<script language="javascript">
    $(document).ready(function() {
        getCountryStates($("#addr_country_id").val(), <?php echo ($stateId) ? $stateId : 0 ;?>, '#addr_state_id');
        
        addTimeSlotRow = function(day){
            var fromTimeHtml = $(".js-from_time_"+day).html();
            var toTimeHtml = $(".js-to_time_"+day).html();
            var html = "<div class='row js-added-rows-"+day+"'><div class='col-md-2'></div><div class='col-md-4 js-from_time_"+day+"'>"+fromTimeHtml+"</div><div class='col-md-4 js-to_time_"+day+"'>"+toTimeHtml+"</div><div class='col-md-2'><div class='field-set'><div class='caption-wraper'><label class='field_label'></label></div><div class='field-wraper'><div class='field_cover'><button class='btn btn-outline-primary' type='button' name='btn_remove_row'><i class='fas fa-minus'></i></button></div> </div></div></div></div>";
			
            $(".js-from_time_"+day).last().parent().after(html);
            $('.js-slot-from-'+day).last().val('');
            $('.js-slot-to-'+day).last().val('');
        }  

        displayFields = function(day, ele){
           if($(ele).prop("checked") == true){
                $(".js-slot-from-"+day).removeAttr('disabled');
                $(".js-slot-to-"+day).removeAttr('disabled');        
                displayAddRowField(day);
           }else{
                $(".js-slot-from-"+day).attr('disabled', 'true');
                $(".js-slot-to-"+day).attr('disabled', 'true');
                $(".js-slot-add-"+day).addClass('d-none');
                $(".js-added-rows-"+day).remove();
           }  
        }

        displayAddRowField = function(day){
            var from_time = $(".js-slot-from-"+day).children("option:selected").val();
            var to_time = $(".js-slot-to-"+day).children("option:selected").val();
			
			$(".js-slot-to-"+day+" option").removeClass('d-none');
			$(".js-slot-to-"+day+" option").each(function(){
				var toVal = $(this).val();
				if(toVal != '' && toVal <= from_time){
					$(this).addClass('d-none');
				}
			});
			
            if(to_time != '' && to_time <= from_time){
                $(".js-slot-to-"+day).val('').addClass('error');
                var to_time = $(".js-slot-to-"+day).children("option:selected").val();
            }else{
                $(".js-slot-to-"+day).removeClass('error');
            }

            if(from_time != ''  && to_time != ''){
                $(".js-slot-add-"+day).removeClass('d-none');
            }else{
                $(".js-slot-add-"+day).addClass('d-none');
            }        

        }

        displaySlotTimings = function(ele){
            var selectedVal = $(ele).val(); 
            if(selectedVal == 2){
                $('.js-slot-individual').addClass('d-none');
                $('.js-slot-all').removeClass('d-none');
            }else{
                $('.js-slot-all').addClass('d-none');
                $('.js-slot-individual').removeClass('d-none');
            }
        }

        validateTimeFields = function(){
            var from_time = $("[name='tslot_from_all']").children("option:selected").val();
            var to_time = $("[name='tslot_to_all']").children("option:selected").val();
            if(to_time != '' && to_time <= from_time){
                $("[name='tslot_to_all']").val('').addClass('error');
            }else{
                $("[name='tslot_to_all']").removeClass('error');
            }
        }
    });
    
    $(document).on("click", "[name='btn_remove_row']", function(){
		$(this).parentsUntil('.row').parent().remove();
    })

</script>
