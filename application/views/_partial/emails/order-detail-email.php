<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$str='<table cellspacing="0" cellpadding="0" border="0" width="100%" style="border:1px solid #ddd; border-collapse:collapse;">
    <tr>
    <td width="40%" style="padding:10px;background:#eee;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;">'.Labels::getLabel('LBL_Product', $siteLangId).'</td>
    <td width="10%" style="padding:10px;background:#eee;font-size:13px; border:1px solid #ddd;color:#333; font-weight:bold;">'.Labels::getLabel('L_Qty', $siteLangId).'</td>
    <td width="15%" style="padding:10px;background:#eee;font-size:13px; border:1px solid #ddd;color:#333; font-weight:bold;" align="right">'.Labels::getLabel('LBL_Price', $siteLangId).'</td>
    <td width="15%" style="padding:10px;background:#eee;font-size:13px; border:1px solid #ddd;color:#333; font-weight:bold;" align="right">'.Labels::getLabel('LBL_Shipping', $siteLangId).'</td>
    <td width="15%" style="padding:10px;background:#eee;font-size:13px; border:1px solid #ddd;color:#333; font-weight:bold;" align="right">'.Labels::getLabel('LBL_Volume/Loyalty_Discount', $siteLangId).'</td>
    <td width="15%" style="padding:10px;background:#eee;font-size:13px; border:1px solid #ddd;color:#333; font-weight:bold;" align="right">'.Labels::getLabel('LBL_Tax_Charges', $siteLangId).'</td>
    <td width="20%" style="padding:10px;background:#eee;font-size:13px; border:1px solid #ddd;color:#333; font-weight:bold;" align="right">'.Labels::getLabel('LBL_Total', $siteLangId).'</td>
    </tr>';

    $taxCharged = 0 ;
    $cartTotal = 0 ;
    $total = 0 ;
    $shippingTotal = 0 ;
    $netAmount = 0;
    $discountTotal = 0;
    $volumeDiscountTotal = 0;
    $rewardPointDiscount = 0;
    foreach ($orderProducts as $key => $val) {
        $opCustomerBuyingPrice = CommonHelper::orderProductAmount($val, 'CART_TOTAL');
        $shippingPrice = CommonHelper::orderProductAmount($val, 'SHIPPING');
        $discountedPrice = CommonHelper::orderProductAmount($val, 'DISCOUNT');
        $taxCharged = $taxCharged + CommonHelper::orderProductAmount($val, 'TAX');
        $productTaxCharged = CommonHelper::orderProductAmount($val, 'TAX');
        $netAmount = $netAmount + CommonHelper::orderProductAmount($val, 'NETAMOUNT');
        $volumeDiscount=  CommonHelper::orderProductAmount($val, 'VOLUME_DISCOUNT');
        $volumeDiscountTotal = $volumeDiscountTotal + abs(CommonHelper::orderProductAmount($val, 'VOLUME_DISCOUNT'));
        $rewardPointDiscount = $rewardPointDiscount + abs(CommonHelper::orderProductAmount($val, 'REWARDPOINT'));

        $skuCodes = $val["op_selprod_sku"];
        $options = $val['op_selprod_options'];

        $cartTotal = $cartTotal + $opCustomerBuyingPrice;
        $shippingTotal = $shippingTotal + $shippingPrice;
        $discountTotal = $discountTotal + abs($discountedPrice);
        $total =  $total + $opCustomerBuyingPrice + $shippingPrice;

        $prodOrBatchUrl = 'javascript:void(0)';
        /* if($val["op_is_batch"]){
            $prodOrBatchUrl  = UrlHelper::generateFullUrl('products','batch',array($val["op_selprod_id"]),"/");
        }else{
            $prodOrBatchUrl  = UrlHelper::generateFullUrl('products','view',array($val["op_selprod_id"]),"/");
        } */

        $productTaxChargedTxt = '';
        if (empty($val['taxOptions'])) {
            $productTaxChargedTxt = CommonHelper::displayMoneyFormat(CommonHelper::orderProductAmount($val, 'TAX'));
        } else {
            foreach ($val['taxOptions'] as $key => $value) {
                $productTaxChargedTxt .= '<p><strong>'.CommonHelper::displayTaxPercantage($value).':</strong> '.CommonHelper::displayMoneyFormat($value['value']).'</p>';
                if (!isset($taxOptionsTotal[$key]['value'])) {
                    $taxOptionsTotal[$key]['value'] = 0;
                }
                $taxOptionsTotal[$key]['value'] += $value['value'];
                $taxOptionsTotal[$key]['name'] = CommonHelper::displayTaxPercantage($value);
            }
        }

        $str .= '<tr>
            <td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;">
            <a href="'.$prodOrBatchUrl.'" style="font-size:13px; color:#333;">'.$val["op_product_name"].'</a>';
            if(!empty($val["op_brand_name"])){
                $str .='<br/>'.Labels::getLabel('Lbl_Brand', $siteLangId).':'.$val["op_brand_name"];
            }
            $str .='<br/>'.Labels::getLabel('Lbl_Sold_By', $siteLangId).':'.$val["op_shop_name"].'<br/>'.$options.'
            </td>
            <td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;">'.$val['op_qty'].'</td>
            <td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.CommonHelper::displayMoneyFormat($val["op_unit_price"]).'</td>
            <td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.CommonHelper::displayMoneyFormat($shippingPrice).'</td>
            <td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.CommonHelper::displayMoneyFormat($volumeDiscount).'</td>
            <td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.$productTaxChargedTxt.'</td>

            <td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.CommonHelper::displayMoneyFormat($opCustomerBuyingPrice + $shippingPrice +$productTaxCharged - abs($volumeDiscount)).'</td>
        </tr>';
        if(!empty($val['pickupAddress'])){
            $pickUpAddressInfo = $val['pickupAddress']['oua_name'].'<br>';
            if ($val['pickupAddress']['oua_address1']!='') {
                $pickUpAddressInfo.=$val['pickupAddress']['oua_address1'].', ';
            }

            if ($val['pickupAddress']['oua_address2']!='') {
                $pickUpAddressInfo.=$val['pickupAddress']['oua_address2'].'<br>';
            }

            if ($val['pickupAddress']['oua_city']!='') {
                $pickUpAddressInfo.=$val['pickupAddress']['oua_city'].', ';
            }

            if ($val['pickupAddress']['oua_zip']!='') {
                $pickUpAddressInfo.=$val['pickupAddress']['oua_state'];
            }

            if ($val['pickupAddress']['oua_zip']!='') {
                $pickUpAddressInfo.= '-'.$val['pickupAddress']['oua_zip'];
            }

            if ($val['pickupAddress']['oua_phone']!='') {
                $pickUpAddressInfo.= '<br/>'.$val['pickupAddress']['oua_phone'];
            }   
            $str .= '<tr>';
            $str .= '<td colspan="4" style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;">'.Labels::getLabel('LBL_Pickup_Address', $siteLangId).':<br/>'.$pickUpAddressInfo.'</td>';
            if($val["opshipping_type"] == OrderProduct::TYPE_PICKUP){
                $fromTime = date('H:i', strtotime($val["opshipping_time_slot_from"]));
                $toTime = date('H:i', strtotime($val["opshipping_time_slot_to"]));
                $pickupDateAndTime =  FatDate::format($val["opshipping_date"]).' '.$fromTime.' - '.$toTime; 
                $str .= '<td colspan="3" style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;">'.Labels::getLabel('LBL_Pickup_Date', $siteLangId).':<br/>'.$pickupDateAndTime.'</td>';
            }
            $str .= '</tr>';
        }    
    }

    /* $str .= '<tr><td colspan="4" style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.Labels::getLabel('L_TOTAL', $siteLangId).'</td><td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.CommonHelper::displayMoneyFormat($total).'</td></tr>'; */

    $str .= '<tr><td colspan="6" style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.Labels::getLabel('L_CART_TOTAL_(_QTY_*_Product_price_)', $siteLangId).'</td><td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.CommonHelper::displayMoneyFormat($cartTotal).'</td></tr>';

    if ($shippingTotal > 0) {
        $str.='<tr>
        <td colspan="6" style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.Labels::getLabel('LBL_SHIPPING', $siteLangId).'</td>
        <td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.CommonHelper::displayMoneyFormat($shippingTotal).'</td>
        </tr>';
    }

    if ($taxCharged > 0) {
        if (empty($taxOptionsTotal)) {
            $str.='<tr>
            <td colspan="6" style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.Labels::getLabel('LBL_Tax', $siteLangId).'</td>
            <td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.CommonHelper::displayMoneyFormat($taxCharged).'</td>
            </tr>';
        } else {
            foreach ($taxOptionsTotal as $key => $val) {
                $str.='<tr>
                <td colspan="6" style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.CommonHelper::displayTaxPercantage($val).'</td>
                <td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.CommonHelper::displayMoneyFormat($val['value']).'</td>
                </tr>';
            }
        }
    }

    if ($discountTotal != 0) {
        $str.='<tr>
        <td colspan="6" style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.Labels::getLabel('LBL_Discount', $siteLangId).'</td>
        <td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">-'.CommonHelper::displayMoneyFormat($discountTotal).'</td>
        </tr>';
    }
    if ($volumeDiscountTotal != 0) {
        $str.='<tr>
        <td colspan="6" style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.Labels::getLabel('LBL_Volume/Loyalty_Discount', $siteLangId).'</td>
        <td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">-'.CommonHelper::displayMoneyFormat($volumeDiscountTotal).'</td>
        </tr>';
    }

    if ($rewardPointDiscount != 0) {
        $str.='<tr>
        <td colspan="6" style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">'.Labels::getLabel('LBL_Reward_Point_Discount', $siteLangId).'</td>
        <td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right">-'.CommonHelper::displayMoneyFormat($rewardPointDiscount).'</td>
        </tr>';
    }

    $str.= '<tr>
    <td colspan="6" style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right"><strong>'.Labels::getLabel('LBL_ORDER_TOTAL', $siteLangId).'</strong></td>
    <td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" align="right"><strong>'.CommonHelper::displayMoneyFormat($netAmount).'</strong></td></tr>';

    $billingInfo = $billingAddress['oua_name'].'<br>';
    if ($billingAddress['oua_address1']!='') {
        $billingInfo.=$billingAddress['oua_address1'].'<br>';
    }

    if ($billingAddress['oua_address2']!='') {
        $billingInfo.=$billingAddress['oua_address2'].'<br>';
    }

    if ($billingAddress['oua_city']!='') {
        $billingInfo.=$billingAddress['oua_city'].',';
    }

    if ($billingAddress['oua_zip']!='') {
        $billingInfo.=$billingAddress['oua_state'];
    }

    if ($billingAddress['oua_zip']!='') {
        $billingInfo.= '-'.$billingAddress['oua_zip'];
    }

    if ($billingAddress['oua_phone']!='') {
        $billingInfo.= '<br>'.$billingAddress['oua_phone'];
    }

    $str.='</table><br/><br/><table cellspacing="0" cellpadding="0" border="0" width="100%" style="border:1px solid #ddd; border-collapse:collapse;"><tr><td style="padding:10px;background:#eee;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;"  bgcolor="#f0f0f0"><strong>'.Labels::getLabel('LBL_Order_Billing_Details', $siteLangId).'</strong></td>';
    if(!empty($shippingAddress)){
        $str.='<td style="padding:10px;background:#eee;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;" bgcolor="#f0f0f0"><strong>'.Labels::getLabel('L_Order_Shipping_Details', $siteLangId).'</strong></td>';
    }
    $str.='</tr><tr><td valign="top" style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;" >'.$billingInfo.'</td>';
    
    if(!empty($shippingAddress)){
        $shippingInfo = $shippingAddress['oua_name'].'<br>';
        if ($shippingAddress['oua_address1']!='') {
            $shippingInfo.=$shippingAddress['oua_address1'].'<br>';
        }

        if ($shippingAddress['oua_address2']!='') {
            $shippingInfo.=$shippingAddress['oua_address2'].'<br>';
        }

        if ($shippingAddress['oua_city']!='') {
            $shippingInfo.=$shippingAddress['oua_city'].',';
        }

        if ($shippingAddress['oua_zip']!='') {
            $shippingInfo.=$shippingAddress['oua_state'];
        }

        if ($shippingAddress['oua_zip']!='') {
            $shippingInfo.= '-'.$shippingAddress['oua_zip'];
        }

        if ($shippingAddress['oua_phone']!='') {
            $shippingInfo.= '<br>'.$shippingAddress['oua_phone'];
        }
        $str.='<td style="padding:10px;font-size:13px; color:#333;border:1px solid #ddd;">'.$shippingInfo.'</td>';
    }

    $str.='</tr></table>';

echo $str;
