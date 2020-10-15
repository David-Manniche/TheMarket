<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
ksort($shippingRates);
$productItems[$count] = [];
foreach ($shippingRates as $shippedBy => $shippedByItemArr) {
    ksort($shippedByItemArr);
    foreach ($shippedByItemArr as $shipLevel => $items) {
        switch ($shipLevel) {
            case Shipping::LEVEL_ORDER:
            case Shipping::LEVEL_SHOP:
                if (isset($items['products']) && !empty($items['products'])) {
                    $productData = $items['products'];
                    $productInfo = current($productData);

                    $productItems[$count]['title'] = ($shipLevel == Shipping::LEVEL_SHOP) ? $productInfo['shop_name'] : FatApp::getConfig('CONF_WEBSITE_NAME_' . $siteLangId, null, '');

                    $shippingCharges = [];
                    if (isset($shippedByItemArr[$shipLevel]['rates'])) {
                        $shippingCharges = $shippedByItemArr[$shipLevel]['rates'];
                    }

                    if (count($shippingCharges) > 0) {
                        $name = current($shippingCharges)['code'];
                        $productItems[$count]['rates']['code'] =  $name;
                        foreach ($shippingCharges as $key => $shippingcharge) {
                            if (!empty($orderShippingData)) {
                                foreach ($orderShippingData as $shipdata) {
                                    if ($shipdata['opshipping_code'] == $name && ($key == $shipdata['opshipping_carrier_code'] . "|" . $shipdata['opshipping_label'] || $key == $shipdata['opshipping_rate_id'])) {
                                        $productItems[$count]['rates']['selected'] = $key;
                                        break;
                                    }
                                }
                            }

                            $productItems[$count]['rates']['data'][] = [
                                'title' => $shippingcharge['title'],
                                'cost' => $shippingcharge['cost'],
                                'id' => $shippingcharge['id'],
                            ];
                        }
                    } else {
                        $productItems[$count]['rates']['data'] = [];
                    }

                    foreach ($productData as $product) {
                        $product['productUrl'] = UrlHelper::generateFullUrl('Products', 'View', array($product['selprod_id']));
                        $product['shopUrl'] = UrlHelper::generateFullUrl('Shops', 'View', array($product['shop_id']));
                        $product['imageUrl'] = UrlHelper::getCachedUrl(UrlHelper::generateFullFileUrl('image', 'product', array($product['product_id'], "THUMB", $product['selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg');
                        $productItems[$count]['products'][] = $product;
                    }
                    $count++;
                }
                break;
            case Shipping::LEVEL_PRODUCT:
                if (isset($items['products']) && !empty($items['products'])) {
                    foreach ($items['products'] as $selProdid => $product) {
                        $productItems[$count]['title'] = $product['shop_name'];
                        $priceListCount = count($shippedByItemArr[$shipLevel]['rates'][$product['selprod_id']]);
                        if ($priceListCount > 0) {
                            $name = current($shippedByItemArr[$shipLevel]['rates'][$product['selprod_id']])['code'];
                            $productItems[$count]['rates']['code'] =  $name;
                            foreach ($shippedByItemArr[$shipLevel]['rates'][$product['selprod_id']] as $key => $shippingcharge) {
                                if (!empty($orderShippingData)) {
                                    foreach ($orderShippingData as $shipdata) {
                                        if ($shipdata['opshipping_code'] == $name && ($key == $shipdata['opshipping_carrier_code'] . "|" . $shipdata['opshipping_label'] || $key == $shipdata['opshipping_rate_id'])) {
                                            $productItems[$count]['rates']['selected'] = $key;
                                            break;
                                        }
                                    }
                                }
                                $productItems[$count]['rates']['data'][] = [
                                    'title' => $shippingcharge['title'],
                                    'cost' => $shippingcharge['cost'],
                                    'id' => $shippingcharge['id'],
                                ];
                            }
                        } else {
                            $productItems[$count]['rates']['data'] = [];
                        }

                        $product['productUrl'] = UrlHelper::generateFullUrl('Products', 'View', array($product['selprod_id']));
                        $product['shopUrl'] = UrlHelper::generateFullUrl('Shops', 'View', array($product['shop_id']));
                        $product['imageUrl'] = UrlHelper::getCachedUrl(UrlHelper::generateFullFileUrl('image', 'product', array($product['product_id'], "THUMB", $product['selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg');
                        $productItems[$count]['products'][] = $product;
                        $count++;
                    }
                }

                if (isset($items['digital_products']) && !empty($items['digital_products'])) {
                    foreach ($items['digital_products'] as $selProdid => $product) {
                        $productItems[$count]['title'] = $product['shop_name'];
                        $priceListCount = count($shippedByItemArr[$shipLevel]['rates'][$product['selprod_id']]);
                        if ($priceListCount > 0) {
                            $name = current($shippedByItemArr[$shipLevel]['rates'][$product['selprod_id']])['code'];
                            $productItems[$count]['rates']['code'] =  $name;
                            foreach ($shippedByItemArr[$shipLevel]['rates'][$product['selprod_id']] as $key => $shippingcharge) {
                                if (!empty($orderShippingData)) {
                                    foreach ($orderShippingData as $shipdata) {
                                        if ($shipdata['opshipping_code'] == $name && ($key == $shipdata['opshipping_carrier_code'] . "|" . $shipdata['opshipping_label'] || $key == $shipdata['opshipping_rate_id'])) {
                                            $productItems[$count]['rates']['selected'] = $key;
                                            break;
                                        }
                                    }
                                }
                                $productItems[$count]['rates']['data'][] = [
                                    'title' => $shippingcharge['title'],
                                    'cost' => $shippingcharge['cost'],
                                    'id' => $shippingcharge['id'],
                                ];
                            }
                        } else {
                            $productItems[$count]['rates']['data'] = [];
                        }

                        $product['productUrl'] = UrlHelper::generateFullUrl('Products', 'View', array($product['selprod_id']));
                        $product['shopUrl'] = UrlHelper::generateFullUrl('Shops', 'View', array($product['shop_id']));
                        $product['imageUrl'] = UrlHelper::getCachedUrl(UrlHelper::generateFullFileUrl('image', 'product', array($product['product_id'], "THUMB", $product['selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg');
                        $productItems[$count]['products'][] = $product;
                        $count++;
                    }
                }
                break;
        }
    }
}