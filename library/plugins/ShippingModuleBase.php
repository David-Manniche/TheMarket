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
    
    public function convertWeightInOunce($productWeight, $productWeightClass)
    {
        $coversionRate = 1;
        switch ($productWeightClass) {
            case "KG":
                $coversionRate = "35.274";
                break;
            case "GM":
                $coversionRate = "0.035274";
                break;
            case "PN":
                $coversionRate = "16";
                break;
            case "OU":
                $coversionRate = "1";
                break;
            case "Ltr":
                $coversionRate = "33.814";
                break;
            case "Ml":
                $coversionRate = "0.033814";
                break;
        }

        return $productWeight * $coversionRate;
    }

    public function convertLengthInCenti($productWeight, $productWeightClass)
    {
        $coversionRate = 1;
        switch ($productWeightClass) {
            case "IN":
                $coversionRate = "2.54";
                break;
            case "MM":
                $coversionRate = "0.1";
                break;
            case "CM":
                $coversionRate = "1";
                break;
        }

        return $productWeight * $coversionRate;
    }
}
