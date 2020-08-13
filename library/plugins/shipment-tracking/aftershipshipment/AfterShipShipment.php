<?php
class AfterShipShipment extends ShipmentTrackingBase
{
    public const KEY_NAME = 'AfterShipShipment';
    public $settings = [];
    public $langId = 0;

    public function __construct($langId = 0)
    {
        $this->langId = FatUtility::int($langId);
        if (1 > $this->langId) {
            $this->langId = CommonHelper::getLangId();
        }

        if (false == $this->validateSettings($this->langId)) {
            return [
                'status' => false,
                'msg' => $this->error
            ];
        }
    }
	
	public function getTrackingInfo($trackingNumber, $courier)
	{	
		$url = 'https://api.aftership.com/v4/trackings/'.$courier.'/'.$trackingNumber;    
		$requestHeaders = array(
						"aftership-api-key:" . $this->settings['api_key'],
						"Content-Type:" . 'application/json'
					);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
		$rslt = curl_exec($ch);
		curl_close($ch); 
		$response = json_decode($rslt, true);
        if($response['meta']['code'] == 200){
            $response['data']['tracking']['checkpoints'] = array_reverse($response['data']['tracking']['checkpoints']);
        }
		return $response;
	}
    
    public function getTrackingCouriers()
	{	
		$url = 'https://api.aftership.com/v4/couriers';    
		$requestHeaders = array(
						"aftership-api-key:" . $this->settings['api_key'],
						"Content-Type:" . 'application/json'
					);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
		$rslt = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($rslt, true);
		return $response;	
	}
    
    
}
