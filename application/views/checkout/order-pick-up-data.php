<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
if(!empty($orderPickUpData)){
?>
        <div class="pop-up-title"><?php echo Labels::getLabel('LBL_Pick_Up', $siteLangId); ?></div>
        <div class="pick-section">
            <ul class="list-group review-block">
                <?php foreach($orderPickUpData as $address) { ?>
                 <li class="list-group-item">
                     <div class="review-block__label">
                         <strong><?php echo ($address['opshipping_by_seller_user_id'] > 0) ? $address['op_shop_name'] : FatApp::getConfig('CONF_WEBSITE_NAME_' . $siteLangId, null, ''); ?></strong>
                     </div>
                    <div class="review-block__content" role="cell">  
                        <div class="delivery-address"> 
                            <p><?php echo ( mb_strlen($address['oua_address1'] ) > 0 ) ? $address['oua_address1'] : '';?>
                            <?php echo ( mb_strlen($address['oua_address1'] ) > 0 ) ? $address['oua_address1'] . '<br>' : '';?>
                            <?php echo ( mb_strlen($address['oua_city']) > 0 ) ? $address['oua_city'] . ',' : '';?>
                            <?php echo ( mb_strlen($address['oua_state']) > 0 ) ? $address['oua_state'] . '<br>' : '';?>
                            <?php echo ( mb_strlen($address['oua_country']) > 0 ) ? $address['oua_country'] . ',' : '';?>
                            <?php echo ( mb_strlen($address['oua_zip']) > 0 ) ?  $address['oua_zip'] . '<br>' : '';?></p>
                            <p class="phone-txt"><?php echo ( mb_strlen($address['oua_phone']) > 0 ) ? $address['oua_phone'] . '' : '';?></p>
                            <?php 
                            $fromTime = date('H:i', strtotime($address["opshipping_time_slot_from"]));
                            $toTime = date('H:i', strtotime($address["opshipping_time_slot_to"]));
                            ?>
                            <p><?php echo "<strong>".FatDate::format($address["opshipping_date"]).' '.$fromTime.' - '.$toTime.'</strong>'; ?></p>
                        </div>
                    </div>
                    <div class="review-block__link" role="cell">
                        <a class="link" href="javascript:void(0);" onClick="ShippingSummaryData();"><span><?php echo Labels::getLabel('LBL_Change_Address', $siteLangId); ?></span></a>
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>
 
<?php }else{ ?>
<h5 class="step-title"><?php echo Labels::getLabel('LBL_No_Pick_Up_address_added', $siteLangId); ?></h5>
<?php } ?>

<script>
ShippingSummaryData = function(){
    $("#facebox .close").trigger('click');
    loadShippingSummaryDiv();
}
</script>