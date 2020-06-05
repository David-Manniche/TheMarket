<?php
use Guzzle\Http\Exception\ClientErrorResponseException;

trait ShipStationFunctions
{
    
    /**
     * getResponse
     *
     * @return mixed
     */
    public function getResponse(bool $convertToArray = true)
    {
        if (empty($this->resp)) {
            return false;
        }
        $response = (string) $this->resp->getBody();
        return (true === $convertToArray ? json_decode($response, true) : $response);
    }
    
    /**
     * getFormatedError
     *
     * @return mixed
     */
    public function getFormatedError()
    {
        $exceptionMsg = isset($this->error['ExceptionMessage']) ? ' ' . $this->error['ExceptionMessage'] : '';
        return (isset($this->error['Message']) ? $this->error['Message'] : $this->error) . $exceptionMsg;
    }
    
    /**
     * carrierList
     *
     * @return object
     */
    private function carrierList(): object
    {
        return $this->shipStation->Carriers->getList();
    }
        
    /**
     * shippingRates
     *
     * @param  array $requestParam
     * @return object
     */
    private function shippingRates(array $requestParam): object
    {
        return $this->shipStation->Shipments->getRates($requestParam);
    }
        
    /**
     * createOrder
     *
     * @param  object $requestParam
     * @return object
     */
    private function createOrder(object $requestParam): object
    {
        return $this->shipStation->orders->createOrder($requestParam);
    }

    /**
     * createLabel
     *
     * @param  object $requestParam
     * @return object
     */
    private function createLabel(object $requestParam): object
    {
        $testLabel = isset($this->settings['test_label']) ? $this->settings['test_label'] : false;
        return $this->shipStation->orders->createLabelForOrder($requestParam, $testLabel);
    }
        
    /**
     * doRequest
     *
     * @param  int $requestType
     * @param  mixed $requestParam
     * @param  bool $formatError
     * @return bool
     */
    public function doRequest(int $requestType, $requestParam = [], bool $formatError = true): bool
    {
        try {
            switch ($requestType) {
                case self::REQUEST_CARRIER_LIST:
                    $this->resp = $this->carrierList();
                    break;
                case self::REQUEST_SHIPPING_RATES:
                    $this->resp = $this->shippingRates($requestParam);
                    break;
                case self::REQUEST_CREATE_ORDER:
                    $this->resp = $this->createOrder($requestParam);
                    break;
                case self::REQUEST_CREATE_LABEL:
                    $this->resp = $this->createLabel($requestParam);
                    break;
            }
            return true;
        } catch (ClientErrorResponseException $e) {
            // Display a very generic error to the user, and maybe send
            $this->resp = $e->getResponse();
            $this->error = $this->getResponse();
            // yourself an email
        } catch (GuzzleHttp\Exception\ClientException $e) {
            // Display a very generic error to the user, and maybe send
            $this->resp = $e->getResponse();
            $this->error = $this->getResponse();
            // yourself an email
        } catch (GuzzleHttp\Exception\ServerException $e) {
            // Display a very generic error to the user, and maybe send
            $this->resp = $e->getResponse();
            $this->error = $this->getResponse();
            // yourself an email
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            // Display a very generic error to the user, and maybe send
            $this->resp = $e->getResponse();
            $this->error = $this->getResponse();
            // yourself an email
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $this->error = $e->getMessage();
        } catch (Error $e) {
            // Handle error
            $this->error = $e->getMessage();
        }

        $this->error =  (true === $formatError ? $this->getFormatedError() : $this->error);
        return false;
    }
}