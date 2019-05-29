<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<?php if( !empty($cartSummary['scartDiscounts']['coupon_code']) ){ ?>
	<div class="gap"></div>
	<div class="applied-coupon"><?php echo Labels::getLabel("LBL_Coupon", $siteLangId); ?> "<strong><?php echo $cartSummary['scartDiscounts']['coupon_code'];?></strong>" <?php echo Labels::getLabel("LBL_Applied", $siteLangId); ?> <a href="javascript:void(0)" onClick="removePromoCode()" class="btn btn--sm btn--white ripplelink fr"><?php echo Labels::getLabel("LBL_Remove", $siteLangId); ?></a></div>
	<div class="gap"></div>
<?php } else { ?>
	<div class="coupon">
	  <input value="" type="text" class="coupon-input" placeholder="<?php echo Labels::getLabel('LBL_I_have_a_coupon', $siteLangId);?>" >
	  <a class="coupon-input btn btn--primary btn--block ripplelink" href="javascript:void(0)"><?php echo Labels::getLabel('LBL_I_have_a_coupon', $siteLangId);?></a>
	</div>
	<div class="gap"></div>
	<div class="heading4 align--center"><?php echo Labels::getLabel('LBL_Order_Summary', $siteLangId); ?> </div>
<?php } ?>

<div class="cartdetail__footer">
  <table>
    <tbody>
      <tr>
        <td class="text-left"><?php echo Labels::getLabel('LBL_Total', $siteLangId); ?></td>
        <td class="text-right"><?php echo CommonHelper::displayMoneyFormat($cartSummary['cartTotal']); ?></td>
      </tr>
      <?php if(!empty($cartSummary['cartAdjustableAmount'])){?>
      <tr>
        <td class="text-left"><?php echo Labels::getLabel('LBL_Adjusted_Amount', $siteLangId); ?></td>
        <td class="text-right"><?php echo CommonHelper::displayMoneyFormat($cartSummary['cartAdjustableAmount']); ?></td>
      </tr>
      <?php }?>
      <?php if(!empty($cartSummary['scartDiscounts'])){?>
      <tr>
        <td class="text-left"><?php echo Labels::getLabel('LBL_Discount', $siteLangId); ?></td>
        <td class="text-right"><?php echo CommonHelper::displayMoneyFormat($cartSummary['scartDiscounts']['coupon_discount_total']); ?></td>
      </tr>
      <?php }?>
	   <?php if(!empty($cartSummary['cartRewardPoints'])){?>
      <tr>
        <td class="text-left"><?php echo Labels::getLabel('LBL_Reward_point_discount', $siteLangId); ?></td>
        <td class="text-right"><?php echo CommonHelper::displayMoneyFormat(CommonHelper::rewardPointDiscount($cartSummary['orderNetAmount'],$cartSummary['cartRewardPoints'])); ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td class="text-left hightlighted"><?php echo Labels::getLabel('LBL_You_Pay', $siteLangId); ?></td>
        <td class="text-right hightlighted"><?php echo CommonHelper::displayMoneyFormat($cartSummary['orderNetAmount']); ?></td>
      </tr>
    </tbody>
  </table>
</div>
<div class="gap"></div>
