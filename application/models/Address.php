<?php

class Address extends MyAppModel
{
    public const DB_TBL = 'tbl_addresses';
    public const DB_TBL_PREFIX = 'addr_';

    public const TYPE_USER = 1;
    public const TYPE_SHOP_PICKUP = 2;
    public const TYPE_SHOP_REUTRN = 3;

    private const GOOGLE_GEOCODE_URL = 'https://maps.googleapis.com/maps/api/geocode/json?';
    private $langId;
    
    /**
     * __contruct
     *
     * @param  int $addressId
     * @param  int $langId
     * @return void
     */
    public function __construct(int $addressId = 0, int $langId = 0)
    {
        $this->langId = 0 < $langId ? $langId : CommonHelper::getLangId();
        parent::__construct(self::DB_TBL, self::DB_TBL_PREFIX . 'id', $addressId);
        $this->objMainTableRecord->setSensitiveFields([self::DB_TBL_PREFIX . 'id']);
    }
    
    /**
     * getTypeArr
     *
     * @param  int $langId
     * @return array
     */
    public static function getTypeArr(int $langId): array
    {
        return [
            self::TYPE_USER => Labels::getLabel('LBL_USER_ADDRESS', $langId),
            self::TYPE_SHOP_PICKUP => Labels::getLabel('LBL_SHOP_PICKUP_ADDRESS', $langId)
        ];
    }
    
    /**
     * getDefaultByRecordId
     *
     * @param  int $type
     * @param  int $recordId
     * @param  int $langId
     * @return array
     */
    public static function getDefaultByRecordId(int $type, int $recordId, int $langId = 0): array
    {
        $srch = new AddressSearch($langId);
        $srch->addCondition(self::tblFld('type'), '=', $type);
        $srch->addCondition(self::tblFld('record_id'), '=', $recordId);
        $srch->setPageSize(1);
        $srch->doNotCalculateRecords();
        $srch->addOrder(self::tblFld('is_default'), 'DESC');
        $rs = $srch->getResultSet();
        return (array) FatApp::getDb()->fetch($rs);
    }
    
    /**
     * getData
     *
     * @param  int $type
     * @param  int $recordId
     * @param  int $isDefault
     * @return array
     */
    public function getData(int $type, int $recordId, int $isDefault = 0) : array
    {
        $srch = new AddressSearch($this->langId);
        $srch->joinCountry();
        $srch->joinState();
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addMultipleFields(array('addr.*', 'state_code', 'country_code','IFNULL(country_name, country_code) as country_name', 'IFNULL(state_name, state_identifier) as state_name'));
        $srch->addCondition('country_active', '=', applicationConstants::ACTIVE);
        $srch->addCondition('state_active', '=', applicationConstants::ACTIVE);
        $srch->addCondition(self::tblFld('type'), '=', $type);
        $srch->addCondition(self::tblFld('record_id'), '=', $recordId);

        if (0 > $isDefault) {
            $srch->addCondition(self::tblFld('is_default'), '=', $isDefault);
        }
        $srch->addOrder(static::tblFld('is_default'), 'DESC');

        if (0 < $this->mainTableRecordId) {
            $srch->addCondition(self::tblFld('id'), '=', $this->mainTableRecordId);

            $rs = $srch->getResultSet();
            return (array) FatApp::getDb()->fetch($rs);
        }

        $rs = $srch->getResultSet();
        return FatApp::getDb()->fetchAll($rs);
    }
    
    /**
     * deleteByRecordId
     *
     * @param  int $type
     * @param  int $recordId
     * @return bool
     */
    public function deleteByRecordId(int $type, int $recordId): bool
    {
        $db = FatApp::getDb();

        if (!$db->deleteRecords(self::DB_TBL, array('smt' => 'addr_type = ? and addr_record_id = ?', 'vals' => array($type, $recordId)))) {
            $this->error = $db->getError();
            return false;
        }
        return true;
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
