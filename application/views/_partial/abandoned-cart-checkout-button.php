<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$url = CommonHelper::generateFullUrl('GuestUser', 'redirectAbandonedCartUser', array($userId,0, true), CONF_WEBROOT_FRONTEND);
$str = '<tr><td style="padding-right: 25px;"></td><td><a href="'.$url.'" style="background: #ff3a59;border:none; border-radius: 4px; color: #fff; cursor: pointer;margin: 0;   width: auto; font-weight: normal; padding: 10px 20px; text-align:left;">Complete Checkout </a></td></tr>';
echo  $str;
            