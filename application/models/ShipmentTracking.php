<?php
class ShipmentTracking
{    

    public function getTrackingInfo($trackingNumber, $courier, $langId)
    {
        $shipmentTracking = FatApp::getConfig('CONF_DEFAULT_PLUGIN_' . Plugin::TYPE_SHIPMENT_TRACKING, FatUtility::VAR_INT, 0);
        if (empty($shipmentTracking) || 1 > $langId) {		
			Message::addErrorMessage(Labels::getLabel('MSG_INVALID_REQUEST', $langId));
			FatUtility::dieWithError( Message::getHtml() );
        }

        $pluginKey = Plugin::getAttributesById($shipmentTracking, 'plugin_code');
        if (1 > Plugin::isActive($pluginKey)) {
			Message::addErrorMessage(Labels::getLabel('MSG_PLUGIN_NOT_ACTIVE', $langId));
			FatUtility::dieWithError( Message::getHtml() );
        }
        
        require_once CONF_PLUGIN_DIR . '/shipment-tracking/' . strtolower($pluginKey) . '/' . $pluginKey . '.php';

        $tracking = new $pluginKey($langId);
        $response = $tracking->getTrackingInfo($trackingNumber, $courier);
        return $response;
    }
    
    public function getTrackingCouriers($langId)
    {
        $shipmentTracking = FatApp::getConfig('CONF_DEFAULT_PLUGIN_' . Plugin::TYPE_SHIPMENT_TRACKING, FatUtility::VAR_INT, 0);
        if (empty($shipmentTracking) || 1 > $langId) {		
			Message::addErrorMessage(Labels::getLabel('MSG_INVALID_REQUEST', $langId));
			FatUtility::dieWithError( Message::getHtml() );
        }

        $pluginKey = Plugin::getAttributesById($shipmentTracking, 'plugin_code');
        if (1 > Plugin::isActive($pluginKey)) {
			Message::addErrorMessage(Labels::getLabel('MSG_PLUGIN_NOT_ACTIVE', $langId));
			FatUtility::dieWithError( Message::getHtml() );
        }
        
        require_once CONF_PLUGIN_DIR . '/shipment-tracking/' . strtolower($pluginKey) . '/' . $pluginKey . '.php';

        $tracking = new $pluginKey($langId);
        $response = $tracking->getTrackingCouriers();
        return $response;
    }
	
}
