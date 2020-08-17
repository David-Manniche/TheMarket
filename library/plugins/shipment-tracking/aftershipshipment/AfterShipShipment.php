<?php
use Curl\Curl;

class AfterShipShipment extends ShipmentTrackingBase
{
    public const KEY_NAME = __CLASS__;
    private const PROD_URL = 'https://api.aftership.com/v4/';
    public $requiredKeys = ['api_key'];
    private $response = [];
    
    public function __construct(int $langId)
    {
        $this->langId = $langId;
    }
    
    /**
     * init
     *
     * @return bool
     */
    public function init(): bool
    {
        if (false == $this->validateSettings($this->langId)) {
            return false;
        }
        return true;
    }
	
	public function getTrackingInfo(string $trackingNumber, string $courierCode): bool
	{	
        $url = self::PROD_URL . 'trackings/'.$courierCode.'/'.$trackingNumber;
        $curl = new Curl();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl->setHeader("aftership-api-key", $this->settings['api_key']);
        $curl->setHeader("Content-Type", "application/json");
        $curl->get($url);
  
        if ($curl->error) { 
            $this->error = $curl->errorCode . ' : ' . $curl->errorMessage;
            $this->error .= !empty($curl->getResponse()->error) ? $curl->getResponse()->error : '';
            //return false;
        } 
         
        $response = json_decode(json_encode($curl->getResponse()), true); 

        if(isset($response['meta']['code']) && $response['meta']['code'] == 200){
            $response['data']['tracking']['checkpoints'] = array_reverse($response['data']['tracking']['checkpoints']);
        }

        $this->response = $response;
        return true;    
	}
    
    public function getTrackingCouriers()
	{	
        $url = self::PROD_URL . 'couriers';
        $curl = new Curl();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl->setHeader("aftership-api-key", $this->settings['api_key']);
        $curl->setHeader("Content-Type", "application/json");
        $curl->get($url);
        
        if ($curl->error) {
            $this->error = $curl->errorCode . ' : ' . $curl->errorMessage;
            $this->error .= !empty($curl->getResponse()->error) ? $curl->getResponse()->error : '';
            return false;
        }

        $response =  json_decode(json_encode($curl->getResponse()), true);
        $couriers = [];
        if(isset($response['meta']['code']) && $response['meta']['code'] == 200){
            foreach($response['data']['couriers'] as $key=>$courier){
                  $couriers[$courier['slug']] = $courier['name'];
            }
        }
        $this->response = $couriers;
        return true;
	}
    
    public function getResponse()
    {
        return $this->response;
    }
    
}
