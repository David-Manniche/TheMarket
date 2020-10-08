<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$productItems = [];

$count = 0;
if (Shipping::FULFILMENT_PICKUP == $fulfillmentType) {
    ksort($shippingRates);
    $productItems[$count] = [];
    $levelNo = 0;
    foreach ($shippingRates as $pickUpBy => $levelItems) {

        if (isset($levelItems['products']) && count($levelItems['products']) > 0 && $pickUpBy == 0) {
            $productData = current($levelItems['products']);
            $productItems[$count]['title'] =  ($pickUpBy == Shipping::LEVEL_SHOP) ? $productData['shop_name'] : FatApp::getConfig('CONF_WEBSITE_NAME_' . $siteLangId, null, '');
            $productItems[$count]['pickup_by'] = $pickUpBy;
            if (!empty($levelItems['pickup_address'])) {
                $productItems[$count]['pickup_address'] = $levelItems['pickup_address'];
            }

            if (count($levelItems['pickup_options']) > 0) {
                $productItems[$count]['pickup_addresses'] = $levelItems['pickup_options'];
            } else {
                $productItems[$count]['pickup_addresses'] = [];
            }
        }

        if (isset($levelItems['products'])) {
            foreach ($levelItems['products'] as $product) {
                if ($levelNo != $pickUpBy) {
                    if (count($levelItems['products']) > 0  && $pickUpBy != 0) {
                        $productItems[$count]['title'] = $product['shop_name'];
                        $productItems[$count]['pickup_by'] = $pickUpBy;
                        if (!empty($levelItems['pickup_address'])) {
                            $productItems[$count]['pickup_address'] = $levelItems['pickup_address'];
                        }

                        if (count($levelItems['pickup_options']) > 0) {
                            $productItems[$count]['pickup_addresses'] = $levelItems['pickup_options'];
                        } else {
                            $productItems[$count]['pickup_addresses'] = [];
                        }
                    }
                }
                $levelNo = $pickUpBy;
                $productItems[$count]['products'][] = $product;

                if (isset($levelItems['products']) && count($levelItems['products']) == 1) {
                    $count++;
                }
            }
            if (isset($levelItems['products']) && count($levelItems['products']) > 1) {
                $count++;
            }

            if (isset($levelItems['digital_products']) && count($levelItems['digital_products']) > 0) {
                foreach ($levelItems['digital_products'] as $product) {
                    $productItems[$count]['title'] = $product['shop_name'];
                    $productItems[$count]['pickup_address'] = [];
                    $productItems[$count]['pickup_addresses'] = [];
                    $productItems[$count]['products'][] = $product;
                }
                $count++;
            }
        }
    }
}

