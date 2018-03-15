<?php

namespace app\helpers;

class Date
{

    public static function normalize($date){
        if(!$date) {
            return $date;
        }
        return date('Y-m-d\TH:i:s.uP', strtotime($date));
    }

    public static function strtotime($date){
        return strtotime(str_replace('T', ' ', $date));
    }
}