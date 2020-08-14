<?php
class ShipmentTracking
{    
    private $keyName; 
    private $shipmentTracking;
   // private $error;
    /**
     * __construct
     *
     * @return void
     */   
    public function __construct()
    {   
        $this->init();
    }
    
    /**
     * init
     *
     * @return void
     */
    private function init()
    {
        $plugin = new Plugin();
        $this->keyName = $plugin->getDefaultPluginKeyName(Plugin::TYPE_SHIPMENT_TRACKING);
        $langId = CommonHelper::getLangId();
        $this->shipmentTracking = PluginHelper::callPlugin($this->keyName, [$langId], $error, $langId);
        if (false === $this->shipmentTracking) {
            FatUtility::dieJsonError($error);
        }

        if (false === $this->shipmentTracking->init()) {
            FatUtility::dieJsonError($this->shipmentTracking->getError());
        }
    }
    
    public function getTrackingInfo($trackingNumber, $courierCode)
    {
        if (false === $this->shipmentTracking->getTrackingInfo($trackingNumber, $courierCode)) {
            $this->error = $this->shipmentTracking->getError();
            return false;
        }        
        return $this->shipmentTracking->getResponse();
    }
    
    public function getTrackingCouriers()
    {   
        if (false === $this->shipmentTracking->getTrackingCouriers()) {
            $this->error = $this->shipmentTracking->getError();
            return false;
        }
        return $this->shipmentTracking->getResponse();
    }
    
    public function getError()
    {
        return $this->error;
    }
	
}
