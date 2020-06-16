<?php

class Address
{
    private const GOOGLE_GEOCODE_URL = 'https://maps.googleapis.com/maps/api/geocode/json?';
    private $langId;

    /**
    * __construct
    *
    * @return void
    */
    public function __contruct(int $langId = 0)
    {
        $this->langId = 0 < $langId ? $langId : CommonHelper::getLangId();
    }

    /**
    * getGeoData
    *
    * @param  string $lat
    * @param  string $lng
    * @param  string $countryCode
    * @param  string $stateCode
    * @param  string $zipCode
    * @return array
    */
    public function getGeoData(string $lat, string $lng, string $countryCode = '', string $stateCode = '', string $zipCode = '', $address = ''): array
    {
        if ((empty($lat) || empty($lng))) {
            $msg = Labels::getLabel('MSG_MSSING_REQUIRED_PARAMETERS', $this->langId);
            return static::formatOutput(false, $msg);
        }
        
        // google map geocode api url
        $apiKey = FatApp::getConfig('CONF_GOOGLEMAP_API_KEY', FatUtility::VAR_STRING, '');
        if (empty($apiKey)) {
            $msg = Labels::getLabel('MSG_GOOGLE_PLACES_API_KEY_NOT_DEFINED', $this->langId);
            return static::formatOutput(false, $msg);
        }

        $url = self::GOOGLE_GEOCODE_URL . 'latlng=' . $lat . ',' . $lng;

        $seperator = '+';
        if (!empty($address)) {
            $address = str_replace(' ', $seperator, $address);
        }

        $seperator = ',' . $seperator ;
        
        if (!empty($countryCode)) {
            $address .= (!empty($address)) ? $seperator : '';
            $address .= $countryCode;
        }

        if (!empty($stateCode)) {
            $address .= (!empty($address)) ? $seperator : '';
            $address .= $stateCode;
        }

        if (!empty($zipCode)) {
            $address .= (!empty($address)) ? $seperator : '';
            $address .= $stateCode;
        }

        if (!empty($address)) {
            $url .= '&address=' . $address;
        }
        
        $url .= '&key=' . $apiKey;
    
        // get the json response
        $resp = file_get_contents($url);
        
        // decode the json
        $resp = json_decode($resp, true);
        // response status will be 'OK', if able to geocode given address
        if ($resp['status'] == 'OK') {
            // get the important data
            $addressComponents = $resp['results'][0]['address_components'];
            if (!empty($addressComponents)) {
                $msg = Labels::getLabel('MSG_SUCCESS', $this->langId);
                $data = [
                    'lat' => $resp['results'][0]['geometry']['location']['lat'],
                    'lng' => $resp['results'][0]['geometry']['location']['lng'],
                    'formatted_address' => $resp['results'][0]['formatted_address']
                ];
                array_walk($addressComponents, function ($value, $key) use (&$data) {
                    switch ($value['types'][0]) {
                        case 'administrative_area_level_1':
                            $data['state_code'] = $value['short_name'];
                            $data['state'] = $value['long_name'];
                            break;
                        case 'country':
                            $data['country_code'] = $value['short_name'];
                            $data['country'] = $value['long_name'];
                            break;
                        case 'administrative_area_level_2':
                            $data['city'] = $value['long_name'];
                            break;
                        default:
                            $data[$value['types'][0]] = $value['long_name'];
                            break;
                    }
                });
                return static::formatOutput(true, $msg, $data);
            } else {
                $msg = Labels::getLabel('MSG_UNABLE_TO_RETRIVE_RESULT', $this->langId);
                return static::formatOutput(false, $msg);
            }
        }
        $msg = Labels::getLabel('MSG_UNABLE_LOCATE_ADDRESS_FOR_GIVEN_LAT_LNG', $this->langId);
        return static::formatOutput(false, $msg);
    }

    /**
     * formatOutput
     *
     * @param  bool $status
     * @param  string $msg
     * @param  array $data
     * @return array
     */
    public static function formatOutput(bool $status, string $msg, array $data = []): array
    {
        return [
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ];
    }
}
