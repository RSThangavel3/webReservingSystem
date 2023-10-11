<?php
require_once(dirname(__FILE__) . '/../config/config.php');
function arrayToSelect($inputName, $srcArray, $selectedIndex = "") 
{
    $temphtml = " <select class=\"form-select\" name=\"{$inputName}\">". PHP_EOL;
    foreach($srcArray as $key => $val) {
        if($key == $selectedIndex) {
            $selectedText = "selected";
        } else {
            $selectedText = "";
        }
        $temphtml .= "<option value=\"{$key}\"{$selectedText}>{$val}</option>" . PHP_EOL;
    }

    $temphtml .= "</select>" . PHP_EOL;

    return $temphtml;
}

function format_date($yyyymmdd) {
    $week = array('日','月','火','水','木','金','土');
     return date('n/j('.$week[date('w',strtotime($yyyymmdd))] .')', strtotime($yyyymmdd));
}

function format_time($time) {
    return substr($time, 0, -3);
}