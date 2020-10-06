<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$data = array(
    'slotDays' => $slotDays,
    'activeDate' => $activeDate,
    'timeSlots' => $timeSlots,
    'selectedDate' => strtotime($selectedDate),
    'level' => $level,
    'selectedSlot' => $selectedSlot,
);

if (empty($timeSlots)) {
    $status = applicationConstants::OFF;
}
