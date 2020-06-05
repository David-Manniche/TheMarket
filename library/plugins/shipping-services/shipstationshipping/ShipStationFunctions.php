<?php

trait ShipStationFunctions
{
    /**
     * getResponse
     *
     * @param  bool $convertToArray
     * @return mixed
     */
    public function getResponse(bool $convertToArray = true)
    {
        if (empty($this->resp)) {
            return false;
        }

        return (true === $convertToArray ? json_decode($this->resp, true) : $this->resp);
    }
    
    /**
     * formatError
     *
     * @return mixed
     */
    public function formatError()
    {
        $exceptionMsg = isset($this->error['ExceptionMessage']) ? ' ' . $this->error['ExceptionMessage'] : '';
        return (isset($this->error['Message']) ? $this->error['Message'] : $this->error) . $exceptionMsg;
    }
    
    /**
     * call - Call ShipStation
     *
     * @return void
     */
    private function call(string $requestType, array $requestParam = [])
    {
        $ch = curl_init();
        $authToken = base64_encode($this->settings['api_key'] . ':' . $this->settings['api_secret_key']);
        $request = [
            CURLOPT_URL => self::PRODUCTION_URL . $this->endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $requestType,
            CURLOPT_HTTPHEADER => [
                'Host: ' . self::HOST,
                'Authorization: Basic ' . $authToken
            ],
        ];

        if (!empty($requestParam)) {
            $requestParam = json_encode($requestParam);
            $request[CURLOPT_POSTFIELDS] = $requestParam;
            $request[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
        }

        curl_setopt_array($ch, $request);
        
        $this->resp = curl_exec($ch);
        if (false === $this->resp) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);
        return true;
    }
        
    /**
     * get - To hit get request
     *
     * @return void
     */
    private function get(): bool
    {
        return $this->call('GET');
    }

    /**
     * post - To hit post request
     *
     * @return void
     */
    private function post(array $requestParam): bool
    {
        return $this->call('POST', $requestParam);
    }

    /**
     * carrierList
     *
     * @return bool
     */
    private function carrierList(): bool
    {
        $this->endpoint = 'carriers';
        return $this->get();
    }
        
    /**
     * shippingRates
     *
     * @param  array $requestParam
     * @return bool
     */
    private function shippingRates(array $requestParam): bool
    {
        $this->endpoint = 'shipments/getrates';
        return $this->post($requestParam);
    }
        
    /**
     * createOrder
     *
     * @param  array $requestParam
     * @return bool
     */
    private function createOrder(array $requestParam): bool
    {
        $this->endpoint = 'orders/createorder';
        return $this->post($requestParam);
    }

    /**
     * createLabel
     *
     * @param  array $requestParam
     * @return bool
     */
    private function createLabel(array $requestParam): bool
    {
        $this->endpoint = 'orders/createlabelfororder';
        $requestParam['testLabel'] = isset($this->settings['test_label']) && 0 < $this->settings['test_label'] ? true : false;
        return $this->post($requestParam);
    }
        
    /**
     * doRequest
     *
     * @param  int $requestType
     * @param  mixed $requestParam
     * @param  bool $formatError
     * @return bool
     */
    private function doRequest(int $requestType, $requestParam = [], bool $formatError = true): bool
    {
        try {
            switch ($requestType) {
                case self::REQUEST_CARRIER_LIST:
                    $this->carrierList();
                    break;
                case self::REQUEST_SHIPPING_RATES:
                    $this->shippingRates($requestParam);
                    break;
                case self::REQUEST_CREATE_ORDER:
                    $this->createOrder($requestParam);
                    break;
                case self::REQUEST_CREATE_LABEL:
                    $this->createLabel($requestParam);
                    break;
            }
            
            if (array_key_exists('Message', $this->getResponse(true))) {
                $this->error = (true === $formatError) ? $this->getResponse(true) : $this->resp;
                if (true === $formatError) {
                    $this->error = $this->formatError();
                }
                return false;
            }

            return true;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        } catch (Error $e) {
            $this->error = $e->getMessage();
        }

        $this->error =  (true === $formatError ? $this->formatError() : $this->error);
        return false;
    }
}
