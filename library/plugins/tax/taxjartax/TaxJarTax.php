<?php

require_once CONF_INSTALLATION_PATH . 'vendor/autoload.php';

class TaxJarTax extends TaxBase
{
    public const KEY_NAME = 'TaxJarTax';
       
    public $error;

    public $langId = 0;
    private $fromAddress = [];
    private $toAddress = [];
    private $client;
    private $params = [];

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
     * @param  array $fromAddress
     * @param  array $toAddress
     */
    public function __construct(int $langId, array $fromAddress = array(), array $toAddress = array())
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

        if (!empty($fromAddress)) {
            $this->setFromAddress($fromAddress);
        }

        if (!empty($toAddress)) {
            $this->setToAddress($toAddress);
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
     * @param  array $itemsArr
     * @param  array $shippingItem
     * @param  int $userId
     * @return array
     */
    public function getRates(array $itemsArr, array $shippingItem, int $userId)
    {
        $this->setItems($itemsArr, $shippingItem, $userId);
        
        try {
            $taxes = $this->client->taxForOrder($this->params);            
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
                'tax' => $taxes->breakdown->tax_collectable,      
                'taxDetails' => $taxDetails,
            );
        }    

        $itemId = $taxes->breakdown->line_items{0}->id;
        if (isset($taxes->breakdown->shipping)) {
            foreach ($rateTypes as $key=> $name){
                if (isset($taxes->breakdown->shipping->$name) && $taxes->breakdown->shipping->$name > 0) {
                    if (isset($formatedTax[$itemId]['taxDetails'][$types[$key]]['value'])) {
                        $formatedTax[$itemId]['taxDetails'][$types[$key]]['value'] = $formatedTax[$itemId]['taxDetails'][$types[$key]]['value'] + $taxes->breakdown->shipping->$name;
                    } else{
                        $formatedTax[$itemId]['taxDetails'][$types[$key]]['name'] = $types[$key];
                        $formatedTax[$itemId]['taxDetails'][$types[$key]]['value'] = $taxes->breakdown->shipping->$name;
                    }
                }
            } 
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

    /**
     * createInvoice
     *
     * @param  array $itemsArr
     * @param  array $shippingItem
     * @param  int $userId
     * @param  string $orderDate
     * @param  string $invoiceNumber
     * @return array
     */
    public function createInvoice(array $itemsArr, array $shippingItem, int $userId, string $txnDateTime, string $invoiceNumber){
        $this->params['transaction_id'] = $invoiceNumber;
        $this->params['transaction_date'] = $this->formatDateTime($txnDateTime);
        $this->setItemsForOrder($itemsArr, $shippingItem);

        try {
            $order = $this->client->createOrder($this->params);                    
        } catch(exception $e){ 
            return [
                'status' => false,
                'msg' => $e->getMessage(),
            ];
        }
      
        return [
            'status' => true,
            'referenceId' => $order->transaction_id,
            'data' => $order
        ];
    }

    private function formatDateTime($txnDateTime){
       return date("Y-m-d\TH:i:s", strtotime($txnDateTime));
    }

    private function setItemsForOrder($itemsArr, $shippingItem) {
                        
        $lineItems = [];
        $salesTax = 0;
        $totalDiscount = 0;
        $totalAmount = 0; 
        //$netAmount = ($childOrderInfo['op_unit_price'] * $quantity) - abs($discount)  + $shippingAmount;
        //348 - 86.12 - 0.1 + 30 = 
        foreach($itemsArr as $item){
            $arr = [
                'id' => $item['itemCode'],
                'quantity' => $item['quantity'],
                'product_identifier' => $item['productName'],
                'description' => $item['description'],
                'unit_price' => $item['amount'],
                'discount' => $item['discount'],
                'sales_tax' => 0 + $item['salesTax'],
            ];

            $totalAmount = $totalAmount + ($item['amount'] * $item['quantity']);
            $salesTax = $salesTax + $item['salesTax'];         
            $totalDiscount = $item['discount'];
            array_push($lineItems, $arr);
        }

        $shipAmount = 0;
        foreach($shippingItem as $item){
            $shipAmount = $shipAmount + $item['amount'];
        }
        
        $this->params['line_items'] = $lineItems;
        $this->params['shipping'] = $shipAmount;        
        $this->params['sales_tax'] = $salesTax;
        $this->params['amount'] = $totalAmount - $totalDiscount + $shipAmount ;
    }

    private function setItems($itemsArr, $shippingItem, $userId) {
        $totalAmount = 0;
        $lineItems = [];
       
        foreach($itemsArr as $item){
            $arr = [
                'id' => $item['itemCode'],
                'quantity' => $item['quantity'],
                'product_tax_code' =>$item['taxCode'],
                'unit_price' => $item['amount'],
                'discount' => 0
            ];
            $totalAmount = $totalAmount + ($item['amount'] * $item['quantity']);
            array_push($lineItems, $arr);
        }

        $shipAmount = 0;
        foreach($shippingItem as $item){
            $shipAmount = $shipAmount + $item['amount'];
        }

        $this->params['line_items'] = $lineItems;
        $this->params['shipping'] = $shipAmount;
        $this->params['amount'] = $totalAmount;
    }

    private function setFromAddress(array $address) {
        
        if (!$this->validateAddress($address)) {
            $this->error = "E_Avalara_Error:_Invalid_From_Address_keys";
            return false;
        }
        $this->params['from_country'] = $address['countryCode'];
        $this->params['from_zip'] = $address['postalCode'];
        $this->params['from_state'] = $address['stateCode'];
        $this->params['from_city'] = $address['city'];
        $this->params['from_street'] = $address['line1'] . " " . $address['line2'];       
        return $this;
    }
    
    private function setToAddress(array $address) { 
        if (!$this->validateAddress($address)) {
            $this->error = "E_Avalara_Error:_Invalid_From_Address_keys";
            return false;
        }
        
        $this->params['to_country'] = $address['countryCode'];
        $this->params['to_zip'] = $address['postalCode'];
        $this->params['to_state'] = $address['stateCode'];
        $this->params['to_city'] = $address['city'];
        $this->params['to_street'] = $address['line1'] . " " . $address['line2'];
        return $this;
    }
    
    private function validateAddress(array $address) {
        if (!is_array($address)) {
            return false;
        }
        
        $requiredKeys = ['line1', 'line2', 'city', 'state', 'postalCode', 'country' , 'stateCode', 'countryCode'];
        return !array_diff($requiredKeys, array_keys($address));
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
            static::RATE_TYPE_STATE => 'state_amount',
            static::RATE_TYPE_COUNTY => 'county_amount',
            static::RATE_TYPE_CITY => 'city_amount',
            static::RATE_TYPE_QST => 'qst',
            static::RATE_TYPE_PST => 'pst',
            static::RATE_TYPE_GST => 'gst',
            static::RATE_TYPE_SPECIAL => 'special_district_amount',
        );
    }
    
}