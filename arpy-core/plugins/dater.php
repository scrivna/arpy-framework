<?php
/*
handles all displaying of the time / date
*/
class Dater {

	private $timezone = 0;		// the number of hours difference between GMT and they're time zone
	private $timediff = 0;		// difference between GMT and the timezone they are using
	
	function &getInstance(){ // this implements the 'singleton' design pattern.
        static $instance;
        
        if (!isset($instance)) {
            $c = __CLASS__;
            $instance = new $c;
        }
        //$instance->set_timezone(0);
        return $instance;
    }
    
	function set_timezone($timezone = 0){
		
		$_this =& self::getInstance();
		
		$_this->timezone = $timezone;
		$_this->timediff = $timezone * (60 * 60);
	}
	
	function get_timezone(){
		
		$_this =& self::getInstance();
		
		return $_this->timezone = $timezone;
	}
	
	// this is so it will take into account timezone
	function date($format='U', $time=false){
		$_this =& self::getInstance();
		
		if ($time){
			$time = $time + $_this->timediff;
		} else {
			$time = self::time();
		}
		return date($format,$time);
	}
	
	function toGMT($time){
		$_this =& self::getInstance();
		return self::sanitize($time) + (-1 * $_this->timediff);
	}
	
	function fromGMT($gmt){
		$_this =& self::getInstance();
		return self::sanitize($gmt) + $_this->timediff;
	}
	
	// turn the tring into a UTS
	function sanitize($str){
		if (!is_numeric($str)){
			return strtotime($str);
		}
		return $str;
	}
	
	
	// return the current time according to the timezone
	function time(){
		$_this =& self::getInstance();
		//pr($_this);
		return time() + $_this->timediff;
	}
	
	
	function getTimeZones(){
		$data = array();
		$data["-12"] 	= '(GMT -12:00) Eniwetok, Kwajalein';
		$data["-11"] 	= '(GMT -11:00) Midway Island, Samoa';
		$data["-10"] 	= '(GMT -10:00) Hawaii';
		$data["-9"] 	= '(GMT -9:00) Alaska';
		$data["-8"] 	= '(GMT -8:00) Pacific Time (US &amp; Canada)';
		$data["-7"] 	= '(GMT -7:00) Mountain Time (US &amp; Canada)';
		$data["-6"] 	= '(GMT -6:00) Central Time (US &amp; Canada), Mexico City';
		$data["-5"] 	= '(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima';
		$data["-4"] 	= '(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz';
		$data["-3"] 	= '(GMT -3:30) Newfoundland';
		$data["-3"] 	= '(GMT -3:00) Brazil, Buenos Aires, Georgetown';
		$data["-2"] 	= '(GMT -2:00) Mid-Atlantic';
		$data["-1"] 	= '(GMT -1:00 hour) Azores, Cape Verde Islands';
		$data["0"] 		= '(GMT) Western Europe Time, London, Lisbon, Casablanca';
		$data["1"] 		= '(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris';
		$data["2"] 		= '(GMT +2:00) Kaliningrad, South Africa';
		$data["3"] 		= '(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg';
		$data["3.5"] 	= '(GMT +3:30) Tehran';
		$data["4"] 		= '(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi';
		$data["4.5"] 	= '(GMT +4:30) Kabul';
		$data["5"] 		= '(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent';
		$data["5.5"] 	= '(GMT +5:30) Bombay, Calcutta, Madras, New Delhi';
		$data["5.75"] 	= '(GMT +5:45) Kathmandu';
		$data["6"] 		= '(GMT +6:00) Almaty, Dhaka, Colombo';
		$data["7"] 		= '(GMT +7:00) Bangkok, Hanoi, Jakarta';
		$data["8"] 		= '(GMT +8:00) Beijing, Perth, Singapore, Hong Kong';
		$data["9"] 		= '(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk';
		$data["9.5"] 	= '(GMT +9:30) Adelaide, Darwin';
		$data["10"] 	= '(GMT +10:00) Eastern Australia, Guam, Vladivostok';
		$data["11"] 	= '(GMT +11:00) Magadan, Solomon Islands, New Caledonia';
		$data["12"] 	= '(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka';
		return $data;
	}
	