$count = 0;
if (Shipping::FULFILMENT_SHIP == $fulfillmentType) {
    if (array_key_exists(Shipping::BY_ADMIN, $shippingRates)) {
        ksort($shippingRates);
    }
    $productItems[$count] = [];
    foreach ($shippingRates as $level => $levelItems) {


        if (isset($levelItems['products']) && count($levelItems['products']) > 1 && $level != Shipping::LEVEL_PRODUCT) {
            $productData = current($levelItems['products']);
            $productItems[$count]['title'] = ($level == Shipping::LEVEL_SHOP) ? $productData['shop_name'] : FatApp::getConfig('CONF_WEBSITE_NAME_' . $siteLangId, null, '');

            if ($level != Shipping::LEVEL_PRODUCT) {
                if (count($levelItems['rates']) > 0) {
                    $productItems[$count]['rates']['code'] = current($levelItems['rates'])['code'];
                    foreach ($levelItems['rates'] as $key => $shippingRate) {
                        if (!empty($orderShippingData)) {
                            foreach ($orderShippingData as $shipdata) {
                                if ($shipdata['opshipping_code'] == $name && ($key == $shipdata['opshipping_carrier_code'] . "|" . $shipdata['opshipping_label'] || $key == $shipdata['opshipping_rate_id'])) {
                                    $productItems[$count]['rates']['selected'] = $key;
                                    break;
                                }
                            }
                        }
                        $productItems[$count]['rates']['data'][] = [
                            'title' => $shippingRate['title'],
                            'cost' => $shippingRate['cost'],
                        ];
                    }
                } else {
                    $productItems[$count]['rates']['data'] = [];
                }
            }
        }

        if (isset($levelItems['products'])) {
            foreach ($levelItems['products'] as $product) {
                if (count($levelItems['products']) == 1 && count($levelItems['rates']) > 0 && $level != Shipping::LEVEL_PRODUCT) {
                    $productItems[$count]['title'] = $product['shop_name'];
                    if (count($levelItems['rates']) > 0) {
                        $productItems[$count]['rates']['code'] =  current($levelItems['rates'])['code'];

                        foreach ($levelItems['rates'] as $key => $shippingRate) {
                            if (!empty($orderShippingData)) {
                                foreach ($orderShippingData as $shipdata) {
                                    if ($shipdata['opshipping_code'] == $name && ($key == $shipdata['opshipping_carrier_code'] . "|" . $shipdata['opshipping_label'] || $key == $shipdata['opshipping_rate_id'])) {
                                        $productItems[$count]['rates']['selected'] = $key;
                                        break;
                                    }
                                }
                            }

                            $productItems[$count]['rates']['data'][] = [
                                'title' => $shippingRate['title'],
                                'cost' => $shippingRate['cost'],
                            ];
                        }
                    } elseif ($product['product_type'] == Product::PRODUCT_TYPE_PHYSICAL) {
                        $productItems[$count]['rates']['data'] = [];
                    }
                }

                if ($level == Shipping::LEVEL_PRODUCT && isset($levelItems['rates'][$product['selprod_id']])) {
                    if (!empty($productItems[$count])) {
                        $count++;
                    }

                    $productItems[$count]['title'] = $product['shop_name'];

                    $priceListCount = count($levelItems['rates'][$product['selprod_id']]);
                    if ($priceListCount > 0) {
                        $productItems[$count]['rates']['code'] =  current($levelItems['rates'][$product['selprod_id']])['code'];

                        foreach ($levelItems['rates'][$product['selprod_id']] as $key => $shippingRate) {
                            if (!empty($orderShippingData)) {
                                foreach ($orderShippingData as $shipdata) {
                                    if ($shipdata['opshipping_code'] == $name && ($key == $shipdata['opshipping_carrier_code'] . "|" . $shipdata['opshipping_label'] || $key == $shipdata['opshipping_rate_id'])) {
                                        $productItems[$count]['rates']['selected'] = $key;
                                        break;
                                    }
                                }
                            }

                            $productItems[$count]['rates']['data'][] = [
                                'title' => $shippingRate['title'],
                                'cost' => $shippingRate['cost'],
                            ];
                        }
                    } elseif ($product['product_type'] == Product::PRODUCT_TYPE_PHYSICAL) {
                        $productItems[$count]['rates']['data'] = [];
                    }
                }

                $productItems[$count]['products'][] = $product;

                if (isset($levelItems['products']) && count($levelItems['products']) == 1) {
                    $count++;
                }
            }
        }

        if (isset($levelItems['products']) && count($levelItems['products']) > 1) {
            $count++;
        }

        if (isset($levelItems['digital_products']) && count($levelItems['digital_products']) > 0) {
            foreach ($levelItems['digital_products'] as $product) {
                $productItems[$count]['title'] = $product['shop_name'];
                $productItems[$count]['rates']['code'] = '';
                $productItems[$count]['products'][] = $product;
            }
            $count++;
        }
    }
}

$data = [
    'fulfillmentType' => $fulfillmentType,
    'hasPhysicalProd' => $hasPhysicalProd,
    'addresses' => $addresses,
    'cartSummary' => $cartSummary,
    'productItems' => $productItems,
];

