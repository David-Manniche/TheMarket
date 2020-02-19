<?php

require_once CONF_INSTALLATION_PATH . 'library/avalara/autoload.php';

use Twilio\Rest\Client;

class Avalaratax extends TaxBase {

    public const KEY_NAME = 'AvalaraTax';

    private $settings = [];
    private $langId = 0;
    private $_client;
    private $_companyCode;
    private $_fromAddress = [];
    private $_toAddress = [];
    private $_products = [];
    private $_productsShipping = [];
    private $_customerCode;
    private $_exemptionCode;
    private $_discountAmount;
    private $_invoiceId;
    private $_taxApiResponse;
    private $_invoiceDate;

    public function __construct($langId) {
        $this->langId = FatUtility::int($langId);
        if (1 > $this->langId) {
            $this->langId = CommonHelper::getLangId();
        }

        $this->settings = $this->getSettings();

        $this->validateSettings();

        $this->_client = new Avalara\AvaTaxClient(FatApp::getConfig('CONF_WEBSITE_NAME_' . $langId), FatApp::getConfig('CONF_YOKART_VERSION'), $_SERVER['HTTP_HOST'], $this->settings['environment']);
        $this->_client->withCatchExceptions(false);

        $this->_client->withLicenseKey($this->settings['account_number'], $this->settings['license_key']);
        $this->_companyCode = $this->settings['company_code'];
    }

    private function validateSettings() {

        $requiredKeyArr = ['account_number', 'commit_transaction', 'company_code', 'environment', 'license_key'];
        foreach ($requiredKeyArr as $key) {
            if (!array_key_exists($key, $this->settings)) {
                $this->error = Labels::getLabel('MSG_SETTINGS_NOT_UPDATED', $this->langId);
                return false;
            }
        }
    }

    public function checkCredentials() {

        $p = $this->_client->ping();
        return $p->authenticated;
    }

    public function getRates($createTxn = false) {

        $formatedTax = [];
        
        
        

        $taxes = $this->calculateTaxes($createTxn);


        if ($taxes) {
            foreach ($taxes->lines as $line) {
                $taxDetails = [];
                foreach ($line->details as $lineTaxdetail) {
                    $taxName = 'L_' . str_replace(' ', '_', $lineTaxdetail->taxName);
                    if (isset($taxDetails[$taxName])) {
                        $taxDetails[$taxName] += $lineTaxdetail->tax;
                    } else {
                        $taxDetails[$taxName] = $lineTaxdetail->tax;
                    }
                }
                $formatedTax[$line->itemCode] = array(
                    'tax' => $line->tax,
                    'taxDetails' => $taxDetails,
                );
            }
        }

        return $formatedTax;



        return [
            'status' => true,
            'msg' => Labels::getLabel("MSG_SUCCESS", $this->langId),            
            'data' => $formatedTax
        ];
    }

    public function validateAddress($line1, $line2, $line3, $city, $state, $postalCode, $country) {

        $addrResult = $this->checkAddress($line1, $line2, $line3, $city, $state, $postalCode, $country);

        if (!$addrResult) {          
            return false;
        }

        if (!isset($addrResult->messages)) {
            return true;
        }
        return false;
    }

    public function getSuggestedAddress($line1, $line2, $line3, $city, $state, $postalCode, $country) {

        $addrResult = $this->checkAddress($line1, $line2, $line3, $city, $state, $postalCode, $country);

        if (!$addrResult) {         
            return false;
        }

        if (isset($addrResult->messages)) {
            $this->error = current($addrResult->messages);
            return false;
        }

        return isset($addrResult->validatedAddresses) ? $addrResult->validatedAddresses : [];
    }

    private function checkAddress($line1, $line2, $line3, $city, $state, $postalCode, $country) {


        try {
            $addressInfoObj = new Avalara\AddressValidationInfo();
            $addressInfoObj->line1 = $line1;
            $addressInfoObj->line2 = $line2;
            $addressInfoObj->line3 = $line3;
            $addressInfoObj->city = $city;
            $addressInfoObj->region = $state;
            $addressInfoObj->country = $country;
            $addressInfoObj->postalCode = $postalCode;
            if (!empty($latitude)) {
                $addressInfoObj->latitude = $latitude;
            }

            if (!empty($longitude)) {
                $addressInfoObj->longitude = $longitude;
            }

            $response = $this->_client->resolveAddressPost($addressInfoObj);

            return $response;
        } catch (\Exception $e) {
            $this->error = "E_Avalara_Error:" . $e->getMessage();
            return false;
        }
    }

    public function setFromAddress($line1, $line2, $line3, $city, $region, $postalCode, $country) {
        if (empty($line1) || empty($city) || empty($region) || empty($postalCode) || empty($country)) {
            throw new Exception("E_Avalara_Error:_Invalid_From_Address");
        }
        $this->_fromAddress = [
            'line1' => $line1,
            'line2' => $line2,
            'line3' => $line3,
            'city' => $city,
            'region' => $region,
            'postalCode' => $postalCode,
            'country' => $country
        ];

        return $this;
    }

    public function setToAddress($line1, $line2, $city, $region, $postalCode, $country) {
        if (empty($line1) || empty($city) || empty($region) || empty($postalCode) || empty($country)) {
            throw new Exception("E_Avalara_Error:_Invalid_To_Address");
        }
        $this->_toAddress = [
            'line1' => $line1,
            'line2' => $line2,
            'city' => $city,
            'region' => $region,
            'postalCode' => $postalCode,
            'country' => $country
        ];
        return $this;
    }

