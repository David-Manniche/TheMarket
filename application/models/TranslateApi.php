<?php
class TranslateApi
{
    private $subscriptionKey;
    private $host;
    private $translatePath;
    private $fromLang;

    public function __construct($fromLang)
    {
        $this->subscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
        $this->host = 'https://api.cognitive.microsofttranslator.com';
        $this->translatePath = '/translate?api-version=3.0';
        $this->fromLang = $fromLang;
    }

    public function getTranslatedData($to, $requestBody)
    {
        //Remove This line
        return $requestBody;
        // ^^^^^^^^^^
        
        if (empty($to) || empty($requestBody)) {
            trigger_error(Labels::getLabel('MSG_INVALID_REQUEST', CommonHelper::getLangId()), E_USER_ERROR);
        }

        // if (empty($this->subscriptionKey)) {
        //     trigger_error(Labels::getLabel('MSG_INVALID_SUBSCRIPTION_KEY', CommonHelper::getLangId()), E_USER_ERROR);
        // }

        $content = LibHelper::convertToJson($requestBody, JSON_UNESCAPED_UNICODE);

        $curl_headers = array(
            'Content-type: application/json',
            'Content-length: ' . strlen($content) ,
            'Ocp-Apim-Subscription-Key: ' . $this->subscriptionKey ,
            'X-ClientTraceId: ' . $this->comCreateGuid()
        );
        $url = $this->host . $this->translatePath;

        //Language Translate From, Translate To
        $url .= "&to=" . $to . "&from=" . $this->fromLang;

        $ch = curl_init();
        $curl_content = ['content' => $content];
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_headers);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $reponse = json_decode($result, true);
        
        return $reponse;
        // Note: We convert result, which is JSON, to and from an object so we can pretty-print it.
        // We want to avoid escaping any Unicode characters that result contains. See:
        // http://php.net/manual/en/function.json-encode.php
        // return $json = json_encode(json_decode($result), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    private function comCreateGuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
