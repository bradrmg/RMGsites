<?php
include("Database.php");
class TxtBlastContent{
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
					SELECT SeminarName, 
					SeminarTime,
					SeminarDate
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
					$val = $this->CutString($val, 35);
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
				case "city":
					$val = $this->CutString($val, 10);
					break;
				case "SeminarDate":
					$val = date("n/j", strtotime($val));
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
	* $contentType is ment so we can send diffeernt msgs based on teh type like confirmation and reminder
	* $options is ment so you can pass in useless information that u need in the msg
	*/
	public function getContent($seminar, $contentType = "confirmation"){
		$seminar = $this->CharLimitValidate($seminar);
		switch($contentType){
			case "v1":
				$this->msg =<<<EOD
{$seminar['Fname']}, TV Star Scott Yancey is inviting you to his event in your area next week. RSVP at scottswealthevent.com or call back at this number. Includes 2 admission tickets, gifts, and the 1st 50 people get a tablet computer! Terms & conditions apply.Text STOP to stop
EOD;
				break;
			case "v2":
				$this->msg =<<<EOD
{$seminar['Fname']}, TV Star Scott Yancey is inviting you to his event in your area next week. RSVP at scottswealthevent.com or call back at this number. Includes 2 admission tickets, gifts, all attendees will receive a free digital camera! Terms & conditions apply.Text STOP to stop
EOD;
				break;
			case "reminderWeekend":
				$this->msg =<<<EOD
Reminder: your VIP reservation is scheduled tomorrow at {$seminar['SeminarTime']} at {$seminar['SeminarName']} Text STOP to stop
EOD;
				break;
			case "confirmation":
				$this->msg =<<<EOD
Your VIP event reservation is confirmed for {$seminar['SeminarDate']} at {$seminar['SeminarTime']} at {$seminar['SeminarName']}  Reply STOP to stop
EOD;
				break;
		}
		return $this->msg;
	}
}

?>