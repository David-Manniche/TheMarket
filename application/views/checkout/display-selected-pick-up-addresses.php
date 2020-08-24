<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
if(!empty($pickUpAddrData)){
?>

        <div class="pop-up-title"><?php echo Labels::getLabel('LBL_Pick_Up', $siteLangId); ?></div>
        <div class="pick-section">
            <ul class="list-group review-block">
                <?php foreach($pickUpAddrData as $address) { ?>
                 <li class="list-group-item">
                     <div class="review-block__label">
                         <strong><?php echo $address['shop_name']; ?></strong>
                     </div>
                    <div class="review-block__content" role="cell">  
                        <div class="delivery-address"> 
                            <p><?php echo ( mb_strlen($address['addr_address1'] ) > 0 ) ? $address['addr_address1'] : '';?>
                            <?php echo ( mb_strlen($address['addr_address2'] ) > 0 ) ? $address['addr_address2'] . '<br>' : '';?>
                            <?php echo ( mb_strlen($address['addr_city']) > 0 ) ? $address['addr_city'] . ',' : '';?>
                            <?php echo ( mb_strlen($address['state_name']) > 0 ) ? $address['state_name'] . '<br>' : '';?>
                            <?php echo ( mb_strlen($address['country_name']) > 0 ) ? $address['country_name'] . ',' : '';?>
                            <?php echo ( mb_strlen($address['addr_zip']) > 0 ) ?  $address['addr_zip'] . '<br>' : '';?></p>
                            <p class="phone-txt"><?php echo ( mb_strlen($address['addr_phone']) > 0 ) ? $address['addr_phone'] . '' : '';?></p>
                            <?php 
                            $fromTime = date('H:i', strtotime($address["time_slot_from"]));
                            $toTime = date('H:i', strtotime($address["time_slot_to"]));
                            ?>
                            <p><?php echo "<strong>".FatDate::format($address["time_slot_date"]).' '.$fromTime.' - '.$toTime.'</strong>'; ?></p>
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