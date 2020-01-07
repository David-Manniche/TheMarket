<?php
class AdvertisementFeedBaseController extends PluginBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    protected function redirectBack()
    {
        FatApp::redirectUser(CommonHelper::generateUrl('Advertisement'));
    }

    protected function doRequest($url, $method, $data)
    {
        if (empty($url) || empty($method)) {
            LibHelper::dieJsonError(Labels::getLabel('LBL_INVALID_REQUEST', CommonHelper::getLangId()));
        }

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
        switch ($method) {
            case "GET":
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                break;
            case "POST":
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE"); 
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                break;
        }
        $response = curl_exec($curl);
    
        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // Check the HTTP Status code
        switch ($httpCode) {
            case 200:
                return $response;
                break;
            case 404:
                $errorMsg = Labels::getLabel("MSG_404:_API_NOT_FOUND", CommonHelper::getLangId());
                break;
            case 500:
                $errorMsg = Labels::getLabel("MSG_500:_SERVERS_REPLIED_WITH_AN_ERROR.", CommonHelper::getLangId());
                break;
            case 502:
                $errorMsg = Labels::getLabel("MSG_502:_SERVERS_MAY_BE_DOWN_OR_BEING_UPGRADED._HOPEFULLY_THEY'LL_BE_OK_SOON!", CommonHelper::getLangId());
                break;
            case 503:
                $errorMsg = Labels::getLabel("MSG_503:_SERVICE_UNAVAILABLE._HOPEFULLY_THEY'LL_BE_OK_SOON!", CommonHelper::getLangId());
                break;
            default:
                $replacements = ['httpCode' => $httpCode, 'error' => curl_error($curl)];
                $message = Labels::getLabel("MSG_UNDOCUMENTED_ERROR:_{httpCode}_:_{error}", CommonHelper::getLangId());
                $errorMsg = CommonHelper::replaceStringData($str, $replacements);
                break;
        }
        curl_close($curl);
        LibHelper::dieJsonError($errorMsg);
    }

    protected function updateMerchantAccountDetail($detail = [])
    {
        if (!is_array($detail)) {
            FatUtility::dieJsonError(Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId));
        }
        $obj = new User(UserAuthentication::getLoggedUserId());
        foreach ($detail as $key => $value) {
            if (false === $obj->updateUserMeta($key, $value)) {
                Message::addErrorMessage($obj->getError());
                $this->redirectBack();
            }
        }
        Message::addMessage(Labels::getLabel("MSG_SUCCESSFULLY_UPDATED", $this->siteLangId));
        $this->redirectBack();
    }

    protected function getMerchantAccountDetail($key = '')
    {
        return User::getUserMeta(UserAuthentication::getLoggedUserId(), $key);
    }
}
