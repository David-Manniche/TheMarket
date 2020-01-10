<?php
class GoogleShoppingFeed extends AdvertisementFeedBase
{
    private const PRODUCTION_URL = 'https://www.googleapis.com/content/v2/';
    private const INSERT_URL = '{merchantId}/products';
    private const GET_URL = '{merchantId}/products/{productId}';
    private const DELETE_URL = '{merchantId}/products/{productId}';
    private const LIST_URL = '{merchantId}/products';
    
    private const BATCH_REQUEST_URL = 'products/batch';

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

    private function makeUrl($url, $replaceData = [])
    {
        $url = self::PRODUCTION_URL . $url;
        $replaceData = ['{merchantId}' => $this->merchantId] + $replaceData;
        return CommonHelper::replaceStringData($url, $replaceData);
    }

    private function formatPushData($data)
    {
        $arr = [
            "entries" => [
                "batchId" => $data['batchId'],
                "merchantId" => $this->merchantId,
                "method" => "insert"
            ]
        ];
        foreach ($data['data'] as $prodDetail) {
            // $color = array_column($users, 'id');
            $colorOption = array_filter($prodDetail['optionsData'], function ($v) {
                return 1 == $v['option_is_color'];
            });
            $color = !empty($colorOption) ? array_shift($colorOption)['optionvalue_identifier'] : '';

            $sizeOption = array_filter($prodDetail['optionsData'], function ($v) {
                return strpos(strtolower($v['option_name']), 'size') !== false;
            });
            $size = !empty($sizeOption) ? array_shift($sizeOption)['optionvalue_identifier'] : '';

            $arr["entries"]["product"][] = [
                "kind" => "content#product",
                "offerId" => $prodDetail['abprod_selprod_id'],
                "source" => "api",
                "title" => $prodDetail['selprod_title'],
                "description" => $prodDetail['product_description'],
                "link" => CommonHelper::generateUrl('Products', 'View', array($prodDetail['selprod_id'])),
                "imageLink" => CommonHelper::generateUrl('image', 'product', array($prodDetail['product_id'], "MEDIUM", $prodDetail['selprod_id'], 0, CommonHelper::getLangId())),
                "contentLanguage" => strtolower(Language::getAttributesById($prodDetail['adsbatch_lang_id'], 'language_code')),
                "targetCountry" => strtoupper(Countries::getAttributesById($prodDetail['adsbatch_target_country_id'], 'country_code')),
                "channel" => $this->getSettings('channel'),
                "ageGroup" => $prodDetail['abprod_age_group'],
                "availability" => 0 < $prodDetail['selprod_stock'] ? "in stock" : 'out of stock',
                "availabilityDate" => date('Y-m-dTH:i:s-Z', strtotime($prodDetail['selprod_available_from'])),
                "brand" => ucfirst(Brand::getAttributesById($prodDetail['product_brand_id'], 'brand_identifier')),
                "color" => $color,
                "condition" => Product::getConditionArr($data['siteLangId'])[$prodDetail['selprod_condition']],
                "gender" => "unisex",
                "googleProductCategory" => $prodDetail['abprod_cat_id'],
                // "gtin" => "608802531656",
                "itemGroupId" => $prodDetail['abprod_item_group_identifier'],
                // "mpn" => "608802531656",
                "price" => [
                    "value" => $prodDetail['selprod_price'],
                    "currency" => strtoupper(Currency::getAttributesById($data['siteCurrencyId'], 'currency_code'))
                ],
                "sizes" => [
                    $size
                ],
                "destinations" => [
                    [
                        "destinationName" => "Shopping",
                        "intention" => "required"
                    ]
                ]
            ];
        }
        return $arr;
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

    public function insert($data)
    {
        if (empty($data) || !is_array($data)) {
            $this->error = Labels::getLabel('LBL_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }
        $url = $this->makeUrl(self::INSERT_URL);
        return $this->doRequest($url, 'POST', $data);
    }

    public function get($productId)
    {
        if (empty($productId)) {
            $this->error = Labels::getLabel('MSG_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }

        $url = $this->makeUrl(self::GET_URL, ['{productId}' => $productId]);
        return $this->doRequest($url, 'GET');
    }

    public function delete($productId)
    {
        if (empty($productId)) {
            $this->error = Labels::getLabel('MSG_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }

        $url = $this->makeUrl(self::DELETE_URL, ['{productId}' => $productId]);
        return $this->doRequest($url, 'DELETE');
    }

    public function list()
    {
        $url = $this->makeUrl(self::LIST_URL);
        return $this->doRequest($url, 'GET');
    }

    public function pushBatch($data)
    {
        if (empty($data) || !is_array($data)) {
            $this->error = Labels::getLabel('LBL_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }
        $url = $this->makeUrl(self::BATCH_REQUEST_URL);
        $data = $this->formatPushData($data);
        return $this->doRequest($url, 'POST', $data);
    }
}
