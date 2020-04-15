<?php

require_once CONF_INSTALLATION_PATH . 'library/avalara/autoload.php';

class Avalaratax extends TaxBase
{
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
    private $_invoiceId;
    private $_taxApiResponse;
    private $_invoiceDate;

    public function __construct($langId)
    {
        $this->langId = FatUtility::int($langId);
        if (1 > $this->langId) {
            $this->langId = CommonHelper::getLangId();
        }

        $this->settings = $this->getSettings();

        $this->validateSettings();

        $environment = FatUtility::int($this->settings['environment']) == 1 ? 'production' : 'sandbox';

        $this->_client = new Avalara\AvaTaxClient(FatApp::getConfig('CONF_WEBSITE_NAME_' . $langId), FatApp::getConfig('CONF_YOKART_VERSION'), $_SERVER['HTTP_HOST'], $environment);
        $this->_client->withLicenseKey($this->settings['account_number'], $this->settings['license_key']);
        $this->_client->withCatchExceptions(false);
        $this->_companyCode = $this->settings['company_code'];
    }

    public function checkCredentials($account_number, $license_key)
    {
        $this->_client->withLicenseKey($account_number, $license_key);
        $p = $this->_client->ping();
        return $p->authenticated;
    }

    public function getRates($fromAddress, $toAddress, $itemsArr, $shippingItem, $userId)
    {
        try {
            $this->setFromAddress($fromAddress)
                    ->setToAddress($toAddress)
                    ->setProducts($itemsArr)
                    ->setProductsShipping($shippingItem)
                    ->setCustomerCode($userId);

            $taxes = $this->calculateTaxes();
        } catch (\Exception $e) {
            return [
                'status' => false,
                'msg' => Labels::getLabel($e->getMessage(), $this->langId),
            ];
        }

        return [
            'status' => true,
            'msg' => Labels::getLabel("MSG_SUCCESS", $this->langId),
            'data' => $this->formatTaxes($taxes)
        ];
    }

    public function createInvoice($fromAddress, $toAddress, $itemsArr, $shippingItem, $userId, $invoiceDate, $InvoiceNo)
    {
        try {
            $this->setFromAddress($fromAddress)
                    ->setToAddress($toAddress)
                    ->setProducts($itemsArr)
                    ->setProductsShipping($shippingItem)
                    //->setCustomerCode($userId)
                    ->setInvoiceId($InvoiceNo)
                    ->setInvoiceDate($invoiceDate);

            $taxes = $this->calculateTaxes(true);
        } catch (\Exception $e) {
            return [
                'status' => false,
                'msg' => Labels::getLabel($e->getMessage(), $this->langId),
            ];
        }

        return [
            'status' => true,
            'msg' => Labels::getLabel("MSG_SUCCESS", $this->langId),
            'data' => $this->formatTaxes($taxes)
        ];
    }

    /*
     * @param string $filter A filter statement to identify specific records to retrieve. For more information on filtering, see [Filtering in REST](http://developer.avalara.com/avatax/filtering-in-rest/).
     * @param int $pageSize If nonzero, return no more than this number of results. Used with `$pageNumber` to provide pagination for large datasets. Unless otherwise specified, the maximum number of records that can be returned from an API call is 1,000 records.
     * @param int $pageNumber If nonzero, skip this number of results before returning data. Used with `$pageSize` to provide pagination for large datasets.
     * @param string $orderBy A comma separated list of sort statements in the format `(fieldname) [ASC|DESC]`, for example `id ASC`.
     */