require_once(CONF_THEME_PATH . 'cart/price-detail.php');

/* if (!empty($cartSummary) && array_key_exists('cartDiscounts', $cartSummary)) {
    $cartSummary['cartDiscounts'] = !empty($cartSummary['cartDiscounts']) ? $cartSummary['cartDiscounts'] : (object)array();
}

usort($products, function ($a, $b) {
    return $a['shop_id'] - $b['shop_id'];
});

foreach ($products as $index => $product) {
    $products[$index]['product_image_url'] = UrlHelper::generateFullUrl('image', 'product', array($product['product_id'], "CLAYOUT3", $product['selprod_id'], 0, $siteLangId));
    $products[$index]['total'] = !empty($product['total']) ? CommonHelper::displayMoneyFormat($product['total']) : 0;
    $products[$index]['totalPrice'] = !empty($product['totalPrice']) ? CommonHelper::displayMoneyFormat($product['totalPrice'], false, false, false) : 0;
    $products[$index]['netTotal'] = !empty($product['netTotal']) ? CommonHelper::displayMoneyFormat($product['netTotal']) : 0;
    // $products[$index]['shop_free_ship_upto'] = !empty($product['shop_free_ship_upto']) ? CommonHelper::displayMoneyFormat($product['shop_free_ship_upto'], false, false, false) : 0;
    $products[$index]['productKey'] = md5($products[$index]['key']);
    $shipping_options = array(
        array(
            'title' => Labels::getLabel("LBL_Select_Shipping", $siteLangId),
            'value' => 0,
        )
    );
    if (count($product["shipping_rates"])) {
        $i = 1;
        foreach ($product["shipping_rates"] as $skey => $sval) {
            $country_code = empty($sval["country_code"]) ? "" : " (" . $sval["country_code"] . ")";
            $product["shipping_free_availbilty"];           
            $shipping_charges = CommonHelper::displayMoneyFormat($sval['pship_charges']);
            $shippingDurationTitle = ShippingDurations::getShippingDurationTitle($sval, $siteLangId);
            $shipping_options[$i]['title'] =  $sval["scompany_name"] ." - " . $shippingDurationTitle . $country_code . " (" . $shipping_charges . ")";
            $shipping_options[$i]['value'] =  $sval['pship_id'];
            $i++;
        }
    }

    $shipStation = array();
    if (!empty($shipStationCarrierList)) {
        $i = 0;
        foreach ($shipStationCarrierList as $key => $value) {
            $shipStation[$i]['title'] = $value;
            $shipStation[$i]['value'] = $key;
            $i++;
        }
    }
    $newShippingMethods = $shippingMethods;
    if (2 > sizeof($shipping_options)) {
        unset($newShippingMethods[SHIPPINGMETHODS::MANUAL_SHIPPING]);
    }

    $products[$index]['shippingMethods'][] = [
        'title' => Labels::getLabel('LBL_Select_Shipping_Method', $siteLangId),
        'value' => 0,
        'rates' => []
    ];
    foreach ($newShippingMethods as $shippingMethodType => $shipingMethodtitle) {
        $shippinhMethodArr = [
            'title' => $shipingMethodtitle,
            'value' => $shippingMethodType
        ];
        switch ($shippingMethodType) {
            case ShippingMethods::MANUAL_SHIPPING:
                $shippinhMethodArr['rates'] = $shipping_options;
                break;
            case ShippingMethods::SHIPPING_SERVICES:
                $shippinhMethodArr['rates'] = $shipStation;
                break;
        }
        $products[$index]['shippingMethods'][] = $shippinhMethodArr;
    }
}

$data = array(
    'products' => !empty(array_filter($products)) ? array_values($products) : array(),
    'cartSummary' => $cartSummary,
    'shippingAddressDetail' => !empty($shippingAddressDetail) && !empty(array_filter($shippingAddressDetail)) ? $shippingAddressDetail : (object)array(),
);


if (empty($products)) {
    $status = applicationConstants::OFF;
} */
