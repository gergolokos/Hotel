<?php

function normal_date($date, $format = 'M d, Y h:i A')
{
    $d = date_create($date);
    return date_format($d, $format);
}

function current_date($format = 'Y-m-d H:i:s')
{
    return date($format);
}

function is_valid_date($date, $format= 'Y-m-d'){
    return $date == date($format, strtotime($date));
}

function add_days_to_date ($date, $days, $format= 'Y-m-d')
{
    return date($format, strtotime("+$days days", strtotime($date)));
}

function normal_to_db_date($date, $format = 'Y-m-d H:i:s')
{
    $d = date_create($date);
    return date_format($d, $format);
}
