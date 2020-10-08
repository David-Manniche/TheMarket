<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

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

            $product['productUrl'] = UrlHelper::generateFullUrl('Products', 'View', array($product['selprod_id']));
            $product['shopUrl'] = UrlHelper::generateFullUrl('Shops', 'View', array($product['shop_id']));
            $product['imageUrl'] = UrlHelper::getCachedUrl(UrlHelper::generateFullFileUrl('image', 'product', array($product['product_id'], "THUMB",$product['selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg');

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
