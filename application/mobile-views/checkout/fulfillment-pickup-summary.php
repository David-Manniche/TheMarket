<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

ksort($shippingRates);

$productItems = [];
$levelNo = 0;
foreach ($shippingRates as $pickUpBy => $levelItems) {
    if (isset($levelItems['products']) && count($levelItems['products']) > 0 && $pickUpBy == 0) {
        $productData = current($levelItems['products']);

        if (!isset($productItems[$pickUpBy]['title'])) {
            $productItems[$pickUpBy]['title'] =  ($pickUpBy == Shipping::LEVEL_SHOP) ? $productData['shop_name'] : FatApp::getConfig('CONF_WEBSITE_NAME_' . $siteLangId, null, '');
        }

        $productItems[$pickUpBy]['pickup_by'] = $pickUpBy;
        if (!empty($levelItems['pickup_address'])) {
            $productItems[$pickUpBy]['pickup_address'] = $levelItems['pickup_address'];
        }

        if (count($levelItems['pickup_options']) > 0) {
            $productItems[$pickUpBy]['pickup_addresses'] = $levelItems['pickup_options'];
        } else {
            $productItems[$pickUpBy]['pickup_addresses'] = [];
        }
    }

    if (isset($levelItems['products'])) {
        foreach ($levelItems['products'] as $product) {
            if ($levelNo != $pickUpBy) {
                if (count($levelItems['products']) > 0  && $pickUpBy != 0) {
                    $productItems[$pickUpBy]['title'] = $product['shop_name'];
                    $productItems[$pickUpBy]['pickup_by'] = $pickUpBy;
                    if (!empty($levelItems['pickup_address'])) {
                        $productItems[$pickUpBy]['pickup_address'] = $levelItems['pickup_address'];
                    }

                    if (count($levelItems['pickup_options']) > 0) {
                        $productItems[$pickUpBy]['pickup_addresses'] = $levelItems['pickup_options'];
                    } else {
                        $productItems[$pickUpBy]['pickup_addresses'] = [];
                    }
                }
            }

            $product['productUrl'] = UrlHelper::generateFullUrl('Products', 'View', array($product['selprod_id']));
            $product['shopUrl'] = UrlHelper::generateFullUrl('Shops', 'View', array($product['shop_id']));
            $product['imageUrl'] = UrlHelper::getCachedUrl(UrlHelper::generateFullFileUrl('image', 'product', array($product['product_id'], "THUMB", $product['selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg');

            $levelNo = $pickUpBy;
            $productItems[$pickUpBy]['products'][] = $product;
        }

        if (isset($levelItems['digital_products']) && count($levelItems['digital_products']) > 0) {
            foreach ($levelItems['digital_products'] as $product) {
                $productItems[$pickUpBy]['title'] = $product['shop_name'];
                $productItems[$pickUpBy]['pickup_address'] = [];
                $productItems[$pickUpBy]['pickup_addresses'] = [];
                $productItems[$pickUpBy]['products'][] = $product;
            }
        }
    }
}