	// returns a fuzzy date / time relative to the time
	// eg echo fuzzy_time(mktime(12,17,58,11,1,2007),time(),2);
	// detail is 0 for not very detailed, 1 for quite detailed
	public function fuzzy($timestamp,$current_timestamp=''){
		if (empty($current_timestamp)) $current_timestamp = time();
		
		$_this =& self::getInstance();
		
		$timestamp 			= $_this->sanitize($timestamp);
		$current_timestamp 	= $_this->sanitize($current_timestamp);
		
		
		// turn the timestamp into my local time 
		$timestamp 			= $_this->date('U',$timestamp);
		$current_timestamp 	= $_this->date('U',$current_timestamp);
		
		// difference in seconds between the 2 times
		$second_diff = $timestamp - $current_timestamp;
		
		$today = mktime(0,0,0,date('m',$current_timestamp),date('d',$current_timestamp),date('Y',$current_timestamp));
		$tomorrow = mktime(0,0,0,date('m',$current_timestamp),date('d',$current_timestamp),date('Y',$current_timestamp))+86400;
		$yesterday = mktime(0,0,0,date('m',$current_timestamp),date('d',$current_timestamp),date('Y',$current_timestamp))-86400;
		$formatted = '';
		
		//echo '<br />Timestamp: '.date('d/m/Y H:i:s',$timestamp);
		//echo '<br />Current Time: '.date('d/m/Y H:i:s',$current_timestamp);
		//echo '<br />Time I want: '.$timestamp;
		//echo '<br />Today: '.date('d/m/y H:i:s',$today);
		//echo '<br />Yesterday: '.date('d/m/y H:i:s',$yesterday);
		//echo '<br />2moro: '.date('d/m/y H:i:s',$tomorrow).'<Br />';
		//echo '<br />Difference in seconds: '.$second_diff;
		
		$formatted = date('M jS Y',$timestamp);
		
		// detect what day it occurred on
		if ($timestamp>=$current_timestamp){
		
			// it's in the future
			// this week?
			if (date('W Y',$timestamp) == date('W Y',$current_timestamp)) $formatted = date('l',$timestamp);
			// tomorrow?
			if (between($timestamp,$tomorrow,$tomorrow+86400)) $formatted = 'Tomorrow';
			// today?
			if (between($timestamp,$today,$today+86400)){ $formatted = 'Today'; $is_today = true; }
			
		} else {
			
			// it's in the past
			// this week?
			if (date('W Y',$timestamp) == date('W Y',$current_timestamp)) $formatted = date('l',$timestamp);
			// yesterday?
			if (between($timestamp,$yesterday,$yesterday+86400)) $formatted = 'Yesterday';
			// today?
			if (between($timestamp,$today,$today+86400)) $formatted = 'Today';
		}
		
		// within 24 hours?
		if (abs($second_diff) <= 86400){
			if ($second_diff>0){
				// it's in the future
				if (between(abs($second_diff),0,120)) 		$formatted = 'in a moment';
				if (between(abs($second_diff),120,3600)) 	$formatted = round(abs($second_diff)/60).' minutes';
				if (abs($second_diff)>=3600) 				$formatted = round(abs($second_diff)/60/60).' hours';
			} else {
				// its in the past
				if (between(abs($second_diff),0,120)) 		$formatted = 'a moment ago';
				if (between(abs($second_diff),120,3600)) 	$formatted = round(abs($second_diff)/60).' minutes ago';
				if (abs($second_diff)>=3600) 				$formatted = round(abs($second_diff)/60/60).' hours ago';
				if (abs($second_diff)>=3000 && abs($second_diff)<7000) $formatted = 'an hour ago';
			}
		}
		
		return $formatted;
	}
}
?>