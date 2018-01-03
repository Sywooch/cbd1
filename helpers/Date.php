<?php


namespace app\helpers;


class Date
{
	public static function normalize($date){
		// $date = (new \Datetime($date))->modify('+2 hours')->format('y-m-d H:i:s');
		return date('Y-m-d\TH:i:s' . '.0000', strtotime($date)) . '+02:00';
	}

	public static function strtotime($date){
	    return strtotime(str_replace('T', ' ', $date));
    }
}