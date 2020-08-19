<?php
use Curl\Curl;

class AfterShipShipment extends ShipmentTrackingBase
{
    public const KEY_NAME = __CLASS__;
    private const API_URI = 'https://api.aftership.com/v4/';
    public $requiredKeys = ['api_key'];
    private $response = [];
        
    /**
     * __construct
     *
     * @param  int $langId
     * @return void
     */
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
        return $this->validateSettings($this->langId);
    }
    
    /**
     * doRequest
     *
     * @param  string $url
     * @return bool
     */
    private function doRequest(string $url): bool
    {
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
        /* Converting Multidimensional object elements to array. */
        $this->response = json_decode(json_encode($curl->getResponse()), true); 
        return true; 
    }
        
    /**
     * getTrackingCouriers
     *
     * @return void
     */
    public function getTrackingCouriers(): bool
	{	
        $url = self::API_URI . 'couriers';

        if (false === $this->doRequest($url)) {
            return false;
        } 

        $couriers = [];
        if(isset($this->response['meta']['code']) && $this->response['meta']['code'] == 200){
            foreach($this->response['data']['couriers'] as  $courier){
                  $couriers[$courier['slug']] = $courier['name'];
            }
        }
        
        $this->response = $couriers;
        return true;
	}
		
	/**
	 * getTrackingInfo
	 *
	 * @param  string $trackingNumber
	 * @param  string $courierCode
	 * @return bool
	 */
	public function getTrackingInfo(string $trackingNumber, string $courierCode): bool
	{	
        $url = self::API_URI . 'trackings/'.$courierCode.'/'.$trackingNumber;

        if (false === $this->doRequest($url)) {
            return false;
        } 

        if(isset($this->response['meta']['code']) && $this->response['meta']['code'] == 200){
            $this->response['data']['tracking']['checkpoints'] = array_reverse($this->response['data']['tracking']['checkpoints']);
        }
        return true;    
	}
        
    /**
     * getResponse
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }
    
}