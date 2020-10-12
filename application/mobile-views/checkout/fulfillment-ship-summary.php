<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

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
                        'id' => $shippingRate['id'],
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
                            'id' => $shippingRate['id'],
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
                            'id' => $shippingRate['id'],
                        ];
                    }
                } elseif ($product['product_type'] == Product::PRODUCT_TYPE_PHYSICAL) {
                    $productItems[$count]['rates']['data'] = [];
                }
            }

            $product['productUrl'] = UrlHelper::generateFullUrl('Products', 'View', array($product['selprod_id']));
            $product['shopUrl'] = UrlHelper::generateFullUrl('Shops', 'View', array($product['shop_id']));
            $product['imageUrl'] = UrlHelper::getCachedUrl(UrlHelper::generateFullFileUrl('image', 'product', array($product['product_id'], "THUMB",$product['selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg');
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