    public function setProducts($products) {
        // need to check array?
        $this->_products = $products;

        return $this;
    }

    public function setProductsShipping($productsShipping) {
        // need to check array?
        $this->_productsShipping = $productsShipping;

        return $this;
    }

    public function setCustomerCode($customerCode) {
        /**
         *      * @param string        $customerCode  The customer code for this transaction
         */
        $this->_customerCode = $customerCode;
        return $this;
    }

    public function setDiscountAmount($discount) {
        $this->_discountAmount = floatval($discount);
        return $this;
    }

    public function setInvoiceId($invoiceId) {
        $this->_invoiceId = $invoiceId;
        return $this;
    }

    public function setInvoiceDate($invoiceDate) {
        $this->_invoiceDate = $invoiceDate;
        return $this;
    }

//
//    public function getTaxes($createTxn = false) {
//
//        $formatedTax = [];
//
//        $taxes = $this->calculateTaxes($createTxn);
//
//
//        if ($taxes) {
//            foreach ($taxes->lines as $line) {
//                $taxDetails = [];
//                foreach ($line->details as $lineTaxdetail) {
//                    $taxName = 'L_' . str_replace(' ', '_', $lineTaxdetail->taxName);
//                    if (isset($taxDetails[$taxName])) {
//                        $taxDetails[$taxName] += $lineTaxdetail->tax;
//                    } else {
//                        $taxDetails[$taxName] = $lineTaxdetail->tax;
//                    }
//                }
//                $formatedTax[$line->itemCode] = array(
//                    'tax' => $line->tax,
//                    'taxDetails' => $taxDetails,
//                );
//            }
//        }
//
//        return $formatedTax;
//    }

    /**

     * @param string        $createTxn    If true is a permanent document and is recorded in AvaTax
     * @param string|null   $txnDate      The datetime of the transaction, defaults to current time when null (Format: Y-m-d)
     */
    private function calculateTaxes($createTxn = false) {

        $invoiceType = Avalara\DocumentType::C_SALESORDER;

        if ($createTxn) {
            $invoiceType = Avalara\DocumentType::C_SALESINVOICE;
        }

        if (empty($this->_customerCode)) {            
            throw new Exception('E_Avalara_Error:_CustomerCode_Is_Not_Set');
        }

        if (1 > count($this->_products)) {
            throw new Exception('E_Avalara_Error:_To_Items_Is_Not_Set');
        }

        if (1 > count($this->_fromAddress)) {
            throw new Exception('E_Avalara_Error:_From_Address_Is_Not_Set');
        }

        if (1 > count($this->_toAddress)) {
            throw new Exception('E_Avalara_Error:_To_Address_Is_Not_Set');
        }

        try {

            $tb = new Avalara\TransactionBuilder($this->_client, $this->_companyCode, $invoiceType, $this->_customerCode, $this->_invoiceDate);
            if ($this->settings['commit_transaction'] == 1) {
                $tb->withCommit();
            }

            foreach ($this->_products as $itemKey => $product) {
                $tb->withLine($product['amount'], $product['quantity'], $product['itemCode'], $product['taxCode']);
                if(!empty($this->_discountAmount)){
                    $tb->withItemDiscount(true);
                }                        
                
                $fromAddress = $this->_fromAddress;
                $toAddress = $this->_toAddress;
                
                if ($product['isDigital'] == 1) {
                    /*
                    * To address will be seller address for tax calulation in case of digital
                    */
                    $toAddress = $fromAddress;
                }

                $tb->withLineAddress(Avalara\TransactionAddressType::C_SHIPFROM, $fromAddress['line1'], $fromAddress['line2'], null, $fromAddress['city'], $fromAddress['region'], $fromAddress['postalCode'], $fromAddress['country'])
                        ->withLineAddress(Avalara\TransactionAddressType::C_SHIPTO, $toAddress['line1'], $toAddress['line2'], null, $toAddress['city'], $toAddress['region'], $toAddress['postalCode'], $toAddress['country']);
            }

            if (0 < $this->_discountAmount) {
                $tb->withDiscountAmount($this->_discountAmount);
            }

            if (!empty($this->_invoiceId)) {
                $tb->withTransactionCode($this->_invoiceId);
            }

            $this->_taxApiResponse = $tb->create();

            return $this->_taxApiResponse;
        } catch (\Exception $e) {
            throw new Exception('E_Avalara_Error:_' . $e->getMessage());
        }
    }

    public function withLicenseKey($accountId, $licenseKey) {
        if (empty($accountId) || empty($licenseKey)) {
            throw new Exception('E_Avalara_Error:_AccountId_and_licenseKey_are_mandatory_fields!');
        }
        $this->_client->withLicenseKey($accountId, $licenseKey);
        return $this;
    }

    public function withSecurity($username, $password) {
        if (empty($username) || empty($password)) {
            throw new Exception('E_Avalara_Error:_Username_and-password_are_mandatory_fields!');
        }
        $this->_client->withSecurity($username, $password);
        return $this;
    }

    public function getTaxApiActualResponse() {
        return $this->_taxApiResponse;
    }

}