    public function getCodes($filter = null, $pageSize = null, $pageNumber = null, $orderBy = null, $formatted = true)
    {
        if (!empty($filter)) {
            $filter = "description contains '$filter' or taxCode contains '$filter'";
        }

        $recordCount = 0;
       
        if (false == $formatted){
            return $this->_client->listTaxCodes($filter, $pageSize, $pageNumber, $orderBy);
        }

        $codesArr = $this->_client->listTaxCodes($filter, $pageSize, $pageNumber, $orderBy);

        $formatedCodesArr = [];
        if (!empty($codesArr)) {
            foreach ($codesArr->value as $code) {
                $formatedCodesArr[$code->id] = array(
                    'taxCode' => $code->taxCode,
                    'description' => $code->description,
                    'parentTaxCode' => $code->parentTaxCode ?? null,
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

    public function getTaxApiActualResponse()
    {
        return $this->_taxApiResponse;
    }

    private function validateSettings()
    {
        $requiredKeyArr = ['account_number', 'commit_transaction', 'company_code', 'environment', 'license_key'];
        foreach ($requiredKeyArr as $key) {
            if (!array_key_exists($key, $this->settings)) {
                throw new Exception(Labels::getLabel('MSG_SETTINGS_NOT_UPDATED', $this->langId));
            }
        }
    }

    private function setFromAddress($address)
    {
        if (!$this->validateAddressArrKeys($address)) {
            throw new Exception("E_Avalara_Error:_Invalid_From_Address_keys");
        }
        $this->_fromAddress = $address;

        return $this;
    }

    private function setToAddress($address)
    {
        if (!$this->validateAddressArrKeys($address)) {
            throw new Exception("E_Avalara_Error:_Invalid_To_Address_keys");
        }

        $this->_toAddress = $address;

        return $this;
    }

    private function setProducts($products)
    {
        if (!$this->validateitemArrKeys(current($products))) {
            throw new Exception("E_Avalara_Error:_Invalid_To_Product_Array_Keys");
        }

        $this->_products = $products;

        return $this;
    }

    private function setProductsShipping($productsShipping)
    {
        if (!$this->validateitemArrKeys(current($productsShipping))) {
            throw new Exception("E_Avalara_Error:_Invalid_To_Product_Shipping_Array_keys");
        }

        $this->_productsShipping = $productsShipping;

        return $this;
    }

    private function setCustomerCode($customerCode)
    {
        /**
         *      * @param string        $customerCode  The customer code for this transaction
         */
        $this->_customerCode = $customerCode;
        return $this;
    }

    private function setInvoiceId($invoiceId)
    {
        if (empty($invoiceId)) {
            throw new Exception("E_Avalara_Error:_Invoice_Id_Empty");
        }
        $this->_invoiceId = $invoiceId;
        return $this;
    }

    private function setInvoiceDate($invoiceDate)
    {
        if (empty($invoiceDate)) {
            throw new Exception("E_Avalara_Error:_Invoice_Date_Empty");
        }
        $this->_invoiceDate = date(DATE_W3C, strtotime($invoiceDate));
        return $this;
    }

    /**

     * @param string        $createTxn    If true is a permanent document and is recorded in AvaTax
     * @param string|null   $txnDate      The datetime of the transaction, defaults to current time when null (Format: Y-m-d)
     */
    private function calculateTaxes($createTxn = false)
    {
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
            if (FatUtility::int($this->settings['commit_transaction']) == 1) {
                $tb->withCommit();
            }

            foreach ($this->_products as $itemKey => $item) {
                $tb->withLine($item['amount'], $item['quantity'], $item['itemCode'], $item['taxCode']);
                $tb->withLineDescription(Labels::getLabel('LBL_Product', $this->langId));

                $fromAddress = $this->_fromAddress;
                $toAddress = $this->_toAddress;

                $tb->withLineAddress(Avalara\TransactionAddressType::C_SHIPFROM, $fromAddress['line1'], $fromAddress['line2'], null, $fromAddress['city'], $fromAddress['state'], $fromAddress['postalCode'], $fromAddress['country'])
                        ->withLineAddress(Avalara\TransactionAddressType::C_SHIPTO, $toAddress['line1'], $toAddress['line2'], null, $toAddress['city'], $toAddress['state'], $toAddress['postalCode'], $toAddress['country']);
            }


            foreach ($this->_productsShipping as $itemKey => $item) {
                $tb->withLine($item['amount'], $item['quantity'], $item['itemCode'], $item['taxCode']);
                $tb->withLineDescription(Labels::getLabel('LBL_Shipping', $this->langId));

                $fromAddress = $this->_fromAddress;
                $toAddress = $this->_toAddress;

                $tb->withLineAddress(Avalara\TransactionAddressType::C_SHIPFROM, $fromAddress['line1'], $fromAddress['line2'], null, $fromAddress['city'], $fromAddress['state'], $fromAddress['postalCode'], $fromAddress['country'])
                        ->withLineAddress(Avalara\TransactionAddressType::C_SHIPTO, $toAddress['line1'], $toAddress['line2'], null, $toAddress['city'], $toAddress['state'], $toAddress['postalCode'], $toAddress['country']);
            }

            if (!empty($this->_invoiceId)) {
                $tb->withTransactionCode($this->_invoiceId);
            }

            $this->_taxApiResponse = $tb->create();

            return $this->_taxApiResponse;
        } catch (\Exception $e) {
            $errorMsgObj = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exception('E_Avalara_Error:_' . str_replace(" ", "_", $errorMsgObj->error->message));
        }
    }

    private function withLicenseKey($accountId, $licenseKey)
    {
        if (empty($accountId) || empty($licenseKey)) {
            throw new Exception('E_Avalara_Error:_AccountId_and_licenseKey_are_mandatory_fields!');
        }
        $this->_client->withLicenseKey($accountId, $licenseKey);
        return $this;
    }

    private function withSecurity($username, $password)
    {
        if (empty($username) || empty($password)) {
            throw new Exception('E_Avalara_Error:_Username_and-password_are_mandatory_fields!');
        }
        $this->_client->withSecurity($username, $password);
        return $this;
    }

    private function validateAddressArrKeys($address)
    {
        if (!is_array($address)) {
            return false;
        }

        $requiredKeys = ['line1', 'line2', 'city', 'state', 'postalCode', 'country'];
        return !array_diff($requiredKeys, array_keys($address));
    }

    private function validateitemArrKeys($item)
    {
        if (!is_array($item)) {
            return false;
        }
        $requiredKeys = ['amount', 'quantity', 'itemCode', 'taxCode'];

        return !array_diff($requiredKeys, array_keys($item));
    }

    private function formatTaxes($taxes)
    {
        $formatedTax = [];
        foreach ($taxes->lines as $line) {
            $taxDetails = [];
            foreach ($line->details as $lineTaxdetail) {
                $taxName = $lineTaxdetail->taxName;                
                if (isset($taxDetails[$taxName])) {
                    $taxDetails[$taxName]['value'] += $lineTaxdetail->tax;
                } else {
                    $taxDetails[$taxName]['value'] = $lineTaxdetail->tax;
                    $taxDetails[$taxName]['name'] = $taxName;
                }
            }
            $formatedTax[$line->itemCode] = array(
                'tax' => $line->tax,
                'taxDetails' => $taxDetails,
            );
        }

        return $formatedTax;
    }
}
