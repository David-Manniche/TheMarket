<?php

require_once CONF_INSTALLATION_PATH . 'vendor/autoload.php';

class TaxJarTax extends TaxBase
{
    public const KEY_NAME = 'TaxJarTax';
       
    public $error;

    public $langId = 0;
    private $_fromAddress = [];
    private $_toAddress = [];
    private $client;

    private const RATE_TYPE_STATE = 1;
    private const RATE_TYPE_COUNTY = 2;
    private const RATE_TYPE_CITY = 3;
    private const RATE_TYPE_QST = 4;
    private const RATE_TYPE_PST = 5;
    private const RATE_TYPE_GST = 6;
    private const RATE_TYPE_SPECIAL = 7;
    
    public $requiredKeys = [
        'live_key',
		'environment'
    ];

    /**
     * __construct
     *
     * @param  int $langId
     */
    public function __construct(int $langId)
    {
        $this->langId = FatUtility::int($langId);
        if (1 > $this->langId) {
            $this->langId = CommonHelper::getLangId();
        }

        if (false == $this->validateSettings($langId)) { 
            return [
                'status' => false,
                'msg' => $this->error,
            ];
        }

        $isLiveMode = $this->settings['environment'];
        $apiToken = $this->settings['live_key'];
        if (0 == $isLiveMode) {
            $apiToken = $this->settings['sandbox_key'];
        }

        $this->client = TaxJar\Client::withApiKey($apiToken);
        if (0 == $isLiveMode) {
            $this->client->setApiConfig('api_url', TaxJar\Client::SANDBOX_API_URL);
        }

        //$this->client->setApiConfig('headers', ['X-TJ-Expected-Response' => 422]);
        //$this->client->setApiConfig('debug', true);
    }    

    /**
     * getRates
     *
     * @param  array $fromAddress
     * @param  array $toAddress
     * @param  array $itemsArr
     * @param  array $shippingItem
     * @param  int $userId
     * @return array
     */
    public function getRates(array $fromAddress, array $toAddress, array $itemsArr, array $shippingItem, int $userId)
    {
        $params = [
            'from_country' => $fromAddress['country'],
            'from_zip' => $fromAddress['postalCode'],
            'from_state' => $fromAddress['state_code'],
            'from_city' => $fromAddress['city'],
            'from_street' => $fromAddress['line1'] . " " . $fromAddress['line2'],
            'to_country' => $toAddress['country'],
            'to_zip' => $toAddress['postalCode'],
            'to_state' => $toAddress['state_code'],
            'to_city' => $toAddress['city'],
            'to_street' => $toAddress['line1'] . " " . $toAddress['line2'],
            'amount' => $itemsArr[0]['amount'] + $shippingItem[0]['amount'],
            'shipping' => $shippingItem[0]['amount'],
            'line_items' => [
                [
                    'id' => '1',
                    'quantity' => $itemsArr[0]['quantity'],
                    'product_tax_code' => "'" . $itemsArr[0]['taxCode'] . "'",
                    'unit_price' => $itemsArr[0]['amount'],
                    'discount' => 0
                ]
            ]
        ];
        
        try {
            $taxes = $this->client->taxForOrder($params);            
        } catch(exception $e){
            return [
                'status' => false,
                'msg' => $e->getMessage(),
            ];
        }
        
        
        if (!isset($taxes->breakdown)) {
            return [
                'status' => false,
                'msg' => Labels::getLabel("LBL_Tax_could_not_be_calculated_from_TaxJar", $this->langId),
            ];
        }    

        return [
            'status' => true,
            'msg' => Labels::getLabel("MSG_SUCCESS", $this->langId),
            'data' => $this->formatTaxes($taxes)
        ];

    }    

    /**
     * formatTaxes
     *
     * @param  array $taxes
     * @return array
     */
    private function formatTaxes($taxes)
    {
        $formatedTax = [];
        $types = $this->getRateTypesNames();
        $rateTypes = $this->getRateTypesKeys();
       
        foreach ($taxes->breakdown->line_items as $item) {           
            $taxDetails = [];
           
            foreach ($rateTypes as $key=> $name){
                if (isset($item->$name) && $item->$name > 0) {
                    $taxDetails[$types[$key]]['name'] = $types[$key];
                    $taxDetails[$types[$key]]['value'] = $item->$name;
                }
            }  
            $formatedTax[$item->id] = array(    
                'tax' => $taxes->breakdown->combined_tax_rate,      
                'taxDetails' => $taxDetails,
            );
        }    
        
        return $formatedTax;
    }

    /**
     * getCodes
     *
     * @param  int $pageSize
     * @param  int $pageNumber
     * @param  string $filter
     * @param  array $orderBy
     * @param  bool $formatted
     * @return array
     */
    public function getCodes(int $pageSize = null, int $pageNumber = null, string $filter = null, array $orderBy = null, bool $formatted = true)
    {
        if (false == $formatted){
            return $this->client->categories();
        }    
        
        $codesArr = $this->client->categories();
        $formatedCodesArr = [];
        if (!empty($codesArr)) {
            foreach ($codesArr as $code) {
                $formatedCodesArr[$code->product_tax_code] = array(
                    'taxCode' => $code->product_tax_code,
                    'name' => $code->name,
                    'description' => $code->description,
                    'parentTaxCode' => null,
                );
            }
        }

        try {
            return [
                'status' => true,
                'data' => $formatedCodesArr
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'msg' => $e->getMessage()
            ];
        }
    }

    private  function getRateTypesNames() {
        return array(
            static::RATE_TYPE_STATE => Labels::getLabel('LBL_STATE_TAX', $this->langId),
            static::RATE_TYPE_COUNTY => Labels::getLabel('LBL_COUNTRY_TAX', $this->langId),
            static::RATE_TYPE_CITY => Labels::getLabel('LBL_CITY_TAX', $this->langId),
            static::RATE_TYPE_QST => Labels::getLabel('LBL_QST_TAX', $this->langId),
            static::RATE_TYPE_PST => Labels::getLabel('LBL_PST_TAX', $this->langId),
            static::RATE_TYPE_GST => Labels::getLabel('LBL_GST_TAX', $this->langId),
            static::RATE_TYPE_SPECIAL => Labels::getLabel('LBL_SPECIAL_TAX', $this->langId),
        );
    }

    private function getRateTypesKeys(){
        return array(
            static::RATE_TYPE_STATE => 'state_sales_tax_rate',
            static::RATE_TYPE_COUNTY => 'county_tax_rate',
            static::RATE_TYPE_CITY => 'city_tax_rate',
            static::RATE_TYPE_QST => 'qst_tax_rate',
            static::RATE_TYPE_PST => 'pst_tax_rate',
            static::RATE_TYPE_GST => 'gst_tax_rate',
            static::RATE_TYPE_SPECIAL => 'special_tax_rate',
        );
    }
    
}