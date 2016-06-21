<?php
include("Database.php");
class TxtMsgContent{
	public $seminar = null;
	public $msg = null;

	/* 
	* purpose: get the event for the tourcode
	* function requires at least the tourcode and phone number to be passed in
	*/
	public function getEvent($options){
                if(null != $options){
                        if((isset($options['tourcode'])) && (isset($options['phone']))){
				$dba = new Database();
                                $dbInstance = $dba->getInstance('production', 'DBA');
                                $sql =<<<EOD
					SELECT 
					SeminarName, 
					SeminarTime,
					SeminarDate,
					SeminarCity,
					SeminarAddress
					FROM Seminars WHERE tourcode = '{$options['tourcode']}'
EOD;
                                $seminar = mssql_fetch_assoc(mssql_query($sql));
				$dba->closeInstance();
				//following line will setup the starttime
				$startTime = date("h:i" , strtotime('-30 minutes', strtotime($seminar['SeminarTime'])));
	
				$seminar['StartTime'] = $startTime;
				return $seminar;
                        }else{
                                return;
                        }
                }

	}
	
	public function CharLimitValidate($arr){
		foreach($arr as $key => &$val){
			switch($key){
				case "SpeakerName":
					$val = $this->CutString($val, 18);
					break;
				case "SeminarName":
					$val = $this->CutString($val, 50);
					break;
				case "SeminarTime":
					$val = date("gA", strtotime($val));
					break;
				case "StartTime":
					$val = date("h:i", strtotime($val));
					break;
				case "fname":
					$val = $this->CutString($val, 10);
					break;
				case "SeminarDate":
					$val = date("n/j", strtotime($val));
					break;
				case "SeminarCity":
					$val = $this->CutString($val, 25);
					break;
			}
		}
		return $arr;
	}
	private function CutString($str, $max){
		return substr($str, 0, $max);
	}
	/*
	* PURPOSE: to create content for the txt msg after making sure all the character limits are met
	*
	* $seminars array requires at least SeminarName, SeminarTime, SpeakerName, StartTime
	* $contentType is the key as to what message will be sent out. 
	* $options is ment so you can pass in useless information that u need in the msg
	*/
	//prev reminderweekday message
	//Reminder: your VIP reservation is scheduled today at {$seminar['SeminarTime']} at {$seminar['SeminarName']} 
	
	public function getContent($seminar, $contentType = "confirmation"){
		$seminar = $this->CharLimitValidate($seminar);
		switch($contentType){
			case "reminderWeekday":
				$this->msg =<<<EOD
Reminder your event reservation is scheduled for {$seminar['SeminarDate']} at {$seminar['SeminarTime']} at {$seminar['SeminarName']} {$seminar['SeminarAddress']}
EOD;
				break;
			case "reminderWeekend":
				$this->msg =<<<EOD
Reminder your event reservation is scheduled for {$seminar['SeminarDate']} at {$seminar['SeminarTime']} at {$seminar['SeminarName']} {$seminar['SeminarAddress']}
EOD;
				break;
			case "confirmation":
				$this->msg =<<<EOD
Your event reservation is confirmed for {$seminar['SeminarDate']} at {$seminar['SeminarTime']} at {$seminar['SeminarName']} at {$seminar['SeminarAddress']}
EOD;
				break;
				case "NAREI":
				$this->msg =<<<EOD
You are all signed up for the upcoming {$seminar['SeminarCity']} REIA event on {$seminar['SeminarDate']}. We look forward to seeing you there
EOD;
				break;
				case "onlineConf":
					$this->msg =<<<EOD
Your event reservation is confirmed for {$seminar['SeminarDate']} at {$seminar['SeminarTime']} at {$seminar['SeminarName']} {$seminar['SeminarAddress']}
EOD;
				break;
				case "doortodoorVideo":
					$this->msg =<<<EOD
Click on this link for a short video about Doug Clark and the event: clarkedu.com/live
EOD;
				break;
				case "reminderDBS":
					$this->msg =<<<EOD
Dean Graziosi is excited to take a picture with you {$seminar['SeminarDate']} on the red carpet at {$seminar['SeminarName']} at {$seminar['SeminarTime']}
EOD;
				break;
				case "resend":
					$this->msg =<<<EOD
Event Info: {$seminar['SeminarDate']} at {$seminar['SeminarTime']} {$seminar['SeminarName']} {$seminar['SeminarAddress']}
EOD;
				break;
				case "stockConf":
					$this->msg =<<<EOD
Congrats we have reserved your VIP seat to attend The Interactive Trader Stock Workshop on {$seminar['SeminarDate']} at {$seminar['SeminarTime']} at {$seminar['SeminarAddress']}
EOD;
				break;
				case "stockReminder":
					$this->msg =<<<EOD
Reminder your VIP seat to attend The Interactive Trader Stock Workshop starts tomorrow {$seminar['SeminarDate']} at {$seminar['SeminarTime']} at {$seminar['SeminarAddress']}
EOD;
				break;
				case "reConf":
					$this->msg =<<<EOD
Congrats we have reserved your VIP seat to attend the Real Estate Workshop on {$seminar['SeminarDate']} at {$seminar['SeminarTime']} at {$seminar['SeminarAddress']}
EOD;
				break;
				case "reReminder":
					$this->msg =<<<EOD
Reminder your VIP seat to attend the Real Estate Workshop starts tomorrow {$seminar['SeminarDate']} at {$seminar['SeminarTime']} at {$seminar['SeminarAddress']}
EOD;
				break;
				default:	
					$this->msg =<<<EOD
Your event reservation is confirmed for {$seminar['SeminarDate']} at {$seminar['SeminarTime']} at {$seminar['SeminarName']} at {$seminar['SeminarAddress']}
EOD;
					break;
				
		}
		return $this->msg;
	}
}

?>
