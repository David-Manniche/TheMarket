<?php

class TimeSlot extends MyAppModel
{
    public const DB_TBL = 'tbl_time_slots';
    public const DB_TBL_PREFIX = 'tslot_';
    
    public const DAY_ALL = 0;
    public const DAY_SUNDAY = 1;
    public const DAY_MONDAY = 2;
    public const DAY_TUESDAY = 3;
    public const DAY_WEDNESDAY = 4;
    public const DAY_THRUSDAY = 5;
    public const DAY_FRIDAY = 6;
    public const DAY_SATURDAY = 7;
    
    
    
    /**
     * __contruct
     *
     * @param  int $timeSlotId
     * @return void
     */
    public function __construct(int $timeSlotId = 0)
    {
        parent::__construct(self::DB_TBL, self::DB_TBL_PREFIX . 'id', $timeSlotId);
    }

    public static function getDaysArr(int $langId): array
    {     
        return [
            self::DAY_ALL => Labels::getLabel('LBL_ALL', $langId),
            self::DAY_SUNDAY => Labels::getLabel('LBL_Sunday', $langId),
            self::DAY_MONDAY => Labels::getLabel('LBL_Monday', $langId),
            self::DAY_TUESDAY => Labels::getLabel('LBL_Tuesday', $langId),
            self::DAY_WEDNESDAY => Labels::getLabel('LBL_Wednesday', $langId),
            self::DAY_THRUSDAY => Labels::getLabel('LBL_Thrusday', $langId),
            self::DAY_FRIDAY => Labels::getLabel('LBL_Friday', $langId),
            self::DAY_SATURDAY => Labels::getLabel('LBL_Saturday', $langId),
        ];
    }
    
    public static function getTimeSlotsArr(): array
    {
        $timeSlots = [];
        for($i = 0; $i<=24; $i++){
            if($i < 10){
                $timeSlots["0".$i.":00:00"] = $i.":00:00";
            }else{
                $timeSlots[$i.":00:00"] = $i.":00:00";   
            }            
        }
        return $timeSlots;
    }
    
    
    public function getTimeSlotByAddressId($addressId)
    {
        $addressId = FatUtility::int($addressId);
        $srch = new SearchBase(static::DB_TBL, 'ts');
        $srch->addCondition(self::tblFld('record_id'), '=', $addressId);
        $srch->addOrder(self::tblFld('day'), 'ASC');
        $rs = $srch->getResultSet();
        return  FatApp::getDb()->fetchAll($rs);
    }
    
}
