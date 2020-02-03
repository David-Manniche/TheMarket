<?php
class SmsTemplate extends MyAppModel
{
    public const DB_TBL = 'tbl_sms_templates';
    public const DB_TBL_PREFIX = 'stpl_';

    public const LOGIN = 'LOGIN';

    private $stplCode;

    public function __construct($stplCode = '')
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'code', $stplCode);
        $this->stplCode = $stplCode;
    }

    public static function getSearchObject($langId = 0)
    {
        $langId =  FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }
        $srch = new SearchBase(static::DB_TBL);
        $srch->addOrder(static::DB_TBL_PREFIX . 'name', 'ASC');
        $srch->addMultipleFields(
            [
                static::DB_TBL_PREFIX . 'code',
                static::DB_TBL_PREFIX . 'lang_id',
                static::DB_TBL_PREFIX . 'name',
                static::DB_TBL_PREFIX . 'body',
                static::DB_TBL_PREFIX . 'replacements',
                static::DB_TBL_PREFIX . 'status',
            ]
        );
        if ($langId > 0) {
            $srch->addCondition(static::DB_TBL_PREFIX . 'lang_id', '=', $langId);
        }
        return $srch;
    }

    public static function getTpl($stpl_code, $langId = 0)
    {
        if (empty($stpl_code)) {
            return false;
        }

        $db = FatApp::getDb();

        $srch = static::getSearchObject($langId);
        $srch->addCondition(static::DB_TBL_PREFIX . 'code', 'LIKE', $stpl_code);
        if ($langId > 0) {
            $srch->addCondition(static::DB_TBL_PREFIX . 'lang_id', '=', $langId);
        }
        $srch->addOrder(static::DB_TBL_PREFIX . 'lang_id', 'ASC');
        $srch->addGroupby(static::DB_TBL_PREFIX . 'code');
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        if ($data = $db->fetch($srch->getResultSet())) {
            return $data;
        }
        return false;
    }

    private function formatData($data)
    {
        $langId = 0 < FatUtility::int($data['stpl_lang_id']) ? $data['stpl_lang_id'] : 0;
        if (1 > $langId) {
            $this->error = Labels::getLabel('MSG_INVALID_LANGUAGE', CommonHelper::getLangId());
            return false;
        }
        
        if (empty($data['stpl_body'])) {
            $this->error = Labels::getLabel('MSG_MESSAGE_BODY_IS_REQUIRED', CommonHelper::getLangId());
            return false;
        }

        return [
            self::DB_TBL_PREFIX . 'code' => !empty($data['stpl_code']) ? $data['stpl_code'] : '',
            self::DB_TBL_PREFIX . 'lang_id' => $langId,
            self::DB_TBL_PREFIX . 'name' => !empty($data['stpl_name']) ? $data['stpl_name'] : '',
            self::DB_TBL_PREFIX . 'body' => $data['stpl_body'],
        ];
    }
    public function addUpdateData($data)
    {
        $assignValues = $this->formatData($data);
        if (false === $assignValues) {
            return false;
        }

        if (!FatApp::getDb()->insertFromArray(static::DB_TBL, $assignValues, false, [], $assignValues)) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }
        return true;
    }

    private function updateStatus($status)
    {
        if (empty($this->stplCode)) {
            $this->error = Labels::getLabel('ERR_INVALID_REQUEST_USER_NOT_INITIALIZED', CommonHelper::getLangId());
            return false;
        }

        $db = FatApp::getDb();
        $updateData = [
            static::DB_TBL_PREFIX . 'status' => $status
        ];
        $condition = [
            'smt' => static::DB_TBL_PREFIX . 'code = ?',
            'vals' => [
                $this->stplCode
            ]
        ];
        if (!$db->updateFromArray(static::DB_TBL, $updateData, $condition)) {
            $this->error = $db->getError();
            return false;
        }
        return true;
    }

    public function makeActive()
    {
        return $this->updateStatus(applicationConstants::ACTIVE);
    }

    public function makeInActive()
    {
        return $this->updateStatus(applicationConstants::INACTIVE);
    }

    public static function formatBody($str, $replacements, $data)
    {
        $replacements = json_decode($replacements, true);
        $repVars = array_column($replacements, 'variable');
        $arr = [];
        if (empty($repVars)) {
            return $arr;
        }

        array_walk($data, function (&$value, &$key) use (&$arr, $repVars) {
            $key = strtoupper($key);
            if (in_array($key, $repVars)) {
                $arr[$key] = $value;
            }
        });

        return CommonHelper::replaceStringData($str, $arr);
    }
}
