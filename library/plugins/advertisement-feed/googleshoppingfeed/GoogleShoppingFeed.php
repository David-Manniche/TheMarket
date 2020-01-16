<?php
include_once CONF_INSTALLATION_PATH . 'library/GoogleAPI/vendor/autoload.php';
class GoogleShoppingFeed extends AdvertisementFeedBase
{
    private $merchantId;

    public function __construct()
    {
        $this->merchantId = $this->getUserAccountDetail(__CLASS__ . '_merchantId');
        if (empty($this->merchantId)) {
            $this->setupMerchantDetail();
        }
    }

    public static function ageGroup($langId)
    {
        return [
            'newborn' => Labels::getLabel('LBL_UP_TO_3_MONTHS_OLD', $langId) . ' - ' . Labels::getLabel('LBL_NEWBORN', $langId),
            'infant' => Labels::getLabel('LBL_BETWEEN_3-12_MONTHS_OLD', $langId) . ' - ' . Labels::getLabel('LBL_INFANT', $langId),
            'toddler' => Labels::getLabel('LBL_BETWEEN_1-5_YEARS_OLD', $langId) . ' - ' . Labels::getLabel('LBL_TODDLER', $langId),
            'kids' => Labels::getLabel('LBL_BETWEEN_5-13_YEARS_OLD', $langId) . ' - ' . Labels::getLabel('LBL_KIDS', $langId),
            'adult' => Labels::getLabel('LBL_TYPICALLY_TEENS_OR_OLDER', $langId) . ' - ' . Labels::getLabel('LBL_ADULT', $langId),
        ];
    }

    public static function form($langId)
    {
        $frm = new Form('frmServiceAccount');
        $privateKey = $frm->addTextArea(Labels::getLabel('LBL_SERVICE_ACCOUNT_INFO', $langId), 'service_account');
        $privateKey->requirements()->setRequired();
        
        $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $langId));
        return $frm;
    }

    private function doRequest($data)
    {
        $client = new Google_Client();
        $serviceAccountDetail = $this->getUserAccountDetail('service_account');
        if (empty($serviceAccountDetail)) {
            $this->error = Labels::getLabel('LBL_SERVICE_ACCOUNT_DETAIL_NOT_FOUND', CommonHelper::getLangId());
            return false;
        }
        $serviceAccountDetail = json_decode($serviceAccountDetail, true);
        $client->setAuthConfig($serviceAccountDetail);
        $client->setScopes(Google_Service_ShoppingContent::CONTENT);
        $client->useApplicationDefaultCredentials();
        $client->setUseBatch(true);

        $service = new Google_Service_ShoppingContent($client);
        $batch = $service->createBatch();

        foreach ($data['data'] as $prodDetail) {
            $colorOption = array_filter($prodDetail['optionsData'], function ($v) {
                return 1 == $v['option_is_color'];
            });
            $color = !empty($colorOption) ? array_shift($colorOption)['optionvalue_identifier'] : '';

            $product = new Google_Service_ShoppingContent_Product();
            $product->setId($prodDetail['abprod_selprod_id']);
            $product->setOfferId($prodDetail['abprod_selprod_id']);
            $product->setTitle($prodDetail['selprod_title']);
            $product->setDescription($prodDetail['product_description']);
            $product->setColor($color);
            $product->setItemGroupId($prodDetail['abprod_item_group_identifier']);
            $product->setBrand(ucfirst(Brand::getAttributesById($prodDetail['product_brand_id'], 'brand_identifier')));
            $product->setLink(CommonHelper::generateFullUrl('Products', 'View', array($prodDetail['selprod_id'])));
            $product->setImageLink(CommonHelper::generateFullUrl('image', 'product', array($prodDetail['product_id'], "MEDIUM", $prodDetail['selprod_id'], 0, CommonHelper::getLangId())));
            $product->setContentLanguage(strtolower(Language::getAttributesById($prodDetail['adsbatch_lang_id'], 'language_code')));
            $product->setTargetCountry(strtoupper(Countries::getAttributesById($prodDetail['adsbatch_target_country_id'], 'country_code')));
            $product->setChannel($this->getSettings('channel'));
            $inStock = (0 < $prodDetail['selprod_stock'] ? "in stock" : 'out of stock');
            $product->setAvailability($inStock);
            $product->setAvailabilityDate(date('Y-m-d', strtotime($prodDetail['selprod_available_from'])));
            
            $timestamp = strtotime($prodDetail['adsbatch_expired_on']);
            if (0 < $timestamp) {
                $product->setExpirationDate(date('Y-m-d', $timestamp));
            }
            $product->setCondition(Product::getConditionArr($data['siteLangId'])[$prodDetail['selprod_condition']]);
            $product->setGoogleProductCategory($prodDetail['abprod_cat_id']);
            $product->setGtin($prodDetail['product_upc']);
            // $product->setMpn('9780007350896');

            $price = new Google_Service_ShoppingContent_Price();
            $price->setValue($prodDetail['selprod_price']);
            $price->setCurrency(strtoupper(Currency::getAttributesById($data['siteCurrencyId'], 'currency_code')));
            $product->setPrice($price);
            
            /* $shipping_price = new Google_Service_ShoppingContent_Price();
            $shipping_price->setValue('0.99');
            $shipping_price->setCurrency('GBP');

            $shipping = new Google_Service_ShoppingContent_ProductShipping();
            $shipping->setPrice($shipping_price);
            $shipping->setCountry('GB');
            $shipping->setService('Standard shipping');

            $shipping_weight = new Google_Service_ShoppingContent_ProductShippingWeight();
            $shipping_weight->setValue(200);
            $shipping_weight->setUnit('grams');

            $product->setPrice($price);
            $product->setShipping(array($shipping));
            $product->setShippingWeight($shipping_weight); */
        
            $request = $service->products->insert($this->merchantId, $product);
            $batch->add($request, $product->getOfferId());
        }
        return $batch->execute();
    }

    public function getProductCategory($keyword = '', $returnFullArray = false)
    {
        $arr = [];
        if ($fh = fopen(__DIR__ . '/googleProductCategory.txt', 'r')) {
            $rowIndex = 1;
            while (!feof($fh)) {
                if (empty($keyword) && false === $returnFullArray) {
                    if ($rowIndex == applicationConstants::PAGE_SIZE) {
                        break;
                    }
                }
                $line = fgets($fh);
                $lineContentArr = explode('-', $line, 2);
                if (!empty($lineContentArr) && 1 < count($lineContentArr)) {
                    $arr[trim($lineContentArr[0])] = trim($lineContentArr[1]);
                }
                $rowIndex++;
            }
            fclose($fh);
        }
        ksort($arr);
        
        if (true === $returnFullArray) {
            return $arr;
        }

        return empty($keyword) ? $arr : preg_grep("/" . preg_quote($keyword) . "/i", $arr);
    }

    public function publishBatch($data)
    {
        if (empty($data) || !is_array($data)) {
            $this->error = Labels::getLabel('LBL_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }
        return $this->doRequest($data);
    }
}
