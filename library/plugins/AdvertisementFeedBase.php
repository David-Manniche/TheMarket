<?php
class AdvertisementFeedBase
{
    protected $envoirment;

    public function __construct()
    {
        $this->envoirment = FatApp::getConfig('CONF_TRANSACTION_MODE', FatUtility::VAR_BOOLEAN, false);
    }
    
    public function getSettings($column = '')
    {
        $class = $keyName = get_called_class();
        if (defined($class . '::KEY_NAME')) {
            $keyName = $class::KEY_NAME;
        }
        return PluginSetting::getConfDataByCode($keyName, $column);
    }

    public function getError()
    {
        return $this->error;
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
        $resultArr = json_decode($response, true);
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
            case 503:
                $errorMsg = Labels::getLabel("MSG_503:_SERVICE_UNAVAILABLE._HOPEFULLY_THEY'LL_BE_OK_SOON!", CommonHelper::getLangId());
                break;
            default:
                $errorMsg = empty($resultArr['error']['message']) ? curl_error($curl) : $resultArr['error']['message'];
                $replacements = ['{HTTPCODE}' => $httpCode, '{ERROR}' => $errorMsg];
                $message = Labels::getLabel("MSG_UNDOCUMENTED_ERROR_-_{HTTPCODE}_:_{ERROR}", CommonHelper::getLangId());
                $errorMsg = CommonHelper::replaceStringData($message, $replacements);
                break;
        }
        curl_close($curl);
        LibHelper::dieJsonError($errorMsg);
    }

    protected function getUserAccountDetail($key = '')
    {
        return User::getUserMeta(UserAuthentication::getLoggedUserId(), $key);
    }
}
