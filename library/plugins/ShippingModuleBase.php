<?php
class ShippingModuleBase extends pluginBase
{
    public function getSystemOrder(string $orderId, int $langId, int $opId)
    {
        $orderObj = new Orders();
        $orderDetail = $orderObj->getOrder($orderId, $langId, $opId);
        if (false == $orderDetail) {
            $this->error = $orderObj->getError();
            return false;
        }
        return $orderDetail;
    }
    
}
