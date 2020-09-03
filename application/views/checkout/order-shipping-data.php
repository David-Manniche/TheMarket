<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
if(!empty($orderShippingData)){ 
?>
        <div class="pop-up-title"><?php echo Labels::getLabel('LBL_Shipping', $siteLangId); ?></div>
        <div class="pick-section">
            <ul class="list-group review-block">
                <?php foreach($orderShippingData as $shipData) { ?>
                <li class="list-group-item">
                <?php foreach($shipData as $data) { 
                    $productUrl = UrlHelper::generateUrl('Products', 'View', array($data['op_selprod_id']));
                ?>
                    <div class="product-profile__thumbnail">
                        <a href="<?php echo $productUrl;?>">
                            <img class="img-fluid" data-ratio="3:4" src="<?php echo UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('image', 'product', array($data['selprod_product_id'], "THUMB", $data['op_selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg'); ?>" alt="<?php echo $data['op_selprod_title']; ?>" title="<?php echo $data['op_selprod_title']; ?>">
                        </a>
                    </div>
                <?php } ?>
                    <div class="title product-profile__data"><?php echo $data['opshipping_label']; ?></div>
                    <a class="link" href="javascript:void(0);" onClick="ShippingSummaryData();"><span><?php echo Labels::getLabel('LBL_Change_Shipping', $siteLangId); ?></span></a>
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